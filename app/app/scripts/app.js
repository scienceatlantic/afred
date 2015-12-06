'use strict';

/**
 * @ngdoc overview
 * @name afredApp
 * @description
 * # afredApp
 *
 * Main module of the application.
 */
angular.module('afredApp', [
  'ngAnimate',
  'ngCookies',
  'ngResource',
  'ngSanitize',
  'ngTouch',
  'ui.router',
  'ui.bootstrap',
  'angularUtils.directives.dirPagination'
]);

angular.module('afredApp').config(['$httpProvider', function($httpProvider) {
    $httpProvider.defaults.withCredentials = true;
  }
]);