'use strict';

angular.module('afredApp').controller('AdminFacilitiesHistoryController', [
  '$scope',
  'RepositoryResource',
  function($scope,
           RepositoryResource) {
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
       * Holds the data returned from `RepositoryResource`.
       * 
       * @type {resource}
       */
      resource: {},
      
      /**
       * Parses the URL parameters.
       *
       * @sideffect $scope.history.form.data.page Parsed page number data is 
       *     attached to this.
       * 
       * @requires $scope.history.form.data.page
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
       * @sideeffect $scope.history.resource Data returned is attached to this.
       *
       * @requires $scope._httpError403()
       * @requires $scope.history.form.data.page
       * @requires RepositoryResource
       */
      query: function() {
        $scope.history.resource = RepositoryResource.query({
          facilityId: $scope._stateParams.facilityId,
          page: $scope.history.form.data.page,
          itemsPerPage: 5
        }, function() {
          // Do nothing if successful.
        }, function(response) {
          $scope._httpError403(response);
        });
      }
    }; 
  }
]);
