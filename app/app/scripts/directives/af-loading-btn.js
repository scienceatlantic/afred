'use strict';

/**
 * Directive for AJAX loading btn GIFs.
 */
angular.module('afredApp').directive('afLoadingBtn',
  function() {
    return {
      restrict: 'A',
      replace: true,
      transclude: true,
      templateUrl: 'views/directives/af-loading-btn.html',
      scope: {
        loading: '=afLoadingBtn'
      }
    };
  }
);