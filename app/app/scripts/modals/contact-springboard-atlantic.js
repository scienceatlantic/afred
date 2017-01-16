'use strict';

/**
 * @fileoverview Angular controller for the contact Springboard Atlantic modal.
 *  
 * @see https://docs.angularjs.org/guide/controller
 * @see https://angular-ui.github.io/bootstrap/#/modal
 */

angular.module('afredApp').controller('ContactSpringboardAtlanticModalController', [
  '$scope',
  'EmailResource',
  '$uibModalInstance',
  function($scope,
           EmailResource,
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
     * $scope.resource Data returned from 'EmailResource' is stored here.
     * EmailResource
     */
    $scope.submit = function() {
      $scope.resource = EmailResource.springboardForm($scope.message,
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
     * Holds data returned from EmailResource.
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
