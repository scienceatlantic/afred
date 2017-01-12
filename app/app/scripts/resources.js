'use strict';

/** 
 * @fileoverview Angular $resource(s) to facilitate communication with the API.
 * @see https://docs.angularjs.org/api/ngResource/service/$resource
 */

angular.module('afredApp').factory('FacilityResource',
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

angular.module('afredApp').factory('RepositoryResource',
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

angular.module('afredApp').factory('OrganizationResource',
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

angular.module('afredApp').factory('IloResource',
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

angular.module('afredApp').factory('ProvinceResource',
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

angular.module('afredApp').factory('DisciplineResource',
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

angular.module('afredApp').factory('SectorResource',
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

angular.module('afredApp').factory('UserResource',
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

angular.module('afredApp').factory('RoleResource',
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

angular.module('afredApp').factory('EmailResource',
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

angular.module('afredApp').factory('WpResource',
  ['$rootScope',
   '$resource',
   function($rootScope,
            $resource) {
    // Aliases to shorten code.
    var root = $rootScope._env.wp.address;
    var username = $rootScope._env.wp.appAuth.username;
    var password = $rootScope._env.wp.appAuth.password;

    // If `window.btoa` is not supported (i.e. < IE 10).
    // @see https://scotch.io/tutorials/how-to-encode-and-decode-strings-with-base64-in-javascript
    if (!window.btoa) {
      var base64 = {
        _keyStr: 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=',
        encode: function(e) {
          var t = '';
          var n, r, i, s, o, u, a;
          var f = 0;
          e = base64._utf8_encode(e);
          while (f < e.length) {
            n = e.charCodeAt(f++);
            r = e.charCodeAt(f++);
            i = e.charCodeAt(f++);
            s = n >> 2;
            o = (n & 3) << 4 | r >> 4;
            u = (r & 15) << 2 | i >> 6;
            a = i & 63;
            if (isNaN(r)) {
              u = a = 64;
            } else if (isNaN(i)) {
              a = 64;
            }
            t = t + base64._keyStr.charAt(s) + 
              base64._keyStr.charAt(o) + 
              base64._keyStr.charAt(u) + 
              base64._keyStr.charAt(a);
          }
          return t;
        },
        _utf8_encode: function(e) {
          e = e.replace(/rn/g, 'n');
          var t = '';
          for (var n = 0; n < e.length; n++) {
            var r = e.charCodeAt(n);
            if (r < 128) {
              t += String.fromCharCode(r);
            } else if (r > 127 && r < 2048) {
              t += String.fromCharCode(r >> 6 | 192);
              t += String.fromCharCode(r & 63 | 128);
            } else {
              t += String.fromCharCode(r >> 12 | 224);
              t += String.fromCharCode(r >> 6 & 63 | 128);
              t += String.fromCharCode(r & 63 | 128);
            }
          }
          return t;
        }
      };

      window.btoa = base64.encode;
    }
    
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

angular.module('afredApp').factory('MiscResource',
  ['$rootScope',
   '$resource',
   function($rootScope,
            $resource) {
    // Alias to shorten code.
    var root = $rootScope._env.api.address;
    
    return $resource(root + '/misc');
  }
]);
