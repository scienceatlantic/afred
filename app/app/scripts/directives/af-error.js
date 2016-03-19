'use strict';

/**
 * Directive for error messages.
 */
angular.module('afredApp').directive('afError',
  function() {
    return {
      restrict: 'A',
      replace: true,
      transclude: true,
      templateUrl: 'views/directives/af-error.html',
      scope: {
        showErrorMsg: '=afError',
        errorType: '@afErrorType'
      },
      link: function(scope, element, attr) {
        switch (scope.errorType) {
          case '404':
            break;
          
          case '500':
            break;
          
          default:
            
        }
      }
    };
  }
);