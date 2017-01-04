'use strict';

angular.module('afredApp').controller('FacilitiesFormEditController',
  ['$scope',
   '$timeout',
   'facilityRepositoryResource',
  function($scope,
           $timeout,
           facilityRepositoryResource) {
    // See explanation in 'facilities.form.create.js'.
    if ($scope._state.needToReload) {
      $scope._location.reload();
    }

    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * Displays the preview.
     *
     * @sideeffect $scope.facility Data returned from 
     *     `$scope.form.formatForPreview()` is stored here.
     * @sideeffect $scope.loading.preview Set to true at the start of the 
     *     function and then set to false at the end.
     * @sideeffect $scope.view.show Set to 'PREVIEW'.
     * 
     * @requires $scope.form.formatForPreview()
     * @requires $timeout()
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
     * @sideeffect $scope.view.show Set to 'FORM'.
     */
    $scope.goBack = function() {
      $scope.view.show = 'FORM';
      scrollTo(0, 0);
    };
    
    /**
     * Submits the data to the API.
     *
     * @sideeffect $scope.fr Promise is attached to this.
     * @sideeffect $scope.view.show Is set to 'SUCCESS_MESSAGE' if the operation
     *     is successful, otherwise it is set to 'FAILURE_MESSAGE'.
     *
     * @requires $scope._stateParams.facilityRepositoryId
     * @requires $scope._stateParams.token
     * @requires $scope.form.formatForApi()
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

    // Get the facility data to edit.
    if ($scope._stateParams.facilityRepositoryId && $scope._stateParams.token) {
      $scope.form.initialise($scope._stateParams.facilityRepositoryId, 
        $scope._stateParams.token).then(function() {
        $scope.loading.form = false;
      });
    } else {
      $scope._httpError('403');
    }
    
    // Holds the promised returned from '$scope.submit()'.
    $scope.fr = null;
    
    /**
     * Controls what is shown to the user.
     * 
     * @type {object} 
     */
    $scope.view = {
      show: 'FORM' //'FORM', 'PREVIEW', 'SUCCESS_MESSAGE', 'FAILURE_MESSAGE'
    };

    /**
     * Loading flags.
     * 
     * @type {object}
     */
    $scope.loading = {
      form: true,
      preview: false
    };
    
    // See explanation in 'facilities.form.create.js'.
    $scope.$on('$stateChangeStart', function(event, toState) {       
      if (toState.name === 'facilities.form.create') {
        $scope._state.needToReload = true;
      }
    });
  }
]);