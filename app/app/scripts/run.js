'use strict';

angular.module('afredApp').run(['$rootScope',
                                '$log',
                                '$state',
                                '$stateParams',
                                '$http',
                                '$cookies',
                                '$window',
                                '$resource',
  function($rootScope,
           $log,
           $state,
           $stateParams,
           $http,
           $cookies,
           $window,
           $resource) {
    $http.get($rootScope._config.api.address + '/csrf').then(
      function(response) {
        $http.defaults.headers.common['X-CSRF-TOKEN'] = response.data;
      }
    );
    
    /* ---------------------------------------------------------------------
     * Log functions.
     * --------------------------------------------------------------------- */
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
    
    /* ---------------------------------------------------------------------
     * State properties.
     * --------------------------------------------------------------------- */

    $rootScope._state = $state;
    $rootScope._stateParams = $stateParams;
    
    /* ---------------------------------------------------------------------
     * Authentication functions
     * --------------------------------------------------------------------- */

    $rootScope._auth = {
      /**
       *
       */
      user: {},
      
      /**
       *
       */
      resolved: false,
      
      /**
       *
       */
      login: function(credentials) {
        return $http.post($rootScope._config.api.address + '/auth/login',
          credentials);
      },
      
      /**
       *
       */
      logout: function() {
        $http.get($rootScope._config.api.address + '/auth/logout').then(
          function() {
            $rootScope._auth.user = {};
            $state.go('login');
          }
        );
      },
      
      /**
       *
       */
      ping: function() {
        return $http.get($rootScope._config.api.address + '/auth/ping');
      },
      
      /**
       *
       */
      save: function(data) {
        $rootScope._auth.user = data;
        angular.forEach(data.roles, function(role) {
          if (role.name == 'Admin') {
            $rootScope._auth.user.isAdmin = true;
          }
        });
      },
    };
    
    $rootScope._auth.ping().then(function(response) {
      $rootScope._auth.save(response.data);
      $rootScope._auth.resolved = true;
    }, function() {
      $rootScope._auth.resolved = true;
    });
    
    /* ---------------------------------------------------------------------
     * Helper functions
     * --------------------------------------------------------------------- */
    $rootScope._helper = {
      // See: http://stackoverflow.com/questions/1038727/how-to-get-browser-width-using-javascript-code
      getWidth: function () {
        if (self.innerHeight) {
          return self.innerWidth;
        }
      
        if (document.documentElement && document.documentElement.clientWidth) {
          return document.documentElement.clientWidth;
        }
      
        if (document.body) {
          return document.body.clientWidth;
        }
      },
      
      getHeight: function() {
        if (self.innerHeight) {
          return self.innerHeight;
        }
      
        if (document.documentElement && document.documentElement.clientHeight) {
          return document.documentElement.clientHeight;
        }
      
        if (document.body) {
          return document.body.clientHeight;
        }
      }
    };
    
    /* ---------------------------------------------------------------------
     * Window properties
     * --------------------------------------------------------------------- */
    window.onresize = function() {
      $rootScope.$apply();
    };
    
    $rootScope._window = $window;
    $rootScope._location = location;
    
    /* ---------------------------------------------------------------------
     * Boostrap contstants.
     * --------------------------------------------------------------------- */
    $rootScope._bootstrap = {
      grid: {
        breakpoints: {
          sm: 768, // >=
          md: 992, // >=
          lg: 1200 // >=
        }
      }
    };
  }
]);
