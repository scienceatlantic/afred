'use strict';

angular.module('afredApp').controller('FacilitiesFormController',
  ['$scope',
   '$interval',
   'institutionResource',
   'provinceResource',
  function($scope,
           $interval,
           institutionResource,
           provinceResource) {
    /* ---------------------------------------------------------------------
     * Functions/Objects.
     * --------------------------------------------------------------------- */

    /**
     * All properties/functions related to the form.
     */
    $scope.form = {
      /**
       * Used to keep track of the current 'contact' being viewed.
       *
       * @type {integer}
       */
      contactIndex: null,
      
      /**
       * Used to keep track of the current 'equipment' being viewed.
       *
       * @type {integer}
       */
      equipmentIndex: null,
      
      /**
       * Holds a list of available institutions for the 'Institutions'
       * dropdown.
       *
       * @type {array}
       */
      institutions: [],
      
      /**
       * Holds a list of available provinces for the 'Provinces' dropdown.
       *
       * @type {array}
       */
      provinces: [],
      
      /**
       * Holds the unique ID returned by '$scope.form.startAutosave()'s
       * setInterval function. Used to either stop or prevent
       * '$scope.form.startAutosave()' from running more than one interval.
       *
       * @type {integer}
       */
      isAutosaving: 0,
      
      /**
       * Holds the names that will used for storing and retrieving
       * form data via the '$scope.form.save()', and '$scope.form.getSave()'
       * functions.
       *
       * @type {object}
       *
       */
      storage: {
        facility: $scope._state.current.name + '-facility',
        dropdowns: $scope._state.current.name + '-dropdowns'
      },
      
      /**
       * Intermediary variable to keep track of the institution and province
       * that was selected. The 'institution.name' holds the name of a new
       * institution if 'Other' was selected in the dropdown.
       *
       * @type {object}
       */
      dropdowns: {
        institution: { id: null, name: null },
        province: { id: null }
      },
      
      /**
       * Initialises the form. All form data is attached to '$scope.facility'.
       */
      initialise: function() {
        // Holds all facility data that will be passed to the API.
        $scope.facility = {
          name: null,
          institution: { id: null, name: null }, // This is for the preview.
          institutionId: null,
          province: { id: null, name: null }, // This is for the preview too.
          provinceId: null,
          description: null,
          city: null,
          website: null,
          primaryContact: {},
          contacts: [],
          equipment: []
        };
        
        // Get a list of all available institutions.
        $scope.form.getInstitutions();
        
        // Get a list of all available provinces.
        $scope.form.getProvinces();
        
        // Add the first contact.
        $scope.form.addContact();
        
        // Add the first equipment.
        $scope.form.addEquipment();
      },
      
      /**
       * Adds an additional contact object to the '$scope.facility.contacts'
       * array and advances '$scope.form.contactIndex' to point to it.
       */      
      addContact: function() {
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
        
        // Point the index to the object that was just added.
        $scope.form.contactIndex = $scope.facility.contacts.length - 1;
      },
      
      /**
       * Removes a contact object from the '$scope.facility.contacts' array.
       * @param {integer} index Array index of '$scope.facility.contacts'.
       */
      removeContact: function(index) {
        // The first contact cannot be removed.
        if (index !== 0) {
          $scope.facility.contacts.splice(index, 1);
          
          // If the user is currently viewing the contact that is being removed
          // or if '$scope.form.contactIndex' is more than the total number
          // of contacts in the array itself, decrease
          // '$scope.form.contactIndex' (ie. point to the previous contact).
          if ($scope.form.contactIndex === index ||
              $scope.form.contactIndex > $scope.facility.contacts.length - 1) {
            $scope.form.contactIndex--;
          }
        }       
      },

      /**
       * Adds additional equipment object to the '$scope.facility.equipment'
       * array and advances '$scope.form.equipmentIndex' to point to it.
       */      
      addEquipment: function() {
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

        // Point to the index to the object that was just added.
        $scope.form.equipmentIndex = $scope.facility.equipment.length - 1;       
      },
     
      /**
       * Removes an equipment object from '$scope.facility.equipment'.
       * @param {number} index Array index of equipment.
       */
      removeEquipment: function(index) {
        // The first equipment cannot be removed.
        if (index !== 0) {
          $scope.facility.equipment.splice(index, 1);
        
          // If the user is currently viewing the equipment that is being
          // removed or if '$scope.form.equipmentIndex' is more than the total
          // number of equipment, decrease '$scope.form.equipmentIndex'.    
          if ($scope.form.equipmentIndex === index ||
              $scope.form.quipmentIndex >
              $scope.facility.equipment.length - 1) {
            $scope.form.equipmentIndex--;
          }      
        }          
      },
      
      /**
       * Gets a list of all institutions and attaches it to
       * '$scope.form.institutions'. 'N/A' is moved to the second last
       * position. 'Other' is added as the last option.
       */
      getInstitutions: function() {
        $scope.form.institutions = institutionResource.query(function() {
          // 'N/A' should be the first entry in the database. We want it
          // to appear just before 'Other'.          
          $scope.form.institutions.push(
            ($scope.form.institutions.splice(0, 1))[0]);
          
          // Finally, add an option for 'Other'.
          $scope.form.institutions.push({id: -1, name: 'Other'});
        });
      },
      
      /**
       * Gets a list of all institutions and attaches it to
       * '$scope.form.provinces'.
       */
      getProvinces: function() {
        $scope.form.provinces = provinceResource.query();
      },
      
      /**
       * Saves the form to localStorage.
       */
      save: function() {        
        try {
          localStorage.setItem($scope.form.storage.facility,
            JSON.stringify($scope.facility));
          localStorage.setItem($scope.form.storage.dropdowns,
            JSON.stringify($scope.form.dropdowns));
        } catch(e) {
          // Do nothing if local storage is not supported.
        }
      },
      
      /**
       * Retrieves any saved data from local storage.
       */
      getSave: function() {        
        try {
          if (localStorage.getItem($scope.form.storage.facility)) {
            $scope.facility =
              JSON.parse(localStorage.getItem($scope.form.storage.facility));
          }
        } catch(e) {
          // Do nothing if local storage is not supported.
        }
        
        try {
          if (localStorage.getItem($scope.form.storage.dropdowns)) {
            $scope.form.dropdowns =
              JSON.parse(localStorage.getItem($scope.form.storage.dropdowns));
              $scope.form.attachInstitution();
              $scope.form.attachProvince();
          }        
        } catch(e) {
          // Do nothing if local storage is not supported.
        }
      },
      
      /**
       * Continuously save the form every 'interval' milliseconds.
       *
       * @param {integer} interval Number of milliseconds between each
       *     interval. If not provided, a default of 750 milliseconds is used.
       */
      startAutosave: function(interval) {
        if (!$scope.form.isAutosaving) {
          try {
            $scope.form.isAutosaving = $interval(function() {
              $scope.form.save();
            }, interval ? interval : 750);       
          } catch(e) {
            // Do nothing if local storage is not supported.
          }          
        }
      },

      /**
       * Since we're using an intermediary variable to keep track of the
       * dropdowns, every time the value is changed, we need to attach
       * to the '$scope.facility' variable.
       */
      attachInstitution: function() {        
        // If it's an existing institution.
        if ($scope.form.dropdowns.institution.id != -1) {
          $scope.facility.institution.id
            = $scope.form.dropdowns.institution.id;
          $scope.facility.institutionId =
            $scope.form.dropdowns.institution.id;
          
          // Use the selected 'option's index to grab the institution's name.
          var e = document.getElementById('facility-institution');
          $scope.facility.institution.name =
            $scope.form.institutions[e.selectedIndex].name;
        // If it's a new institution.
        } else {
          $scope.facility.institution.id = null;
          $scope.facility.institutionId = null;
          $scope.facility.institution.name =
            $scope.form.dropdowns.institution.name;
        }
      },
      
      /**
       * Same logic as '$scope.form.attachInstitutions()'
       */
      attachProvince: function() {
        $scope.facility.province.id = $scope.form.dropdowns.province.id;
        $scope.facility.provinceId = $scope.form.dropdowns.province.id;
        
        // Use the selected 'option's index to grab the province's name.
        var e = document.getElementById('facility-province');
        $scope.facility.province.name =
          $scope.form.provinces[e.selectedIndex].name;
      },
      
      /**
       * The API expects a single primary contact and (optionally) regular
       * contacts. In the form, the first contact is the primary contact.
       * This functions copies and returns '$scope.facility' to match
       * what the API expects.
       *
       * @return {object} Facility object.
       */
      formatForApi: function() {
        var facility = angular.copy($scope.facility);
        facility.primaryContact = (facility.contacts.splice(0, 1))[0];
        return facility;
      }
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    /**
     * Stores form data that will be sent to the API.
     */ 
    $scope.facility = {};
    
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
  }
]);