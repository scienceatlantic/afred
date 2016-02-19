'use strict';

angular.module('afredApp').controller('FacilitiesUpdateController',
  ['$scope',
   'facilityResource',
   'facilityRepositoryResource',
  function($scope,
           facilityResource,
           facilityRepositoryResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    $scope.submit = function(page) {
      // Don't run if the email field is empty.
      if ($scope.form.email) {
        page = page ? page : 1;
  
        $scope.facilities = facilityRepositoryResource.queryTokens(
          {
            email: $scope.form.email,
            page: page,
            itemsPerPage: 5
          }, function() {
            if (!$scope.facilities.total) {
              $scope.view.show = 'NO_RESULTS_MESSAGE';
            } else {
              $scope.view.show = null;
            }
          }
        );
      }
    };
    
    $scope.reset = function() {
      $scope.view.show = null;
      $scope.facilities = {};
    };
    
    $scope.requestToken = function(index, id) {
      $scope.ful = facilityRepositoryResource.generateToken({
        facilityId: id,
        email: $scope.form.email
      }, function(data) {
        $scope.facilities.data[index].editorFirstName = data.editorFirstName;
        $scope.facilities.data[index].editorLastName = data.editorLastName;
        $scope.facilities.data[index].editorEmail = data.editorEmail;
        $scope.facilities.data[index].status = data.status;
      });
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */ 
    $scope.form = {
      email: null,
      buttons: {
        selectedIndex: null
      }
    };
    
    $scope.ful = {};
    
    $scope.facilities = null;
    
    $scope.view = {
      show: null // 'NO_RESULTS_MESSAGE'
    };
  }
]);