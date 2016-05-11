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
     * App settings.
     * --------------------------------------------------------------------- */
    app: {
      name: 'Atlantic Facilities and Research Equipment Database',
      shortname: 'AFRED',
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
        'terms of service': { pageId: 38 },
        'privacy policy': { pageId: 36 },
        'disclaimer': { pageId: 40 }
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
      /**
       * Should be a shared account.
       */
      general: {
        firstName: '',
        lastName: '',
        title: '',
        email: 'afred@scienceatlantic.ca'
      },
      
      /**
       * Personal email account.
       */
      personal: {
        firstName: 'Patty',
        lastName: 'King',
        title: '',
        email: 'patty.king@scienceatlantic.ca'
      }
    }
  };
}]);