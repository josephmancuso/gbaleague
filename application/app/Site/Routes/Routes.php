<?php

use Mira\Route;
use Mira\Render\Render;
use Mira\Mail\Mail;

use App\League\Models\Leagues;
use App\League\Models\Accounts;
use App\League\Models\Teams;
use App\Site\Models\Affiliate;

use Cocur\Slugify\Slugify;

use Middleware\Authentication;
use Middleware\MailChimp;

// Routes Here

$currentUser = (new Authentication)->getCurrentUser();

(new Authentication)->checkTrialStatus();

Route::get('home/', function() use ($currentUser){
    
    Render::view('Site.index', [
        'currentUser' => $currentUser,
        'memberCount' => $members
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

    if (isset($_SESSION['ref'])) {
        $_POST['ref'] = $_SESSION['ref'];
    }

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

    $currentUser = (new Authentication)->getCurrentUser();
    $result = MailChimp::subscribe($currentUser);  

    if (isset($_SESSION['ref'])) {
        Render::redirect("/premium");
        return;
    }  

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

    if ($_POST['tournament']) {
        $league->tournament = $_POST['tournament'];
    }

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

Route::get('trial/', function() use ($currentUser){

    if ($currentUser->isOnTrial() || $currentUser->member || $currentUser->trial_ends) {
        return Render::redirect('/premium/?message=You have already activated the trial');
    }

    Render::view('Site.premium-trial', [
        'currentUser' => $currentUser
    ]);
});

Route::post('trial/', function() use($currentUser){

    if ($currentUser->isOnTrial() || $currentUser->member || $currentUser->trial_ends) {
        return Render::redirect('/premium/?message=You have already activated the trial');
    }

    $currentUser->member = 1;
    $currentUser->trial_ends = date('Y-m-d', strtotime('+7 day', time()));
    $currentUser->save();

    Render::redirect('/discover/?success=You Are Now A Premium Member For 7 Days! Congrats!');
});

Route::get('premium/{affiliate}', function($affiliate) use ($currentUser) {
    if ($currentUser->id) {
        if ($affiliate) {
            $currentUser->ref = $affiliate;
            $currentUser->save();
            // var_dump($currentUser);
        }

        MailChimp::premium($currentUser);
    } else {
        $_SESSION['ref'] = $affiliate;
    }

    Render::view('Site.premium', [
        'currentUser' => $currentUser, 
        'stripe_public_key' => getenv('stripe_public_key')
    ]);
});

Route::get('premium', function() use ($currentUser) {

    Render::view('Site.premium', [
        'currentUser' => $currentUser, 
        'stripe_public_key' => getenv('stripe_public_key')
    ]);
});


