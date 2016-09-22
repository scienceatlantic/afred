'use strict';

angular.module('afredApp').controller('FacilitiesFormEditController',
  ['$scope',
   '$timeout',
   'facilityRepositoryResource',
  function($scope,
           $timeout,
           facilityRepositoryResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */    
    // See explanation in 'facilities.form.create.js'.
    if ($scope._state.needToReload) {
      $scope._location.reload();
    }
    
    /**
     * Displays the preview.
     *
     * Side effects:
     * $scope.facility Data returned from '$scope.form.formatForPreview()'
     *     is attached to this.
     * $scope.loading.preview Set to true at the start of the function and then
     *     set to false at the end.
     * $scope.view.show Set to 'PREVIEW'.
     * 
     * Uses/calls/requires:
     * $scope.form.formatForPreview()
     * $timeout()
     */
    $scope.preview = function() {
      $scope.loading.preview = true;
      $scope.facility = $scope.form.formatForPreview();
      $scope.view.show = 'PREVIEW';
      // Introduce an artificial delay (1s).
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
     * Submits the data to the API.
     *
     * Side effects:
     * $scope.fr Promise is attached to this.
     * $scope.view.show Is set to 'SUCCESS_MESSAGE' if the operation is
     *     successful, otherwise it is set to 'FAILURE_MESSAGE'.
     *
     * Uses/calls/requires:
     * $scope._stateParams.facilityRepositoryId
     * $scope._stateParams.token
     * $scope.form.formatForApi()
     *
     */
    $scope.submit = function() {
      $scope.fr = facilityRepositoryResource.submitEdit({
        facilityRepositoryId: $scope._stateParams.facilityRepositoryId,
        token: $scope._stateParams.token
      }, {
        data: $scope.form.formatForApi()
      }, function() {
        $scope.view.show = 'SUCCESS_MESSAGE';
      }, function() {
        $scope.view.show = 'FAILURE_MESSAGE';
      });
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */    
    // Initialise the form. True is passed because we're in edit mode.
    $scope.form.initialise(true);
    
    // Get the facility data to edit.
    if ($scope._stateParams.facilityRepositoryId && $scope._stateParams.token) {
      $scope.form.getFacilityRepositoryData(
        $scope._stateParams.facilityRepositoryId,
        $scope._stateParams.token
      );
    } else {
      $scope._httpError('403');
    }
    
    // Holds the promised returned from '$scope.submit()'.
    $scope.fr = null;
    
    /**
     * Controls what is shown to the user.
     * @type {string} 'FORM',
     *                'PREVIEW',
     *                'SUCCESS_MESSAGE',
     *                'FAILURE_MESSAGE',
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
      preview: false // Preview.
    };
    
    // See explanation in 'facilities.form.create.js'.
    $scope.$on('$stateChangeStart',
      function(event, toState, toParams, fromState, fromParams, options) {       
        if (toState.name == 'facilities.form.create') {
          $scope._state.needToReload = true;
        }
      }
    );
  }
]);