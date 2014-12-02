'use strict';

angular.module('afredApp').controller('ContactUsModalController', ['$scope', '$modalInstance',
  function($scope, $modalInstance) {
    $scope.submit = function() {
      $modalInstance.close();
    };
    
    $scope.cancel = function () {
      $modalInstance.dismiss();
    };
  }
]);