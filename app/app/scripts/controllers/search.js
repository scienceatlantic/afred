'use strict';

angular.module('afredApp').controller('SearchController',
  ['$scope',
   'searchResource',
   '$uibModal',
   function($scope,
            searchResource,
            $uibModal) {
    /* ---------------------------------------------------------------------
     * Function/Object declarations.
     * --------------------------------------------------------------------- */
    
    /**
     * Holds all search state related code.
     */
    $scope.search = {
      /**
       * Holds all search query data.
       * 
       * @type {Object} query - All query related data.
       * @type {?string} query.q - Search query.
       * @type {!string} query.type - Search type. Valid values are 'equipment'
       *     or 'facility'.
       * @type {number} query.page
       * @type {number} query.itemsPerPage
       * @type {Array.<number>} disciplineId[]
       * @type {Array.<number>} organizationId[]
       * @type {Array.<number>} provinceId[]
       * @type {Array.<number>} sectorId[]
       */
      query: {
        q: null,
        type: 'equipment',
        page: 1,
        itemsPerPage: 10,
        'disciplineId[]': [],
        'organizationId[]': [],
        'provinceId[]': [],
        'sectorId[]': []
      },

      /**
       * Flag to signify if this is the first time we're running the search.
       * 
       * @type {boolean}
       */
      firstTime: true,

      /**
       * Flag to signify if the query has changed since the last time search was
       * run.
       * 
       * @type {boolean}
       */
      hasQueryChanged: false,

      /**
       * Flag to signify if the filters have changed since the last time search
       * was run.
       * 
       * @type {boolean}
       */
      hasFiltersChanged: false,
      
      /**
       * Filters subobject.
       */
      filters: {
        /**
         * Show/Hide filter panel flag.
         * 
         * @type {!boolean}
         */
        show: false,
        
        /**
         * Toggle (show (true)/hide (false)) filter panel.
         * 
         * @sideeffect $scope.search.filters.show - If true, set to false and
         *     vice versa.
         */
        toggle: function() {
          $scope.search.filters.show = !$scope.search.filters.show;
        },
        
        /**
         * Holds resource returned from `$scope.search.filters.get()`.
         * 
         * @type {Angular $resource}
         */
        resource: {},

        /**
         * All or selected disciplines radio button flag.
         * 
         * @type {boolean}
         */
        allDisciplines: true,

        /**
         * All or selected organizations radio button flag.
         * 
         * @type {boolean}
         */
        allOrganizations: true,

        /**
         * All or selected provinces radio button flag.
         * 
         * @type {boolean}
         */
        allProvinces: true,

        /**
         * All or selected sectors radio button flag.
         * 
         * @type {boolean}
         */
        allSectors: true,

        /**
         * Holds discipline data returned from `$scope.search.filters.get()`.
         * 
         * @type {Array}
         */
        disciplines: [],

        /**
         * Holds organization data returned from `$scope.search.filters.get()`.
         * 
         * @type {Array}
         */
        organizations: [],

        /**
         * Holds provinces data returned from `$scope.search.filters.get()`.
         * 
         * @type {Array}
         */
        provinces: [],
        
        /**
         * Holds sectors data returned from `$scope.search.filters.get()`.
         * 
         * @type {Array}
         */
        sectors: [],
        
        /**
         * If the user selects 'All' for any one of the filter radio buttons, 
         * clear corresponding array.
         * 
         * @sideeffect $scope.search.query['disciplineId[]'] - Array cleared if 
         *     `$scope.search.filters.radios.AllDisciplines` is true.
         * @sideeffect $scope.search.query['sectorId[]'] - Array cleared if 
         *     `$scope.search.filters.radios.AllSectors` is true.
         * @sideeffect $scope.search.query['organizationId[]'] - Array cleared 
         *     if `$scope.search.filters.radios.allOrganizations` is true.
         * @sideeffect $scope.search.query['provinceId[]'] - Array cleared if 
         *     `$scope.search.filters.radios.AllProvinces` is true.
         * @requires $scope.search.filters.allDisciplines
         * @requires $scope.search.filters.allOrganizations
         * @requires $scope.search.filters.allProvinces
         * @requires $scope.search.filters.allSectors
         */
        update: function() {
          if ($scope.search.filters.allProvinces) {
            $scope.search.query['provinceId[]'] = [];
          }
          if ($scope.search.filters.allOrganizations) {
            $scope.search.query['organizationId[]'] = [];
          }          
          if ($scope.search.filters.allDisciplines) {
            $scope.search.query['disciplineId[]'] = [];
          }          
          if ($scope.search.filters.allSectors) {
            $scope.search.query['sectorId[]'] = [];
          }
        },
        
        /**
         * Clears all filters.
         *
         * @sideeffect $scope.search.filters.allProvinces Set to true.
         * @sideeffect $scope.search.filters.allOrganizations Set to true.
         * @sideeffect $scope.search.filters.allDisciplines Set to true.
         * @sideeffect $scope.search.filters.allSectors Set to true.
         * @sideeffect $scope.search.query.type Set to 'equipment'
         * @sideeffect $scope.search.query['disciplineId[]'] Array cleared.
         * @sideeffect $scope.search.query['sectorId[]'] Array cleared.
         * @sideeffect $scope.search.query['organizationId[]'] Array cleared.
         * @sideeffect $scope.search.query['provinceId[]'] Array cleared.
         */
        reset: function() {
          $scope.search.query.type = 'equipment';
          $scope.search.filters.allDisciplines = true;
          $scope.search.filters.allOrganizations = true;
          $scope.search.filters.allProvinces = true;
          $scope.search.filters.allSectors = true;
          $scope.search.query['provinceId[]'] = [];
          $scope.search.query['organizationId[]'] = [];
          $scope.search.query['disciplineId[]'] = [];
          $scope.search.query['sectorId[]'] = [];
        },
        
        /**
         * Gets all filter related data.
         * 
         * @sideeffect $scope.search.filters.disciplines Discipline data (array)
         *     is assigned to this.
         * @sideeffect $scope.search.filters.organizations Organization data 
         *     (array) is assigned to this.
         * @sideeffect $scope.search.filters.provinces Province data (array)
         *     is assigned to this.
         * @sideeffect $scope.search.filters.sectors Sector data (array) is
         *     assigned to this.
         * @requires $scope._httpError() - Called if the AJAX request fails.
         * @requires searchResource - To query the API.
         */
        get: function() {
          $scope.search.filters.resource = searchResource.query({
            advancedSearchOptions: true
          }, function(data) {
            $scope.search.filters.disciplines = data.disciplines;
            $scope.search.filters.organizations = data.organizations;
            $scope.search.filters.provinces = data.provinces;
            $scope.search.filters.sectors = data.sectors;
          }, function(response) {
            $scope._httpError(response);
          });
        }  
      },
      
      /**
       * Search results stored here.
       * 
       * @type {Array}
       */
      results: [],
      
      /**
       * Holds Angular $resource returned from `$scope.search.get()`.
       * 
       * @type {Angular $resource}
       */
      resource: {},
      
      /**
       * Redirects to the search results page. The search results are shown in
       * a separate state so that the search parameters can be attached to the
       * URL.
       *
       * @requires $scope._state.go()
       * @requires $scope.search.query
       * @param {boolean} showAll - If true, redirect to search.all state, if
       *     false, redirect to search.q state.
       */
      index: function(showAll) {        
        if (!showAll) {
          // Search only if the query is not empty, otherwise return to parent
          // state.
          if ($scope.search.query.q) {
            $scope._state.go('search.q', $scope.search.query);
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
       * @sideeffect $scope.search.query.q - Query grabbed from URL.
       * @sideeffect $scope.search.query.type - Type grabbed from URL. If not
       *     either 'equipment' or 'facility', set to 'equipment'.
       * @sideeffect $scope.search.query['disciplinesId[]'] - Valid values are
       *     inserted into array.
       * @sideeffect $scope.search.query['organizationsId[]'] - Valid values
       *     are inserted into array.
       * @sideeffect $scope.search.query['provincesId[]'] - Valid values are
       *     inserted into array.
       * @sideeffect $scope.search.query['sectorsId[]'] - Valid values are
       *     inserted into array.
       * @requires $scope._state.is()
       * @requires $scope._stateParams.q
       * @requires $scope._stateParams.type - If found.
       * @requires $scope._stateParams['disciplineId[]'] - If found.
       * @requires $scope._stateParams['organizationId[]'] - If found.
       * @requires $scope._stateParams['provinceId[]'] - If found.
       * @requires $scope._stateParams['sectorId[]'] - If found.
       * @requires $scope.search.filters.allDisciplines
       * @requires $scope.search.filters.allOrganizations
       * @requires $scope.search.filters.allProvinces
       * @requires $scope.search.filters.allSectors
       * @requires $scope.search.toInt()
       */
      parseParams: function() {
        // Aliases to shorten code.
        var s = $scope.search;
        var p = $scope._stateParams;
        var f = $scope.search.filters;
        
        s.query.q = $scope._state.is('search.q') ? p.q : null;
        s.query.type = p.type == 'facility' ? 'facility' : 'equipment';
        s.query['provinceId[]'] = s.toInt(p['provinceId[]']);
        s.query['organizationId[]'] = s.toInt(p['organizationId[]']);
        s.query['disciplineId[]'] = s.toInt(p['disciplineId[]']);
        s.query['sectorId[]'] = s.toInt(p['sectorId[]']);
        
        // Update the radio buttons. If one or more disciplines, organizations,
        // provinces, or sectors were selected, set the corresponding flags to 
        // false.
        f.allProvinces = s.query['provinceId[]'].length == 0;
        f.allOrganizations = s.query['organizationId[]'].length == 0;
        f.allDisciplines = s.query['disciplineId[]'].length == 0;
        f.allSectors = s.query['sectorId[]'].length == 0;
      },
      
      /**
       * Accepts an array of values and attempts to parse the values into
       * integers. Values that are successfully parsed are added into an array
       * that is returned after all values have been parsed.
       * 
       * @param {Array} arr Array of values to parse.
       * @return {Array.<number>}
       */
      toInt: function(arr) {
        var values = [];
        angular.forEach(arr, function(v) {
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
       * @sideeffect $scope.search.query.page - Set to 1 if `page` param not
       *     provided.
       * @sideeffect $scope.search.hasQueryChanged Set to false after query has
       *     completed.
       * @sideeffect $scope.search.hasFiltersChanged Set to false after query
       *     has completed.
       * @sideeffect $scope.search.resource - Angular $resource returned from
       *     `searchResource` is assigned to this.
       * @sideeffect $scope.search.results - Results returned after querying the
       *     API are concatenated into this array.
       * @requires $scope._httpError() Called if the query fails.
       * @requires searchResource
       * @param {integer=1} page
       */
      get: function(page) {
        $scope.search.query.page = page ? page : 1; // Set default.

        $scope.search.resource = searchResource.query($scope.search.query,
          function(results) {
            $scope.search.results = $scope.search.results.concat(results.data);
            $scope.search.hasQueryChanged = false;
            $scope.search.hasFiltersChanged = false;
          }, function (response) {
            $scope._httpError(response);
          }
        );
      },
      
      /**
       * Instantiates a modal that allows the user to contact Springboard
       * Atlantic.
       *
       * @requires $uibModal
       */
      contactSpringboardAtlantic: function() {        
        var modalInstance = $uibModal.open({
          templateUrl: 'views/modals/contact-springboard-atlantic.html',
          controller: 'ContactSpringboardAtlanticModalController',
          backdrop: false
        });
      }
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    // Get filters.
    $scope.search.filters.get();

    // Ensures that the search results are cleared every time this state is
    // loaded and every time a new search is performed.
    $scope.$on('$stateChangeStart', function() {
      $scope.search.results = [];
    });

    // Watch the `$scope.search.query` object for changes so that we can update
    // the HTML to notfy the user to update their search results.
    $scope.$watch('search.query', function(newQuery, oldQuery) {
      // Don't update the flags if it's the first time the search is being run.
      if (!$scope.search.firstTime) {
        // Before updating the flags, check to make sure that the user has made
        // changes that will result in a different set of results (i.e. by 
        // checking the URLs). Skip this check if we're on the 'search' state.
        // If the URL stays the same, update both flags to false.
        if ($scope._state.current.name != 'search') {
          var oldUrl = $scope._state.href('search.q', $scope._stateParams);
          var newUrl = $scope._state.href('search.q', newQuery);
          if (newUrl == oldUrl) {
            $scope.search.hasQueryChanged = false;
            $scope.search.hasFiltersChanged = false;
            return;
          }
        }
        
        // Determine if either the query or the filters have changed.
        if (newQuery.q == oldQuery.q) {
          $scope.search.hasFiltersChanged = true;
        } else {
          $scope.search.hasQueryChanged = true;
        }
      } else {
        $scope.search.firstTime = false;
      }
    }, true);
  }
]);
