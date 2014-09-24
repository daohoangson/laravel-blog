@extends('layouts.master')

@section('title')
	{{{ $entry->title }}}
@stop

@section('content')
<div class="jumbotron">
	<h1>{{{ $entry->title }}}</h1>
	<p>{{{ $entry->body }}}</p>
	
	<div class="panel panel-default">
		<div class="panel-body">
			@include('entry.info', array('entry' => $entry))
		</div>
	</div>
</div>
@stop