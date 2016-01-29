'use strict';

angular.module('afredApp').controller('SearchResultsController',
  ['$scope',
   'searchResource',
  function($scope,
           searchResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * Query the database.
     */
    $scope.getSearchResults = function(q) {
      searchResource.query({q: q}, function(data) {
        $scope.results.data = data;
        $scope.loading.searchResults = false;
      });
    };
    
    /**
     * Redirects the user to the equipment's page.
     */
    $scope.showEquipmentPage = function(facilityId, equipmentId) {
      $scope._state.go('equipment.show', {
        facilityId: facilityId,
        equipmentId: equipmentId
      });
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
    $scope.search.q = ($scope._state.is('search.q') ?
      $scope._stateParams.q : null);
    
    // Run the search.
    $scope.getSearchResults($scope.search.q);
  }
]);