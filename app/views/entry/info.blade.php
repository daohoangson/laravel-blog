<span>Views: {{{ $entry->views }}}</span>

@if (!Auth::guest())
	@if (Auth::user()->canEditEntry($entry))
		{{ HTML::link(route('entry_edit', $entry->id), 'Edit') }}
	@endif

	@if (!empty($entry->deleted_at) AND Auth::user()->canDeleteEntry($entry))
		{{ HTML::link(route('entry_delete', $entry->id), 'Restore / Hard Delete') }}
	@endif
@endif