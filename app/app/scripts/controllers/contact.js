'use strict';

angular.module('afredApp').controller('ContactController',
  ['$scope',
   'emailResource',
  function($scope,
           emailResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * Submits form data to API.
     *
     * Side effects:
     * $scope.resource Angular resource returned from 'emailResource' is stored
     *     here.
     * $scope.view.show Is set to 'SUCCESS' on success, or 'FAILURE' on failure.
     * 
     * Calls/uses/requires:
     * emailResource
     * $scope.message
     */
    $scope.submit = function() {
      $scope.resource = emailResource.contactForm($scope.message, function() {        
        $scope.view.show = 'SUCCESS';
      }, function() {
        $scope.view.show = 'FAILURE';
      });
    };
    
    /**
     * Resets contact form.
     *
     * Side effects:
     * $scope.message Is set to empty object.
     * $scope.view.show Is set to 'CONTACT_FORM'.
     *
     * @param {Angular form} form Contact form controller.
     */
    $scope.reset = function(form) {
      form.$setPristine();
      $scope.message = {};
      $scope.view.show = 'CONTACT_FORM';
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    /**
     * Holds all contact form data.
     * 
     * @type {object}
     */
    $scope.message = {
      name: null,
      email: null,
      subject: null,
      message: null
    };
    
    /**
     * Holds data returned from emailResource.
     *
     * @type {Angular resource}
     */
    $scope.resource = null;
    
    /**
     * Code related to the HTML view.
     */
    $scope.view = {
      /**
       * Controls what is displayed.
       *
       * @type {string} 'CONTACT_FORM', 'SUCCESS', 'FAILURE'.
       */
      show: 'CONTACT_FORM'
    };
  }
]);
