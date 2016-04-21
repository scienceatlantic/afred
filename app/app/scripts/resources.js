'use strict';

angular.module('afredApp').factory('facilityResource',
  ['$rootScope',
   '$resource',
   function($rootScope,
            $resource) {
    var root = $rootScope._config.api.address;
    
    return $resource(root + '/facilities/:facilityId',
      {
        facilityId: '@id'
      },
      {
        query: {
          method: 'GET',
          isArray: false
        },
        update: {
          method: 'PUT'
        }
      }
    );
  }
]);

angular.module('afredApp').factory('facilityRepositoryResource',
  ['$rootScope',
   '$resource',
   function($rootScope,
            $resource) {
    var root = $rootScope._config.api.address;
    
    return $resource(root + '/facility-repository/:facilityRepositoryId',
      {
        facilityRepositoryId: '@id'
      },
      {
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
          url: root + '/facilities',
          method: 'GET',
          isArray: false
        },
        queryTokensNoPaginate: {
          url: root + '/facilities',
          method: 'GET',
          isArray: true,
          params: {
            paginate: 0
          }
        },
        generateToken: {
          url: root + '/facility-update-links/generate-token',
          method: 'POST'
        },
        verifyToken: {
          url: root + '/facility-repository/verify-token',
          method: 'POST'
        }
      }
    );
  }
]);

angular.module('afredApp').factory('searchResource',
  ['$rootScope',
   '$resource',
   function($rootScope,
            $resource) {
    var root = $rootScope._config.api.address;
    
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
    var root = $rootScope._config.api.address;
    
    return $resource(root + '/organizations/:organizationId',
      {
        organizationId: '@id'
      },
      {
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
      }
    );
  }
]);

angular.module('afredApp').factory('provinceResource',
  ['$rootScope',
   '$resource',
   function($rootScope,
            $resource) {
    var root = $rootScope._config.api.address;
    
    return $resource(root + '/provinces/:provinceId',
      {
        provinceId: '@id'
      },
      {
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
      }
    );
  }
]);

angular.module('afredApp').factory('disciplineResource',
  ['$rootScope',
   '$resource',
   function($rootScope,
            $resource) {
    var root = $rootScope._config.api.address;
    
    return $resource(root + '/disciplines/:disciplineId',
      {
        disciplineId: '@id'  
      },
      {
        query: {
          method: 'GET',
          isArray: false
        },
        queryNoPaginate: {
          isArray: true,
          params: {
            paginate: 0
          }
        }
      }
    );
  }
]);

angular.module('afredApp').factory('sectorResource',
  ['$rootScope',
   '$resource',
   function($rootScope,
            $resource) {
    var root = $rootScope._config.api.address;
    
    return $resource(root + '/sectors/:sectorId',
      {
        sectorId: '@id'
      },
      {
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
      }
    );
  }
]);

angular.module('afredApp').factory('wpResource',
  ['$rootScope',
   '$resource',
   function($rootScope,
            $resource) {
    var root = $rootScope._config.wp.address;
    
    var headers = {
      'Authorization': 'Basic '
        + btoa($rootScope._config.wp.appAuth.username + ':'
        + $rootScope._config.wp.appAuth.password)
    };
    
    return $resource(root, null,
      {
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
      }
    );
  }
]);
