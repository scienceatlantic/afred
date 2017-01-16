'use strict';

angular.module('afredApp').controller('AdminSectorsShowController', [
  '$scope',
  'confirmModal',
  'infoModal',
  'warningModal',
  'SectorResource',
  function($scope,
           confirmModal,
           infoModal,
           warningModal,
           SectorResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * Update sector instance. The '$scope.commit()' function is called
     * if the AJAX operation was successful, otherwise the '$scope.rollback()'
     * function is called instead.
     *
     * Side effects:
     * $scope.loading.update Is set to true at the start of the function and
     *     then is set to false after the AJAX operation is complete.
     *
     * Calls/uses/requires:
     * $scope.sector
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
      var t = 'update-sector'; // Template name (to shorten code).
      confirmModal.open(t).result.then(function() {
        $scope.loading.update = true;
        $scope.sector.$update(function(response) {
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
     * Delete the sector instance.
     * Note: If the operation is successful, user will be redirected to
     * 'admin.sector.index'.
     *
     * Side effects:
     * $scope.loading.remove Is set to true at the start of the function and
     *     then is set to false after the AJAX operation has completed.
     *
     * Calls/uses/requires:
     * $scope.sector
     * $scope._state.go()
     * confirmModal
     * infoModal
     * warningModal
     */
    $scope.remove = function() {
      var t = 'delete-sector'; // Template name (to shorten code).
      confirmModal.open(t).result.then(function() {
        $scope.loading.remove = true;
        $scope.sector.$delete(function() {
          infoModal.open(t + '-success').result.then(function() {
            $scope.loading.remove = false;
            $scope._state.go('admin.sectors.index');
          });
        }, function() {
          warningModal.open(t + '-failed');
          $scope.loading.remove = false;
        });
      });
    };
    
    /**
     * A copy of '$scope.sector' is made and stored in
     * '$scope.sectorCopy'.
     *
     * Side effects:
     * $scope.sectorCopy
     */
    $scope.commit = function() {
      $scope.sectorCopy = angular.copy($scope.sector);
    };
    
    /**
     * A copy of '$scope.sectorCopy' is made and stored in
     * '$scope.sector'.
     *
     * Side effects:
     * $scope.sector
     */
    $scope.rollback = function() {
      $scope.sector = angular.copy($scope.sectorCopy);
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    /**
     * Holds the sector resource. If the operation fails, redirect
     * to error state.
     *
     * Side effects:
     * $scope.sectorCopy A copy of the resource is stored here.
     * 
     * Uses/calls/requires:
     * angular.copy()
     * $scope._httpError403()
     * 
     * @type {Angular resource}
     */
    $scope.sector = SectorResource.get($scope._stateParams,
      function() {
        $scope.sectorCopy = angular.copy($scope.sector);
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
     * Stores a copy of '$scope.sector' in case the update operation
     * fails and we have to revert.
     * 
     * @type {Angular resource}
     */
    $scope.sectorCopy = null;
  }
]);
