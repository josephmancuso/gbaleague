<?php

use Mira\Route;
use Mira\Render\Render;

use App\League\Models\Pull;
use App\League\Models\Leagues;
use Middleware\Slack;

use Middleware\Authentication;

Route::post('integrations/slack/add/{leagueId}/', function($leagueId){

    $league = (new Leagues)->find($leagueId);

    $league->slackwebhook = $_POST['webhook'];
    $league->slackchannel = $_POST['channel'];
    $league->save();
    
    Render::redirect("/league/$league->slug/chat/");
});

Route::get('integration/oauth/slack/', function(){
    $integration = Slack::acceptIntegration();

    $league = (new Leagues)->find($_GET['state']);
    $league->slackwebhook = $integration->incoming_webhook->url;
    $league->slackchannel = $integration->incoming_webhook->channel;
    $league->save();

    (new Slack($league))->client()->send('GBALeague.com has been integrated into your league');

    Render::redirect("/league/$league->slug/chat/");
});
// Routes Here
Route::post('integrations/slack/', function(){
    $slackStuff = json_decode(file_get_contents("php://input"), true);

    var_dump($slackStuff);
});

Route::post('integrations/stripe/plan/', function() {
    $currentUser = (new Authentication)->getCurrentUser();

    \Stripe\Stripe::setApiKey(getenv('stripe_secret_key'));
    $customer = \Stripe\Customer::create(array(
        "description" => "GBALeague.com account for $currentUser->username",
        "source" => $_POST['stripeToken'] // obtained with Stripe.js
    ));

    $currentUser->member = 1;
    $currentUser->customer = $customer->id;

    $currentUser->save();
    
    \Stripe\Subscription::create(array('plan' => 'gbaleague', 'customer' => $customer->id));

    Render::redirect('/');
});
