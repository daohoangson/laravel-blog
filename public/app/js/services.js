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
        }
    };

    return Entry;
});
