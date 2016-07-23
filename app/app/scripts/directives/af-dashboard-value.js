'use strict';

/**
 * @fileoverview Angular directive to display numeric data inside a Bootstrap
 * CSS panel (see the admin dashboard).
 * 
 * Example usage:
 * <div data-af-dashboard-value="someValue" data-af-dashboard-value-label="Total"></div>
 * 
 * @see https://docs.angularjs.org/guide/directive
 */

angular.module('afredApp').directive('afDashboardValue', [
  function() {
    return {
      restrict: 'A',
      replace: true,
      templateUrl: 'views/directives/af-dashboard-value.html',
      scope: {
        /**
         * Value to be displayed.
         * 
         * @type {integer}
         */
        value: '=afDashboardValue',
        /**
         * A label to describe the value being displayed.
         * 
         * @type {string}
         */
        label: '@afDashboardValueLabel',
        /**
         * (Optional) A link that will be attached to the numeric value.
         * 
         * @type {string - URL}
         */
        sref: '@afDashboardValueSref'
      }
    };
  }
]);
