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
     * State class.
     */
    $scope.facilities = {
      /**
       * Data returned from facilityResource.
       *
       * @type {Angular resource}
       */
      resource: {},
      
      /**
       * Retrieves the facility data from the API.
       *
       * Side effects:
       * $scope.facilities.resource Data retrieved is stored here.
       *
       * Calls/requires/uses:
       * facilityResource
       * $scope._stateParans.facilityId
       * $scope.facilities.format()
       * $scope._httpError()
       */
      get: function() {
        $scope.facilities.resource = facilityResource.get({
          facilityId: $scope._stateParams.facilityId
        }, function() {
          $scope.facilities.format();
        }, function(response) {
          $scope._httpError(response);
        });        
      },
      
      /**
       * Equipment class.
       */
      equipment: {
        /**
         * Data returned from facilityResource.
         *
         * @type {Angular resource}
         */
        resource: {},
        
        /**
         * Retrieves facility data from the API. Is different from
         * '$scope.facilities.get()' such that the equipment ID is also passed
         * to the API. If the combination of the facility ID and equipment ID
         * produces no results, the API should return a 404.
         *
         * Side effects:
         * $scope.facilities.equipment.resource Stores data return from
         *     facilityResource.
         *
         * Calls/uses/requires:
         * facilityResource
         * $scope._stateParams.facilityId
         * $scope._stateParams.equipmentId
         * $scope.facilities.format()
         * $scope._httpError()
         */
        get: function() {
          $scope.facilities.equipment.resource = facilityResource.get({
            facilityId: $scope._stateParams.facilityId,
            equipmentId: $scope._stateParams.equipmentId
          }, function() {
            $scope.facilities.format($scope._stateParams.equipmentId);
          }, function(response) {
            $scope._httpError(response);
          });
        }
      },
      
      /**
       * Formats the data for the view.
       *
       * 'Contacts' data is formatted to match the view (primary contact &
       * contacts are merged into a single array). If in the
       * 'facilities.show.equipment.show' state, all data in the 'equipment'
       *  array is removed except for the piece of equipment with the ID in the
       *  URL.
       *
       * Side effects:
       * $scope.facilities.resource
       * $scope.facilities.equipment.resource Only if in the 
       *     'facilities.show.equipment.show' state.
       * 
       */
      format: function(equipmentId) {
        var data = null;
        
        // Use the correct resource. We're using 'data' as an alias for either
        // '$scope.facilities.equipment.resource' or
        // '$scope.facilities.resource' to shorten code.
        if (equipmentId) {
          data = $scope.facilities.equipment.resource;
        } else {
          data = $scope.facilities.resource;
        }
        
        // Format the contacts.
        if (data.contacts) {
          data.contacts.unshift(data.primaryContact);
        } else {
          data.contacts = [];
          data.contacts.push(data.primaryContact);
        }
        
        // If it in the 'facilities.show.equipment.show' state, remove all other
        // pieces of equipment except for the one matching the id in the URL.
        if (equipmentId) {
          for (var i = 0; i < data.equipment.length; i++) {
            if (data.equipment[i].id == equipmentId) {
              data.equipment = data.equipment.splice(i, 1);
              return;
            }
          }          
        }
      }
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    $scope.facilities.get();
  }
]);