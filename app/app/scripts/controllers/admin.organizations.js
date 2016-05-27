'use strict';

angular.module('afredApp').controller('AdminOrganizationsController', [
  '$scope',
  'organizationResource',
  function($scope,
           organizationResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * State class.
     */
    $scope.organizations = {
      /**
       * All form related functions/data. In this case only the page number.
       * @type {object}
       */
      form: {
        data: {
            page: null
        }
      },
      
      /**
       * Holds the data returned from 'organizationResource'.
       * @type {resource}
       */
      resource: {},
      
      /**
       * Parses the URL parameters.
       *
       * Side effects:
       * $scope.organizations.form.data.page Parsed page number data is attached
       *     to this.
       * 
       * Uses/calls/requires:
       * $scope.organizations.form.data.page
       * 
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
       * Side effects:
       * $scope.organization.resource Data returned is attached to this.
       *
       * Uses/calls/requires:
       * organizationResource
       * $scope.organizations.form.data.page
       */
      query: function() {
        $scope.organizations.resource = organizationResource.query({
          page: $scope.organizations.form.data.page,
          itemsPerPage: 10
        });
      }
    }; 
  }
]);
