'use strict';

angular.module('afredApp').controller('LoginController',
  ['$scope',
   '$state',
  function($scope,
           $state) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
  
    $scope.submit = function() {
      $scope._auth.login($scope.credentials).then(function(response) {
        $scope._auth.user = response.data;
        $state.go('admin.dashboard');
      }, function() {
        $scope.credentials.invalid = true;
      });
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    $scope.credentials = {
      username: null,
      password: null,
      invalid: false
    };
  }
]);