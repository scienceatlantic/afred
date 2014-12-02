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
      
      $scope.contactIndex = $scope.record.contacts.length - 1;
    };
    
    $scope.removeContact = function(index) {
      if (index !== 0) {
        $scope.record.contacts.splice(index, 1);
        
        if ($scope.contactIndex === index ||
            $scope.contactIndex > $scope.record.contacts.length - 1) {
          $scope.contactIndex--;
        }
      }
    };
    
    $scope.setContactIndex = function(index) {
      $scope.contactIndex = index;
    };
    
    $scope.addEquipment = function() {
      $scope.record.equipment.push({
        name: null,
        specifications: null,
        purpose: null,
        excessCapacity: null,
        keywords: null
      });
      
      $scope.equipmentIndex = $scope.record.equipment.length - 1;
    };
    
    $scope.removeEquipment = function(index) {
      if (index !== 0) {
        $scope.record.equipment.splice(index, 1);
      }
      
      if ($scope.equipmentIndex === index ||
          $scope.equipmentIndex > $scope.record.equipment.length - 1) {
        $scope.equipmentIndex--;
      }
    };
    
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