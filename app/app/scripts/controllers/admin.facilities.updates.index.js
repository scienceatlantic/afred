'use strict';

angular.module('afredApp').controller('AdminFacilitiesUpdatesIndexController', [
  '$scope',
  function($scope) {    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    $scope.facilities.updates.parseParams();
    $scope.facilities.updates.query();
  }
]);
