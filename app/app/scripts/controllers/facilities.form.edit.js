'use strict';

angular.module('afredApp').controller('FacilitiesFormEditController',
  ['$scope',
   'facilityRepositoryResource',
  function($scope,
           facilityRepositoryResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */    
    
    /**
     * Displays the preview.
     *
     * Side effects:
     * $scope.facility Data returned from '$scope.form.formatForPreview()'
     *     is attached to this.
     * $scope.view.show Set to 'PREVIEW'.
     * 
     * Uses/calls/requires:
     * $scope.form.formatForPreview()
     */
    $scope.preview = function() {
      $scope.facility = $scope.form.formatForPreview();
      $scope.view.show = 'PREVIEW';
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
      $scope.fr = facilityRepositoryResource.submitEdit(
        {
          facilityRepositoryId: $scope._stateParams.facilityRepositoryId,
          token: $scope._stateParams.token
        },
        {
          data: $scope.form.formatForApi()
        },
        function() {
          $scope.view.show = 'SUCCESS_MESSAGE';
        },
        function() {
          $scope.view.show = 'FAILURE_MESSAGE';
        }
      );
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    // Initialise the form. True is passed because we're in edit mode.
    $scope.form.initialise(true);
    
    // Get the facility data. 
    $scope.form.getFacilityRepositoryData(
      $scope._stateParams.facilityRepositoryId,
      $scope._stateParams.token,
      // This is the failure callback.
      function() {
        $scope.view.show = 'INVALID_TOKEN_MESSAGE';
      }
    );
    
    // Holds the promised returned from '$scope.submit()'.
    $scope.fr = null;
    
    /**
     * Controls what is shown to the user.
     * @type {string} 'FORM',
     *                'PREVIEW',
     *                'SUCCESS_MESSAGE',
     *                'FAILURE_MESSAGE',
     *                'INVALID_TOKEN_MESSAGE'.
     */
    $scope.view = {
      show: 'FORM'
    };
  }
]);