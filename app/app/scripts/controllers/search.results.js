'use strict';

angular.module('afredApp').controller('SearchResultsController', ['$scope',
  '$state', '$stateParams', 'equipmentResource', function($scope, $state,
  $stateParams, equipmentResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * Query the database.
     */
    $scope.getSearchResults = function(q) {
      equipmentResource.query({q: q}, function(data) {
        $scope.results.data = data;
        $scope.loading.searchResults = false;
      });
    };
    
    /**
     * Redirects the user to the equipment's page.
     */
    $scope.showEquipmentPage = function(facilityId, equipmentId) {
      $state.go('equipment', {facilityId: facilityId, equipmentId:
        equipmentId});
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    // This object will store the results.
    $scope.results = {
      data: null
    };
    
    // AJAX loading flag.
    $scope.loading = {
      searchResults: true
    };
    
    // Since the search query is appended to the URL, users might potentially
    // bypass the search bar altogether. If they do that, we'll just copy
    // the query from the URL into the search bar.
    $scope.search.q = ($state.is('search.q') ?
      $stateParams.q : null);
    
    // Run the search.
    $scope.getSearchResults($scope.search.q);
  }
]);