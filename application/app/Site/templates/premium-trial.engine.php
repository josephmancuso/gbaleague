@extends('Site.header')
@section('title') Create Pokemon Draft Leagues @endsection

<!-- Hero
============================================ -->
<div class="hero-wrapper text-center" style="background-image: url({{ loadStatic('Site') }}/images/page-banner.jpg)">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="hero-content fix">
					<h1>7 Day GBALeague <span class="gold">Premium</span></h1>
					<h3>Become a premium member today and expand your legacy!</h3>
					<a href="#feature" class="button large color-hover">Premium</a>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Demo Section
============================================ -->
<div id="demos" class="padding-90 text-center bg-off-white fix">
	<div class="container">
		<div class="row">
			<div class="section-title text-center col-xs-12">
				<h1>Become a Premium Member Today</h1>

                <h3>
                    Gain access to many more features!
                </h3>
			</div>
		</div>
	</div>
</div>
<!-- Feature Section
============================================ -->
<div id="feature" class="feature-area bg-white padding-90 fix">
	<div class="container">
		<div class="row text-center">
			<div class="section-title text-center col-xs-12">
				<h1>Premium Feature List</h1>
			</div>
			<div class="col-xs-12 col-sm-4">
			    <span class="fa fa-check"> </span>
				Unlimited league coaches
			</div>
			
			<div class="col-xs-12 col-sm-4">
			    <span class="fa fa-check"> </span>
				Discord and Slack Integrations
			</div>

			<div class="col-xs-12 col-sm-4">
			    <span class="fa fa-check"> </span>
				Unlimited Teams
			</div>

			<div class="col-xs-12 col-sm-4">
			    <span class="fa fa-check"> </span>
				Join Unlimited Leagues
			</div>
			
			<div class="col-xs-12 col-sm-4">
			    <span class="fa fa-check"> </span>
				Gain Access to Official Leagues
			</div>

			<div class="col-xs-12 col-sm-4">
			    <span class="fa fa-check"> </span>
				Unlock Trading
			</div>

			<div class="col-xs-12 col-sm-4">
			    <span class="fa fa-check"> </span>
				Unlock Tournaments
			</div>
		</div>
	</div>
</div>

<div class="more-demo text-center bg-off-white padding-90 fix">
	<div class="container">	
		<div class="row">
			<div class="col-md-12 col-sm-6 col-xs-12">
				<div class="support-team">
					<h2 style="text-decoration: line-through">Join Now For Only $4.99 /mo</h2>
					<h2>FREE 7 Day Trial</h2>
                    <p>No Card Required</p>
					<p>Over 6,000 people have joined the site!</p>
					@if ($currentUser->id)
					<form action="/trial" method="POST">
						<button class="btn btn-success" type="submit">FREE Activation</button>
                    </form>
					@else
					<a href="/login/">
						You Must Sign In
					</a>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>

