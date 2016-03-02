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
     *
     * Side effects:
     * $scope.view.show Set to 'PREVIEW'.
     * $scope.facility Data returned from '$scope.form.formatForPreview()'
     *     is attached to this.
     *
     * Uses/calls/requires:
     * $scope.form.formatForPreview();
     */
    $scope.preview = function() {
      $scope.facility = $scope.form.formatForPreview();
      $scope.view.show = 'PREVIEW';
      scrollTo(0, 300);
    };
    
    
    /**
     * Returns view to the form.
     *
     * Side effects:
     * $scope.view.show Set to 'FORM'.
     */
    $scope.goBack = function() {
      $scope.view.show = 'FORM';
      scrollTo(0, 0);
    };
    
    /**
     * Submits the form. A success message is shown if the operation was
     * successful otherwise an error message is shown instead.
     *
     * Side effects:
     * $scope.fr Promised returned is attached to this.
     * $scope.view.show Set to 'SUCCESS_MESSAGE' if the operation was
     *     successful, otherwise it is set to 'FAILURE_MESSAGE'.
     *
     * Uses/calls/requires:
     * facilityRepositoryResource
     * $scope.form.formatForApi()
     * $scope.form.clearSave()
     */
    $scope.submit = function() {
      $scope.fr = facilityRepositoryResource.submit(
        {
          data: $scope.form.formatForApi()
        },
        function() {
          // Clear any saved data.
          $scope.form.clearSave(true);
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
     * @type {string} 'FORM',
     *                'PREVIEW',
     *                'SUCCESS_MESSAGE',
     *                'FAILURE_MESSAGE'.
     */
    $scope.view = {
      show: 'FORM'
    };
    
    /**
     * Will hold the data return by '$scope.submit()'.
     * @type {object}
     */
    $scope.fr = {};
    
    // Initialise the form.
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
    
    // Make sure to disable '$scope.form.autosave()' if the route is changed. 
    $scope.$on('$stateChangeStart', function() {
      $interval.cancel($scope.form.isAutosaving);
    });
  }
]);