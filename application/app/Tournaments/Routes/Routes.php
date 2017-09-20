<?php

use Mira\Route;
use Mira\Render\Render;

use App\Tournaments\Models\Tournaments;

use App\League\Models\TournamentPokemon;
use App\League\Models\Teams;
use App\League\Models\Pokemon;
use App\League\Models\Leagues;
use App\League\Models\Requests;

use Middleware\Authentication;

// Routes Here

$currentUser = (new Authentication)->getCurrentUser();

Route::get('tournaments/team/{slug}', function($leagueSlug) use ($currentUser) {

    $league = (new Leagues)->find("slug = '$leagueSlug'");

    $tournaments = (new Leagues)->getTournaments();

    $teams = (new Teams)->filter("owner = '$currentUser->id' AND league IS NULL");

    $pokemon = new Pokemon;

    Render::view('Tournaments.register', [
        'currentUser' => $currentUser,
        'teams' => $teams,
        'tournaments' => $tournaments,
        'league' => $league,
        'pokemon' => $pokemon,
    ]);
});

Route::post('tournaments/team/{slug}/', function($leagueSlug) use ($currentUser) {
    $league = (new Leagues)->find("slug = '$leagueSlug'");

    $teamId = $_POST['team'];
    $team = (new Teams)->find($teamId);

    $pokemon = (new Pokemon)->all();
    $pokemonList = [];

    foreach ($pokemon as $pokemonInList){
        $pokemonList[] = $pokemonInList->pokemonName;
    }
    

    $tournamentPokemon = new TournamentPokemon;

    foreach($_POST['pokemon'] as $pokemon) {
        if ($pokemon) {
            if (!in_array($pokemon, $pokemonList)) {
                // not in list
                Render::redirect("/tournaments/team/$league->slug/?error=Could Not Find: $pokemon. Please choose a Pokemon from the provided dropdown.");
                return;
            }
        }
    }
    
    $tournamentPokemon->delete("team = '$team->id'");
    foreach($_POST['pokemon'] as $pokecheck) {
        if ($pokecheck) {
            $tournamentPokemon->team = $team->id;
            $tournamentPokemon->pokemon = (new Pokemon)->find("pokemonName = '$pokecheck'")->id;
            $tournamentPokemon->save();
        }
    }

    $requests = new Requests;

    $requests->team = $team->id;
    $requests->owner = $currentUser->id;
    $requests->league = $league->id;
    $requests->save();

    Render::redirect("/league/$league->slug/join/");
});

Route::get('tournaments/', function() use ($currentUser) {

    $tournaments = (new Leagues);

    Render::view('Tournaments.discover', [
        'currentUser' => $currentUser,
        'tournaments' => $tournaments,
    ]);
});
