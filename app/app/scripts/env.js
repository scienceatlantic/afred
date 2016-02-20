'use strict';

angular.module('afredApp').run(['$rootScope', function($rootScope) {
  $rootScope._config = {
    api: {
      address: '//localhost:8000',
    },
    app: {
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
        email: 'patty.king@scienceatlantic.ca'
      }
    }
  };
}]);