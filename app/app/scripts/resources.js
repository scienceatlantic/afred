'use strict';

angular.module('afredApp').factory('facilityResource', ['$resource',
  function($resource) {
    return $resource('//localhost/afred/api/public/facility/:facilityId', {}, {
      update: {method: 'PUT'},
      queryContacts: {method: 'GET', isArray: true, url: '//localhost/afred/api/public/facility/:facilityId/contacts'},
      queryEquipment: {method: 'GET', isArray: true, url: '//localhost/afred/api/public/facility/:facilityId/equipment'},
      getEquipment: {method: 'GET', url: '//localhost/afred/api/public/facility/:facilityId/equipment/:equipmentId'}
    });
  }
]);

angular.module('afredApp').factory('equipmentResource', ['$resource',
  function($resource) {
    return $resource('//localhost/afred/api/public/equipment');
  }
]);