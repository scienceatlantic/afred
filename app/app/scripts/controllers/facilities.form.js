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
       * Array of available organizations for the 'Organizations' dropdown.
       * @type {array}
       */
      organizations: [],
      
      /**
       * Array of available provinces for the 'Provinces' dropdown.
       * @type {array}
       */
      provinces: [],
      
      /**
       * Array of all disciplines for the 'Disciplines' dropdown.
       * @type {array}
       */
      disciplines: [],
      
      /**
       * Array of all sectors for the 'Sectors' dropdown.
       * @type {array}
       */
      sectors: [],
      
      /**
       * Holds the unique ID returned by '$scope.form.startAutosave()'. Can be
       * used to either stop or prevent '$scope.form.startAutosave()' from
       * running more than one interval at a time.
       * @type {integer}
       */
      isAutosaving: 0,
      
      /**
       * If the '$scope.form.getSave()' method fails, this property will be set
       * to false. This prevents '$scope.form.autosave()' from executing if
       * local storage is not supported.
       * @type {boolean}
       */
      isStorageSupported: true,
      
      /**
       * Loading flags for GIFs. The '$resolved' property from the Angular
       * resource factories are insufficient because we're still doing some
       * processing after the data has been retrieved from the API. These flags
       * will be set to false after all processing has been completed.
       * @type {object}
       */
      loading: {
        disciplines: true,
        sectors: true
      },
      
      /**
       * Initialises the form. All form data is attached to '$scope.form.data'.
       * @param {boolean} isEdit If in edit mode, this will prevent the function
       *     from calling '$scope.form.getDisciplines()' and
       *     '$scope.form.getSectors()' since
       *     '$scope.form.getFacilityRepositoryData()' will call them.
       */
      initialise: function(isEdit) {       
        // Holds all facility data that will be passed to the API.
        $scope.form.data = {
          // Facility information.
          facility: {
            name: null,
            organizationId: null,
            provinceId: null,
            description: null,
            city: null,
            website: null
          },
          
          // Array of selected discipline IDs.
          disciplines: [],
          
          // Array of selected sector IDs.
          sectors: [],
          
          // Primary contact information.
          primaryContact: {},
          
          // Contact(s) information.
          contacts: [],
          
          // Equipment information
          equipment: []
        };
        
        // Get an array of all available organizations.
        $scope.form.getOrganizations();
        
        // Get an array all available provinces.
        $scope.form.getProvinces();
       
        if (!isEdit) {
          // Get an array of all disciplines.
          $scope.form.getDisciplines();
          
          // Get an array of all sectors.
          $scope.form.getSectors();
        }
        
        // Add the first contact to the form.
        $scope.form.addContact();
        
        // Add the first equipment to the form.
        $scope.form.addEquipment();
      },
      
      /**
       * Adds an additional contact object to the '$scope.form.data.contacts'
       * array and advances '$scope.form.contactIndex' to point to it.
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
       * @param {integer} index Array index of '$scope.form.data.contacts' to
       *     delete.
       */
      removeContact: function(index) {
        // The first contact cannot be removed.
        if (index !== 0) {
          $scope.form.data.contacts.splice(index, 1);
          
          // If the user is currently viewing the contact that is being removed
          // or if '$scope.form.contactIndex' is more than the total number of
          // contacts in the array itself, decrease '$scope.form.contactIndex'
          // (ie. point to the previous contact).
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
       * @param {number} index Array index of '$scope.form.data.equipment' to
       *     delete.
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
       * Gets an array of all organizatoins and attaches it to
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
       * Gets an array of all provinces and attaches it to
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
       * Gets an array of all disciplines and attaches it to
       * '$scope.form.disciplines'.
       * @param {array} disciplines If in edit mode, this will mark any already
       *     selected checkboxes.
       */
      getDisciplines: function(disciplines) {
        // Set loading flag to true.
        $scope.form.loading.disciplines = true;
        
        $scope.form.disciplines = disciplineResource.queryNoPaginate(null,
          function() {
            // Adds an 'isSelected' property for the checkboxes. Used in the
            // form.
            angular.forEach($scope.form.disciplines, function (discipline) {
              discipline.isSelected = false;
            });
            
            // For the form validation. We're only attaching it to the first
            // element in the array.
            if ($scope.form.disciplines.length) {
              $scope.form.disciplines[0].isRequired = true;
            }
            
            // This part will check all the selected checkboxes from the data in
            // 'disciplines'.
            if (disciplines) {
              var formDislength = $scope.form.disciplines.length;
              var disLength = disciplines.length;
              var countSelected = 0;
              
              for (var i = 0; i < formDislength; i++) {
                var id = $scope.form.disciplines[i].id;
                if (disciplines.indexOf(id) != -1) {
                  $scope.form.disciplines[i].isSelected = true;
                  
                  // This line ensures that the form doesn't complain that
                  // the mandatory fields have not been filled.
                  $scope.form.disciplines[0].isRequired = false;
                  
                  // Keep count of the number of items we've processed. If
                  // we've gone through the entire array from the API, there's
                  // no need to complete the loop.                  
                  if (++countSelected >= disLength) {
                    break;
                  }
                }
              }
            }
            
            // Set loading flag to false.
            $scope.form.loading.disciplines = false;
          }
        );
      },
      
      /**
       * Gets an array of all sectors and attaches it to '$scope.form.sectors'.
       * @param {array} sectors If in edit mode, this will mark any already
       *     selected checkboxes.
       */
      getSectors: function(sectors) {
        // Set loading flag to true.
        $scope.form.loading.sectors = true;
        
        $scope.form.sectors = sectorResource.queryNoPaginate(null,
          function() {
            // Same deal as '$scope.form.getDisciplines()'.
            angular.forEach($scope.form.sectors, function(sector) {
              sector.isSelected = false;
            });
            
            if ($scope.form.sectors.length) {
              $scope.form.sectors[0].isRequired = true;
            }
            
            // This part will check all the selected checkboxes from the data in
            // 'sectors'.
            if (sectors) {
              var formSecLength = $scope.form.sectors.length;
              var secLength = sectors.length;
              var countSelected = 0;
              
              // For a more detailed explanation of this part see
              // '$scope.form.getDisciplines()'.
              for (var i = 0; i < formSecLength; i++) {
                var id = $scope.form.sectors[i].id;
                if (sectors.indexOf(id) != -1) {
                  $scope.form.sectors[i].isSelected = true;
                  $scope.form.sectors[0].isRequired = false;
                  
                  if (++countSelected >= secLength) {
                    break;
                  }
                }
              }
            }
            
            // Set loading flag to false.
            $scope.form.loading.sectors = false;
          }
        );
      },
      
      /**
       * Retrieves a facility for editing and attaches it '$scope.form.data'.
       * @param {integer} frId Id of the facility repository record to
       *     retrieve.
       * @param {string} token A string that authorises the user to retrieve
       *     the facility repository record.
       */
      getFacilityRepositoryData: function(frId, token) {
        $scope.form.fr =
          facilityRepositoryResource.get({
              facilityRepositoryId: frId,
              token: token
            },
            function() {
              $scope.form.data = angular.copy($scope.form.fr.data);
              
              // Since the form stores all contacts (primary or not) in the
              // '$scope.form.contacts' array, we need to format the data
              // retrieved from the API to match the form. The first part checks
              // if there's any data in the contacts array (since non-primary)
              // contacts are optional.
              if (angular.isArray($scope.form.data.contacts)) {
                $scope.form.data.contacts.unshift(
                  $scope.form.fr.data.primaryContact);
              } else {
                $scope.form.data.contacts = [];
                $scope.form.data.contacts.push(
                  $scope.form.fr.data.primaryContact);
              }
              
              // Get the disciplines and sectors for the form. We're calling
              // these methods now because we needed '$scope.form.fr.data'
              // to be able to mark all the selected disciplines and sectors.
              $scope.form.getDisciplines($scope.form.fr.data.disciplines);
              $scope.form.getSectors($scope.form.fr.data.sectors);
              
            }, function() {
              $scope._warn('Invalid token and/or id provided');
            }
          );        
      },
      
      /**
       * Makes sure that the user has selected at least one discipline. Makes
       * use of '$scope.form.discipline[0].isRequired'.
       * @var {object} disciplinesForm Instance of the 'disciplinesForm'.
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
       * Saves the form data to local storage.
       */
      save: function() {
        var f = $scope.form.getStorageItemName('facility');
        var d = $scope.form.getStorageItemName('disciplines');
        var s = $scope.form.getStorageItemName('sectors');
        var dData = $scope.form.getSelectedDisciplines(true);
        var sData = $scope.form.getSelectedSectors(true);
        
        try {
          localStorage.setItem(f, JSON.stringify($scope.form.data));
          localStorage.setItem(d, JSON.stringify(dData));
          localStorage.setItem(s, JSON.stringify(sData));
        } catch(e) {
          // Do nothing if local storage is not supported.
        }
      },
      
      /**
       * Retrieves any saved data from local storage.
       */
      getSave: function(frId, token) {
        var f = $scope.form.getStorageItemName('facility');
        var d = $scope.form.getStorageItemName('disciplines');
        var s = $scope.form.getStorageItemName('sectors');

        try {
          if (localStorage.getItem(f)
              && localStorage.getItem(d)
              && localStorage.getItem(s)) {
            $scope.form.data = JSON.parse(localStorage.getItem(f));
            $scope.form.getDisciplines(JSON.parse(localStorage.getItem(d)));
            $scope.form.getSectors(JSON.parse(localStorage.getItem(s)));
          }
        } catch(e) {
          // Local storage is not supported.
          $scope.form.isStorageSupported = false;
        }
      },
      
      /**
       * Clears local storage of any saved form data.
       */
      clearSave: function() {
        try {
          var f = $scope.form.getStorageItemName('facility');
          var d = $scope.form.getStorageItemName('disciplines');
          var s = $scope.form.getStorageItemName('sectors');
          
          localStorage.removeItem(f);
          localStorage.removeItem(d);
          localStorage.removeItem(s);
        } catch(e) {
          $scope._info('Local storage is not supported');
        }
      },
      
      /**
       * Continuously save the form data to local storage every 'interval'
       * milliseconds. Calls '$scope.form.save()'.
       * @param {integer} interval Number of milliseconds between each
       *     interval. If not provided, a default of 500 milliseconds is used.
       */
      startAutosave: function(interval) {
        if (!$scope.form.isAutosaving || $scope.form.isStorageSupported) {
          try {
            $scope.form.isAutosaving = $interval(function() {
              $scope.form.save();
            }, interval ? interval : 500);       
          } catch(e) {
            // Do nothing if local storage is not supported.
          }          
        }
      },
      
      /**
       * Retrieves the item name used for storing form data.
       * @param {string} item The item to retrieve (facility, disciplines,
       *     sectors)
       */
      getStorageItemName: function(item) {
        return $scope._state.current.name + '-' + item;
      },
      
      /**
       * Formats the data for the preview. It gets the names of the selected
       * organization and province.
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
       * @return {object} Facility object.
       */
      formatForApi: function() {
        var data = angular.copy($scope.form.data);
        data.disciplines = $scope.form.getSelectedDisciplines(true);
        data.sectors = $scope.form.getSelectedSectors(true);
        
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
      
      /**
       * Returns an array of selected disciplines.
       * @param {boolean} idOnly If true, the function will only return an
       *     array of selected IDs instead of an array of selected discipline
       *     objects.
       * @return {array} Selected disciplines.
       */
      getSelectedDisciplines: function(idOnly) {
        var selectedDisciplines = [];
        angular.forEach($scope.form.disciplines, function(discipline) {
          if (discipline.isSelected) {  
            selectedDisciplines.push(idOnly ? discipline.id : discipline);
          }
        });
        return selectedDisciplines;
      },
      
      /**
       * Returns an array of selected sectors.
       * @param {boolean} idOnly If true, the function will only return an
       *     array of selected IDs instead of an array of selected sector
       *     objects.
       * @return {array} Selected sectors.
       */
      getSelectedSectors: function(idOnly) {
        var selectedSectors = [];
        angular.forEach($scope.form.sectors, function(sector) {
          if (sector.isSelected) {
            selectedSectors.push(idOnly ? sector.id : sector);
          }
        });
        return selectedSectors;
      }
    };
  }
]);