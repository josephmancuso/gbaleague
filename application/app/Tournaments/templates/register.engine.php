@extends('Site.header')
<!-- Hero
============================================ -->
<div class="hero-wrapper text-center" style="background-image: url({{ loadStatic('Site') }}/images/page-banner.jpg)">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="hero-content fix">
					<h1>Tournament Registration</h1>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="container">
    <h1 class="text-center">Register Your Team</h1>

    @if ($_GET['error'])
        <div class="alert alert-danger text-center">{{$_GET['error']}}</div>
    @endif
    <hr>
    <div class="col-md-4 col-md-offset-4">
    <form action="/tournaments/team/{{$league->slug}}/" method="POST" enctype="multipart/form-data">
    @if ($_GET['next'])
    <input type="hidden" name="next" value="{{ $_GET['next'] }}">
    @endif
        <div class="form-group">
            <label>Team</label>
            <select class="form-control" name="team">

            @unless (count($teams))
                <option selected disabled>No Teams Available</option>
            @endunless

            @foreach ($teams as $team)
                <option value="{{ $team->id }}">{{ $team->name }}</option>
            @endforeach

            </select>
        </div>

        <div class="form-group text-right">
            <a class="" href="/team/create/?next=/tournaments/team/first-tournament">
                <div class="btn btn-primary">
                    Create New Team
                </div>
            </a>
        </div>

        <div class="form-group">
            <label>Pokemon 1</label>
            <input name="pokemon[]" class="form-control" list="browsers">
        </div>  

        <div class="form-group">
            <label>Pokemon 2</label>
            <input name="pokemon[]" class="form-control" list="browsers">
        </div>  

        <div class="form-group">
            <label>Pokemon 3</label>
            <input name="pokemon[]" class="form-control" list="browsers">
        </div>  

        <div class="form-group">
            <label>Pokemon 4</label>
            <input name="pokemon[]" class="form-control" list="browsers">
        </div>  

        <div class="form-group">
            <label>Pokemon 5</label>
            <input name="pokemon[]" class="form-control" list="browsers">
        </div>  

        <div class="form-group">
            <label>Pokemon 6</label>
            <input name="pokemon[]" class="form-control" list="browsers">
        </div>  

        <div class="row text-center">
        @if ($currentUser->member || (!$currentUser->member && $teamCount < 2))
            @if ($currentUser->id)
                <button class="btn btn-danger">
                    Register
                </button>
            @else 
                Please <a href="/login/"><span class="btn btn-success">Sign In</span></a> or <a href="/register/"><span class="btn btn-primary">Register</span></a>
            @endif
        @else
            <h2 class="text-center"><span class="fa fa-lock"></span> More than 3 Teams are Locked for <span class="gold">Premium</span> Members Only</h2>
            <div class="text-center">
                <a href="/premium/">
                    <div class="btn btn-warning">
                        Sign Up For Premium
                    </div>
                </a>
            </div>
        @endif        
        </div>      
    </form>
    </div>
</div>

<datalist id="browsers">
    @foreach($pokemon->all() as $pokemon)
        <option value="{{$pokemon->pokemonName}}">
    @endforeach
    
    <option value="Internet Explorer">
    <option value="Firefox">
    <option value="Chrome">
    <option value="Opera">
    <option value="Safari">
</datalist>

<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>