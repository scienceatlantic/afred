'use strict';

angular.module('afredApp').controller('ContactController',
  ['$scope',
   'EmailResource',
   'WpResource',
  function($scope,
           EmailResource,
           WpResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * Submits form data to API.
     *
     * @sideffect $scope.resource Angular resource returned from `EmailResource`
     *     is stored here.
     * @sideffect $scope.view.show Is set to 'SUCCESS' on success, or 'FAILURE'
     *     on failure.
     * 
     * @requires $scope.message
     * @requires EmailResource
     */
    $scope.submit = function() {
      $scope.resource = EmailResource.contactForm($scope.message, function() {        
        $scope.view.show = 'SUCCESS';
      }, function() {
        $scope.view.show = 'FAILURE';
      });
    };
    
    /**
     * Resets contact form.
     *
     * @sideffect $scope.message Is set to empty object.
     * @sideffect $scope.view.show Is set to 'CONTACT_FORM'.
     *
     * @param {Angular FormController} form Contact form controller.
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
     * Holds data returned from EmailResource.
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

    // Get contact page from WordPress.
    $scope.wp = WpResource.getPage($scope._env.wp.pages['contact']);
    $scope.wp.$promise.then(null, function(response) {
      $scope._httpError(response);
    });
  }
]);
