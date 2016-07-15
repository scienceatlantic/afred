'use strict';

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
