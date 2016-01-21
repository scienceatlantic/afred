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
          rejectEdit: { method: 'PUT', params: { state: 'REJECTED_EDIT' }}    
        }
    );
  }
]);

angular.module('afredApp').factory('facilityUpdateLinkResource',
  ['$rootScope', '$resource', function($rootScope, $resource) {
    return $resource($rootScope._config.api +
      '/facility-update-links/:facilityUpdateLinkId',
        {
          facilityUpdateLinkId: '@id'
        },
        {
          find: { isArray: false },
          findNoPaginate: { isArray: true, params: { paginate: 0 } },
          generateToken: { method: 'POST' },
          verifyToken: { method: 'PUT' }
        }
    );
  }
]);

angular.module('afredApp').factory('equipmentResource', ['$rootScope',
  '$resource', function($rootScope, $resource) {
    return $resource($rootScope._config.api + '/equipment/:equipmentId');
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
