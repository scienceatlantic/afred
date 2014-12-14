'use strict';

angular.module('afredApp').factory('facilityResource', ['$resource',
  function($resource) {
    return $resource('//localhost/afred/api/public/facilities/:facilityId',
      {
        facilityId: '@id'
      },
      {
        update: {method: 'PUT'},
        queryContacts: {method: 'GET', isArray: true, url: '//localhost/afred/api/public/facilities/:facilityId/contacts'},
        queryEquipment: {method: 'GET', isArray: true, url: '//localhost/afred/api/public/facilities/:facilityId/equipment'},
        getEquipment: {method: 'GET', url: '//localhost/afred/api/public/facilities/:facilityId/equipment/:equipmentId'}
      }
    );
  }
]);

angular.module('afredApp').factory('equipmentResource', ['$resource',
  function($resource) {
    return $resource('//localhost/afred/api/public/equipment');
  }
]);

angular.module('afredApp').factory('institutionResource', ['$resource',
  function($resource) {
    return $resource('//localhost/afred/api/public/institutions/:institutionId');
  }
]);