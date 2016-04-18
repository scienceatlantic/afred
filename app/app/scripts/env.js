'use strict';
/* ---------------------------------------------------------------------
 * Environment variables.
 *
 * Update these settings to match your environment.
 * Note: Please do not commit any sensitive information to GitHub. When
 * committing any changes to the structure of the file, replace values
 * with a blank string (i.e. '').
 * --------------------------------------------------------------------- */

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
        username: '',
        password: '',
      },
      
      address: 'http://afred.ca/wp/api/wp-json/wp/v2',
      
      // Location of specific pages on WordPress. 
      pages: {
        'about': { pageId: 5 },
        'update facility instructions': { pageId: '' },
        'submit a facility intro': { pageId: 4 }
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