'use strict';

angular.module('afredApp').controller('AdminProvincesController', [
  '$scope',
  'ProvinceResource',
  function($scope,
           ProvinceResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * State class.
     */
    $scope.provinces = {
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
       * Holds the data returned from 'ProvinceResource'.
       * @type {resource}
       */
      resource: {},
      
      /**
       * Parses the URL parameters.
       *
       * Side effects:
       * $scope.provinces.form.data.page Parsed page number data is attached
       *     to this.
       * 
       * Uses/calls/requires:
       * $scope.provinces.form.data.page
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
        $scope.provinces.form.data.page = page;
      },
      
      /**
       * Query province data.
       *
       * Side effects:
       * $scope.province.resource Data returned is attached to this.
       *
       * Uses/calls/requires:
       * ProvinceResource
       * $scope.provinces.form.data.page
       * $scope._httpError403()
       */
      query: function() {
        $scope.provinces.resource = ProvinceResource.query({
          page: $scope.provinces.form.data.page,
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
