'use strict';

angular.module('afredApp').controller('AdminSectorsCreateController', [
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
     * Creates a new sector record. If the operation was successful,
     * the user is redirected to the 'admin.sectors.show' state of the
     * newly created record.
     *
     * Side effects:
     * $scope.loading.update Is set to true at the start of the function and
     *     then is set to false after the AJAX operation is complete.
     *
     * Calls/uses/requires:
     * $scope.sector
     * $scope._state.go()
     * confirmModal
     * infoModal
     * warningModal
     */
    $scope.create = function() {
      $scope.loading.create = true;
      var t = 'create-sector'; // Template name (to shorten code).
      
      confirmModal.open(t).result.then(function() {
        $scope.sector.$save(function(response) {
          infoModal.open(t + '-success').result.then(function() {
            $scope._state.go('admin.sectors.show', {
              sectorId: response.id
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
     * Side effects:
     * $scope.sector 'name' and 'isHidden' property is set to null.
     *
     * Uses/calls/requires:
     * confirmModal
     *
     * @param {Angular FormController} formCtrl '$setPristine()' method is
     *     is called if the user confirms the action.
     */
    $scope.clear = function(formCtrl) {
      var t = 'clear-create-sector-form'; // Template name (shorten code).
      
      confirmModal.open(t).result.then(function() {
        $scope.sector.name = null;
        $scope.sector.isHidden = null;
        formCtrl.$setPristine();        
      }, function() {
        // User hits cancel button, do nothing.
      });
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    /**
     * Holds the sector resource.
     * 
     * Uses/calls/requires:
     * SectorResource()
     * 
     * @type {Angular resource}
     */
    $scope.sector = new SectorResource();
    
    /**
     * AJAX loading flags.
     * 
     * @type {object}
     */
    $scope.loading = {
      create: false // Create sector.
    };
  }
]);
