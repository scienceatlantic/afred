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
           * Array of images and corresponding URLs.
           *
           * @type array
           */
          images: [{
            // Science Atlantic
            src: 'images/slider/sa-main-logo-200x80.png',
            url: 'https://scienceatlantic.ca'
          }, {
            // Springboard Atlantic
            src: 'images/slider/springboard-200x80.png',
            url: 'http://springboardatlantic.ca'
          }, {
            // NSERC
            src: 'images/slider/nserc-200x80.png',
            url: 'http://www.nserc-crsng.gc.ca'
          }, {
            // ACOA
            src: 'images/slider/acoa-200x80.png',
            url: 'http://www.acoa-apeca.gc.ca'
          }],
          
          /**
           * Current image source.
           *
           * @type string
           */
          currentImgSrc: null,
          
          /**
           * Current image URL.
           *
           * @type string
           */
          currentImgUrl: null,
          
          /**
           * Current index of '$scope.main.footer.slider.images'.
           *
           * @type integer
           */
          currentImgIndex: 0,
          
          /**
           * Interval in milliseconds for the '$scope.main.footer.slider.run'
           * method.
           */
          interval: 2500,
          
          /**
           * Executes the slider.
           *
           * Side effects:
           * currentImgIndex Increments the index every 'interval' milliseconds.
           *     The index is reset when it reaches the length of
           *     '$scope.main.footer.slider.images'.
           * currentImgSrc Updated to match the 'src' property of the current
           *     element '$scope.main.footer.slider.currentImgIndex' is pointing
           *     to in '$scope.main.footer.slider.images'.
           * currentImgUrl Same as 'currentImgSrc' except it's updated to match
           *     'url' property instead.
           *
           * Uses/calls/requires
           * $scope.main.footer.slider.images
           * $scope.main.footer.slider.interval
           */
          run: function() {
            // Alias to shorten code.
            var s = $scope.main.footer.slider;
            
            s.currentImgSrc = s.images[s.currentImgIndex].src;
            s.currentImgUrl = s.images[s.currentImgIndex].url;
            
            $interval(function() {
              if (++s.currentImgIndex == s.images.length) {
                s.currentImgIndex = 0;
              }
            
              s.currentImgSrc = s.images[s.currentImgIndex].src;
              s.currentImgUrl = s.images[s.currentImgIndex].url;
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
