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
    /**
     * Performs a search request
     */
    $scope.search = function() {
      //Search only if the query is not empty
      if ($scope.searchBar.query) {
        $state.go('search.query', {query: $scope.searchBar.query});
      }
      //Otherwise return to the main search page
      else {
        $state.go('search');
      }
    };
    
    /**
     * Instantiates a modal that allows the user to send
     * a message
     */
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