'use strict';

/**
 * @fileoverview Privacy Policy page. Content is retrieved from 
 *     WordPress.
 * 
 * @see https://docs.angularjs.org/guide/controller
 * @see /scripts/routes.js
 */

angular.module('afredApp').controller('AboutLegalPrivacyPolicyController',
  ['$scope',
   'WpResource',
  function($scope,
           WpResource) {
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    $scope.wp = WpResource.getPage($scope._env.wp.pages['privacy policy']);
    $scope.wp.$promise.then(null, function(response) {
      $scope._httpError(response);
    });
  }
]);
