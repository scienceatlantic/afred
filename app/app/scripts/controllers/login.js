'use strict';

angular.module('afredApp').controller('LoginController',
  ['$scope',
  function($scope) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
  
    $scope.submit = function() {
      $scope._auth.login($scope.credentials).then(function(response) {
        $scope._auth.user = response.data;
        $scope._state.go('admin.dashboard');
      }, function() {
        $scope.credentials.invalid = true;
      });
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    $scope.credentials = {
      email: null,
      password: null,
      invalid: false
    };
  }
]);