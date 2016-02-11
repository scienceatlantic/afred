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
        $scope.loading.facilities = true;
        page = page ? page : 1;
  
        $scope.facilities = facilityRepositoryResource.queryTokens(
          {
            email: $scope.form.email,
            page: page,
            itemsPerPage: 5
          }, function() {
            $scope.loading.facilities = false;
            
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
    
    $scope.requestEditToken = function(id) {
      facilityRepositoryResource.generateToken({
        facilityId: id,
        email: $scope.form.email
      }, function() {
        $scope.submit();
      });
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */ 
    $scope.form = {
      email: null
    };
    
    $scope.loading = {
      facilities: false
    };
    
    $scope.view = {
      show: null // 'NO_RESULTS_MESSAGE'
    };
  }
]);