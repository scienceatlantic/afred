'use strict';

/**
 * @ngdoc overview
 * @name afredApp
 * @description
 * # afredApp
 *
 * Main module of the application.
 */
var afredApp = angular.module('afredApp', [
  'ngAnimate',
  'ngCookies',
  'ngResource',
  'ngSanitize',
  'ngTouch',
  'ui.router'
]);
  
afredApp.config(['$stateProvider', '$urlRouterProvider',
  function($stateProvider, $urlRouterProvider) {
    $urlRouterProvider.otherwise('/search');
    
    $stateProvider
      .state('search', {
        url: '/search',
        templateUrl: 'views/search.html',
        controller: 'searchController'
      }).
      state('submission', {
        url: '/submission',
        templateUrl: 'views/submission.html',
        controller: 'submissionController'
      }).
      state('about', {
        url: '/about',
        templateUrl: 'views/about.html'
      });
  }
]);
