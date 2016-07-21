'use strict';

/**
 * @fileoverview Admin/Disciplines/Index page.
 * 
 * @see https://docs.angularjs.org/guide/controller
 * @see /scripts/run.js (for route info)
 */

angular.module('afredApp').controller('AdminDisciplinesIndexController', [
  '$scope',
  function($scope) {    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */

    $scope.disciplines.parseParams();
    $scope.disciplines.query();
  }
]);
