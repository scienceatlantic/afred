'use strict';

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
      state('search.q', {
        url: '/q/:q',
        templateUrl: 'views/search.results.html',
        controller: 'SearchResultsController'
      }).
      state('createFacility', {
        url: '/facilities/create',
        templateUrl: 'views/facility-form.html',
        controller: 'FacilityFormController'
      }).
      state('editFacility', {
        url: '/facilities/:facilityId/edit',
        templateUrl: 'views/facility-form.html',
        controller: 'FacilityFormController'
      }).
      state('facility', {
        url: '/facilities/:facilityId',
        templateUrl: 'views/facility.html',
        controller: 'FacilityController'
      }).          
      state('equipment', {
        url: '/facilities/:facilityId/equipment/:equipmentId',
        templateUrl: 'views/equipment.html',
        controller: 'EquipmentController'
      }).
      state('about', {
        url: '/about',
        templateUrl: 'views/about.html'
      }).
      state('admin', {
        url: '/admin',
        templateUrl: 'views/admin.html',
        controller: 'AdminController'
      }).
      state('login', {
        url: '/login',
        templateUrl: 'views/login.html',
        controller: 'LoginController'
      });
  }
]);