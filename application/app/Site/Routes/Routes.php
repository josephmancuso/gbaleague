<?php

use Mira\Route;
use Mira\Render\Render;
use Mira\Mail\Mail;

use App\League\Models\Leagues;
use App\League\Models\Accounts;

use Cocur\Slugify\Slugify;

use Middleware\Authentication;

// Routes Here

$currentUser = (new Authentication)->getCurrentUser();

Route::get('home/', function() use ($currentUser){
    
    Render::view('Site.index', [
        'currentUser' => $currentUser
    ]);
});

Route::get('league/create/', function() use ($currentUser) {

    Render::view('Site.league-create', [
        'currentUser' => $currentUser
    ]);
});

Route::get('register/', function() use ($currentUser){
    Render::view('Site.account-create', [
        'currentUser' => $currentUser
    ]);
});

Route::post('register/', function(){

    $accounts = new Accounts;

    // check if username exists
    $username = $_POST['username'];
    if ($accounts->find("username = '$username'")->id) {
        Render::redirect("/register/?error=username $username already exists");
        return;
    }

    // password validation
    if ($_POST['password'] != $_POST['confirmpassword']) {
        Render::redirect("/register/?error=Passwords do not match");
        return;
    }

    $_POST['dos'] = time();

    $password = $_POST['password'];

    // hashed password
    $_POST['password'] = sha1($_POST['password']);

    $accounts->exclude('confirmpassword')->insertFromPost();

    $login = (new Authentication)->login($_POST['username'], $password);

    if (!$login) {
        Render::redirect("/register/?error=Could not login");
        return;
    }

    new Mail('welcome', [
        'username' => $username,
        'email' => $_POST['email']
    ]);

    Render::redirect("/discover/?success=Account created! Now find a league!");
});

Route::post('league/create/', function(){

    $currentUser = (new Authentication)->getCurrentUser();

    $league = new Leagues;

    $slug = (new Slugify)->slugify($_POST['name']);

    if ($league->find("slug = '$slug' ")->id) {
        Render::redirect("/league/create/?error=A league with that name exists. Please try a different league name.");
        return;
    }

    $league->name = $_POST['name'];
    $league->overview = $_POST['overview'];
    $league->owner = $currentUser->id;
    $league->uniqueid = uniqid();
    $league->slug = $slug;
    $league->save();

    Render::redirect("/league/$slug/join/");
});

Route::get('logout/', function(){
    (new Authentication)->logout();
});

Route::get('login/', function() use ($currentUser){
    
    Render::view('Site.account-login', [
        'currentUser' => $currentUser
    ]);
});

Route::post('login/', function(){
    $login = (new Authentication)->login($_POST['username'], $_POST['password']);

    if ($login) {
        Render::redirect('/');
        return;
    }

    Render::redirect('/login/?error=Incorrect username or password');
});

Route::get('premium/', function() use($currentUser) {

    Render::view('Site.premium', [
        'currentUser' => $currentUser, 
        'stripe_public_key' => getenv('stripe_public_key')
    ]);
});





