'use strict';

angular.module('afredApp').controller('MainController',
  ['$scope',
   '$interval',
   '$timeout',
  function($scope,
           $interval,
           $timeout) {
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
          angular.element(".navbar-nav li a").click(function(event) {
            angular.element(".navbar-collapse").collapse('hide');
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
           * Current index of image to show. See 'footer.html'.
           *
           * @type float
           */
          currentImgIndex: 0.0,
          
          /**
           * Interval in milliseconds for the '$scope.main.footer.slider.run'
           * method.
           *
           * @type integer
           */
          interval: 2500,
          
          /**
           * Executes the slider.
           *
           * Side effects:
           * currentImgIndex Increments the index every 'interval' milliseconds.
           *     The index is reset to 0 when it's more than 3.
           */
          run: function() {
            // Alias to shorten code.
            var s = $scope.main.footer.slider;
            
            $interval(function() {
              // Hack fix. Some browsers don't properly render the change if
              // the images are loaded one after the other immediately. There's
              // a little bit of a flicker because for a split second both
              // images are shown at the same time. So we have to introduce a
              // tiny delay in between.
              s.currentImgIndex += 0.1;
              $timeout(function() {
                s.currentImgIndex = Math.floor(++s.currentImgIndex);
                if (s.currentImgIndex == 4) {
                  s.currentImgIndex = 0;
                }
              }, 100);        
            }, s.interval);
          }
        }       
      }
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    $scope.main.footer.slider.run();
  }
]);
