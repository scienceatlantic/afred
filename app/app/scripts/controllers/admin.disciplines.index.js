'use strict';
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