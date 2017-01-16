'use strict';

/**
 * @fileoverview Development environment variables.
 */

angular.module('afredApp').run(['$rootScope', function($rootScope) {  
  /**
   * Environment object is attached to the $rootScope so that it is accessible
   * globally. 
   */
  $rootScope._env = {
    /* ---------------------------------------------------------------------
     * API settings.
     * --------------------------------------------------------------------- */
    api: {
      address: '//afred.ca/api/app/v2/dev',
    },
    
    /* ---------------------------------------------------------------------
     * App settings.
     * --------------------------------------------------------------------- */
    app: {
      name: 'Atlantic Facilities and Research Equipment Database',
      shortname: 'AFRED',
      address: '//afred.ca/dev'
    },
    
    /* ---------------------------------------------------------------------
     * WordPress settings.
     * --------------------------------------------------------------------- */
    wp: {
      // Application authentication. Will be sent as a header with each request
      // to the API.
      // @see http://v2.wp-api.org/guide/authentication/#application-passwords-or-basic-authentication
      appAuth: {
        username: 'development',
        password: 'R0KU jxX7 KjOR vDvX yPCQ 3DR0',
      },
      
      address: '//afred.ca/api/wp/wp-json/wp/v2',
      
      // Location of specific pages on WordPress. 
      pages: {
        'what\'s new': { pageId: 93 },
        'about': { pageId: 5 },
        'terms of service': { pageId: 38 },
        'privacy policy': { pageId: 36 },
        'disclaimer': { pageId: 40 },
        'form guide': { pageId: 48 }
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
     * Google settings.
     * --------------------------------------------------------------------- */
    google: {
      analytics: {
        id: 'UA-80861919-1'
      }
    },

    /* ---------------------------------------------------------------------
     * Algolia settings.
     * --------------------------------------------------------------------- */
    algolia: {
      api: {
        applicationId: 'C8MSUIO9J3',
        key: '576b55b1594fa147cb9266ce2572fdd4'
      },
      
      // Index names for facilities and equipment searches.
      indices: {
        facilities: 'prod_facilities',
        equipment: 'prod_equipment' 
      }
    }
  };
}]);
