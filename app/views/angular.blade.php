@extends('layouts.master')

@section('content')
    <div ng-view>
        Loading...
    </div>
@stop

@section('foot')
	{{ HTML::script('app/js/angular.min.js') }}
	{{ HTML::script('app/js/angular-route.min.js') }}
	{{ HTML::script('app/js/ui-bootstrap-tpls-0.11.0.min.js') }}
	{{ HTML::script('app/js/services.js') }}
	{{ HTML::script('app/js/controllers.js') }}
	{{ HTML::script('app/js/app.js') }}
@stop