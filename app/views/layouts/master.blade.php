<!DOCTYPE html>
<html lang="en">
	<head>
		<title>@yield('title', 'Laravel Blog')</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">

		{{ HTML::style('css/bootstrap.css') }}
		{{ HTML::style('css/bootstrap-theme.css') }}
		
		<style>@yield('style')</style>
	</head>

	<body>
		<div class="container">
			@yield('content', 'Hmm, nothing?')
		</div>

		{{ HTML::script('js/jquery-1.11.1.min.js') }}
		{{ HTML::script('js/bootstrap.min.js') }}
	</body>
</html>