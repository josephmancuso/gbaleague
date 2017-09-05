<?php

use Mira\Route;
use Mira\Render\Render;

use App\League\Models\Teams;
use App\League\Models\DraftedPokemon;
use App\League\Models\Leagues;

use Middleware\Upload;

// Routes Here

$currentUser = (new Middleware\Authentication)->getCurrentUser();

Route::get('team/create/', function() use ($currentUser) {

    Render::view('Teams.create', [
        'currentUser' => $currentUser
    ]);
});

Route::post('team/create/', function() use ($currentUser) {

    $team = new Teams;
    

    $team->name = $_POST['name'];
    $team->owner = $currentUser->id;
    $team->picture = Upload::file($_FILES['logo']);

    $team->save();
    if ($_POST['next']) {
        Render::redirect($_POST['next']);
        return;
    }

    Render::view('Teams.create');
});

Route::post('team/remove/{draftedPokemonId}/{leagueId}/', function($draftPokemonId, $leagueId) use ($currentUser) {

    $league = (new Leagues)->find($leagueId);

    if ($currentUser->id == $league->owner) {
        (new DraftedPokemon)->delete($draftPokemonId);
    }

    Render::redirect("/league/$league->slug/teams/");
});

Route::post('team/points/{teamId}/{leagueId}/', function($teamId, $leagueId) use ($currentUser) {

    $league = (new Leagues)->find($leagueId);

    if ($currentUser->id == $league->owner && $_POST['points']) {

        $team = (new Teams)->find($teamId);

        $team->points += $_POST['points'];

        $team->save();
    }

    Render::redirect("/league/$league->slug/teams/");
});




