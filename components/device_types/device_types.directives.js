'use strict';

var app = angular.module('myApp.device_types.directives', []);

app.directive('listDeviceTypes', ['DeviceType', '$routeParams', '$location', '$rootScope', 'Auth', '$pusher', 'showErrors', 'showToast', '$mdDialog', 'gettextCatalog', 'menu', 'pagination_labels', function(DeviceType, $routeParams, $location, $rootScope, Auth, $pusher, showErrors, showToast, $mdDialog, gettextCatalog, menu, pagination_labels) {

  var link = function(scope) {

    menu.isOpen = false;
    menu.hideBurger = true;
    menu.sectionName = gettextCatalog.getString('Device Types');

    scope.options = {
      boundaryLinks: false,
      largeEditDialog: false,
      pageSelector: false,
      rowSelection: false
    };

    scope.pagination_labels = pagination_labels;
    scope.query = {
      order:      'updated_at',
      filter:     $routeParams.q,
      limit:      $routeParams.per || 25,
      page:       $routeParams.page || 1,
      options:    [5,10,25,50,100],
      direction:  $routeParams.direction || 'desc'
    };

    var createMenu = function() {
      scope.menu = [];
      scope.menu.push({
        name: gettextCatalog.getString('Edit'),
        icon: 'edit',
        type: 'edit'
      });
      scope.menu.push({
        name: gettextCatalog.getString('Delete'),
        icon: 'delete_forever',
        type: 'delete'
      });
    };

    scope.action = function(gp,type) {
      switch(type) {
        case 'delete':
          destroy(gp.id);
          break;
        case 'edit':
          scope.update(gp);
          break;
      }
    };

    scope.update = function(gp) {
      $mdDialog.show({
        clickOutsideToClose: true,
        templateUrl: 'components/views/device_types/_edit.html',
        parent: angular.element(document.body),
        controller: DialogController,
        locals: {
          layers: scope.layers,
          device_types: scope.device_types,
          gp: gp
        }
      });
    };

    function DialogController ($scope, layers, device_types, gp) {
      if (gp && gp.id) {
        $scope.gp = gp;
      }
      $scope.layers = layers;
      $scope.device_types = device_types;
      $scope.close = function() {
        $mdDialog.cancel();
      };
      $scope.save = function(zone) {
        $mdDialog.cancel();
        scope.createUpdate($scope.gp);
      };
    }
    DialogController.$inject = ['$scope', 'layers', 'device_types', 'gp'];

    var update = function(data) {
      DeviceType.update({
        id: data.id,
        device_type: data
      }).$promise.then(function(results) {
        for (var i = 0, len = scope.device_types.length; i < len; i++) {
          if (scope.device_types[i].id === results.id) {
            scope.device_types[i] = results;
            break;
          }
        }
        showToast(gettextCatalog.getString('Device Type successfully updated.'));
        scope.creating = undefined;
      }, function(error) {
        scope.creating = undefined;
        showErrors(error);
      });
    };

    scope.createUpdate = function(data) {
      scope.creating = true;
      if (data.id) {
        update(data);
      }
    };

    var removeFromList = function(id) {
      for (var i = 0, len = scope.device_types.length; i < len; i++) {
        if (scope.device_types[i].id === id) {
          scope.device_types.splice(i, 1);
          break;
        }
      }
    };

    var destroy = function(id) {
      var confirm = $mdDialog.confirm()
      .title(gettextCatalog.getString('Delete Filter'))
      .textContent(gettextCatalog.getString('Are you sure you want to delete this device type?'))
      .ariaLabel(gettextCatalog.getString('Delete Device Type'))
      .ok(gettextCatalog.getString('Delete'))
      .cancel(gettextCatalog.getString('Cancel'));
      $mdDialog.show(confirm).then(function() {
        scope.destroy(id);
      }, function() {
      });
    };

    scope.destroy = function(id) {
      DeviceType.destroy({
        id: id
      }).$promise.then(function(results) {
        removeFromList(id);
        showToast(gettextCatalog.getString('Successfully deleted device type'));
      }, function(err) {
        showErrors(err);
      });
    };

    var init = function() {
      DeviceType.query({}).$promise.then(function(results) {
        scope.device_types  = results.device_types;
        scope._links  = results._links;
        scope.loading = undefined;
        createMenu();
      }, function(err) {
        console.log(err);
        // scope.loading = undefined;
      });
    };

    init();

  };

  return {
    link: link,
    scope: {
      loading: '='
    },
    templateUrl: 'components/views/device_types/_index.html'
  };

}]);

app.directive('newDeviceTypeForm', ['DeviceType', '$routeParams', '$location', '$rootScope', 'Auth', '$pusher', 'showErrors', 'showToast', '$mdDialog', 'gettextCatalog', 'menu', 'pagination_labels', function(DeviceType, $routeParams, $location, $rootScope, Auth, $pusher, showErrors, showToast, $mdDialog, gettextCatalog, menu, pagination_labels) {

  var link = function( scope, element, attrs ) {

    scope.loading = true;
    menu.isOpen = false;
    menu.hideBurger = true;

    scope.save = function(form, device_type) {
      form.$setPristine();
      scope.device_type.creating = true;
      updateCT(device_type);
    };

    var updateCT = function(device_type) {
      DeviceType.save({
        device_type: device_type,
      }).$promise.then(function(results) {
        scope.device_type = results;
        showToast(gettextCatalog.getString('Device Type successfully created.'));
        window.location.href = '/device-types';
      }, function(err) {
        showErrors(err);
      });
    };

    scope.back = function() {
      window.location.href = '/device-types';
    };

    var init = function() {
      scope.loading = undefined;
    };

    init();
  };

  return {
    link: link,
    restrict: 'E',
    scope: {
      // accountId: '@'
    },
    templateUrl: 'components/views/device_types/new/_index.html'
  };

}]);

app.directive('deviceType', ['DeviceType', '$routeParams', '$location', '$rootScope', 'Auth', '$pusher', 'showErrors', 'showToast', '$mdDialog', 'gettextCatalog', 'menu', 'pagination_labels', function(DeviceType, $routeParams, $location, $rootScope, Auth, $pusher, showErrors, showToast, $mdDialog, gettextCatalog, menu, pagination_labels) {

  var link = function(scope) {

    scope.locations = ['eu-west', 'us-central', 'us-west', 'asia-east'];
    scope.locales = [{key: 'Deutsch', value: 'de-DE'}, { key: 'English', value: 'en-GB'}];

    var init = function() {
      DeviceType.get({id: $routeParams.id}).$promise.then(function(results) {
        scope.device_type = results;
        menu.header = results.name;
        scope.loading = undefined;
      }, function(err) {
        console.log(err);
        // scope.loading = undefined;
      });
    };

    var update = function() {
      DeviceType.update({},
        {
          id: scope.device_type.id,
          device_type: scope.device_type
        }).$promise.then(function(results) {
          scope.device_type    = results;
          // scope.errors      = undefined;
          // scope.updating    = undefined;
          // scope.updateBrand = undefined;
          showToast(gettextCatalog.getString('Successfully updated device type'));
        }, function(err) {
          showErrors(err);
        });
    };

    var confirmChange = function() {
      var confirm = $mdDialog.confirm()
      .title(gettextCatalog.getString('Confirm Update'))
      .textContent(gettextCatalog.getString('You may need to resync your boxes after updating your device type.'))
      .ariaLabel(gettextCatalog.getString('Change'))
      .ok(gettextCatalog.getString('Change'))
      .cancel(gettextCatalog.getString('Cancel'));
      $mdDialog.show(confirm).then(function() {
        update();
      }, function() {
      });
    };

    scope.save = function(form) {
      form.$setPristine();
      confirmChange();
    };

    init();
  };

  return {
    link: link,
    scope: {
      loading: '='
    },
    templateUrl: 'components/views/device_types/_show.html'
  };

}]);
