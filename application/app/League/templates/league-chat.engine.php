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

    <div class="row">

    
       <div class="col-xs-12 col-md-6 col-md-offset-3">
       <form action="/integrations/slack/add/{{ $league->id }}/" method="POST">
            <div class="form-group">
                <label>Slack Webhook </label>
                <input type="text" name="webhook" class="form-control" placeholder="" value="{{ $league->slackwebhook }}" required>
                <div> Not sure about this? </div>
                <a href="https://my.slack.com/apps/new/A0F7XDUAZ-incoming-webhooks" target="_blank">
                    <div class="btn btn-primary">Find my Slack webhook</div>
                </a>
            </div>
            
            <div class="form-group">
                <label>Default Channel </label>
                <input type="text" name="channel" class="form-control" placeholder="" value="{{ $league->slackchannel }}" required>
                <div>Leaving this blank will default to the #general channel</div>
            </div>
            
            <div class="form-group text-center">
                <button class="btn btn-success">
                    Integrate Slack
                </button>
            </div>
        </form>
        </div>
    </div>

    <div class="row">
        
    </div>
</div>