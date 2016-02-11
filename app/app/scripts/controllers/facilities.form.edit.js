'use strict';

angular.module('afredApp').controller('FacilitiesFormEditController',
  ['$scope',
   'facilityRepositoryResource',
  function($scope,
           facilityRepositoryResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */    
    
    $scope.preview = function() {
      $scope.facility = $scope.form.formatForPreview();
      $scope.view.show = 'PREVIEW';
    };
    
    $scope.submit = function() {
      facilityRepositoryResource.submitEdit({ facilityRepositoryId: frId },
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
    var frId = $scope._stateParams.facilityRepositoryId;
    var token = $scope._stateParams.token;
    
    $scope.form.initialise(true);
    $scope.form.getFacilityRepositoryData(frId, token);
    
    /**
     * Controls what is shown to the user.
     * @type {string} 'FORM', 'PREVIEW', 'SUCCESS_MESSAGE', 'FAILURE_MESSAGE'.
     */
    $scope.view = {
      show: 'FORM'
    };
  }
]);