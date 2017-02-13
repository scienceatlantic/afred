'use strict';

/**
 * @fileoverview Admin/Facilities page.
 * 
 * @see https://docs.angularjs.org/guide/controller
 * @see /scripts/routes.js (for route info)
 */

angular.module('afredApp').controller('AdminFacilitiesController',
  ['$scope',
   'RepositoryResource',
  function($scope,
           RepositoryResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * State class.
     */
    $scope.facilities = {
      /**
       * Holds the promise returned from '$scope.facilities.get()'.
       * 
       * @type {promise}
       */
      fr: {},
      
      /**
       * Form related objects/functions.
       *
       * @type {object}
       */
      form: {
        /**
         * Stores all form data.
         */
        data: {
          state: null, // Facility repository state.
          page: null, // Page number (pagination).
          visibility: null
        },
        
        /**
         * Clears the form.
         *
         * @sideeffect $scope.facilities.form.data All properties are set to 
         *     null.
         */
        clear: function() {
          $scope.facilities.form.data.state = null,
          $scope.facilities.form.data.page = null,
          $scope.facilities.form.data.visibility = null          
        }
      },
      
      /**
       * Redirects to the results page.
       *
       * @sideeffect $scope.facilities.form.data.page See @param.
       * @sideeffect $scope.facilities.form.data.visibility If 
       *     `resetPage` = true, and `$scope.facilities.form.data.state` 
       *     = 'PUBLISHED', it is set to 1, otherwise null.
       *
       * @requires $scope._state.go()
       * @requires $scope.facilities.form.data
       *
       * @param {boolean} resetPage If true, the page number is reset to 1.
       */
      index: function(resetPage) {
        if (resetPage) {
          $scope.facilities.form.data.page = 1;
          
          // If we're viewing PUBLISHED facilities, set visibility to 1. This
          // means that public facilities are the default view.
          if ($scope.facilities.form.data.state == 'PUBLISHED') {
            $scope.facilities.form.data.visibility = 1;
          } else {
            $scope.facilities.form.data.visibility = null;
          }
        }
        
        $scope._state.go('admin.facilities.index', $scope.facilities.form.data);
      },
      
      /**
       * Parses the parameters. To be used by a child state.
       *
       * @sideeffect $scope.facilities.form.data.page Page number is updated to match value
       *     retrieved from the URL if it is valid.
       * @sideffect $scope.facilities.form.data.state State is updated to match
       *     the value retrieved from the URL if it is valid.
       * @sideeffect $scope.facilities.form.data.visibility If 
       *     `$scope._stateParams.state` = PUBLISHED, visibility is updated to
       *     match value retrieved from the URL if it is valid. If invalid, gets
       *     set to 1. If state is not PUBLISHED, visibility is set to null.
       *
       * @requires $scope._state.go()
       * @requires $scope._stateParams
       */
      parseParams: function() {
        var state = null;
        var page = null;
        var visibility = null;
        
        try {
          state = $scope._stateParams.state.toUpperCase();
        } catch(e) {
          // Do nothing.
        }
        
        try {
          page = parseInt($scope._stateParams.page);
        } catch(e) {
          page = 1;
        }
        
        try {
          visibility = parseInt($scope._stateParams.visibility) == 1 ? 1 : 0;
        } catch(e) {
          visibility = 1;
        }
        
        switch (state) {
          case 'PUBLISHED':
            $scope.facilities.form.data.visibility = visibility;
            $scope.facilities.form.data.state = state;
            $scope.facilities.form.data.page = page;
            break;
          
          case 'PENDING_APPROVAL':
          case 'REJECTED':
          case 'DELETED':
            $scope.facilities.form.data.state = state;
            $scope.facilities.form.data.page = page;
            $scope.facilities.form.data.visibility = null;
            break;
          
          default:
            $scope.facilities.form.data.state = null;
            $scope._state.go('admin.facilities');
        }
      },
      
      /**
       * Retrieves facility repository data from the API.
       *
       * @sideeffect $scope.facilities.fr Promise object is attached to this.
       *
       * @requires $scope._httpError403()
       * @requires $scope.facility.form.data.page
       * @requires $scope.facility.form.data.state
       * @requires RepositoryResource
       */
      query: function() {
        $scope.facilities.fr = RepositoryResource.query({
          page: $scope.facilities.form.data.page,
          itemsPerPage: 10,
          state: $scope.facilities.form.data.state,
          visibility: $scope.facilities.form.data.visibility
        }, function() {
          // Do nothing if successful.
        }, function(response) {
          $scope._httpError403(response);
        });
      }
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    // Remember that angular ui router does not re-instantiate parent
    // controllers, so clear the form data if we're returning (e.g. browser
    // history) from a child state.
    $scope.$on('$stateChangeStart',
      function(event, toState, toParams, fromState, fromParams, options) {
        if (fromState.name === 'admin.facilities.index') {
          $scope.facilities.form.clear();
        }
      }
    );
  }
]);
