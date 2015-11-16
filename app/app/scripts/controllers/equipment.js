'use strict';

angular.module('afredApp').controller('EquipmentController', ['$scope',
  '$state', '$stateParams', '$modal', 'equipmentResource',
  function($scope, $state, $stateParams, $modal, equipmentResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    $scope.getEquipment = function() {
      $scope.equipment = equipmentResource.get({equipmentId:
        $stateParams.equipmentId,
        expand: 'facility.province,facility.institution,facility.contacts'});
    };
    
    $scope.viewCompleteFacility = function() {
      $state.go('facility', {facilityId: $stateParams.facilityId});
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    $scope.getEquipment();
  }
]);