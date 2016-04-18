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
            $rootScope._auth.destroy(true);
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
      save: function(response) {
        var resp = response.data ? response.data : response;
        
        if (resp.firstName) {
          $rootScope._auth.user = resp;
          
          angular.forEach(resp.roles, function(role) {
            if (role.name == 'Admin') {
              $rootScope._auth.user.isAdmin = true;
            }
          });
          
          return true;
        }
        
        return false;
      },
      
      destroy: function(redirectToLogin) {
        $rootScope._auth.user = {};
        
        if (redirectToLogin) {
          $rootScope._state.go('login');
        }
      }
    };
    
    // Try pinging on app load to check if user is already logged in.
    $rootScope._auth.ping().then(function(response) {
      $rootScope._auth.save(response);
    }, function() {
      // If the ping fails, redirect to 500?
    });
    
    
    
    //
    $rootScope._httpError = function(response) {
      var statusCode = angular.isObject(response) ? response.status : response;
      
      switch (statusCode) {
        case '404':
        case '500':
          $rootScope._state.go(statusCode);
          
        default:
          $rootScope._state.go('500');
      }
    };
    
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
    
    $rootScope._window = window;
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
