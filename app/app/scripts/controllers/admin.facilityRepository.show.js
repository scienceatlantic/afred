'use strict';

angular.module('afredApp').controller(
  'AdminfacilityRepositoryShowController',
  ['$scope',
   'organizationResource',
   'provinceResource',
   'facilityRepositoryResource',
  function($scope,
           organizationResource,
           provinceResource,
           facilityRepositoryResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    $scope.getFacility = function() {
      $scope.fr = facilityRepositoryResource.get({
        facilityRepositoryId: $scope._stateParams.facilityRepositoryId
      }, function() {
        $scope.formatForApp();
      });
    };
    
    $scope.approve = function() {
      facilityRepositoryResource.approve({
        facilityRepositoryId: $scope._stateParams.facilityRepositoryId
      }, null, function(data) {
        $scope.fr = data;
      });
    };
    
    $scope.reject = function() {
      facilityRepositoryResource.reject({
        facilityRepositoryId: $scope._stateParams.facilityRepositoryId
      }, null, function(data) {
        $scope.fr = data;
      });   
    };
    
    $scope.approveEdit = function() {
      facilityRepositoryResource.approveEdit({
        facilityRepositoryId: $scope._stateParams.facilityRepositoryId
      }, null, function(data) {
        $scope.fr = data;
      });      
    };
    
    $scope.rejectEdit = function() {
      facilityRepositoryResource.rejectEdit({
        facilityRepositoryId: $scope._stateParams.facilityRepositoryId
      }, null, function(data) {
        $scope.fr = data;
      });         
    };
    
    $scope.formatForApp = function() {
      $scope.facility = angular.copy($scope.fr.data.facility);
      $scope.facility.contacts = angular.copy($scope.fr.data.contacts);
      $scope.facility.equipment = angular.copy($scope.fr.data.equipment);
      
      // Primary contact & contacts section.
      if (!$scope.fr.data.contacts) {
        $scope.facility.contacts = [];
        $scope.facility.contacts.push($scope.fr.data.primaryContact);
      } else {
        $scope.facility.contacts.unshift($scope.fr.data.primaryContact);
      }
      
      // Organization section.
      if ($scope.fr.data.facility.organizationId) {
        $scope.facility.organization = organizationResource.get({
          organizationId: $scope.fr.data.facility.organizationId
        });
      } else {
        $scope.facility.organization = $scope.fr.data.organization;
      }
      
      // Province section.
      $scope.facility.province = provinceResource.get({
        provinceId: $scope.fr.data.facility.provinceId
      });
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    $scope.facility = {};
    $scope.fr = {};
    $scope.getFacility();
    
    $scope.loading = {
      
    };
  }
]);