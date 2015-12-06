'use strict';

angular.module('afredApp').controller(
  'AdminFacilityRevisionHistoryShowController',
  ['$scope',
   '$stateParams',
   'institutionResource',
   'provinceResource',
   'facilityRevisionHistoryResource',
  function($scope,
           $stateParams,
           institutionResource,
           provinceResource,
           facilityRevisionHistoryResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    $scope.getFacility = function() {
      $scope.facilityRevisionHistory = facilityRevisionHistoryResource.get({
        facilityRevisionHistoryId: $stateParams.facilityRevisionHistoryId
      }, function() {
        $scope.facility = $scope.facilityRevisionHistory.data.facility;
        $scope.formatForApp();
      });
    };
    
    $scope.approve = function() {
      facilityRevisionHistoryResource.approve({
        facilityRevisionHistoryId: $stateParams.facilityRevisionHistoryId
      }, null, function(data) {
        $scope.facilityRevisionHistory = data;
      });
    };
    
    $scope.reject = function() {
      facilityRevisionHistoryResource.reject({
        facilityRevisionHistoryId: $stateParams.facilityRevisionHistoryId
      }, null, function() {
        
      });   
    };
    
    $scope.approveEdit = function() {
      
    };
    
    $scope.rejectEdit = function() {
      
    };
    
    $scope.formatForApp = function() {
      if (!$scope.facility.contacts) {
        $scope.facility.contacts = [];
        $scope.facility.contacts.push($scope.facility.primaryContact);
      } else {
        $scope.facility.contacts.unshift($scope.facility.primaryContact);
      }
      
      if ($scope.facility.institutionId) {
        $scope.facility.institution = institutionResource.get({
          institutionId: $scope.facility.institutionId
        });
      } else {
        $scope.facility.institution =
          $scope.facilityRevisionHistory.data.institution;
      }
      
      $scope.facility.province = provinceResource.get({
        provinceId: $scope.facility.provinceId
      });
    };
    
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    $scope.getFacility();
    
    $scope.loading = {
      
    };
  }
]);