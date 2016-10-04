'use strict';

angular.module('afredApp').controller('SearchResultsController',
  ['$scope',
  function($scope) {    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    // Return to parent if query is blank and not in search.all state.
    if ($scope._state.is('search.q') && !$scope._stateParams.q) {
      $scope._state.go('search');
    }

    $scope.search.parseParams();    
    $scope.search.get();
  }
]);
