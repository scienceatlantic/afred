'use strict';

angular.module('afredApp').controller('AdminFacilitiesCompareController', [
  '$filter',
  '$q',
  '$scope',
  '$timeout',
  'JsDiff',
  'Repository',
  function($filter,
           $q,
           $scope,
           $timeout,
           JsDiff,
           Repository) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * Compares two facility records.
     * 
     * @sideffect edit The attributes are directly modified.
     * 
     * @requires $filter
     * @requires $q
     * @requires $timeout
     * @requires JsDiff
     * 
     * @param {FacilityRepository} original 
     * @param {FacilityRepository} edit
     * 
     * @return {Facility} Facility with attributes marked up (HTML) to highlight
     *     the differences. Similar to how Angular resources return values, the
     *     facility also returns a promise stored in a `$promise` attribute and
     *     a `$resolved` attribute.
     */
    $scope.compare = function(original, edit) {
      // Aliases to shorten code.
      var o = original, e = edit, dW = JsDiff.words, dL = JsDiff.lines;

      // Initialise promise.
      var deferred = $q.defer();

      // Set flag.
      e.$resolved = false;

      $timeout(function() {
        // ---------- Facility section.  ---------- 
        e.name = dW(o.name, e.name);
        e.organization.name = dW(o.organization.name, e.organization.name);
        e.city = dW(o.city, e.city);
        e.province.name = dW(o.province.name, e.province.name);
        e.website = dW(o.website, e.website);
        e.description = dW(o.description, e.description, true);

        // ---------- Disciplines section.  ---------- 
        // Sort first.
        e.disciplines = $filter('orderBy')(e.disciplines, 'name');
        o.disciplines = $filter('orderBy')(o.disciplines, 'name');

        // Get the larger list (original vs. edit).
        var numDisciplines;
        if (e.disciplines.length >= o.disciplines) {
          numDisciplines = e.disciplines.length;
        } else {
          numDisciplines = o.disciplines.length;
        }

        // Finally, compare based on larger list.
        for (var i = 0; i < numDisciplines; i++) {
          var eD = typeof e.disciplines[i] === 'object' ? e.disciplines[i] : {};
          var oD = typeof o.disciplines[i] === 'object' ? o.disciplines[i] : {}; 
          eD.name = dL(oD.name, eD.name);
          e.disciplines[i] = eD;   
        }

        // ---------- Sectors section. ---------- 
        // Sort first.
        e.sectors = $filter('orderBy')(e.sectors, 'name');
        o.sectors = $filter('orderBy')(o.sectors, 'name');

        // Get larger list (original vs. edit).
        var numDisciplines;
        if (e.sectors.length >= o.sectors) {
          numDisciplines = e.sectors.length;
        } else {
          numDisciplines = o.sectors.length;
        }

        // Finally, compare based on larger list.
        for (var i = 0; i < numDisciplines; i++) {
          var eD = typeof e.sectors[i] === 'object' ? e.sectors[i] : {};
          var oD = typeof o.sectors[i] === 'object' ? o.sectors[i] : {}; 
          eD.name = dL(oD.name, eD.name);
          e.sectors[i] = eD;   
        }

        // ---------- Contacts section. ---------- 
        // The primary contact is the first element in the array and we don't 
        // want that to change with the sorting.
        var ePc = e.contacts.shift();
        var oPc = o.contacts.shift();
        e.contacts = $filter('orderBy')(e.contacts, 'id');
        o.contacts = $filter('orderBy')(o.contacts, 'id');
        e.contacts.unshift(ePc);
        o.contacts.unshift(oPc);

        // Get the larger length.
        if (e.contacts.length >= o.contacts.length) {
          var numContacts = e.contacts.length;
        } else {
          var numContacts = o.contacts.length;
        }

        // Finally, compare based on the larger length.
        for (var i = 0; i < numContacts; i++) {
          var eC = typeof e.contacts[i] === 'object' ? e.contacts[i] : {};
          var oC = typeof o.contacts[i] === 'object' ? o.contacts[i] : {};
          eC.firstName = dW(oC.firstName, eC.firstName);
          eC.lastName = dW(oC.lastName, eC.lastName);
          eC.email = dW(oC.email, eC.email);
          eC.telephone = dW(oC.telephone, eC.telephone);
          eC.extension = dW(oC.extension, eC.extension);
          eC.position = dW(oC.position, eC.position);
          eC.website = dW(oC.website, eC.website);
          e.contacts[i] = eC;
        }

        // ---------- Equipment section. ---------- 

        // Sort first.
        e.equipment = $filter('orderBy')(e.equipment, 'id');
        o.equipment = $filter('orderBy')(o.equipment, 'id');

        // Get the larger length.
        if (e.equipment.length >= o.equipment.length) {
          var numEquipment = e.equipment.length;
        } else {
          var numEquipment = o.equipment.length;
        }

        // Finally, compare based on the larger length.
        for (var i = 0; i < numEquipment; i++) {
          var eE = typeof e.equipment[i] === 'object' ? e.equipment[i] : {};
          var oE = typeof o.equipment[i] === 'object' ? o.equipment[i] : {};
          
          eE.type = dW(oE.type, eE.type);
          eE.manufacturer = dW(oE.manufacturer, eE.manufacturer);
          eE.model = dW(oE.model, eE.model);
          eE.purpose = dW(oE.purpose, eE.purpose, true);
          eE.specifications = dW(oE.specifications, eE.specifications, true);
          eE.yearPurchased = dW(oE.yearPurchased, eE.yearPurchased);
          eE.yearManufactured = dW(oE.yearManufactured, eE.yearManufactured);
          eE.isPublic = eE.isPublic ? 'Public' : 
            (eE.isPublic !== undefined ? 'Hidden' : null);
          oE.isPublic = oE.isPublic ? 'Public' : 
            (oE.isPublic !== undefined ? 'Hidden' : null);
          eE.isPublic = dW(oE.isPublic, eE.isPublic);
          eE.hasExcessCapacity = eE.hasExcessCapacity ? 'Yes' : 
            (eE.hasExcessCapacity !== undefined ? 'No' : null);
          oE.hasExcessCapacity = oE.hasExcessCapacity ? 'Yes' : 
            (oE.hasExcessCapacity !== undefined ? 'No' : null);
          eE.hasExcessCapacity = dW(oE.hasExcessCapacity, eE.hasExcessCapacity);
          eE.keywords = dW(oE.keywords, eE.keywords);
          e.equipment[i] = eE;
        }

        // Resolve.
        deferred.resolve(e);
      });

      // Attach promise.
      e.$promise = deferred.promise;
      e.$promise.then(function() {
          e.$resolved = true;
      });

      return e;
    };

    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    /**
     * Holds facility data.
     * 
     * @type {object}
     */
    $scope.facility = {};

    // Get facility repository records.
    var originalFr = Repository.get($scope._stateParams.originalFrId);
    var editedFr = Repository.get($scope._stateParams.editedFrId);

    $q.all([originalFr.$promise, editedFr.$promise]).then(function() {
      // Make sure records are valid for comparison first.
      if ((originalFr.facilityId < 1 || editedFr.facilityId < 1)
          || (originalFr.facilityId !== editedFr.facilityId)
          || (originalFr.id >= editedFr.id)) {
        $scope._httpError(404);
        return;
      }

      var originalF = Repository.getFacility(originalFr);
      var editedF = Repository.getFacility(editedFr);

      $q.all([editedF.$promise, originalF.$promise]).then(function() {
        $scope.facility = $scope.compare(originalF, editedF);
      });
    }, function() {
      // Do nothing if it fails.
    });
  }
]);
