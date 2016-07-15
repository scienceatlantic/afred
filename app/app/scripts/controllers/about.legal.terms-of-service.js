'use strict';

angular.module('afredApp').controller('AboutLegalTermsOfServiceController',
  ['$scope',
   'wpResource',
  function($scope,
           wpResource) {
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    $scope.wp = wpResource.getPage($scope._env.wp.pages['terms of service']);
    $scope.wp.$promise.then(null, function(response) {
      $scope._httpError(response);
    });
  }
]);
