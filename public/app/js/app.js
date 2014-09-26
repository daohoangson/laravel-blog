'use strict';

var blogApp = angular.module('blogApp', ['ngRoute', 'ui.bootstrap', 'entryCtrl', 'entryService']);

blogApp.config(function($httpProvider, $routeProvider, $locationProvider)
{
    $httpProvider.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest';

    $routeProvider.when('/',
    {
        redirectTo: '/entries'
    });
    $routeProvider.when('/entries',
    {
        templateUrl: '/app/views/entry/list.html',
        controller: 'EntryController'
    });
    $routeProvider.when('/entries/:id',
    {
        templateUrl: '/app/views/entry/view.html',
        controller: 'EntryController'
    });

    $locationProvider.html5Mode(true);
});
