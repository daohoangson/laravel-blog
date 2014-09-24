@extends('layouts.master')

@section('content')
<div class="row">
	{{ Form::model($entry, array('route' => array('entry_delete', $entry->id), 'class' => 'col-md-12')) }}
	
		<h1>{{{ $entry->title }}}</h1>

		<div class="alert alert-danger form-group checkbox">
			<label>
				{{ Form::radio('action', 'hard_delete') }}
				Hard Delete
			</label>
		</div>
		
		<div class="alert alert-success form-group checkbox">
			<label>
				{{ Form::radio('action', 'restore', true) }}
				Restore
			</label>
		</div>

		{{ Form::submit('Continue', array('class' => 'btn btn-default')) }}

		{{ Form::hidden('_token', csrf_token()) }}

	{{ Form::close() }}
</div>
@stop