'use strict';

angular.module('afredApp').controller(
  'AdminFacilityRevisionHistoryShowController',
  ['$scope',
   '$stateParams',
   'facilityRevisionHistoryResource',

  function($scope,
           $stateParams,
           facilityRevisionHistoryResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    $scope.getFacility = function() {
      $scope.facilityRevisionHistory = facilityRevisionHistoryResource.get({
        facilityRevisionHistoryId: $stateParams.facilityRevisionHistoryId
      }, function() {
        $scope.facility = $scope.facilityRevisionHistory.facilityInJson;
      });
    };
    
    $scope.approve = function() {
      facilityRevisionHistoryResource.approve({
        facilityRevisionHistoryId: $stateParams.facilityRevisionHistoryId
      }, null);
    };
    
    $scope.reject = function() {
      facilityRevisionHistoryResource.reject({
        facilityRevisionHistoryId: $stateParams.facilityRevisionHistoryId
      }, null);   
    };
    
    $scope.approveEdit = function() {
      
    };
    
    $scope.rejectEdit = function() {
      
    };
    
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    $scope.getFacility();
  }
]);