'use strict';

/**
 * @fileoverview Home page. Certain content is retrieved from WordPress.
 * 
 * @see https://docs.angularjs.org/guide/controller
 * @see /scripts/routes.js (for route info)
 * @see /scripts/env.js (for WordPress settings)
 */

angular.module('afredApp').controller('HomeController',
  ['$interval',
   '$scope',
   '$timeout',
   'MiscResource',
   'WpResource',
  function($interval,
           $scope,
           $timeout,
           MiscResource,
           WpResource) {
    /* ---------------------------------------------------------------------
     * Functions/Objects.
     * --------------------------------------------------------------------- */
    /**
     * Search properties/functions.
     * 
     * @type {object}
     */
    $scope.search = {
      q: null,

      run: function() {
        $scope._state.go('search.q', { q: $scope.search.q });
      }
    };

    /**
     * Loading flags.
     * 
     * @type {object}
     */
    $scope.loading = {
      twitter: true
    };

    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    // Get "What's New" content.
    $scope.whatsNew = WpResource.getPage($scope._env.wp.pages['what\'s new']);

    // Get "Have a Look" content.
    $scope.haveALook = MiscResource.get({ item: 'randomEquipment' });

    // Get "Recent Tweets" content.
    // Load Twitter timeline and hide any images (check that it exists first). 
    // @see https://dev.twitter.com/web/javascript/initialization
    // @see https://dev.twitter.com/web/javascript/events
    var attemptCount = 1;
    var intervalId = $interval(function() {
      if (attemptCount++ <= 15) {
        if (typeof twttr !== 'undefined' && twttr !== null) {
          // Cancel interval.
          $interval.cancel(intervalId);

          // Load twitter widget.
          twttr.widgets.load();

          // Hide images and readjust height (after removing images the height
          // needs to be readjusted to match the new height of the content).
          twttr.events.bind('loaded', function(event) {
            event.widgets.forEach(function(widget) {
              var id = '#' + widget.id;

              // Hide images.
              angular.element(id).contents().find('.timeline-Tweet-media')
                .hide();

              // Readjust height.
              var contentHeight = angular.element(id).contents()
                .find('.timeline-Widget').height();
              angular.element(id).height(contentHeight);
            });

            // Set loading flag to false (after an artificial delay).
            $timeout(function() {
              $scope.loading.twitter = false;
            }, 600);
          });
        }
      } else {
        // Maximum number of attempts reached, quit.
        $interval.cancel(intervalId);
        
        // Set loading flag to false.
        $scope.loading.twitter = false;
      }
    }, 50);
  }
]);
