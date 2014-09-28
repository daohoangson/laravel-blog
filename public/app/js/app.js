'use strict';

var blogApp = angular.module('blogApp', ['ngRoute', 'ui.bootstrap', 'mainCtrl', 'entryCtrl', 'entryService']);

blogApp.config(function ($httpProvider, $routeProvider, $locationProvider) {
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
            templateUrl: function ($routeParams) {
                if ($routeParams['id'] > 0) {
                    return '/app/views/entry/view.html';
                } else {
                    return '/app/views/entry/edit.html';
                }
            },
            controller: 'EntryController'
        });
    $routeProvider.when('/entries/:id/:action',
        {
            templateUrl: function ($routeParams) {
                return '/app/views/entry/' + $routeParams['action'] + '.html';
            },
            controller: 'EntryController'
        });

    $locationProvider.html5Mode(true);
});
