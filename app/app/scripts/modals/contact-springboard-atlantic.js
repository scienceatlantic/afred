'use strict';

angular.module('afredApp').controller('ContactSpringboardAtlanticModalController', [
  '$scope',
  '$uibModalInstance',
  '$timeout',
  function($scope, $uibModalInstance, $timeout) {
    /**
     * Submits the message
     */
    $scope.submit = function() {
      $uibModalInstance.close();
    };
    
    /**
     * Dismisses the modal
     */
    $scope.cancel = function () {
      $uibModalInstance.dismiss();
    };
    
    $scope.loading = {
      form: true
    };
    
    $timeout(function() {
      $scope.loading.form = false;
    }, 1000);
  }
]);