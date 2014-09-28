'use strict';

var blogApp = angular.module('blogApp', ['ngRoute', 'ui.bootstrap', 'entryCtrl', 'entryService', 'userCtrl', 'userService']);

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

    $routeProvider.when('/users',
        {
            templateUrl: '/app/views/user/list.html',
            controller: 'UserController'
        });
    $routeProvider.when('/users/:id',
        {
            templateUrl: '/app/views/user/view.html',
            controller: 'UserController'
        });

    $locationProvider.html5Mode(true);
});
