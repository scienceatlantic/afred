'use strict';

angular.module('afredApp').controller('AdminUsersIndexController', [
  '$scope',
  function($scope) {    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    $scope.users.parseParams();
    $scope.users.query();
  }
]);
