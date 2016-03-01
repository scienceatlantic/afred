'use strict';

angular.module('afredApp').config(['$httpProvider', function($httpProvider) {
    $httpProvider.defaults.withCredentials = true;
  }
]);

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
