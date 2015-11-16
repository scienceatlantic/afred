'use strict';

/**
 * Directive for form fields.
 */
angular.module('afredApp').directive('afField',
  function() {
    return {
      restrict: 'A',
      replace: true,
      require: '^form',
      transclude: true,
      templateUrl: 'views/directives/af-field.html',
      scope: {
        label: '@afFieldLabel'
      },
      link: function($scope, element, attrs, form) {      
        var field = element.find('input, select, textarea');
        $scope.name = field.attr('name');
        $scope.id = field.attr('id');
        $scope.form = form;
        
        $scope.template = {
          isInput: field.prop('tagName') === 'INPUT',
          isSelect: field.prop('tagName') === 'SELECT',
          isTextarea: field.prop('tagName') === 'TEXTAREA',
          isRadio: field.attr('type') === 'radio',
          requiredField: field.attr('required')
        };
      }
    };
  }
);