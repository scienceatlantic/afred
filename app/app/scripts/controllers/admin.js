'use strict';

/**
 * @fileoverview Admin abstract controller.
 * 
 * @see https://docs.angularjs.org/guide/controller
 * @see /scripts/routes.js
 */

angular.module('afredApp').controller('AdminController',
  ['$scope',
  function($scope) {
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    // Check if the user is logged in and if not, redirect to login. This code
    // is being run in the abstract 'admin' state so that all states requiring
    // authenticated access are covered.
    $scope._auth.ping('promise').then(function(response) {
      // We're assuming that if the response doesn't contain the user's details
      // (id in this case), the user is not authenticated.
      if (!response.data.id) {
        $scope._httpError403('403');
      }
    }, function(response) {
      $scope._httpError(response);
    });
  }
]);
