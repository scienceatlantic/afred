'use strict';

/**
 * Directive for form fields.
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
        facility: '=afFacilityModel',
        view: '@afFacility',
        hidePanel: '=afFacilityHidePanel',
        isPreview: '=afFacilityIsPreview'
      },
      link: function($scope, element, attrs) {
        // Since we're using an isolate scope, the directive no longer inherits
        // from the $rootScope. So we have to manually copy it.
        $scope._window = $rootScope._window;
        $scope._bootstrap = $rootScope._bootstrap;
      }
    };
  }
]);