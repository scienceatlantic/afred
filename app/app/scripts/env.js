'use strict';

/**
 * @fileoverview Local environment variables.
 */

angular.module('afredApp').run(['$rootScope', function($rootScope) {  
  /**
   * Environment object is attached to the $rootScope so that it is accessible
   * globally. 
   */
  $rootScope._env = {
    /* ---------------------------------------------------------------------
     * Environment mode.
     * --------------------------------------------------------------------- */
    local: true,
    dev: false,
    production: false,
    testing: false,

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
      address: '//localhost:9000'
    },
    
    /* ---------------------------------------------------------------------
     * WordPress settings.
     * --------------------------------------------------------------------- */
    wp: {      
      address: '//wp.afred.ca/wp-json/wp/v2',
      
      // Location of specific pages on WordPress. 
      pages: {
        'what\'s new': { pageId: 93 },
        'about': { pageId: 5 },
        'contact': { pageId: 183 },
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
        facilities: 'dev_facilities',
        equipment: 'dev_equipment' 
      }
    }    
  };
}]);
