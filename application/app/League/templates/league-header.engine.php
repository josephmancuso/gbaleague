@extends('Site.header')
<div class="hero-wrapper text-center" style="background-image: url({{ loadStatic('Site') }}/images/page-banner.jpg)">
<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<div class="hero-content fix">
				<h1>{{ $league->name }}</h1>
				<h3>
				</h3>
				<a href="/league/{{$league->slug}}/" class="button large color-hover">Overview</a>
				<a href="/league/{{$league->slug}}/draft/" class="button large color-hover">Draft</a>
				<a href="/league/{{$league->slug}}/teams/" class="button large color-hover">Teams</a>
				<a href="/league/{{$league->slug}}/join/" class="button large color-hover">Join</a>
				<br><br>
				<a href="/league/{{$league->slug}}/trade/" class="button large color-hover">Trade</a>
				<a href="/league/{{$league->slug}}/schedule/" class="button large color-hover">Schedule</a>

				@if ($isHost)
				<a href="/league/{{$league->slug}}/requests/" class="button large color-hover">Requests</a>
				@endif
				
				<a href="/league/{{$league->slug}}/chat/" class="button large color-hover">Chat</a>
			</div>
		</div>
	</div>
</div>
</div>