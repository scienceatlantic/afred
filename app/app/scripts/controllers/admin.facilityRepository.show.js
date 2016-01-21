'use strict';

angular.module('afredApp').controller(
  'AdminfacilityRepositoryShowController',
  ['$scope',
   '$stateParams',
   'organizationResource',
   'provinceResource',
   'facilityRepositoryResource',
  function($scope,
           $stateParams,
           organizationResource,
           provinceResource,
           facilityRepositoryResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    $scope.getFacility = function() {
      $scope.facilityRepository = facilityRepositoryResource.get({
        facilityRepositoryId: $stateParams.facilityRepositoryId
      }, function() {
        $scope.facility = $scope.facilityRepository.data.facility;
        $scope.formatForApp();
      });
    };
    
    $scope.approve = function() {
      facilityRepositoryResource.approve({
        facilityRepositoryId: $stateParams.facilityRepositoryId
      }, null, function(data) {
        $scope.facilityRepository = data;
      });
    };
    
    $scope.reject = function() {
      facilityRepositoryResource.reject({
        facilityRepositoryId: $stateParams.facilityRepositoryId
      }, null, function(data) {
        $scope.facilityRepository = data;
      });   
    };
    
    $scope.approveEdit = function() {
      facilityRepositoryResource.approveEdit({
        facilityRepositoryId: $stateParams.facilityRepositoryId
      }, null, function(data) {
        $scope.facilityRepository = data;
      });      
    };
    
    $scope.rejectEdit = function() {
      facilityRepositoryResource.rejectEdit({
        facilityRepositoryId: $stateParams.facilityRepositoryId
      }, null, function(data) {
        $scope.facilityRepository = data;
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
          $scope.facilityRepository.data.organization;
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