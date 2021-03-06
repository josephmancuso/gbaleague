@extends('Site.header')
@section('title') Discover Leagues @endsection

<!-- Hero
============================================ -->
<div class="hero-wrapper text-center" style="background-image: url({{ loadStatic('Site') }}/images/page-banner.jpg)">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="hero-content fix">
					<h1>Tournaments</h1>
					<h3>Discover tournaments to play in</h3>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="container">
	@if ($_GET['success'])
		<div class="alert alert-success text-center">{{ $_GET['success'] }}</div>
	@endif

	<div class="row text-center padding-sm">
		<a href="/league/create/">
			<div class="btn btn-primary">
				Create A League
			</div>
		</a>
	</div>
    <div class="row">

		<h2 class="text-center">Tournaments</h2>
        @foreach (array_chunk($tournaments->getTournaments(), 3) as $tournamentChunk)

		<div class="row">
		@foreach($tournamentChunk as $league)
            <div class="col-xs-12 col-sm-4">
                <h1>{{ $league->name }}</h1>
				<h3>Owner: {{ $league->owner()->username }}</h3>
				<h4>Status: 
					@if ($league->status == 0)
						Not Started
					@elseif ($league->status == 1)
						Started
					@elseif ($league->status == 2)
						Done
					@endif
				</h4>

				<div class="row text-center">
					<a href="/league/{{$league->slug}}/">
						<div class="btn btn-success">
							View
						</div>
					</a>
				</div>
            </div>
		@endforeach
		</div>
        @endforeach
        <div class="col-xs-12 col-sm-6 col-md-4"></div>
    </div>
</div>