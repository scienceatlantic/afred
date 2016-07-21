'use strict';

/**
 * @fileoverview Displays facility data.
 * 
 * Example usage:
 * <div data-af-facility="all" data-af-facility-model="facility"></div>
 * 
 * @see https://docs.angularjs.org/guide/directive
 */

angular.module('afredApp').directive('afFacility', [
  '$rootScope',
  function($rootScope) {
    return {
      restrict: 'A',
      replace: true,
      transclude: true,
      templateUrl: 'views/directives/af-facility.html',
      scope: {
        /**
         * Facility data.
         * 
         * @type {Angular model} Expects a facility object in this format:
         *   {
         *     name: '',
         *     organizationId: #,
         *     ...
         *     contacts: [{
         *       firstName: '',
         *       ...
         *     }, {
         *       ...
         *     }],
         *     ilo: {
         *       firstName: '',
         *       ...
         *     },
         *     equipment: [{
         *       type: '',
         *       ...
         *     }, {
         *       ...
         *     }]
         *   }
         */
        facility: '=afFacilityModel',
        /**
         * Content to display:
         * 
         * @type {string} 
         *   'all' - All facility data is displayed. This will require the 
         *   complete facility object (including ilo, contacts array, and 
         *   equipment array).
         * 
         *   'facility' - Only facility data is displayed.
         * 
         *   'contacts' - Only contacts data is displayed.
         *   
         *   'equipment' - Only equipment data is displayed.
         */
        view: '@afFacility',
        /**
         * (Optional) By default the data is displayed in Bootstrap panels.
         * 
         * @type {boolean} True - will displayed the data without Bootstrap
         *     panels.
         */
        hidePanel: '=afFacilityHidePanel',
        /**
         * (Optional) By default the data is displayed as if it's meant for the
         * end user. This property enables certain additional properties (e.g. 
         * excess capacity, etc) to be displayed (assuming it's provided in the
         * facility object).
         * 
         * @type {boolean} True - preview mode is enabled.
         */
        isPreview: '=afFacilityIsPreview'
      },
      link: function($scope, element, attrs) {
        // Since we're using an isolate scope, the directive no longer inherits
        // from the $rootScope. So we have to manually copy the properties we
        // need.
        $scope._window = $rootScope._window;
        $scope._bootstrap = $rootScope._bootstrap;
        $scope._state = $rootScope._state;
      }
    };
  }
]);
