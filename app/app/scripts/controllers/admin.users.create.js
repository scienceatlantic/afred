'use strict';

angular.module('afredApp').controller('AdminUsersCreateController', [
  '$scope',
  'confirmModal',
  'infoModal',
  'RoleResource',
  'UserResource',
  'warningModal',
  function($scope,
           confirmModal,
           infoModal,
           RoleResource,
           UserResource,
           warningModal) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * Creates a new user record. If the operation was successful, the user is
     * redirected to the 'admin.users.show' state of the newly created record.
     *
     * @sideffect $scope.loading.update Is set to true at the start of the
     *     function and then is set to false after the AJAX operation is
     *     complete.
     *
     * @requires $scope._form.cb.getSelected()
     * @requires $scope._state.go()
     * @requires $scope.user
     * @requires confirmModal
     * @requires infoModal
     * @requires warningModal
     */
    $scope.create = function() {
      $scope.loading.create = true;
      var t = 'create-user'; // Template name (to shorten code).
      
      confirmModal.open(t).result.then(function() {
        $scope.user.roles = $scope._form.cb.getSelected($scope.roles, true);
        $scope.user.$save(function(response) {
          infoModal.open(t + '-success').result.then(function() {
            $scope._state.go('admin.users.show', {
              userId: response.id
            });
          });
        }, function() {
          $scope.loading.create = false;
          warningModal.open(t + '-failed');
        });        
      }, function() {
        // User hits the cancel button...
        $scope.loading.create = false;
      });
    };
    
    /**
     * Clear the form.
     *
     * @sideeffect $scope.user `name` and `isHidden` property is set to null.
     *
     * @requires confirmModal
     *
     * @param {Angular FormController} formCtrl `$setPristine()` method is
     *     called if the user confirms the action.
     */
    $scope.clear = function(formCtrl) {
      var t = 'clear-create-user-form'; // Template name (shorten code).
      
      confirmModal.open(t).result.then(function() {
        $scope.user.firstName = null;
        $scope.user.lastName = null;
        $scope.user.email = null;
        $scope.user.isActive = null;
        $scope.roles.forEach(function(role) {
          role.isSelected = false;
        });
        formCtrl.$setPristine();        
      }, function() {
        // User hits cancel button, do nothing.
      });
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    /**
     * Holds the user resource.
     * 
     * @type {Angular resource}
     */
    $scope.user = new UserResource();

    /**
     * 
     * 
     */
    $scope.roles = RoleResource.queryNoPaginate();
    $scope.roles.$promise.then(function(roles) {
      roles.forEach(function(role) {
        role.isSelected = false;
      });
    });
    
    /**
     * AJAX loading flags.
     * 
     * @type {object}
     */
    $scope.loading = {
      create: false // Create user.
    };
  }
]);
