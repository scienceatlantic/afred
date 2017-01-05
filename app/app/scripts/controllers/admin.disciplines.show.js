'use strict';

/**
 * @fileoverview Admin/Disciplines/Show page.
 * 
 * @see https://docs.angularjs.org/guide/controller
 * @see /scripts/routes.js (for route info)
 */

angular.module('afredApp').controller('AdminDisciplinesShowController', [
  '$scope',
  'confirmModal',
  'infoModal',
  'warningModal',
  'DisciplineResource',
  function($scope,
           confirmModal,
           infoModal,
           warningModal,
           DisciplineResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * Update discipline instance. The '$scope.commit()' function is called
     * if the AJAX operation was successful, otherwise the '$scope.rollback()'
     * function is called instead.
     *
     * Side effects:
     * $scope.loading.update Is set to true at the start of the function and
     *     then is set to false after the AJAX operation is complete.
     *
     * Calls/uses/requires:
     * $scope.discipline
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
      var t = 'update-discipline'; // Template name (to shorten code).
      confirmModal.open(t).result.then(function() {
        $scope.loading.update = true;
        $scope.discipline.$update(function(response) {
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
      });
    };
    
    /**
     * Delete the discipline instance.
     * Note: If the operation is successful, user will be redirected to
     * 'admin.discipline.index'.
     *
     * Side effects:
     * $scope.loading.remove Is set to true at the start of the function and
     *     then is set to false after the AJAX operation has completed.
     *
     * Calls/uses/requires:
     * $scope.discipline
     * $scope._state.go()
     * confirmModal
     * infoModal
     * warningModal
     */
    $scope.remove = function() {
      var t = 'delete-discipline'; // Template name (to shorten code).
      confirmModal.open(t).result.then(function() {
        $scope.loading.remove = true;
        $scope.discipline.$delete(function() {
          infoModal.open(t + '-success').result.then(function() {
            $scope.loading.remove = false;
            $scope._state.go('admin.disciplines.index');
          });
        }, function() {
          warningModal.open(t + '-failed');
          $scope.loading.remove = false;
        });
      });
    };
    
    /**
     * A copy of '$scope.discipline' is made and stored in
     * '$scope.disciplineCopy'.
     *
     * Side effects:
     * $scope.disciplineCopy
     */
    $scope.commit = function() {
      $scope.disciplineCopy = angular.copy($scope.discipline);
    };
    
    /**
     * A copy of '$scope.disciplineCopy' is made and stored in
     * '$scope.discipline'.
     *
     * Side effects:
     * $scope.discipline
     */
    $scope.rollback = function() {
      $scope.discipline = angular.copy($scope.disciplineCopy);
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    /**
     * Holds the discipline resource. If the operation fails, redirect
     * to error state.
     *
     * Side effects:
     * $scope.disciplineCopy A copy of the resource is stored here.
     * 
     * Uses/calls/requires:
     * angular.copy()
     * $scope._httpError403()
     * 
     * @type {Angular resource}
     */
    $scope.discipline = DisciplineResource.get($scope._stateParams,
      function() {
        $scope.disciplineCopy = angular.copy($scope.discipline);
      }, function(response) {
        $scope._httpError403(response);
      }
    );
    
    /**
     * AJAX loading flags.
     * 
     * @type {object}
     */
    $scope.loading = {
      update: false, // Update operation.
      remove: false // Remove operation.
    };
    
    /**
     * Stores a copy of '$scope.discipline' in case the update operation
     * fails and we have to revert.
     * 
     * @type {Angular resource}
     */
    $scope.disciplineCopy = null;
  }
]);
