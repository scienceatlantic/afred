'use strict';

angular.module('afredApp').controller('ContactController',
  ['$scope',
   'emailResource',
  function($scope,
           emailResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    $scope.submit = function(form) {
      $scope.resource = emailResource.contactForm($scope.contact, function() {
        // Reset the form and data.
        form.$setPristine();
        $scope.contact = {};
        
        // Show success message.
        $scope.view.show = 'SUCCESS';
      }, function() {
        $scope.view.show = 'FAILURE';
      });
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
    $scope.show = {
      /**
       * Controls what is displayed.
       *
       * @type {string} 'CONTACT_FORM', 'SUCCESS', 'FAILURE'.
       */
      show: 'CONTACT_FORM'
    };
  }
]);
