'use strict';

angular.module('afredApp').controller('SearchResultsController', ['$scope', '$state', '$stateParams', 'equipmentResource',
  function($scope, $state, $stateParams, equipmentResource) {
    $scope.getSearchResults = function(query) {
      equipmentResource.query({query: query}, function(data) {
        $scope.results.equipment = data;
        $scope.loading.searchResults = false;
      });
    };
    
    $scope.showEquipmentPage = function(facilityId, equipmentId) {
      $state.go('equipment', {facilityId: facilityId, equipmentId: equipmentId});
    };
    
    //Initialise
    $scope.results = {
      equipment: []
    };
    $scope.loading = {
      searchResults: true
    };
    $scope.searchBar.query = ($state.is('search.query') ? $stateParams.query : null); 
    $scope.getSearchResults($scope.searchBar.query);
  }
]);