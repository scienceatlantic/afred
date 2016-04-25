'use strict';
/**
 * @fileoverview Environment variables. Update these settings to match your
 * environment.
 * 
 * !IMPORTANT NOTE!: Please do not commit any sensitive information to GitHub.
 * When committing any changes to the structure of the file, replace values with
 * a blank string (i.e. '').
 */

angular.module('afredApp').run(['$rootScope', function($rootScope) {
  $rootScope._config = {
    /* ---------------------------------------------------------------------
     * API settings.
     * --------------------------------------------------------------------- */
    api: {
      address: '//localhost:8000',
    },
    
    /* ---------------------------------------------------------------------
     * APP settings.
     * --------------------------------------------------------------------- */
    app: {
      name: 'Atlantic Facilities and Research Equipment Database',
      acronym: 'AFRED',
      address: '//localhost:9000/#'
    },
    
    /* ---------------------------------------------------------------------
     * WordPress settings.
     * --------------------------------------------------------------------- */
    wp: {
      // Application authentication. Will be sent as a header with each request
      // to the API.
      appAuth: {
        username: 'development',
        password: 'R0KU jxX7 KjOR vDvX yPCQ 3DR0',
      },
      
      address: 'http://afred.ca/wp/api/wp-json/wp/v2',
      
      // Location of specific pages on WordPress. 
      pages: {
        'about': { pageId: 5 },
        'update a facility': { pageId: '' },
        'submit a facility': { pageId: 12 },
        'submit a facility - success' : { pageId: 19 },
        'submit a facility - failure' : { pageId: 21 }
      }
    },
    
    /* ---------------------------------------------------------------------
     * Log settings.
     * --------------------------------------------------------------------- */  
    log: {
      log: true,
      info: true,
      warn: true,
      error: true,
      debug: true
    },
    
    /* ---------------------------------------------------------------------
     * Contact information.
     * --------------------------------------------------------------------- */
    contacts: {
      general: {
        name: 'Patty King',
        title: 'AFRED Program Manager',
        email: 'patty.king@scienceatlantic.ca'
      }
    }
  };
}]);