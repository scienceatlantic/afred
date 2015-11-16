'use strict';

angular.module('afredApp').controller('ContactUsModalController', ['$scope', '$uibModalInstance',
  function($scope, $uibModalInstance) {
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
  }
]);