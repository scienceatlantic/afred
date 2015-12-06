'use strict';

angular.module('afredApp').controller('AdminFacilitiesController',
  ['$scope',
   'facilityRevisionHistoryResource',
   'facilityResource',

  function($scope,
           facilityRevisionHistoryResource,
           facilityResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */ 
    
    $scope.getFacilities = function(page, itemsPerPage) {
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
            facilityRevisionHistoryResource.query(
              {
                itemsPerPage: itemsPerPage,
                page: page,
                state: $scope.view.state
              }
            );
      }
    };
    
    $scope.view = {
      state: null,
      facility: {
        isPublic: true
      }
    };
  }
]);