@extends('layouts.master')

@section('content')
<div class="table-responsive">
	<table class="table table-hover">
		<thead>
			<tr>
				<td>#</td>
				<td>Email</td>
				<td>Roles</td>
			</tr>
		</thead>

		<tbody>
			@foreach ($users as $user)
				<tr>
					<td>{{{ $user->id }}}</td>
					<td>{{ HTML::link(route('user_view', $user->id), $user->email) }}</td>
					<td>
						@foreach ($user->roles as $role)
							{{{ $role->title }}}
						@endforeach
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>
@stop