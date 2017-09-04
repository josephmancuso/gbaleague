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

use Middleware\Authentication;

// Routes Here

$currentUser = (new Authentication)->getCurrentUser();

Route::get('discover/', function() use ($currentUser){
    $leagues = new Leagues();

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
    ]);

    die();
});

Route::post('league/{leagueId}/team/remove/', function($leagueId){
    $teamId = $_POST['team'];

    $team = (new Teams)->find($teamId);

    $team->league = 'NULL';

    $team->save();
    $league = (new Leagues)->find($leagueId);

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

    Render::redirect("/league/$league->slug/draft/");
});


Route::get('league/{slug}/draft/', function($slug) use ($currentUser) {
    $leagues = new Leagues;
    $teams = new Teams;

    $league = $leagues->find("slug = '$slug'");
    $teams = $teams->filter("league = '$league->id'");
    $draftedPokemon = (new DraftedPokemon)->filter("league = '$league->id'");

    $usersTeam = (new Teams)->find("league = '$league->id' AND owner = '$currentUser->id' ");
    
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
        'currentUser' => (new Authentication)->getCurrentUser(),
        'currentDrafter' => $league->current()
    ]);

    die();
});

Route::post('league/{leagueId}/skip/', function($leagueId){
    $league = (new Leagues)->find($leagueId);

    $currentDrafter = $league->current()->id;

    $draftOrder = explode(',', $league->draftorder);
    $i = 0;

    foreach($draftOrder as $draftUser) {

        if ($league->ordering == 0){

            if ($draftUser == $currentDrafter){
                
                if ($draftOrder[$i+1]) {
                    $nextUser = $draftOrder[$i+1];
                    break;
                } else {
                    $nextUser = $draftOrder[$i];
                    $order = 1;
                    break;
                }
            }

        } else {
            // order = 1
            if ($draftUser == $currentDrafter){
                
                if ($draftOrder[$i-1]) {
                    $nextUser = $draftOrder[$i-1];
                    break;
                } else {
                    $nextUser = $draftOrder[$i];
                    $order = 0;
                    break;
                }
            }
            
        }
        
        $i++;
    }
    $league->current = $nextUser;
    if (isset($order)) {
        $league->ordering = $order;
        $league->round++;
    }
    $league->save();
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

    // current user 11
    $league = (new Leagues)->find($leagueId);

    $currentDrafter = $league->current()->id;

    $draftOrder = explode(',', $league->draftorder);
    $i = 0;

    foreach($draftOrder as $draftUser) {

        if ($league->ordering == 0){

            if ($draftUser == $currentDrafter){
                
                if ($draftOrder[$i+1]) {
                    echo $nextUser = $draftOrder[$i+1];
                    break;
                } else {
                    echo $nextUser = $draftOrder[$i];
                    echo $order = 1;
                    break;
                }
            }

        } else {
            // order = 1
            if ($draftUser == $currentDrafter){
                
                if ($draftOrder[$i-1]) {
                    echo $nextUser = $draftOrder[$i-1];
                    break;
                } else {
                    echo $nextUser = $draftOrder[$i];
                    echo $order = 0;
                    break;
                }
            }
            
        }
        
        $i++;
    }


    $teams = (new Teams)->find("league = '$leagueId' AND owner = '$currentUser->id'");

    $pokemon = (new Pokemon)->find($pokemonId);

    

    $draftedPokemon = new DraftedPokemon;

    if ($league->current == $currentUser->id) {
        $draftedPokemon->team = $teams->id;
        $draftedPokemon->pokemon = $pokemonId;
        $draftedPokemon->league = $leagueId;
        $draftedPokemon->save();

        $teams->points = $teams->points - $pokemon->points;
        $teams->save();

        $league->current = $nextUser;
        if (isset($order)) {
            $league->ordering = $order;
            $league->round++;
        }
        $league->save();
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
        'teams' => new Teams
    ]);

    die();
});


Route::post('league/{slug}/requests/accept/{requestId}/{teamId}/', function($slug, $requestId, $teamId){
    $league = (new Leagues)->find("slug = '$slug' ");

    $teams = (new Teams)->find($teamId);
    $teams->league = $league->id;
    $teams->points = 1000;
    $teams->save();

    if ($league->draftorder) {
        $league->draftorder = "$league->draftorder,$teams->owner";
        $league->save();
    } else {
        $league->draftorder = "$teams->owner";
        $league->save();
    }
    
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
