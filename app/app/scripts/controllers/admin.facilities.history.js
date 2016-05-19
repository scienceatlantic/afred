'use strict';

angular.module('afredApp').controller('AdminFacilitiesHistoryController', [
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
    $scope.history = {
      /**
       * All form related functions/data. In this case only the page number.
       * 
       * @type {object}
       */
      form: {
        data: {
            page: null
        }
      },
      
      /**
       * Holds the data returned from 'facilityRepositoryResource'.
       * 
       * @type {resource}
       */
      resource: {},
      
      /**
       * Parses the URL parameters.
       *
       * Side effects:
       * $scope.history.form.data.page Parsed page number data is attached
       *     to this.
       * 
       * Uses/calls/requires:
       * $scope.history.form.data.page
       * 
       */
      parseParams: function() {
        var page = null;
        try {
          if (isFinite(parseInt($scope._stateParams.page))) {
            page = parseInt($scope._stateParams.page);
          }
        } catch (e) {
          page = 1;
        }
        $scope.history.form.data.page = page;
      },

      /**
       * Query revision history data.
       *
       * Side effects:
       * $scope.history.resource Data returned is attached to this.
       *
       * Uses/calls/requires:
       * facilityRepositoryResource
       * $scope.history.form.data.page
       */
      query: function() {
        $scope.history.resource = facilityRepositoryResource.query({
          facilityId: $scope._stateParams.facilityId,
          page: $scope.history.form.data.page,
          itemsPerPage: 5
        });
      }
    }; 
  }
]);
