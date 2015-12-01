'use strict';

angular.module('afredApp').controller('FacilitiesFormCreateController',
  ['$scope',
   '$timeout',
   'facilityRevisionHistoryResource',
  function($scope,
           $timeout,
           facilityRevisionHistoryResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */    
    
    $scope.preview = function() {
      $scope.view.show = 'preview';
    };
    
    $scope.submit = function() {
      facilityRevisionHistoryResource.save($scope.prepareForDb(), function() {
        // Reset form
        //$scope.initialiseForm();
        //$scope.view.show = 'successMessage';
      }, function(response) {
        //$scope.r = response.data;
        //$scope.view.show = 'failureMessage';
      });
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    $scope.initialiseForm();
    $scope.getAutosave();
    $scope.autosave();
    
    $scope.view = {
      show: 'form'  //'form', 'preview', 'successMessage', 'failureMessage'
    };
    
    $scope.loading = {
      form: true
    };
    
    // Insert an artificial delay to the form.
    $timeout(function() {
      $scope.loading.form = false;
    }, 2000);
  }
]);