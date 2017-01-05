'use strict';

/** 
 * @fileoverview Angular services are stored here.
 * @see https://docs.angularjs.org/api/auto/service/$provide#service
 * @see https://docs.angularjs.org/guide/services
 */

/* ---------------------------------------------------------------------
 * Generic injectable modals.
 * @see https://angular-ui.github.io/bootstrap/#/modal
 * --------------------------------------------------------------------- */

angular.module('afredApp').service('confirmModal', [
  '$uibModal',
  function($uibModal) {    
    this.open = function(template) {
      return $uibModal.open({
        templateUrl: 'views/modals/confirm.html',
        controller: ['$scope', '$uibModalInstance',
          function($scope, $uibModalInstance) {
            $scope.modal = $uibModalInstance;
            $scope.template = 'views/includes/modals/'+ template + '.html';
          }
        ]
      });        
    };
  }
]);

angular.module('afredApp').service('infoModal', [
  '$uibModal',
  function($uibModal) {    
    this.open = function(template) {
      return $uibModal.open({
        templateUrl: 'views/modals/info.html',
        controller: ['$scope', '$uibModalInstance',
          function($scope, $uibModalInstance) {
            $scope.modal = $uibModalInstance;
            $scope.template = 'views/includes/modals/' + template + '.html';
          }
        ],
        keyboard: false,
        backdrop: 'static'
      });        
    };
  }
]);

angular.module('afredApp').service('warningModal', [
  '$uibModal',
  function($uibModal) {    
    this.open = function(template) {
      return $uibModal.open({
        templateUrl: 'views/modals/warning.html',
        controller: ['$scope', '$uibModalInstance',
          function($scope, $uibModalInstance) {
            $scope.modal = $uibModalInstance;
            $scope.template = 'views/includes/modals/' + template + '.html';
          }
        ],
        keyboard: false,
        backdrop: 'static'
      });        
    };
  }
]);

/* ---------------------------------------------------------------------
 * Algolia Search.
 * @see https://www.algolia.com/doc/api-client/javascript/getting-started#install
 * --------------------------------------------------------------------- */

angular.module('afredApp').service('algolia', [
  '$rootScope',
  function($rootScope) {
    var env = $rootScope._env.algolia;
    var client = algoliasearch(env.api.applicationId, env.api.key);

    // Initialise each index.
    this.facilities = function() {
      return client.initIndex(env.indices.facilities)
    };
    this.equipment = function() {
      return client.initIndex(env.indices.equipment)
    };
  }
]);

/* ---------------------------------------------------------------------
 * JsDiff.
 * @see https://github.com/kpdecker/jsdiff
 * --------------------------------------------------------------------- */

angular.module('afredApp').service('JsDiff', [
  function() {
    function formatter(original, edit, isHtml, diffType) {
      original = (original ? String(original) : '');
      edit = (edit ? String(edit) : '');

      if (isHtml) {
        original = angular.element('<div>' + original + '</div>').text();
        edit = angular.element('<div>' + edit + '</div>').text();
      }

      if (!original.trim() && !edit.trim()) {
        return '';
      }

      var diff = null;
      switch (diffType) {
        case 'chars':
          diff = JsDiff.diffChars(original, edit);
          break;
        case 'words':
          diff = JsDiff.diffWords(original, edit);
          break;
        case 'lines':
          diff = JsDiff.diffLines(original, edit);
          break;              
      }

      var span = '';
      diff.forEach(function(part) {
        // green for additions, red for deletions
        // grey for common parts
        span += '<span style="color:';

        if (part.added) {
          span += 'green; font-weight: bold';
        } else if (part.removed) {
          span += 'red; text-decoration: line-through';
        } else {
          span += 'inherit';
        }

        span += ';">' + part.value + '</span>';
      });

      return span;     
    }

    this.chars = function(original, edit, isHtml) {
      return formatter(original, edit, isHtml, 'chars');
    };

    this.words = function(original, edit, isHtml) {
      return formatter(original, edit, isHtml, 'words');
    };

    this.lines = function(original, edit, isHtml) {
      return formatter(original, edit, isHtml, 'lines');
    };
  }
]);


/* ---------------------------------------------------------------------
 * Facility Repository service.
 * TODO: comments..
 * --------------------------------------------------------------------- */

angular.module('afredApp').service('Repository', [
  '$q',
  '$rootScope',
  'DisciplineResource',
  'RepositoryResource',
  'OrganizationResource',
  'ProvinceResource',
  'SectorResource',
  function($q,
           $rootScope,
           DisciplineResource,
           RepositoryResource,
           OrganizationResource,
           ProvinceResource,
           SectorResource) {

    this.get = function(id) {
      return RepositoryResource.get({
        facilityRepositoryId: id ? id : 0
      }, function() {
        // Do nothing if successful.
      }, function(response) {
        $rootScope._httpError403(response);
      });
    };

    this.getFacility = function(fr) {
      var data = angular.copy(fr.data);
      var facility = data.facility;
      facility.organization = data.organization;
      facility.contacts = data.contacts;
      facility.equipment = data.equipment;
      facility.state = fr.state;  
      
      try {
        facility.isPublic = fr.publishedFacility.isPublic;
      } catch (e) {
        // Do nothing if it fails.
      }
            
      // Primary contact & contacts section. In the DB primary contacts and
      // regular contacts are stored in separate tables, however, when the user
      // is viewing it, it's stored in a single array (where the first element
      // is the primary contact).
      if (!data.contacts || !angular.isArray(data.contacts)) {
        facility.contacts = [];
        facility.contacts.push(data.primaryContact);
      } else {
        facility.contacts.unshift(data.primaryContact);
      }
      
      // Organization section. Check if the facility belongs to an existing
      // organization or a new organization. If it belongs to an existing
      // organization, grab the details from the API.
      if (data.facility.organizationId) {
        facility.organization = OrganizationResource.get({
          organizationId: data.facility.organizationId
        }, function() {
          // Do nothing if successful.
        }, function(response) {
          $rootScope._httpError(response);
        });
      }
      
      // Province section.
      facility.province = ProvinceResource.get({
        provinceId: data.facility.provinceId
      }, function() {
        // Do nothing if successful.
      }, function(response) {
        $rootScope._httpError(response);
      });
      
      // Disciplines section. Grab the complete list of disciplines from the API
      // so that we can get the names (the facility repository record only
      // contains the IDs of the disciplines).
      facility.disciplines = [];
      var isDisciplineReady = $q.defer();
      var disciplines = DisciplineResource.queryNoPaginate(function() {
        angular.forEach(disciplines, function(d) {
          if (data.disciplines.indexOf(d.id) >= 0) {
            facility.disciplines.push(d);
          }
        });
        isDisciplineReady.resolve();
      }, function(response) {
        $rootScope._httpError(response); 
      });
      
      // Sectors section. (Same as disciplines).
      facility.sectors = [];
      var isSectorReady = $q.defer();
      var sectors = SectorResource.queryNoPaginate(function() {
        angular.forEach(sectors, function(s) {
          if (data.sectors.indexOf(s.id) >= 0) {
            facility.sectors.push(s);
          }
        });
        isSectorReady.resolve();
      }, function(response) {
        $rootScope._httpError(response); 
      });

      // Set up promise.
      var deferred = $q.defer();
      facility.$promise = deferred.promise;
      facility.$promise.then(function() {
        facility.$resolved = true;
      });

      // Waits for all the async calls to complete before resolving the
      // promise.
      $q.all([
        facility.organization.$promise,
        facility.province.$promise,
        isDisciplineReady.promise,
        isSectorReady.promise
      ]).then(function() {
        deferred.resolve(facility);
      });

      return facility;
    };
  }
]);
