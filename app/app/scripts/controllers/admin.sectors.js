'use strict';

angular.module('afredApp').controller('AdminSectorsController', [
  '$scope',
  'SectorResource',
  function($scope,
           SectorResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * State class.
     */
    $scope.sectors = {
      /**
       * All form related functions/data. In this case only the page number.
       * @type {object}
       */
      form: {
        data: {
            page: null
        }
      },
      
      /**
       * Holds the data returned from 'SectorResource'.
       * @type {resource}
       */
      resource: {},
      
      /**
       * Parses the URL parameters.
       *
       * Side effects:
       * $scope.sectors.form.data.page Parsed page number data is attached
       *     to this.
       * 
       * Uses/calls/requires:
       * $scope.sectors.form.data.page
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
        $scope.sectors.form.data.page = page;
      },
      
      /**
       * Query sector data.
       *
       * Side effects:
       * $scope.sector.resource Data returned is attached to this.
       *
       * Uses/calls/requires:
       * SectorResource
       * $scope.sectors.form.data.page
       * $scope._httpError403()
       */
      query: function() {
        $scope.sectors.resource = SectorResource.query({
          page: $scope.sectors.form.data.page,
          itemsPerPage: 10
        }, function() {
          // Do nothing if successful.
        }, function(response) {
          $scope._httpError403(response);
        });
      }
    }; 
  }
]);
