'use strict';

angular.module('afredApp').controller('FacilitiesFormCreateController',
  ['$scope',
   '$timeout',
   'facilityRepositoryResource',
  function($scope,
           $timeout,
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
          // Reset form
          //$scope.form.initialiseForm(true);
          //$scope.form.save();
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
     * @type {string} 'FORM', 'PREVIEW', 'SUCCESS_MESSAGE', 'FAILURE_MESSAGE'
     */
    $scope.view = {
      show: 'FORM'
    };
    
    /**
     * Loading flags.
     */
    $scope.loading = {
      form: true
    };
    
    // Initialise the form, retrieve any saved data, and start autosaving.
    $scope.form.initialise();
    $scope.form.getSave(); // BUG! Radio buttons are not highlighted
                           // after data is retrieved.
    $scope.form.startAutosave();
    
    // Insert an artificial delay before showing the form.
    $timeout(function() {
      $scope.loading.form = false;
    }, 1500);
  }
]);