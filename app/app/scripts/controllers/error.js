'use strict';

/**
 * @fileoverview Error 
 * 
 * @see https://docs.angularjs.org/guide/controller
 * @see /scripts/routes.js (for route info)
 */

angular.module('afredApp').controller('ErrorController',
  ['$scope',
  function($scope) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */

    /**
     * Calculates the margins necessary to centerise the error message. 
     * 
     * @return string
     */
    $scope.margins = function() {
      // Top margin. Height of window halved minus the height of the content
      // container (i.e. 360px) halved. 
      var top = Math.max(0, ($scope._getHeight() / 2) - (360 / 2));
      
      return 'margin: ' + top + 'px auto 0 auto';  
    };

    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    $scope.httpStatusCode = parseInt($scope._state.current.name.split('.')[1]);
  }
]);
