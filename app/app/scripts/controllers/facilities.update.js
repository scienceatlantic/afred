'use strict';

angular.module('afredApp').controller('FacilitiesUpdateController',
  ['$scope',
   'confirmModal',
   'MiscResource',
   'infoModal',
   'RepositoryResource',
   'warningModal',
  function($scope,
           confirmModal,
           MiscResource,
           infoModal,
           RepositoryResource,
           warningModal) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * Submits the email to the API to retrieve the array of facilities.
     *
     * @sideffect $scope.fr Attaches a facility repository resource (promise) to
     *     this.
     * @sideffect $scope.view.show Updates the view to either 'RESULTS' or
     *     'NO_RESULTS_MESSAGE'.
     *
     * @requires $scope.form
     * @requires MiscResource
     * @requires RepositoryResource
     */
    $scope.submit = function() {
      // Don't run if the email field is empty.
      if ($scope.form.data.email) {
        $scope.fr = MiscResource.get({
          item: 'facilitiesByEmailWithUnclosedUpdateRequests',
          email: $scope.form.data.email,
          page: $scope.pagination.page,
          itemsPerPage: $scope.pagination.itemsPerPage
        }, function() {
          // If matches were found.
          if ($scope.fr.total) { 
            $scope.facilities = $scope.facilities.concat($scope.fr.data);
            $scope.view.show = 'RESULTS';
          // If nothing was found.
          } else {
            $scope.view.show = 'NO_RESULTS_MESSAGE';
          }
        });
      }
    };
    
    /**
     * Reset.
     * 
     * @sideeffect $scope.facilities Resets the array.
     * @sideeffect $scope.fr Clears object.
     * @sideeffect $scope.pagination.page Resets to the page to 1.
     * @sideeffect $scope.view.show Sets to null.
     */
    $scope.reset = function() {
      $scope.view.show = null;
      $scope.fr = {};
      $scope.facilities = [];
      $scope.pagination.page = 1;
    };
    
    /**
     * Generates the token.
     *
     * @sideffect $scope.ful Holds the promise returned by `RepositoryResource`
     * 
     * @requires $scope.facilities
     * @requires $scope.form.data.email
     * @requires $scope.ful
     * @requires confirmModal
     * @requires infoModal
     * @requires RepositoryResource
     * @requires warningModal
     * 
     * @param index Array index of $scope.facilities
     * @param id Facility id.
     */
    $scope.requestToken = function(index, id) {
      var t = 'create-update-facility-token';

      confirmModal.open(t).result.then(function() {
        $scope.ful = RepositoryResource.createToken({
          facilityId: id,
          email: $scope.form.data.email
        }, function(data) {
          // Updates 
          $scope.facilities[index].editorFirstName = data.editorFirstName;
          $scope.facilities[index].editorLastName = data.editorLastName;
          $scope.facilities[index].editorEmail = data.editorEmail;
          $scope.facilities[index].status = data.status;
          infoModal.open(t + '-success');
        }, function() {
          warningModal.open(t + '-failed');
        });
      }, function() {
        // Do nothing if user hits cancel.
      });
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */ 
    /**
     * All form related info.
     * 
     * @type {object}
     */
    $scope.form = {
      data: {
        email: null,
      }
    };
    
    /**
     * Holds the facility repository resource.
     * 
     * @type {object}
     */
    $scope.fr = {};
    
    /**
     * Will hold the facility update link resource.
     * 
     * @type {object}
     */
    $scope.ful = {};
    
    /**
     * Array of facilities.
     * 
     * @type {array}
     */
    $scope.facilities = [];
    
    /**
     * Controls what is displayed to the user.
     * 
     * @type {object}
     */
    $scope.view = {
      // Valid values are: 'NO_RESULTS_MESSAGE', 'RESULTS'.
      show: null
    };
    
    /**
     * Keeps track of the current page number and items per page.
     * 
     * @type {object}
     */
    $scope.pagination = {
      page: 1,
      itemsPerPage: 5
    };
    
    /**
     * AJAX loading flags.
     * 
     * @type {object}
     */
    $scope.loading = {
      // Keeps track of the 'Email token' button that was selected.
      buttons: {
        selectedIndex: null
      }
    };
  }
]);
