'use strict';

angular.module('afredApp').controller('LoginController', ['$scope', '$state',
  function($scope, $state) {
    $scope.submit = function() {
      $state.go('control-panel');
    };
    
    //Init
    $scope.loginCredentials = {
      username: null,
      password: null
    };
  }
]);