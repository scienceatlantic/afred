'use strict';

/**
 * @ngdoc function
 * @name afredApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the afredApp
 */
angular.module('afredApp').controller('SearchController', ['$scope', '$state', '$modal',
  function($scope, $state, $modal) {
    $scope.search = function() {
      if ($scope.searchBar.query) {
        $state.go('search.query', {query: $scope.searchBar.query});
      }
      else {
        $state.go('search');
      }
    };
    
    $scope.contactUs = function () {
      var modalInstance = $modal.open({
        templateUrl: 'views/modals/contact-us.html',
        controller: 'ContactUsModalController'
      });
      
      modalInstance.dummy = 'dummy';
    };
    
    //Initialise
    $scope.searchBar = {
      query: null
    };
  }
]);