'use strict';

angular.module('afredApp').controller('AdminSectorsIndexController', [
  '$scope',
  function($scope) {    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    $scope.sectors.parseParams();
    $scope.sectors.query();
  }
]);
