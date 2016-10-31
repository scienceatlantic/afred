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
         *     disciplines: [{
         *       id: #,
         *       name: ''
         *     },...],
         *     sectors: [{
         *       id: #,
         *       name: ''
         *     },...],
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
         *     },...]
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
         * (Optional) By default only the:
         * 
         * `facility.description`
         * `facility.equipment.purpose`
         * `facility.equipment.specification`
         * 
         * properties are injected using the `ngBindHtml` directive. Set this to
         * true if you want all properties to be injected via the `ngBindHtml`
         * directive.
         */
        useBindHtml: '=afFacilityUseBindHtml',
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
        isPreview: '=afFacilityIsPreview',
        /**
         * (Optional) A label/tag is added next to the organization's name
         * indicating that it is a new organization (not in the drop down list).
         * The `isPreview` property (above) also has to be set to true for this
         * to apply. 
         * 
         * @type {boolean} True - label is shown.
         */
        isNewOrganization: '=afFacilityIsNewOrganization'
      },
      link: function($scope, element, attrs) {
        // Since we're using an isolate scope, the directive no longer inherits
        // from the $rootScope. So we have to manually copy the properties we
        // need.
        $scope._getWidth = $rootScope._getWidth;
        $scope._bootstrap = $rootScope._bootstrap;
        $scope._state = $rootScope._state;
      }
    };
  }
]);
