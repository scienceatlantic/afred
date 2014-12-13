'use strict';

angular.module('afredApp').controller('FacilityPreviewModalController', ['$scope', '$modalInstance', 'facilityResource', 'facility', 'templateMode',
  function($scope, $modalInstance, facilityResource, facility, templateMode) {
    $scope.submit = function() {
      $scope.templateMode.preview = false;
      
      if ($scope.templateMode.create) {
        facilityResource.save($scope.facility, function() {
          $scope.templateMode.confirmation = true;
        });
      }
      else if ($scope.templateMode.edit) {
        $scope.facility.$update(function() {
          $scope.templateMode.confirmation = true;
        });
      }
    };
    
    $scope.close = function() {
      $modalInstance.close();
    };
    
    $scope.cancel = function () {
      $modalInstance.dismiss();
    };
    
    //Initialise
    $scope.facility = facility;
    $scope.templateMode = {
      preview: true,
      create: templateMode.create,
      edit: templateMode.edit,
      confirmation: false
    };
    $scope.loading = {
      
    };
  }
]);