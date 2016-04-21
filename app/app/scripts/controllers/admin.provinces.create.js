'use strict';

angular.module('afredApp').controller('AdminProvincesCreateController', [
  '$scope',
  'confirmModal',
  'infoModal',
  'warningModal',
  'provinceResource',
  function($scope,
           confirmModal,
           infoModal,
           warningModal,
           provinceResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * Creates a new province record. If the operation was successful,
     * the user is redirected to the 'admin.provinces.show' state of the
     * newly created record.
     *
     * Side effects:
     * $scope.loading.update Is set to true at the start of the function and
     *     then is set to false after the AJAX operation is complete.
     *
     * Calls/uses/requires:
     * $scope.province
     * $scope._state.go()
     * confirmModal
     * infoModal
     * warningModal
     */
    $scope.create = function() {
      $scope.loading.create = true;
      var t = 'create-province'; // Template name (to shorten code).
      
      confirmModal.open(t).result.then(function() {
        $scope.province.$save(function(response) {
          infoModal.open(t + '-success').result.then(function() {
            $scope._state.go('admin.provinces.show', {
              provinceId: response.id
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
     * $scope.province 'name' and 'isHidden' property is set to null.
     *
     * Uses/calls/requires:
     * confirmModal
     *
     * @param {Angular FormController} formCtrl '$setPristine()' method is
     *     is called if the user confirms the action.
     */
    $scope.clear = function(formCtrl) {
      var t = 'clear-create-province-form'; // Template name (shorten code).
      
      confirmModal.open(t).result.then(function() {
        $scope.province.name = null;
        $scope.province.isHidden = null;
        formCtrl.$setPristine();        
      }, function() {
        // User hits cancel button, do nothing.
      });
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    /**
     * Holds the province resource.
     * 
     * Uses/calls/requires:
     * provinceResource()
     * 
     * @type {Angular resource}
     */
    $scope.province = new provinceResource();
    
    /**
     * AJAX loading flags.
     * 
     * @type {object}
     */
    $scope.loading = {
      create: false // Create province.
    };
  }
]);
