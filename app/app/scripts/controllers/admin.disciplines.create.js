'use strict';

/**
 * @fileoverview Admin/Disciplines/Create page.
 * 
 * @see https://docs.angularjs.org/guide/controller
 * @see /scripts/routes.js (for route info)
 */

angular.module('afredApp').controller('AdminDisciplinesCreateController', [
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
     * Creates a new discipline record. If the operation was successful,
     * the user is redirected to the 'admin.disciplines.show' state of the
     * newly created record.
     *
     * Side effects:
     * $scope.loading.update Is set to true at the start of the function and
     *     then is set to false after the AJAX operation is complete.
     *
     * Calls/uses/requires:
     * $scope.discipline
     * $scope._state.go()
     * confirmModal
     * infoModal
     * warningModal
     */
    $scope.create = function() {
      $scope.loading.create = true;
      var t = 'create-discipline'; // Template name (to shorten code).
      
      confirmModal.open(t).result.then(function() {
        $scope.discipline.$save(function(response) {
          infoModal.open(t + '-success').result.then(function() {
            $scope._state.go('admin.disciplines.show', {
              disciplineId: response.id
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
     * $scope.discipline 'name' and 'isHidden' property is set to null.
     *
     * Uses/calls/requires:
     * confirmModal
     *
     * @param {Angular FormController} formCtrl '$setPristine()' method is
     *     is called if the user confirms the action.
     */
    $scope.clear = function(formCtrl) {
      var t = 'clear-create-discipline-form'; // Template name (shorten code).
      
      confirmModal.open(t).result.then(function() {
        $scope.discipline.name = null;
        $scope.discipline.isHidden = null;
        formCtrl.$setPristine();        
      }, function() {
        // User hits cancel button, do nothing.
      });
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    /**
     * Holds the discipline resource.
     * 
     * Uses/calls/requires:
     * DisciplineResource()
     * 
     * @type {Angular resource}
     */
    $scope.discipline = new DisciplineResource();
    
    /**
     * AJAX loading flags.
     * 
     * @type {object}
     */
    $scope.loading = {
      create: false // Create discipline.
    };
  }
]);
