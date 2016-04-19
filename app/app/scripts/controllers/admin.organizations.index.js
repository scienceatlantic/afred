'use strict';

angular.module('afredApp').controller('AdminOrganizationsIndexController', [
  '$scope',
  function($scope) {    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    $scope.organizations.parseParams();
    $scope.organizations.query();
  }
]);
