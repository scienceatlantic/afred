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
       * If the '$scope.form.getSave()' method fails, this property will be set
       * to false. This prevents '$scope.form.autosave()' from executing
       * even though local storage is not supported.
       */
      isStorageSupported: true,
      
      /**
       * Holds the names that will used for storing and retrieving
       * form data via the '$scope.form.save()', and '$scope.form.getSave()'
       * functions.
       *
       * @type {object}
       */
      storage: {
        facility: $scope._state.current.name + '-facility'
      },
      
      /**
       * Initialises the form. All form data is attached to '$scope.facility'.
       */
      initialise: function() {
        // Holds all facility data that will be passed to the API.
        $scope.facility = {
          name: null,
          institution: { name: null }, // This is for the preview.
          institutionId: null,
          province: { name: null }, // This is for the preview too.
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
              $scope.form.equipmentIndex >
              $scope.facility.equipment.length - 1) {
            $scope.form.equipmentIndex--;
          }      
        }          
      },
      
      /**
       * Gets a list of all institutions (and corresponding ILOs) and attaches
       * it to '$scope.form.institutions'. 'N/A' is moved to the second last
       * position. 'Other' is added as the last option.
       *
       * Note: this assumes that 'N/A' is the first institution listed in the
       * database.
       */
      getInstitutions: function() {
        $scope.form.institutions = institutionResource.queryNoPaginate(
          {
            expand: 'ilo'
          },
          function() {
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
        $scope.form.provinces = provinceResource.queryNoPaginate();
      },
      
      /**
       * Saves the form to localStorage.
       */
      save: function() {
        try {
          localStorage.setItem($scope.form.storage.facility,
            JSON.stringify($scope.facility));
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
        try {
          if (localStorage.getItem($scope.form.storage.facility)) {
            $scope.facility =
              JSON.parse(localStorage.getItem($scope.form.storage.facility));
              
              // Institution and province IDs will not change but in the
              // unlikely event that their names have changed since the
              // last time the end user was filling out the form, re-retrieve
              // it. For institutions, if the user selected 'Other', don't
              // retrieve the name because that will reset it to null.
              if ($scope.facility.institutionId != -1) {
                $scope.form.attachInstitutionForPreview();
              }
              $scope.form.attachProvinceForPreview();
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
       * Attaches the institution's name to '$scope.facility.institution'
       * if an institution other than 'Other' was selected. This is for
       * the preview.
       */
      attachInstitutionForPreview: function() {        
        if ($scope.facility.institutionId != -1) {
          var e = document.getElementById('facility-institution');
          $scope.facility.institution =
            $scope.form.institutions[e.selectedIndex];
        } else {
          $scope.facility.institution = { name: null };
        }
      },
      
      /**
       * Attaches the name of the province to '$scope.facility.province'.
       * This is for the preview.
       */
      attachProvinceForPreview: function() {
        var e = document.getElementById('facility-province');
        $scope.facility.province = $scope.form.provinces[e.selectedIndex];
      },
      
      /**
       * Formats the data to match what the API requires. The API expects a
       * single primary contact and (optionally) regular contacts. In the form,
       * the first contact is the primary contact, so splice it out and attach
       * it to a property called 'primaryContact'. The 'institutionId' field
       * must either be empty or contain a valid institution id, so if it's
       * set to '-1' (ie. 'Other'), change it to null.
       *
       * @return {object} Facility object.
       */
      formatForApi: function() {
        var facility = angular.copy($scope.facility);
        
        facility.primaryContact = (facility.contacts.splice(0, 1))[0];
        if (facility.institutionId == -1) {
          facility.institutionId = null;
        }
        
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