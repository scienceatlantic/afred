'use strict';

/**
 * Directive for form fields.
 */
angular.module('afredApp').directive('afField', [
  '$timeout',
  function($timeout) {
    return {
      restrict: 'A',
      replace: true,
      require: '^form',
      transclude: true,
      templateUrl: 'views/directives/af-field.html',
      scope: {
        label: '@afFieldLabel',
        nameOverride: '@afFieldName',
        popover: '@afFieldPopover',
        isTextAngular: '@afFieldTextangular'
      },
      link: function($scope, element, attrs, form) { 
        var field = element.find('input, select, textarea');
        
        // Note: '$timeout' is used as a hack-ish fix to give Angular time
        // to interpolate '$index' (if used).
        if ($scope.isTextAngular) {
          if ($scope.nameOverride) {
            $scope.name = $scope.nameOverride;
          } else {
            $scope.name = field.get(1).name;
          }
          
          $scope.id = field.get(0).id; // Not working, need fix!          
        } else {
          if ($scope.nameOverride) {
            $scope.name = $scope.nameOverride;
          } else {
            $timeout(function() {
              $scope.name = field.attr('name');
            });
          }
          
          $scope.id = field.attr('id');  
        }
        
        $scope.form = form; 
      }
    };
  }
]);