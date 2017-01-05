'use strict';

/**
 * @fileoverview Terms of Service page. Content is retrieved from WordPress.
 * 
 * @see https://docs.angularjs.org/guide/controller
 * @see /scripts/routes.js
 */

angular.module('afredApp').controller('AboutLegalTermsOfServiceController',
  ['$scope',
   'WpResource',
  function($scope,
           WpResource) {
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    $scope.wp = WpResource.getPage($scope._env.wp.pages['terms of service']);
    $scope.wp.$promise.then(null, function(response) {
      $scope._httpError(response);
    });
  }
]);
