'use strict';

angular.module('afredApp').controller('SearchResultsController', ['$scope', '$stateParams', 'mode',
  function($scope, $stateParams, mode) {
    $scope.getSearchResults = function(query) {
      $scope.equipment = [
        {
          name: 'Microscope',
          created: new Date(),
          updated: new Date()
        }
      ];   
    };
    
    //Initialise
    $scope.searchBar.query = (mode === 'query' ? $stateParams.query : null); 
    $scope.getSearchResults($scope.searchBar.query);
  }
]);