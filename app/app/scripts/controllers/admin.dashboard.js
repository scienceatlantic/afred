'use strict';

/**
 * @fileoverview Admin/Dashboard page.
 * 
 * @see https://docs.angularjs.org/guide/controller
 * @see /scripts/routes.js
 */

angular.module('afredApp').controller('AdminDashboardController',
  ['$scope',
   'miscResource',
  function($scope,
           miscResource) {
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    // Get the data.
    $scope.stats = miscResource.get({ item: 'facilityRepositoryBreakdown' });
    $scope.stats.$promise.then(function() {
      // Do nothing if successful.
    }, function(response) {
      $scope._httpError403(response);
    });
  }
]);
