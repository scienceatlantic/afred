'use strict';

angular.module('afredApp').controller('SearchController',
  ['$scope',
   'provinceResource',
   'organizationResource',
   '$uibModal',
   function($scope,
            provinceResource,
            organizationResource,
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
        $scope._state.go('search.q', {q: $scope.search.q});
      } else { // Otherwise return to the main search page
        $scope._state.go('search');
      }
    };
    
    /**
     * Instantiates a modal that allows the user to send
     * a message to Springboard Atlantic.
     */
    $scope.contactUs = function() {
      var modalInstance = $uibModal.open({
        templateUrl: 'views/modals/contact-us.html',
        controller: 'ContactUsModalController'
      });
      
      modalInstance.dummy = 'dummy';
    };
    
    $scope.getProvinces = function() {
      $scope.provinces = provinceResource.queryNoPaginate(null, function() {
        $scope.provinces.unshift({ id: -1, name: 'All' });
      });
    };
    
    $scope.getOrganizations = function() {
      $scope.organizations = organizationResource.queryNoPaginate(null,
        function() {
          $scope.organizations.unshift({ id: -1, name: 'All' });
        }
      );
    }
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    // This will store the search queries.
    $scope.search = {
      q: null,
      listBy: 'equipment',
    };
    
    $scope.provinces = null;
    $scope.organizations = null;
    
    $scope.getProvinces();
    $scope.getOrganizations();
  }
]);