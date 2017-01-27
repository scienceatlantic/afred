'use strict';

/**
 * @fileoverview Admin/Settings controller.
 * 
 * @see https://docs.angularjs.org/guide/controller
 * @see /scripts/routes.js (for route info)
 */

angular.module('afredApp').controller('AdminSettingsController', [
  '$scope',
  '$timeout',
  'confirmModal',
  'infoModal',
  'MiscResource',
  'SettingResource',
  'warningModal',
  function($scope,
           $timeout,
           confirmModal,
           infoModal,
           MiscResource,
           SettingResource,
           warningModal) {
    /* ---------------------------------------------------------------------
     * Functions/Objects.
     * --------------------------------------------------------------------- */

    /**
     * Search indices related properties/methods.
     */
    $scope.indices = {
      /**
       * Loading flag for the `$scope.indices.refresh()` method.
       * 
       * @type {boolean}
       */
      loading: false,

      /**
       * Holds the value that is returned from `$scope.indices.get()`.
       * 
       * @type {Angular resource}
       */
      resource: {},

      /**
       * An artificial delay (in milliseconds) that will be added on top of the
       * regular processing delay for the `$scope.indices.refresh()` method.
       * This (hopefully) ensures that Algolia has had enough time to update all
       * the indices before we grab it again after re-indexing.
       * 
       * @type {number}
       */
      delayOnSuccess: 2000,
      
      /**
       * Refresh search indices.
       * 
       * @sideeffect $scope.indices.loading Set to true if the operation is 
       *     confirmed, and the set to false once it completes.
       * 
       * @requires $scope.indices.delayOnSuccess
       * @requires $scope.indices.get()
       * @requires confirmModal
       * @requires infoModal
       * @requires MiscResource
       * @requires warningModal
       */
      refresh: function() {
        var t = 'refresh-search-indices'; // Template name (to shorten code).
        confirmModal.open(t).result.then(function() {
          $scope.indices.loading = true;
          MiscResource.get({ item: 'refreshSearchIndices' }, function() {
            $timeout(function() {
              infoModal.open(t + '-success').result.then(function() {
                $scope.indices.loading = false;
                $scope.indices.get();            
              });
            }, $scope.indices.delayOnSuccess);
          }, function() {
            warningModal.open(t + '-failed').result.then(function() {
              $scope.indices.loading = false;
              $scope.indices.get();
            });
          });
        });
      },

      /**
       * Gets search indices.
       * 
       * @sideeffect $scope.indices.resource Value returned from `MiscResource`
       *     is attached here.
       * 
       * @requires MiscResource
       */
      get: function() {
        $scope.indices.resource = MiscResource.get({ item: 'searchIndices' });
      }  
    };

    /**
     * Website notice related properties/methods.
     */
    $scope.notice = {
      /**
       * Loading flag.
       * 
       * @type {boolean}
       */
      loading: false,

      /**
       * Stores value returned from `$scope.notice.update()`.
       * 
       * @type {Angular resource}
       */
      resource: {},

      /**
       * Updates website notice.
       * 
       * @sideeffect $scope.notice.loading Set to true if the operation is 
       *     confirmed, and then set to false once it completes.
       * 
       * @requires $scope._location.reload()
       */
      update: function() {
        // Alias.
        var notice = $scope.notice.resource.websiteNotice;
        
        var t = 'update-website-notice'; // Template name (to shorten code).
        confirmModal.open(t).result.then(function() {
          $scope.notice.loading = true;
          SettingResource.update(notice, function() {
            infoModal.open(t + '-success').result.then(function() {
              $scope._location.reload();
            });
          }, function() {
            warningModal.open(t + '-failed').result.then(function() {
              $scope._location.reload();
            });
          });
        });
      },

      /**
       * Get website notice.
       * 
       * @sideeffect $scope.notice.resource Value returned from 
       *     `SettingResource` is attached here.
       * 
       * @requires SettingResource
       */
      get: function() {
        $scope.notice.resource = SettingResource.get({ name: 'websiteNotice' });
      }
    };

    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */

    $scope.indices.get();
    $scope.notice.get();
  }
]);
