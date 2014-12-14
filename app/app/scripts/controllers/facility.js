'use strict';

/**
 * @ngdoc function
 * @name afredApp.controller:submissionController
 * @description
 * # submissionController
 * Controller of the afredApp
 */
angular.module('afredApp').controller('FacilityController', ['$scope', '$state', '$stateParams', '$modal', 'facilityResource', 'institutionResource',
  function($scope, $state, $stateParams, $modal, facilityResource, institutionResource) {
    /**
     * Gets the facility
     */
    $scope.getFacility = function() {
      $scope.loading.facility = true;
      $scope.loading.institution = true;
      $scope.loading.contacts = true;
      $scope.loading.equipment = true;
      
      $scope.facility = facilityResource.get({facilityId: $stateParams.facilityId}, function() {
        $scope.loading.facility = false;
        
        $scope.facility.contacts = facilityResource.queryContacts({facilityId: $stateParams.facilityId}, function() {
          $scope.loading.contacts = false;
        });
        
        $scope.facility.institution = institutionResource.get({institutionId: $scope.facility.institutionId}, function() {
          $scope.loading.institution = false;
        });
        
        //If viewing the facility page, get all the equipment for that facility
        if ($scope.templateMode.facility) {
          $scope.facility.equipment = facilityResource.queryEquipment({facilityId: $stateParams.facilityId}, function() {
            $scope.loading.equipment = false;
          });
        }
        //If viewing the equipment page, get only that equipment's data.
        else if($scope.templateMode.equipment) {
          $scope.facility.equipment = [];
          $scope.facility.equipment[0] = facilityResource.getEquipment({facilityId: $stateParams.facilityId, equipmentId: $stateParams.equipmentId}, function() {
            $scope.loading.equipment = false;
          });        
        }
      });
    };
    
    /**
     * Redirects the user to the edit a facility page
     */
    $scope.edit = function() {
      $state.go('editFacility', {facilityId: $stateParams.facilityId});
    };
    
    /**
     * Deletes a facility
     */
    $scope.remove = function() {
      $scope.facility.$remove(function() {
        $state.go('search');
      });
    };
    
    /**
     * Deactivates a facility
     */
    $scope.deactivate = function() {
      $scope.facility.isActive = 0;
      $scope.facility.$update();
      location.reload();
    };
    
    /**
     * Reactivates a facility
     */
    $scope.reactivate = function() {
      $scope.facility.isActive = true;
      $scope.facility.$update();
      location.reload();
    };
    
    /**
     * Redirects a user to the facility page
     */
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