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
      state('facilities', {
        abstract: true,
        url: '/facilities',
        templateUrl: 'views/facilities.html'
      }).
      state('facilities.form', {
        abstract: true,
        url: '/form',
        template: '<div data-ui-view></div>',
        controller: 'FacilitiesFormController'
      }).
      state('facilities.form.create', {
        url: '/create',
        templateUrl: 'views/facilities.form.create.html',
        controller: 'FacilitiesFormCreateController'
      }).
      state('facilities.form.edit', {
        url: '/:facilityId/edit',
        templateUrl: 'views/facilities.form.edit.html',
        controller: 'FacilitiesFormEditController'
      }).
      state('facilities.show', {
        url: '/:facilityId',
        templateUrl: 'views/facilities.show.html',
        controller: 'FacilitiesShowController'
      }).
      state('equipment', {
        abstract: true,
        'url': '/equipment',
        template: '<div data-ui-view></div>'
      }).
      state('equipment.show', {
        url: '/:facilityId/equipment/:equipmentId',
        templateUrl: 'views/equipment.html',
        controller: 'EquipmentController'
      }).
      state('about', {
        url: '/about',
        templateUrl: 'views/about.html'
      }).
      state('admin', {
        abstract: true,
        url: '/admin',
        template: '<div data-ui-view><div>',
        resolve: {
          ping: ['$rootScope', '$state', function($rootScope, $state) {
            return $rootScope._auth.ping().then(function(response) {
              $rootScope._auth.user = response.data;
            }, function() {
              $state.go('login');
            });
          }]
        }
      }).
      state('admin.dashboard', {
        url: '/dashboard',
        templateUrl: 'views/admin.dashboard.html',
        controller: 'AdminDashboardController'
      }).
      state('admin.facilityRevisionHistory', {
        abstract: true,
        url: '/facility-revision-history',
        template: '<div data-ui-view></div>'
      }).
      state('admin.facilityRevisionHistory.show', {
        url: '/:facilityRevisionHistoryId',
        templateUrl: 'views/admin.facility-revision-history.show.html',
        controller: 'AdminFacilityRevisionHistoryShowController'
      }).
      state('login', {
        url: '/login',
        templateUrl: 'views/login.html',
        controller: 'LoginController',
        resolve: {
          ping: ['$rootScope', '$state', function($rootScope, $state) {
            return $rootScope._auth.ping().then(function(response) {
              $state.go('admin.dashboard');
            }, function() {
              // Error function has to be defined in order for the login
              // page to render.
            });
          }]
        }
      });
  }
]);