'use strict';

/**
 * @ngdoc function
 * @name afredApp.controller:submissionController
 * @description
 * # submissionController
 * Controller of the afredApp
 */
angular.module('afredApp').controller('FacilityFormController', ['$scope',
  '$state', '$stateParams', '$timeout', '$modal','facilityResource',
  'institutionResource', 'provinceResource', function($scope, $state,
  $stateParams, $timeout, $modal, facilityResource, institutionResource,
  provinceResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * Adds an additional contact object to the '$scope.facility.contacts'
     * array and advances the index to point to it.
     */
    $scope.addContact = function() {
      $scope.facility.contacts.push({
        firstName: null,
        lastName: null,
        email: null,
        telephone: null,
        extension: null,
        position: null,
        department: null,
        website: null,
        isPrimary: false
      });
      
      // Point 'contactIndex' to the object that was just added.
      $scope.contactIndex = $scope.facility.contacts.length - 1;
    };
    
    /**
     * Removes a contact object from the '$scope.facility.contacts' array.
     * @param {number} index Array index of '$scope.facility.contacts'.
     */
    $scope.removeContact = function(index) {
      // The first contact cannot be removed.
      if (index !== 0) {
        $scope.facility.contacts.splice(index, 1);
        
        // If the user is currently viewing the contact that is being removed
        // or if 'contactIndex' is more than the total number of contacts
        // in the array itself, decrease 'contactIndex'.
        if ($scope.contactIndex === index ||
            $scope.contactIndex > $scope.facility.contacts.length - 1) {
          $scope.contactIndex--;
        }
      }
    };
    
    /**
     * Point to a specific contact object in the '$scope.facility.contacts'
     * array.
     * @param {number} index Array index of contact
     */
    $scope.setContactIndex = function(index) {
      $scope.contactIndex = index;
    };
    
    /**
     * Adds additional equipment object to the '$scope.facility.equipment'
     * array and advances '$scope.equipmentIndex' to point to it.
     */
    $scope.addEquipment = function() {
      $scope.facility.equipment.push({
        type: null,
        manufacturer: null,
        model: null,
        specifications: null,
        purpose: null,
        isPublic: null,
        hasExcessCapacity: null,
        keywords: null
      });
      
      // Point the equipment object that was just added to the array.
      $scope.equipmentIndex = $scope.facility.equipment.length - 1;
    };
    
    /**
     * Removes an equipment object from '$scope.facility.equipment'.
     * @param {number} index Array index of equipment
     */
    $scope.removeEquipment = function(index) {
      //The first equipment cannot be removed
      if (index !== 0) {
        $scope.facility.equipment.splice(index, 1);
      
        // If the user is currently viewing the equipment that is being removed
        // or if 'equipmentIndex' is more than the total number of equipment,
        // decrease 'equipmentIndex'    
        if ($scope.equipmentIndex === index ||
            $scope.equipmentIndex > $scope.facility.equipment.length - 1) {
          $scope.equipmentIndex--;
        }      
      }
    };
    
    /**
     * Point to a specific equipment object in the '$scope.facility.equipment'
     * array.
     * @param {number} index Array index of equipment
     */
    $scope.setEquipmentIndex = function(index) {
      $scope.equipmentIndex = index;
    };
    
    /**
     * Gets a list of all institutions and attaches it to
     * '$scope.institutions'. The function also adds an institutions called
     * 'Other' with an ID of -1.
     */
    $scope.getInstitutions = function() {
      $scope.institutions = institutionResource.query(function() {
        $scope.institutions.push({id: '-1', name: 'Other'});
      });
    };
    
    /**
     * Gets a list of all institutions and attaches it to '$scope.provinces'.
     */
    $scope.getProvinces = function() {
      $scope.provinces = provinceResource.query();
    };
    
    /**
     * Shows a preview of the form.
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
            return { create: $state.is('createFacility'),
              edit: $state.is('editFacility') };
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
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    /** 
     * Determines what mode the template is functioning in. So it's either
     * in 'create' mode or 'edit' mode.
     */
    $scope.templateMode = {
      createFacility: $state.is('createFacility'),
      editFacility: $state.is('editFacility')
    };
    
    /**
     * An index variable for '$scope.facility.contacts' array.
     */
    $scope.contactIndex = 0;
    
    /**
     * An index variable for '$scope.facility.equipment' array.
     */
    $scope.equipmentIndex = 0;
    
    /**
     * Settings for 'CKEditor'.
     */
    $scope.ckEditorConfig = {
      height: 80,
      toolbar: [
        ['Bold', 'Italic', 'Subscript','Superscript', 'NumberedList',
         'BulletedList', 'Indent', 'Outdent', 'Link']
      ]
    };
    
    // Get a list of all institutions.
    $scope.getInstitutions();
    
    // Get a list of all provinces.
    $scope.getProvinces();
    
    // If the form is in 'create' mode, initialise the '$scope.facility'
    // object with all the necessary fields.
    if ($state.is('createFacility')) {
      $scope.facility = {
        name: null,
        institution: null,
        description: null,
        city: null,
        province: null,
        website: null,
        contacts: [],
        ilo: null,
        equipment: []
      };
      
      // Push the first contact object to the '$scope.facility.contacts' array.
      $scope.addContact();
      
      // Push the first equipment object to '$scope.facility.equipment' array.
      $scope.addEquipment();
      
    // Else if the form is in 'edit' mode, get the existing facility data from
    // the database.
    } else if ($state.is('editFacility')) {
      $scope.facility = facilityResource.get({facilityId:
        $stateParams.facilityId,
        expand: 'institution,province,equipment,contacts'});
    }
  }
]);