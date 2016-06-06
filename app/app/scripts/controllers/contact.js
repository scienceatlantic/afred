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
     * $scope.contact
     */
    $scope.submit = function() {
      $scope.resource = emailResource.contactForm($scope.contact, function() {        
        $scope.view.show = 'SUCCESS';
      }, function() {
        $scope.view.show = 'FAILURE';
      });
    };
    
    /**
     * Resets contact form.
     *
     * Side effects:
     * $scope.contact Is set to empty object.
     * $scope.view.show Is set to 'CONTACT_FORM'.
     *
     * @param {Angular form} form Contact form controller.
     */
    $scope.reset = function(form) {
      form.$setPristine();
      $scope.contact = {};
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
    $scope.contact = {
      name: null,
      email: null,
      subject: null,
      message: null
    };
    
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
