'use strict';

/** 
 * @fileoverview All angular.config() code.
 * @see https://docs.angularjs.org/api/ng/type/angular.Module#config
 */

// $http configuration.
angular.module('afredApp').config(['$httpProvider', function($httpProvider) {
    $httpProvider.defaults.withCredentials = true;
  }
]);

// TextAngular configuration.
angular.module('afredApp').config(['$provide', function($provide) {
  $provide.decorator('taOptions', ['$delegate', function(taOptions) {
    taOptions.toolbar = [
      [
        'bold',
        'italics',
        'underline',
        'ul',
        'ol',
        'indent',
        'outdent',
        'insertLink'
      ]  
    ];
    
    return taOptions; 
  }]);
}]);
