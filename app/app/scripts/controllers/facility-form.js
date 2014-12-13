'use strict';

/**
 * @ngdoc function
 * @name afredApp.controller:submissionController
 * @description
 * # submissionController
 * Controller of the afredApp
 */
angular.module('afredApp').controller('FacilityFormController', ['$scope', '$state', '$stateParams', '$timeout', '$modal', 'facilityResource',
  function($scope, $state, $stateParams, $timeout, $modal, facilityResource) {
    /**
     * Adds an additional contact to the form
     */
    $scope.addContact = function() {
      $scope.facility.contacts.push({
        firstName: null,
        lastName: null,
        email: null,
        telephone: null,
        position: null,
        department: null,
        website: null
      });
      
      //Show the form of the newly created contact
      $scope.contactIndex = $scope.facility.contacts.length - 1;
    };
    
    /**
     * Removes a contact
     * @param {number} index Array index of contact
     */
    $scope.removeContact = function(index) {
      //The first contact cannot be removed
      if (index !== 0) {
        $scope.facility.contacts.splice(index, 1);
        
        //If the user is currently viewing the contact that is being removed or
        //if contactIndex is less than the total number of contacts, decrease
        //contactIndex
        if ($scope.contactIndex === index ||
            $scope.contactIndex > $scope.facility.contacts.length - 1) {
          $scope.contactIndex--;
        }
      }
    };
    
    /**
     * Show the specified contact
     * @param {number} index Array index of contact
     */
    $scope.setContactIndex = function(index) {
      $scope.contactIndex = index;
    };
    
    /**
     * Adds additional equipment to the form
     */
    $scope.addEquipment = function() {
      $scope.facility.equipment.push({
        name: null,
        specifications: null,
        purpose: null,
        excessCapacity: null,
        keywords: null
      });
      
      //Show the form of the newly created equipment
      $scope.equipmentIndex = $scope.facility.equipment.length - 1;
    };
    
    /**
     * Removes an equipment
     * @param {number} index Array index of equipment
     */
    $scope.removeEquipment = function(index) {
      //The first equipment cannot be removed
      if (index !== 0) {
        $scope.facility.equipment.splice(index, 1);
      
        //If the user is currently viewing the equipment that is being removed or
        //if equipmentIndex is less than the total number of equipment, decrease
        //equipmentIndex    
        if ($scope.equipmentIndex === index ||
            $scope.equipmentIndex > $scope.facility.equipment.length - 1) {
          $scope.equipmentIndex--;
        }      
      }
    };
    
    /**
     * Show the specified equipment
     * @param {number} index Array index of equipment
     */
    $scope.setEquipmentIndex = function(index) {
      $scope.equipmentIndex = index;
    };
    
    $scope.fillIloForm = function() {
      if ($scope.facility.institution !== 'other') {
        $scope.facility.ilo.firstName = 'John';
        $scope.facility.ilo.lastName = 'Doe';
        $scope.facility.ilo.email = 'john.doe@dal.ca';
        $scope.facility.ilo.telephone = '9024001235';
        $scope.facility.ilo.position = 'Director';
      }
      else {
        $scope.facility.ilo.firstName = null;
        $scope.facility.ilo.lastName = null;
        $scope.facility.ilo.email = null;
        $scope.facility.ilo.telephone = null;
        $scope.facility.ilo.position = null;        
      }
    };
    
    /**
     * Shows a preview of the record
     */
    $scope.preview = function() {
      var modalInstance = $modal.open({
        size: 'lg',
        backdrop: 'static',
        keyboard: false,
        templateUrl: 'views/modals/facility-preview.html',
        controller: 'FacilityPreviewModalController',
        resolve: {
          facility: function() { return $scope.facility; },
          templateMode: function() {
            return {create: $state.is('createFacility'), edit: $state.is('editFacility')};
          }
        }
      });
      
      modalInstance.result.then(
        function() {
          $state.go('search');
        },
        function() {
          
        }
      );
    };
    
    //Initialise
    $scope.templateMode = {
      createFacility: $state.is('createFacility'),
      editFacility: $state.is('editFacility')
    };
    $scope.contactIndex = 0;
    $scope.equipmentIndex = 0;
    $scope.ckEditorConfig = {
      height: 80,
      toolbar: [
        ['Bold', 'Italic', 'Subscript','Superscript', 'NumberedList', 'BulletedList', 'Indent', 'Outdent', 'Link']
      ]
    };
    $scope.loading = {
      facility: true,
      contacts: true,
      equipment: true
    };
    
    if ($state.is('createFacility')) {
      $scope.facility = {
        name: null,
        institution: null,
        description: null,
        city: null,
        province: null,
        website: null,
        contacts: [],
        ilo: {
          firstName: null,
          lastName: null,
          email: null,
          telephone: null,
          position: null
        },
        equipment: []
      };
      $scope.addContact();
      $scope.addEquipment();
      
      $timeout(function() {
        $scope.loading.facility = false;
        $scope.loading.contacts = false;
        $scope.loading.equipment = false;
      }, 1500);
    }
    else if ($state.is('editFacility')) {
      $scope.loading.facility = true;
      $scope.loading.contacts = true;
      $scope.loading.equipment = true;
      
      $scope.facility = facilityResource.get({facilityId: $stateParams.facilityId}, function() {
        $scope.loading.facility = false;
        $scope.facility.ilo = {};
        
        $scope.facility.contacts = facilityResource.queryContacts({facilityId: $stateParams.facilityId}, function() {
          $scope.loading.contacts = false;
        });
        
        $scope.facility.equipment = facilityResource.queryEquipment({facilityId: $stateParams.facilityId}, function() {
          $scope.loading.equipment = false;
        });
      });
    }
  }
]);