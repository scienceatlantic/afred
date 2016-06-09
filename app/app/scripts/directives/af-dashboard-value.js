'use strict';

/**
 * Directive for form fields.
 */
angular.module('afredApp').directive('afDashboardValue', [
  function() {
    return {
      restrict: 'A',
      replace: true,
      templateUrl: 'views/directives/af-dashboard-value.html',
      scope: {
        value: '=afDashboardValue',
        label: '@afDashboardValueLabel',
        sref: '@afDashboardValueSref'
      }
    };
  }
]);
