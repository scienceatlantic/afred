'use strict';

angular.module('afredApp').controller('AdminSectorsController', [
  '$scope',
  'sectorResource',
  function($scope,
           sectorResource) {
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
       * Holds the data returned from 'sectorResource'.
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
       * sectorResource
       * $scope.sectors.form.data.page
       */
      query: function() {
        $scope.sectors.resource = sectorResource.query({
          page: $scope.sectors.form.data.page,
          itemsPerPage: 10
        });
      }
    }; 
  }
]);
