'use strict';

angular.module('afredApp').controller('AdminFacilitiesStateController',
  ['$scope',
  function($scope) {
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */ 
    
    $scope.facilities.parseParams();
    $scope.facilities.get();
    
    // If we're going back to the parent state from this state, clear the form
    // data. Remember that angular ui router does not re-instantiate parent
    // controllers.
    $scope.$on('$stateChangeStart',
      function(event, toState, toParams, fromState, fromParams, options) {
        if (toState.name == 'admin.facilities') {
          $scope.facilities.form.clear();
        }
      }
    );
  }
]);