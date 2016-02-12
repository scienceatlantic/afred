'use strict';

angular.module('afredApp').run(['$rootScope',
                                '$log',
                                '$state',
                                '$stateParams',
                                '$http',
                                '$cookies',
                                '$resource',
  function($rootScope,
           $log,
           $state,
           $stateParams,
           $http,
           $cookies,
           $resource) {
    $http.get($rootScope._config.api + '/csrf').then(function(response) {
      $http.defaults.headers.common['X-CSRF-TOKEN'] = response.data;
    });
    
    $rootScope._log = function(msg) {
      if ($rootScope._config.log.log) {
        $log.log(msg);
      }
    };
    
    $rootScope._info = function(msg) {
      if ($rootScope._config.log.info) {
        $log.info(msg);
      }
    };
    
    $rootScope._warn = function(msg) {
      if ($rootScope._config.log.warn) {
        $log.warn(msg);
      }
    };
    
    $rootScope._error = function(msg) {
      if ($rootScope._config.log.error) {
        $log.error(msg);
      }
    };
    
    $rootScope._debug = function(msg) {
      if ($rootScope._config.log.debug) {
        $log.debug(msg);
      }
    };
    
    $rootScope._state = $state;
    $rootScope._stateParams = $stateParams;
    
    $rootScope._auth = {
      user: {},
      resolved: false,
      login: function(credentials) {
        return $http.post($rootScope._config.api + '/auth/login', credentials);
      },
      logout: function() {
        $http.get($rootScope._config.api + '/auth/logout').then(function() {
          $rootScope._auth.user = {};
          $state.go('login');
        });
      },
      ping: function() {
        return $http.get($rootScope._config.api + '/auth/ping');
      }
    };
    
    $rootScope._auth.ping().then(function(response) {
      $rootScope._auth.user = response.data;
      $rootScope._auth.resolved = true;
    }, function() {
      $rootScope._auth.resolved = true;
    });
  }
]);
