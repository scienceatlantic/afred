'use strict';

angular.module('afredApp').controller('AdminFacilitiesHistoryIndexController', [
  '$scope',
  function($scope) {    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    $scope.history.parseParams();
    $scope.history.query();
  }
]);
