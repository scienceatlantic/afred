'use strict';

angular.module('afredApp').directive('field',
  function() {
    return {
      restrict: 'A',
      replace: true,
      require: '^form',
      transclude: true,
      templateUrl: 'views/directives/form-field.html',
      scope: {
        label: '@'
      },
      link: function($scope, element, attrs, form) {      
        var field = element.find('input, select, textarea, div[data-text-angular]');
        $scope.name = field.attr('name');
        $scope.id = field.attr('id');
        $scope.form = form;
      }
    };
  }
);

angular.module('afredApp').directive('loading',
  function() {
    return {
      restrict: 'A',
      replace: true,
      transclude: true,
      templateUrl: 'views/directives/loading.html',
      scope: {
        loading: '='
      }
    };
  }
);