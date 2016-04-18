'use strict';

angular.module('afredApp').controller('AdminFacilitiesShowController', [
  '$scope',
  'organizationResource',
  'provinceResource',
  'disciplineResource',
  'sectorResource',
  'facilityRepositoryResource',
  function($scope,
           organizationResource,
           provinceResource,
           disciplineResource,
           sectorResource,
           facilityRepositoryResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /*
     * Note: This file inherits froms 'admin.facilities.js'. So we could attach
     * these functions to '$scope.facilities' but we're not going to do that
     * since these functions are only really useful for this state.
     */
    
    /**
     * Retrieves the facility repository record from the API.
     *
     * Side effects:
     * $scope.fr Data returned from the API is attached to this.
     *
     * Uses/calls/requires:
     * $scope.formatForApp()
     */
    $scope.getFacilityRepository = function() {
      if (isFinite($scope._stateParams.facilityRepositoryId)) {
        $scope.fr = facilityRepositoryResource.get({
          facilityRepositoryId: $scope._stateParams.facilityRepositoryId,
          facilityId: $scope._stateParams.facilityId
        }, function() {
          $scope.formatForApp();
        }, function() {
        
        });        
      } else {
        $scope.view.show = 'ERROR';
      }
    };
    
    $scope.approve = function() {
      $scope.loading.approve = true;
      $scope.fr.state = 'PUBLISHED';
      $scope.fr.$approve(function() {
        $scope.facility.state = $scope.fr.state;
        $scope.loading.approve = false;
      });
    };
    
    $scope.reject = function() {
      $scope.loading.reject = true;
      $scope.fr.state = 'REJECTED';
      $scope.fr.$reject(function() {
        $scope.facility.state = $scope.fr.state;
        $scope.loading.reject = false;
      });   
    };
    
    $scope.approveEdit = function() {
      $scope.loading.approveEdit = true;
      $scope.fr.state = 'PUBLISHED_EDIT';
      $scope.fr.$approveEdit(function() {
        $scope.loading.approveEdit = false;
        $scope.facility.state = $scope.fr.state;
      });   
    };
    
    $scope.rejectEdit = function() {
      $scope.loading.rejectEdit = true;
      $scope.fr.state = 'REJECTED_EDIT';
      $scope.fr.$rejectEdit(function() {
        $scope.facility.state = $scope.fr.state;
        $scope.loading.rejectEdit = false;
      });    
    };
    
    $scope.formatForApp = function() {
      $scope.facility = angular.copy($scope.fr.data.facility);
      $scope.facility.organization = angular.copy($scope.fr.data.organization);
      $scope.facility.contacts = angular.copy($scope.fr.data.contacts);
      $scope.facility.equipment = angular.copy($scope.fr.data.equipment);
      $scope.facility.state = $scope.fr.state;
            
      // Primary contact & contacts section.
      if (!$scope.fr.data.contacts) {
        $scope.facility.contacts = [];
        $scope.facility.contacts.push($scope.fr.data.primaryContact);
      } else {
        $scope.facility.contacts.unshift($scope.fr.data.primaryContact);
      }
      
      // Organization section.
      if ($scope.fr.data.facility.organizationId) {
        $scope.facility.organization = organizationResource.get({
          organizationId: $scope.fr.data.facility.organizationId
        });
      }
      
      // Province section.
      $scope.facility.province = provinceResource.get({
        provinceId: $scope.fr.data.facility.provinceId
      });
      
      // Disciplines section.
      $scope.facility.disciplines = [];
      $scope.disciplines = disciplineResource.queryNoPaginate(null,
        function() {
          angular.forEach($scope.disciplines, function(d) {
            if ($scope.fr.data.disciplines.indexOf(d.id) >= 0) {
              $scope.facility.disciplines.push(d)
            }
          });
          
          // Set loading flag to false.
          $scope.loading.disciplines = false;
        }
      );
      
      // Sectors section.
      $scope.facility.sectors = [];
      $scope.sectors = sectorResource.queryNoPaginate(null,
        function() {
          angular.forEach($scope.sectors, function(s) {
            if ($scope.fr.data.sectors.indexOf(s.id) >= 0) {
              $scope.facility.sectors.push(s)
            }
          });
          
          // Set loading flag to false.
          $scope.loading.sectors = false;
        }
      );
      
      $scope.facility.isPreview = true;
      $scope.facility.isNewOrganization =
        !$scope.fr.data.facility.organizationId;
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    /**
     * Facility repository object.
     * @type {object}
     */
    $scope.fr = {};
    
    /**
     * Facility data.
     * @type {object}
     */
    $scope.facility = {};
    
    /**
     * Array of disciplines.
     * @type {array}
     */
    $scope.disciplines = {};
    
    /**
     * Array of sectors.
     * @type {array}
     */
    $scope.sectors = {};
    
    /**
     * Loading flags.
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
    
    /**
     * Determines what is displayed to the user.
     * @type {object}
     */
    $scope.view = {
      show: 'FACILITY'
    };
    
    // Retrieve the facility repository record.
    $scope.getFacilityRepository();
    
    // See reason for this bit in 'admin.facilities.state.js'.
    $scope.$on('$stateChangeStart',
      function(event, toState, toParams, fromState, fromParams, options) {
        if (toState.name == 'admin.facilities') {
          $scope.facilities.form.clear();
        }
      }
    );
  }
]);