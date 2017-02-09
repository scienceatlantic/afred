'use strict';

angular.module('afredApp').controller('AdminProvincesShowController', [
  '$scope',
  'confirmModal',
  'infoModal',
  'ProvinceResource',
  'warningModal',
  function($scope,
           confirmModal,
           infoModal,
           ProvinceResource,
           warningModal) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * Update province instance. The `$scope.commit()` function is called
     * if the AJAX operation was successful, otherwise the `$scope.rollback()`
     * function is called instead.
     *
     * @sideffect $scope.loading.update Is set to true at the start of the
     *     function and then is set to false after the AJAX operation is
     *     complete.
     *
     * @requires $scope.commit() 
     * @requires $scope.province
     * @requires $scope.rollback()
     * @requires confirmModal
     * @requires infoModal
     * @requires warningModal
     *
     * @param {Angular FormController} formCtrl The `$setPristine()` function is
     *     called after the AJAX operation is complete (regardless of whether it
     *     failed or not; will not be called if the user hits the cancel
     *     button).
     */
    $scope.update = function(formCtrl) {
      var t = 'update-province'; // Template name (to shorten code).
      confirmModal.open(t).result.then(function() {
        $scope.loading.update = true;
        $scope.province.$update(function(response) {
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
     * Delete the province instance.
     * Note: If the operation is successful, user will be redirected to
     * 'admin.province.index'.
     *
     * @sideeffect $scope.loading.remove Is set to true at the start of the
     *     function and then is set to false after the AJAX operation has
     *     completed.
     *
     * @requires $scope._state.go()
     * @requires $scope.province
     * @requires confirmModal
     * @requires infoModal
     * @requires warningModal
     */
    $scope.remove = function() {
      var t = 'delete-province'; // Template name (to shorten code).
      confirmModal.open(t).result.then(function() {
        $scope.loading.remove = true;
        $scope.province.$delete(function() {
          infoModal.open(t + '-success').result.then(function() {
            $scope.loading.remove = false;
            $scope._state.go('admin.provinces.index');
          });
        }, function() {
          warningModal.open(t + '-failed');
          $scope.loading.remove = false;
        });
      });
    };
    
    /**
     * A copy of `$scope.province` is made and stored in `$scope.provinceCopy`.
     *
     * @sideeffect $scope.provinceCopy
     * 
     * @requires $scope.province
     * @requires angular.copy()
     */
    $scope.commit = function() {
      $scope.provinceCopy = angular.copy($scope.province);
    };
    
    /**
     * A copy of `$scope.provinceCopy` is made and stored in `$scope.province`.
     *
     * @sideffect $scope.province
     * 
     * @requires $scope.provinceCopy
     * @requires angular.copy()
     */
    $scope.rollback = function() {
      $scope.province = angular.copy($scope.provinceCopy);
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    /**
     * Stores a copy of '$scope.province' in case the update operation
     * fails and we have to revert.
     * 
     * @type {Angular resource}
     */
    $scope.provinceCopy = null;

    /**
     * Holds the province resource. If the operation fails, redirect
     * to error state.
     *
     * @sideeffect $scope.provinceCopy A copy of the resource is stored here.
     * 
     * @requires $scope._httpError403()
     * @requires angular.copy()
     * 
     * @type {Angular resource}
     */
    $scope.province = ProvinceResource.get($scope._stateParams,
      function() {
        $scope.provinceCopy = angular.copy($scope.province);
      },
      function(response) {
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
  }
]);
