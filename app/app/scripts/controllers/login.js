'use strict';

angular.module('afredApp').controller('LoginController',
  ['$scope',
  function($scope) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
  
    /**
     * Submits the login data to the API.
     */
    $scope.submit = function() {
      // Set loading flag to true.
      $scope.loading.login = true;
      
      $scope.auth = $scope._auth.login($scope.credentials).then(function(response) {
        // If login was successful, save the authenticated user's details
        // and redirect the user to the dashboard.
        $scope._auth.user = response.data;
        $scope._state.go('admin.dashboard');
      }, function() {
        console.log($scope.auth);
        $scope.credentials.invalid = true;
        
        // Set loading flag to false.
        $scope.loading.login = false;
      });
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    /**
     * Stores the form data that will be passed to the API.
     * 
     * @type {object}
     */
    $scope.credentials = {
      email: null,
      password: null,
      invalid: false // Used to display an error message if login failed.
    };
    
    // Loading flags. 
    $scope.loading = {
      login: false
    };
  }
]);