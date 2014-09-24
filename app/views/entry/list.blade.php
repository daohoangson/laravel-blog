@extends('layouts.master')

@section('content')
	@foreach($entries as $entry)
		<?php
			$panelType = 'panel-primary';
			if (!empty($entry->deleted_at)) {
				$panelType = 'panel-danger';
			}
		?>
		<div class="panel {{ $panelType }}">
			<div class="panel-heading">
				<h3 class="panel-title">
					@if (!empty($entry->deleted_at))
						<span class="label label-danger">Soft Deleted</span>
					@endif

					{{ HTML::link(route('entry_view', $entry->id), $entry->title) }}
				</h3>
			</div>
			<div class="panel-body">
				<p>{{{ $entry->body }}}</p>
				
				<div class="well well-sm">
					@include('entry.info', array('entry' => $entry))
				</div>
			</div>
		</div>
	@endforeach
	
	{{ $entries->links() }}
@stop