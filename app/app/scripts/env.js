'use strict';

angular.module('afredApp').run(['$rootScope', function($rootScope) {
  $rootScope._config = {
    api: {
      address: '//localhost:8000',
    },
    app: {
      name: 'Atlantic Facilities and Research Equipment Database',
      acronym: 'AFRED',
      address: '//localhost:9000/#'
    },
    log: {
      log: true,
      info: true,
      warn: true,
      error: true,
      debug: true
    },
    
    contacts: {
      general: {
        name: 'Patty King',
        title: 'AFRED Program Manager',
        email: 'patty.king@scienceatlantic.ca'
      }
    }
  };
}]);