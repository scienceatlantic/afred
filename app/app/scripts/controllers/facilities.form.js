'use strict';

angular.module('afredApp').controller('FacilitiesFormController',
  ['$scope',
   '$interval',
   'organizationResource',
   'provinceResource',
   'facilityRevisionHistoryResource',
  function($scope,
           $interval,
           organizationResource,
           provinceResource,
           facilityRevisionHistoryResource) {
    /* ---------------------------------------------------------------------
     * Functions/Objects.
     * --------------------------------------------------------------------- */

    /**
     * All properties/functions related to the form.
     */
    $scope.form = {
      /**
       * Holds all form data.
       *
       * @type {object}
       */
      facility: {},
      
      /**
       * Holds an instance of 'facilityRevisionHistoryResource'.
       *
       * @type {resource}
       *
       */
      facilityRevisionHistory: {},
      
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
       * Holds a list of available organizations for the 'Organizations'
       * dropdown.
       *
       * @type {array}
       */
      organizations: [],
      
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
       * If the '$scope.form.getSave()' method fails, this property will be set
       * to false. This prevents '$scope.form.autosave()' from executing
       * even though local storage is not supported.
       */
      isStorageSupported: true,
      
      /**
       * Initialises the form. All form data is attached to
       * '$scope.form.facility'.
       */
      initialise: function() {
        // Holds all facility data that will be passed to the API.
        $scope.form.facility = {
          name: null,
          organization: { name: null }, // This is for the preview.
          organizationId: null,
          province: { name: null }, // This is for the preview too.
          provinceId: null,
          description: null,
          city: null,
          website: null,
          primaryContact: {},
          contacts: [],
          equipment: []
        };
        
        // Get a list of all available organizations.
        $scope.form.getOrganizations();
        
        // Get a list of all available provinces.
        $scope.form.getProvinces();
        
        // Add the first contact.
        $scope.form.addContact();
        
        // Add the first equipment.
        $scope.form.addEquipment();
      },
      
      /**
       * Adds an additional contact object to the
       * '$scope.form.facility.contacts' array and advances
       * '$scope.form.contactIndex' to point to it.
       */      
      addContact: function() {
        $scope.form.facility.contacts.push({
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
        $scope.form.contactIndex = $scope.form.facility.contacts.length - 1;
      },
      
      /**
       * Removes a contact object from the '$scope.form.facility.contacts'
       * array.
       * @param {integer} index Array index of '$scope.form.facility.contacts'.
       */
      removeContact: function(index) {
        // The first contact cannot be removed.
        if (index !== 0) {
          $scope.form.facility.contacts.splice(index, 1);
          
          // If the user is currently viewing the contact that is being removed
          // or if '$scope.form.contactIndex' is more than the total number
          // of contacts in the array itself, decrease
          // '$scope.form.contactIndex' (ie. point to the previous contact).
          if ($scope.form.contactIndex === index ||
              $scope.form.contactIndex >
              $scope.form.facility.contacts.length - 1) {
            $scope.form.contactIndex--;
          }
        }       
      },

      /**
       * Adds additional equipment object to the
       * '$scope.form.facility.equipment' array and advances
       * '$scope.form.equipmentIndex' to point to it.
       */      
      addEquipment: function() {
        $scope.form.facility.equipment.push({
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
        $scope.form.equipmentIndex = $scope.form.facility.equipment.length - 1;       
      },
     
      /**
       * Removes an equipment object from '$scope.form.facility.equipment'.
       * @param {number} index Array index of equipment.
       */
      removeEquipment: function(index) {
        // The first equipment cannot be removed.
        if (index !== 0) {
          $scope.form.facility.equipment.splice(index, 1);
        
          // If the user is currently viewing the equipment that is being
          // removed or if '$scope.form.equipmentIndex' is more than the total
          // number of equipment, decrease '$scope.form.equipmentIndex'.    
          if ($scope.form.equipmentIndex === index ||
              $scope.form.equipmentIndex >
              $scope.form.facility.equipment.length - 1) {
            $scope.form.equipmentIndex--;
          }      
        }          
      },
      
      /**
       * Gets a list of all organizatoins and attaches it to
       * '$scope.form.organizations'.
       */
      getOrganizations: function() {
        $scope.form.organizations = organizationResource.queryNoPaginate(
          {
            expand: 'ilo'
          },
          function() {
            // Add an option for 'N/A'.
            $scope.form.organizations.push({id: -1, name: 'N/A'});
            
            // Add an option for 'Other'.
            $scope.form.organizations.push({id: -2, name: 'Other'});
        });
      },
      
      /**
       * Gets a list of all provinces and attaches it to
       * '$scope.form.provinces'.
       */
      getProvinces: function() {
        $scope.form.provinces = provinceResource.queryNoPaginate();
      },
      
      /**
       * Retrieves a facility for editing and attaches it
       * '$scope.form.facility'.
       *
       * Note: ..
       *
       */
      getFacility: function() {
        $scope.form.facilityRevisionHistory =
          facilityRevisionHistoryResource.get(
            {
              facilityRevisionHistoryId:
                $scope._stateParams.facilityRevisionHistoryId
            },
            function() {
              $scope.form.facility =
                $scope.form.facilityRevisionHistory.data.facility;
              
              if (angular.isArray($scope.form.facility.contacts)) {
                $scope.form.facility.contacts.unshift(
                  $scope.form.facility.primaryContact);
              } else {
                $scope.form.facility.contacts = [];
                $scope.form.facility.contacts.push(
                  $scope.form.facility.primaryContact);
              }
            }
          );        
      },
      
      /**
       * Saves the form to localStorage.
       */
      save: function() {
        var itemName = $scope._state.current.name + '-facility';
        
        try {
          localStorage.setItem(itemName, JSON.stringify($scope.form.facility));
        } catch(e) {
          // Do nothing if local storage is not supported.
        }
      },
      
      /**
       * Retrieves any saved data from local storage.
       *
       * Bugs: Radio buttons are not highlighted after data is retrieved.
       */
      getSave: function() {
        var itemName = $scope._state.current.name + '-facility';
        
        try {
          if (localStorage.getItem(itemName)) {
            $scope.form.facility = JSON.parse(localStorage.getItem(itemName));
          }
        } catch(e) {
          // Local storage is not supported.
          $scope.form.isStorageSupported = false;
        }
      },
      
      /**
       * Continuously save the form every 'interval' milliseconds.
       *
       * @param {integer} interval Number of milliseconds between each
       *     interval. If not provided, a default of 750 milliseconds is used.
       */
      startAutosave: function(interval) {
        if (!$scope.form.isAutosaving || $scope.form.isStorageSupported) {
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
       * Formats the data for the preview. It gets the names of the selected
       * organization and province. If 'N/A' is selected for organization,
       * clear the '$scope.form.facility.organization' object.
       */
      formatForPreview: function() {        
        if ($scope.form.facility.organizationId > 0) {
          var e = document.getElementById('facility-organization');
          $scope.form.facility.organization =
            $scope.form.organizations[e.selectedIndex];
        } else if ($scope.form.facility.organizationId == -1) {
          $scope.form.facility.organization = {};
        }
        
        var e = document.getElementById('facility-province');
        $scope.form.facility.province = $scope.form.provinces[e.selectedIndex];
        
        return angular.copy($scope.form.facility);
      },
      
      /**
       * Formats the data to match what the API requires. The API expects a
       * single primary contact and (optionally) regular contacts. In the form,
       * the first contact is the primary contact, so splice it out and attach
       * it to a property called 'primaryContact'. The 'organizationId' field
       * must either be empty or contain a valid organization id, so if it's
       * less than 1, set it to null.
       *
       * @return {object} Facility object.
       */
      formatForApi: function() {
        var facility = angular.copy($scope.form.facility);
        
        // If 'N/A' was selected, clear the 'organization' object.
        if (facility.organizationId == -1) {
          facility.organization = {};
        }
        
        if (facility.organizationId < 1) {
          facility.organizationId = null;
        }
        
        facility.primaryContact = (facility.contacts.splice(0, 1))[0];
        
        return facility;
      }
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
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