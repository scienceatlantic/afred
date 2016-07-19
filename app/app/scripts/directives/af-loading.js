'use strict';

/**
 * @fileoverview Loading GIF container.
 * 
 * Example usage:
 * <div af-loading="someFlag" af-loading-text="Retrieving...">
 *   <p>Transcluded content...</p>
 * </div>
 * 
 * @see https://docs.angularjs.org/guide/directive
 */

angular.module('afredApp').directive('afLoading',
  function() {
    return {
      restrict: 'A',
      replace: true,
      transclude: true,
      templateUrl: 'views/directives/af-loading.html',
      scope: {
        /**
         * Loading flag.
         * 
         * @type {boolean} True - shows the loading GIF and hides the
         * transcluded content. False - does the exact opposite.
         */
        loading: '=afLoading',
        /**
         * (Optional) Set your own custom loading text. The default is 
         * 'Loading...'
         */
        loadingText: '@afLoadingText'
      }
    };
  }
);
