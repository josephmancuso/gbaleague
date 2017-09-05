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

			@if ($isHost)
			<form action="/team/points/{{$team->id}}/{{$league->id}}/" method="POST">
				<div class="input-group">
					<input type="number" name="points" class="form-control" placeholder="" required>
					<span class="input-group-btn">
						<button class="btn btn-success" type="button">Add Points</button>
					</span>
				</div>
			</form>
			@endif
			
			<hr>

			<h4>Pokemon</h4>
			<hr>
			
			@foreach ($draftedPokemon->filter("team = $team->id AND queue IS NULL") as $pokemon)
			@declare $pokemonInfo = $pokemonList->find($pokemon->pokemon)

			<div>
				{{ $pokemonInfo->pokemonName }}
				{{ $pokemonInfo->points }}
			

			@if ($isHost)
			<form action="/team/remove/{{ $pokemon->id }}/{{$league->id}}/" method="POST">
				<button class="btn btn-danger">
					<span class="fa fa-close"></span> Remove
				</button>
			</form>
			@endif


			<hr>
			</div>
			
			@endforeach

        </div>
    @endforeach
        
    </div>
</div>