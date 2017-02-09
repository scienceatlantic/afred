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
  'DisciplineResource',
  'infoModal',
  'warningModal',
  function($scope,
           confirmModal,
           DisciplineResource,
           infoModal,
           warningModal) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * Update discipline instance. The `$scope.commit()` function is called
     * if the AJAX operation was successful, otherwise the `$scope.rollback()`
     * function is called instead.
     *
     * @sideeffect $scope.loading.update Is set to true at the start of the
     *     function and then is set to false after the AJAX operation is
     *     complete.
     *
     * @requires $scope.commit() 
     * @requires $scope.discipline
     * @requires $scope.rollback()
     * @requires confirmModal
     * @requires infoModal
     * @requires warningModal
     *
     * @param {Angular FormController} formCtrl The `$setPristine()` function is
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
     * Delete the discipline instance. Note: If the operation is successful,
     * user will be redirected to the 'admin.discipline.index' state.
     *
     * @sideeffect $scope.loading.remove Is set to true at the start of the
     *     function and then is set to false after the AJAX operation has
     *     completed.
     *
     * @requires $scope._state.go()
     * @requires $scope.discipline
     * @requires confirmModal
     * @requires infoModal
     * @requires warningModal
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
     * A copy of `$scope.discipline` is made and stored in
     * `$scope.disciplineCopy`.
     *
     * @sifeeffect $scope.disciplineCopy
     * 
     * @requires angular.copy()
     */
    $scope.commit = function() {
      $scope.disciplineCopy = angular.copy($scope.discipline);
    };
    
    /**
     * A copy of `$scope.disciplineCopy` is made and stored in
     * `$scope.discipline`.
     *
     * @sideffect $scope.discipline
     * 
     * @requires angular.copy()
     */
    $scope.rollback = function() {
      $scope.discipline = angular.copy($scope.disciplineCopy);
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    /**
     * Stores a copy of `$scope.discipline` in case the update operation fails
     * and we have to revert.
     * 
     * @type {Angular resource}
     */
    $scope.disciplineCopy = null;

    /**
     * Holds the discipline resource. If the operation fails, redirect
     * to error state.
     *
     * @sideeffect $scope.disciplineCopy A copy of the resource is stored here.
     * 
     * @requires $scope._httpError403()
     * @requires $scope._stateParams
     * @requires angular.copy()
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
      update: false,
      remove: false
    };
  }
]);
