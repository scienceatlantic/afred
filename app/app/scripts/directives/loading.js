'use strict';

angular.module('afredApp').directive('loading',
  function() {
    return {
      restrict: 'A',
      replace: true,
      transclude: true,
      templateUrl: 'views/directives/loading.html',
      scope: {
        loading: '='
      }
    };
  }
);