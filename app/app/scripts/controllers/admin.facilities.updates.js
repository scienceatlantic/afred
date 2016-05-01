'use strict';

angular.module('afredApp').controller('AdminFacilitiesUpdatesController', [
  '$scope',
  'facilityRepositoryResource',
  function($scope,
           facilityRepositoryResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * State class.
     */
    $scope.facilities.updates = {
      /**
       * Holds the promise returned from '$scope.facilities.updates.get()'.
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
          status: null, // Facility update link statu.
          page: null, // Page number (pagination).
        },
        
        /**
         * Clears the form.
         *
         * Side effects:
         * $scope.facilities.updates.form.data All properties are set to null.
         */
        clear: function() {
          $scope.facilities.updates.form.data.status = null,
          $scope.facilities.updates.form.data.page = null        
        }
      },
      
      /**
       * Go to index state.
       *
       * Side effects:
       * $scope.facilities.updates.form.data.page See @param.
       *
       * Calls/uses/requires:
       * $scope._state.go()
       * $scope.facilities.updates.form.data
       *
       * @param {boolean} resetPage If true, the page number is reset to 1.
       */
      index: function(resetPage) {
        if (resetPage) {
          $scope.facilities.updates.form.data.page = 1;
        }
        
        $scope._state.go('admin.facilities.updates.index',
          $scope.facilities.updates.form.data);
      },
      
      /**
       * Parses the parameters. To be used by a child state.
       *
       * Side effects:
       * $scope.facilities.updates.form.data.status Status is updated to match the value
       *     retrieved from the URL if it is valid.
       * $scope.facilities.updates.form.data.page Page number is updated to match value
       *     retrieved from the URL if it is valid.
       *
       * Calls/uses/requires:
       * $scope._state.go()
       * $scope._stateParams
       */
      parseParams: function() {
        var status = null;
        var page = null;
        
        try {
          status = $scope._stateParams.status.toUpperCase();
        } catch(e) {
          // Do nothing.
        }
        
        try {
          page = parseInt($scope._stateParams.page);
          if (!isFinite(page)) {
            page = 1;
          }
        } catch(e) {
          page = 1;
        }
        
        switch (status) {
          case 'OPEN':
          case 'PENDING':
          case 'CLOSED':
            $scope.facilities.updates.form.data.status = status;
            $scope.facilities.updates.form.data.page = page;
            break;
          
          default:
            $scope.facilities.updates.form.data.status = null;
            $scope._state.go('admin.facilities.updates');
        }
      },
      
      /**
       * Retrieves facility repository data from the API.
       *
       * Side effects:
       * $scope.facilities.updates.fr Promise object is attached to this.
       *
       * Uses/calls/requires:
       * facilityRepositoryResource
       * $scope.facility.form.data.page
       * $scope.facility.form.data.status
       */
      query: function() {
        $scope.facilities.updates.fr = facilityRepositoryResource.queryTokens({
          page: $scope.facilities.updates.form.data.page,
          itemsPerPage: 10,
          status: $scope.facilities.updates.form.data.status
        });
      }
    };
  }
]);
