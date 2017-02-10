'use strict';

angular.module('afredApp').controller('MainController',
  ['$interval',
   '$scope',
   '$timeout',
   'SettingResource',
  function($interval,
           $scope,
           $timeout,
           SettingResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * Controller class.
     */
    $scope.main = {
      /**
       * Navbar releated properties/methods.
       */
      navbar: {
        /**
         * The navbar should call this function (onload and only once) to make
         * sure that on smaller screens expanding the hamburger menu and
         * clicking on an item will collapse the menu after the user is
         * redirected.
         *
         * The navbar has to call this function because the DOM (ng-include)
         * might not have loaded in time for this event to attach itself to the
         * right element.
         * 
         * Credit: https://github.com/twbs/bootstrap/issues/12852#issuecomment-36163121
         */
        toggle: function() {
          angular.element('.navbar-nav li a').click(function(e) {
            // Do not hide the navbar if the element that was clicked is a 
            // dropdown element that contains more nav options.
            var eClass = e.target.className;
            if (eClass !== 'caret' && eClass !== 'dropdown-toggle') {
              angular.element('.navbar-collapse').collapse('hide');
            }
          });
        }
      },

      /**
       * Website notice related properties/methods.
       */
      notice: {
        /**
         * Holds value returned from `$scope.main.notice.get()`.
         * 
         * @type {Angular resource}
         */
        resource: {},

        /**
         * Alias of `$scope.notice.main.resource.websiteNotice.value`.
         * 
         * @type {string}
         */
        content: null,

        /**
         * Show/Hide website notice flag.
         * 
         * @type {boolean}
         */
        dismissed: false,

        /**
         * Get website notice.
         * 
         * @sideffect $scope.main.notice.resource Value returned from 
         *     `SettingResource` is attached here.
         */
        get: function() {
          // Alias.
          var notice = $scope.main.notice;
          
          notice.resource = SettingResource.query({ name: 'websiteNotice'});
          notice.resource.$promise.then(function(data) {
            notice.content = data.websiteNotice.value;
          });
        }
      },
      
      /**
       * Footer related properties/methods.
       */
      footer: {
        /**
         * Current year for the copyright message in the footer.
         *
         * @type integer
         */
        copyrightEndYear: new Date().getFullYear()     
      }
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    $scope.main.notice.get();
  }
]);
