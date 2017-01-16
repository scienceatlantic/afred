'use strict';

angular.module('afredApp').controller('FacilitiesFormCreateController',
  ['$interval',
   '$scope',
   '$timeout',
   'RepositoryResource',
  function($interval,
           $scope,
           $timeout,
           RepositoryResource) {    
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */    
    
    /**
     * Shows the preview.
     *
     * @sideeffect $scope.facility Data returned from 
     *    `$scope.form.formatForPreview()` is stored here.
     * @sideeffect $scope.loading.preview Set to true at the start of the 
     *     function and then set to false at the end.
     *  @sideeffect $scope.view.show Set to 'PREVIEW'.
     * 
     * @requires $scope.form.formatForPreview();
     * @requires $timeout()
     */
    $scope.preview = function() {
      $scope.loading.preview = true;
      $scope.facility = $scope.form.formatForPreview();
      $scope.view.show = 'PREVIEW';

      // Introduce an articial delay (1s).
      $timeout(function() {
        $scope.loading.preview = false;
        scrollTo(0, 300);
      }, 1000);
    };
    
    /**
     * Returns view to the form.
     *
     * @sideeffect $scope.view.show Set to 'FORM'.
     */
    $scope.goBack = function() {
      $scope.view.show = 'FORM';
      scrollTo(0, 0);
    };
    
    /**
     * Submits the form. A success message is shown if the operation was
     * successful otherwise an error message is shown instead.
     *
     * @sideffect $scope.fr Promised returned is stored here.
     * @sideffect $scope.view.show Set to 'SUCCESS_MESSAGE' if the operation was
     *     successful, otherwise it is set to 'FAILURE_MESSAGE'.
     *
     * @requires $scope.form.clearSave() Called if the operation was successful.
     * @requires $scope.form.formatForApi()
     * @requires RepositoryResource
     */
    $scope.submit = function() {
      $scope.fr = RepositoryResource.submit({
        data: $scope.form.formatForApi()
      }, function() {
        // Clear any saved data.
        $scope.form.clearSave(true);
        $scope.view.show = 'SUCCESS_MESSAGE';
      }, function() {
        $scope.view.show = 'FAILURE_MESSAGE';
      });
    };
    
    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    /**
     * Controls what is shown to the user.
     * 
     * @type {object} 
     */
    $scope.view = {
      show: 'FORM' //'FORM', 'PREVIEW', 'SUCCESS_MESSAGE', 'FAILURE_MESSAGE'
    };

    /**
     * Loading flags.
     * 
     * @type {object}
     */
    $scope.loading = {
      form: true,
      preview: false
    };
    
    /**
     * Will hold the data returned by `$scope.submit()`.
     * 
     * @type {promise}
     */
    $scope.fr = {};
    
    // Initialise the form.
    $scope.form.initialise().then(function() {
      $scope.form.startAutosave('facilities.form.create').then(function() {
        $scope.loading.form = false;
      });
    });
    
    // We're also setting the `reload` property of `$rootScope._persist` to true
    // if we're going to the 'facilities.form.edit' state. If we don't do a hard
    // reload, TextAngular will complain that ('Editor with name "..." already
    // exists'). This is because the parent state is not reloaded if we're only
    // switching between child states.
    $scope.$on('$stateChangeStart', function(event, toState) {
      $scope._persist.reload = (toState.name === 'facilities.form.edit');
    });
  }
]);
