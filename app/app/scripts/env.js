'use strict';

angular.module('afredApp').run(['$rootScope', function($rootScope) {
  $rootScope._config = {
    api: '//localhost:8000',
    appAddress: '//localhost:9000',
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