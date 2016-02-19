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
        'abstract': true,
        url: '/facilities',
        templateUrl: 'views/facilities.html'
      }).
      state('facilities.update', {
        url: '/update',
        templateUrl: 'views/facilities.update.html',
        controller: 'FacilitiesUpdateController'
      }).
      state('facilities.form', {
        'abstract': true,
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
        url: '/:facilityRepositoryId/edit?token',
        templateUrl: 'views/facilities.form.edit.html',
        controller: 'FacilitiesFormEditController'
      }).
      state('facilities.show', {
        url: '/:facilityId',
        templateUrl: 'views/facilities.show.html',
        controller: 'FacilitiesShowController'
      }).
      state('equipment', {
        'abstract': true,
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
        'abstract': true,
        url: '/admin',
        templateUrl: 'views/admin.html',
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
      state('admin.facilities', {
        url: '/facilities?state',
        templateUrl: 'views/admin.facilities.html',
        controller: 'AdminFacilitiesController'
      }).
      state('admin.facilityRepository', {
        'abstract': true,
        url: '/facility-repository',
        template: '<div data-ui-view></div>'
      }).
      state('admin.facilityRepository.show', {
        url: '/:facilityRepositoryId',
        templateUrl: 'views/admin.facility-repository.show.html',
        controller: 'AdminFacilityRepositoryShowController'
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