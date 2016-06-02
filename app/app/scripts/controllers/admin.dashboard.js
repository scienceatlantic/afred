'use strict';

angular.module('afredApp').controller('AdminDashboardController',
  ['$scope',
   '$http',
  function($scope,
           $http) {
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    // Get the data.
    $scope.stats = $http.get($scope._config.api.address + '/dashboard');
    $scope.stats.then(function(response) {
      $scope.stats.data = response.data;
      $scope.loading.stats = false;
    }, function(response) {
      $scope._httpError(response);
    });
    
    /**
     * AJAX loading flags.
     *
     * @type {object}
     */
    $scope.loading = {
      stats: true
    };
  }
]);
