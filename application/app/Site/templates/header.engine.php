
<!doctype html>
<html class="no-js" lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title> GBA League - 
		@yield('title')
	</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- Google Fonts -->
	<link href='https://fonts.googleapis.com/css?family=Merriweather:400,700,900' rel='stylesheet' type='text/css'>
	<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
	
	<!-- css  -->
	<!-- Bootstrap CSS
	============================================ -->
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<!-- Icon Font CSS
	============================================ -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<!-- Mean Menu CSS
	============================================ -->
	<link rel="stylesheet" href="{{ loadStatic('Site') }}/css/mean-menu.css">
	<!-- Animate CSS
	============================================ -->
	<link rel="stylesheet" href="{{ loadStatic('Site') }}/css/animate.min.css">

	<link rel="stylesheet" href="{{ loadStatic('Site') }}/js/pickadate/lib/themes/default.css">
	<link rel="stylesheet" href="{{ loadStatic('Site') }}/js/pickadate/lib/themes/default.date.css">
	<!-- Style CSS
	============================================ -->
	<link rel="stylesheet" href="{{ loadStatic('Site') }}/css/style.css">
	<!-- Responsive CSS 
	============================================ -->
	<link rel="stylesheet" href="{{ loadStatic('Site') }}/css/responsive.css">

	<link rel="stylesheet" href="{{ loadStatic('League') }}/css/padding.css">
	<link rel="stylesheet" href="{{ loadStatic('Site') }}/js/summernote/dist/summernote.css">
	<!-- Modernizer JS
	============================================ -->
	<script src="{{ loadStatic('Site') }}/js/modernizr.js"></script>
</head>
<body>
<!-- Header Area Start -->
<div id="header-area" class="header-area section">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <!-- Logo -->

                <!---- Menu ---->
                <div id="main-menu" class="main-menu float-right">
                    
                </div>
                <!---- Mobile Menu ---->
				<nav class="navbar navbar-default navbar-fixed-top">
					<div class="container">
						<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="/">GBALeague</a>
						</div>
						<div id="navbar" class="navbar-collapse collapse" aria-expanded="false" style="height: 1px;">
						<ul class="nav navbar-nav">
							<li class="active"><a href="/">Home</a></li>
							<li><a href="/discover/">Discover</a></li>
							@unless ($currentUser->member)
								<li><a href="/premium/" style="color: #f1c40f">Premium</a></li>
							@endunless
							<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Create <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="/league/create/">Create A League</a></li>
								<li><a href="/team/create/">Create A Team</a></li>
							</ul>
							</li>

							@if ($currentUser->username && count($currentUser->getLeagues()))
								<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">My Leagues <span class="caret"></span></a>
								<ul class="dropdown-menu">

									@foreach($currentUser->getLeagues() as $league)
										<li><a href="/league/{{$league->slug}}/">{{ $league->name }}</a></li>
									@endforeach

								</ul>
								</li>
							@endif
						</ul>



						<ul class="nav navbar-nav navbar-right">

							@if ($currentUser->username)
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
									{{$currentUser->username}} 

									@if ($currentUser->member)
									(premium)
									@endif
									
									<span class="caret"></span>
								</a>
								<ul class="dropdown-menu">
									<li><a href="/logout/">Logout</a></li>
								</ul>
							</li>

							@else
								<li><a href="/login/">Login</a></li>
								<li class="active"><a href="/register/">Register</a></li>
							@endif
						</ul>
						</div><!--/.nav-collapse -->
					</div>
					</nav>
            </div>
        </div>
    </div>
</div>
<!-- Header Area End -->