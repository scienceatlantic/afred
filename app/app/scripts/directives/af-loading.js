'use strict';

angular.module('afredApp').directive('af-loading',
  function() {
    return {
      restrict: 'A',
      replace: true,
      transclude: true,
      templateUrl: 'views/directives/af-loading.html',
      scope: {
        loading: '='
      }
    };
  }
);