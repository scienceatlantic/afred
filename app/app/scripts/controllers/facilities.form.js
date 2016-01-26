'use strict';

angular.module('afredApp').controller('FacilitiesFormController',
  ['$scope',
   '$interval',
   'organizationResource',
   'provinceResource',
   'facilityRepositoryResource',
  function($scope,
           $interval,
           organizationResource,
           provinceResource,
           facilityRepositoryResource) {
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
       * Holds an instance of 'facilityRepositoryResource'.
       *
       * @type {resource}
       *
       */
      facilityRepository: {},
      
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
       * '$scope.form.data'.
       */
      initialise: function(resetStorage) {
        // Holds all facility data that will be passed to the API.
        $scope.form.data = {};
        
        $scope.form.data.facility = {
          name: null,
          organizationId: null,
          provinceId: null,
          description: null,
          city: null,
          website: null
        };
        
        $scope.form.data.organization = {};
        $scope.form.data.province = {};
        $scope.form.data.primaryContact = {};
        $scope.form.data.contacts = [];
        $scope.form.data.equipment = [];
        
        // Get a list of all available organizations.
        $scope.form.getOrganizations();
        
        // Get a list of all available provinces.
        $scope.form.getProvinces();
        
        // Add the first contact.
        $scope.form.addContact();
        
        // Add the first equipment.
        $scope.form.addEquipment();
        
        if (resetStorage) {
          try {
            localStorage.removeItem($scope.form.getStorageItemName());
          } catch(e) {
            // Do nothing.
          }
        }
      },
      
      /**
       * Adds an additional contact object to the
       * '$scope.form.data.contacts' array and advances
       * '$scope.form.contactIndex' to point to it.
       */      
      addContact: function() {
        $scope.form.data.contacts.push({
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
        $scope.form.contactIndex = $scope.form.data.contacts.length - 1;
      },
      
      /**
       * Removes a contact object from the '$scope.form.data.contacts'
       * array.
       * @param {integer} index Array index of '$scope.form.data.contacts'.
       */
      removeContact: function(index) {
        // The first contact cannot be removed.
        if (index !== 0) {
          $scope.form.data.contacts.splice(index, 1);
          
          // If the user is currently viewing the contact that is being removed
          // or if '$scope.form.contactIndex' is more than the total number
          // of contacts in the array itself, decrease
          // '$scope.form.contactIndex' (ie. point to the previous contact).
          if ($scope.form.contactIndex === index ||
              $scope.form.contactIndex >
              $scope.form.data.contacts.length - 1) {
            $scope.form.contactIndex--;
          }
        }       
      },

      /**
       * Adds additional equipment object to the
       * '$scope.form.data.equipment' array and advances
       * '$scope.form.equipmentIndex' to point to it.
       */      
      addEquipment: function() {
        $scope.form.data.equipment.push({
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
        $scope.form.equipmentIndex = $scope.form.data.equipment.length - 1;       
      },
     
      /**
       * Removes an equipment object from '$scope.form.data.equipment'.
       * @param {number} index Array index of equipment.
       */
      removeEquipment: function(index) {
        // The first equipment cannot be removed.
        if (index !== 0) {
          $scope.form.data.equipment.splice(index, 1);
        
          // If the user is currently viewing the equipment that is being
          // removed or if '$scope.form.equipmentIndex' is more than the total
          // number of equipment, decrease '$scope.form.equipmentIndex'.    
          if ($scope.form.equipmentIndex === index ||
              $scope.form.equipmentIndex >
              $scope.form.data.equipment.length - 1) {
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
      getFacilityRepositoryData: function() {
        $scope.form.fr =
          facilityRepositoryResource.get({
              facilityRepositoryId: $scope._stateParams.facilityRepositoryId
            },
            function() {
              $scope.form.data = angular.copy($scope.form.fr.data);
                
              if (angular.isArray($scope.form.data.contacts)) {
                $scope.form.data.contacts.unshift(
                  $scope.form.fr.data.primaryContact);
              } else {
                $scope.form.data.contacts = [];
                $scope.form.data.contacts.push(
                  $scope.form.fr.data.primaryContact);
              }
              
              if (!$scope.form.fr.data.facility.organizationId) {
                if ($scope.form.fr.data.organization.name) {
                  $scope.form.data.organization.name =
                    $scope.form.fr.data.organization.name;
                } else {
                  $scope.form.data.facility.organizationId = -1;
                }
              }
              
              console.log($scope.form.data);
            }
          );        
      },
      
      /**
       * Saves the form to localStorage.
       */
      save: function() {
        var itemName = $scope.form.getStorageItemName();
        
        try {
          localStorage.setItem(itemName, JSON.stringify($scope.form.data));
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
        var itemName = $scope.form.getStorageItemName();
        
        try {
          if (localStorage.getItem(itemName)) {
            $scope.form.data = JSON.parse(localStorage.getItem(itemName));
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
      
      getStorageItemName: function() {
        return $scope._state.current.name + '-facility';
      },
      
      /**
       *
       * FIX THIS DESCRIPTION
       * Formats the data for the preview. It gets the names of the selected
       * organization and province. If 'N/A' is selected for organization,
       * clear the '$scope.form.data.organization' object.
       *
       * Note: This function has to be called before the data is submitted
       * to the API because the '$scope.form.formatForApi' ........ !!?
       */
      formatForPreview: function() {
        var facility = angular.copy($scope.form.data.facility);
        facility.primaryContact = angular.copy($scope.form.data.primaryContact);
        facility.contacts = angular.copy($scope.form.data.contacts);
        facility.equipment = angular.copy($scope.form.data.equipment);
        
        if ($scope.form.data.facility.organizationId > 0) {
          var e = document.getElementById('facility-organization');
          facility.organization = {
            name: $scope.form.organizations[e.selectedIndex].name
          };
        } else if ($scope.form.data.facility.organizationId == -2) {
          facility.organization = {
            name: $scope.form.data.organization.name
          }
        }
        
        var e = document.getElementById('facility-province');
        facility.province = {
          name: $scope.form.provinces[e.selectedIndex].name
        };
        
        return facility;
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
        var data = angular.copy($scope.form.data);
        
        // If 'N/A' was selected, clear the 'organization' object.
        if (data.facility.organizationId == -1) {
          data.organization = {};
        }
        
        if (data.facility.organizationId < 1) {
          data.facility.organizationId = null;
        }
        
        data.primaryContact = (data.contacts.splice(0, 1))[0];
        
        return data;
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