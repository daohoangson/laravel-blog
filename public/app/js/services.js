'use strict';

angular.module('entryService', []).factory('Entry', function ($http) {
    var Entry =
    {
        all: function (page) {
            return $http.get('/resources/entries?page=' + page);
        },

        store: function (entry) {
            return $http(
                {
                    method: 'POST',
                    url: '/resources/entries',
                    data: entry
                });
        },

        get: function (id) {
            return $http.get('/resources/entries/' + id);
        },

        update: function (entry) {
            return $http(
                {
                    method: 'PUT',
                    url: '/resources/entries/' + entry.id,
                    data: entry
                });
        },

        destroy: function (id, hardDelete) {
            return $http.delete('/resources/entries/' + id,
                {
                    params: {
                        hard_delete: hardDelete ? 1 : 0
                    }
                });
        },

        read: function(id) {
            return $http.post('/resources/entries/' + id + '/read');
        }
    };

    return Entry;
});

angular.module('userService', []).factory('User', function ($http) {
    var User =
    {
        all: function () {
            return $http.get('/resources/users');
        },

        store: function (user) {
            return $http(
                {
                    method: 'POST',
                    url: '/resources/users',
                    data: user
                });
        },

        get: function (id) {
            return $http.get('/resources/users/' + id);
        },

        update: function (user) {
            return $http(
                {
                    method: 'PUT',
                    url: '/resources/users/' + user.id,
                    data: user
                });
        }
    };

    return User;
});
