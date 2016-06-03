'use strict';

angular.module('afredApp').controller('SearchResultsController',
  ['$scope',
  function($scope) {    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    $scope.search.parseParams();
    
    // Clears the search results array (only) when a new search is performed.
    $scope.search.results = [];
    
    $scope.search.getResults();
  }
]);
