'use strict';

angular.module('afredApp').controller('FacilitiesShowController',
  ['$scope',
   '$uibModal',
   'facilityResource',
   'facilityRepositoryResource',
  function($scope,
           $uibModal,
           facilityResource,
           facilityRepositoryResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * Retrieves facility data (and all relationships) from the database and
     * attaches it to '$scope.facility'.
     */
    $scope.getFacility = function() {
      $scope.facility = facilityResource.get(
        {
          facilityId: $scope._stateParams.facilityId,
          expand: 'organization,province,equipment,primaryContact,contacts'
        },
        function() {
          if ($scope.facility.contacts) {
            $scope.facility.contacts.unshift($scope.facility.primaryContact);
          } else {
            $scope.facility.contacts = [];
            $scope.facility.contacts.push($scope.facility.primaryContact);
          }
        }
      );
    };
    
    $scope.admin = {
      edit: function() {
        $scope._state.go('editFacility', {facilityId: $scope._stateParams.facilityId});
      },
      
      remove: function() {
        $scope.facility.$remove(function() {
          $scope._state.go('search');
        });        
      },
      
      makePrivate: function() {
        var facility = angular.copy($scope.facility);
        facility.isPublic = false;
        $scope.facility.$update();
        location.reload();        
      },
      
      makePublic: function() {
        $scope.facility.isActive = true;
        $scope.facility.$update();
        location.reload();       
      }
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    // Will contain all facility data.
    $scope.facility = {};
    
    $scope.getFacility();
  }
]);