'use strict';

/**
 * @fileoverview Admin/Disciplines abstract class.
 * 
 * @see https://docs.angularjs.org/guide/controller
 * @see /scripts/routes.js (for route info)
 */

angular.module('afredApp').controller('AdminDisciplinesController', [
  '$scope',
  'disciplineResource',
  function($scope,
           disciplineResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * State object.
     */
    $scope.disciplines = {
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
       * Holds the data returned from 'disciplineResource'.
       * @type {resource}
       */
      resource: {},
      
      /**
       * Parses the URL parameters.
       *
       * Side effects:
       * $scope.disciplines.form.data.page Parsed page number data is attached
       *     to this.
       * 
       * Uses/calls/requires:
       * $scope.disciplines.form.data.page
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
        $scope.disciplines.form.data.page = page;
      },

      /**
       * Query discipline data.
       *
       * Side effects:
       * $scope.discipline.resource Data returned is attached to this.
       *
       * Uses/calls/requires:
       * disciplineResource
       * $scope.disciplines.form.data.page
       */
      query: function() {
        $scope.disciplines.resource = disciplineResource.query({
          page: $scope.disciplines.form.data.page,
          itemsPerPage: 10
        });
      }
    }; 
  }
]);
