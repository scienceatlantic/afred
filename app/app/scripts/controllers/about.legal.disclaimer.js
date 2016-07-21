'use strict';

/**
 * @fileoverview Disclaimer page. Content is retrieved from WordPress.
 * 
 * @see https://docs.angularjs.org/guide/controller
 * @see /scripts/routes.js (for route info)
 */

angular.module('afredApp').controller('AboutLegalDisclaimerController',
  ['$scope',
   'wpResource',
  function($scope,
           wpResource) {
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    $scope.wp = wpResource.getPage($scope._env.wp.pages['disclaimer']);
    $scope.wp.$promise.then(null, function(response) {
      $scope._httpError(response);
    });
  }
]);
