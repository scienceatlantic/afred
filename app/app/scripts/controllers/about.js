'use strict';

angular.module('afredApp').controller('AboutController',
  ['$scope',
   'wpResource',
  function($scope,
           wpResource) {

    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    $scope.wp = wpResource.getPage($scope._config.wp.pages['about']);
    $scope.wp.$promise.then(null, function() {
      $scope._state.go('500');
    });
  }
]);
