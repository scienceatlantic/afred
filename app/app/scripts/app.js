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
  'textAngular'
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
          templateMode: function() { return { all: true, query: false }; }
        }
      }).
      state('search.query', {
        url: '/query/:query',
        templateUrl: 'views/search.results.html',
        controller: 'SearchResultsController',
        resolve: {
          templateMode: function() { return {all: false, query: true}; }
        }
      }).
      state('facility-create', {
        url: '/facility/create',
        templateUrl: 'views/facility-form.html',
        controller: 'FacilityFormController',
        resolve: {
          templateMode: function() { return { create: true, edit: false }; }
        }
      }).
      state('facility-edit', {
        url: 'facility/:facilityId/edit',
        templateUrl: 'views/facility-form.html',
        controller: 'FacilityFormController',
        resolve: {
          templateMode: function() { return { create: false, edit: true }; }
        }
      }).
      state('facility', {
        url: '/facility/:facilityId',
        templateUrl: 'views/facility.html',
        controller: 'FacilityController',
        resolve: {
          templateMode: function() { return { facility: true, equipment: false }; }
        }
      }).          
      state('facility-equipment', {
        url: '/facility/:facilityId/equipment/:equipmentId',
        templateUrl: 'views/facility.html',
        controller: 'FacilityController',
        resolve: {
          templateMode: function() { return { facility: false, equipment: true }; }
        }
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