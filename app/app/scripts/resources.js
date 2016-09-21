'use strict';

/** 
 * @fileoverview Angular $resource(s) to facilitate communication with the API.
 * @see https://docs.angularjs.org/api/ngResource/service/$resource
 */

angular.module('afredApp').factory('facilityResource',
  ['$rootScope',
   '$resource',
   function($rootScope,
            $resource) {
    // Alias to shorten code.
    var root = $rootScope._env.api.address;
    
    return $resource(root + '/facilities/:facilityId', {
      facilityId: '@id'
    }, {
      query: {
        method: 'GET',
        isArray: false
      },
      update: {
        method: 'PUT'
      }
    });
  }
]);

angular.module('afredApp').factory('facilityRepositoryResource',
  ['$rootScope',
   '$resource',
   function($rootScope,
            $resource) {
    // Alias to shorten code.
    var root = $rootScope._env.api.address;
    
    return $resource(root + '/facility-repository/:facilityRepositoryId', {
      facilityRepositoryId: '@id'
    }, {
      query: {
        method: 'GET',
        isArray: false
      },
      submit: {
        method: 'POST',
        params: {
          state: 'PENDING_APPROVAL'
        }
      },
      approve: {
        method: 'PUT',
        params: {
          state: 'PUBLISHED'
        }
      },
      reject: {
        method: 'PUT',
        params: {
          state: 'REJECTED'
        }
      },
      submitEdit: {
        method: 'PUT',
        params: {
          state: 'PENDING_EDIT_APPROVAL'
        }
      },
      approveEdit: {
        method: 'PUT',
        params: {
          state: 'PUBLISHED_EDIT'
        }
      },
      rejectEdit: {
        method: 'PUT',
        params: {
          state: 'REJECTED_EDIT'
        }
      },
      queryTokens: {
        url: root + '/facility-update-links',
        method: 'GET',
        isArray: false
      },
      queryTokensNoPaginate: {
        url: root + '/facility-update-links',
        method: 'GET',
        isArray: true,
        params: {
          paginate: 0
        }
      },
      createToken: {
        url: root + '/facility-update-links',
        method: 'POST'
      },
      updateToken: {
        url: root + '/facility-update-links/:facilityUpdateLinkId',
        method: 'PUT'
      },
      destroyToken: {
        url: root + '/facility-update-links/:facilityUpdateLinkId',
        method: 'DELETE'
      }
    });
  }
]);

angular.module('afredApp').factory('searchResource',
  ['$rootScope',
   '$resource',
   function($rootScope,
            $resource) {
    // Alias to shorten code.
    var root = $rootScope._env.api.address;
    
    return $resource(root + '/search', null, {
      query: {
        method: 'GET',
        isArray: false
      }
    });
  }
]);

angular.module('afredApp').factory('organizationResource',
  ['$rootScope',
   '$resource',
   function($rootScope,
            $resource) {
    // Alias to shorten code.
    var root = $rootScope._env.api.address;
    
    return $resource(root + '/organizations/:organizationId', {
      organizationId: '@id'
    }, {
      query: {
        method: 'GET',
        isArray: false
      },
      queryNoPaginate: {
        method: 'GET',
        isArray: true,
        params: {
          paginate: 0
        }
      },
      update: {
        method: 'PUT'
      }
    });
  }
]);

angular.module('afredApp').factory('iloResource',
  ['$rootScope',
   '$resource',
   function($rootScope,
            $resource) {
    // Alias to shorten code.
    var root = $rootScope._env.api.address;
    
    return $resource(root + '/ilos/:iloId', {
      iloId: '@id'
    }, {
      query: {
        method: 'GET',
        isArray: false
      },
      queryNoPaginate: {
        method: 'GET',
        isArray: true,
        params: {
          paginate: 0
        }
      },
      update: {
        method: 'PUT'
      }
    });
  }
]);

angular.module('afredApp').factory('provinceResource',
  ['$rootScope',
   '$resource',
   function($rootScope,
            $resource) {
    // Alias to shorten code.              
    var root = $rootScope._env.api.address;
    
    return $resource(root + '/provinces/:provinceId', {
      provinceId: '@id'
    }, {
      query: {
        method: 'GET',
        isArray: false
      },
      queryNoPaginate: {
        method: 'GET',
        isArray: true,
        params: {
          paginate: 0
        }
      },
      update: {
        method: 'PUT'
      }
    });
  }
]);

angular.module('afredApp').factory('disciplineResource',
  ['$rootScope',
   '$resource',
   function($rootScope,
            $resource) {
    // Alias to shorten code.
    var root = $rootScope._env.api.address;
    
    return $resource(root + '/disciplines/:disciplineId', {
      disciplineId: '@id'  
    }, {
      query: {
        method: 'GET',
        isArray: false
      },
      queryNoPaginate: {
        isArray: true,
        params: {
          paginate: 0
        }
      },
      update: {
        method: 'PUT'
      }
    });
  }
]);

angular.module('afredApp').factory('sectorResource',
  ['$rootScope',
   '$resource',
   function($rootScope,
            $resource) {
    // Alias to shorten code.
    var root = $rootScope._env.api.address;
    
    return $resource(root + '/sectors/:sectorId', {
      sectorId: '@id'
    }, {
      query: {
        method: 'GET',
        isArray: false
      },
      queryNoPaginate: {
        method: 'GET',
        isArray: true,
        params: {
          paginate: 0
        }
      },
      update: {
        method: 'PUT'
      }
    });
  }
]);

angular.module('afredApp').factory('userResource',
  ['$rootScope',
   '$resource',
   function($rootScope,
            $resource) {
    // Alias to shorten code.
    var root = $rootScope._env.api.address;
    
    return $resource(root + '/users/:userId', {
      userId: '@id'
    }, {
      query: {
        method: 'GET',
        isArray: false
      },
      queryNoPaginate: {
        method: 'GET',
        isArray: true,
        params: {
          paginate: 0
        }
      },
      update: {
        method: 'PUT'
      }
    });
  }
]);

angular.module('afredApp').factory('roleResource',
  ['$rootScope',
   '$resource',
   function($rootScope,
            $resource) {
    // Alias to shorten code.
    var root = $rootScope._env.api.address;
    
    return $resource(root + '/roles/:roleId', {
      roleId: '@id'
    }, {
      query: {
        method: 'GET',
        isArray: false
      },
      queryNoPaginate: {
        method: 'GET',
        isArray: true,
        params: {
          paginate: 0
        }
      }
    });
  }
]);

angular.module('afredApp').factory('emailResource',
  ['$rootScope',
   '$resource',
   function($rootScope,
            $resource) {
    // Alias to shorten code.
    var root = $rootScope._env.api.address;
    
    return $resource(root + '/email', null, {
      contactForm: {
        method: 'POST',
        params: {
          type: 'contactForm'
        }
      },
      springboardForm: {
        method: 'POST',
        params: {
          type: 'springboardAtlantic'
        }
      }
    });
  }
]);

angular.module('afredApp').factory('wpResource',
  ['$rootScope',
   '$resource',
   function($rootScope,
            $resource) {
    // Aliases to shorten code.
    var root = $rootScope._env.wp.address;
    var username = $rootScope._env.wp.appAuth.username;
    var password = $rootScope._env.wp.appAuth.password;
    
    var headers = {
      'Authorization': 'Basic ' + btoa(username + ':' + password),
      'X-CSRF-TOKEN': undefined // Override the global default headers.
                                 // WordPress does not need this.
    };
    
    return $resource(root, null, {
      queryPages: {
        url: root + '/pages',
        method: 'GET',
        isArray: true,
        headers: headers,
        withCredentials: false
      },
      getPage: {
        url: root + '/pages/:pageId',
        method: 'GET',
        isArray: false,
        headers: headers,
        withCredentials: false
      }
    });
  }
]);

angular.module('afredApp').factory('homeResource',
  ['$rootScope',
   '$resource',
   function($rootScope,
            $resource) {
    // Alias to shorten code.
    var root = $rootScope._env.api.address;
    
    return $resource(root + '/home');
  }
]);
