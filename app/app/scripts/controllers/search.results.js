'use strict';

angular.module('afredApp').controller('SearchResultsController', ['$scope', '$state', '$stateParams', 'equipmentResource', 'templateMode',
  function($scope, $state, $stateParams, equipmentResource, templateMode) {
    $scope.getSearchResults = function(query) {
      $scope.results.equipment = equipmentResource.query({query: query});
    };
    
    $scope.showEquipmentPage = function(facilityId, equipmentId) {
      $state.go('facility-equipment', {facilityId: facilityId, equipmentId: equipmentId});
    };
    
    //Initialise
    $scope.results = {
      equipment: []
    };
    $scope.searchBar.query = (templateMode.query ? $stateParams.query : null); 
    $scope.getSearchResults($scope.searchBar.query);
  }
]);