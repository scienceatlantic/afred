'use strict';

angular.module('afredApp').controller('FacilitiesFormEditController',
  ['$scope',
   'facilityRevisionHistoryResource',
  function($scope,
           facilityRevisionHistoryResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */    
    
    $scope.preview = function() {
      $scope.showPreview = true;
      $scope.showForm = false;
    };
    
    $scope.form = function() {
      $scope.showPreview = false;
      $scope.showForm = true;
    };
    
    $scope.submit = function() {
      
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    $scope.initialiseForm();
    $scope.showForm = true;
    $scope.showPreview = false;
    $scope.getAutosave();
    $scope.autosave();
    
  }
]);