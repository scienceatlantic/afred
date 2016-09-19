'use strict';

/** 
 * @fileoverview All angular.config() code.
 * @see https://docs.angularjs.org/api/ng/type/angular.Module#config
 */

/**
 * $http config.
 * @see https://docs.angularjs.org/api/ng/provider/$httpProvider
 */
angular.module('afredApp').config(['$httpProvider', function($httpProvider) {
  $httpProvider.defaults.withCredentials = true;
}]);

/**
 * $httpProvider config - Use HTML5 mode.
 * 
 * @see https://docs.angularjs.org/guide/$location (part about HTML5 mode)
 * @see https://docs.angularjs.org/api/ng/provider/$locationProvider (part about HTML5 mode)
 */
angular.module('afredApp').config(['$locationProvider', 
  function($locationProvider) {
    $locationProvider.html5Mode(true);
  }
]);

/**
 * TextAngular config.
 * @see https://github.com/fraywing/textAngular/wiki/Setting-Defaults
 */
angular.module('afredApp').config(['$provide', function($provide) {
  $provide.decorator('taOptions', ['$delegate', function(taOptions) {
    taOptions.toolbar = [[
      'bold',
      'italics',
      'underline',
      'ul',
      'ol',
      'indent',
      'outdent',
      'insertLink'
    ]];
    return taOptions; 
  }]);
}]);
