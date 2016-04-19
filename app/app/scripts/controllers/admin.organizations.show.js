'use strict';

angular.module('afredApp').controller('AdminOrganizationsShowController', [
  '$scope',
  'confirmModal',
  'infoModal',
  'organizationResource',
  function($scope,
           confirmModal,
           infoModal,
           organizationResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * Update organization instance.
     */
    $scope.update = function() {
      confirmModal.open('update-organization').result.then(function() {
        $scope.organization.$update();
      });
    };
    
    $scope.remove = function() {
      confirmModal.open('delete-organization').result.then(function() {
        $scope.organization.$delete(function(response) {
          $scope._state.go('admin.organizations.index');
        }, function(response) {
          infoModal.open('cant-delete-organization');
        });
      });
    }
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    // Get the organization.
    $scope.organization = organizationResource.get($scope._stateParams);
    
    // If the operation failed, redirect to error page.
    $scope.organization.$promise.then(null, function(response) {
      $scope._httpError(response);
    });
  }
]);
