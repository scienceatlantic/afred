'use strict';

angular.module('afredApp').controller('ContactSpringboardAtlanticModalController', [
  '$scope',
  'emailResource',
  '$uibModalInstance',
  function($scope,
           emailResource,
           $uibModalInstance) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * Submits the message to the API.
     *
     * Side effects:
     * $scope.view.show Set to 'SUCCESS' on success and 'FAILURE' on failure.
     *
     * Calls/uses/requires:
     * $scope.resource Data returned from 'emailResource' is stored here.
     * emailResource
     */
    $scope.submit = function() {
      $scope.resource = emailResource.springboardForm($scope.message,
        function() {
          $scope.view.show = 'SUCCESS';  
      }, function() {
          $scope.view.show = 'FAILURE';
      });
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    /**
     * Add modal instance to scope.
     */
    $scope.modal = $uibModalInstance;
    
    /**
     * Holds data returned from emailResource.
     *
     * @type {Angular resource}
     */
    $scope.resource = null;
    
    /**
     * Holds all form data.
     *
     * @type {object}
     */
    $scope.message = {
      name: null,
      email: null,
      body: null
    };
    
    /**
     * Properties related to the view.
     *
     * @type {object}
     */
    $scope.view = {
      /**
       * Controls what is displayed to the user.
       *
       * @type {string} 'CONTACT_FORM', 'SUCCESS', 'FAILURE'.
       */
      show: 'CONTACT_FORM'
    };
  }
]);
