'use strict';

angular.module('afredApp').controller('AdminOrganizationsController', [
  '$scope',
  'OrganizationResource',
  function($scope,
           OrganizationResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * State class.
     */
    $scope.organizations = {
      /**
       * All form related functions/data. In this case only the page number.
       * 
       * @type {object}
       */
      form: {
        data: {
            page: null
        }
      },
      
      /**
       * Holds the data returned from `OrganizationResource`.
       * 
       * @type {Angular resource}
       */
      resource: {},
      
      /**
       * Parses the URL parameters.
       *
       * @sideeffect $scope.organizations.form.data.page Parsed page number data
       *     is attached to this.
       * 
       * @requires $scope.organizations.form.data.page
       */
      parseParams: function() {
        var page = null;
        try {
          if (isFinite(parseInt($scope._stateParams.page))) {
            page = parseInt($scope._stateParams.page);
          }
        } catch (e) {
          page = 1;
        }
        $scope.organizations.form.data.page = page;
      },
      
      /**
       * Query organization data.
       *
       * @sideffect $scope.organization.resource Data returned is attached to
       *     this.
       *
       * @requires $scope._httpError403()
       * @requires $scope.organizations.form.data.page
       * @requires OrganizationResource
       */
      query: function() {
        $scope.organizations.resource = OrganizationResource.query({
          page: $scope.organizations.form.data.page,
          itemsPerPage: 10
        }, function() {
          // Do nothing if successful.
        }, function(response) {
          $scope._httpError403(response);
        });
      }
    }; 
  }
]);
