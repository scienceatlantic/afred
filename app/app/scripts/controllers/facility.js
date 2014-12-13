'use strict';

/**
 * @ngdoc function
 * @name afredApp.controller:submissionController
 * @description
 * # submissionController
 * Controller of the afredApp
 */
angular.module('afredApp').controller('FacilityController', ['$scope', '$state', '$stateParams', '$modal', 'facilityResource',
  function($scope, $state, $stateParams, $modal, facilityResource) {        
    $scope.getFacility = function() {
      $scope.loading.facility = true;
      $scope.loading.contacts = true;
      $scope.loading.equipment = true;
      
      $scope.facility = facilityResource.get({facilityId: $stateParams.facilityId}, function() {
        $scope.loading.facility = false;
        $scope.facility.contacts = facilityResource.queryContacts({facilityId: $stateParams.facilityId}, function() {
          $scope.loading.contacts = false;
        });
        
        if ($scope.templateMode.facility) {
          $scope.facility.equipment = facilityResource.queryEquipment({facilityId: $stateParams.facilityId}, function() {
            $scope.loading.equipment = false;
          });
        }
        else if($scope.templateMode.equipment) {
          $scope.facility.equipment = [];
          $scope.facility.equipment[0] = facilityResource.getEquipment({facilityId: $stateParams.facilityId, equipmentId: $stateParams.equipmentId}, function() {
            $scope.loading.equipment = false;
          });        
        }
      });
    };
    
    $scope.edit = function() {
      $state.go('editFacility', {facilityId: $stateParams.facilityId});
    };
    
    $scope.remove = function() {
      $scope.facility.$remove(function() {
        $state.go('search');
      });
    };
    
    $scope.deactivate = function() {
      $scope.facility.isActive = 0;
      $scope.facility.$update();
      location.reload();
    };
    
    $scope.reactivate = function() {
      $scope.facility.isActive = true;
      $scope.facility.$update();
      location.reload();
    };
    
    $scope.viewFacility = function() {
      $state.go('facility', {facilityId: $stateParams.facilityId});
    };
    
    //Initialise
    $scope.panels = {};
    $scope.loading = {
      facility: true,
      contacts: true,
      equipment: true
    };
    $scope.facility = {};
    $scope.templateMode = {
      facility: $state.is('facility'),
      equipment: $state.is('equipment')
    };
    $scope.getFacility();
    
    if ($scope.templateMode.facility) {
      $scope.panels.first = 'facility-panel.html';
      $scope.panels.second = 'contact-panel.html';
      $scope.panels.third = 'equipment-panel.html';
    }
    else if ($scope.templateMode.equipment) {
      $scope.panels.first = 'equipment-panel.html';
      $scope.panels.second = 'facility-panel.html';
      $scope.panels.third = 'contact-panel.html';  
    }
  }
]);