'use strict';

var cachedEntries = null;
var cachedUsers = null;

angular.module('mainCtrl', []).controller('MainController', function ($scope, $location) {
    $scope.go = function ($event, path) {
        $location.path(path);

        $event.preventDefault();
    };
});

angular.module('entryCtrl', ['entryService']).controller('EntryController', function ($scope, $routeParams, $location, Entry) {
    $scope.entries = null;
    $scope.totalItems = 0;
    $scope.itemsPerPage = 10;
    $scope.currentPage = 1;

    $scope.entry = null;
    $scope.formData = {
        delete: false,
        action: 'restore',
        errors: null
    };

    $scope.changePage = function () {
        var $entriesPromise = Entry.all($scope.currentPage);
        $entriesPromise.success(function (data) {
            $scope.entries = data.data;
            $scope.totalItems = data.total;
            $scope.itemsPerPage = data.per_page;
            $scope.currentPage = data.current_page;

            cachedEntries = data.data;
        });
    };

    $scope.view = function ($event, entryId) {
        $location.path('/entries/' + entryId);

        $event.preventDefault();
    };

    $scope.edit = function ($event, entryId) {
        $location.path('/entries/' + entryId + '/edit');

        $event.preventDefault();
    };

    $scope.save = function ($event) {
        var onSaved = function (data) {
            if (data.errors) {
                $scope.formData.errors = data.errors;
                return false;
            }

            if (data.entry) {
                cachedEntries = null;
                $location.path('/entries/' + data.entry.id);
            }
        };

        if ($scope.entry.id) {
            if ($scope.formData.delete) {
                // deleting
                Entry.destroy($scope.entry.id).success(function (data) {
                    if (data.success) {
                        cachedEntries = null;
                        $location.path('/entries');
                    }
                    else {
                        // handle errors and stuff
                        onSaved(data);
                    }
                });
            }
            else {
                // updating
                Entry.update($scope.entry).success(onSaved);
            }
        }
        else {
            // creating
            Entry.store($scope.entry).success(onSaved);
        }

        $event.preventDefault();
    };

    $scope.delete = function ($event, entryId) {
        $location.path('/entries/' + entryId + '/delete');

        $event.preventDefault();
    };

    $scope.hardDeleteOrRestore = function ($event) {
        var onDone = function (data) {
            cachedEntries = null;
            $location.path('/entries');
        };

        if ($scope.formData.action == 'hard_delete') {
            // hard deleting
            Entry.destroy($scope.entry.id, true).success(onDone);
        }
        else {
            // restoring
            var data = $scope.entry;
            data.restore = 1;

            Entry.update(data).success(onDone);
        }

        $event.preventDefault();
    };

    if ($routeParams.id > 0) {
        $scope.entry = null;

        // try to get entry from cachedEntries
        for (var i in cachedEntries) {
            if (cachedEntries[i].id == $routeParams.id) {
                $scope.entry = cachedEntries[i];
            }
        }

        if ($scope.entry === null) {
            // fetch it from server then...
            Entry.get($routeParams.id).success(function (data) {
                $scope.entry = data.entry;
            });
        }
    }
    else {
        // /entries page, load the first page
        if ($scope.entries === null) {
            $scope.changePage();
        }
    }
});

angular.module('userCtrl', ['userService']).controller('UserController', function ($scope, $routeParams, $location, User) {
    $scope.users = null;

    $scope.user = null;
    $scope.formData = {
        errors: null
    };

    $scope.view = function ($event, userId) {
        $location.path('/users/' + userId);

        $event.preventDefault();
    };

    $scope.edit = function ($event, userId) {
        $location.path('/users/' + userId + '/edit');

        $event.preventDefault();
    };

    $scope.save = function ($event) {
        var onSaved = function (data) {
            if (data.errors) {
                $scope.formData.errors = data.errors;
                return false;
            }

            if (data.user) {
                cachedUsers = null;
                $location.path('/users');
            }
        };

        var user = {};

        if ($scope.user.email) {
            user.email = $scope.user.email;
        }

        if ($scope.user.password) {
            user.password = $scope.user.password;
        }

        user.roles = [];
        for (var i in $scope.user.roles) {
            if ($scope.user.roles[i].isUserRole) {
                user.roles.push($scope.user.roles[i].id);
            }
        }

        if ($scope.user.id) {
            // updating
            user.id = $scope.user.id;
            User.update(user).success(onSaved);
        }
        else {
            // creating
            User.store(user).success(onSaved);
        }

        $event.preventDefault();
    };

    if ($routeParams.id > 0) {
        $scope.user = null;

        // try to get user from cachedUsers
        for (var i in cachedUsers) {
            if (cachedUsers[i].id == $routeParams.id) {
                $scope.user = cachedUsers[i];
            }
        }

        if ($scope.user === null) {
            // fetch it from server then...
            User.get($routeParams.id).success(function (data) {
                $scope.user = data.user;
            });
        }
    }
    else {
        // /users page
        User.all().success(function (data) {
            $scope.users = data.data;

            cachedUsers = data.data;
        });
    }
});
