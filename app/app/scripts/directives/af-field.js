'use strict';

/**
 * @fileoverview Angular directive for form fields.
 * 
 * Example usage:
 *  <div data-af-field data-af-field-label="Facility/Lab*">
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
      transclude: true,
      templateUrl: 'views/directives/af-field.html',
      scope: {
        /**
         * Input label.
         * 
         * @type {string}
         */
        label: '@afFieldLabel',
        /**
         * (Optional) Input name attribute. This property is used if the 
         * directive is not able to retrieve the name attribute from the 
         * transcluded content.
         * 
         * @type {string}
         */
        nameOverride: '@afFieldName',
        /**
         * (Optional) Input Angular model. Only used if we need to keep track of 
         * character lengths (the maximum number of characters is determined 
         * based on the transcluded content's maxlength property).
         * 
         * @type {Angular model}
         */
        model: '=afFieldModel',
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
          'textarea:not([ta-bind]), div[data-text-angular], div[text-angular]');

        // Attach the form object to the scope.
        $scope.form = form;

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
        try {
          $scope.minLength = parseInt(field.attr('minlength'));
          if (!$scope.minLength) {
            $scope.minLength = parseInt(field.attr('data-ta-minlength'));
          }
          if (!$scope.minLength) {
            $scope.minLength = parseInt(field.attr('ta-minlength'));
          }
        } catch(e) {
          // Do nothing.
        }
        
        // Parse the 'maxlength' property if available.
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

        // Check if the element is a Text Angular element.
        $scope.isTextAngular = field.hasClass('ta-root');
        
        // TextAngular code for content length.
        if ($scope.isTextAngular) {
          $timeout(function() {
            $scope.getTextAngularContentLength = function() {
              var ta = textAngularManager.retrieveEditor($scope.name);
              return angular.element(ta.scope.displayElements.text[0])
                .text().length;
            };          
          });
        }        
      }
    };
  }
]);
