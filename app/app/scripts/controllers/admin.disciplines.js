'use strict';

/**
 * @fileoverview Admin/Disciplines abstract class.
 * 
 * @see https://docs.angularjs.org/guide/controller
 * @see /scripts/routes.js (for route info)
 */

angular.module('afredApp').controller('AdminDisciplinesController', [
  '$scope',
  'DisciplineResource',
  function($scope,
           DisciplineResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * State object.
     */
    $scope.disciplines = {
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
       * Holds the data returned from 'DisciplineResource.query()`. See
       * `$scope.disciplines.query()`.
       * 
       * @type {Angular resource}
       */
      resource: {},
      
      /**
       * Parses the URL parameters.
       *
       * @sideeffect $scope.disciplines.form.data.page Parsed page number data
       *     is attached to this.
       * 
       * @requires $scope._stateParams.page
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
        $scope.disciplines.form.data.page = page;
      },

      /**
       * Query discipline data.
       *
       * @sideffect $scope.discipline.resource Data returned is attached to
       *     this.
       *
       * @requires $scope._httpError403()
       * @requires $scope.disciplines.form.data.page
       * @requires DisciplineResource
       */
      query: function() {
        $scope.disciplines.resource = DisciplineResource.query({
          page: $scope.disciplines.form.data.page,
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
