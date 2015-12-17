'use strict';

angular.module('afredApp').controller(
  'AdminFacilityRevisionHistoryShowController',
  ['$scope',
   '$stateParams',
   'organizationResource',
   'provinceResource',
   'facilityRevisionHistoryResource',
  function($scope,
           $stateParams,
           organizationResource,
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
      }, null, function(data) {
        $scope.facilityRevisionHistory = data;
      });   
    };
    
    $scope.approveEdit = function() {
      facilityRevisionHistoryResource.approveEdit({
        facilityRevisionHistoryId: $stateParams.facilityRevisionHistoryId
      }, null, function(data) {
        $scope.facilityRevisionHistory = data;
      });      
    };
    
    $scope.rejectEdit = function() {
      facilityRevisionHistoryResource.rejectEdit({
        facilityRevisionHistoryId: $stateParams.facilityRevisionHistoryId
      }, null, function(data) {
        $scope.facilityRevisionHistory = data;
      });         
    };
    
    $scope.formatForApp = function() {
      if (!$scope.facility.contacts) {
        $scope.facility.contacts = [];
        $scope.facility.contacts.push($scope.facility.primaryContact);
      } else {
        $scope.facility.contacts.unshift($scope.facility.primaryContact);
      }
      
      if ($scope.facility.organizationId) {
        $scope.facility.organization = organizationResource.get({
          organizationId: $scope.facility.organizationId
        });
      } else {
        $scope.facility.organization =
          $scope.facilityRevisionHistory.data.organization;
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