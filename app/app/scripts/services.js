'use strict';

/** 
 * @fileoverview Angular services are stored here.
 * @see https://docs.angularjs.org/api/auto/service/$provide#service
 * @see https://docs.angularjs.org/guide/services
 */

angular.module('afredApp').service('confirmModal', [
  '$uibModal',
  function($uibModal) {    
    this.open = function(template) {
      return $uibModal.open({
        templateUrl: 'views/modals/confirm.html',
        controller: ['$scope', '$uibModalInstance',
          function($scope, $uibModalInstance) {
            $scope.modal = $uibModalInstance;
            $scope.template = 'views/includes/modal-' + template + '.html';
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
            $scope.template = 'views/includes/modal-' + template + '.html';
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
            $scope.template = 'views/includes/modal-' + template + '.html';
          }
        ],
        keyboard: false,
        backdrop: 'static'
      });        
    };
  }
]);
