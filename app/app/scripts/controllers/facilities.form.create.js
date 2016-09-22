'use strict';

angular.module('afredApp').controller('FacilitiesFormCreateController',
  ['$scope',
   '$timeout',
   '$interval',
   'facilityRepositoryResource',
  function($scope,
           $timeout,
           $interval,
           facilityRepositoryResource) {
    // See explanation at the bottom ($stateChangeStart) for why this is needed.
    if ($scope._state.needToReload) {
      $scope._location.reload();
    }
    
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */    
    
    /**
     * Shows the preview.
     *
     * Side effects:
     * $scope.view.show Set to 'PREVIEW'.
     * $scope.loading.preview Set to true at the start of the function and then
     *     set to false at the end.
     * $scope.facility Data returned from '$scope.form.formatForPreview()'
     *     is attached to this.
     *
     * Uses/calls/requires:
     * $scope.form.formatForPreview();
     * $timeout()
     */
    $scope.preview = function() {
      $scope.loading.preview = true;
      $scope.facility = $scope.form.formatForPreview();
      $scope.view.show = 'PREVIEW';
      // Introduce an articial delay (1s).
      $timeout(function() {
        $scope.loading.preview = false;
        scrollTo(0, 300);
      }, 1000);
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
      $scope.fr = facilityRepositoryResource.submit({
        data: $scope.form.formatForApi()
      }, function() {
        // Clear any saved data.
        $scope.form.clearSave(true);
        $scope.view.show = 'SUCCESS_MESSAGE';
      }, function() {
        $scope.view.show = 'FAILURE_MESSAGE';
      });
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
     * Loading flags.
     * 
     * @type {object}
     */
    $scope.loading = {
      preview: false // Preview form.
    };
    
    /**
     * Will hold the data return by '$scope.submit()'.
     * @type {object}
     */
    $scope.fr = {};
    
    // Initialise the form.
    $scope.form.initialise();
    
    // Start autosaving to local storage.
    $scope.form.startAutosave();
    
    // Stops the '$scope.form.startAutosave()' from autosaving if we leave
    // this state. We're also setting the 'needToReload' property of
    // '$scope._state' to true if we're going to the 'facilities.form.edit'
    // state. If we don't do a hard reload, textAngular will complain that
    // ('Editor with name "..." already exists'). This is because the parent
    // state is not reloaded if we're only switching between child states. Note
    // that the 'needToReload' property is custom code. It is not part of
    // angular ui router.
    $scope.$on('$stateChangeStart',
      function(event, toState, toParams, fromState, fromParams, options) {
        $interval.cancel($scope.form.isAutosaving);
        
        if (toState.name == 'facilities.form.edit') {
          $scope._state.needToReload = true;
        }
      }
    );
  }
]);
