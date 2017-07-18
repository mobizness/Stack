'use strict';

var app = angular.module('myApp.access-token.services', ['ngResource', 'ngCookies']);

app.factory('RefreshToken', ['$q', '$http', function($q, $http) {

  var refresh = function(token) {
    var deferred = $q.defer();
    $http({
      method: 'GET',
      url: '/auth/refreshToken',
      params: {token: token}
    }).
    success(function(msg) {
      deferred.resolve(msg);
    }).
    error(function(err,a) {
      deferred.reject(err);
    });
    return deferred.promise;
  };

  return {
    refresh: refresh,
  };

}]);

app.factory('AccessToken', ['$cookies', '$q', function($cookies, $q) {

  var domain = function() {
    var uri = new window.URI(window.location.href);
    var host = uri.domain();
    //return '.' + host;
    return host;
  };

  var set = function(token) {
    $cookies.put('digifi_token', token);
    return true;
  };

  var del = function() {
    var d = domain();
    $cookies.remove('digifi_token');
  };

  var get = function() {
    var ls;

    //prefere url token to cookie
    var params = document.location.href.match(/[?&]token=([0-9a-f]+)/);
    if (params) {
      ls = params[1];
      set(ls);
    } else {
      ls = $cookies.get('digifi_token', {path: '/'});
    }

    return ls;
  };

  return {
    set: set,
    del: del,
    get: get,
  };

}]);
