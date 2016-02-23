'use strict';

angular.module('afredApp').controller('SearchController',
  ['$scope',
   'provinceResource',
   'organizationResource',
   'disciplineResource',
   'sectorResource',
   'searchResource',
   '$uibModal',
   function($scope,
            provinceResource,
            organizationResource,
            disciplineResource,
            sectorResource,
            searchResource,
            $uibModal) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    $scope.search = {
      query: {
        q: null,
        'disciplineId[]': null,
        'sectorId[]': null,
        'organizationId[]': null,
        'provinceId[]': null,
        type: 'facility',
      },
      
      advanced: {
        radios: {
          provinces: true,
          organizations: true,
          disciplines: true,
          sectors: true,
          
          update: function() {
            if ($scope.search.advanced.radios.provinces) {
              $scope.search.query['provinceId[]'] = [];
            }
            
            if ($scope.search.advanced.radios.organizations) {
              $scope.search.query['organizationId[]'] = [];
            }
            
            if ($scope.search.advanced.radios.disciplines) {
              $scope.search.query['disciplineId[]'] = [];
            }
            
            if ($scope.search.advanced.radios.sectors) {
              $scope.search.query['sectorId[]'] = [];
            }
          }
        }
      },
      
      results: {},
      
      disciplines: [],
      
      sectors: [],
      
      organizations: [],
      
      provinces: [],
      
      goToResultsPage: function(showAll) {
        if (!showAll) {
          // Search only if the query is not empty
          if ($scope.search.query.q) {
            $scope._state.go('search.q', $scope.search.query);
          } else { // Otherwise return to the main search page
            $scope._state.go('search');
          }   
        } else {
          $scope._state.go('search.all', $scope.search.query);
        }
    
      },
      
      parseParams: function() {        
        $scope.search.query = {
          q: $scope._stateParams.q,
          type: $scope._stateParams.type == 'facility' ?
            'facility' : 'equipment',
          'provinceId[]':
            $scope.search.toInt($scope._stateParams['provinceId[]']),
          'organizationId[]':
            $scope.search.toInt($scope._stateParams['organizationId[]']),
          'disciplineId[]':
            $scope.search.toInt($scope._stateParams['disciplineId[]']),
          'sectorId[]':
            $scope.search.toInt($scope._stateParams['sectorId[]'])
        };
        
        $scope.search.advanced.radios.provinces =
          $scope.search.query['provinceId[]'].length == 0;
        
        $scope.search.advanced.radios.organizations =
          $scope.search.query['organizationId[]'].length == 0;
          
        $scope.search.advanced.radios.disciplines =
          $scope.search.query['disciplineId[]'].length == 0;
          
        $scope.search.advanced.radios.sectors =
          $scope.search.query['sectorId[]'].length == 0;
      },
      
      toInt: function(arr) {
        var values = [];
        angular.forEach(arr, function(v, k) {
          try {
            values.push(parseInt(v));
          } catch(e) {
            // Do nothing.
          }
        });
        return values;
      },
      
      getResults: function() {
        $scope.search.results = searchResource.query($scope.search.query);
      },
      
      contactSpringboard: function() {
        var modalInstance = $uibModal.open({
          templateUrl: 'views/modals/contact-us.html',
          controller: 'ContactUsModalController'
        });
        
        modalInstance.dummy = 'dummy';        
      },
      
      getDisciplines: function() {
        $scope.search.disciplines = disciplineResource.queryNoPaginate();
      },
      
      getSectors: function() {
        $scope.search.sectors = sectorResource.queryNoPaginate();   
      },
      
      getOrganizations: function() {
        $scope.search.organizations = organizationResource.queryNoPaginate(null,
          function() {
            for (var i = 0; i < $scope.search.organizations.length; i++) {
              if ($scope.search.organizations[i].name == 'N/A') {
                $scope.search.organizations.splice(i, 1);
                break;
              }
            }
          }
        );          
      },
      
      getProvinces: function() {
        $scope.search.provinces = provinceResource.queryNoPaginate(null,
          function () {
            for (var i = 0; i < $scope.search.provinces.length; i++) {
              if ($scope.search.provinces[i].name == 'N/A') {
                $scope.search.provinces.splice(i, 1);
                break;
              }
            }
          }
        );        
      },
      
      initialise: function() {
        $scope.search.getDisciplines();
        $scope.search.getSectors();
        $scope.search.getOrganizations();
        $scope.search.getProvinces();
      }
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    $scope.search.initialise();
  }
]);