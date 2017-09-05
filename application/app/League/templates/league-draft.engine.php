@extends('League.league-header')

@section('title') {{ $league->name }} - Draft @endsection

<div class="container">
    <div class="row">
        <h1 class="text-center">Draft Status: 
        @if (!$league->status)
            Not Started
        @elseif ($league->status == 1) 
            Open
        @else
            Closed
        @endif
        </h1>
        @if ($league->status || $isHost)
        @declare $teaminfo = $teams->find("league = '$league->id' AND owner='$league->current' ")
        <h2 class="text-center">Currently Drafting: 

            <div>
            
            </div>
            
            <div class="padding-sm">
            {{ $league->current()->username }}. Owner of the {{ $teaminfo->name }} who have {{ $teaminfo->points }} points left
            </div>


            @unless ($league->current()->username)
                Nobody
            @endunless
        </h2>

        <h2 class="text-center">
           Round {{ $league->round }}
        </h2>

        <div class='text-center'>

        @if ($isHost)
            <form action="/league/{{$league->id}}/status/" method="POST">
            @if ($league->status == 1) 
                <input type="hidden" value="2" name="status">
                <button class="btn btn-danger text-center">Close Draft</button>
            @elseif ($league->status == 2) 
                <input type="hidden" value="1" name="status">
                <button class="btn btn-success text-center">Reopen Draft</button>
            @else
                <input type="hidden" value="1" name="status">
                <button class="btn btn-success text-center">Open Draft</button>
            @endif
            </form>

            @if ($league->status == 1)
            <form action="/league/{{$league->id}}/skip/" method="POST" class="padding-sm">
                <button class="btn btn-primary text-center">Skip</button>
            </form>
            @endif
        @endif
        
        </div>

        <div class="pull-right">
            <select class="form-control" id="tier-control">
                <option value='all'>All</option>
                <option value='1' selected>Tier 1</option>
                <option value='2'>Tier 2</option>
                <option value='3'>Tier 3</option>
                <option value='4'>Tier 4</option>
                <option value='5'>Tier 5</option>
            </select>
        </div>
        <hr>

        @if ($league->status == 1)
            @foreach ($pokemonList->order('tier, pokemonName')->all() as $pokemon)

                <div class="col-xs-12 col-sm-3 text-center" tier="{{ $pokemon->tier }}">
                    <h2>{{ $pokemon->pokemonName }}</h2>
                    <h4>Points: {{ $pokemon->points }}</h4>
            
            @if ($user->id == $currentDrafter->id)

                @if (!in_array($pokemon->id, $listOfDraftedPokemon))

                @if ($usersTeam->points >= $pokemon->points)
                <form action="/league/draft/pokemon/{{$pokemon->id}}/{{$league->id}}/" method="post">
                    <button class="btn btn-success">
                        Draft
                    </button>
                </form>
                @else
                Not enough points
                @endif

                @if (!in_array($pokemon->id, $listOfQueuedPokemon))
                    <form action="/league/queue/pokemon/{{$pokemon->id}}/{{$league->id}}/" method="post">
                        <button class="btn btn-primary">
                            Queue
                        </button>
                    </form>
                @else
                    <form action="/league/unqueue/pokemon/{{$pokemon->id}}/{{$league->id}}/" method="post">
                        <button class="btn btn-primary">
                            Unqueue
                        </button>
                    </form>
                @endif

                @else
                    <div class="btn btn-danger">
                        Drafted
                    </div>
                @endif

                

            @else
            Not Your Turn
            @endif
                </div>

            
            @endforeach
        @endif 
        @endif    
        
    </div>
</div>