'use strict';

angular.module('afredApp').controller('FacilityPreviewModalController', ['$scope', '$modalInstance', 'facilityResource', 'facility', 'templateMode',
  function($scope, $modalInstance, facilityResource, facility, templateMode) {
    /**
     * Submits the form
     */
    $scope.submit = function() {
      $scope.templateMode.preview = false;
      
      //Creating a new facility
      if ($scope.templateMode.create) {
        facilityResource.save($scope.facility, function() {
          $scope.templateMode.confirmation = true;
        });
      }
      //Updating an existing facility
      else if ($scope.templateMode.edit) {
        $scope.facility.$update(function() {
          $scope.templateMode.confirmation = true;
        });
      }
    };
    
    /**
     * Closes the modal
     */
    $scope.close = function() {
      $modalInstance.close();
    };
    
    /**
     * Dismisses the modal
     */
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