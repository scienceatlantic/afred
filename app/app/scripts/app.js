'use strict';

/**
 * @fileoverview Instantiate app with required modules.
 * @author Prasad Rajandran (prasad@scienceatlantic.ca)
 * @see https://docs.angularjs.org/api/ng/type/angular.Module
 */

angular.module('afredApp', [
  'ngAnimate',
  'ngCookies',
  'ngResource',
  'ngSanitize',
  'ngTouch',
  'ui.router',
  'ui.bootstrap',
  'angularUtils.directives.dirPagination',
  'textAngular',
  'ta-maxlength'
]);
