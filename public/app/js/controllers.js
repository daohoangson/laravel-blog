'use strict';

var cachedEntries = null;

angular.module('entryCtrl', ['entryService']).controller('EntryController', function($scope, $routeParams, $http, $location, Entry)
{
    $scope.entries = null;
    $scope.totalItems = 0;
    $scope.itemsPerPage = 10;
    $scope.currentPage = 1;

    $scope.entry = null;

    $scope.changePage = function()
    {
        var $entriesPromise = Entry.all($scope.currentPage);
        $entriesPromise.success(function(data)
        {
            $scope.entries = data.data;
            $scope.totalItems = data.total;
            $scope.itemsPerPage = data.per_page;
            $scope.currentPage = data.current_page;

            cachedEntries = data.data;
        });
    };

    $scope.view = function($event, entryId)
    {
        $location.path('/entries/' + entryId);

        $event.preventDefault();
    };

    $scope.edit = function($event, entryId)
    {
        $location.path('/entries/' + entryId + '/edit');

        $event.preventDefault();
    };

    $scope.delete = function($event, entryId)
    {
        console.log('delete', entryId);

        $event.preventDefault();
    };

    if ($routeParams.id > 0)
    {
        $scope.entry = null;

        // try to get entry from cachedEntries
        for (var i in cachedEntries)
        {
            if (cachedEntries[i].id == $routeParams.id)
            {
               $scope.entry = cachedEntries[i];
            }
        }

        if ($scope.entry === null)
        {
            // fetch it from server then...
            Entry.get($routeParams.id).success(function(data)
            {
                $scope.entry = data.entry;
            });
        }
    }
    else
    {
        // /entries page, load the first page
        if ($scope.entries === null)
        {
            $scope.changePage();
        }
    }
});
