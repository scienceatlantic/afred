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
  'ui.bootstrap'
]);
  
angular.module('afredApp').config(['$stateProvider', '$urlRouterProvider',
  function($stateProvider, $urlRouterProvider) {
    $urlRouterProvider.otherwise('/search');
    
    $stateProvider
      .state('search', {
        url: '/search',
        templateUrl: 'views/search.html',
        controller: 'SearchController'
      }).
      state('search.all', {
        url: '/all',
        templateUrl: 'views/search.results.html',
        controller: 'SearchResultsController',
        resolve: {
          mode: function() { return 'all'; }
        }
      }).
      state('search.query', {
        url: '/query/:query',
        templateUrl: 'views/search.results.html',
        controller: 'SearchResultsController',
        resolve: {
          mode: function() { return 'query'; }
        }
      }).
      state('submit-a-record', {
        url: '/submit-a-record',
        templateUrl: 'views/submit-a-record.html',
        controller: 'SubmitARecordController'
      }).
      state('about', {
        url: '/about',
        templateUrl: 'views/about.html'
      }).
      state('control-panel', {
        url: '/control-panel',
        templateUrl: 'views/control-panel.html',
        controller: 'ControlPanelController'
      }).
      state('login', {
        url: '/login',
        templateUrl: 'views/login.html',
        controller: 'LoginController'
      });
  }
]);