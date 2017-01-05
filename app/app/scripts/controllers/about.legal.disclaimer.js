'use strict';

/**
 * @fileoverview Disclaimer page. Content is retrieved from WordPress.
 * 
 * @see https://docs.angularjs.org/guide/controller
 * @see /scripts/routes.js (for route info)
 */

angular.module('afredApp').controller('AboutLegalDisclaimerController',
  ['$scope',
   'WpResource',
  function($scope,
           WpResource) {
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    $scope.wp = WpResource.getPage($scope._env.wp.pages['disclaimer']);
    $scope.wp.$promise.then(null, function(response) {
      $scope._httpError(response);
    });
  }
]);
