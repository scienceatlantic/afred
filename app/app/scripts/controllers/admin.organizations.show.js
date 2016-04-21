'use strict';

angular.module('afredApp').controller('AdminOrganizationsShowController', [
  '$scope',
  'confirmModal',
  'infoModal',
  'warningModal',
  'organizationResource',
  function($scope,
           confirmModal,
           infoModal,
           warningModal,
           organizationResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * Update organization instance. The '$scope.commit()' function is called
     * if the AJAX operation was successful, otherwise the '$scope.rollback()'
     * function is called instead.
     *
     * Side effects:
     * $scope.loading.update Is set to true at the start of the function and
     *     then is set to false after the AJAX operation is complete.
     *
     * Calls/uses/requires:
     * $scope.organization
     * $scope.commit() 
     * $scope.rollback()
     * confirmModal
     * infoModal
     * warningModal
     *
     * @param {Angular FormController} formCtrl The '$setPristine()' function is
     *     called after the AJAX operation is complete (regardless of whether it
     *     failed or not). Will not be called if the user hits the cancel
     *     button.
     */
    $scope.update = function(formCtrl) {
      $scope.loading.update = true;
      var t = 'update-organization'; // Template name (to shorten code).
      
      confirmModal.open(t).result.then(function() {
        $scope.organization.$update(function(response) {
          infoModal.open(t + '-success').result.then(function() {
            $scope.commit();
            formCtrl.$setPristine();
            $scope.loading.update = false;            
          });
        }, function() {
          warningModal.open(t + '-failed').result.then(function() {
            $scope.rollback();
            formCtrl.$setPristine();
            $scope.loading.update = false;
          });
        });
      }, function() {
        // If the user hits the cancel button, we have to reset the AJAX flag.
        $scope.loading.update = false;
      });
    };
    
    /**
     * Delete the organization instance.
     * Note: If the operation is successful, user will be redirected to
     * 'admin.organization.index'.
     *
     * Side effects:
     * $scope.loading.remove Is set to true at the start of the function and
     *     then is set to false after the AJAX operation has completed.
     *
     * Calls/uses/requires:
     * $scope.organization
     * $scope._state.go()
     * confirmModal
     * infoModal
     * warningModal
     */
    $scope.remove = function() {
      $scope.loading.remove = true;
      var t = 'delete-organization'; // Template name (to shorten code).
      
      confirmModal.open(t).result.then(function() {
        $scope.organization.$delete(function() {
          infoModal.open(t + '-success').result.then(function() {
            $scope.loading.remove = false;
            $scope._state.go('admin.organizations.index');
          });
        }, function() {
          warningModal.open(t + '-failed');
          $scope.loading.remove = false;
        });
      }, function() {
        // Cancel button is clicked, reset AJAX flag.
        $scope.loading.remove = false;
      });
    };
    
    /**
     * A copy of '$scope.organization' is made and stored in
     * '$scope.organizationCopy'.
     *
     * Side effects:
     * $scope.organizationCopy
     */
    $scope.commit = function() {
      $scope.organizationCopy = angular.copy($scope.organization);
    };
    
    /**
     * A copy of '$scope.organizationCopy' is made and stored in
     * '$scope.organization'.
     *
     * Side effects:
     * $scope.organization
     */
    $scope.rollback = function() {
      $scope.organization = angular.copy($scope.organizationCopy);
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    /**
     * Holds the organization resource. If the operation fails, redirect
     * to error state.
     *
     * Side effects:
     * $scope.organizationCopy A copy of the resource is stored here.
     * 
     * Uses/calls/requires:
     * angular.copy()
     * $scope._httpError()
     * 
     * @type {Angular resource}
     */
    $scope.organization = organizationResource.get($scope._stateParams,
      function() {
        $scope.organizationCopy = angular.copy($scope.organization);
      },
      function(response) {
        $scope._httpError(response);
      }
    );
    
    /**
     * AJAX loading flags.
     * 
     * @type {object}
     */
    $scope.loading = {
      update: false, // Update operation.
      remove: false // Remvoe operation.
    };
    
    /**
     * Stores a copy of '$scope.organization' in case the update operation
     * fails and we have to revert.
     * 
     * @type {Angular resource}
     */
    $scope.organizationCopy = null;
  }
]);
