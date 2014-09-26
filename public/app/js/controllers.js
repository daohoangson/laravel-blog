'use strict';

angular.module('entryCtrl', ['entryService']).controller('EntryController', function($scope, $http, $location, Entry)
{
    $scope.entries = [];
    $scope.totalItems = 0;
    $scope.itemsPerPage = 10;
    $scope.currentPage = 1;

    $scope.changePage = function()
    {
        var $entriesPromise = Entry.all($scope.currentPage);
        $entriesPromise.success(function(data)
        {
            $scope.entries = data.data;
            $scope.totalItems = data.total;
            $scope.itemsPerPage = data.per_page;
            $scope.currentPage = data.current_page;
        });
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
    }

    $scope.changePage();
});
