'use strict';

angular.module('afredApp').controller('SearchController',
  ['$scope',
   'searchResource',
   '$uibModal',
   function($scope,
            searchResource,
            $uibModal) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * Search class/object.
     */
    $scope.search = {
      /**
       * Holds the entire search query including any disciplines, sectors,
       * organizations, provinces, and the search type.
       * 
       * @type {object}
       */
      query: {
        q: null,
        type: 'equipment',
        'disciplineId[]': null,
        'sectorId[]': null,
        'organizationId[]': null,
        'provinceId[]': null
      },
      
      /**
       * Advanced search radio buttons.
       * @type {object}
       */
      advanced: {
        /**
         *
         */
        show: false,
        
        toggle: function() {
          $scope.search.advanced.show = !$scope.search.advanced.show;
        },
        
        resource: {},
        
        radios: {
          // True = all, false = select.
          provinces: true,
          organizations: true,
          disciplines: true,
          sectors: true,
          
          /**
           * Call this function every time the user switches between 'all' and
           * 'select'. It basically clears the arrays when 'all' is selected.
           *
           * Side effects:
           * $scope.search.query Object properties (the arrays) are cleared if
           *     the corresponding radio buttons are true (='All').
           *
           * Uses/requres:
           * $scope.search.advanced.radios
           */
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
        },
        
        /**
         * Reset function, clears all selections (including params).
         *
         * Side effects:
         * $scope.search.query Arrays are cleared and 'type' is set to
         *     'equipment'.
         * $scope.search.advanced.radios All radio buttons are set to true.
         */
        reset: function() {
          $scope.search.advanced.radios = {
            provinces: true,
            organizations: true,
            disciplines: true,
            sectors: true
          };
          
          $scope.search.query.type = 'equipment';
          $scope.search.query['provinceId[]'] = null;
          $scope.search.query['organizationId[]'] = null;
          $scope.search.query['disciplineId[]'] = null;
          $scope.search.query['sectorId[]'] = null;
        },
        
        get: function() {
          $scope.search.advanced.resource = searchResource.query({
            advancedSearchOptions: true
          }, function(data) {
            $scope.search.disciplines = data.disciplines;
            $scope.search.sectors = data.sectors;
            $scope.search.provinces = data.provinces;
            $scope.search.organizations = data.organizations;
          }, function() {
            // If it fails...
          });
        }  
      },
      
      /**
       * Search results.
       * 
       * @type {array}
       */
      results: [],
      
      /**
       * Holds the 'searchResource' promise.
       * 
       * @type {object}
       */
      resource: {},
      
      /**
       * Array of disciplines.
       * 
       * @type {array}
       */
      disciplines: [],
      
      /**
       * Array of sectors.
       * 
       * @type {array}
       */
      sectors: [],
      
      /**
       * Array of sectors.
       * 
       * @type {array}
       */
      organizations: [],
      
      /**
       * Array of provinces.
       * 
       * @type {array}
       */
      provinces: [],
      
      /**
       * Redirects to the search results page. The search results are shown in
       * a separate state so that the search parameters can be attached to the
       * URL.
       *
       * Side effects:
       * $scope.search.results Array is cleared.
       * 
       * Uses/requires:
       * $scope.search.query
       * $scope._state
       * 
       * @param {boolean} showAll true = show everything, false = regular
       *     search.
       */
      goToResultsPage: function(showAll) {
        // Clear the search results array since we're starting a new search.
        $scope.search.results = [];
        
        if (!showAll) {
          // Search only if the query is not empty
          if ($scope.search.query.q) {
            $scope._state.go('search.q', $scope.search.query);
          // Otherwise return to the main search page
          } else { 
            $scope._state.go('search');
          }   
        } else {
          $scope._state.go('search.all', $scope.search.query);
        }
      },
      
      /**
       * Parse the search parameters from the URL.
       *
       * Side effects:
       * $scope.search.query All properties are modified based on values
       *     retrieved from the URL.
       * 
       * Uses/requires:
       * $scope._stateParams
       * $scope.search.advanced.radios
       * $scope.search.toInt()
       */
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
        
        // Update the radio buttons. If a (or more) province, organization,
        // discipline, or sector was select, set the radio button to false
        // (ie. 'Select').
        $scope.search.advanced.radios.provinces =
          $scope.search.query['provinceId[]'].length == 0;
        
        $scope.search.advanced.radios.organizations =
          $scope.search.query['organizationId[]'].length == 0;
          
        $scope.search.advanced.radios.disciplines =
          $scope.search.query['disciplineId[]'].length == 0;
          
        $scope.search.advanced.radios.sectors =
          $scope.search.query['sectorId[]'].length == 0;
      },
      
      /**
       * Accepts an array of values and attempts to parse the values into
       * integers and stores them into a new array. Values that fail to get
       * parse are ignored.
       * @param {array} arr Array of values.
       * @return {array} Array of integers.
       */
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
      
      /**
       * Retrieves the search results.
       *
       * Side effects:
       * $scope.search.query 'page' and 'itemsPerPage' are added.
       * $scope.search.results Stores search results.
       * $scope.search.resource Stores promise returned from 'searchResource'.
       *
       * Uses/requires:
       * $scope.search.resource
       * searchResource
       *
       * @param {integer} page Page number of pagination.
       */
      getResults: function(page) {
        $scope.search.query.page = page ? page : 1;
        $scope.search.query.itemsPerPage = 10;
        
        $scope.search.resource = searchResource.query($scope.search.query,
          function(results) {
            $scope.search.results = $scope.search.results.concat(results.data);
          }
        );
      },
      
      /**
       * Instantiates a modal that allows the user to contact Springboard
       * Atlantic.
       *
       * Uses/calls/requires:
       * $uibModal
       */
      contactSpringboardAtlantic: function() {        
        var modalInstance = $uibModal.open({
          templateUrl: 'views/modals/contact-springboard-atlantic.html',
          controller: 'ContactSpringboardAtlanticModalController',
          backdrop: false
        });
      },
      
      /**
       * Retrieves an array of disciplines from the API.
       *
       * Side effects:
       * $scope.search.disciplines Array stored here.
       *
       * Uses/requires:
       * disciplineResource
       */
      getDisciplines: function() {
        $scope.search.disciplines = disciplineResource.queryNoPaginate();
      },
      
      /**
       * Retrieves an array of sectors from the API.
       *
       * Side effects:
       * $scope.search.sectors Array stored here.
       *
       * Uses/requires:
       * sectorResource
       */
      getSectors: function() {
        $scope.search.sectors = sectorResource.queryNoPaginate();   
      },
      
      /**
       * Retrieves an array of organizations from the API. 'N/A' is removed.
       *
       * Side effects:
       * $scope.search.organizations Array stored here.
       *
       * Uses/requires:
       * organizationResource
       */
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
      
      /**
       * Retrieves an array of provinces from the API. 'N/A' is removed.
       *
       * Side effects:
       * $scope.search.provinces Array stored here.
       *
       * Uses/requires:
       * provinceResource
       */
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
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    $scope.search.advanced.get();
  }
]);