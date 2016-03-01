'use strict';

angular.module('afredApp').controller('FacilitiesShowController',
  ['$scope',
   '$uibModal',
   'facilityResource',
  function($scope,
           $uibModal,
           facilityResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * Retrieves facility data including organization, ILO, province,
     * disciplines, sectors, equipment, primary contact, and contacts.
     *
     * Side effects:
     * $scope.facility Data from the API is attached to this.
     * $scope.view.show Updated to 'INVALID_FACILITY_ID_MESSAGE' if the
     *     facility was not found or 'INVALID_EQUIPMENT_ID_MESSAGE' if we're
     *     in the equipment state and the piece of equipment was not found.
     *
     * Uses/Requires:
     * facilityResource
     * $scope._stateParams
     * $scope._state
     */
    $scope.getFacility = function() {
      $scope.facility = facilityResource.get(
        {
          facilityId: $scope._stateParams.facilityId,
          expand: 'organization.ilo,province,disciplines,sectors,' +
            'equipment,primaryContact,contacts'
        },
        function() {
          // Contact section.
          if ($scope.facility.contacts) {
            $scope.facility.contacts.unshift($scope.facility.primaryContact);
          } else {
            $scope.facility.contacts = [];
            $scope.facility.contacts.push($scope.facility.primaryContact);
          }
          
          // Equipment state.
          // Splice the equipment array to retrieve the piece of equipment the
          // user wants to view.
          if ($scope._state.is('facilities.show.equipment.show')) {
            var id = $scope._stateParams.equipmentId;
            
            for (var i = 0; i < $scope.facility.equipment.length; i++) {
              if ($scope.facility.equipment[i].id == id) {
                $scope.facility.equipment =
                  $scope.facility.equipment.splice(i, 1);
                return;
              }
            }
            
            // Equipment not found.
            $scope.view.show = 'INVALID_EQUIPMENT_ID_MESSAGE';
          }
        }, function() {
          // Facility not found.
          $scope.view.show = 'INVALID_FACILITY_ID_MESSAGE';
        }
      );
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    /**
     * Facility data from the API.
     * @type {object}
     */
    $scope.facility = {};
    
    /**
     * Controls what is shown to the user.
     * @type {object}
     */
    $scope.view = {
      show: 'LISTING'
    };
    
    // Get the facility.
    $scope.getFacility();
  }
]);