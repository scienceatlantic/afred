'use strict';

/**
 * @fileoverview Router.
 * @see https://github.com/angular-ui/ui-router/wiki
 */

angular.module('afredApp').config(['$stateProvider', '$urlRouterProvider',
  function($stateProvider, $urlRouterProvider) {
    // If page (state) not found, show 404.
    // @see http://stackoverflow.com/questions/26181141/angularjs-ui-router-otherwise-to-state-instead-of-url
    $urlRouterProvider.otherwise(function($injector) {
      $injector.invoke(['$state', function($state) {
        $state.go('error.404');
      }]);
    });
    
    // Redirect to home if user is on root. Otherwise the user will see 404.
    // Commenting this line out because we're moving the app to the root and the
    // root still has to redirect to the landing page.
    //$urlRouterProvider.when('/', 'home');
    
    // Routes.
    $stateProvider
      .state('home', {
        url: '/home',
        templateUrl: 'views/home.html',
        controller: 'HomeController'
      }).
      state('search', {
        url: '/search',
        templateUrl: 'views/search.html',
        controller: 'SearchController'
      }).
      state('search.all', {
        url: '/all?page&type&provinceId[]&organizationId[]&disciplineId[]&sectorId[]',
        templateUrl: 'views/search.results.html',
        controller: 'SearchResultsController'
      }).
      state('search.q', {
        url: '/?q&page&type&provinceId[]&organizationId[]&disciplineId[]&sectorId[]',
        templateUrl: 'views/search.results.html',
        controller: 'SearchResultsController'
      }).
      state('facilities', {
        'abstract': true,
        url: '/facilities',
        template: '<div data-ui-view></div>'
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
      state('facilities.form.guide', {
        url: '/guide',
        templateUrl: 'views/facilities.form.guide.html',
        controller: 'FacilitiesFormGuideController'
      }).
      state('facilities.form.create', {
        url: '/create',
        templateUrl: 'views/facilities.form.create.html',
        controller: 'FacilitiesFormCreateController'
      }).
      state('facilities.form.edit', {
        url: '/{facilityRepositoryId:int}/edit?token',
        templateUrl: 'views/facilities.form.edit.html',
        controller: 'FacilitiesFormEditController'
      }).
      state('facilities.show', {
        url: '/{facilityId:int}',
        templateUrl: 'views/facilities.show.html',
        controller: 'FacilitiesShowController'
      }).
      state('facilities.show.equipment', {
        'abstract': true,
        'url': '/equipment',
        template: '<div data-ui-view></div>'
      }).
      state('facilities.show.equipment.show', {
        url: '/{equipmentId:int}',
        templateUrl: 'views/facilities.show.equipment.show.html',
        controller: 'FacilitiesShowEquipmentShowController'
      }).
      state('about', {
        url: '/about',
        templateUrl: 'views/about.html',
        controller: 'AboutController'
      }).
      state('about.legal', {
        'abstract': true,
        url: '/legal',
        template: '<div data-ui-view></div>'
      }).
      state('about.legal.privacyPolicy', {
        url: '/privacy-policy',
        templateUrl: 'views/about.legal.privacy-policy.html',
        controller: 'AboutLegalPrivacyPolicyController'
      }).
      state('about.legal.termsOfService', {
        url: '/terms-of-service',
        templateUrl: 'views/about.legal.terms-of-service.html',
        controller: 'AboutLegalTermsOfServiceController'
      }).
      state('about.legal.disclaimer', {
        url: '/disclaimer',
        templateUrl: 'views/about.legal.disclaimer.html',
        controller: 'AboutLegalDisclaimerController'
      }).
      state('contact', {
        url: '/contact',
        templateUrl: 'views/contact.html',
        controller: 'ContactController'
      }).
      state('admin', {
        'abstract': true,
        url: '/admin',
        templateUrl: 'views/admin.html',
        controller: 'AdminController'
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
      state('admin.facilities.compare', {
        url: '/compare?editedFrId&originalFrId',
        templateUrl: 'views/admin.facilities.compare.html',
        controller: 'AdminFacilitiesCompareController'
      }).
      state('admin.facilities.history', {
        'abstract': true,
        url: '/history?facilityId',
        templateUrl: 'views/admin.facilities.history.html',
        controller: 'AdminFacilitiesHistoryController'
      }).
      state('admin.facilities.history.index', {
        url: '?page',
        templateUrl: 'views/admin.facilities.history.index.html',
        controller: 'AdminFacilitiesHistoryIndexController'
      }).
      state('admin.facilities.updates', {
        url: '/update-requests',
        templateUrl: 'views/admin.facilities.updates.html',
        controller: 'AdminFacilitiesUpdatesController'
      }).
      state('admin.facilities.updates.index', {
        url: '/index?status&page',
        templateUrl: 'views/admin.facilities.updates.index.html',
        controller: 'AdminFacilitiesUpdatesIndexController'
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
        url: '/{organizationId:int}',
        templateUrl: 'views/admin.organizations.show.html',
        controller: 'AdminOrganizationsShowController'
      }).
      state('admin.provinces', {
        'abstract': true,
        url: '/provinces',
        templateUrl: 'views/admin.provinces.html',
        controller: 'AdminProvincesController'
      }).
      state('admin.provinces.index', {
        url: '/index?page',
        templateUrl: 'views/admin.provinces.index.html',
        controller: 'AdminProvincesIndexController'
      }).
      state('admin.provinces.create', {
        url: '/create',
        templateUrl: 'views/admin.provinces.create.html',
        controller: 'AdminProvincesCreateController'
      }).
      state('admin.provinces.show', {
        url: '/{provinceId:int}',
        templateUrl: 'views/admin.provinces.show.html',
        controller: 'AdminProvincesShowController'
      }).
      state('admin.sectors', {
        'abstract': true,
        url: '/sectors',
        templateUrl: 'views/admin.sectors.html',
        controller: 'AdminSectorsController'
      }).
      state('admin.sectors.index', {
        url: '/index?page',
        templateUrl: 'views/admin.sectors.index.html',
        controller: 'AdminSectorsIndexController'
      }).
      state('admin.sectors.create', {
        url: '/create',
        templateUrl: 'views/admin.sectors.create.html',
        controller: 'AdminSectorsCreateController'
      }).
      state('admin.sectors.show', {
        url: '/{sectorId:int}',
        templateUrl: 'views/admin.sectors.show.html',
        controller: 'AdminSectorsShowController'
      }).
      state('admin.disciplines', {
        'abstract': true,
        url: '/disciplines',
        templateUrl: 'views/admin.disciplines.html',
        controller: 'AdminDisciplinesController'
      }).
      state('admin.disciplines.index', {
        url: '/index?page',
        templateUrl: 'views/admin.disciplines.index.html',
        controller: 'AdminDisciplinesIndexController'
      }).
      state('admin.disciplines.create', {
        url: '/create',
        templateUrl: 'views/admin.disciplines.create.html',
        controller: 'AdminDisciplinesCreateController'
      }).
      state('admin.disciplines.show', {
        url: '/{disciplineId:int}',
        templateUrl: 'views/admin.disciplines.show.html',
        controller: 'AdminDisciplinesShowController'
      }).
      state('admin.users', {
        'abstract': true,
        url: '/users',
        templateUrl: 'views/admin.users.html',
        controller: 'AdminUsersController'
      }).
      state('admin.users.index', {
        url: '/index?page',
        templateUrl: 'views/admin.users.index.html',
        controller: 'AdminUsersIndexController'
      }).
      state('admin.users.create', {
        url: '/create',
        templateUrl: 'views/admin.users.create.html',
        controller: 'AdminUsersCreateController'
      }).
      state('admin.users.show', {
        url: '/{userId:int}',
        templateUrl: 'views/admin.users.show.html',
        controller: 'AdminUsersShowController'
      }).
      state('login', {
        url: '/login',
        templateUrl: 'views/login.html',
        controller: 'LoginController'
      }).
      state('error', {
        'abstract': true,
        templateUrl: 'views/error.html',
        controller: 'ErrorController'
      }).
      state('error.403', {
        templateUrl: 'views/error.403.html'
      }).
      state('error.404', {
        templateUrl: 'views/error.404.html'
      }).
      state('error.500', {
        templateUrl: 'views/error.500.html'
      }).
      state('error.503', {
        templateUrl: 'views/error.503.html'
      });
  }
]);
