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
   'wpResource',
  function($scope,
           wpResource) {
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    $scope.wp = wpResource.getPage($scope._env.wp.pages['privacy policy']);
    $scope.wp.$promise.then(null, function(response) {
      $scope._httpError(response);
    });
  }
]);
