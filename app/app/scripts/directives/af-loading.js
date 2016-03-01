'use strict';

/**
 * Directive for AJAX loading GIFs.
 */
angular.module('afredApp').directive('afLoading',
  function() {
    return {
      restrict: 'A',
      replace: true,
      transclude: true,
      templateUrl: 'views/directives/af-loading.html',
      scope: {
        loading: '=afLoading'
      }
    };
  }
);