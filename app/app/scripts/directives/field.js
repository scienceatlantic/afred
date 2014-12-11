'use strict';

angular.module('afredApp').directive('field',
  function() {
    return {
      restrict: 'A',
      replace: true,
      require: '^form',
      transclude: true,
      templateUrl: 'views/directives/field.html',
      scope: {
        label: '@'
      },
      link: function($scope, element, attrs, form) {      
        var field = element.find('input, select, textarea, div[data-text-angular]');
        $scope.name = field.attr('name');
        $scope.id = field.attr('id');
        $scope.form = form;
        
        console.log(attrs.label + ' ' + field.attr('required'));
        
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