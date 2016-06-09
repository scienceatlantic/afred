'use strict';

/** 
 * @fileoverview Contains code that should be run on app load. It will mostly
 * contain global functions/properties (attached to the $rootScope).
 * Functions/properties attached to $rootScope should be prefixed with an
 * underscore (not a requirement, just so it can be easily identified and
 * won't be overwritten by similarly name functions/properties attached
 * to the $scope).
 */

angular.module('afredApp').run(['$rootScope',
                                '$log',
                                '$state',
                                '$stateParams',
                                '$http',
  function($rootScope,
           $log,
           $state,
           $stateParams,
           $http) {
    
    /* ---------------------------------------------------------------------
     * Log functions. Making it globally accessble.
     * See this:
     * https://docs.angularjs.org/api/ng/service/$log
     * for an explanation of the differences between log, info, warn, and error.
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
     * Note: We're just attaching angular ui router's '$state' and
     * '$stateParams' to the $rootScope so that it is globally accessible.
     * --------------------------------------------------------------------- */

    $rootScope._state = $state;
    $rootScope._stateParams = $stateParams;
    
    /* ---------------------------------------------------------------------
     * Authentication functions
     * --------------------------------------------------------------------- */

    $rootScope._auth = {
      /**
       * Authenticated user's details are stored here.
       *
       * @type {object}
       */
      user: {},
      
      /**
       * Login function.
       *
       * @param {object} credentials Must contain an 'email' property and a
       *     'password' property.
       */
      login: function(credentials) {
        return $http.post($rootScope._config.api.address + '/auth/login',
          credentials);
      },
      
      /**
       * Logout function.
       */
      logout: function() {
        $http.get($rootScope._config.api.address + '/auth/logout').then(
          function() {
            $rootScope._auth.destroy(true);
          }
        );
      },
      
      /**
       * Can be used to 'ping' the API's auth controller to check if a user is
       * already authenticated.
       *
       * @return {promise}
       */
      ping: function() {
        return $http.get($rootScope._config.api.address + '/auth/ping');
      },
      
      /**
       * Saves an authenticated user's details.
       *
       * Side effects:
       * $rootScope._auth.user Authenticated user's details are stored here.
       *
       * @param {object} Response from the API.
       * @return {bool} True if successful (response contains authenticated
       *     user's details), false otherwise.
       */
      save: function(response) {
        // Depending on the angular function used ($http or $resource) the data
        // returned from the API could either be stored in 'response' or in a
        // property 'response.data'.
        var resp = response.data ? response.data : response;
        
        // We're going to assume that if the 'firstName' propery is set, the
        // user successfully logged in.
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
      
      /**
       * Clears the authenticated user's details.
       *
       * Side effects:
       * $rootScope._auth.user Set to empty object.
       *
       * @param {boolean} redirectToLogin If set to true, user will be
       *     redirected to the login page.
       */
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
    
    /* ---------------------------------------------------------------------
     * Error state function.
     * --------------------------------------------------------------------- */
    
    /**
     * Redirect the user to an error state. Currently, only two error states are
     * supported, 404s and 500s. The default is 500.
     *
     * Calls/uses/requires:
     * $rootScope._state.go()
     *
     * @param {object|string} response If using a string, state the error code
     *     (e.g. response='404', response='500', etc). If it's an object, use
     *     the response from the API.
     */
    $rootScope._httpError = function(response) {
      var statusCode = angular.isObject(response) ? response.status : response;
      
      switch (statusCode) {
        case 403:
        case '403':
          $rootScope._state.go('login');
          break;
        
        case 404:
        case 500:
        case '404':
        case '500':
          $rootScope._state.go('error.' + statusCode);
          break;
        
        default:
          $rootScope._state.go('error.500');
      }
    };
    
    /* ---------------------------------------------------------------------
     * Helper functions
     * --------------------------------------------------------------------- */
    $rootScope._helper = {
      // Credit for the first two functions, see:
      // http://stackoverflow.com/questions/1038727/how-to-get-browser-width-using-javascript-code
      
      /**
       * Get window width.
       *
       * @return {integer}
       */
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
      
      /**
       * Get window height.
       *
       * @return {integer}
       */
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
     * Window properties. Making 'window' and 'location' accessible via
     * '$rootScope'. Also making angular run a digest loop if the window is
     * resized (to keep width and height properties up-to-date).
     * --------------------------------------------------------------------- */
    window.onresize = function() {
      $rootScope.$apply();
    };
    
    $rootScope._window = window;
    $rootScope._location = location;
    
    /* ---------------------------------------------------------------------
     * Add the Math class to the global scope.
     * --------------------------------------------------------------------- */
    $rootScope._math = Math;
    
    /* ---------------------------------------------------------------------
     * Boostrap contstants.
     * See: http://getbootstrap.com
     * --------------------------------------------------------------------- */
    $rootScope._bootstrap = {
      /**
       * These are Bootstrap's grid's breakpoints (in pixels) (useful for responsive
       * design).
       */
      grid: {
        breakpoints: {
          sm: 768,
          md: 992, 
          lg: 1200 
        }
      }
    };

    /* ---------------------------------------------------------------------
     * Page titles.
     *
     * Will update the page titles to match the current state if the current
     * state has a 'pageTitle' property. See 'routes.js'.
     * --------------------------------------------------------------------- */
    
    // Save the unmodified page title first before making any changes.
    var upt = angular.element('title').text();
    
    // Update the page title on every successful state change.
    $rootScope.$on('$stateChangeSuccess', function() {
      var pt = $rootScope._state.current.data.pageTitle;
      angular.element('title').html(pt ? pt + ' | ' + upt : upt);
    });
  }
]);
