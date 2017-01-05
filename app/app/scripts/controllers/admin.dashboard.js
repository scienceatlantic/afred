'use strict';

/**
 * @fileoverview Admin/Dashboard page.
 * 
 * @see https://docs.angularjs.org/guide/controller
 * @see /scripts/routes.js
 */

angular.module('afredApp').controller('AdminDashboardController',
  ['$scope',
   'MiscResource',
  function($scope,
           MiscResource) {
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    // Get the data.
    $scope.stats = MiscResource.get({ item: 'facilityRepositoryBreakdown' });
    $scope.stats.$promise.then(function() {
      // Do nothing if successful.
    }, function(response) {
      $scope._httpError403(response);
    });
  }
]);
