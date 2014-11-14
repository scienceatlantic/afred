'use strict';

angular.module('afredApp').controller('ContactSpringboardModalController', ['$scope', '$modalInstance',
  function($scope, $modalInstance) {
   
    
   
    $scope.submit = function() {
      
    };
    
    $scope.cancel = function () {
      $modalInstance.dismiss();
    };
  }
]);