'use strict';

/**
 * @fileoverview Home page. Certain content is retrieved from WordPress.
 * 
 * @see https://docs.angularjs.org/guide/controller
 * @see /scripts/routes.js (for route info)
 * @see /scripts/env.js (for WordPress settings)
 */

angular.module('afredApp').controller('HomeController',
  ['$scope',
   '$timeout',
   'MiscResource',
   'WpResource',
  function($scope,
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
     * Twitter related properties/methods
     */
    $scope.twitter = {
      /**
       * Status flag.
       * 
       * @type {number} -1 = failed to load, 0 = loading, 1 = loaded
       */
      loaded: 0,

      /**
       * Attempt count.
       * 
       * @type {number}
       */
      attempts: 0,

      /**
       * Maximum number of attempts before failing.
       * 
       * @type {number}
       */
      maxNumAttempts: 15,

      /**
       * Delay (in milliseconds) before each attempt.
       * 
       * @type {number}
       */
      attemptDelay: 100,

      /**
       * Initialisation method.
       * @see https://dev.twitter.com/web/javascript/initialization
       * @see https://dev.twitter.com/web/javascript/events
       */
      init: function(elementId) {
        $scope.twitter.attempts++;

        try {
          // Load twitter widget.
          twttr.widgets.load(document.getElementById(elementId));

          // Remove images and readjust height (after removing images the 
          // height needs to be readjusted to match the new height of the
          // content).
          twttr.events.bind('loaded', function(event) {
            event.widgets.forEach(function(widget) {
              var id = '#' + widget.id;

              // Remove images.
              angular.element(id).contents().find('.timeline-Tweet-media')
                .remove();

              // Readjust height.
              angular.element(id).height(angular.element(id).contents()
                .find('.timeline-Widget').height());
            });

            $scope.twitter.loaded = 1;
            $scope._info('Loaded Twitter widget.');
          });            
        } catch (err) {
          if ($scope.twitter.attempts <= $scope.twitter.maxNumAttempts) {
            $timeout(function() {
              $scope._error('Failed to load Twitter widget (attempt #' 
                + $scope.twitter.attempts + '), trying again...');

              $scope.twitter.init(elementId);
            }, $scope.twitter.attemptDelay);
          } else {
            $scope.twitter.loaded = -1;
            $scope._error(err);
          }
        }
      }
    };

    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    // Get "What's New" content.
    $scope.whatsNew = WpResource.getPage($scope._env.wp.pages['what\'s new']);

    // Get "Have a Look" content.
    $scope.haveALook = MiscResource.get({ item: 'randomEquipment' });
  }
]);
