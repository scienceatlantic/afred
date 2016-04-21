'use strict';

angular.module('afredApp').controller('AdminProvincesIndexController', [
  '$scope',
  function($scope) {    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    $scope.provinces.parseParams();
    $scope.provinces.query();
  }
]);
