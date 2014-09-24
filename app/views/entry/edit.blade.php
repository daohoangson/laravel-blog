@extends('layouts.master')

@section('content')
<div class="row">
	{{ Form::model($entry, array('route' => 'entry_save', 'class' => 'col-md-12')) }}

		<div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
			{{ Form::label('title', 'Title') }}
			{{ Form::text('title', null, array('class' => 'form-control', 'placeholder' => 'Enter entry title')) }}
			{{ $errors->first('title', '<span class="help-block">:message</span>') }}
		</div>

		<div class="form-group {{ $errors->has('body') ? 'has-error' : '' }}">
			{{ Form::label('body', 'Body') }}
			{{ Form::textarea('body', null, array('class' => 'form-control', 'placeholder' => 'Entry body', 'rows' => 5)) }}
			{{ $errors->first('body', '<span class="help-block">:message</span>') }}
		</div>
		
		@if (!empty($entry) AND !empty($entry->id))
			<div class="alert alert-danger form-group checkbox">
				<label>
					{{ Form::checkbox('delete', '1') }}
					Soft Delete
				</label>
			</div>
		@endif

		{{ Form::submit('Save', array('class' => 'btn btn-default')) }}

		{{ Form::hidden('_token', csrf_token()) }}
		{{ Form::hidden('id') }}

	{{ Form::close() }}
</div>
@stop