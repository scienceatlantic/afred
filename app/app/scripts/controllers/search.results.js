'use strict';

angular.module('afredApp').controller('SearchResultsController',
  ['$scope',
  function($scope) {    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    // Since the search query is appended to the URL, users might potentially
    // bypass the search bar altogether. Regardless of whether or not they do
    // that, we'll just copy the query from the URL into the search bar.
    $scope.search.parseParams();
   
    // Run the search.
    $scope.search.getResults();
  }
]);