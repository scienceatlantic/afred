'use strict';

angular.module('afredApp').controller('FacilitiesFormEditController',
  ['$scope',
   'facilityRevisionHistoryResource',
  function($scope,
           facilityRevisionHistoryResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */    
    
    $scope.preview = function() {
      $scope.facility = $scope.form.formatForPreview();
      $scope.view.show = 'preview';
    };
    
    $scope.submit = function() {
      facilityRevisionHistoryResource.submitEdit(
        {
          facilityRevisionHistoryId:
            $scope._stateParams.facilityRevisionHistoryId
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
    $scope.form.getFacility();
    
    $scope.view = {
      show: 'form'
    };
  }
]);