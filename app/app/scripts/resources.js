'use strict';

angular.module('afredApp').factory('facilityResource', ['$rootScope',
  '$resource', function($rootScope, $resource) {
    return $resource($rootScope._config.api + '/facilities/:facilityId',
      {
        facilityId: '@id'
      },
      {
        query: { isArray: false },
        update: { method: 'PUT' }
      }
    );
  }
]);

angular.module('afredApp').factory('facilityRepositoryResource',
  ['$rootScope', '$resource', function($rootScope, $resource) {
    return $resource($rootScope._config.api +
      '/facility-repository/:facilityRepositoryId',
        {
          facilityRepositoryId: '@id'
        },
        {
          query: { isArray: false },
          submit: { method: 'POST', params: { state: 'PENDING_APPROVAL' }},
          approve: { method: 'PUT', params: { state: 'PUBLISHED' }},
          reject: { method: 'PUT', params: { state: 'REJECTED' }},
          submitEdit: { method: 'PUT', params: { state:
            'PENDING_EDIT_APPROVAL' }},
          approveEdit: { method: 'PUT', params: { state: 'PUBLISHED_EDIT' }},
          rejectEdit: { method: 'PUT', params: { state: 'REJECTED_EDIT' }},
          
          queryTokens: { url: $rootScope._config.api +
            '/facility-update-links', method: 'GET', isArray: false },
          queryTokensNoPaginate: { url: $rootScope._config.api +
            '/facility-update-links', method: 'GET', isArray: true,
            params: { paginate: 0 }},
          generateToken: { url: $rootScope._config.api +
            '/facility-update-links/generate-token', method: 'POST' },
          verifyToken: { url: $rootScope._config.api +
            '/facility-repository/verify-token', method: 'POST' }
        }
    );
  }
]);

angular.module('afredApp').factory('searchResource', ['$rootScope',
  '$resource', function($rootScope, $resource) {
    return $resource($rootScope._config.api + '/search');
  }
]);

angular.module('afredApp').factory('organizationResource', ['$rootScope',
  '$resource', function($rootScope, $resource) {
    return $resource($rootScope._config.api + '/organizations/:organizationId',
      {},
      {
        query: { isArray: false },
        queryNoPaginate: { isArray: true, params: { paginate: 0 }}
      }
    );
  }
]);

angular.module('afredApp').factory('provinceResource', ['$rootScope',
  '$resource', function($rootScope, $resource) {
    return $resource($rootScope._config.api + '/provinces/:provinceId',
      {},
      {
        query: { isArray: false },
        queryNoPaginate: { isArray: true, params: { paginate: 0 }}
      }
    );
  }
]);

angular.module('afredApp').factory('disciplineResource', ['$rootScope',
  '$resource', function($rootScope, $resource) {
    return $resource($rootScope._config.api + '/disciplines/:disciplineId',
      {},
      {
        query: { isArray: false },
        queryNoPaginate: { isArray: true, params: { paginate: 0 }}
      }
    );
  }
]);

angular.module('afredApp').factory('sectorResource', ['$rootScope',
  '$resource', function($rootScope, $resource) {
    return $resource($rootScope._config.api + '/sectors/:sectorId',
      {},
      {
        query: { isArray: false },
        queryNoPaginate: { isArray: true, params: { paginate: 0 }}
      }
    );
  }
]);
