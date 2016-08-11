'use strict';

/**
 * @fileoverview Error 
 * 
 * @see https://docs.angularjs.org/guide/controller
 * @see /scripts/routes.js (for route info)
 */

angular.module('afredApp').controller('ErrorController',
  ['$scope',
  function($scope) {
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    $scope.httpStatusCode = parseInt($scope._state.current.name.split('.')[1]);
  }
]);
