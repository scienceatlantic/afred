'use strict';

angular.module('afredApp').controller('FacilityController', ['$scope',
  '$state', '$stateParams', '$uibModal', 'facilityResource', function($scope,
  $state, $stateParams, $uibModal, facilityResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * Retrieves facility data (and all relationships) from the database and
     * attaches it to '$scope.facility'.
     */
    $scope.getFacility = function() {
      $scope.facility = facilityResource.get({facilityId:
        $stateParams.facilityId, expand:
        'institution,province,equipment,contacts'});
    };
    
    /**
     * Redirects to the 'editFacility' state.
     */
    $scope.edit = function() {
      $state.go('editFacility', {facilityId: $stateParams.facilityId});
    };
    
    /**
     * Deletes a facility and redirects to the 'search' page.
     */
    $scope.remove = function() {
      $scope.facility.$remove(function() {
        $state.go('search');
      });
    };
    
    /**
     * Makes a facility private.
     */
    $scope.makePrivate = function() {
      $scope.facility.isPublic = false;
      $scope.facility.$update();
      location.reload();
    };
    
    /**
     * Makes a facility public.
     */
    $scope.makePublic = function() {
      $scope.facility.isActive = true;
      $scope.facility.$update();
      location.reload();
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    // Will contain all facility data.
    $scope.facility = {};
    
    // Get facility data.
    $scope.getFacility();
  }
]);