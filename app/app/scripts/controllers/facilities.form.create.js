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
    
    /**
     * Shows the preview.
     */
    $scope.preview = function() {
      $scope.view.show = 'preview';
    };
    
    /**
     * Submits the form. A success message is shown if the operation was
     * successful otherwise an error message is shown instead.
     */
    $scope.submit = function() {
      facilityRevisionHistoryResource.save($scope.form.formatForApi(),
        function() {
          // Reset form
          //$scope.form.initialiseForm();
          //$scope.form.save();
          $scope.view.show = 'successMessage';
        }, function() {
          $scope.view.show = 'failureMessage';
        }
      );
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    /**
     * Controls what is shown to the user.
     * @type {string} 'form', 'preview', 'successMessage', 'failureMessage'
     */
    $scope.view = {
      show: 'form'
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