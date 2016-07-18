'use strict';
/**
 * @fileoverview Runs a string through AngularJS' $sce.trustAsHtml() function.
 *
 * @see https://docs.angularjs.org/api/ng/filter/filter
 * @see https://docs.angularjs.org/guide/filter
 * @see https://docs.angularjs.org/api/ng/service/$sce#trustAsHtml
 * 
 * Credit:
 * @see http://stackoverflow.com/questions/19415394/with-ng-bind-html-unsafe-removed-how-do-i-inject-html
 */

angular.module('afredApp').filter('afTrustAsHtml',
  ['$sce',
  function($sce) {
    return function(content) {
      return $sce.trustAsHtml(content);
    };
  }
]);
