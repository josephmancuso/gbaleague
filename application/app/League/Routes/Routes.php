<?php

use Mira\Route;
use Mira\Render\Render;

use App\League\Models\Leagues;
use App\League\Models\Teams;
use App\League\Models\DraftedPokemon;
use App\League\Models\Pokemon;
use App\League\Models\Requests;
use App\League\Models\Schedule;
use App\League\Models\Trading;
use App\League\Models\TradingOffers;
use App\League\Models\Pull;
use App\League\Models\TournamentPokemon;

use Middleware\Authentication;
use Middleware\Slack;

// Routes Here

$currentUser = (new Authentication)->getCurrentUser();

Route::get('discover/', function() use ($currentUser){

    Render::view('League.discover', [
        'leagues' => new Leagues,
        'currentUser' => $currentUser
    ]);
});


Route::get('league/{slug}/teams/', function($slug) use ($currentUser){
    $leagues = new Leagues;
    $teams = new Teams;

    $league = $leagues->find("slug = '$slug'");
    $teams = $teams->filter("league = '$league->id'");

    if ($currentUser->id == $league->owner) {
        $isHost = true;
    }

    $usersTeam = (new Teams)->find("league = '$league->id' AND owner = '$currentUser->id'");

    Render::view('League.league-teams', [
        'league' => $league,
        'teams' => $teams,
        'isHost' => $isHost,
        'currentUser' => $currentUser,
        'usersTeam' => $usersTeam,
        'teamModel' => new Teams,
        'draftedPokemon' => new DraftedPokemon,
        'pokemonList' => new Pokemon,
        'tournamentPokemon' => new TournamentPokemon,
    ]);

    die();
});

Route::post('league/{leagueId}/team/remove/', function($leagueId){
    $league = (new Leagues)->find($leagueId);

    $teamId = $_POST['team'];

    $team = (new Teams)->find($teamId);

    if ($league->status == 1 && $league->current == $team->owner) {
        $league->nextDraftUser();
    }

    $team->league = 'NULL';

    $team->save(); 
    

    if ($league->slackwebhook) {
        $slack = new Slack($league);

        $slack->client()->send($team->name. " has been removed from the league");
    }

    $draftorder = '';
    $allTeams = (new Teams)->filter(" league = '$leagueId' ");
    foreach($allTeams as $team) {
        
        $draftorder .= ','.$team->owner;
    }

    $draftorder = ltrim($draftorder, ',');

    $league = (new Leagues)->find($leagueId);
    $league->draftorder = $draftorder;
    $league->save();

    Render::redirect("/league/$league->slug/teams/"); 
});

Route::get('league/{slug}/join/', function($slug) use ($currentUser) {

    $leagues = new Leagues;
    $teams = new Teams;

    $league = $leagues->find("slug = '$slug'");
    $teams = $teams->filter("owner = '$currentUser->id' AND league IS NULL");

    $userRequests = (new Requests)->filter("league = '$league->id' AND owner = '$currentUser->id'");

    if ($currentUser->id == $league->owner) {
        $isHost = true;
    }

    if ((new Teams)->find("owner = '$currentUser->id' AND league = '$league->id' ")->id) {
        $isLeagueMember = true;
    }

    Render::view('League.league-join', [
        'league' => $league,
        'teams' => $teams,
        'isHost' => $isHost,
        'isLeagueMember' => $isLeagueMember,
        'currentUser' => $currentUser,
        'userRequests' => $userRequests
    ]);

    die();
});


Route::post('league/{leagueId}/status/', function($leagueId) use ($currentUser) {

    $league = (new Leagues)->find($leagueId);

    $league->status = $_POST['status'];

    if ($_POST['status'] == 1) {
        $league->current = explode(',', $league->draftorder)[0];
    }
    $league->save();

    if ($league->slackwebhook) {
        $slack = new Slack($league);

        if ($league->status == 2) {
            $slack->client()->send("Drafting has closed!");
        } else {
            $slack->client()->send("Drafting has opened!");
        }
        
    }

    Render::redirect("/league/$league->slug/draft/");
});


Route::get('league/{slug}/draft/', function($slug) use ($currentUser) {
    $leagues = new Leagues;
    $teams = new Teams;

    $league = $leagues->find("slug = '$slug'");
    $teams = $teams->filter("league = '$league->id'");
    $draftedPokemon = (new DraftedPokemon)->filter("league = '$league->id'");

    $usersTeam = (new Teams)->find("league = '$league->id' AND owner = '$currentUser->id' ");

    $draftersTeam = (new Teams)->find("league = '$league->id' AND owner = '$league->current' ");
    
    $draftedPokemon = (new DraftedPokemon)->filter("league = '$league->id' AND queue IS NULL ");

    $listOfDraftedPokemon = [];
    foreach ($draftedPokemon as $pokemon) {
        $listOfDraftedPokemon[] = $pokemon->pokemon;
    }

    $queuedPokemon = (new draftedPokemon)->filter("league = '$league->id' AND team = '$usersTeam->id' AND queue IS NOT NULL");

    $listOfQueuedPokemon = [];
    foreach ($queuedPokemon as $pokemon) {
        $listOfQueuedPokemon[] = $pokemon->queue;
    }

    if ($currentUser->id == $league->owner) {
        $isHost = true;
    }

    Render::view('League.league-draft', [
        'pokemonList' => new Pokemon,
        'teams' => new Teams,
        'league' => $league,
        'isHost' => $isHost,
        'listOfDraftedPokemon' => $listOfDraftedPokemon,
        'listOfQueuedPokemon' => $listOfQueuedPokemon,
        'user' => (new Authentication)->getCurrentUser(),
        'usersTeam' => $usersTeam,
        'draftersTeam' => $draftersTeam,
        'queuedPokemon' => (new DraftedPokemon)->filter("team = '$draftersTeam->id' AND queue IS NOT NULL"),
        'currentUser' => (new Authentication)->getCurrentUser(),
        'currentDrafter' => $league->current()
    ]);

    die();
});

Route::post('league/{leagueId}/skip/', function($leagueId){
    $league = (new Leagues)->find($leagueId);

    if ($league->slackwebhook) {
        $slack = new Slack($league);

        $slack->client()->send($league->current()->username. " has just been skipped!");
    }

    $league->nextDrafter();

    if ($league->slackwebhook) {
         $slack->client()->send($league->current()->username. " is now drafting");
    }

    Render::redirect("/league/$league->slug/draft/");
});

### DRAFT

Route::post('league/queue/pokemon/{pokemonId}/{leagueId}/', function($pokemonId, $leagueId){

    $league = (new Leagues)->find($leagueId);
    $currentUser = (new Authentication)->getCurrentUser();
    $teams = (new Teams)->find("league = '$leagueId' AND owner = '$currentUser->id'");

    if ($teams->id) {
        $draftedPokemon = new DraftedPokemon;

        $draftedPokemon->team = $teams->id;
        $draftedPokemon->queue = $pokemonId;
        $draftedPokemon->league = $leagueId;
        $draftedPokemon->save();
    }
    
    Render::redirect("/league/$league->slug/draft/");
});

Route::post('league/unqueue/pokemon/{pokemonId}/{leagueId}/', function($pokemonId, $leagueId){
    $league = (new Leagues)->find($leagueId);
    $currentUser = (new Authentication)->getCurrentUser();
    $teams = (new Teams)->find("league = '$leagueId' AND owner = '$currentUser->id'");

    if ($teams->id) {
        $draftedPokemon = new DraftedPokemon;

        $queue = $draftedPokemon->find("league = '$leagueId' AND team = '$teams->id' AND queue = '$pokemonId'");

        $queue->queue = 'NULL';
        $queue->save();
    }
    
    Render::redirect("/league/$league->slug/draft/");
});

Route::post('league/draft/pokemon/{pokemonId}/{leagueId}/', function($pokemonId, $leagueId){

    $currentUser = (new Authentication)->getCurrentUser();

    $league = (new Leagues)->find($leagueId);

    if ($league->current == $currentUser->id || $currentUser->id == $league->owner) {
        $league->draftPokemon($pokemonId);

        $pokemon = (new Pokemon)->find($pokemonId);

        if ($league->slackwebhook) {
            $slack = new Slack($league);

            $slack->client()->send($league->current()->username. " has just drafted Tier ".$pokemon->tier.' '.$pokemon->pokemonName. " for ".$pokemon->points." points");
        }

        $league->nextDrafter();

        
        if ($league->slackwebhook) {
            $slack->client()->send('Now Drafting: '.$league->current()->username);
        }
        
        
    }
    
    Render::redirect("/league/$league->slug/draft/");
});


Route::get('league/{slug}/requests/', function($slug){
    $currentUser = (new Authentication)->getCurrentUser();
    $league = (new Leagues)->find("slug = '$slug' ");
    $requests = (new Requests)->filter("league = '$league->id'");

    $currentUser = (new Authentication)->getCurrentUser();

    if ($currentUser->id == $league->owner) {
        $isHost = true;
    } 

    Render::view('League.league-requests', [
        'requests' => $requests,
        'league' => $league,
        'isHost' => $isHost,
        'currentUser' => $currentUser,
        'teams' => new Teams,
        'tournamentPokemon' => new tournamentPokemon,
        'pokemonModel' => new Pokemon,
    ]);

    die();
});


Route::post('league/{slug}/requests/accept/{requestId}/{teamId}/', function($slug, $requestId, $teamId){
    $league = (new Leagues)->find("slug = '$slug' ");

    $teams = (new Teams)->find($teamId);
    $teams->league = $league->id;
    $teams->points = 1000;
    $teams->save();

    if ($league->slackwebhook) {
        $slack = new Slack($league);

        $slack->client()->send("$teams->name owned by ". $teams->owner()->username." has just joined the league");
    }

    $draftorder = '';
    $allTeams = (new Teams)->filter(" league = '$league->id' ");

    foreach($allTeams as $team) {
        $draftorder .= ','.$team->owner;
    }

    $draftorder = ltrim($draftorder, ',');

    $league->draftorder = $draftorder;
    $league->save();
    
    
    $requests = (new Requests)->find($requestId);
    $requests->delete($requestId);

    Render::redirect("/league/$slug/requests/");
});

Route::post('league/{slug}/requests/decline/{requestId}/', function($slug, $requestId){
    $league = (new Leagues)->find("slug = '$slug' ");

    $requests = (new Requests)->find($requestId);
    $requests->delete($requestId);

    Render::redirect("/league/$slug/requests/");
});

Route::post('league/{slug}/join/', function($slug){
    $currentUser = (new Authentication)->getCurrentUser();

    $league = (new Leagues)->find("slug = '$slug' ");
    $requests = new Requests;

    $requests->team = $_POST['team'];
    $requests->owner = $currentUser->id;
    $requests->league = $league->id;

    $requests->save();

    if ($league->slackwebhook) {
        $slack = new Slack($league);

        $slack->client()->send($requests->owner()->username." has requested to join with the ". $requests->team()->name);
    }

    Render::redirect("/league/$slug/join/");
});

Route::get('league/{slug}/schedule/', function($slug) use ($currentUser) {
    $league = (new Leagues)->find("slug = '$slug' ");

    $teams = (new Teams)->filter("league = '$league->id'");

    if ($currentUser->id == $league->owner) {
        $isHost = true;
    } 

    Render::view("League.league-schedule", [
        'league' => $league,
        'teams' => $teams,
        'isHost' => $isHost,
        'currentUser' => $currentUser,
        'teamsModel' => new Teams,
        'schedules' => (new Schedule)->filter("league = '$league->id' ORDER BY date ASC"),
    ]);
    die();
});

Route::post('league/{slug}/schedule/delete/{id}/', function($slug, $id){
    $league = (new Leagues)->find("slug = '$slug' ");

    $schedule = new Schedule;

    $schedule->delete($id);

    Render::redirect("/league/$slug/schedule/");
});

Route::post('league/{slug}/schedule/', function($slug){
    $league = (new Leagues)->find("slug = '$slug' ");

    $schedule = new Schedule;
    
    $schedule->league = $league->id;
    $schedule->team1 = $_POST['team1'];
    $schedule->team2 = $_POST['team2'];
    $schedule->date = date('Y-m-d', strtotime($_POST['date']));
    $schedule->save();

    $team1 = (new Teams)->find($_POST['team1']);

    $team2 = (new Teams)->find($_POST['team2']);


    if ($league->slackwebhook) {
        $slack = new Slack($league);

        $slack->client()->send("A match has been created for $team1->name and $team2->name on ".date('l F jS Y', strtotime($_POST['date'])));
    }
    

    Render::redirect("/league/$slug/schedule/");
});

Route::post('league/{leagueId}/tradeoffer/', function($leagueId){

    $league = (new Leagues)->find($leagueId);

    if ($_POST['choice'] == "Decline") {
        $tradingOffer = new TradingOffers;
        $tradeOfferId = $_POST['tradingoffer'];
        $tradingOffer->delete($tradeOfferId);
        Render::redirect("/league/$league->slug/trade/");
    }

    // make the trade
    $oldteam = $_POST['oldteam'];
    $currentUser = (new Authentication)->getCurrentUser();
    $usersTeam = (new Teams)->find("league = '$leagueId' AND owner = '$currentUser->id'");

    $pokemon = $_POST['pokemon'];
    $draftedPokemon = (new DraftedPokemon)->find("team = '$oldteam' AND league = '$leagueId' AND pokemon = '$pokemon'");

    // accepted the trade, put the "pokemon" on my team
    $draftedPokemon->team = $usersTeam->id;
    $draftedPokemon->save();

    // put the pokemon on their team
    $pokemon = $_POST['pokemontrading'];
    $draftedPokemon = (new DraftedPokemon)->find("team = '$usersTeam->id' AND league = '$leagueId' AND pokemon = '$pokemon'");

    $draftedPokemon->team = $oldteam;
    $draftedPokemon->save();

    $tradingOffer = new TradingOffers;
    $tradeOfferId = $_POST['tradingoffer'];
    $tradingOffer->delete($tradeOfferId);

    // delete the trade
    $trade = $_POST['trade'];
    $trading = new Trading;
    $trading->delete($trade);

    Render::redirect("/league/$league->slug/trade/");
    die();
});

Route::get('league/{slug}/trade/', function($slug) use ($currentUser){
    $league = (new Leagues)->find("slug = '$slug'");

    $trading = new Trading;

    $tradingPokemon = $trading->filter("league = '$league->id'");

    $usersTeam = (new Teams)->find("league = '$league->id' AND owner = '$currentUser->id'");

    if ($currentUser->id == $league->owner) {
        $isHost = true;
    } 

    Render::view('League.league-trade', [
        'league' => (new Leagues)->find("slug = '$slug'"),
        'isHost' => $isHost,
        'currentUser' => $currentUser,
        'usersTeam' => $usersTeam,
        'tradingPokemon' => $tradingPokemon,
        'teamModel' => new Teams,
        'pokemonModel' => new Pokemon,
        'tradingOffers' => new TradingOffers,
        'tradingModel' => new Trading,
        'usersPokemon' => (new DraftedPokemon)->filter("league = '$league->id' AND team = '$usersTeam->id'")
    ]);

    die();
});

Route::post('league/{leagueId}/trade/', function($leagueId){
    $trading = new Trading;
    $currentUser = (new Authentication)->getCurrentUser();

    $usersTeam = (new Teams)->find("league = '$leagueId' AND owner = '$currentUser->id'");
    $trading->league = $leagueId;
    $trading->tradingteam = $usersTeam->id;
    $trading->pokemon = $_POST['pokemon'];
    $trading->save();

    $league = (new Leagues)->find($leagueId);

    if ($league->slackwebhook) {
        $slack = new Slack($league);

        $pokemon = (new Pokemon)->find($_POST['pokemon']);

        $slack->client()->send("$pokemon->pokemonName has been put up for trade by the $usersTeam->name");
    }
    
    Render::redirect("/league/$league->slug/trade/");
});

Route::post('league/{leagueId}/offer/', function($leagueId){
    $currentUser = (new Authentication)->getCurrentUser();
    $usersTeam = (new Teams)->find("league = '$leagueId' AND owner = '$currentUser->id'");

    $tradingOffer = new TradingOffers;
    $tradingOffer->league = $leagueId;
    $tradingOffer->trade = $_POST['trade'];
    $tradingOffer->offer = $_POST['pokemon'];
    $tradingOffer->team = $usersTeam->id;
    $tradingOffer->save();

    $league = (new Leagues)->find($leagueId);
    
    Render::redirect("/league/$league->slug/trade/");
});


Route::get('league/{slug}/chat/', function($slug) use($currentUser) {

    $league = (new Leagues)->find("slug = '$slug'");

    if ($currentUser->id == $league->owner) {
        $isHost = true;
    }
    
    Render::view('League.league-chat', [
        'league' => $league,
        'isHost' => $isHost,
        'currentUser' => (new Authentication)->getCurrentUser()
    ]);
});

Route::get('league/{slug}/', function($slug) use ($currentUser) {
    $league = (new Leagues)->find("slug = '$slug'");

    if ($currentUser->id == $league->owner) {
        $isHost = true;
    }
    
    Render::view('League.league', [
        'league' => $league,
        'isHost' => $isHost,
        'currentUser' => (new Authentication)->getCurrentUser()
    ]);
});

Route::get('testing/', function($slug){
    
    $pull = (new Pull)->all();

    foreach ($pull as $pulledPokemon){
        $pulledPokemon->name;

        $newPokemon = (new Pokemon)->find("pokemonName = '$pulledPokemon->name'");

        $newPokemon
        ->points = $pulledPokemon->type;

        $newPokemon->save();
    }
    
});
