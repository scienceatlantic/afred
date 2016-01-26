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
      $scope.view.show = 'preview';
    };
    
    $scope.submit = function() {
      facilityRepositoryResource.submitEdit(
        {
          facilityRepositoryId:
            $scope._stateParams.facilityRepositoryId
        },
        {
          data: $scope.form.formatForApi()
        },
        function() {
          console.log('yay!');
        },
        function() {
          console.log('aww...');
        }
      );
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    $scope.form.initialise();
    $scope.form.getFacilityRepositoryData();
    
    $scope.view = {
      show: 'form'
    };
  }
]);