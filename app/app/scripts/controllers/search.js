'use strict';

angular.module('afredApp').controller('SearchController',
  ['$scope',
   '$state',
   '$uibModal',
   function($scope,
            $state,
            $uibModal) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * Performs a search request.
     */
    $scope.searchFn = function() {
      // Search only if the query is not empty
      if ($scope.search.q) {
        $state.go('search.q', {q: $scope.search.q});
      } else { // Otherwise return to the main search page
        $state.go('search');
      }
    };
    
    /**
     * Instantiates a modal that allows the user to send
     * a message to Springboard Atlantic.
     */
    $scope.contactUs = function () {
      var modalInstance = $uibModal.open({
        templateUrl: 'views/modals/contact-us.html',
        controller: 'ContactUsModalController'
      });
      
      modalInstance.dummy = 'dummy';
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    // This will store the search queries.
    $scope.search = {
      q: null,
      listBy: 'equipment',
      
    };
  }
]);