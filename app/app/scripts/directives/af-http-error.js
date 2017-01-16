'use strict';

/**
 * @fileoverview Displays an error message if a particular snippet (retrieved
 * via AJAX) fails (instead of displaying a fullpage 403, 404, etc).
 * 
 * Example usage:
 * <div af-http-error="someFlag">
 *   <div>Actual page content</div>
 * </div>
 * 
 * @see https://docs.angularjs.org/guide/directive
 */

angular.module('afredApp').directive('afHttpError',
  function() {
    return {
      restrict: 'A',
      replace: true,
      transclude: true,
      templateUrl: 'views/directives/af-http-error.html',
      scope: {
        /**
         * Loading flag.
         * 
         * @type {boolean} true - shows the error message, false - shows the
         *     actual page content.
         */
        error: '=afHttpError'
      }
    };
  }
);
