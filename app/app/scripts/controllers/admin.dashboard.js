'use strict';

angular.module('afredApp').controller('AdminDashboardController',
  ['$scope',
   'facilityRevisionHistoryResource',
   'facilityResource',

  function($scope,
           facilityRevisionHistoryResource,
           facilityResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */ 
    
    $scope.getFacilities = function() {
      switch ($scope.view.state) {
        case 'PENDING_APPROVAL':
        case 'PENDING_EDIT_APPROVAL':
          $scope.facilities = facilityRevisionHistoryResource.query({state: $scope.view.state});
          break;
        
        case 'PUBLISHED':
          $scope.facilities = facilityResource.query({isPublic: $scope.view.isPublic});
          break;
      }
    };
    
    
    $scope.view = {
      state: null,
      isPublic: 1
    };
  }
]);