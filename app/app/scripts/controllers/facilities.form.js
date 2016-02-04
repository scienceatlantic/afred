'use strict';

angular.module('afredApp').controller('FacilitiesFormController',
  ['$scope',
   '$interval',
   'organizationResource',
   'provinceResource',
   'disciplineResource',
   'sectorResource',
   'facilityRepositoryResource',
  function($scope,
           $interval,
           organizationResource,
           provinceResource,
           disciplineResource,
           sectorResource,
           facilityRepositoryResource) {
    /* ---------------------------------------------------------------------
     * Functions/Objects.
     * --------------------------------------------------------------------- */

    /**
     * All properties/functions related to the form.
     */
    $scope.form = {
      /**
       * Form data that will be submitted to the API.
       *
       * @type {object}
       */
      data: {},
          
      /**
       * Holds an instance of 'facilityRepositoryResource'.
       * @type {resource}
       */
      facilityRepository: {},
      
      /**
       * Keeps track of the current 'contact' being viewed.
       * @type {integer}
       */
      contactIndex: null,
      
      /**
       * Keeps track of the current 'equipment' being viewed.
       * @type {integer}
       */
      equipmentIndex: null,
      
      /**
       * A list of available organizations for the 'Organizations'
       * dropdown.
       * @type {array}
       */
      organizations: [],
      
      /**
       * A list of available provinces for the 'Provinces' dropdown.
       * @type {array}
       */
      provinces: [],
      
      /**
       * A list of all disciplines for the 'Disciplines' dropdown.
       * @type {array}
       */
      disciplines: [],
      
      /**
       * A list of all sectors for the 'Sectors' dropdown.
       * @type {array}
       */
      sectors: [],
      
      /**
       * Holds the unique ID returned by '$scope.form.startAutosave()'s
       * setInterval function. Used to either stop or prevent
       * '$scope.form.startAutosave()' from running more than one interval
       * at a time.
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
       * @param {boolean} resetStorage If set to true, the form will also
       *     delete any saved local storage data.
       */
      initialise: function(resetStorage) {
        // Holds all facility data that will be passed to the API.
        $scope.form.data = {};
        
        // Facility information.
        $scope.form.data.facility = {
          name: null,
          organizationId: null,
          provinceId: null,
          description: null,
          city: null,
          website: null
        };
        
        // An array of selected discipline IDs.
        $scope.form.data.disciplines = [];
        
        // An array of selected sector IDs.
        $scope.form.data.sectors = [];
        
        // Primary contact information.
        $scope.form.data.primaryContact = {};
        
        // Contact(s) information.
        $scope.form.data.contacts = [];
        
        // Equipment information
        $scope.form.data.equipment = [];
        
        // Get a list of all available organizations.
        $scope.form.getOrganizations();
        
        // Get a list of all available provinces.
        $scope.form.getProvinces();
        
        // Get a list of all disciplines.
        $scope.form.getDisciplines();
        
        // Get a list of all sectors.
        $scope.form.getSectors();
        
        // Add the first contact.
        $scope.form.addContact();
        
        // Add the first equipment.
        $scope.form.addEquipment();
        
        // Deletes any local storage data.
        if (resetStorage) {
          try {
            localStorage.removeItem($scope.form.getStorageItemName());
          } catch(e) {
            // Do nothing.
          }
        }
      },
      
      /**
       * Adds an additional contact object to the '$scope.form.data.contacts'
       *  array and advances '$scope.form.contactIndex' to point to it.
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
       * Removes a contact object from the '$scope.form.data.contacts' array.
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
       * Adds additional equipment object to the '$scope.form.data.equipment'
       * array and advances '$scope.form.equipmentIndex' to point to it.
       */      
      addEquipment: function() {
        $scope.form.data.equipment.push({
          type: null,
          manufacturer: null,
          model: null,
          specifications: null,
          purpose: null,
          yearPurchased: null,
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
            // Find the 'N/A' option and push it to the end of the array.
            for (var i = 0; i < $scope.form.organizations.length; i++) {
              if ($scope.form.organizations[i].name == 'N/A') {
                $scope.form.organizations.push(
                  ($scope.form.organizations.splice(i, 1))[0]);
                break;
              }
            }
            
            // Add an option for 'Other'.
            $scope.form.organizations.push({id: -1, name: 'Other'});
        });
      },
      
      /**
       * Gets a list of all provinces and attaches it to
       * '$scope.form.provinces'.
       */
      getProvinces: function() {
        $scope.form.provinces = provinceResource.queryNoPaginate(null,
          function() {
            // Find the 'N/A' option and push it to the end of the array.
            for (var i = 0; i < $scope.form.provinces.length; i++) {
              if ($scope.form.provinces[i].name == 'N/A') {
                $scope.form.provinces.push(
                  ($scope.form.provinces.splice(i, 1))[0]);
                break;
              }
            }
          }
        );
      },
      
      /**
       * Gets a list of all disciplines and attaches it to
       * '$scope.form.disciplines'
       */
      getDisciplines: function() {
        $scope.form.disciplines = disciplineResource.queryNoPaginate(null,
          function() {
            // Adds an 'isSelected' property for the checkboxes.
            angular.forEach($scope.form.disciplines, function (discipline) {
              discipline.isSelected = false;
            });
            
            // For the form validation. We're only attaching it to the first
            // element in the array.
            if ($scope.form.disciplines.length) {
              $scope.form.disciplines[0].isRequired = true;
            }
          }
        );
      },
      
      /**
       * Gets a list of all sectors and attaches it to '$scope.form.sectors'
       */
      getSectors: function() {
        $scope.form.sectors = sectorResource.queryNoPaginate(null,
          function() {
            // Same deal as '$scope.form.getDisciplines()'.
            angular.forEach($scope.form.sectors, function(sector) {
              sector.isSelected = false;
            });
            
            if ($scope.form.sectors.length) {
              $scope.form.sectors[0].isRequired = true;
            }
          }
        );
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
            }
          );        
      },
      
      /**
       * Makes sure that the user has selected at least one discipline.
       * Makes use of '$scope.form.discipline[0].isRequired'.
       * @var {object} disciplinesForm Instance of the 'disciplinesForm'
       */
      validateDisciplines: function(disciplinesForm) {
        // Have a look at the comments in the HTML template for more context.
        // If any of the checkboxes are selected, we need to set the first
        // checkboxes's $dirty property to true in order for the validation
        // to work because the directive used in the template is only given
        // the first checkboxes's name. If we don't set this property to true,
        // then validation (visually speaking - the red text) for the checkboxes
        // will only start working if the first checkbox is ticked.
        disciplinesForm.facilityDisciplinesC10.$dirty = true;
        
        $scope.form.disciplines[0].isRequired = true;
        
        angular.forEach($scope.form.disciplines, function(discipline) {
          if (discipline.isSelected) {
            $scope.form.disciplines[0].isRequired = false;
          }
        });
      },
      
      /**
       * Same deal as '$scope.form.validateDisciplines()'.
       */
      validateSectors: function(sectorsForm) {
        sectorsForm.facilitySectorsC10.$dirty = true;
        
        $scope.form.sectors[0].isRequired = true;
        
        angular.forEach($scope.form.sectors, function(sector) {
          if (sector.isSelected) {
            $scope.form.sectors[0].isRequired = false;
          }
        });
      },
      
      /**
       * !Note: Saving, retrieving, autosaving functions are buggy, do not use
       * until fixed. Also sectors and disciplines are not saved into storage,
       * so if this function ends up being fixed, don't forget to include
       * them!
       * 
       * Saves the form to local storage.
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
      
      /**
       * Helper function. Retrieves the item name used for storing form data.
       */
      getStorageItemName: function() {
        return $scope._state.current.name + '-facility';
      },
      
      /**
       *
       * Formats the data for the preview. It gets the names of the selected
       * organization and province. If 'N/A' is selected for organization,
       * clear the '$scope.form.data.organization' object.
       *
       * Note: This function has to be called before the data is submitted
       * to the API because the '$scope.form.formatForApi' ........ !!?
       */
      formatForPreview: function() {
        var facility = angular.copy($scope.form.data.facility);
        facility.disciplines = $scope.form.getSelectedDisciplines();
        facility.sectors = $scope.form.getSelectedSectors();
        facility.primaryContact = angular.copy($scope.form.data.primaryContact);
        facility.contacts = angular.copy($scope.form.data.contacts);
        facility.equipment = angular.copy($scope.form.data.equipment);
        
        // Organization section.
        if ($scope.form.data.facility.organizationId > 0) {
          var e = document.getElementById('facility-organization');
          facility.organization = $scope.form.organizations[e.selectedIndex];
        } else if ($scope.form.data.facility.organizationId == -1) {
          facility.organization = $scope.form.data.organization;
        }
        
        // Province section.
        var e = document.getElementById('facility-province');
        facility.province = $scope.form.provinces[e.selectedIndex];
        
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
        data.disciplines = $scope.form.getSelectedDisciplines();
        data.sectors = $scope.form.getSelectedSectors();
        
        // If 'Other' was selected, clear the ID since it's not a valid
        // ID and the API will reject that.
        if (data.facility.organizationId == -1) {
          data.facility.organizationId = null;
        // Otherise (meaning an existing organization was selected), clear
        // the organization object since we're not creating a new organization.
        } else {
          data.organization = null;
        }
        
        // The first contact is the primary contact.
        data.primaryContact = (data.contacts.splice(0, 1))[0];
        
        return data;
      },
      
      getSelectedDisciplines: function() {
        var selectedDisciplines = [];
        angular.forEach($scope.form.disciplines, function(discipline) {
          if (discipline.isSelected) {  
            selectedDisciplines.push(discipline);
          }
        });
        return selectedDisciplines;
      },
      
      getSelectedSectors: function() {
        var selectedSectors = [];
        angular.forEach($scope.form.sectors, function(sector) {
          if (sector.isSelected) {
            selectedSectors.push(sector);
          }
        });
        return selectedSectors;
      }
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    // Nothing here.
  }
]);