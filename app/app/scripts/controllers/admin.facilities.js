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
       *
       * Calls/uses/requires:
       * $scope._state.go()
       */
      parseParams: function() {
        var state = null;
        var page = null;
        try {
          state = $scope._stateParams.state.toUpperCase();
          page = parseInt($scope._stateParams.page);
        } catch(e) {
          page = 1;
        }
        
        switch (state) {
          case 'PENDING_APPROVAL':
          case 'PENDING_EDIT_APPROVAL':
          case 'REJECTED':
          case 'REJECTED_EDIT':
          case 'PUBLISHED':
            $scope.facilities.form.data.state = state;
            $scope.facilities.form.data.page = page;
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
          state: $scope.facilities.form.data.state        
        });
      }
    };    
  }
]);