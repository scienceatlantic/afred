'use strict';

/**
 * @fileoverview Angular directive for form fields.
 * 
 * Example usage:
 *  <div data-af-field="Facility/Lab*">
 *    <input class="form-control" name="facilityName" data-ng-model="facility.name" required>
 *  </div>
 * 
 * @see https://docs.angularjs.org/guide/directive
 */

angular.module('afredApp').directive('afField', [
  '$timeout',
  'textAngularManager',
  function($timeout, textAngularManager) {
    return {
      restrict: 'A',
      replace: true,
      require: '^form', // The directive requires an Angular form controller.
      transclude: {
        // Content that should be placed after any validation messages (e.g. 
        // input descriptions, buttons, etc).
        'afOtherContent': '?afOtherContent'
      },
      templateUrl: 'views/directives/af-field.html',
      scope: {
        /**
         * Input label.
         * 
         * @type {string}
         */
        label: '@afField',
        /**
         * (Optional) Input name attribute. This property is used if the 
         * directive is not able to retrieve the name attribute from the 
         * transcluded content.
         * 
         * @type {string}
         */
        nameOverride: '@afFieldName',
        /**
         * (Optional) Helpful tooltip text.
         * 
         * @type {string} Can also include HTML (though it will not be 
         *     compiled).
         */
        tooltip: '=afFieldTooltip',
        /**
         * (Optional) By default the 'form-group-sm' Bootstrap CSS class is 
         * applied to the form group. We can disable this by setting this 
         * property to true.
         * 
         * @type {boolean}
         */
        isMd: '=afFieldIsMd',
        /**
         * (Optional) By default the form elements are displayed as a horizontal 
         * Bootstrap form. We can disable this by setting this value to true.
         * 
         * @type {boolean}
         */
        isVertical: '=afFieldIsVertical'
      },
      link: function($scope, element, attrs, form) {
        // Find the form field element.
        var field = element.find('input[type!="hidden"], select, ' +
          'textarea:not([ta-bind]), text-angular');

        // Attach the form object to the scope.
        $scope.form = form;

        // Check if the element is a Text Angular element.
        $scope.isTextAngular = field.hasClass('ta-root');

        // Get the HTML field's name attribute. 
        if ($scope.nameOverride) {
          $scope.name = $scope.nameOverride;
        } else {
          // Note: '$timeout' is used as a hack-ish fix to give Angular time
          // to interpolate anything inside '{{ }}' (if used in an HTML name 
          // attribute).
          $timeout(function() {
            $scope.name = field.attr('name');
          });
        }

        // Parse the 'minlength' property if available.
        ['data-ng-', 'ng-', ''].some(function(prefix) {
          $scope.minLength = parseInt(field.attr(prefix + 'minlength'));
          return $scope.minLength > 0;
        });
        
        // Parse the 'maxlength' property if available.
        ['data-ng-', 'ng-', 'data-ta-', 'ta-', ''].some(function(prefix) {
          var attr = $scope.isTextAngular ? 'max-text' : 'maxlength';
          $scope.maxLength = parseInt(field.attr(prefix + attr));
          return $scope.maxLength > 0;
        });
        
        // Content length.
        // Placed in a `$timeout` so that `$scope.name` will execute first 
        // (see code above).
        $timeout(function() {
          if ($scope.maxLength < 0 || typeof form[$scope.name] !== 'object') {
            $scope.len = function() {
              return 0;
            }
          } else if ($scope.isTextAngular) {
            $scope.len = function() {
              if (typeof form[$scope.name].$modelValue === 'string') {
                return angular.element('<div>' + form[$scope.name].$modelValue 
                  + '</div>').text().trim().length;
              }
              return 0;
            };
          } else {
            $scope.len = function() {
              if (typeof form[$scope.name].$modelValue === 'string') {
                return form[$scope.name].$modelValue.length;
              }
              return 0;
            };
          }
        });        
      }
    };
  }
]);
