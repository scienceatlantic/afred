'use strict';

angular.module('afredApp').controller('FacilityPreviewModalController', ['$scope', '$modalInstance', 'facilityResource', 'record', 'templateMode',
  function($scope, $modalInstance, facilityResource, record, templateMode) {
    $scope.submit = function() {
      $scope.templateMode.preview = false;
      
      if ($scope.templateMode.create) {
        facilityResource.save($scope.record, function() {
          $scope.templateMode.confirmation = true;
        });
      }
      else if ($scope.templateMode.edit){
        facilityResource.update({facilityId: $scope.record.facility.id}, $scope.record, function() {
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
    $scope.record = record;
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