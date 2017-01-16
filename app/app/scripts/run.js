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
                                '$location',
                                '$http',
  function($rootScope,
           $log,
           $state,
           $stateParams,
           $location,
           $http) {
    
    /* ---------------------------------------------------------------------
     * Google Analytics.
     * @see http://jasonwatmore.com/post/2015/11/07/AngularJS-Google-Analytics-with-the-UI-Router.aspx
     * --------------------------------------------------------------------- */

    window.ga('create', $rootScope._env.google.analytics.id, 'auto');
    
    $rootScope.$on('$stateChangeSuccess', function () {
      window.ga('send', 'pageview', $location.path());
    });
    
    /* ---------------------------------------------------------------------
     * Log functions. Making it globally accessible.
     * @see https://docs.angularjs.org/api/ng/service/$log
     * --------------------------------------------------------------------- */

    $rootScope._log = function(msg) {
      if ($rootScope._env.log.log) {
        $log.log(msg);
      }
    };
    
    $rootScope._info = function(msg) {
      if ($rootScope._env.log.info) {
        $log.info(msg);
      }
    };
    
    $rootScope._warn = function(msg) {
      if ($rootScope._env.log.warn) {
        $log.warn(msg);
      }
    };
    
    $rootScope._error = function(msg) {
      if ($rootScope._env.log.error) {
        $log.error(msg);
      }
    };
    
    $rootScope._debug = function(msg) {
      if ($rootScope._env.log.debug) {
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
     * Authentication functions.
     * --------------------------------------------------------------------- */

    $rootScope._auth = {
      /**
       * Authenticated user's details are stored here.
       *
       * @type {object}
       */
      user: {},
      
      /**
       * AJAX loading flags.
       *
       * @type {object}
       */
      loading: {
        logout: false
      },
      
      /**
       * Login function.
       *
       * @param {object} credentials Must contain an 'email' property and a
       *     'password' property.
       */
      login: function(credentials) {
        return $http.post($rootScope._env.api.address + '/auth/login',
          credentials);
      },
      
      /**
       * Logout function.
       *
       * Side effects:
       * $rootScope._auth.loading.logout Set to true at the start of the
       *     function and then set to false after the AJAX operation is
       *     complete.
       *
       * Calls/uses/requires:
       * $rootScope._env.api.address
       * $rootScope._auth.destroy()
       * $http
       */
      logout: function() {
        $rootScope._auth.loading.logout = true;
        
        $http.get($rootScope._env.api.address + '/auth/logout').then(
          function() {
            $rootScope._auth.loading.logout = false;
            $rootScope._auth.destroy(true);
          }, function() {  
            $rootScope._auth.loading.logout = false;
          }
        );
      },
      
      /**
       * Can be used to 'ping' the API's auth controller to check if a user is
       * already authenticated or redirect the user if they're not.
       * 
       * @uses $rootScope._auth.save()
       * @uses $rootScope._state.go()
       * @uses $rootScope._state.is()
       * @uses $http.get()
       *
       * @param {string} action
       *     Acceptable values are (default is 'save'):
       *     (1) 'redirect': What happens here depends on what state the user is
       *                     in. If the user is at the login page and this is
       *                     value is passed, the user will be redirected to the
       *                     admin dashboard if the ping returned user data 
       *                     (i.e. not logged in), otherwise nothing will 
       *                     happen. If the user is at any other page and this 
       *                     value is used, the user will be redirected to the 
       *                     login page if the ping did not return user data,
       *                     otherwise nothing will happen.
       * 
       *     (2) 'save': If the ping was successful (in the sense that it
       *                 returned data signifying that the user is logged in),
       *                 save the login info (uses the '$rootScope._auth.save()' 
       *                 to do so).
       * 
       *     (3) 'promise': After pinging the API, the promise is returned
       *                    directly.
       * 
       * @return {promise} 
       */
      ping: function(action) {
        var promise = $http.get($rootScope._env.api.address + '/auth/ping');

        promise.then(function(resp) {
          switch (action) {
            case 'promise':
              break;
            case 'redirect':
              // User is not logged and not at the login page, redirect to the
              // login page.
              if (!resp.data.id && !$rootScope._state.is('login')) {
                $rootScope._state.go('login');
              } 
              // User is logged in and at the login page, redirect to the admin 
              // dashboard.
              else if (resp.data.id && $rootScope._state.is('login')) {
                $rootScope._state.go('admin.dashboard');
              }
              break;
            case 'save':
              // No break.
            default:
              promise.then(function(resp) {
                $rootScope._auth.save(resp);
              });  
          }
        }, function() {
          // If call fails, do nothing.
        });

        return promise;
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
        // returned from the API could either be stored in 'response' or in
        // 'response.data'.
        var resp = response.data ? response.data : response;
        
        // We're going to assume that if the 'id' propery is set, the user
        // successfully logged in.
        if (resp.id) {
          $rootScope._auth.user = resp;
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
    
    // Try pinging API on app load to check if user is already logged in.
    $rootScope._auth.ping();
    
    /* ---------------------------------------------------------------------
     * Error state function.
     * --------------------------------------------------------------------- */

    /**
     * Redirect the user to an error state. The default is 500.
     *
     * Calls/uses/requires:
     * $rootScope._state.go()
     *
     * @param {object|string} response If using a string, state the error code
     *     (e.g. response='404', response='500', etc). If it's an object, use
     *     the response from the API.
     * @param {string} toState Note: this param has no purpose right now.
     * @param {string} fromState (Optional) A URL the user will be redirected to 
     *     if login was successful (only applies to the 403 case). This param is
     *     useful for cases where instead of showing the usual 403 unauthorised
     *     page we could instead redirect the user to the login page (if the
     *     reason why they can't view it is because they have not logged in).
     */
    $rootScope._httpError = function(response, toState, fromState) {
      var statusCode = angular.isObject(response) ? response.status : response;

      switch (String(statusCode)) {
        case '403':
          // If the user is not already logged in and the 'fromState' param
          // is set, redirect the user to the login page instead and the set
          // '$scope._persist.fromState' property to 'fromState' so that they can
          // be redirected back to it after login. We need to check that the
          // user has already logged in (first condition in the if statement)
          // because it could be that they have already logged in but are simply
          // not authorised (permission level) to view the content, if we didn't
          // do that, this would create an infinite redirect.
          if (!$rootScope._auth.user.id && fromState) {
            // Do not update the '$rootScope._persist.fromState' property if it's
            // a redirect to the login page. If there are multiple identical 
            // AJAX requests to redirect to the login page and one (or more)
            // calls are made after the app has already redirected to the login
            // page, this condition prevents the more recent calls from
            // overwriting the '$rootScope._persist.fromState' property to 
            // 'login' (i.e. losing the actual state the app was in). 
            if (!fromState.includes('login')) {
              $rootScope._persist.fromState = fromState;
            }
            $rootScope._state.go('login');
            break;
          }
        case '404':
        case '500':
        case '503':
          $rootScope._state.go('error.' + statusCode);
          break;
        
        default:
          $rootScope._state.go('error.500');
      }
    };

    /**
     * A shortcut method for '$rootScope._httpError()' method's 403 case. It
     * will call the '$rootScope._httpError()' method passing the response
     * param with the second and third params set to null and location.href
     * respectively.
     * 
     * @param {object|string} Either a '403' string or response from the API.
     */
    $rootScope._httpError403 = function(response) {
      $rootScope._httpError(response, null, location.href);
    };

    /* ---------------------------------------------------------------------
     * Form helper functions.
     * --------------------------------------------------------------------- */

    $rootScope._form = {
      /**
       * Checkbox class.
       */
      cb: {
        /**
         * Use together with the 'ngRequired' directive to ensure that the user
         * selects at least one checkbox in a form.
         * 
         * Goes through an entire array checking each element's 'isSelected' 
         * property. If at least one is selected, false is returned (i.e. the
         * user has selected at least one checkbox, validation should pass).
         * 
         * @param {array} items Array of elements to check.
         * @param {Angular FormController} formElement (Optional) Will set the
         *     '$dirty' property to true if at least one checkbox was selected.
         * @param {string} selectProp (Optional) The default is to look for the
         *     'isSelected' property of each array element, you can change that
         *     using this.
         * 
         * @return {boolean}
         */
        isRequired: function(items, formElement, selectProp) {
          selectProp = selectProp ? selectProp : 'isSelected'; // Set default.
          formElement = formElement ? formElement : {}; // Set default.

          for (var i = 0; i < items.length; i++) {
            if (items[i][selectProp]) {
              formElement.$dirty = true;
              return false;
            }
          }
          return true;
        },

        /**
         * Returns array of selected elements
         * 
         * Goes through an array checking the 'isSelected' property of each 
         * element. If the property is true, that element is added to an array
         * that is returned once it has checked all elements.
         * 
         * @param {array} items Array of elements to check.
         * @param {boolean} idOnly (Optional) Default is false. If set to true,
         *     will only add the array element's ID to the array that will
         *     eventually be returned.
         * @param {string} idProp (Optional) If the 'idOnly' property is set to
         *     true, by default it will look for an 'id' property in each array
         *     element, you can use this to change that.
         * @param {string} selectProp (Optional) The default is to look for the
         *     'isSelected' property of each array element, you can change that
         *     using this.
         * 
         * @return {array}
         */
        getSelected: function(items, idOnly, idProp, selectProp) {
          idProp = idProp ? idProp : 'id'; // Set default.
          selectProp = selectProp ? selectProp : 'isSelected'; // Set default.

          var selected = [];
          angular.forEach(items, function(item) {
            if (item[selectProp]) {
              selected.push(idOnly ? item[idProp] : item);
            }
          });
          return selected;          
        }
      } 
    };

    /* ---------------------------------------------------------------------
     * URL parameter parsing helper functions.
     * --------------------------------------------------------------------- */
    $rootScope._param = {
      /**
       * Runs `val` through `parseInt()` and then passes the parsed value into
       * `Number.isFinite()` and if that passes, `parseInt(val)` is returned,
       * otherwise `defaultVal` is returned.
       * 
       * @param {} val
       * @param {} defaultVal
       * @returns {number|}
       */
      toInt: function(val, defaultVal) {
        try {
          if (Number.isFinite(parseInt(val))) {
            return parseInt(val);
          }
        } catch(e) {
          // Do nothing.
        }
        return defaultVal;
      }
    };

    /* ---------------------------------------------------------------------
     * Make 'location' accessible to all scopes.
     * --------------------------------------------------------------------- */

    $rootScope._location = location;

    /* ---------------------------------------------------------------------
     * Helper functions
     * --------------------------------------------------------------------- */

    // Credit for the first two functions:
    // @see http://stackoverflow.com/a/1038781

    /**
     * Get window width.
     *
     * @return {integer}
     */
    $rootScope._getWidth = function () {
      if (self.innerHeight) {
        return self.innerWidth;
      }
    
      if (document.documentElement && document.documentElement.clientWidth) {
        return document.documentElement.clientWidth;
      }
    
      if (document.body) {
        return document.body.clientWidth;
      }
    };
      
    /**
     * Get window height.
     *
     * @return {integer}
     */
    $rootScope._getHeight = function() {
      if (self.innerHeight) {
        return self.innerHeight;
      }
  
      if (document.documentElement && document.documentElement.clientHeight) {
        return document.documentElement.clientHeight;
      }

      if (document.body) {
        return document.body.clientHeight;
      }
    };
    
    /* ---------------------------------------------------------------------
     * Boostrap constants.
     * @see http://getbootstrap.com
     * --------------------------------------------------------------------- */

    $rootScope._bootstrap = {
      /**
       * These are Bootstrap's grid's breakpoints (in pixels) (useful for 
       * responsive design).
       */
      sm: 768,
      md: 992, 
      lg: 1200 
    };


    /* ---------------------------------------------------------------------
     * Boostrap contstants.
     * 
     * The persist object can be used to store anything that needs to 
     * persisted across states.
     * --------------------------------------------------------------------- */
    $rootScope._persist = {
      reload: false, // Flag that tells the app to perform a hard reload.
      fromState: null, // If the user is not logged in and accesses protected
                       // content, this property stores that state so that we
                       // can redirect the user after login.
      facilitySubmissionFormId: null // The facility submission form is given
                                     // a unique ID so that we can prevents
                                     // duplicate 'autosave' instances.
    };


    /* ---------------------------------------------------------------------
     * Global events.
     * --------------------------------------------------------------------- */

    // Reload app if flag is set.
    $rootScope.$on('$stateChangeSuccess', function() {
      if ($rootScope._persist.reload) {
        $rootScope._location.reload();
      }
    });
  }
]);
