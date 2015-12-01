'use strict';

angular.module('afredApp').controller('FacilitiesFormController',
  ['$scope',
   'institutionResource',
   'provinceResource',
  function($scope,
           institutionResource,
           provinceResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    $scope.saveForm = function() {
      console.log('saveForm()');
      try {
        localStorage.setItem('facility', JSON.stringify($scope.facility));
        localStorage.setItem('dropdowns', JSON.stringify($scope.dropdowns));
      } catch(e) {
        // Do nothing if local storage is not supported.
      }
    };
    
    $scope.autosave = function() {
      try {
        setInterval(function() {
          $scope.saveForm();
        }, 1000);       
      } catch(e) {
        // Do nothing if local storage is not supported.
      } 
    };
    
    $scope.getAutosave = function() {
      try {
        if (localStorage.getItem('facility')) {
          $scope.facility = JSON.parse(localStorage.getItem('facility'));
        }
      } catch(e) {
        // Do nothing if local storage is not supported.
      }
      
      try {
        if (localStorage.getItem('dropdowns')) {
          $scope.dropdowns = JSON.parse(localStorage.getItem('dropdowns'));
          $scope.attachInstitution();
          $scope.attachProvince();
        }        
      } catch(e) {
        // Do nothing if local storage is not supported.
      }
    };
    
    $scope.initialiseForm = function() {
      $scope.facility = {
        name: null,
        institution: { id: null, name: null },
        institutionId: null,
        description: null,
        city: null,
        province: { id: null, name: null },
        provinceId: null,
        website: null,
        primaryContact: {},
        contacts: [],
        equipment: []
      };
      
      $scope.dropdowns = {
        institutions: { index: null, name: null },
        provinces: { index: null }
      };
      
      // Push the first contact object to the '$scope.facility.contacts' array.
      $scope.addContact();
      
      // Push the first equipment object to '$scope.facility.equipment' array.
      $scope.addEquipment();
    };
    
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
        website: null
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
        // Move 'N/A' to the bottom of the list.
        $scope.institutions.push(($scope.institutions.splice(0, 1))[0]);
        
        // Finally, add an option for 'Other'.
        $scope.institutions.push({id: '-1', name: 'Other'});
      });
    };
    
    $scope.attachInstitution = function() {
      console.log($scope.dropdowns);      
      // If it's an existing institutions.
      if ($scope.dropdowns.institutions.index
          < $scope.institutions.length - 1) {
        $scope.facility.institution.id =
          $scope.institutions[$scope.dropdowns.institutions.index].id;
        $scope.facility.institutionId =
            $scope.institutions[$scope.dropdowns.institutions.index].id;         
        $scope.facility.institution.name =
          $scope.institutions[$scope.dropdowns.institutions.index].name;
      // If it's a new institution.
      } else {
        $scope.facility.institution.id = null;
        $scope.facility.institutionId = null;
        $scope.facility.institution.name = $scope.dropdowns.institutions.name;
      }
    };
    
    /**
     * Gets a list of all institutions and attaches it to '$scope.provinces'.
     */
    $scope.getProvinces = function() {
      $scope.provinces = provinceResource.query();
    };
    
    $scope.attachProvince = function() {
      $scope.facility.province.id =
        $scope.provinces[$scope.dropdowns.provinces.index].id;
      $scope.facility.provinceId = 5;
      $scope.facility.province.name =
        $scope.provinces[$scope.dropdowns.provinces.index].name;
    };
    
    $scope.prepareForDb = function() {
      var facility = angular.copy($scope.facility);
      facility.primaryContact = (facility.contacts.splice(0, 1))[0];
      return facility;
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
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
  }
]);