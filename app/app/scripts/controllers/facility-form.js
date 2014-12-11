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
      $scope.record.contacts.push({
        firstName: null,
        lastName: null,
        email: null,
        telephone: null,
        position: null,
        department: null,
        website: null
      });
      
      //Show the form of the newly created contact
      $scope.contactIndex = $scope.record.contacts.length - 1;
    };
    
    /**
     * Removes a contact
     * @param {number} index Array index of contact
     */
    $scope.removeContact = function(index) {
      //The first contact cannot be removed
      if (index !== 0) {
        $scope.record.contacts.splice(index, 1);
        
        //If the user is currently viewing the contact that is being removed or
        //if contactIndex is less than the total number of contacts, decrease
        //contactIndex
        if ($scope.contactIndex === index ||
            $scope.contactIndex > $scope.record.contacts.length - 1) {
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
      $scope.record.equipment.push({
        name: null,
        specifications: null,
        purpose: null,
        excessCapacity: null,
        keywords: null
      });
      
      //Show the form of the newly created equipment
      $scope.equipmentIndex = $scope.record.equipment.length - 1;
    };
    
    /**
     * Removes an equipment
     * @param {number} index Array index of equipment
     */
    $scope.removeEquipment = function(index) {
      //The first equipment cannot be removed
      if (index !== 0) {
        $scope.record.equipment.splice(index, 1);
      
        //If the user is currently viewing the equipment that is being removed or
        //if equipmentIndex is less than the total number of equipment, decrease
        //equipmentIndex    
        if ($scope.equipmentIndex === index ||
            $scope.equipmentIndex > $scope.record.equipment.length - 1) {
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
      console.log('fillIloForm()');
      console.log($scope.record.facility.institution);
      
      if ($scope.record.facility.institution !== 'other') {
        $scope.record.ilo.firstName = 'John';
        $scope.record.ilo.lastName = 'Doe';
        $scope.record.ilo.email = 'john.doe@dal.ca';
        $scope.record.ilo.telephone = '9024001235';
        $scope.record.ilo.position = 'Director';
      }
      else {
        $scope.record.ilo.firstName = null;
        $scope.record.ilo.lastName = null;
        $scope.record.ilo.email = null;
        $scope.record.ilo.telephone = null;
        $scope.record.ilo.position = null;        
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
          record: function() { return $scope.record; },
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
      $scope.record = {
        facility: {
          name: null,
          institution: null,
          description: null,
          additionalInformation: null,
          city: null,
          province: null,
          website: null
        },
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
      $scope.record = {};
      
      facilityResource.get({facilityId: $stateParams.facilityId}, function(data) {
        $scope.record.facility = data;
        $scope.loading.facility = false;
      });
      
      facilityResource.queryContacts({facilityId: $stateParams.facilityId}, function(data) {
        $scope.record.contacts = data;
        $scope.loading.contacts = false;
      });
      
      facilityResource.queryEquipment({facilityId: $stateParams.facilityId}, function(data) {
        $scope.record.equipment = data;
        $scope.loading.equipment = false;
      });
    }
  }
]);