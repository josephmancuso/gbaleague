
<!-- Hero
============================================ -->
<div class="hero-wrapper text-center" style="background-image: url({{ loadStatic('Site') }}/images/page-banner.jpg)">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="hero-content fix">
					<h1>Create A Team</h1>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="container">
    <h1 class="text-center">Create A Team </h1>
    <hr>
    <div class="col-md-4 col-md-offset-4">
    <form action="/team/create/" method="POST" enctype="multipart/form-data">
    @if ($_GET['next'])
    <input type="hidden" name="next" value="{{ $_GET['next'] }}">
    @endif
        <div class="form-group">
            <label>Team Name</label>
            <input type="text" class="form-control" name="name">
        </div>
        
        <div class="form-group">
            <label>Logo</label>
            <input type="file" name="logo">
        </div>

        <div class="row text-center">
        @if ($currentUser->member || (!$currentUser->member && $teamCount < 2))
            @if ($currentUser->id)
                <button class="btn btn-success">
                    Create Team
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