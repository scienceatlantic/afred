'use strict';

/**
 * @fileoverview Loading GIF container for buttons.
 * 
 * Example usage:
 * <button class="btn btn-default" data-af-loading-btn="someFlag" data-af-loading-btn-text="Searching...">Search</button>
 * 
 * @see https://docs.angularjs.org/guide/directive
 */

angular.module('afredApp').directive('afLoadingBtn',
  function() {
    return {
      restrict: 'A',
      replace: false,
      transclude: true,
      templateUrl: 'views/directives/af-loading-btn.html',
      scope: {
        /**
         * Loading flag.
         * 
         * @type {boolean} True - button content is replaced with 'Loading...'
         * by default. The button will not be disabled, you have to set this
         * manually.
         */
        loading: '=afLoadingBtn',
        /**
         * (Optional) Set your own custom loading text. The default is
         * 'Loading...'.
         * 
         * @type {string}
         */
        loadingText: '@afLoadingBtnText'
      }
    };
  }
);
