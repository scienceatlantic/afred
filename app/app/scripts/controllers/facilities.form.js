'use strict';

angular.module('afredApp').controller('FacilitiesFormController',
  ['$scope',
   '$interval',
   'organizationResource',
   'provinceResource',
   'disciplineResource',
   'sectorResource',
   'facilityRepositoryResource',
   'confirmModal',
  function($scope,
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
       * 
       * @type {object}
       */
      data: {},
          
      /**
       * Holds an instance of 'facilityRepositoryResource'.
       *
       * @type {Angular resource}
       */
      facilityRepository: {},
      
      /**
       * Keeps track of the current 'contact' being viewed.
       *
       * @type {integer}
       */
      contactIndex: null,
      
      /**
       * Keeps track of the current 'equipment' being viewed.
       *
       * @type {integer}
       */
      equipmentIndex: null,
      
      /**
       * Array of available organizations for the 'Organizations' dropdown.
       *
       * @type {array}
       */
      organizations: [],
      
      /**
       * Array of available provinces for the 'Provinces' dropdown.
       *
       * @type {array}
       */
      provinces: [],
      
      /**
       * Array of all disciplines for the 'Disciplines' dropdown.
       *
       * @type {array}
       */
      disciplines: [],
      
      /**
       * Array of all sectors for the 'Sectors' dropdown.
       *
       * @type {array}
       */
      sectors: [],
      
      /**
       * Holds the unique ID returned by '$scope.form.startAutosave()'. Can be
       * used to either stop or prevent '$scope.form.startAutosave()' from
       * running more than one interval at a time.
       *
       * @type {integer}
       */
      isAutosaving: 0,
      
      /**
       * Loading flags for GIFs. The '$resolved' property from the Angular
       * resource factories are insufficient because we're still doing some
       * processing after the data has been retrieved from the API. These flags
       * will be set to false after all processing has been completed.
       *
       * @type {object}
       */
      loading: {
        disciplines: true,
        sectors: true,
        save: true // Has the saved data been retrieved. 'Create' mode only.
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
       *     from calling:
       *     '$scope.form.getOrganizations()',
       *     '$scope.form.getProvinces()',
       *     '$scope.form.getDisciplines()', and
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
       
        if (!isEdit) {
          // Get an array of all available organizations.
          $scope.form.getOrganizations();
          
          // Get an array all available provinces.
          $scope.form.getProvinces();
        
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
          yearManufactured: null,
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
       *
       * @param (integer) organizationId If it edit mode, the facility being
       *     edited could belong to an organization that is (or was recently
       *     made) hidden. If it is hidden, the API wouldn't have returned it,
       *     so we need to retrieve it and manually push it into the array.
       */
      getOrganizations: function(organizationId) {
        $scope.form.organizations = organizationResource.queryNoPaginate({
          isHidden: 0
        }, function() {
          // Find the 'N/A' option and push it to the end of the array.
          for (var i = 0; i < $scope.form.organizations.length; i++) {
            if ($scope.form.organizations[i].name == 'N/A') {
              var na = ($scope.form.organizations.splice(i, 1))[0];
              $scope.form.organizations.push(na);
              break;
            }
          }
          
          // Hidden organization check.
          if (organizationId) {
            var isFound = false;
            for (var i = 0; i < $scope.form.organizations.length; i++) {
              if ($scope.form.organizations[i].id == organizationId) {
                isFound = true;
                break;
              }
            }
            
            if (!isFound) {
              organizationResource.get({ organizationId: organizationId },
                function(data) {                  
                  $scope.form.organizations.push(data);  
                }
              );              
            }
          }
          
          // Add an option for 'Other'.
          $scope.form.organizations.push({ id: -1, name: 'Other' });
        }, function(response) {
          $scope._httpError(response);
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
       *
       * @param {integer} provinceId See reason in
       *     '$scope.form.getOrganizations()'.
       */
      getProvinces: function(provinceId) {
        $scope.form.provinces = provinceResource.queryNoPaginate({
          isHidden: 0
        }, function() {
          // Find the 'N/A' option and push it to the end of the array.
          for (var i = 0; i < $scope.form.provinces.length; i++) {
            if ($scope.form.provinces[i].name == 'N/A') {
              var na = ($scope.form.provinces.splice(i, 1))[0]; 
              $scope.form.provinces.push(na);
              break;
            }
          }
          
          // Hidden province check.
          if (provinceId) {
            var isFound = false;
            for (var i = 0; i < $scope.form.provinces.length; i++) {
              if ($scope.form.provinces[i].id == provinceId) {
                isFound = true;
                break;
              }
            }
            
            if (!isFound) {
              provinceResource.get({ provinceId: provinceId },
                function(data) {
                  $scope.form.provinces.push(data);
                }
              );                
            }
          }
        }, function (response) {
          $scope._httpError(response);
        });
      },
      
      /**
       * Gets an array of all disciplines and attaches it to
       * '$scope.form.disciplines'.
       *
       * Side effects:
       * $scope.form.disciplines Array of disciplines is attached to this. Will
       *    add a property called 'isSelected' to each array element.
       * $scope.form.loading Set to true at the beginning of the function and
       *     then set to false at the end.
       *
       * Uses/calls/requires:
       * disciplineResource
       * 
       * @param {array} disciplines This will mark any already selected
       *     checkboxes.
       */
      getDisciplines: function(disciplines) {
        // Set loading flag to true.
        $scope.form.loading.disciplines = true;
        
        $scope.form.disciplines = disciplineResource.queryNoPaginate(null,
          function() {
            // Adds an 'isSelected' property for the checkboxes. It is used in
            // the form.
            angular.forEach($scope.form.disciplines, function (discipline) {
              discipline.isSelected = false;
            });
            
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
          }, function(response) {
            $scope._httpError(response);
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
       * @param {array} sectors This will mark any already selected checkboxes.
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
                  
                  if (++countSelected >= secLength) {
                    break;
                  }
                }
              }
            }
            
            // Set loading flag to false.
            $scope.form.loading.sectors = false;
          }, function(response) {
            $scope._httpError(response);
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
       * $scope._state.go()
       * 
       * @param {integer} frId Id of the facility repository record to
       *     retrieve.
       * @param {string} token A string that authorises the user to retrieve
       *     the facility repository record.
       */
      getFacilityRepositoryData: function(frId, token) {
        $scope.form.fr = facilityRepositoryResource.get({
          facilityRepositoryId: frId,
          token: token
        }, function() {
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
          
          // Get the list of organizations and provinces. We're calling them
          // now because we need to check for hidden organizations and
          // provinces.
          var organizationId = $scope.form.fr.data.facility.organizationId;
          $scope.form.getOrganizations(organizationId);
          var provinceId = $scope.form.fr.data.facility.provinceId;
          $scope.form.getProvinces(provinceId);
          
          // Get the disciplines and sectors for the form. We're calling
          // these methods now because we needed '$scope.form.fr.data'
          // to be able to mark all the selected disciplines and sectors.
          $scope.form.getDisciplines($scope.form.fr.data.disciplines);
          $scope.form.getSectors($scope.form.fr.data.sectors);
          
        }, function(response) {
          $scope._httpError(response);
        });        
      },
      
      /**
       * Note: If this function is not being used, set '$scope.loading.save'
       * to false manually. Otherwise the form will not be displayed.
       *
       * Retrieve existing data from locol storage then continuously save the
       * form data to local storage every 'interval' milliseconds.
       *
       * Side effects:
       * $scope.form.isAutosaving ID returned from $interval is attached to this
       *     if the operation is successful.
       * $scope.form.data Data retrieved from local storage is attached to this.
       * $scope.form.loading.save Is set to false once the data has been
       *     retrieved or if the operation fails.
       *
       * Calls/uses/requires:
       * $interval
       * $scope._state.current.name
       * $scope.form.organizations.$resolved
       * $scope.form.provinces.$resolved
       * $scope.form.disciplines.$resolved
       * $scope.form.sectors.$resolved
       * $scope.form.loading.disciplines
       * $scope.form.loading.sectors
       * $scope.form.getDisciplines()
       * $scope.form.getSectors()
       * 
       * @param {integer} interval Number of milliseconds between each
       *     interval. If not provided, a default of 500 milliseconds is used.
       */
      startAutosave: function(interval) {
        // Local storage item names.
        var f = $scope._state.current.name + '-' + 'facility';
        var d = $scope._state.current.name + '-' + 'disciplines';
        var s = $scope._state.current.name + '-' + 'sectors';

        // We're putting the code below inside an interval because we have to
        // test if all the form data has fully loaded before attempting to
        // retrieve and save data. Otherwise data might be overwritten with
        // blank data.
        var intervalId = $interval(function() {
          if ($scope.form.organizations.$resolved
            && $scope.form.provinces.$resolved
            && $scope.form.disciplines.$resolved
            && $scope.form.sectors.$resolved
            && !$scope.form.loading.disciplines
            && !$scope.form.loading.sectors) {
            // Once the condition passes (i.e. the form has loaded), cancel the
            // interval.
            $interval.cancel(intervalId);

            // This is in a try/catch block because we want to test if
            // local storage is supported by the browser. If it's not, quit.
            try {
              var data = angular.fromJson(localStorage.getItem(f));

              if (data) {
                $scope.form.data = data;
                $scope.form.getDisciplines(angular.fromJson(localStorage.getItem(d)));
                $scope.form.getSectors(angular.fromJson(localStorage.getItem(s)));
              }              
              
              // Loading flag is set to true.
              $scope.form.loading.save = false;
            } catch(e) {
              
              // This means that local storage is (probably) not supported. So
              // The loading flag still has to be set to true, otherwise the
              // form will not be shown.
              $scope.form.loading.save = false;
              return;
            }           
            
            // Continuously save data (if it's not already doing so).
            if (!$scope.form.isAutosaving) {
              try {
                $scope.form.isAutosaving = $interval(function() {
                  var dData = 
                    $scope._form.cb.getSelected($scope.form.disciplines, true);
                  var sData = 
                    $scope._form.cb.getSelected($scope.form.sectors, true);
            
                  localStorage.setItem(f, angular.toJson($scope.form.data));
                  localStorage.setItem(d, angular.toJson(dData));
                  localStorage.setItem(s, angular.toJson(sData));               
                }, interval ? interval : 500);
              } catch(e) {
                $interval.cancel($scope.form.isAutosaving);
              }          
            }
          }
        }, 500);
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
       * $scope._state.current.name
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
            // work if the page is reloaded after this function is called.
            $interval.cancel($scope.form.isAutosaving);
            
            var f = $scope._state.current.name + '-' + 'facility';
            var d = $scope._state.current.name + '-' + 'disciplines';
            var s = $scope._state.current.name + '-' + 'sectors';
            localStorage.removeItem(f);
            localStorage.removeItem(d);
            localStorage.removeItem(s);
          } catch(e) {
            // Do nothing if local storage is not supported.
          }
        }
      },
      
      /**
       * Formats the data for the preview. 
       * 
       * Uses/calls/requires:
       * $scope.form.data
       * 
       * @return {object} Facility data in this format:
       *     {
       *       name: '',
       *       city: '',
       *       ...
       *       sectors: [{
       *         id: #,
       *         name: ''
       *       },...],
       *       disciplines: [{
       *         id: #,
       *         name: ''
       *       },...],
       *       primaryContact: {
       *         firstName: '',
       *         lastName: '',
       *         ... 
       *       },
       *       contacts: [{
       *         firstName: '',
       *         lastName: '',
       *         ...
       *       },...]
       *       equipment: [{
       *         type: '',
       *         model: '',
       *         ...
       *       },...]
       *     }
       */
      formatForPreview: function() {
        var f = angular.copy($scope.form.data.facility);
        f.disciplines = $scope._form.cb.getSelected($scope.form.disciplines);
        f.sectors = $scope._form.cb.getSelected($scope.form.sectors);
        f.primaryContact = angular.copy($scope.form.data.primaryContact);
        f.contacts = angular.copy($scope.form.data.contacts);
        f.equipment = angular.copy($scope.form.data.equipment);
        
        // Organization section. Grabs the selected organization's data if 
        // an existing organization was selected, otherwise the name of the new
        // organization is copied.
        if ($scope.form.data.facility.organizationId > 0) {
          var e = document.getElementById('facility-organization');
          f.organization = $scope.form.organizations[e.selectedIndex];
        } else if ($scope.form.data.facility.organizationId == -1) {
          f.organization = $scope.form.data.organization;
        }
        
        // Province section. Grabs the selected province's data.
        var e = document.getElementById('facility-province');
        f.province = $scope.form.provinces[e.selectedIndex];

        return f;
      },
      
      /**
       * Formats the data to match what the API requires. The API expects a
       * single primary contact and (optionally) regular contacts. In the form,
       * the first contact is assumed to be the primary contact.
       *
       * Calls/uses/requires:
       * $scope.form.data
       * $scope._form.cb.getSelected()
       * $scope._form.cb.getSelected()
       * 
       * @return {object} Facility object.
       */
      formatForApi: function() {
        var d = angular.copy($scope.form.data);
        d.disciplines = $scope._form.cb.getSelected($scope.form.disciplines, true);
        d.sectors = $scope._form.cb.getSelected($scope.form.sectors, true);
        
        // If 'Other' was selected, clear the ID since it's not a valid
        // ID and the API will reject that.
        if (d.facility.organizationId == -1) {
          d.facility.organizationId = null;
        // Otherwise (meaning an existing organization was selected), clear
        // the organization object since we're not creating a new organization.
        } else {
          d.organization = null;
        }
        
        // The first contact is the primary contact.
        d.primaryContact = (d.contacts.splice(0, 1))[0];
        
        return d;
      }
    };
  }
]);
