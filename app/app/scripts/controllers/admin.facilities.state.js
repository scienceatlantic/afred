'use strict';

angular.module('afredApp').controller('AdminFacilitiesStateController',
  ['$scope',
  function($scope) {
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */ 
    
    $scope.facilities.parseParams();
    $scope.facilities.get();
  }
]);