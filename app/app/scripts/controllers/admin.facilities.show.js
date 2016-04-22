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
  'facilityRepositoryResource',
  function($scope,
           confirmModal,
           infoModal,
           warningModal,
           organizationResource,
           provinceResource,
           disciplineResource,
           sectorResource,
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
     * $scope.fr.state Set to 'PUBLISHED'.
     * $scope.facility.state Set to 'PUBLISHED' if the AJAX call was successful.
     *
     * Uses/calls/requires:
     * confirmModal
     * infoModal
     * warningModal
     * $scope.fr.$approve()
     */
    $scope.approve = function() {
      var t = 'approve-facility'; // Template name (to shorten code).
      confirmModal.open(t).result.then(function() {
        $scope.loading.approve = true;
        $scope.fr.state = 'PUBLISHED';
        $scope.fr.$approve(function() {
          infoModal.open(t + '-success').result.then(function() {
            $scope.facility.state = $scope.fr.state;
            $scope.loading.approve = false;            
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
     * $scope.fr.state Set to 'REJECTED'.
     * $scope.facility.state Set to 'REJECTED' if the AJAX call was successful.
     *
     * Calls/uses/requires:
     * confirmModal
     * infoModal
     * warningModal
     * $scope.fr.$reject()
     */
    $scope.reject = function() {
      var t = 'reject-facility'; // Template name (to shorten code).
      confirmModal.open(t).result.then(function() {
        $scope.loading.reject = true;
        $scope.fr.state = 'REJECTED';
        $scope.fr.$reject(function() {
          infoModal.open(t + '-success').result.then(function() {
            $scope.facility.state = $scope.fr.state;
            $scope.loading.reject = false;            
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
     * $scope.fr.state Set to 'PUBLISHED_EDIT'.
     * $scope.facility.state Set to 'PUBLISHED_EDIT' if the AJAX call was
     *     successful.
     *
     * Uses/calls/requires:
     * confirmModal
     * infoModal
     * warningModal
     * $scope.fr.$approveEdit()
     */
    $scope.approveEdit = function() {
      var t = 'approve-facility-edit'; // Template name (to shorten code).
      confirmModal.open(t).result.then(function() {
        $scope.loading.approveEdit = true;
        $scope.fr.state = 'PUBLISHED_EDIT';
        $scope.fr.$approveEdit(function() {
          infoModal.open(t + '-success').result.then(function() {
            $scope.facility.state = $scope.fr.state;
            $scope.loading.approveEdit = false;                    
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
     * $scope.fr.state Set to 'REJECTED_EDIT'.
     * $scope.facility.state Set to 'REJECTED_EDIT' if the AJAX call was
     *     successful.
     *
     * Uses/calls/requires:
     * confirmModal
     * infoModal
     * warningModal
     * $scope.fr.$rejectEdit()
     */
    $scope.rejectEdit = function() {
      var t = 'reject-facility-edit'; // Template name (to shorten code).
      confirmModal.open(t).result.then(function() {
        $scope.loading.rejectEdit = true;
        $scope.fr.state = 'REJECTED_EDIT';
        $scope.fr.$rejectEdit(function() {
          infoModal.open(t + '-success').result.then(function() {
            $scope.facility.state = $scope.fr.state;
            $scope.loading.rejectEdit = false;          
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
     * Facility data.
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