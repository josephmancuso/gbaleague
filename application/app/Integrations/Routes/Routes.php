<?php

use Mira\Route;
use Mira\Render\Render;

use App\League\Models\Pull;
use App\League\Models\Leagues;
use Middleware\Slack;

Route::post('integrations/slack/add/{leagueId}/', function($leagueId){

    $league = (new Leagues)->find($leagueId);

    $league->slackwebhook = $_POST['webhook'];
    $league->slackchannel = $_POST['channel'];
    $league->save();

    (new Slack($league))->client()->send('GBALeague.com has been integrated into your league');

    Render::redirect("/league/$league->slug/chat/");
});

Route::get('integration/oauth/slack/', function(){
    Slack::acceptIntegration();
});
// Routes Here
Route::post('integrations/slack/', function(){
    $slackStuff = json_decode(file_get_contents("php://input"), true);

    var_dump($slackStuff);

    $pull = new Pull;

    $pull->name = json_encode($slackStuff);

    $pull->save();
});
