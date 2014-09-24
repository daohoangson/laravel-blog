@extends('layouts.master')

@section('content')
<div class="row">
	{{ Form::open(array('route' => 'register', 'class' => 'col-sm-12 col-md-offset-4 col-md-4')) }}

		<div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
			{{ Form::label('email', 'Email') }}
			{{ Form::text('email', Input::old('email'), array('class' => 'form-control', 'placeholder' => 'Enter email')) }}
			{{ $errors->first('email', '<span class="help-block">:message</span>') }}
		</div>

		<div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
			{{ Form::label('password', 'Password') }}
			{{ Form::password('password', array('class' => 'form-control', 'placeholder' => 'Password')) }}
			{{ $errors->first('password', '<span class="help-block">:message</span>') }}
		</div>

		{{ Form::submit('Register', array('class' => 'btn btn-default')) }}

		<input type="hidden" name="_token" value="{{ csrf_token(); }}">

	{{ Form::close() }}
</div>
@stop