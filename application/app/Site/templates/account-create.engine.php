@extends('Site.header')
<!-- Hero
============================================ -->
<div class="hero-wrapper text-center" style="background-image: url({{ loadStatic('Site') }}/images/page-banner.jpg)">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="hero-content fix">
					<h1>Register</h1>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="container">
    @if ($_GET['error'])
        <div class="alert alert-danger text-center">{{$_GET['error']}}</div>
    @endif
    
    <div class="row">
        <h1 class="text-center">Account Register</h1><hr>
        <div class="col-xs-12 col-md-8 col-md-offset-2">
        <form action="/register/" method="POST">
            <div class="form-group">
                <label>Firstname</label>
                <input type="text" class="form-control" name="firstname" required="true">
            </div>

            
            <div class="form-group">
                <label>Lastname</label>
                <input type="text" class="form-control" name="lastname" required="true">
            </div>


            <div class="form-group">
                <label>Username</label>
                <input type="text" pattern="\w+" class="form-control" name="username" required="true" title="Username cannot have spaces or special characters">
            </div>

            
            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" name="email" required="true">
            </div>

            
            <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="password" required="true">
            </div>

            
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" class="form-control" name="confirmpassword" required="true">
            </div>


            <div class="form-group text-center">
                <button class="btn btn-success">Register</button>
            </div>
        </form>


        </div>
    </div>
</div>