'use strict';

angular.module('afredApp').controller('AdminUsersController', [
  '$scope',
  'userResource',
  'roleResource',
  function($scope,
           userResource,
           roleResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * State class.
     */
    $scope.users = {
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
       * Holds the data returned from 'userResource'.
       * @type {resource}
       */
      resource: {},
      
      /**
       * Parses the URL parameters.
       *
       * Side effects:
       * $scope.users.form.data.page Parsed page number data is attached
       *     to this.
       * 
       * Uses/calls/requires:
       * $scope.users.form.data.page
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
        $scope.users.form.data.page = page;
      },
      
      /**
       * Query user data.
       *
       * Side effects:
       * $scope.user.resource Data returned is attached to this.
       *
       * Uses/calls/requires:
       * userResource
       * $scope.users.form.data.page
       * $scope._httpError403()
       */
      query: function() {
        $scope.users.resource = userResource.query({
          page: $scope.users.form.data.page,
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
