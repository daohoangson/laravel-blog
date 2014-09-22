@extends('layouts.master')

@section('content')
<div class="row">
	{{ Form::open(array('url' => '/users/save', 'class' => 'col-sm-12 col-md-offset-4 col-md-4')) }}

		<div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
			{{ Form::label('email', 'Email') }}
			{{ Form::text('email', $user->email, array('class' => 'form-control', 'placeholder' => 'Enter email')) }}
			{{ $errors->first('email', '<span class="help-block">:message</span>') }}
		</div>

		<div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
			{{ Form::label('password', 'Password') }}
			{{ Form::password('password', array('class' => 'form-control', 'placeholder' => 'Enter new password if needed')) }}
			{{ $errors->first('password', '<span class="help-block">:message</span>') }}
		</div>

		@if (Auth::user()->isAdministrator())
			<div class="form-group">
				{{ Form::label('', 'Roles') }}

				@foreach (Role::all() as $role)
					<div class="checkbox">
						<label>
							{{ Form::checkbox('roles[]', $role->id, $user->roles->contains($role->id)) }}
							{{{ $role->title }}}
						</label>
					</div>
				@endforeach
			</div>
		@endif

		{{ Form::submit('Save', array('class' => 'btn btn-default')) }}
		
		<input type="hidden" name="user_id" value="{{ $user->id }}">
		<input type="hidden" name="_token" value="{{ csrf_token(); }}">

	{{ Form::close() }}
</div>
@stop