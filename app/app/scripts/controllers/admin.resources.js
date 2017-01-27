'use strict';

/**
 * @fileoverview Admin / Resources page. Content is retrieved from WordPress.
 * 
 * @see https://docs.angularjs.org/guide/controller
 * @see /scripts/routes.js (for route info)
 */

angular.module('afredApp').controller('AdminResourcesController',
  ['$scope',
   'WpResource',
  function($scope,
           WpResource) {
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    $scope.wp = WpResource.getPage($scope._env.wp.pages['admin resources']);
    $scope.wp.$promise.then(null, function(response) {
      $scope._httpError(response);
    });
  }
]);
