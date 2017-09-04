@section('title') {{ $league->name }} - Teams @endsection

@extends('League.league-header')

<div class="container">
    <div class="row">

	@unless($teams)
		<h1 class="text-center">No Teams</h1>
	@endunless
    @foreach ($teams as $team)
        <div class="col-xs-12 col-sm-4 text-center">

			@if($isHost || $usersTeam->id == $team->id)
			<form action="/league/{{$league->id}}/team/remove/" method="POST" class="padding-sm">
				<input type="hidden" value="{{ $team->id }}" name="team">
				<button class="btn btn-danger">Remove Team</button>
			</form>
			@endif

			<img src="{{ loadStatic('Teams') }}/images/uploads/{{$team->picture}}">
            <h2>{{ $team->name }}</h2>

			<h4>Owner: {{ $teamModel->find($team->id)->owner()->username }}</h4>

			<h4>Points: {{ $team->points }}</h4>
			
			<hr>

			<h4>Pokemon</h4>
			
			
			@foreach ($draftedPokemon->filter("team = $team->id AND queue IS NULL") as $pokemon)
			@declare $pokemonInfo = $pokemonList->find($pokemon->pokemon)
				{{ $pokemonInfo->pokemonName }}
				{{ $pokemonInfo->points }}
				
			@endforeach

        </div>
    @endforeach
        
    </div>
</div>