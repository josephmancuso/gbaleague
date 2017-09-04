
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

	<!-- Favicon -->
	<link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
	
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
                    <nav>
                        <ul>
                            <li class="active"><a href="/">home</a></li>
                            <!-- <li><a href="suparsport/about.html">pokedex</a></li> -->
                            <li><a href="/discover/">discover</a></li>
                            <li><a>Create</a>
                                <ul>
                                    <li><a href="/league/create/">A League</a></li>
                                    <li><a href="/team/create/">A Team</a></li>
                                </ul>
                            </li>                           

							@if ($currentUser->username)
							<li><a>{{ $currentUser->username }}</a>
                                <ul>
                                    <li><a href="/logout/">Logout</a></li>
                                </ul>
                            </li>
							@else
								<li><a href="/register/">Register</a></li>
								<li><a href="/login/">Login</a></li>
							@endif
							
                        </ul>
                    </nav>
                </div>
                <!---- Mobile Menu ---->
                <div class="mobile-menu"></div>
            </div>
        </div>
    </div>
</div>
<!-- Header Area End -->