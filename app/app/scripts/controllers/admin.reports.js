'use strict';

/**
 * @fileoverview Admin / Reports page.
 * 
 * @see https://docs.angularjs.org/guide/controller
 * @see /scripts/routes.js (for route info)
 */

angular.module('afredApp').controller('AdminReportsController',
  ['$scope',
   'infoModal',
   'ReportResource',
   'warningModal',
  function($scope,
           infoModal,
           ReportResource,
           warningModal) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */

     /**
      * Generate report.
      *
      * @sideeffect $scope.report Value returned from `ReportResource` stored
      *     here.
      *
      * @requires infoModal
      * @requires ReportResource
      * @requires warningModal
      */
    $scope.getReport = function() {
      var t = 'create-report';
      $scope.report = ReportResource.get(null, function() {
        infoModal.open(t + '-success');
      }, function() {
        warningModal.open(t + '-failed');        
      });
    };

    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    /**
     * Stores value returned from `ReportResource`.
     * 
     * @type {Angular resource}
     */
    $scope.report = null;
  }
]);
