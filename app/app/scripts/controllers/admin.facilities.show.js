'use strict';

angular.module('afredApp').controller('AdminFacilitiesShowController', [
  '$scope',
  'confirmModal',
  'infoModal',
  'warningModal',
  'organizationResource',
  'provinceResource',
  'disciplineResource',
  'sectorResource',
  'facilityResource',
  'facilityRepositoryResource',
  function($scope,
           confirmModal,
           infoModal,
           warningModal,
           organizationResource,
           provinceResource,
           disciplineResource,
           sectorResource,
           facilityResource,
           facilityRepositoryResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * Retrieves the facility repository record from the API.
     *
     * Side effects:
     * $scope.fr Data returned from the API is stored here.
     *
     * Uses/calls/requires:
     * $scope.formatForApp()
     * $scope._stateParams.facilityRepositoryId
     * facilityRepositoryResource
     * isFinite()
     * $scope._httpError() Called when either an AJAX call fails or when
     *     the URL contains invalid parameters.
     */
    $scope.getFacilityRepository = function() {
      if (isFinite($scope._stateParams.facilityRepositoryId)) {
        $scope.fr = facilityRepositoryResource.get({
          facilityRepositoryId: $scope._stateParams.facilityRepositoryId
        }, function() {
          $scope.formatForApp();
        }, function(response) {
          $scope._httpError(response);
        });        
      } else {
        // Called if the '$scope._stateParams.facilityId' contains an invalid
        // value.
        $scope._httpError('404');
      }
    };
    
    /**
     * Approve a facility.
     *
     * Side effects:
     * $scope.loading.approve Set to true at the start of the function and set
     *     to false when the AJAX call is complete.
     *
     * Uses/calls/requires:
     * confirmModal
     * infoModal
     * warningModal
     * $scope.fr.$approve()
     * $scope.getFacilityRepository() Called if the AJAX operation was
     *     successful.
     */
    $scope.approve = function() {
      var t = 'approve-facility'; // Template name (to shorten code).
      confirmModal.open(t).result.then(function() {
        $scope.loading.approve = true;
        $scope.fr.state = 'PUBLISHED';
        $scope.fr.$approve(function() {
          infoModal.open(t + '-success').result.then(function() {
            $scope.loading.approve = false;
            $scope.getFacilityRepository();
          });
        }, function() {
          warningModal.open(t + '-failed').result.then(function() {
            $scope.loading.approve = false;
          });
        });
      });
    };
    
    /**
     * Reject a facility record.
     *
     * Side effects:
     * $scope.loading.reject Set to true at the start of the function and then
     *     set to false after the AJAX operation is complete.
     *
     * Calls/uses/requires:
     * confirmModal
     * infoModal
     * warningModal
     * $scope.fr.$reject()
     * $scope.getFacilityRepository() Called if the AJAX operation was
     *     successful.
     */
    $scope.reject = function() {
      var t = 'reject-facility'; // Template name (to shorten code).
      confirmModal.open(t).result.then(function() {
        $scope.loading.reject = true;
        $scope.fr.state = 'REJECTED';
        $scope.fr.$reject(function() {
          infoModal.open(t + '-success').result.then(function() {            
            $scope.loading.reject = false;
            $scope.getFacilityRepository();
          });
        }, function() {
          warningModal.open(t + '-failed').result.then(function() {
            $scope.loading.reject = false;
          });
        });           
      });
    };
    
    /**
     * Approve a facility edit.
     *
     * Side effects:
     * $scope.loading.approveEdit Set to true at the start of the function and
     *     set to false when the AJAX call is complete.
     *
     * Uses/calls/requires:
     * confirmModal
     * infoModal
     * warningModal
     * $scope.fr.$approveEdit()
     * $scope.getFacilityRepository() Called if the AJAX operation was
     *     successful.
     */
    $scope.approveEdit = function() {
      var t = 'approve-facility-edit'; // Template name (to shorten code).
      confirmModal.open(t).result.then(function() {
        $scope.loading.approveEdit = true;
        $scope.fr.state = 'PUBLISHED_EDIT';
        $scope.fr.$approveEdit(function() {
          infoModal.open(t + '-success').result.then(function() {
            $scope.loading.approveEdit = false;
            $scope.getFacilityRepository();
          });
        }, function() {
          warningModal.open(t + '-failed').result.then(function() {
            $scope.loading.approveEdit = false;
          });
        });        
      });
    };
    
    /**
     * Reject a facility edit.
     *
     * Side effects:
     * $scope.loading.rejectEdit Set to true at the start of the function and
     *     set to false when the AJAX call is complete.     
     *
     * Uses/calls/requires:
     * confirmModal
     * infoModal
     * warningModal
     * $scope.fr.$rejectEdit()
     * $scope.getFacilityRepository() Called if the AJAX operation was
     *     successful.
     */
    $scope.rejectEdit = function() {
      var t = 'reject-facility-edit'; // Template name (to shorten code).
      confirmModal.open(t).result.then(function() {
        $scope.loading.rejectEdit = true;
        $scope.fr.state = 'REJECTED_EDIT';
        $scope.fr.$rejectEdit(function() {
          infoModal.open(t + '-success').result.then(function() {
            $scope.loading.rejectEdit = false;
            $scope.getFacilityRepository();
          });
        }, function() {
          warningModal.open(t + '-failed').result.then(function() {
            $scope.loading.rejectEdit = false;
          });
        });        
      });    
    };
    
    /**
     * Format the repository data for user viewing.
     *
     * Side effects:
     * $scope.facility Formatted data is stored here.
     * $scope.loading.disciplines Set to false after disciplines are formatted.
     * $scope.loading.sectors Set to false after sectors are formatted.
     *
     * Calls/uses/requires:
     * $scope.fr
     * $scope._httpError() Call whenever an AJAX call fails.
     * provinceResource
     * organizationResource
     * disciplineResource
     * sectorResource
     */
    $scope.formatForApp = function() {
      $scope.facility = angular.copy($scope.fr.data.facility);
      $scope.facility.organization = angular.copy($scope.fr.data.organization);
      $scope.facility.contacts = angular.copy($scope.fr.data.contacts);
      $scope.facility.equipment = angular.copy($scope.fr.data.equipment);
      $scope.facility.state = $scope.fr.state;
      
      try {
        $scope.facility.isPublic = $scope.fr.publishedFacility.isPublic;
      } catch (e) {
        // Do nothing if it fails.
      }
      
            
      // Primary contact & contacts section. In the DB primary contacts and
      // regular contacts are stored in separate tables, however, when the user
      // is viewing it, it's stored in a single array (where the first element
      // is the primary contact).
      if (!$scope.fr.data.contacts) {
        $scope.facility.contacts = [];
        $scope.facility.contacts.push($scope.fr.data.primaryContact);
      } else {
        $scope.facility.contacts.unshift($scope.fr.data.primaryContact);
      }
      
      // Organization section. Check if the facility belongs to an existing
      // organization or a new organization. If it belongs to an existing
      // organization, grab the details from the API.
      if ($scope.fr.data.facility.organizationId) {
        $scope.facility.organization = organizationResource.get({
          organizationId: $scope.fr.data.facility.organizationId
        }, function() {
          // Do nothing if successful.
        }, function(response) {
          $scope._httpError(response);
        });
      }
      
      // Province section.
      $scope.facility.province = provinceResource.get({
        provinceId: $scope.fr.data.facility.provinceId
      }, function() {
        // Do nothing if successful.
      }, function(response) {
        $scope._httpError(response);
      });
      
      // Disciplines section. Grab the complete list of disciplines from the API
      // so that we can get the names (the facility repository record only
      // contains the IDs of the disciplines).
      $scope.facility.disciplines = [];
      $scope.disciplines = disciplineResource.queryNoPaginate(function() {
        angular.forEach($scope.disciplines, function(d) {
          if ($scope.fr.data.disciplines.indexOf(d.id) >= 0) {
            $scope.facility.disciplines.push(d);
          }
        });
        
        $scope.loading.disciplines = false;
      }, function(response) {
        $scope._httpError(response); 
      });
      
      // Sectors section. (Same as disciplines).
      $scope.facility.sectors = [];
      $scope.sectors = sectorResource.queryNoPaginate(function() {
        angular.forEach($scope.sectors, function(s) {
          if ($scope.fr.data.sectors.indexOf(s.id) >= 0) {
            $scope.facility.sectors.push(s);
          }
        });
        
        $scope.loading.sectors = false;
      }, function(response) {
        $scope._httpError(response); 
      });
      
      // This is for the HTML template. Setting the 'isPreview' property to true
      // will show additional facility data (e.g. excess capacity, etc).
      $scope.facility.isPreview = true;
      
      // If the facility contains a new organization, set this property to true.
      // This affects the HTML template.
      $scope.facility.isNewOrganization =
        !$scope.fr.data.facility.organizationId;
    };
    
    /**
     * Hides a facility (does not appear in search results).
     *
     * Side effects:
     * $scope.loading.hide Set to true at the start of the function and then
     *     set to false after the AJAX operation is complete.
     * 
     * 
     * Calls/uses/requires:
     * confirmModal
     * infoModal
     * warningModal
     * $scope.fr.facilityId
     * $scope.getFacilityRepository() Called if the AJAX operation was
     *     successful.
     */
    $scope.hide = function() {
      var t = 'hide-facility';
      confirmModal.open(t).result.then(function() {
        $scope.loading.hide = true;
        facilityResource.update({ facilityId: $scope.fr.facilityId }, {
          isPublic: 0
        }, function() {
          infoModal.open(t + '-success').result.then(function() {
            $scope.loading.hide = false;
            $scope.getFacilityRepository();
          });
        }, function() {
          warningModal.open(t + '-failed').result.then(function() {
            $scope.loading.hide = false;
          });
        });  
      });
    };
    
    /**
     * Unhides a facility (appears in search results).
     *
     * Side effects:
     * $scope.loading.unhide Set to true at the start of the function and then
     *     set to false after the AJAX operation is complete.
     * 
     * 
     * Calls/uses/requires:
     * confirmModal
     * infoModal
     * warningModal
     * $scope.fr.facilityId
     * $scope.getFacilityRepository() Called if the AJAX operation was
     *     successful.
     */
    $scope.unhide = function() {
      var t = 'unhide-facility';
      confirmModal.open(t).result.then(function() {
        $scope.loading.unhide = true;
        facilityResource.update({
          facilityId: $scope.fr.facilityId
        }, {
          isPublic: 1
        }, function() {
          infoModal.open(t + '-success').result.then(function() {
            $scope.loading.unhide = false;
            $scope.getFacilityRepository();
          });
        }, function() {
          warningModal.open(t + '-failed').result.then(function() {
            $scope.loading.unhide = false;
          });
        });  
      });      
    };
    
    /**
     * Generates an update request and redirects the user to the edit form.
     *
     * Side effects:
     * $scope.loading.edt Set to true at the start of the function and then
     *     set to false when the AJAX operation is complete.
     *
     * Calls/uses/requires:
     * confirmModal
     * infoModal
     * warningModal
     * facilityRepositoryResource
     * $scope._auth.user.email
     * $scope.fr.id
     * $scope.fr.facilityId
     */
    $scope.edit = function() {
      var t = 'edit-facility';
      
      // If the facility has an open/pending update request, prevent the user
      // from opening a new one.
      if ($scope.fr.fulB.length) {
        warningModal.open(t + '-not-allowed');
        return;
      }
      
      confirmModal.open(t).result.then(function() {
        $scope.loading.edit = true;
        facilityRepositoryResource.createToken({
          isAdmin: 1,
          email: $scope._auth.user.email,
          facilityId: $scope.fr.facilityId
        }, null, function(response) {
          infoModal.open(t + '-success').result.then(function() {
            $scope.loading.edit = false;
            $scope._state.go('facilities.form.edit', {
              facilityRepositoryId: $scope.fr.id,
              token: response.token
            });            
          });        
        }, function() {
          warningModal.open(t + '-failed').result.then(function() {
            $scope.loading.edit = false;
          });
        });        
      })
    };
    
    /**
     * Deletes a published facility.
     *
     * Side effects:
     * $scope.loading.remove Set to true at the start of the function and the
     *     set to false when the AJAX operation is complete.
     *
     * Calls/uses/requires:
     * confirmModal
     * infoModal
     * warningModal
     * $scope.fr.fulB.length
     * $scope.fr.facilityId
     * facilityResource
     * $scope.getFacilityRepository()
     *
     */
    $scope.remove = function() {
      var t = 'delete-facility';
      
      // If the facility has an open/pending update request, prevent the user
      // from deleting the facility.
      if ($scope.fr.fulB.length) {
        warningModal.open(t + '-not-allowed');
        return;
      }
      
      confirmModal.open(t).result.then(function() {
        $scope.loading.remove = true;
        facilityResource.remove({
          facilityId: $scope.fr.facilityId
        }, null, function() {
          infoModal.open(t + '-success').result.then(function() {
            $scope.loading.remove = false;
            $scope.getFacilityRepository();
          });
        }, function() {
          warningModal.open(t + '-failed').result.then(function() {
            $scope.loading.remove = false;
          });
        });        
      });
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    /**
     * Facility repository object.
     *
     * @type {Angular resource}
     */
    $scope.fr = {};
    
    /**
     * Formatted facility data.
     *
     * @type {object}
     */
    $scope.facility = {};
    
    /**
     * Array of disciplines.
     *
     * @type {array}
     */
    $scope.disciplines = {};
    
    /**
     * Array of sectors.
     *
     * @type {array}
     */
    $scope.sectors = {};
    
    /**
     * Loading flags.
     * 
     * @type {object}
     */
    $scope.loading = {
      disciplines: true,
      sectors: true,
      approve: false,
      approveEdit: false,
      reject: false,
      rejectEdit: false
    };
    
    // Retrieve the facility repository record.
    $scope.getFacilityRepository();
  }
]);