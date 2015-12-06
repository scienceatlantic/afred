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

angular.module('afredApp').factory('facilityRevisionHistoryResource',
  ['$rootScope', '$resource', function($rootScope, $resource) {
    return $resource($rootScope._config.api +
      '/facilityRevisionHistory/:facilityRevisionHistoryId',
        {
          facilityRevisionHistoryId: '@id'
        },
        {
          query: { isArray: false },
          approve: { method: 'PUT', params: { state: 'PUBLISHED' }},
          reject: { method: 'PUT', params: { state: 'REJECTED' }},
          saveEditDraft: { method: 'PUT', params: { state: 'EDIT_DRAFT' }},
          approveEdit: { method: 'PUT', params: { state: 'PUBLISHED' }},
          rejectEdit: { method: 'PUT', params: { state: 'REJECTED_EDIT' }}    
        }
    );
  }
]);

angular.module('afredApp').factory('equipmentResource', ['$rootScope',
  '$resource', function($rootScope, $resource) {
    return $resource($rootScope._config.api + '/equipment/:equipmentId');
  }
]);

angular.module('afredApp').factory('institutionResource', ['$rootScope',
  '$resource', function($rootScope, $resource) {
    return $resource($rootScope._config.api + '/institutions/:institutionId',
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
