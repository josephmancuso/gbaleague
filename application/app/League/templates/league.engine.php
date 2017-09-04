@section('title') {{ $league->name }} - A Pokemon Draft League @endsection

@extends('League.league-header')

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <h1 class="text-center">Overview</h1><hr>
            <p>
                {{$league->overview}}
            </p>
        </div>
    </div>
</div>
</div>

