
@extends('Site.header')
@section('title') Create Pokemon Draft Leagues @endsection

<!-- Hero
============================================ -->
<div class="hero-wrapper text-center" style="background-image: url({{ loadStatic('Site') }}/images/page-banner.jpg)">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="hero-content fix">
					<h1>GBALeague</h1>
					<h3>Global Battle Association</h3>
					<a href="/discover/" class="button large color-hover">Discover</a>
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
				<h1>Choose Your Legacy</h1>
			</div>

			
			<div class="section-title text-center col-xs-12 col-sm-4">
			<a href="/league/create/">
				<div class="btn btn-primary btn-lg">Create A League</div>
			</a>
			</div>

			
			<div class="section-title text-center col-xs-12 col-sm-4">
			<a href="/discover/">
				<div class="btn btn-success btn-lg">Join a League</div>
			</a>
			</div>

			<div class="section-title text-center col-xs-12 col-sm-4">
			<a href="/team/create/">
				<div class="btn btn-danger btn-lg">Create A Team</div>
			</a>
			</div>

			<div id="demo-container">
				<div class="mix onepage col-md-4 col-sm-6 col-xs-12">
					<div class="single-demo">
						<div class="demo-img">
							<a href="suparsport/index.html" target="_blank"><img src="img/demo/1.jpg" alt="demo img"></a>
						</div>
						<div class="demo-title">
							<h2>SuparSport</h2>
							<span>Home Page</span>
						</div>						
					</div>
				</div>
				<div class="mix onepage col-md-4 col-sm-6 col-xs-12">
					<div class="single-demo">
						<div class="demo-img">
							<a href="suparsport/about.html" target="_blank"><img src="img/demo/2.jpg" alt="demo img"></a>
						</div>
						<div class="demo-title">
							<h2>SuparSport</h2>
							<span>About Page</span>
						</div>						
					</div>
				</div>
				<div class="mix onepage col-md-4 col-sm-6 col-xs-12">
					<div class="single-demo">
						<div class="demo-img">
							<a href="suparsport/team.html" target="_blank"><img src="img/demo/3.jpg" alt="demo img"></a>
						</div>
						<div class="demo-title">
							<h2>SuparSport</h2>
							<span>Team Page</span>
						</div>						
					</div>
				</div>
				<div class="mix onepage col-md-4 col-sm-6 col-xs-12">
					<div class="single-demo">
						<div class="demo-img">
							<a href="suparsport/shop.html" target="_blank"><img src="img/demo/4.jpg" alt="demo img"></a>
						</div>
						<div class="demo-title">
							<h2>SuparSport</h2>
							<span>Shop Page</span>
						</div>						
					</div>
				</div>
				<div class="mix onepage col-md-4 col-sm-6 col-xs-12">
					<div class="single-demo">
						<div class="demo-img">
							<a href="suparsport/blog.html" target="_blank"><img src="img/demo/5.jpg" alt="demo img"></a>
						</div>
						<div class="demo-title">
							<h2>SuparSport</h2>
							<span>Blog Page</span>
						</div>						
					</div>
				</div>
				<div class="mix onepage col-md-4 col-sm-6 col-xs-12">
					<div class="single-demo">
						<div class="demo-img">
							<a href="suparsport/contact.html" target="_blank"><img src="img/demo/6.jpg" alt="demo img"></a>
						</div>
						<div class="demo-title">
							<h2>SuparSport</h2>
							<span>Contact Page</span>
						</div>						
					</div>
				</div>
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
				<h1>GBALeague Feature List</h1>
			</div>
			<div class="col-xs-12 col-sm-4">
			<span class="fa fa-check"> </span>
				Build a League
			</div>

			
			<div class="col-xs-12 col-sm-4">
			<span class="fa fa-check"> </span>
				Create A Team
			</div>

			<div class="col-xs-12 col-sm-4">
			<span class="fa fa-check"> </span>
				Draft Pokemon
			</div>

			<div class="col-xs-12 col-sm-4">
			<span class="fa fa-check"> </span>
				Find Friends
			</div>
			
			<div class="col-xs-12 col-sm-4">
			<span class="fa fa-check"> </span>
				Trade Pokemon
			</div>

			
			<div class="col-xs-12 col-sm-4">
			<span class="fa fa-check"> </span>
				Create Schedules
			</div>

		</div>
	</div>
</div>

<div class="more-demo text-center bg-off-white padding-90 fix">
	<div class="container">	
		<div class="row">
			<div class="col-md-12 col-sm-6 col-xs-12">
				<div class="support-team">
					<h2>Join Now!</h2>
					<p>Over 5500 people have signed up already!</p>
					<a href="/register/">Register</a>
				</div>
			</div>
		</div>
	</div>
</div>

