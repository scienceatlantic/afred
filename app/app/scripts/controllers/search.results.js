'use strict';

angular.module('afredApp').controller('SearchResultsController', ['$scope', '$stateParams', 'equipmentResource', 'templateMode',
  function($scope, $stateParams, equipmentResource, templateMode) {
    $scope.getSearchResults = function(query) {
      $scope.results.equipment = equipmentResource.query({query: query});
      /*
      $scope.results.equipment = [
        {
          name: 'Environmental chamber and gas blender for instrument benchmarking',
          facility: 'Dalhousie University',
          purpose: 'Automated system for benchmarking of gas concentration measurement instruments, from -70 to 200, and concentrations from ppb to 100%. Automated sequencing is available for high repeatability.',
          province: 'Nova Scotia'
        },
        {
          name: 'Cavity Ringdown Spectroscopy (CRDS)',
          province: 'Prince Edward Island',
          facility: 'Dalhousie University',
          purpose: 'Carbon isotopic composition and ppb concentrations of CO2, CH4. Mobile and accessible for drive-around surveys.'
        }
      ];*/
    };
    
    //Initialise
    $scope.results = {
      equipment: []
    };
    $scope.searchBar.query = (templateMode.query ? $stateParams.query : null); 
    $scope.getSearchResults($scope.searchBar.query);
  }
]);