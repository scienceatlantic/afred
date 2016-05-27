'use strict';

// Credit: http://stackoverflow.com/questions/19415394/with-ng-bind-html-unsafe-removed-how-do-i-inject-html
angular.module('afredApp').filter('afTrustAsHtml',
  ['$sce',
  function($sce) {
    return function(content) {
      return $sce.trustAsHtml(content);
    };
  }
]);
