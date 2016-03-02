'use strict';

angular.module('afredApp').controller('FacilitiesFormController',
  ['$rootScope',
   '$scope',
   '$interval',
   'organizationResource',
   'provinceResource',
   'disciplineResource',
   'sectorResource',
   'facilityRepositoryResource',
   'confirmModal',
  function($rootScope,
           $scope,
           $interval,
           organizationResource,
           provinceResource,
           disciplineResource,
           sectorResource,
           facilityRepositoryResource,
           confirmModal) {
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
        sectors: true,
        save: true // Has the saved data been retrieved. 'Create' mode only.
                   // Because the '$scope.form.getSave()' function calls
                   // '$scope.form.getDisciplines()' and
                   // '$scope.form.getSectors()' again
                   // (the '$scope.form.initialise()' function calls them first)
                   // we need this additional flag so that the loading GIF
                   // doesn't stop in between calling '$scope.form.getSave()'
                   // and '$scope.form.save()'.
      },
      
      /**
       * Initialises the form.
       *
       * Side effects:
       * $scope.form.data All form data is attached to this object.
       *
       * Uses/calls/requires:
       * $scope.form.getDisciplines()
       * $scope.form.getOrganizations()
       * $scope.form.getSectors()
       * $scope.form.getProvinces()
       * $scope.form.addContacts()
       * $scope.form.addEquipment()
       * 
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
       * Adds an additional contact object.
       *
       * Side effects:
       * $scope.form.data.contacts Contact object is pushed into this array.
       * $scope.form.contactIndex Index is advanced.
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
       * Removes a contact object.
       *
       * Side effects:
       * $scope.form.data.contacts Contact object of index 'index' is removed
       *     from this array.
       * $scope.form.contactIndex Index is decreased.
       * 
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
       * Adds additional equipment object.
       *
       * Side effects:
       * $scope.form.data.equipment Equipment object is pushed into this array.
       * $scope.form.equipmentIndex Index is advanced.
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
       * Removes an equipment object.
       *
       * Side effects:
       * $scope.form.data.equipment Equipment object is added to this array.
       * $scope.form.equipmentIndex Index is decreased.
       * 
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
       * Gets an array of all organizations.
       *
       * Side effects:
       * $scope.form.organizations Data retrieved is attached to this array.
       *     'N/A' (if found) is moved to them bottom of the array and an
       *     organization called 'Other' is added to the array (after 'N/A')
       *     with an id of -1.
       *
       * Uses/calls/requires:
       * organizationResource
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
       * Gets an array of all provinces.
       *
       * Side effects:
       * $scope.form.provinces Data retrieved is attached to this array.
       *     'N/A' (if found) is moved to them bottom of the array.
       *
       * Uses/calls/requires:
       * provinceResource
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
       *
       * Side effects:
       * $scope.form.disciplines Array of disciplines is attached to this. Will
       *    add a property called 'isSelected' to each array element.
       * $scope.form.loading Set to true at the beginning of the function and
       *     then set to false at the end. 'isRequired' is added to the first
       *     element in the array.
       *
       * Uses/calls/requires:
       * disciplineResource
       * 
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
       *
       * Side effects:
       * $scope.form.sectors Array of sectors is attached to this. See
       *     '$scope.form.getDisciplines()' for more details.
       * $scope.form.loading Set to true at the beginning of the function
       *     and then set to false once all processing is complete.
       *
       * Uses/calls/requires:
       * sectorResource
       * 
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
       * Retrieves a facility for editing.
       *
       * Side effects:
       * $scope.form.fr Promise is attached to this.
       * $scope.form.data Facility data is attached to this.
       *
       * Calls/uses/requires:
       * facilityRepositoryResource
       * $scope.form.getDisciplines()
       * $scope.form.getSectors()
       * 
       * @param {integer} frId Id of the facility repository record to
       *     retrieve.
       * @param {string} token A string that authorises the user to retrieve
       *     the facility repository record.
       * @param {function} failureCallback A function to call if the request
       *     fails.
       */
      getFacilityRepositoryData: function(frId, token, failureCallback) {
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
              failureCallback();
              $scope._warn('Invalid token and/or id provided');
            }
          );        
      },
      
      /**
       * Makes sure that the user has selected at least one discipline. Makes
       * use of '$scope.form.discipline[0].isRequired'.
       *
       * Side effects:
       * $scope.form.disciplines[0].isRequired Set to false if any of the
       *     elements in the array is selected. Otherwise it is set to true.
       * 
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
       *
       * Side effects:
       * localStorage 'facilities', 'disciplines', and 'sectors' are saved.
       * $scope.form.loading.save Set to true at the beginning of the function
       *     call and then set to false once all processing is complete.
       * $scope.form.isStorageSupported Set to false if the operation fails.
       *
       * Uses/calls/requires:
       * $scope.form.provinces.$resolved
       * $scope.form.disciplines.$resolved
       * $scope.form.sectors.$resolved
       * $scope.form.loading.disciplines
       * $scope.form.loading.sectors
       * $scope.form.getLsName()
       * $scope.form.getSelectedDisciplines()
       * $scope.form.getSelectedSectors()
       *  
       * @return {integer} 1 if the operation was successful, 0 if local storage
       *     is not supported, and -1 if form data has not been resolved (the
       *     function will not attempt to save).
       */
      save: function() {
        // Because of async issues, we have to make sure that all data has been
        // retrieved before attempting to save. Otherwise the form might end up
        // overriding actual data with blank data.
        if ($scope.form.organizations.$resolved
            && $scope.form.provinces.$resolved
            && $scope.form.disciplines.$resolved
            && $scope.form.sectors.$resolved
            && !$scope.form.loading.disciplines
            && !$scope.form.loading.sectors) {
          var f = $scope.form.getLsName('facility');
          var d = $scope.form.getLsName('disciplines');
          var s = $scope.form.getLsName('sectors');
          var dData = $scope.form.getSelectedDisciplines(true);
          var sData = $scope.form.getSelectedSectors(true);
          
          try {
            localStorage.setItem(f, angular.toJson($scope.form.data));
            localStorage.setItem(d, angular.toJson(dData));
            localStorage.setItem(s, angular.toJson(sData));
                        
            // Set the loading flag to false.
            $scope.form.loading.save = false;
            
            return 1;
          } catch(e) {
            // Local storage is not supported.
            $scope.form.isStorageSupported = false;
            
            // We need to set this to false if local storage is not supported
            // otherwise the form will never get displayed.
            $scope.form.loading.save = false;
            return 0;
          }
        }
        
        return -1;
      },
      
      /**
       * Retrieves any saved data from local storage.
       *
       * Side effects:
       * $scope.form.data Data retrieved from localstorage is attached to this.
       * $scope.form.loading.save Set to false if the operation fails because
       *     that means that local storage is not supported. We have to set
       *     this to false because in case the '$scope.form.save()' function
       *     doesn't get called, '$scope.form.loading' will always be true.
       *
       * Uses/calls/requires:
       * $scope.form.provinces.$resolved
       * $scope.form.disciplines.$resolved
       * $scope.form.sectors.$resolved
       * $scope.form.loading.disciplines
       * $scope.form.loading.sectors
       *  
       * @returns {integer} 1 if the operation was successful regardless if data
       *     was actually retrieved. 0 if local storage is not supported. -1 if
       *     the form data (organizations, provinces, disciplines, sectors) has
       *     not been resolved. -1 also means that the function did not attempt
       *     to retrieve the data.
       */
      getSave: function() {
        // See '$scope.form.save()' for explanation.
        if ($scope.form.organizations.$resolved
            && $scope.form.provinces.$resolved
            && $scope.form.disciplines.$resolved
            && $scope.form.sectors.$resolved
            && !$scope.form.loading.disciplines
            && !$scope.form.loading.sectors) {
          try {
            var f = localStorage.getItem($scope.form.getLsName('facility'));
            var d = localStorage.getItem($scope.form.getLsName('disciplines'));
            var s = localStorage.getItem($scope.form.getLsName('sectors'));
          
            if (f && d && s) {
              $scope.form.data = angular.fromJson(f);
              $scope.form.getDisciplines(angular.fromJson(d));
              $scope.form.getSectors(angular.fromJson(s));
            }
            return 1;
          } catch(e) {
            // Local storage is not supported.
            $scope.form.isStorageSupported = false;
            
            // See explanation in '$scope.form.save()'.
            $scope.form.loading.save = false;
            
            return 0;
          }
        }
        
        return -1;
      },
      
      /**
       * Clears local storage of any saved form data.
       *
       * Side effects:
       * localStorage 'facilities', 'disciplines', 'sectors' data deleted.
       * $scope.form.isAutosaving Interval stored here is stopped.
       * $scope.form.isStorageSupported Set to false if operation fails.
       * 
       * Uses/calls/requires:
       * $interval
       * $scope.form.initialise()
       *
       * @param dontConfirm If set to true, confirmation modal will not be shown
       *     and localStorage will be cleared immediately and the form will be
       *     reinitialised. If set to false, the confirmation modal will be 
       *     shown and if confirmed, the page will be reloaded (instead of
       *     being reinitialised).
       */
      clearSave: function(dontConfirm) {
        if (dontConfirm) {
          remove();
          $scope.form.initialise();
        } else {
          var modalInstance = confirmModal.open('reset-create-facility-form');
          modalInstance.result.then(function() {
            remove();
            $scope._location.reload();
          });
        }
        
        function remove() {
          try {
            // We have to stop autosaving otherwise clearing the data won't
            // work if the page is reloaded after this function is called (the
            // '$scope.forn.save()' function might have been called before
            // the page was reloaded thereby saving the form data again).
            $interval.cancel($scope.form.isAutosaving);
            
            localStorage.removeItem($scope.form.getLsName('facility'));
            localStorage.removeItem($scope.form.getLsName('disciplines'));
            localStorage.removeItem($scope.form.getLsName('sectors'));
          } catch(e) {
            // Local storage is not supported.
            $scope.form.isStorageSupported = false;
          }
        }
      },
      
      /**
       * Continuously save the form data to local storage every 'interval'
       * milliseconds.
       *
       * Side effects:
       * $scope.form.isAutosaving ID returned from $interval is attached to this
       *     if the operation is successful.
       * 
       * @param {integer} interval Number of milliseconds between each
       *     interval. If not provided, a default of 500 milliseconds is used.
       */
      startAutosave: function(interval) {
        if (!$scope.form.isAutosaving && $scope.form.isStorageSupported) {
          try {
            $scope.form.isAutosaving = $interval(function() {
              $scope.form.save();
            }, interval ? interval : 500);
            $rootScope._formIsAutosaving = $scope.form.isAutosaving;
          } catch(e) {
            // Local storage is not supported.
            $scope.form.isStorageSupported = false;
            $interval.cancel($scope.form.isAutosaving);
          }          
        }
      },
      
      /**
       * Retrieves the item name used for storing form data.
       *
       * Uses/calls/requires:
       * $scope._state.current.name
       * 
       * @param {string} item The item to retrieve (facility, disciplines,
       *     sectors)
       */
      getLsName: function(item) {
        return $scope._state.current.name + '-' + item;
      },
      
      /**
       * Formats the data for the preview. It gets the names of the selected
       * organization and province.
       *
       * Uses/calls/requires:
       * $scope.form.data
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
        
        facility.isPreview = true;
        facility.isNewOrganization =
          !($scope.form.data.facility.organizationId > 0);
          
        return facility;
      },
      
      /**
       * Formats the data to match what the API requires. The API expects a
       * single primary contact and (optionally) regular contacts. In the form,
       * the first contact is assumed to be the primary contact.
       *
       * Calls/uses/requires:
       * $scope.form.data
       * $scope.form.getSelectedDisciplines()
       * $scope.forn.getSelectedSectors()
       * 
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
       *
       * Uses/calls/requires:
       * $scope.form.disciplines
       * 
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
       *
       * Uses/calls/requires:
       * $scope.form.sectors
       * 
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