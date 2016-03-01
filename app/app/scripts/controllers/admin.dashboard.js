'use strict';

angular.module('afredApp').controller('AdminDashboardController',
  ['$scope',
   'facilityRepositoryResource',
   'facilityResource',
  function($scope,
           facilityRepositoryResource,
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
        return facilityRepositoryResource.query({
            itemsPerPage: 1,
            page: 1,
            state: state
        });         
      }
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    $scope.facilityStats = {
      published: $scope.getFacilityTotals('PUBLISHED'),
      pendingApproval: $scope.getFacilityTotals('PENDING_APPROVAL'),
      pendingEditApproval: $scope.getFacilityTotals('PENDING_EDIT_APPROVAL'),
      rejected: $scope.getFacilityTotals('REJECTED'),
      rejectedEdit: $scope.getFacilityTotals('REJECTED_EDIT'),
    };
  }
]);