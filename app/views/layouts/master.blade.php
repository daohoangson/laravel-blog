<!DOCTYPE html>
<html lang="en">
	<head>
		<title>@yield('title', 'Laravel Blog')</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">

		{{ HTML::style('css/bootstrap.css') }}
		{{ HTML::style('css/bootstrap-theme.css') }}
		
		<style>@yield('style')</style>
		@yield('head')
	</head>

	<body>
		<nav class="navbar navbar-default" role="navigation">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#">Blog</a>
				</div>

				<div class="collapse navbar-collapse" id="navbar-collapse">
					<ul class="nav navbar-nav">
						<li><a href="{{{ route('index') }}}">Entries</a></li>
						<li><a href="{{{ route('user_list') }}}">Users</a></li>
						@if (Auth::guest())
							<li>{{ HTML::link(route('login'), 'Login') }}</li>
							<li>{{ HTML::link(route('register'), 'Register') }}</li>
						@else
							@if (Auth::user()->canCreateEntry())
								<li>{{ HTML::link(route('entry_create'), 'Create New Entry') }}</li>
							@endif
							<li>{{ HTML::link(route('profile'), Auth::user()->email) }}</li>
							<li>{{ HTML::link(route('logout'), 'Logout') }}</li>
						@endif
					</ul>
			 	</div>
			</div>
		</nav>

		<div class="container">
			@yield('content', 'Hmm, nothing?')
		</div>

		{{ HTML::script('js/jquery-1.11.1.min.js') }}
		{{ HTML::script('js/bootstrap.min.js') }}
		@yield('foot')
	</body>
</html>