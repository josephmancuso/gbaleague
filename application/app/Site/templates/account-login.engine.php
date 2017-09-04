<!-- Hero
============================================ -->
<div class="hero-wrapper text-center" style="background-image: url({{ loadStatic('Site') }}/images/page-banner.jpg)">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="hero-content fix">
					<h1>Login</h1>
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
        <h1 class="text-center">Login</h1><hr>
        <div class="col-xs-12 col-md-8 col-md-offset-2">
        <form action="/login/" method="POST">

            <div class="form-group">
                <label>Username</label>
                <input type="text" pattern="\w+" class="form-control" name="username" required="true" title="Username cannot have spaces or special characters">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="password" required="true">
            </div>

            <div class="form-group text-center">
                <button class="btn btn-success">Login</button>
            </div>

        </div>
    </div>
</div>