'use strict';

angular.module('afredApp').controller('EquipmentController',
  ['$scope',
   'equipmentResource',
  function($scope,
           equipmentResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    $scope.getEquipment = function() {
      $scope.equipment = equipmentResource.get({
        equipmentId: $scope._stateParams.equipmentId,
        expand: 'facility.province,facility.organization,facility.contacts'
      });
    };
    
    $scope.viewCompleteFacility = function() {
      $scope._state.go('facility', {
        facilityId: $scope._stateParams.facilityId
      });
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    $scope.getEquipment();
  }
]);