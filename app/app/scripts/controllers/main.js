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
        copyrightEndYear: new Date().getFullYear(),
        
        /**
         * Footer slider related properties/methods.
         */
        slider: {
          
          /**
           * Current index of image to show.
           * 
           * @see 'footer.html' for the index values corresponding to each 
           *     image.
           *
           * @type float See '$scope.main.footer.slider.run()' for why this is 
           *     a float instead of an int.
           */
          currentImgIndex: 0.0,
          
          /**
           * Interval in milliseconds for '$scope.main.footer.slider.run()'.
           *
           * @type integer
           */
          interval: 2500,

          /**
           * Total number of images in the slider. 
           * 
           * Used to reset the counter.
           * 
           * @type integer
           */
          numImages: 6,
          
          /**
           * Executes the slider.
           *
           * Side effects:
           * currentImgIndex Is incremente
           */
          run: function() {
            // Alias to shorten code.
            var slider = $scope.main.footer.slider;
            
            $interval(function() {
              // Hack fix. Some browsers don't properly render the change if the
              // images are loaded one after the other immediately. There's a
              // little bit of a flicker because for a split second both images
              // are shown at the same time. So instead of incrementing the 
              // index by 1, we're going to increase it by 0.1 (i.e. no images 
              // are shown) and then use the timeout function (with a delay of
              // 100ms) to increment it by 1 and then floor it back to an int.
              slider.currentImgIndex += 0.1;
              $timeout(function() {
                slider.currentImgIndex = Math.floor(++slider.currentImgIndex);
                if (slider.currentImgIndex === slider.numImages) {
                  slider.currentImgIndex = 0;
                }
              }, 100);        
            }, slider.interval);
          }
        }       
      }
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    $scope.main.footer.slider.run();
    $scope.main.notice.get();
  }
]);
