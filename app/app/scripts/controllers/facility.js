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
      facilityResource.get({facilityId: $stateParams.facilityId}, function(data) {
        $scope.record.facility = data;
        $scope.loading.facility = false;
      });
    };
    
    $scope.getContacts = function() {
      $scope.loading.contacts = true;
      facilityResource.queryContacts({facilityId: $stateParams.facilityId}, function(data) {
        $scope.record.contacts = data;
        $scope.loading.contacts = false;
      });
    };
    
    $scope.getEquipment = function() {
      $scope.loading.equipment = true;
      if ($scope.templateMode.facility) {
        facilityResource.queryEquipment({facilityId: $stateParams.facilityId}, function(data) {
          $scope.record.equipment = data;
          $scope.loading.equipment = false;
        });
      }
      else if($scope.templateMode.equipment) {
        facilityResource.getEquipment({facilityId: $stateParams.facilityId, equipmentId: $stateParams.equipmentId}, function(data) {
          $scope.record.equipment.push(data);
          $scope.loading.equipment = false;
        });        
      }
    };
    
    $scope.edit = function() {
      $state.go('editFacility', {facilityId: $stateParams.facilityId});
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
    $scope.record = {
      facility: null,
      contacts: [],
      equipment: []
    };
    $scope.templateMode = {
      facility: $state.is('facility'),
      equipment: $state.is('equipment')
    };
    $scope.getFacility();
    $scope.getContacts();
    $scope.getEquipment();
    
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