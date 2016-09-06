'use strict';

/**
 * @fileoverview Admin/Dashboard page.
 * 
 * @see https://docs.angularjs.org/guide/controller
 * @see /scripts/routes.js
 */

angular.module('afredApp').controller('AdminDashboardController',
  ['$scope',
   '$http',
  function($scope,
           $http) {
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    // Get the data.
    $scope.stats = $http.get($scope._env.api.address + '/dashboard');
    $scope.stats.then(function(response) {
      $scope.stats.data = response.data;
      $scope.loading.stats = false;
    }, function(response) {
      $scope._httpError403(response);
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
