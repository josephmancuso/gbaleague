@section('title') {{ $league->name }} - Requests @endsection

@extends('League.league-header')

<div class="container">
    <div class="row">
        <h1 class="text-center">
            Requests
        </h1>
    </div>

    <div class="row">
        @unless (count($requests)) 
            <h1 class="text-center">No Requests</h1>
        @endunless

        @foreach ($requests as $request)
        @declare $team = $teams->find($request->team)
            <h1>{{ $team->name }}</h1>

            <h3>Owner  {{ $team->owner()->username }}</h3>

            <form action="/league/{{ $league->slug }}/requests/accept/{{ $request->id }}/{{ $request->team}}/" method="POST">
                <button class="btn btn-success">Accept</button>
            </form>


            <form action="/league/{{ $league->slug }}/requests/decline/{{ $request->id }}/" method="POST">
                
                <button class="btn btn-danger">Decline</button>
            </form>

            
            
           
        @endforeach
    </div>

</div>