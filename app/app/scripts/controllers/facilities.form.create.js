'use strict';

angular.module('afredApp').controller('FacilitiesFormCreateController',
  ['$scope',
   'facilityRepositoryResource',
  function($scope,
           facilityRepositoryResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */    
    
    /**
     * Shows the preview.
     */
    $scope.preview = function() {
      $scope.facility = $scope.form.formatForPreview();
      $scope.view.show = 'PREVIEW';
    };
    
    /**
     * Submits the form. A success message is shown if the operation was
     * successful otherwise an error message is shown instead.
     */
    $scope.submit = function() {
      facilityRepositoryResource.submit(
        {
          data: $scope.form.formatForApi()
        },
        function() {
          // Clear any saved data.
          //$scope.form.clearSave();
          $scope.view.show = 'SUCCESS_MESSAGE';
        }, function() {
          $scope.view.show = 'FAILURE_MESSAGE';
        }
      );
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    /**
     * Controls what is shown to the user.
     * @type {string} 'FORM', 'PREVIEW', 'SUCCESS_MESSAGE', 'FAILURE_MESSAGE'.
     */
    $scope.view = {
      show: 'FORM'
    };
    
    // Initialise the form, retrieve any saved data, and start autosaving.
    $scope.form.initialise();
    $scope.form.getSave();
    $scope.form.startAutosave();
  }
]);