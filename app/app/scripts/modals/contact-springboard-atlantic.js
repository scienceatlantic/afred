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
    
    $scope.loading = {
      form: true
    };
    
    $timeout(function() {
      $scope.loading.form = false;
    }, 1000);
    
    $scope.modal = $uibModalInstance;
  }
]);