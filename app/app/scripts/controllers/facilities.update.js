'use strict';

angular.module('afredApp').controller('FacilitiesUpdateController',
  ['$scope',
   'facilityResource',
   'facilityRepositoryResource',
  function($scope,
           facilityResource,
           facilityRepositoryResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * Submits the email to the API to retrieve the array of facilities.
     *
     * Side effects:
     * $scope.fr Attaches a facility repository resource (promise) to this.
     * $scope.view.show Updates the view to either 'RESULTS' or
     *     'NO_RESULTS_MESSAGE'.
     * 
     * Uses:
     * $scope.form
     * facilityRepositoyResource
     * 
     */
    $scope.submit = function() {
      // Don't run if the email field is empty.
      if ($scope.form.data.email) {
        $scope.fr = facilityResource.query({
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
     * Side effects:
     * $scope.view.show Sets to null.
     * $scope.fr Clears object.
     * $scope.facilities Resets the array.
     * #scope.pagination.page Resets to the page to 1.
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
     * Side effects:
     * $scope.ful Holds the promise returned by facilityRepositoryResource
     * 
     * Uses:
     * facilityRepositoryResource
     * 
     * @param index Array index of $scope.facilities
     * @param id Facility id.
     */
    $scope.requestToken = function(index, id) {
      $scope.ful = facilityRepositoryResource.createToken({
        facilityId: id,
        email: $scope.form.data.email
      }, function(data) {
        // Updates 
        $scope.facilities[index].editorFirstName = data.editorFirstName;
        $scope.facilities[index].editorLastName = data.editorLastName;
        $scope.facilities[index].editorEmail = data.editorEmail;
        $scope.facilities[index].status = data.status;
      });
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */ 
    /**
     * All form related info.
     * @type {object}
     */
    $scope.form = {
      data: {
        email: null,
      }
    };
    
    /**
     * Holds the facility repository resource.
     * @type {object}
     */
    $scope.fr = {};
    
    /**
     * Will hold the facility update link resource.
     * @type {object}
     */
    $scope.ful = {};
    
    /**
     * Array of facilities.
     * @type {array}
     */
    $scope.facilities = [];
    
    /**
     * Controls what is displayed to the user.
     * @type {object}
     */
    $scope.view = {
      // Valid values are: 'NO_RESULTS_MESSAGE', 'RESULTS'.
      show: null
    };
    
    /**
     * Keeps track of the current page number and items per page.
     * @type {object}
     */
    $scope.pagination = {
      page: 1,
      itemsPerPage: 5
    };
    
    /**
     * AJAX loading flags.
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