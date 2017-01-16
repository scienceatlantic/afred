'use strict';

/**
 * @fileoverview Instantiate app with required modules.
 * @author Prasad Rajandran (prasad@scienceatlantic.ca)
 * @see https://docs.angularjs.org/api/ng/function/angular.module
 */

angular.module('afredApp', [
  'ngResource',
  'ngSanitize',
  'ui.router',
  'ui.bootstrap',
  'angularUtils.directives.dirPagination',
  'angular-bind-html-compile',
  'textAngular'
]);
