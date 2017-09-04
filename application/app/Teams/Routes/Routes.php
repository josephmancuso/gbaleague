<?php

use Mira\Route;
use Mira\Render\Render;

use App\League\Models\Teams;

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


