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
   'homeResource',
   'wpResource',
  function($scope,
           homeResource,
           wpResource) {
    /* ---------------------------------------------------------------------
     * Functions/Objects.
     * --------------------------------------------------------------------- */
    $scope.search = {
      q: null,

      run: function() {
        $scope._state.go('search.q', { q: $scope.search.q });
      }
    };

    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    // Get "What's New" content.
    $scope.whatsNew = wpResource.getPage($scope._env.wp.pages['what\'s new']);

    // Get "Have a Look" content.
    $scope.haveALook = homeResource.get();

    // Load Twitter timeline and hide any images.
    // @see https://dev.twitter.com/web/javascript/initialization
    // @see https://dev.twitter.com/web/javascript/events
    twttr.widgets.load();
    twttr.events.bind('loaded', function(event) {
      event.widgets.forEach(function(widget) {
        angular.element('#' + widget.id).contents()
          .find('.timeline-Tweet-media').hide();
      });
    });
  }
]);
