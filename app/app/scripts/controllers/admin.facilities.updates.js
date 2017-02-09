'use strict';

angular.module('afredApp').controller('AdminFacilitiesUpdatesController', [
  '$scope',
  'confirmModal',
  'infoModal',
  'RepositoryResource',
  'warningModal',
  function($scope,
           confirmModal,
           infoModal,
           RepositoryResource,
           warningModal) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * State class.
     */
    $scope.facilities.updates = {
      /**
       * Holds the promise returned from `$scope.facilities.updates.query()`.
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
          status: null, // Facility update link status.
          page: null, // Page number (pagination).
        },
        
        /**
         * Clears the form.
         *
         * @sideffect $scope.facilities.updates.form.data All properties are set
         *     to null.
         */
        clear: function() {
          $scope.facilities.updates.form.data.status = null,
          $scope.facilities.updates.form.data.page = null        
        }
      },
      
      /**
       * Go to index state.
       *
       * @sideffect $scope.facilities.updates.form.data.page See @param.
       *
       * @requires $scope._state.go()
       * @requires $scope.facilities.updates.form.data
       *
       * @param {boolean} resetPage If true, the page number is reset to 1.
       */
      index: function(resetPage) {
        if (resetPage) {
          $scope.facilities.updates.form.data.page = 1;
        }
        
        $scope._state.go('admin.facilities.updates.index',
          $scope.facilities.updates.form.data);
      },
      
      /**
       * Parses the parameters. To be used by a child state.
       *
       * @sideeffect $scope.facilities.updates.form.data.status Status is
       *     updated to match the value retrieved from the URL if it is valid.
       * @sideeffect $scope.facilities.updates.form.data.page Page number is
       *     updated to match value retrieved from the URL if it is valid.
       *
       * @requires $scope._state.go()
       * @requires $scope._stateParams
       */
      parseParams: function() {
        var status = null;
        var page = null;
        
        try {
          status = $scope._stateParams.status.toUpperCase();
        } catch(e) {
          // Do nothing.
        }
        
        try {
          page = parseInt($scope._stateParams.page);
          if (!isFinite(page)) {
            page = 1;
          }
        } catch(e) {
          page = 1;
        }
        
        switch (status) {
          case 'OPEN':
          case 'PENDING':
          case 'CLOSED':
            $scope.facilities.updates.form.data.status = status;
            $scope.facilities.updates.form.data.page = page;
            break;
          
          default:
            $scope.facilities.updates.form.data.status = null;
            $scope._state.go('admin.facilities.updates');
        }
      },
      
      /**
       * Retrieves facility repository data from the API.
       *
       * @sideffect $scope.facilities.updates.fr Promise object is attached to
       *     this.
       *
       * @requires $scope._httpError403()
       * @requires $scope.facility.form.data.page
       * @requires $scope.facility.form.data.status
       * @requires RepositoryResource
       */
      query: function() {
        $scope.facilities.updates.fr = RepositoryResource.queryTokens({
          page: $scope.facilities.updates.form.data.page,
          itemsPerPage: 5,
          status: $scope.facilities.updates.form.data.status
        }, function() {
          // Do nothing if call was successful.
        }, function(response) {
          $scope._httpError403(response);
        });
      },
      
      /**
       * Close an open token.
       *
       * @sideeffect $scope.facilities.updates.loading.selectedBtnIndex Is set
       *     equal to the `index` param. Is set to null after the AJAX operation
       *     is complete.
       * @sideeffect $scope.facilities.updates.loading.close Set to true at the
       *     start of the function and then set to false after the AJAX
       *     operation is complete.
       * @sideeffect $scope.facilities.updates.fr.data Removes the closed token
       *     from the array if the operation was successful.
       *
       * @requires confirmModal
       * @requires infoModal
       * @requires RepositoryResource
       * @requires warningModal
       *
       * @param {integer} index Index of the element in
       *     `$scope.facilities.updates.fr.data` that will be closed.
       * @param {object} token The token element from
       *     `$scope.facilities.updates.fr.data` that will be closed.
       */
      close: function(index, token) {
        var t = 'close-token';
        
        confirmModal.open(t).result.then(function() {
          $scope.facilities.updates.loading.selectedBtnIndex = index;
          $scope.facilities.updates.loading.close = true;
          RepositoryResource.updateToken({
            facilityUpdateLinkId: token.id
          }, {
            status: 'CLOSED'
          }, function() {
            infoModal.open(t + '-success').result.then(function() {
              $scope.facilities.updates.fr.data.splice(index, 1);        
              $scope.facilities.updates.loading.close = false;
              $scope.facilities.updates.loading.selectedBtnIndex = null;
            });
          }, function() {
            warningModal.open(t + '-failed').result.then(function() {
              $scope.facilities.updates.loading.close = false;
              $scope.facilities.updates.loading.selectedBtnIndex = null;
            });
          });
        });
      },
      
      /**
       * AJAX loading flags.
       *
       * @type {object}
       */
      loading: {
        close: false, // For the 'close' operation.
        selectedBtnIndex: null // Index of the selected button.
      }
    };
  }
]);
