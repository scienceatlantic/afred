'use strict';

angular.module('afredApp').controller('FacilitiesFormController',
  ['$scope',
   '$timeout',
   '$q',
   'confirmModal',
   'DisciplineResource',
   'RepositoryResource',
   'OrganizationResource',
   'ProvinceResource',
   'SectorResource',
  function($scope,
           $timeout,
           $q,
           confirmModal,
           DisciplineResource,
           RepositoryResource,
           OrganizationResource,
           ProvinceResource,
           SectorResource) {
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
       * Holds an instance of 'RepositoryResource'.
       *
       * @type {Angular resource}
       */
      facilityRepository: {},
      
      /**
       * Keeps track of the current 'contact' being viewed.
       *
       * @type {number}
       */
      contactIndex: null,
      
      /**
       * Keeps track of the current 'equipment' being viewed.
       *
       * @type {number}
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
       * Holds the unique ID returned by `$scope.form.startAutosave()`.
       *
       * @type {number}
       */
      isAutosaving: 0,


      /**
       * Autosave interval (`$scope.form.autosave()` method).
       * 
       * @type {number} Milliseconds
       */
      autosaveInterval: 500,

      /**
       * The form is given a unique ID so that we can ensure the 
       * `$scope.form.autosave()` method is only running a single interval.
       * 
       * @type {number}
       */
      id: Math.floor((Math.random() + 1) * 100000),
      
      /**
       * Initialises the form.
       * 
       * @sideeffect $scope._persist.facilitySubmissionFormId Set to 
       *     `$scope.form.id`.
       * @sideeffect $scope.form.data All form data is attached to this object.
       *
       * @requires $scope.form.addContacts()
       * @requires $scope.form.addEquipment()
       * @requires $scope.form.getDisciplines()
       * @requires $scope.form.getOrganizations()
       * @requires $scope.form.getProvinces()
       * @requires $scope.form.getSectors()
       * 
       * @param {number=undefined} frId If editing a facility, this is the 
       *     facility repository ID of the facility.
       * @param {string=undefined} token If editing a facility, this is the 
       *     unique token that authorises the edit instance.
       */
      initialise: function(frId, token) {
        // Persist form ID.
        $scope._persist.facilitySubmissionFormId = $scope.form.id;

        var deferred = $q.defer();
        var promises = [];

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
          
          // (Non-primary) Contact information.
          contacts: [],
          
          // Equipment information
          equipment: []
        };
       
        if (!frId || !token) {
          promises.push($scope.form.getOrganizations());
          promises.push($scope.form.getProvinces());
          promises.push($scope.form.getDisciplines());
          promises.push($scope.form.getSectors());
        } else {
          promises.push($scope.form.getFacilityRepositoryData(frId, token));
        }
        
        // Add the first contact to the form.
        $scope.form.addContact();
        
        // Add the first equipment to the form.
        $scope.form.addEquipment();

        // Resolve.
        $q.all(promises).then(function() {
          deferred.resolve();
        });

        return deferred.promise;
      },
      
      /**
       * Adds an additional contact object.
       *
       * @sideeffect $scope.form.contactIndex Index is advanced.
       * @sideeffect $scope.form.data.contacts Contact object is pushed into 
       *     this array.
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
       * @sideeffect $scope.form.contactIndex Index might be reduced.
       * @sideeffect $scope.form.data.contacts Contact object of index `index` 
       *     is removed from this array.
       * 
       * @param {number} index Array index of `$scope.form.data.contacts` to
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
       * @sideeffect $scope.form.data.equipment Equipment object is pushed into 
       *     this array.
       * @sideeffect $scope.form.equipmentIndex Index is advanced.
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
       * @sideffect $scope.form.data.equipment Equipment object is added to this
       *     array.
       * @sideeffect $scope.form.equipmentIndex Index might be decreased.
       * 
       * @param {number} index Array index of `$scope.form.data.equipment` to
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
       * @sideeffect $scope.form.organizations Data retrieved is stored here.
       *     'N/A' (if found) is moved to them bottom of the array and an
       *     organization called 'Other' is added to the array (after 'N/A')
       *     with an id of -1.
       *
       * @requires $q
       * @requires $scope._httpError This is called if any AJAX call fails.
       * @requires OrganizationResource
       *
       * @param (number=undefined) organizationId If it edit mode, the facility
       *     being edited could belong to an organization that is (or was 
       *     recently made) hidden. If it is hidden, the API wouldn't have 
       *     returned it, so we need to retrieve it and push it into the array.
       * 
       * @returns {promise} Promise that resolves to an array of organizations.
       */
      getOrganizations: function(organizationId) {
        var getOrgDeferred = $q.defer();
        var getHiddenOrgDeferred = $q.defer();
        var isHidden;
        
        $scope.form.organizations = OrganizationResource.queryNoPaginate({
          isHidden: 0
        }, function() {
          // Find the 'N/A' option and push it to the end of the array.
          $scope.form.organizations.some(function(o, i) {
            if (o.name === 'N/A') {
              var na = ($scope.form.organizations.splice(i, 1))[0];
              return $scope.form.organizations.push(na);
            }
          });
          
          // Check if organization is hidden, if it is, add it to array.
          if (organizationId) {
            isHidden = !$scope.form.organizations.some(function(o) {
              return o.id === organizationId;
            });
            
            if (isHidden) {
              OrganizationResource.get({ organizationId: organizationId },
                function(data) {                  
                  $scope.form.organizations.push(data);
                  getHiddenOrgDeferred.resolve();  
                }, function(response) {
                  $scope._httpError(response);
                }
              );              
            }
          }
          if (!organizationId || !isHidden) {
            getHiddenOrgDeferred.resolve();
          }

          // Add an option for 'Other'.
          $scope.form.organizations.push({ id: -1, name: 'Other' });

          // Resolve.
          getHiddenOrgDeferred.promise.then(function() {
            getOrgDeferred.resolve($scope.form.organizations);
          });
        }, function(response) {
          $scope._httpError(response);
        });

        return getOrgDeferred.promise;
      },
      
      /**
       * Gets an array of all provinces.
       *
       * @sideeffect $scope.form.provinces Data retrieved is stored here.
       *     'N/A' (if found) is moved to them bottom of the array.
       * 
       * @requires $q
       * @requires $scope._httpError This is called if any AJAX call fails.
       * @requires ProvinceResource
       *
       * @param {number=undefined} provinceId See description in
       *     `$scope.form.getOrganization`.
       * 
       * @returns {promise} Promise that resolves to an array of provinces.
       */
      getProvinces: function(provinceId) {
        var getProvinceDeferred = $q.defer();
        var getHiddenProvinceDeferred = $q.defer();
        var isHidden;

        $scope.form.provinces = ProvinceResource.queryNoPaginate({
          isHidden: 0
        }, function() {
          // Find the 'N/A' option and push it to the end of the array.
          $scope.form.provinces.some(function(p, i) {
            if (p.name === 'N/A') {
              var na = ($scope.form.provinces.splice(i, 1))[0]; 
              return $scope.form.provinces.push(na);
            }
          });
          
          // Check if province is hidden, if it is, add it to array.
          if (provinceId) {
            isHidden = $scope.form.provinces.some(function(p) {
              return p.id === provinceId;
            });
            
            if (isHidden) {
              ProvinceResource.get({ provinceId: provinceId },
                function(data) {
                  $scope.form.provinces.push(data);
                  getHiddenProvinceDeferred.resolve();
                }, function(response) {
                  $scope._httpError(response);
                }
              );                
            }
          }
          if (!provinceId || !isHidden) {
            getHiddenProvinceDeferred.resolve();
          }

          // Resolve.
          getHiddenProvinceDeferred.promise.then(function() {
            getProvinceDeferred.resolve($scope.form.provinces);
          });
        }, function (response) {
          $scope._httpError(response);
        });

        return getProvinceDeferred.promise;
      },
      
      /**
       * Gets an array of all disciplines.
       *
       * @sideeffect $scope.form.disciplines Array of disciplines is store here.
       *     A property called `isSelected` will be added to each discipline
       *     object in the array.
       *
       * @requires $q
       * @requires $scope._httpError This is called if any AJAX call fails.
       * @requires DisciplineResource
       * 
       * @param {Array.<number>=undefined} disciplines Array of IDs. If
       *     provided, the `isSelected` property of each matching discipline is
       *     marked true.
       * 
       * @returns {promise} Promise that resolves to an array of disciplines.
       */
      getDisciplines: function(disciplines) {
        var deferred = $q.defer();

        $scope.form.disciplines = DisciplineResource.queryNoPaginate(null,
          function() {
            // Adds an `isSelected` property for the checkboxes. It is used in
            // the form.
            $scope.form.disciplines.forEach(function(d, i) {
              $scope.form.disciplines[i].isSelected = false;
            });
            
            // This part will check all the selected checkboxes from the data in
            // `disciplines`.
            if (disciplines) {
              // Create an object where each property is the discipline's ID
              // and it's value is the array's index.
              var obj = {};
              $scope.form.disciplines.forEach(function(d, i) {
                obj[d.id] = i;
              });

              disciplines.forEach(function(id) {
                if (obj.hasOwnProperty(id)) {
                  $scope.form.disciplines[obj[id]].isSelected = true;
                }
              });
            }
            
            // Resolve.
            deferred.resolve($scope.form.disciplines);
          }, function(response) {
            $scope._httpError(response);
          }
        );

        return deferred.promise;
      },
      
      /**
       * Gets an array of all sectors.
       *
       * @sideeffect $scope.form.sectors Array of sectors is attached to this. 
       *     See `$scope.form.getDisciplines()` for more details.
       *
       * @requires $q
       * @requires $scope._httpError This is called if any AJAX call fails.
       * @requires SectorResource
       * 
       * @param {Array.<number>=undefined} sectors Array if IDs. If provided,
       *     the `isSelected` property of each matching sector is marked true.
       */
      getSectors: function(sectors) {
        var deferred = $q.defer();

        $scope.form.sectors = SectorResource.queryNoPaginate(null,
          function() {
            // Same deal as `$scope.form.getDisciplines()`.      
            $scope.form.sectors.forEach(function(s, i) {
              $scope.form.sectors[i].isSelected = false;
            });

            // This part will check all the selected checkboxes from the data in
            // 'sectors'.
            if (sectors) {
              var obj = {};
              $scope.form.sectors.forEach(function(s, i) {
                obj[s.id] = i;
              });

              sectors.forEach(function(id) {
                if (obj.hasOwnProperty(id)) {
                  $scope.form.sectors[obj[id]].isSelected = true;
                }
              });
            }

            // Resolve.
            deferred.resolve($scope.form.sectors);
          }, function(response) {
            $scope._httpError(response);
          }
        );

        return deferred.promise;
      },
      
      /**
       * Retrieves a facility for editing.
       *
       * @sideeffect $scope.form.data Facility data retrieved is stored here.
       * @sideeffect $scope.form.fr Data returned from 
       *     `RepositoryResource.get()` is stored here.
       *
       * @requires $q
       * @requires $scope._httpError This is called if any AJAX call fails.
       * @requires $scope.form.getDisciplines()
       * @requires $scope.form.getOrganizations()
       * @requires $scope.form.getProvinces()
       * @requires $scope.form.getSectors()
       * @requires RepositoryResource
       * 
       * @param {number=undefined} frId Id of the facility repository record to
       *     retrieve.
       * @param {string=undefined} token A string that authorises the user to 
       *     retrieve the facility repository record.
       * 
       * @returns {promise} Promise that resolves to facility data.
       */
      getFacilityRepositoryData: function(frId, token) {
        var deferred = $q.defer();
        var pr = []; // Holds promises.

        $scope.form.fr = RepositoryResource.get({
          facilityRepositoryId: frId,
          token: token
        }, function(fr) {
          $scope.form.data = angular.copy(fr.data);

          // Alias
          var data = $scope.form.data;
          
          // Since the form stores all contacts (primary or not) in the
          // '$scope.form.contacts' array, we need to format the data
          // retrieved from the API to match the form. The first part checks
          // if there's any data in the contacts array (since non-primary
          // contacts are optional).
          if (Array.isArray(data.contacts)) {
            data.contacts.unshift(data.primaryContact);
          } else {
            data.contacts = [];
            data.contacts.push(data.primaryContact);
          }
          
          // Get the list of organizations and provinces. We're calling them
          // now because we need to check for hidden organizations and
          // provinces.
          pr.push($scope.form.getOrganizations(data.facility.organizationId));
          pr.push($scope.form.getProvinces(data.facility.provinceId));
          
          // Get the disciplines and sectors for the form. We're calling
          // these methods now because we needed '$scope.form.fr.data'
          // to be able to mark all the selected disciplines and sectors.
          pr.push($scope.form.getDisciplines(data.disciplines));
          pr.push($scope.form.getSectors(data.sectors));

          // Resolve.
          $q.all(pr).then(function() {
            deferred.resolve(data);
          });
        }, function(response) {
          $scope._httpError(response);
        });

        return deferred.promise;        
      },
      
      /**
       * Retrieves form data from local storage (if found) and then continuously
       * save form data every `$scope.form.autosaveInterval` milliseconds.
       *
       * @sideeffect $scope.form.isAutosaving ID returned from `$timeout` is 
       *     stored here if the operation is successful.
       * @sideeffect $scope.form.data Data retrieved from local storage is 
       *     stored here.
       *
       * @requires $q
       * @requires $scope._form.cb.getSelected()
       * @requires $scope._state.current.name
       * @requires $scope._state.is()
       * @requires $scope._info()
       * @requires $scope._warn()
       * @requires $scope.form.getDisciplines()
       * @requires $scope.form.getSectors()
       * @requires $timeout
       * @requires angular.fromJson()
       * @requires angular.toJson()
       * @requires localStorage
       * 
       * @param {string} state Router state the function is being called in.
       *     This will ensure that autosave is terminated if we leave that
       *     state.
       * 
       * @returns {promise} Promise that resolves when either the operation is
       *     not supported or the first interval is run.
       */
      startAutosave: function(state) {
        var deferred = $q.defer();
        var promises = [];

        // Resolve and return if already autosaving.
        if ($scope.form.isAutosaving) {
          $scope._warn('Already autosaving.');
          deferred.resolve();
          return deferred.promise;
        }

        // Aliases for local storage item names.
        var facilityItem = $scope._state.current.name + '-facility';
        var disciplinesItem = $scope._state.current.name + '-disciplines';
        var sectorsItem = $scope._state.current.name + '-sectors';

        // This is in a try/catch block because we want to test if local storage
        // is supported by the browser. If it's not, resolve immediately.
        try {
          // Aliases
          var ls = localStorage;
          var fromJson = angular.fromJson;
          var toJson = angular.toJson;
          var facility = fromJson(ls.getItem(facilityItem));
          var disciplines = fromJson(ls.getItem(disciplinesItem));
          var sectors = fromJson(ls.getItem(sectorsItem));

          if (facility) {
            $scope.form.data = facility;
            promises.push($scope.form.getDisciplines(disciplines));
            promises.push($scope.form.getSectors(sectors));
          }              
        } catch(err) {
          $scope._warn('`localStorage` is not supported. ' + err);
          deferred.resolve();
          return deferred.promise;
        }           
        
        // Continuously save form data.
        $q.all(promises).then(function() {
          try {
            save();
          } catch (err) {
            $scope._error(err);
          }
          deferred.resolve();
        });

        function save() {
          // Terminates the interval if we've changed router states.
          if (!$scope._state.is(state)) {
            $scope._info('State changed, autosave terminated.');
            return;
          }

          // Ensures that only a single recursive loop is executing.
          if ($scope._persist.facilitySubmissionFormId !== $scope.form.id) {
            $scope._info('Terminating duplicate autosave loop. Form ID: ' 
              + $scope.form.id + '. Persisted ID: ' 
              + $scope._persist.facilitySubmissionFormId);
            return;
          }

          // Aliases.
          var d = $scope.form.disciplines;
          var s = $scope.form.sectors;

          var selectedDisciplines = $scope._form.cb.getSelected(d, true);
          var selectedSectors = $scope._form.cb.getSelected(s, true);

          ls.setItem(facilityItem, toJson($scope.form.data));
          ls.setItem(disciplinesItem, toJson(selectedDisciplines));
          ls.setItem(sectorsItem, toJson(selectedSectors));

          // Log.
          $scope._info('Form data saved. Form ID:' + $scope.form.id);

          // Loop.
          $scope.form.isAutosaving = $timeout(save, 
            $scope.form.autosaveInterval);
        }

        return deferred.promise;
      },
      
      /**
       * Clears local storage of any saved form data and stops autosaving.
       * 
       * @requires $scope._location.reload();
       * @requires $scope._state.current.name
       * @requires $scope._warn()
       * @requires $scope.form.initialise()
       * @requires $timeout
       *
       * @param {boolean=false} dontConfirm If set to true, confirmation modal 
       *     will not be shown and localStorage will be cleared immediately and 
       *     the form will be reinitialised. If set to false, the confirmation 
       *     modal will be shown and if confirmed, the page will be reloaded 
       *     (instead of being reinitialised).
       */
      clearSave: function(dontConfirm) {
        if (dontConfirm) {
          remove().then(function() {
            $scope.form.initialise();
          });
        } else {
          confirmModal.open('reset-create-facility-form').result.then(
            function() {
              remove().then(function() {
                $scope._location.reload();
              });
            }
          );
        }
        
        function remove() {
          // We have to stop autosaving, otherwise clearing the data won't
          // work if the page is reloaded after this function is called.
          $timeout.cancel($scope.form.isAutosaving);

          // Delay the clearing process to ensure that no autosave intervals
          // are running.
          return $timeout(function() {
            try {
              ['-facility', '-disciplines', '-sectors'].forEach(function(item) {
                localStorage.removeItem($scope._state.current.name + item);
              });
            } catch (error) {
              $scope._warn('`localStorage` is not supported');
            }
          }, $scope.form.autosaveInterval + 250);
        }
      },
      
      /**
       * Formats the data for the preview. 
       * 
       * @requires $scope.form.data
       * @requires angular.copy()
       * 
       * @returns {object} Facility data in this format:
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
        } else if ($scope.form.data.facility.organizationId === -1) {
          f.organization = $scope.form.data.organization;
        }
        
        // Province section. Grabs the selected province's data.
        var e = document.getElementById('facility-province');
        f.province = $scope.form.provinces[e.selectedIndex];

        return f;
      },
      
      /**
       * Formats the data to match what the API requires. The API expects a
       * single primary contact and (optionally) (non-primary) contacts. In the
       * form, the first contact is assumed to be the primary contact.
       *
       * @requires $scope._form.cb.getSelected()
       * @requires $scope.form.data
       * 
       * @returns {object} Facility object.
       */
      formatForApi: function() {
        var data = angular.copy($scope.form.data);
        data.disciplines = $scope._form.cb.getSelected($scope.form.disciplines, 
          true);
        data.sectors = $scope._form.cb.getSelected($scope.form.sectors, true);
        
        // If 'Other' was selected, clear the ID since it's not a valid
        // ID and the API will reject that.
        if (data.facility.organizationId === -1) {
          data.facility.organizationId = null;
        // Otherwise (meaning an existing organization was selected), clear
        // the organization object since we're not creating a new organization.
        } else {
          data.organization = null;
        }
        
        // The first contact is the primary contact.
        data.primaryContact = (data.contacts.splice(0, 1))[0];
        
        return data;
      }
    };
  }
]);
