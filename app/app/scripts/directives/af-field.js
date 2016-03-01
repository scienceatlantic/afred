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
        label: '@afFieldLabel',
        nameOverride: '@afFieldName',
        typeOverride: '@afFieldType',
        isTextAngular: '@afFieldTextangular'
      },
      link: function($scope, element, attrs, form) { 
        if ($scope.nameOverride) {
          $scope.name = $scope.nameOverride;
          
          $scope.template = {
            isRadio: $scope.typeOverride === 'radio',
            isCheckbox: $scope.typeOverride === 'checkbox'
          };
        } else {
          var field = element.find('input, select, textarea');
          
          if ($scope.isTextAngular) {
            $scope.name = field.get(1).name;
            $scope.id = field.get(1).id;
            
            $scope.template = {
              isTextArea: true
            };
          } else {
            $scope.name = field.attr('name');
            $scope.id = field.attr('id');
  
            $scope.template = {
              isInput: field.prop('tagName') === 'INPUT',
              isSelect: field.prop('tagName') === 'SELECT',
              isTextarea: field.prop('tagName') === 'TEXTAREA',
              isRadio: field.attr('type') === 'radio',
              isCheckbox: field.attr('type') === 'checkbox'
            };     
          }               
        }
        
        $scope.form = form; 
      }
    };
  }
);