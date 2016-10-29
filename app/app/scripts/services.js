'use strict';

/** 
 * @fileoverview Angular services are stored here.
 * @see https://docs.angularjs.org/api/auto/service/$provide#service
 * @see https://docs.angularjs.org/guide/services
 */

/* ---------------------------------------------------------------------
 * Generic injectable modals.
 * @see https://angular-ui.github.io/bootstrap/#/modal
 * --------------------------------------------------------------------- */

angular.module('afredApp').service('confirmModal', [
  '$uibModal',
  function($uibModal) {    
    this.open = function(template) {
      return $uibModal.open({
        templateUrl: 'views/modals/confirm.html',
        controller: ['$scope', '$uibModalInstance',
          function($scope, $uibModalInstance) {
            $scope.modal = $uibModalInstance;
            $scope.template = 'views/includes/modals/'+ template + '.html';
          }
        ]
      });        
    };
  }
]);

angular.module('afredApp').service('infoModal', [
  '$uibModal',
  function($uibModal) {    
    this.open = function(template) {
      return $uibModal.open({
        templateUrl: 'views/modals/info.html',
        controller: ['$scope', '$uibModalInstance',
          function($scope, $uibModalInstance) {
            $scope.modal = $uibModalInstance;
            $scope.template = 'views/includes/modals/' + template + '.html';
          }
        ],
        keyboard: false,
        backdrop: 'static'
      });        
    };
  }
]);

angular.module('afredApp').service('warningModal', [
  '$uibModal',
  function($uibModal) {    
    this.open = function(template) {
      return $uibModal.open({
        templateUrl: 'views/modals/warning.html',
        controller: ['$scope', '$uibModalInstance',
          function($scope, $uibModalInstance) {
            $scope.modal = $uibModalInstance;
            $scope.template = 'views/includes/modals/' + template + '.html';
          }
        ],
        keyboard: false,
        backdrop: 'static'
      });        
    };
  }
]);

/* ---------------------------------------------------------------------
 * Algolia Search.
 * @see https://www.algolia.com/doc/api-client/javascript/getting-started#install
 * --------------------------------------------------------------------- */

angular.module('afredApp').service('algolia', [
  '$rootScope',
  function($rootScope) {
    var env = $rootScope._env.algolia;
    var client = algoliasearch(env.api.applicationId, env.api.key);

    // Initialise each index.
    this.facilities = function() {
      return client.initIndex(env.indices.facilities)
    };
    this.equipment = function() {
      return client.initIndex(env.indices.equipment)
    };
  }
]);

angular.module('afredApp').service('JsDiff', [
  function() {
    function formatter(original, edit, isHtml, diffType) {
      original = (original ? String(original) : '');
      edit = (edit ? String(edit) : '');

      if (isHtml) {
        original = angular.element('<div>' + original + '</div>').text();
        edit = angular.element('<div>' + edit + '</div>').text();
      }

      if (!original.trim() && !edit.trim()) {
        return '';
      }

      var diff = null;
      switch (diffType) {
        case 'chars':
          diff = JsDiff.diffChars(original, edit);
          break;
        case 'words':
          diff = JsDiff.diffWords(original, edit);
          break;
        case 'lines':
          diff = JsDiff.diffLines(original, edit);
          break;              
      }

      var span = '';
      diff.forEach(function(part) {
        // green for additions, red for deletions
        // grey for common parts
        span += '<span style="color:';

        if (part.added) {
          span += 'green; font-weight: bold';
        } else if (part.removed) {
          span += 'red; text-decoration: line-through';
        } else {
          span += 'inherit';
        }

        span += ';">' + part.value + '</span>';
      });

      return span;     
    }

    this.chars = function(original, edit, isHtml) {
      return formatter(original, edit, isHtml, 'chars');
    };

    this.words = function(original, edit, isHtml) {
      return formatter(original, edit, isHtml, 'words');
    };

    this.lines = function(original, edit, isHtml) {
      return formatter(original, edit, isHtml, 'lines');
    };
  }
]);
