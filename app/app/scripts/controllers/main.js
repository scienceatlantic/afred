'use strict';

angular.module('afredApp').controller('MainController',
  ['$scope',
   '$interval',
  function($scope,
           $interval) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * Controller class.
     */
    $scope.main = {
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
           * @type integer
           */
          currentImgIndex: 0,
          
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
              if (++s.currentImgIndex == 4) {
                s.currentImgIndex = 0;
              }
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
