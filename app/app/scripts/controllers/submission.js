'use strict';

/**
 * @ngdoc function
 * @name afredApp.controller:submissionController
 * @description
 * # submissionController
 * Controller of the afredApp
 */
angular.module('afredApp').controller('submissionController', ['$scope',
  function($scope) {
    $scope.addContact = function() {
      $scope.form.contacts.push({
        firstName: null,
        lastName: null,
        email: null,
        telephone: null,
        position: null,
        department: null,
        website: null
      });
    };
    
    $scope.removeContact = function(index) {
      if (index !== 0) {
        $scope.form.contacts.splice(index, 1);
      }
    };
    
    $scope.addEquipment = function() {
      $scope.form.equipment.push({
        name: null,
        specifications: null,
        purpose: null,
        links: []
      });
      
      $scope.addEquipmentLink($scope.form.equipment.length - 1);
    };
    
    $scope.removeEquipment = function(index) {
      if (index !== 0) {
        $scope.form.equipment.splice(index, 1);
      }
    };
    
    $scope.addEquipmentLink = function(index) {
      $scope.form.equipment[index].links.push({
        url: null
      });
    };
    
    $scope.removeEquipmentLink = function(equipmentIndex, linkIndex) {
      if (linkIndex !== 0) {
        $scope.form.equipment[equipmentIndex].links.splice(linkIndex, 1);
      }
    };
    
    $scope.submit = function() {
      console.log($scope.form);
    };
    
    //Init
    $scope.form = {
      facility: {
        name: null,
        institution: null,
        description: null,
        additionalInfo: null,
        city: null,
        province: null,
        website: null
      },
      contacts: [],
      equipment: []
    };
    $scope.addContact();
    $scope.addEquipment();
  }
]);

