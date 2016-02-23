'use strict';

angular.module('afredApp').controller('FacilitiesFormCreateController',
  ['$scope',
   '$interval',
   'facilityRepositoryResource',
  function($scope,
           $interval,
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
      scrollTo(0, 300);
    };
    
    $scope.goBack = function() {
      $scope.view.show = 'FORM';
      scrollTo(0, 0);
    };
    
    /**
     * Submits the form. A success message is shown if the operation was
     * successful otherwise an error message is shown instead.
     */
    $scope.submit = function() {
      $scope.fr = facilityRepositoryResource.submit(
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
    
    /**
     * Will the data return by '$scope.submit()'.
     * @type {object}
     */
    $scope.fr = {};
    
    // Initialise the form, retrieve any saved data, and start autosaving.
    $scope.form.initialise();
    
    // Because of async issues, we're going to keep calling
    // '$scope.form.getSave()' until it either retrieves the data successfully
    // or fails because local storage is not supported.
    var intervalId = $interval(function() {
      if ($scope.form.getSave() >= 0) {
        $interval.cancel(intervalId);
        $scope.form.startAutosave();
      }
    }, 350);
  }
]);