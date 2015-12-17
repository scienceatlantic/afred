'use strict';

angular.module('afredApp').controller('FacilitiesShowController',
  ['$scope',
   '$state',
   '$stateParams',
   '$uibModal',
   'facilityResource',
   'facilityRevisionHistoryResource',
  function($scope,
           $state,
           $stateParams,
           $uibModal,
           facilityResource,
           facilityRevisionHistoryResource) {
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
          facilityId: $stateParams.facilityId,
          expand: 'institution,province,equipment,primaryContact,contacts'
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
        $state.go('editFacility', {facilityId: $stateParams.facilityId});
      },
      
      remove: function() {
        $scope.facility.$remove(function() {
          $state.go('search');
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