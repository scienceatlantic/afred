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
  'ngCkeditor'
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
        controller: 'SearchResultsController'
      }).
      state('search.query', {
        url: '/query/:query',
        templateUrl: 'views/search.results.html',
        controller: 'SearchResultsController'
      }).
      state('createFacility', {
        url: '/facility/create',
        templateUrl: 'views/facility-form.html',
        controller: 'FacilityFormController'
      }).
      state('editFacility', {
        url: '/facility/:facilityId/edit',
        templateUrl: 'views/facility-form.html',
        controller: 'FacilityFormController'
      }).
      state('facility', {
        url: '/facility/:facilityId',
        templateUrl: 'views/facility.html',
        controller: 'FacilityController'
      }).          
      state('equipment', {
        url: '/facility/:facilityId/equipment/:equipmentId',
        templateUrl: 'views/facility.html',
        controller: 'FacilityController'
      }).
      state('about', {
        url: '/about',
        templateUrl: 'views/about.html'
      }).
      state('controlPanel', {
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