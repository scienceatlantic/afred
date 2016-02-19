'use strict';

angular.module('afredApp').controller('AdminFacilitiesController',
  ['$scope',
   'facilityRepositoryResource',
   'facilityResource',
  function($scope,
           facilityRepositoryResource,
           facilityResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */ 
    
    $scope.getFacilities = function(page, itemsPerPage) {
      $scope._stateParams.state = $scope.view.state;
      
      page = page ? page : 1;
      itemsPerPage = itemsPerPage ? itemsPerPage : 5;
      
      switch ($scope.view.state) {
        case 'PUBLISHED':
          $scope.facilities =
            facilityResource.query(
              {
                itemsPerPage: itemsPerPage,
                page: page,
                isPublic: $scope.view.facility.isPublic
              }
            );
          break;
        
        default:
          $scope.facilities =
            facilityRepositoryResource.query(
              {
                itemsPerPage: itemsPerPage,
                page: page,
                state: $scope.view.state
              }
            );
      }
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */ 
    
    $scope.view = {
      state: $scope._stateParams.state,
      facility: {
        isPublic: true
      }
    };
    
    $scope.getFacilities();
  }
]);