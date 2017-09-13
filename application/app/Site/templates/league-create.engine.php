@extends('Site.header')
@section('title') Create A League @endsection
<!-- Hero
============================================ -->
<div class="hero-wrapper text-center" style="background-image: url({{ loadStatic('Site') }}/images/page-banner.jpg)">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="hero-content fix">
					<h1>Create A League</h1>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="container">
    @if (isset($_GET['error']))
        <div class="alert alert-danger text-center">{{$_GET['error']}}</div>
    @endif
    
    <div class="row">
        <h1 class="text-center">League Create Form</h1><hr>
        <div class="col-xs-12 col-md-8 col-md-offset-2">
        <form action="/league/create/" method="POST">
            <div class="form-group">
                <label>League Name</label>
                <input type="text" class="form-control" name="name">
            </div>

            @if ($currentUser->member)
            <div class="form-group">
                <label>League Tournament?</label>
                <select class="form-control" name="tournament">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>

                <div class="alert alert-warning">
                    League Tournaments do not require a draft. All coaches will sign up with a team and register their pokemon. League hosts will then confirm that team into the league and create a schedule.
                </div>
            </div>
            @endif

            
            <div class="form-group">
                <label>League Overview</label>
                <textarea class="form-control summernote" name="overview">
                </textarea>
            </div>

            <div class="text-center padding-sm">
            @if ($currentUser->id)
                 <button class="btn btn-success">Create League</button>
            @else 
                Please <a href="/login/"><span class="btn btn-success">Sign In</span></a> or <a href="/register/"><span class="btn btn-primary">Register</span></a>
            @endif
            </div>
        </form>


        </div>
    </div>
</div>