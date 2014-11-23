'use strict';

angular.module('afredApp').controller('SearchResultsController', ['$scope', '$stateParams', 'equipmentResource', 'templateMode',
  function($scope, $stateParams, equipmentResource, templateMode) {
    $scope.getSearchResults = function(query) {
      $scope.equipment = equipmentResource.query({query: query});
    };
    
    //Initialise
    $scope.equipment = [];
    $scope.searchBar.query = (templateMode.query ? $stateParams.query : null); 
    $scope.getSearchResults($scope.searchBar.query);
  }
]);