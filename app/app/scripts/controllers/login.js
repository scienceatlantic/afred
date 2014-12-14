'use strict';

angular.module('afredApp').controller('LoginController', ['$scope', '$state',
  function($scope, $state) {
    $scope.submit = function() {
      $state.go('controlPanel');
    };
    
    //Initialise
    $scope.loginCredentials = {
      username: null,
      password: null
    };
  }
]);