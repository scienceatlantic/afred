'use strict';

/**
 * Directive for form fields.
 */
angular.module('afredApp').directive('afField', [
  '$timeout',
  'textAngularManager',
  function($timeout, textAngularManager) {
    return {
      restrict: 'A',
      replace: true,
      require: '^form',
      transclude: true,
      templateUrl: 'views/directives/af-field.html',
      scope: {
        label: '@afFieldLabel',
        nameOverride: '@afFieldName',
        model: '=afFieldModel',
        isMd: '=afFieldIsMd',
        isVertical: '=afFieldIsVertical'
      },
      link: function($scope, element, attrs, form) {
        // Find the form field element.
        var field = element.find('input[type!="hidden"], select, ' +
          'textarea:not([ta-bind]), div[data-text-angular], div[text-angular]');
        
        // Note: '$timeout' is used as a hack-ish fix to give Angular time
        // to interpolate anything inside '{{}}' (if used in a name attribute).
        if ($scope.nameOverride) {
          $scope.name = $scope.nameOverride;
        } else {
          $timeout(function() {
            $scope.name = field.attr('name');
          });
        }
        
        // Parse the 'maxLength' property.
        try {
          $scope.maxLength = parseInt(field.attr('maxlength'));
          
          if (!$scope.maxLength) {
            $scope.maxLength = parseInt(field.attr('data-ta-maxlength'));
          }
          
          if (!$scope.maxLength) {
            $scope.maxLength = parseInt(field.attr('ta-maxlength'));
          }
        } catch(e) {
          // Do nothing.
        }
        
        // TextAngular code.
        $scope.isTextAngular = field.hasClass('ta-root');
        
        if ($scope.isTextAngular) {
          $timeout(function() {
            $scope.getTextAngularContentLength = function() {
              var ta = textAngularManager.retrieveEditor($scope.name);
              return angular.element(ta.scope.displayElements.text[0])
                .text().length;
            };          
          });
        }
        
        $scope.form = form;
      }
    };
  }
]);