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
    
    $scope.getFacilityTotals = function(state) {
      if (state == 'PUBLISHED') {
        return facilityResource.query({
            itemsPerPage: 1,
            page: 1
        });        
      } else {
        return facilityRevisionHistoryResource.query({
            itemsPerPage: 1,
            page: 1,
            state: state
        });         
      }
    };
    
    $scope.facilityStats = {
      published: $scope.getFacilityTotals('PUBLISHED'),
      pendingApproval: $scope.getFacilityTotals('PENDING_APPROVAL'),
      rejected: $scope.getFacilityTotals('REJECTED'),
    };
  }
]);