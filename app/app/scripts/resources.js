'use strict';

angular.module('afredApp').factory('facilityResource', ['$resource',
  function($resource) {
    return $resource('//localhost/afred/api/public/facilities/:facilityId',
      {
        facilityId: '@id'
      },
      {
        update: {method: 'PUT'}
      }
    );
  }
]);

angular.module('afredApp').factory('equipmentResource', ['$resource',
  function($resource) {
    return $resource('//localhost/afred/api/public/equipment/:equipmentId');
  }
]);

angular.module('afredApp').factory('institutionResource', ['$resource',
  function($resource) {
    return $resource('//localhost/afred/api/public/institutions/:institutionId');
  }
]);

angular.module('afredApp').factory('provinceResource', ['$resource',
  function($resource) {
    return $resource('//localhost/afred/api/public/provinces/:provinceId');
  }
]);