'use strict';

angular.module('afredApp').controller('AdminFacilitiesController',
  ['$scope',
   'facilityRepositoryResource',
  function($scope,
           facilityRepositoryResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * State class.
     */
    $scope.facilities = {
      /**
       * Holds the promise returned from '$scope.facilities.get()'.
       * @type {promise}
       */
      fr: {},
      
      /**
       * Holds all form data.
       * @type
       */
      form: {
        data: {
          state: null, // Facility repository state.
          page: null, // Page number (pagination).
          visibility: null
        }
      },
      
      /**
       * Redirects to the results page.
       *
       * Side effects:
       * $scope.facilities.form.data.page See @param.
       * $scope.facilities.form.data.visibility If 'resetPage' = true,
       *     and '$scope.facilities.form.data.state' = PUBLISHED, it is set
       *     to 1, otherwise null.
       *
       * Calls/uses/requires:
       * $scope._state.go()
       * $scope.facilities.form.data
       *
       * @param {boolean} resetPage If true, the page number is reset to 1.
       */
      goToResultsPage: function(resetPage) {
        if (resetPage) {
          $scope.facilities.form.data.page = 1;
          
          if ($scope.facilities.form.data.state == 'PUBLISHED') {
            $scope.facilities.form.data.visibility = 1;
          } else {
            $scope.facilities.form.data.visibility = null;
          }
        }
        
        $scope._state.go('admin.facilities.state', $scope.facilities.form.data);
      },
      
      /**
       * Parses the parameters.
       *
       * Side effects:
       * $scope.facilities.form.data.state State is updated to match the value
       *     retrieved from the URL if it is valid.
       * $scope.facilities.form.data.page Page number is updated to match value
       *     retrieved from the URL if it is valid.
       * $scope.facilities.form.data.visibility If '$scope._stateParams.state'
       *     = PUBLISHED, visibility is updated to match value retrieveed from
       *     the URL if it is valid. If invalid, gets set to 1. If state is not
       *     PUBLISHED, visibility is set to null.
       *
       * Calls/uses/requires:
       * $scope._state.go()
       * $scope._stateParams
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
       * Side effects:
       * $scope.facilities.fr Promise object is attached to this.
       *
       * Uses/calls/requires:
       * facilityRepositoryResource
       * $scope.facility.form.data.page
       * $scope.facility.form.data.state
       */
      get: function() {
        $scope.facilities.fr = facilityRepositoryResource.query({
          page: $scope.facilities.form.data.page,
          itemsPerPage: 10,
          state: $scope.facilities.form.data.state,
          visibility: $scope.facilities.form.data.visibility
        });
      }
    };    
  }
]);