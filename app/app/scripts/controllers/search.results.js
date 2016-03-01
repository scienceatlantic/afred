'use strict';

angular.module('afredApp').controller('SearchResultsController',
  ['$scope',
  function($scope) {    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    $scope.search.parseParams();
    $scope.search.getResults();
  }
]);