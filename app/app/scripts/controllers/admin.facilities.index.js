'use strict';

angular.module('afredApp').controller('AdminFacilitiesIndexController',
  ['$scope',
  function($scope) {
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */ 
    
    $scope.facilities.parseParams();
    $scope.facilities.query();
  }
]);
