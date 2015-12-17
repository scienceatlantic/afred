'use strict';

angular.module('afredApp').run(['$rootScope',
                                '$state',
                                '$stateParams',
                                '$http',
                                '$resource',
  function($rootScope,
           $state,
           $stateParams,
           $http,
           $resource) {
    $rootScope._config = {
      api: '//localhost:8000',
      log: true
    };
    
    $http.get($rootScope._config.api + '/csrf').then(function(response) {
      $http.defaults.headers.common['X-CSRF-TOKEN'] = response.data;
    });
    
    $rootScope._log = function(msg) {
      if ($rootScope._config.log) {
        try {
          console.log(msg);
          /*if (typeof msg === 'object') {
            console.log(msg);
          } else {
            var date = (new Date()).toString();
            console.log(date + ': ' + msg);
          }*/
          
        } catch(e) {
          // Do nothing.
        }
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