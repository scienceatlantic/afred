'use strict';

angular.module('afredApp').controller('AdminOrganizationsCreateController', [
  '$scope',
  'confirmModal',
  'infoModal',
  'warningModal',
  'OrganizationResource',
  function($scope,
           confirmModal,
           infoModal,
           warningModal,
           OrganizationResource) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * Creates a new organization record. If the operation was successful,
     * the user is redirected to the 'admin.organizations.show' state of the
     * newly created record.
     *
     * Side effects:
     * $scope.loading.update Is set to true at the start of the function and
     *     then is set to false after the AJAX operation is complete.
     *
     * Calls/uses/requires:
     * $scope.organization
     * $scope._state.go()
     * confirmModal
     * infoModal
     * warningModal
     */
    $scope.create = function() {
      $scope.loading.create = true;
      var t = 'create-organization'; // Template name (to shorten code).
      
      confirmModal.open(t).result.then(function() {
        $scope.organization.$save(function(response) {
          infoModal.open(t + '-success').result.then(function() {
            $scope._state.go('admin.organizations.show', {
              organizationId: response.id
            });
          });
        }, function() {
          $scope.loading.create = false;
          warningModal.open(t + '-failed');
        });        
      }, function() {
        // User hits the cancel button...
        $scope.loading.create = false;
      });
    };
    
    /**
     * Clear the form.
     *
     * Side effects:
     * $scope.organization 'name' and 'isHidden' property is set to null.
     *
     * Uses/calls/requires:
     * confirmModal
     *
     * @param {Angular FormController} formCtrl '$setPristine()' method is
     *     is called if the user confirms the action.
     */
    $scope.clear = function(formCtrl) {
      var t = 'clear-create-organization-form'; // Template name (shorten code).
      
      confirmModal.open(t).result.then(function() {
        $scope.organization.name = null;
        $scope.organization.isHidden = null;
        formCtrl.$setPristine();        
      }, function() {
        // User hits cancel button, do nothing.
      });
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    /**
     * Holds the organization resource.
     * 
     * Uses/calls/requires:
     * OrganizationResource()
     * 
     * @type {Angular resource}
     */
    $scope.organization = new OrganizationResource();
    
    /**
     * AJAX loading flags.
     * 
     * @type {object}
     */
    $scope.loading = {
      create: false // Create organization.
    };
  }
]);
