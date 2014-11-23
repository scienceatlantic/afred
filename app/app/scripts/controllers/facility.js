'use strict';

/**
 * @ngdoc function
 * @name afredApp.controller:submissionController
 * @description
 * # submissionController
 * Controller of the afredApp
 */
angular.module('afredApp').controller('FacilityController', ['$scope', '$stateParams', 'facilityResource', 'templateMode', 
  function($scope, $stateParams, facilityResource, templateMode) {
    //Initialise
    $scope.panels = {};
    $scope.data = {
      facility: facilityResource.get({facilityId: $stateParams.facilityId}),
      contacts: facilityResource.queryContacts({facilityId: $stateParams.facilityId}),
      equipment: []
    };
    
    if (templateMode.facility) {
      $scope.panels.first = 'facility-panel.html';
      $scope.panels.second = 'contact-panel.html';
      $scope.panels.third = 'equipment-panel.html';
      $scope.data.equipment = facilityResource.queryEquipment({facilityId: $stateParams.facilityId});
    }
    else if (templateMode.equipment) {
      $scope.panels.first = 'equipment-panel.html';
      $scope.panels.second = 'facility-panel.html';
      $scope.panels.third = 'contact-panel.html';
      $scope.data.equipment.push(facilityResource.getEquipment(
        {facilityId: $stateParams.facilityId, equipmentId: $stateParams.equipmentId}
      ));
    }
  }
]);