'use strict';

angular.module('afredApp').controller('FacilitiesShowEquipmentShowController',
  ['$scope',
  function($scope) {
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */

    $scope.facilities.equipment.get();
  }
]);