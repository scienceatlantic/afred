'use strict';

/**
 * @ngdoc function
 * @name afredApp.controller:submissionController
 * @description
 * # submissionController
 * Controller of the afredApp
 */
angular.module('afredApp').controller('FacilityFormController', ['$scope', '$timeout', 'facilityResource', 'templateMode',
  function($scope, $timeout, facilityResource, templateMode) {
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
     * Submits the form to the API
     */
    $scope.submit = function() {
      console.log($scope.record);
    };
    
    //Initialise
    $scope.templateMode = templateMode;
    $scope.contactIndex = 0;
    $scope.equipmentIndex = 0;
    $scope.textAngularConfig = '[["p"],["bold","italics"],["ul","ol","indent","outdent"],["undo","redo"],["insertLink"]]';
    $scope.loading = {
      facilityForm: true
    };
    
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
      $scope.loading.facilityForm = false;
    }, 1500);
  }
]);