'use strict';

angular.module('afredApp').controller('AdminProvincesCreateController', [
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
     * Creates a new province record. If the operation was successful,
     * the user is redirected to the 'admin.provinces.show' state of the
     * newly created record.
     *
     * @sideffect $scope.loading.update Is set to true at the start of the
     *      function and then is set to false after the AJAX operation is
     *      complete.
     *
     * @requires $scope._state.go()
     * @requires $scope.province
     * @requires confirmModal
     * @requires infoModal
     * @requires warningModal
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
     * @sideeffect $scope.province `name` and `isHidden` property is set to
     *     null.
     *
     * @requires confirmModal
     *
     * @param {Angular FormController} formCtrl `$setPristine()` method is
     *     called if the user confirms the action.
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
     * @requires ProvinceResource()
     * 
     * @type {Angular resource}
     */
    $scope.province = new ProvinceResource();
    
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
