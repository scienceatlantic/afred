'use strict';

angular.module('afredApp').controller('AdminFacilityRepositoryShowController', [
  '$scope',
  'organizationResource',
  'provinceResource',
  'disciplineResource',
  'sectorResource',
  'facilityRepositoryResource',
  function($scope,
           organizationResource,
           provinceResource,
           disciplineResource,
           sectorResource,
           facilityRepositoryResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    $scope.getFacilityRepository = function() {
      $scope.fr = facilityRepositoryResource.get({
        facilityRepositoryId: $scope._stateParams.facilityRepositoryId
      }, function() {
        $scope.formatForApp();
      });
    };
    
    $scope.approve = function() {
      $scope.frPromise = facilityRepositoryResource.approve({
        facilityRepositoryId: $scope._stateParams.facilityRepositoryId
      }, null, function(data) {
        $scope.fr = data;
      });
    };
    
    $scope.reject = function() {
      $scope.frPromise = facilityRepositoryResource.reject({
        facilityRepositoryId: $scope._stateParams.facilityRepositoryId
      }, null, function(data) {
        $scope.fr = data;
      });   
    };
    
    $scope.approveEdit = function() {
      $scope.frPromise = facilityRepositoryResource.approveEdit({
        facilityRepositoryId: $scope._stateParams.facilityRepositoryId
      }, null, function(data) {
        $scope.fr = data;
      });      
    };
    
    $scope.rejectEdit = function() {
      $scope.frPromise = facilityRepositoryResource.rejectEdit({
        facilityRepositoryId: $scope._stateParams.facilityRepositoryId
      }, null, function(data) {
        $scope.fr = data;
      });         
    };
    
    $scope.formatForApp = function() {
      $scope.facility = angular.copy($scope.fr.data.facility);
      $scope.facility.organization = angular.copy($scope.fr.data.organization);
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
      }
      
      // Province section.
      $scope.facility.province = provinceResource.get({
        provinceId: $scope.fr.data.facility.provinceId
      });
      
      // Disciplines section.
      $scope.facility.disciplines = [];
      $scope.disciplines = disciplineResource.queryNoPaginate(null,
        function() {
          angular.forEach($scope.disciplines, function(d) {
            if ($scope.fr.data.disciplines.indexOf(d.id) >= 0) {
              $scope.facility.disciplines.push(d)
            }
          });
          
          // Set loading flag to false.
          $scope.loading.disciplines = false;
        }
      );
      
      // Sectors section.
      $scope.facility.sectors = [];
      $scope.sectors = sectorResource.queryNoPaginate(null,
        function() {
          angular.forEach($scope.sectors, function(s) {
            if ($scope.fr.data.sectors.indexOf(s.id) >= 0) {
              $scope.facility.sectors.push(s)
            }
          });
          
          // Set loading flag to false.
          $scope.loading.sectors = false;
        }
      );
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    $scope.fr = {};
    $scope.frPromise = {};
    $scope.facility = {};
    $scope.disciplines = {};
    $scope.sectors = {};
    $scope.getFacilityRepository();
    
    /*
     * Loading flags.
     */
    $scope.loading = {
      disciplines: true,
      sectors: true,
      selectedButton: null
    };
  }
]);