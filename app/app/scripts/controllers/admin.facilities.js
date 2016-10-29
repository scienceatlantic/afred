'use strict';

/**
 * @fileoverview Admin/Facilities page.
 * 
 * @see https://docs.angularjs.org/guide/controller
 * @see /scripts/routes.js (for route info)
 */

angular.module('afredApp').controller('AdminFacilitiesController',
  ['$scope',
   'organizationResource',
   'provinceResource',
   'disciplineResource',
   'sectorResource',
   'facilityRepositoryResource',
   '$q',
  function($scope,
           organizationResource,
           provinceResource,
           disciplineResource,
           sectorResource,
           facilityRepositoryResource,
           $q) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * State class.
     */
    $scope.facilities = {
      /**
       * Holds the promise returned from '$scope.facilities.get()'.
       * 
       * @type {promise}
       */
      fr: {},
      
      /**
       * Form related objects/functions.
       *
       * @type {object}
       */
      form: {
        /**
         * Stores all form data.
         */
        data: {
          state: null, // Facility repository state.
          page: null, // Page number (pagination).
          visibility: null
        },
        
        /**
         * Clears the form.
         *
         * Side effects:
         * $scope.facilities.form.data All properties are set to null.
         */
        clear: function() {
          $scope.facilities.form.data.state = null,
          $scope.facilities.form.data.page = null,
          $scope.facilities.form.data.visibility = null          
        }
      },
      
      /**
       * Redirects to the results page.
       *
       * Side effects:
       * $scope.facilities.form.data.page See @param.
       * $scope.facilities.form.data.visibility If 'resetPage' = true,
       *     and '$scope.facilities.form.data.state' = PUBLISHED, it is set
       *     to 1, otherwise null.
       *
       * Calls/uses/requires:
       * $scope._state.go()
       * $scope.facilities.form.data
       *
       * @param {boolean} resetPage If true, the page number is reset to 1.
       */
      index: function(resetPage) {
        if (resetPage) {
          $scope.facilities.form.data.page = 1;
          
          // If we're viewing PUBLISHED facilities, set visibility to 1. This
          // means that public facilities is the default view.
          if ($scope.facilities.form.data.state == 'PUBLISHED') {
            $scope.facilities.form.data.visibility = 1;
          } else {
            $scope.facilities.form.data.visibility = null;
          }
        }
        
        $scope._state.go('admin.facilities.index', $scope.facilities.form.data);
      },
      
      /**
       * Parses the parameters. To be used by a child state.
       *
       * Side effects:
       * $scope.facilities.form.data.state State is updated to match the value
       *     retrieved from the URL if it is valid.
       * $scope.facilities.form.data.page Page number is updated to match value
       *     retrieved from the URL if it is valid.
       * $scope.facilities.form.data.visibility If '$scope._stateParams.state'
       *     = PUBLISHED, visibility is updated to match value retrieved from
       *     the URL if it is valid. If invalid, gets set to 1. If state is not
       *     PUBLISHED, visibility is set to null.
       *
       * Calls/uses/requires:
       * $scope._state.go()
       * $scope._stateParams
       */
      parseParams: function() {
        var state = null;
        var page = null;
        var visibility = null;
        
        try {
          state = $scope._stateParams.state.toUpperCase();
        } catch(e) {
          // Do nothing.
        }
        
        try {
          page = parseInt($scope._stateParams.page);
        } catch(e) {
          page = 1;
        }
        
        try {
          visibility = parseInt($scope._stateParams.visibility) == 1 ? 1 : 0;
        } catch(e) {
          visibility = 1;
        }
        
        switch (state) {
          case 'PUBLISHED':
            $scope.facilities.form.data.visibility = visibility;
            $scope.facilities.form.data.state = state;
            $scope.facilities.form.data.page = page;
            break;
          
          case 'PENDING_APPROVAL':
          case 'REJECTED':
          case 'DELETED':
            $scope.facilities.form.data.state = state;
            $scope.facilities.form.data.page = page;
            $scope.facilities.form.data.visibility = null;
            break;
          
          default:
            $scope.facilities.form.data.state = null;
            $scope._state.go('admin.facilities');
        }
      },
      
      /**
       * Retrieves facility repository data from the API.
       *
       * Side effects:
       * $scope.facilities.fr Promise object is attached to this.
       *
       * Uses/calls/requires:
       * facilityRepositoryResource
       * $scope.facility.form.data.page
       * $scope.facility.form.data.state
       * $scope._httpError403()
       */
      query: function() {
        $scope.facilities.fr = facilityRepositoryResource.query({
          page: $scope.facilities.form.data.page,
          itemsPerPage: 10,
          state: $scope.facilities.form.data.state,
          visibility: $scope.facilities.form.data.visibility
        }, function() {
          // Do nothing if successful.
        }, function(response) {
          $scope._httpError403(response);
        });
      },

      /**
       * TODO: comments
       */
      getFromRevisionData: function(fr) {
        var facility = angular.copy(fr.data.facility);
        facility.organization = angular.copy(fr.data.organization);
        facility.contacts = angular.copy(fr.data.contacts);
        facility.equipment = angular.copy(fr.data.equipment);
        facility.state = fr.state;
        
        try {
          facility.isPublic = fr.publishedFacility.isPublic;
        } catch (e) {
          // Do nothing if it fails.
        }
              
        // Primary contact & contacts section. In the DB primary contacts and
        // regular contacts are stored in separate tables, however, when the user
        // is viewing it, it's stored in a single array (where the first element
        // is the primary contact).
        if (!fr.data.contacts || !angular.isArray(fr.data.contacts)) {
          facility.contacts = [];
          facility.contacts.push(fr.data.primaryContact);
        } else {
          facility.contacts.unshift(fr.data.primaryContact);
        }
        
        // Organization section. Check if the facility belongs to an existing
        // organization or a new organization. If it belongs to an existing
        // organization, grab the details from the API.
        if (fr.data.facility.organizationId) {
          facility.organization = organizationResource.get({
            organizationId: fr.data.facility.organizationId
          }, function() {
            // Do nothing if successful.
          }, function(response) {
            $scope._httpError(response);
          });
        }
        
        // Province section.
        facility.province = provinceResource.get({
          provinceId: fr.data.facility.provinceId
        }, function() {
          // Do nothing if successful.
        }, function(response) {
          $scope._httpError(response);
        });
        
        // Disciplines section. Grab the complete list of disciplines from the API
        // so that we can get the names (the facility repository record only
        // contains the IDs of the disciplines).
        facility.disciplines = [];
        var isDisciplineReady = $q.defer();
        var disciplines = disciplineResource.queryNoPaginate(function() {
          angular.forEach(disciplines, function(d) {
            if (fr.data.disciplines.indexOf(d.id) >= 0) {
              facility.disciplines.push(d);
            }
          });
          isDisciplineReady.resolve();
        }, function(response) {
          $scope._httpError(response); 
        });
        
        // Sectors section. (Same as disciplines).
        facility.sectors = [];
        var isSectorReady = $q.defer();
        var sectors = sectorResource.queryNoPaginate(function() {
          angular.forEach(sectors, function(s) {
            if (fr.data.sectors.indexOf(s.id) >= 0) {
              facility.sectors.push(s);
            }
          });
          isSectorReady.resolve();
        }, function(response) {
          $scope._httpError(response); 
        });

        facility.$promise = $q.all([
          facility.organization.$promise,
          facility.province.$promise,
          facility.disciplines.$promise,
          facility.sectors.$promise,
          isDisciplineReady.promise,
          isSectorReady.promise
        ]);

        facility.$promise.then(function() {
          facility.$resolved = true;
        });

        return facility;
      },

      /**
       * TODO: comments.
       */
      getRevision: function(revisionId) {
        if (isFinite(revisionId)) {
          return facilityRepositoryResource.get({
            facilityRepositoryId: revisionId
          }, function() {
            // Do nothing if successful.
          }, function(response) {
            $scope._httpError403(response);
          });        
        } else {
          $scope._httpError('404');
        }
      }
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    // Remember that angular ui router does not re-instantiate parent
    // controllers, so clear the form data if we're returning (e.g. browser
    // history) from a child state.
    $scope.$on('$stateChangeStart',
      function(event, toState, toParams, fromState, fromParams, options) {
        if (fromState.name == 'admin.facilities.index') {
          $scope.facilities.form.clear();
        }
      }
    );
  }
]);
