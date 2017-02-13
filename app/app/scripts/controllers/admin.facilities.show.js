'use strict';

angular.module('afredApp').controller('AdminFacilitiesShowController', [
  '$scope',
  'confirmModal',
  'FacilityResource',
  'infoModal',
  'warningModal',
  'RepositoryResource',
  'Repository',
  function($scope,
           confirmModal,
           FacilityResource,
           infoModal,
           warningModal,
           RepositoryResource,
           Repository) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * Get facility repository record.
     * 
     * @sideeffect $scope.facility Facility data attached here.
     * @sideeffect $scope.fr Facility repository data attached here.
     * 
     * @requires $scope._stateParams.facilityRepositoryId
     * @requires Repository
     */
    $scope.getFr = function() {
      $scope.fr = Repository.get($scope._stateParams.facilityRepositoryId);
      $scope.fr.$promise.then(function() {
        $scope.facility = Repository.getFacility($scope.fr);
      });
    };

    /**
     * Approve a facility.
     *
     * @sideffect $scope.loading.approve Set to true at the start of the 
     *     function and set to false when the AJAX call is complete.
     *
     * @requires $scope.fr
     * @requires $scope.getFr() Called if the AJAX operation was successful.
     * @requires confirmModal
     * @requires infoModal
     * @requires warningModal
     */
    $scope.approve = function() {
      var t = 'approve-facility'; // Template name (to shorten code).
      confirmModal.open(t).result.then(function() {
        $scope.loading.approve = true;
        $scope.fr.state = 'PUBLISHED';
        $scope.fr.$approve(function() {
          infoModal.open(t + '-success').result.then(function() {
            $scope.loading.approve = false;
            $scope.getFr();
          });
        }, function() {
          warningModal.open(t + '-failed').result.then(function() {
            $scope.loading.approve = false;
          });
        });
      });
    };
    
    /**
     * Reject a facility record.
     *
     * @sideeffect $scope.loading.reject Set to true at the start of the 
     *     function and then set to false after the AJAX operation is complete.
     *
     * @requires $scope.fr
     * @requires $scope.getFr() Called if the AJAX operation was successful.
     * @requires confirmModal
     * @requires infoModal
     * @requires warningModal
     */
    $scope.reject = function() {
      var t = 'reject-facility'; // Template name (to shorten code).
      confirmModal.open(t).result.then(function() {
        $scope.loading.reject = true;
        $scope.fr.state = 'REJECTED';
        $scope.fr.$reject(function() {
          infoModal.open(t + '-success').result.then(function() {            
            $scope.loading.reject = false;
            $scope.getFr();
          });
        }, function() {
          warningModal.open(t + '-failed').result.then(function() {
            $scope.loading.reject = false;
          });
        });           
      });
    };
    
    /**
     * Approve a facility edit.
     *
     * @sideeffect $scope.loading.approveEdit Set to true at the start of the
     *     function and set to false when the AJAX call is complete.
     *
     * @requires $scope.fr
     * @requires $scope.getFr() Called if the AJAX operation was successful.
     * @requires confirmModal
     * @requires infoModal
     * @requires warningModal
     */
    $scope.approveEdit = function() {
      var t = 'approve-facility-edit'; // Template name (to shorten code).
      confirmModal.open(t).result.then(function() {
        $scope.loading.approveEdit = true;
        $scope.fr.state = 'PUBLISHED_EDIT';
        $scope.fr.$approveEdit(function() {
          infoModal.open(t + '-success').result.then(function() {
            $scope.loading.approveEdit = false;
            $scope.getFr();
          });
        }, function() {
          warningModal.open(t + '-failed').result.then(function() {
            $scope.loading.approveEdit = false;
          });
        });        
      });
    };
    
    /**
     * Reject a facility edit.
     *
     * @sideffect $scope.loading.rejectEdit Set to true at the start of the
     *     function and set to false when the AJAX call is complete.
     *
     * @requires $scope.fr
     * @requires $scope.getFr() Called if the AJAX operation was successful.
     * @requires confirmModal
     * @requires infoModal
     * @requires warningModal
     */
    $scope.rejectEdit = function() {
      var t = 'reject-facility-edit'; // Template name (to shorten code).
      confirmModal.open(t).result.then(function() {
        $scope.loading.rejectEdit = true;
        $scope.fr.state = 'REJECTED_EDIT';
        $scope.fr.$rejectEdit(function() {
          infoModal.open(t + '-success').result.then(function() {
            $scope.loading.rejectEdit = false;
            $scope.getFr();
          });
        }, function() {
          warningModal.open(t + '-failed').result.then(function() {
            $scope.loading.rejectEdit = false;
          });
        });        
      });    
    };
    
    /**
     * Hides a facility (does not appear in search results).
     *
     * @sideeffect $scope.loading.hide Set to true at the start of the function
     *     and then set to false after the AJAX operation is complete.
     * 
     * @requires $scope.fr
     * @requires $scope.getFr() Called if the AJAX operation was successful.
     * @requires confirmModal
     * @requires infoModal
     * @requires warningModal
     */
    $scope.hide = function() {
      var t = 'hide-facility';
      confirmModal.open(t).result.then(function() {
        $scope.loading.hide = true;
        FacilityResource.update({ facilityId: $scope.fr.facilityId }, {
          isPublic: 0
        }, function() {
          infoModal.open(t + '-success').result.then(function() {
            $scope.loading.hide = false;
            $scope.getFr();
          });
        }, function() {
          warningModal.open(t + '-failed').result.then(function() {
            $scope.loading.hide = false;
          });
        });  
      });
    };
    
    /**
     * Unhides a facility (appears in search results).
     *
     * @sideffect $scope.loading.unhide Set to true at the start of the function
     *     and then set to false after the AJAX operation is complete.
     *
     * @requires $scope.fr
     * @requires $scope.getFr() Called if the AJAX operation was successful.
     * @requires confirmModal
     * @requires infoModal
     * @requires warningModal     
     */
    $scope.unhide = function() {
      var t = 'unhide-facility';
      confirmModal.open(t).result.then(function() {
        $scope.loading.unhide = true;
        FacilityResource.update({
          facilityId: $scope.fr.facilityId
        }, {
          isPublic: 1
        }, function() {
          infoModal.open(t + '-success').result.then(function() {
            $scope.loading.unhide = false;
            $scope.getFr();
          });
        }, function() {
          warningModal.open(t + '-failed').result.then(function() {
            $scope.loading.unhide = false;
          });
        });  
      });      
    };
    
    /**
     * Generates an update request and redirects the user to the edit form.
     *
     * @sideffect $scope.loading.edt Set to true at the start of the function
     *     and then set to false when the AJAX operation is complete.
     *
     * @requires $scope._auth.user.email
     * @requires $scope.fr
     * @requires confirmModal
     * @requires infoModal
     * @requires RepositoryResource
     * @requires warningModal
     */
    $scope.edit = function() {
      var t = 'edit-facility';
      
      // If the facility has an open/pending update request, prevent the user
      // from opening a new one.
      if ($scope.fr.isBeingUpdated === 1) {
        warningModal.open(t + '-not-allowed');
        return;
      }
      
      confirmModal.open(t).result.then(function() {
        $scope.loading.edit = true;
        RepositoryResource.createToken({
          isAdmin: 1,
          email: $scope._auth.user.email,
          facilityId: $scope.fr.facilityId
        }, null, function(response) {
          infoModal.open(t + '-success').result.then(function() {
            $scope.loading.edit = false;
            $scope._state.go('facilities.form.edit', {
              facilityRepositoryId: $scope.fr.id,
              token: response.token
            });            
          });        
        }, function() {
          warningModal.open(t + '-failed').result.then(function() {
            $scope.loading.edit = false;
          });
        });        
      })
    };
    
    /**
     * Deletes a published facility.
     *
     * @sideeffect $scope.loading.remove Set to true at the start of the
     *     function and the set to false when the AJAX operation is complete.
     *
     * @requires $scope.fr
     * @requires $scope.getFr()
     * @requires confirmModal
     * @requires FacilityResource
     * @requires infoModal
     * @requires warningModal
     */
    $scope.remove = function() {
      var t = 'delete-facility';
      
      // If the facility has an open/pending update request, prevent the user
      // from deleting the facility.
      if ($scope.fr.isBeingUpdated === 1) {
        warningModal.open(t + '-not-allowed');
        return;
      }
      
      confirmModal.open(t).result.then(function() {
        $scope.loading.remove = true;
        FacilityResource.remove({
          facilityId: $scope.fr.facilityId
        }, null, function() {
          infoModal.open(t + '-success').result.then(function() {
            $scope.loading.remove = false;
            $scope.getFr();
          });
        }, function() {
          warningModal.open(t + '-failed').result.then(function() {
            $scope.loading.remove = false;
          });
        });        
      });
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    /**
     * Facility repository object.
     *
     * @type {Angular resource}
     */
    $scope.fr = {};
    
    /**
     * Formatted facility data.
     *
     * @type {object}
     */
    $scope.facility = {};
    
    /**
     * Loading flags.
     * 
     * @type {object}
     */
    $scope.loading = {
      approve: false,
      approveEdit: false,
      reject: false,
      rejectEdit: false
    };

    // Get facility repository record.
    $scope.getFr();
  }
]);
