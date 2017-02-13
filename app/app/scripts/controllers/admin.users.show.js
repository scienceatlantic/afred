'use strict';

angular.module('afredApp').controller('AdminUsersShowController', [
  '$scope',
  'confirmModal',
  'infoModal',
  'warningModal',
  'UserResource',
  'RoleResource',
  function($scope,
           confirmModal,
           infoModal,
           warningModal,
           UserResource,
           RoleResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * Update user instance. The '$scope.commit()' function is called
     * if the AJAX operation was successful, otherwise the '$scope.rollback()'
     * function is called instead.
     *
     * @sideffect $scope.loading.update Is set to true at the start of the
     *     function and then is set to false after the AJAX operation is
     *     complete.
     *
     * @requires $scope._form.cb.getSelected()
     * @requires $scope.getUser()
     * @requires $scope.user
     * @requires confirmModal
     * @requires infoModal
     * @requires warningModal
     *
     * @param {Angular FormController} formCtrl The `$setPristine()` function is
     *     called after the AJAX operation is complete (regardless of whether it
     *     failed or not). Will not be called if the user hits the cancel
     *     button.
     */
    $scope.update = function(formCtrl, resetPwd) {
      var t = 'update-user'; // Template name (to shorten code).
      confirmModal.open(t).result.then(function() {
        $scope.loading.update = true;

        // Get roles.
        $scope.user.roles = $scope._form.cb.getSelected($scope.roles, true);

        $scope.user.$update(function(response) {
          // If the user is updating their own profile, make sure to ping the
          // API after to the save changes 
          // (i.e. update the '_auth.user' object).
          if (response.id == $scope._auth.user.id) {
            $scope._auth.ping().then(function() {
              infoModal.open(t + '-success').result.then(function() {
                formCtrl.$setPristine();
                $scope.loading.update = false;            
              });  
            });
          } else {
            infoModal.open(t + '-success').result.then(function() {
              formCtrl.$setPristine();
              $scope.loading.update = false;            
            });
          }
        }, function() {
          warningModal.open(t + '-failed').result.then(function() {
            $scope.getUser();
            formCtrl.$setPristine();
            $scope.loading.update = false;
          });
        });
      });
    };
    
    /**
     * Delete the user instance.
     * Note: If the operation is successful, user will be redirected to
     * 'admin.user.index'.
     *
     * @sideffect $scope.loading.remove Is set to true at the start of the
     *     function and then is set to false after the AJAX operation has
     *     completed.
     *
     * @requires $scope._state.go()
     * @requires $scope.user
     * @requires confirmModal
     * @requires infoModal
     * @requires warningModal
     */
    $scope.remove = function() {
      var t = 'delete-user'; // Template name (to shorten code).
      confirmModal.open(t).result.then(function() {
        $scope.loading.remove = true;
        $scope.user.$delete(function() {
          infoModal.open(t + '-success').result.then(function() {
            $scope.loading.remove = false;
            $scope._state.go('admin.users.index');
          });
        }, function() {
          warningModal.open(t + '-failed');
          $scope.loading.remove = false;
        });
      });
    };

    /**
     * Reset a user's password.
     * 
     * @requires $scope._stateParams.userId
     * @requires $scope.userPassword
     * @requires confirmModal
     * @requires infoModal
     * @requires UserResource
     * @requires warningModal
     * 
     * @param {Angular FormController} formCtrl The `$setPristine()` function is
     *     called after the AJAX operation is complete (regardless of whether it
     *     failed or not; will not be called if the user hits the cancel
     *     button).
     */
    $scope.updatePassword = function(formCtrl) {
      var t = 'reset-user-password'; // Template name (to shorten code).
      confirmModal.open(t).result.then(function() {
        $scope.loading.updatePassword = true;
        UserResource.update({
          userId: $scope._stateParams.userId
        }, $scope.userPassword, function(response) {
          infoModal.open(t + '-success').result.then(function() {
            formCtrl.$setPristine();
            $scope.loading.updatePassword = false;            
          });
        }, function() {
          warningModal.open(t + '-failed').result.then(function() {
            $scope.loading.updatePassword = false;
          });
        });
      });
    };

    /**
     * Gets user.
     * 
     * @sideeffect $scope.user Data retrieved is stored here.
     * 
     * @requires $scope._httpError403() Called if AJAX operation fails.
     * @requires RoleResource
     * @requires UserResource
     */
    $scope.getUser = function() {
      $scope.user = UserResource.get($scope._stateParams, function() {
        // Get and set roles.
        $scope.roles = RoleResource.queryNoPaginate(null, function(roles) {
          roles.forEach(function(role) {
            role.isSelected = Boolean($scope.user.roles[role.id]);
          });
        }, function(response) {
          $scope._httpError403(response);
        });          
      }, function(response) {
        $scope._httpError403(response);
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
    $scope.user = null;


    /**
     * Holds data from the user password reset form.
     * 
     * @type {object}
     */
    $scope.userPassword = {
      password: null
    };

    /**
     * Holds array of roles.
     * 
     * @type {Array of Angular resources}
     */
    $scope.roles = null;
    
    /**
     * AJAX loading flags.
     * 
     * @type {object}
     */
    $scope.loading = {
      update: false, // Update operation.
      remove: false, // Remove operation.
      updatePassword: false // Update password operation.
    };

    // Get user.
    $scope.getUser();
  }
]);
