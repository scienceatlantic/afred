'use strict';

angular.module('afredApp').config(['$stateProvider', '$urlRouterProvider',
  function($stateProvider, $urlRouterProvider, $rootScope) {
    // Code: http://stackoverflow.com/questions/26181141/angularjs-ui-router-otherwise-to-state-instead-of-url
    $urlRouterProvider.otherwise(function($injector, $location) {
      $injector.invoke(['$state', function($state) {
        $state.go('404');
      }]);
    });
    
    // Redirect to search if user is on root. Otherwise the user will be shown
    // the 404 page.
    $urlRouterProvider.when('', 'search');
    
    $stateProvider
      .state('search', {
        url: '/search',
        templateUrl: 'views/search.html',
        controller: 'SearchController'
      }).
      state('search.all', {
        // This line is passed 80 chars, but I'm not breaking it up.
        url: '/all?type&provinceId[]&organizationId[]&disciplineId[]&sectorId[]',
        templateUrl: 'views/search.results.html',
        controller: 'SearchResultsController'
      }).
      state('search.q', {
        url: '/?q&type&provinceId[]&organizationId[]&disciplineId[]&sectorId[]',
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
      state('facilities.show.equipment', {
        'abstract': true,
        'url': '/equipment',
        template: '<div data-ui-view></div>'
      }).
      state('facilities.show.equipment.show', {
        url: '/:equipmentId'
      }).
      state('about', {
        url: '/about',
        templateUrl: 'views/about.html',
        controller: 'AboutController'
      }).
      state('admin', {
        'abstract': true,
        url: '/admin',
        templateUrl: 'views/admin.html',
        resolve: {
          ping: ['$rootScope', '$state', function($rootScope, $state) {
            return $rootScope._auth.ping().then(function(response) {
              if (!$rootScope._auth.save(response)) {
                $rootScope._state.go('login', { redirect: location.href }); 
              }
            }, function() {
              $rootScope._httpError(response);
            });
          }]
        }
      }).
      state('admin.account', {
        url: '/account',
        templateUrl: 'views/admin.account.html'
      }).
      state('admin.dashboard', {
        url: '/dashboard',
        templateUrl: 'views/admin.dashboard.html',
        controller: 'AdminDashboardController'
      }).
      state('admin.facilities', {
        url: '/facilities',
        templateUrl: 'views/admin.facilities.html',
        controller: 'AdminFacilitiesController'
      }).
      state('admin.facilities.index', {
        url: '/index/?state&visibility&page',
        templateUrl: 'views/admin.facilities.index.html',
        controller: 'AdminFacilitiesIndexController'
      }).
      state('admin.facilities.show', {
        url: '/show?facilityRepositoryId',
        templateUrl: 'views/admin.facilities.show.html',
        controller: 'AdminFacilitiesShowController'
      }).
      state('admin.organizations', {
        'abstract': true,
        url: '/organizations',
        templateUrl: 'views/admin.organizations.html',
        controller: 'AdminOrganizationsController'
      }).
      state('admin.organizations.index', {
        url: '/index?page',
        templateUrl: 'views/admin.organizations.index.html',
        controller: 'AdminOrganizationsIndexController'        
      }).
      state('admin.organizations.create', {
        url: '/create',
        templateUrl: 'views/admin.organizations.create.html',
        controller: 'AdminOrganizationsCreateController'
      }).
      state('admin.organizations.show', {
        url: '/:organizationId',
        templateUrl: 'views/admin.organizations.show.html',
        controller: 'AdminOrganizationsShowController'
      }).
      state('admin.provinces', {
        url: '/provinces',
        templateUrl: 'views/admin.provinces.html',
        controller: 'AdminProvincesController'
      }).
      state('admin.provinces.show', {
        url: '/:provinceId',
        templateUrl: 'views/admin.provinces.show.html',
        controller: 'AdminProvincesShowController'
      }).
      state('login', {
        url: '/login?redirect',
        templateUrl: 'views/login.html',
        controller: 'LoginController',
        resolve: {
          ping: ['$rootScope', '$state', function($rootScope, $state) {
            return $rootScope._auth.ping().then(function(response) {
              if ($rootScope._auth.save(response)) {  
                $rootScope._state.go('admin.dashboard');
              }
            }, function() {
              $rootScope._httpError(response);
            });
          }]
        }
      }).
      state('404', {
        templateUrl: 'views/error.html'
      }).
      state('500', {
        templateUrl: 'views/error.html'
      });
  }
]);