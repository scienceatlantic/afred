'use strict';

angular.module('afredApp').controller('FacilitiesFormGuideController',
  ['$scope',
   'WpResource',
  function($scope,
           WpResource) {
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    $scope.wp = WpResource.getPage($scope._env.wp.pages['form guide']);
    $scope.wp.$promise.then(null, function(response) {
      $scope._httpError(response);
    });
  }
]);
