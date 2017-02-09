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
       * 
       * @type {object}
       */
      form: {
        data: {
            page: null
        }
      },
      
      /**
       * Holds the data returned from `ProvinceResource`.
       * 
       * @type {Angular resource}
       */
      resource: {},
      
      /**
       * Parses the URL parameters.
       *
       * @sideffect $scope.provinces.form.data.page Parsed page number data is
       *     attached to this.
       * 
       * @requires $scope.provinces.form.data.page
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
       * @sideffect $scope.province.resource Data returned is attached to this.
       *
       * @requires $scope._httpError403()
       * @requires $scope.provinces.form.data.page
       * @requires ProvinceResource
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
