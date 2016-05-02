'use strict';

angular.module('afredApp').controller('AdminOrganizationsShowController', [
  '$scope',
  'confirmModal',
  'infoModal',
  'warningModal',
  'organizationResource',
  'iloResource',
  function($scope,
           confirmModal,
           infoModal,
           warningModal,
           organizationResource,
           iloResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * Update organization instance. The '$scope.commit()' function is called
     * if the AJAX operation was successful, otherwise the '$scope.rollback()'
     * function is called instead.
     *
     * Side effects:
     * $scope.loading.update Is set to true at the start of the function and
     *     then is set to false after the AJAX operation is complete.
     *
     * Calls/uses/requires:
     * $scope.organization
     * $scope.commit() 
     * $scope.rollback()
     * confirmModal
     * infoModal
     * warningModal
     *
     * @param {Angular FormController} formCtrl The '$setPristine()' function is
     *     called after the AJAX operation is complete (regardless of whether it
     *     failed or not). Will not be called if the user hits the cancel
     *     button.
     */
    $scope.update = function(formCtrl) {
      var t = 'update-organization'; // Template name (to shorten code).
      confirmModal.open(t).result.then(function() {
        $scope.loading.update = true;
        $scope.organization.$update(function(response) {
          infoModal.open(t + '-success').result.then(function() {
            $scope.commit();
            formCtrl.$setPristine();
            $scope.loading.update = false;            
          });
        }, function() {
          warningModal.open(t + '-failed').result.then(function() {
            $scope.rollback();
            formCtrl.$setPristine();
            $scope.loading.update = false;
          });
        });
      });
    };
    
    /**
     * Delete the organization instance.
     * Note: If the operation is successful, user will be redirected to
     * 'admin.organization.index'.
     *
     * Side effects:
     * $scope.loading.remove Is set to true at the start of the function and
     *     then is set to false after the AJAX operation has completed.
     *
     * Calls/uses/requires:
     * $scope.organization
     * $scope._state.go()
     * confirmModal
     * infoModal
     * warningModal
     */
    $scope.remove = function() {
      var t = 'delete-organization'; // Template name (to shorten code).
      confirmModal.open(t).result.then(function() {
        $scope.loading.remove = true;
        $scope.organization.$delete(function() {
          infoModal.open(t + '-success').result.then(function() {
            $scope.loading.remove = false;
            $scope._state.go('admin.organizations.index');
          });
        }, function() {
          warningModal.open(t + '-failed');
          $scope.loading.remove = false;
        });
      });
    };
    
    /**
     * A copy of '$scope.organization' is made and stored in
     * '$scope.organizationCopy'.
     *
     * Side effects:
     * $scope.organizationCopy
     */
    $scope.commit = function() {
      $scope.organizationCopy = angular.copy($scope.organization);
    };
    
    /**
     * A copy of '$scope.organizationCopy' is made and stored in
     * '$scope.organization'.
     *
     * Side effects:
     * $scope.organization
     */
    $scope.rollback = function() {
      $scope.organization = angular.copy($scope.organizationCopy);
    };
    
    /**
     * Creates a new 'iloResource' instance.
     *
     * Side effects:
     * $scope.ilo Instance is stored here.
     * $scope.ilo.organizationId Is set to '$scope.organization.id'.
     */
    $scope.newIlo = function() {
      $scope.ilo = new iloResource();
      $scope.ilo.organizationId = $scope.organization.id;
    };
    
    /**
     * Retrieves the ILO from the database.
     * 
     * Side effects:
     * $scope.ilo Resource returned is stored here.
     * $scope.iloCopy A copy of the resource is stored here.
     * 
     * Calls/uses/requires:
     * iloResource
     * $scope.organization.ilo.id
     * $scope._httpError() If the operation fails, the response is passed to
     *     this.
     * angular.copy()
     */ 
    $scope.getIlo = function() {
      $scope.ilo = iloResource.get({
        iloId: $scope.organization.ilo.id
      }, function() {
        $scope.iloCopy = angular.copy($scope.ilo);
      }, function(response) {
        $scope._httpError(response);
      });
    };
    
    /**
     * Creates a new ILO record.
     *
     * Side effects:
     * $scope.loading.createIlo Set to true at the start of the function and
     *     then set to false at the end of the AJAX operation.
     *
     * Calls/uses/requires:
     * $scope.ilo.$save()
     * confirmModal
     * infoModal
     * warningModal
     *
     * @param {Angular FormController} formCtrl The '$setPristine()' function is
     *     called after the AJAX operation is complete and was successuful.
     */
    $scope.createIlo = function(formCtrl) {
      // This if condition prevents the function from executing if the ILO
      // already exists (i.e. the '$scope.updateIlo()' function should be called
      // instead.)
      if ($scope.ilo && !$scope.ilo.id) {
        var t = 'create-ilo'; // Template name (to shorten code).
        confirmModal.open(t).result.then(function() {
          $scope.loading.createIlo = true;
          $scope.ilo.$save(function() {
            infoModal.open(t + '-success').result.then(function() {
              formCtrl.$setPristine();
              $scope.loading.createIlo = false;
            }, function() {
              warningModal.open(t + '-failed').result.then(function() {
                $scope.loading.createIlo = false;
              });
            });
          });
        });
      }
    };
    
    /**
     * Update ILO instance.
     *
     * Side effects:
     * $scope.loading.updateIlo Is set to true at the start of the function and
     *     then is set to false after the AJAX operation is complete.
     *
     * Calls/uses/requires:
     * $scope.ilo.$update()
     * $scope.commit() Called if the AJAX operation was successful.
     * $scope.rollback() Called if the AJAX operation failed.
     * confirmModal
     * infoModal
     * warningModal
     *
     * @param {Angular FormController} formCtrl The '$setPristine()' function is
     *     called after the AJAX operation is complete (regardless of whether it
     *     failed or not). Will not be called if the user hits the cancel
     *     button.
     */
    $scope.updateIlo = function(formCtrl) {
      // This if condition prevents the operation from executing if the ILO
      // does not already exist (i.e. the '$scope.createIlo()' function should
      // be called instead).
      if ($scope.ilo && $scope.ilo.id) {
        var t = 'update-ilo'; // Template name (to shorten code).
        confirmModal.open(t).result.then(function() {
        $scope.loading.updateIlo = true;
          $scope.ilo.$update(function() {
            infoModal.open(t + '-success').result.then(function() {
              $scope.commitIlo();
              formCtrl.$setPristine();
              $scope.loading.updateIlo = false;
            });
          }, function() {
            warningModal.open(t + '-failed').result.then(function() {
              $scope.rollbackIlo();
              formCtrl.$setPristine();
              $scope.loading.updateIlo = false;
            });
          });
        });
      }
    };
    
    /**
     * Delete the ilo instance.
     *
     * Side effects:
     * $scope.loading.removeIlo Is set to true at the start of the function and
     *     then is set to false after the AJAX operation has completed.
     *
     * Calls/uses/requires:
     * $scope.ilo.$delete()
     * $scope.newIlo() Called if the AJAX was successful.
     * confirmModal
     * infoModal
     * warningModal
     */
    $scope.removeIlo = function() {
      if ($scope.ilo && $scope.ilo.id) {
        var t = 'delete-ilo'; // Template name (to shorten code).
        confirmModal.open(t).result.then(function() {
          $scope.loading.removeIlo = true;
          $scope.ilo.$delete(function() {
            infoModal.open(t + '-success').result.then(function() {
              $scope.newIlo();
              $scope.loading.removeIlo = false;
            });
          }, function() {
            warningModal.open(t + '-failed');
            $scope.loading.removeIlo = false;
          });
        });
      }
    };    
    
    /**
     * A copy of '$scope.ilo' is made and stored in '$scope.iloCopy'.
     * 
     * Side effects:
     * $scope.iloCopy
     */
    $scope.commitIlo = function() {
      $scope.iloCopy = angular.copy($scope.ilo);
    };
    
    /**
     * A copy of '$scope.iloCopy' is made and stored in '$scope.ilo'.
     *
     * Side effects:
     * $scope.ilo
     */
    $scope.rollbackIlo = function() {
      $scope.ilo = angular.copy($scope.iloCopy);
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    /**
     * Holds the organization resource. If the operation fails, redirect
     * to error state.
     *
     * Side effects:
     * $scope.organizationCopy A copy of the resource is stored here.
     * 
     * Uses/calls/requires:
     * angular.copy()
     * $scope._httpError()
     * $scope.getIlo() Called if the Organization instance returned does not
     *     have an ILO.
     * $scope.newIlo() Called if the organization instance returned already has
     *     have an ILO.
     * 
     * @type {Angular resource}
     */
    $scope.organization = organizationResource.get($scope._stateParams,
      function() {
        $scope.organizationCopy = angular.copy($scope.organization);
        
        if ($scope.organization.ilo) {
          $scope.getIlo();
        } else {
          $scope.newIlo();
        }
      },
      function(response) {
        $scope._httpError(response);
      }
    );
    
    /**
     * Holds the ILO resource returned from '$scope.getIlo()'.
     * 
     * @type {Angular resource}
     */
    $scope.ilo = {};
    
    /**
     * AJAX loading flags.
     * 
     * @type {object}
     */
    $scope.loading = {
      update: false, // Update organization operation.
      remove: false, // Remove organization operation.
      createIlo: false, // Create ILO operation.
      updateIlo: false, // Update ILO operation.
      removeIlo: false // Remove ILO operation.
    };
    
    /**
     * Stores a copy of '$scope.organization' in case the update operation
     * fails and we have to revert.
     * 
     * @type {Angular resource}
     */
    $scope.organizationCopy = null;
    
    /**
     * Stores a copy of '$scope.ilo' in case the update operation fails and we
     * have to revert.
     * 
     * @type {Angular resource}
     */
    $scope.iloCopy = null;
  }
]);
