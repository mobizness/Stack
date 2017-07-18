'use strict';

var app = angular.module('myApp.menu.services', ['ngResource',]);

app.factory('menu', ['$location', '$rootScope', function ($location, $rootScope) {

  var sections = [{
  }];

  var self;

  return self = {
    sections: sections,
    isOpen: true,

    toggleSelectSection: function (section) {
      self.openedSection = (self.openedSection === section ? null : section);
    },
    isSectionSelected: function (section) {
      return self.openedSection === section;
    }
  };

}]);

app.factory('showToast', ['$mdToast', function ($mdToast) {

  function t(msg) {
    var toast = $mdToast.simple()
    .textContent(msg)
    .action('Close')
    .highlightAction(true)
    .hideDelay(3000);
    $mdToast.show(toast);
  }

  return t;

}]);

app.factory('showErrors', ['$mdBottomSheet', 'gettextCatalog', function ($mdBottomSheet, gettextCatalog) {

  // Serious tidy in order //
  // Should handle all the errors gracefully. It doesn't //

  var formatErrors = function(errors) {
    var e = [];
    var err, res;
    if (errors.data && errors.data.errors) {
      res = errors.data.errors;
    }
    if (res) {
      var keys = Object.keys(res);
      if (keys && keys[0] === '0') {
        for (var i = 0; i < res.length; i++) {
          e.push(res[i]);
        }
      } else if (keys) {
        angular.forEach(keys, function(v,k) {
          e.push(v.split('_').join(' ')  + ' ' + res[v]);
        });
      }
    } else if (errors.data && errors.data.message) {
      // This should loop through the array and remove the 'keys'
      // The keys don't actually work though since Rails sends full messages
      e.push(errors.data.message);
    } else if (errors && errors.data) {
      console.log(errors);
      err = errors.data || gettextCatalog.getString('An unknown error occurred, try again.');
      e.push(err);
    } else if (errors && errors.message) {
      err = errors.message;
      e.push(err);
    } else {
      console.log(errors);
      e.push(gettextCatalog.getString('An unknown error occurred, please try again.'));
    }
    if (e.length > 0) {
      return e[0];
    } else {
      return e;
    }
  };

  function MenuCtrl($scope, errors) {
    $scope.errors = errors;
    $scope.close = function() {
      $mdBottomSheet.hide();
    };
  }
  MenuCtrl.$inject = ['$scope','errors'];

  function t(msg) {
    var errors = formatErrors(msg);
    if (errors && errors.length > 0) {
      $mdBottomSheet.show({
        template:
          '<md-bottom-sheet class="md-list md-has-header" ng-cloak>'+
          '<md-subheader class="md-warn">There was a problem processing your request.'+
          '<ul ng-repeat="error in errors" class="errors"><li>{{ error }}</li></ul>'+
          '</md-subheader>'+
          '<md-button ng-click="close()" class="md-list-item-content" >'+
          '<span class="md-inline-list-icon-label md-warn">Close</span>'+
          '</md-button>'+
          '</md-bottom-sheet>',
        locals: {
          errors: errors
        },
        controller: MenuCtrl,
        clickOutsideToClose: true
      });

    }
  }

  return t;

}]);

