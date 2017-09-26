@extends('League.league-header')

@section('title') {{ $league->name }} - Join @endsection

<div class="container">
    <div class="row">
        <h1 class="text-center">
            Chatroom
        </h1>
    </div>

    <div class="alert alert-warning">
    The league chat is still under construction although we strongly advise using Slack as a way to keep your team in touch. With a premium GBALeague account, we can send messages to your Slack group so everyone can be updated in real time
    about drafts, schedules and trades. You'll receive messages from GBALeague.com into your Slack channels when certain actions occur within the league. Teams can stay up to date on their mobile phones with the Slack app.
    </div>

@unless ($isHost && $currentUser->member)
<h2 class="text-center"><span class="fa fa-lock"></span> Locked for <span class="gold">Premium</span> Members Only</h2>
<div class="text-center">
    <a href="/premium/">
        <div class="btn btn-warning">
            Sign Up For Premium
        </div>
    </a>
</div>
@endunless

@if ($isHost && $currentUser->member)
    <div class="row text-center">
    <div class="col-xs-12 col-sm-6">
    <h2> Integrate With Slack </h2>

    @if ($league->slackwebhook)
        <div class="alert alert-success text-center">
            This league is integrated with Slack. Any major events of this league will be broadcasted in your Slack team.
        </div>
    @endif

    <a href="https://slack.com/oauth/authorize?scope=incoming-webhook&client_id=140404785458.236564549872&state={{$league->id}}"><img alt="Add to Slack" height="40" width="139" src="https://platform.slack-edge.com/img/add_to_slack.png" srcset="https://platform.slack-edge.com/img/add_to_slack.png 1x, https://platform.slack-edge.com/img/add_to_slack@2x.png 2x" /></a>
    </div>

    <div class="col-xs-12 col-sm-6">
    <h2> Integrate With Discord </h2>

    @if ($league->discordid)
        <div class="alert alert-success text-center">
            This league is integrated with Discord. Any major events of this league will be broadcasted in your Discord guild.
        </div>
    @endif

    <a href="https://discordapp.com/api/oauth2/authorize?response_type=code&client_id=362339600925982721&scope=webhook.incoming&state={{$league->id}}">
        <img src="{{ loadStatic('League') }}/images/discord.png" style="max-height: 75px">
    </a>
    
    </div>
        
    </div>
@endif
    <div class="row">
        
    </div>
</div>