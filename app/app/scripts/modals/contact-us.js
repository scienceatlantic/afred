'use strict';

angular.module('afredApp').controller('ContactUsModalController', ['$scope', '$modalInstance',
  function($scope, $modalInstance) {
    /**
     * Submits the message
     */
    $scope.submit = function() {
      $modalInstance.close();
    };
    
    /**
     * Dismisses the modal
     */
    $scope.cancel = function () {
      $modalInstance.dismiss();
    };
  }
]);