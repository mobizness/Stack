'use strict';

var app = angular.module('myApp.device_types.services', ['ngResource',]);

app.factory('DeviceTypeName', [function() {
  return { name: '', image: '', icon: '' };
}]);

app.factory('DeviceType', ['$resource', 'API_END_POINT',
  function($resource, API_END_POINT){
    return $resource(API_END_POINT + '/device-types/:id',
      {
        id: '@id'
      },
      {
      query: {
        method: 'GET',
        isArray: false,
        // cache: true,
        dataType: 'json',
        params: {
          id: '@id',
          cname: '@cname'
        }
      },
      get: {
        method: 'GET',
        isArray: false,
        dataType: 'json',
        params: {
          q: '@q'
        }
      },
      update: {
        method: 'PATCH'
      },
      create: {
        method: 'POST',
        params: {
          brand: '@device_type'
        }
      },
      destroy: {
        method: 'DELETE',
        isArray: false,
        params: {
          id: '@id'
        }
      }
    });
  }]);
