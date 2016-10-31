'use strict';

angular.module('afredApp').controller('AdminFacilitiesCompareController', [
  '$scope',
  'JsDiff',
  '$q',
  '$filter',
  'Repository',
  function($scope,
           JsDiff,
           $q,
           $filter,
           Repository) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * TODO: comments
     * TODO: use `for in` loop instead?
     */
    $scope.compare = function(original, edit) {
      // Aliases to shorten code.
      var o = original, e = edit, dW = JsDiff.words, dL = JsDiff.lines;

      // Set flag.
      e.$resolved = false;

      // Facility section.
      e.name = dW(o.name, e.name);
      e.organization.name = dW(o.organization.name, e.organization.name);
      e.city = dW(o.city, e.city);
      e.province.name = dW(o.province.name, e.province.name);
      e.website = dW(o.website, e.website);
      e.description = dW(o.description, e.description, true);

      // Disciplines section.
      e.disciplines = $filter('orderBy')(e.disciplines, 'name');
      o.disciplines = $filter('orderBy')(o.disciplines, 'name');
      var numDisciplines;
      if (e.disciplines.length >= o.disciplines) {
        numDisciplines = e.disciplines.length;
      } else {
        numDisciplines = o.disciplines.length;
      }
      for (var i = 0; i < numDisciplines; i++) {
        var eD = typeof e.disciplines[i] === 'object' ? e.disciplines[i] : {};
        var oD = typeof o.disciplines[i] === 'object' ? o.disciplines[i] : {}; 
        eD.name = dL(oD.name, eD.name);
        e.disciplines[i] = eD;   
      }

      // Sectors section.
      e.sectors = $filter('orderBy')(e.sectors, 'name');
      o.sectors = $filter('orderBy')(o.sectors, 'name');
      var numDisciplines;
      if (e.sectors.length >= o.sectors) {
        numDisciplines = e.sectors.length;
      } else {
        numDisciplines = o.sectors.length;
      }
      for (var i = 0; i < numDisciplines; i++) {
        var eD = typeof e.sectors[i] === 'object' ? e.sectors[i] : {};
        var oD = typeof o.sectors[i] === 'object' ? o.sectors[i] : {}; 
        eD.name = dL(oD.name, eD.name);
        e.sectors[i] = eD;   
      }

      // Contacts section.
      // TODO: needs to be fixed: primary contact id...
      e.contacts = $filter('orderBy')(e.contacts, 'id');
      o.contacts = $filter('orderBy')(o.contacts, 'id');
      var numEquipment = null;
      if (e.contacts.length >= o.contacts.length) {
        numEquipment = e.contacts.length;
      } else {
        numEquipment = o.contacts.length;
      }
      for (var i = 0; i < numEquipment; i++) {
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

      // Equipment section.
      e.equipment = $filter('orderBy')(e.equipment, 'id');
      o.equipment = $filter('orderBy')(o.equipment, 'id');
      var numContacts = null;
      if (e.equipment.length >= o.equipment.length) {
        numContacts = e.equipment.length;
      } else {
        numContacts = o.equipment.length;
      }
      for (var i = 0; i < numContacts; i++) {
        var eE = typeof e.equipment[i] === 'object' ? e.equipment[i] : {};
        var oE = typeof o.equipment[i] === 'object' ? o.equipment[i] : {};
        eE.type = dW(oE.type, eE.type);
        eE.manufacturer = dW(oE.manufacturer, eE.manufacturer);
        eE.model = dW(oE.model, eE.model);
        eE.purpose = dW(oE.purpose, eE.purpose, true);
        eE.specifications = dW(oE.specifications, eE.specifications, true);
        eE.yearPurchased = dW(oE.yearPurchased, eE.yearPurchased);
        eE.yearManufactured = dW(oE.yearManufactured, eE.yearManufactured);
        eE.isPublic = eE.isPublic ? 'Public' : 'Hidden';
        oE.isPublic = oE.isPublic ? 'Public' : 'Hidden';
        eE.isPublic = dW(oE.isPublic, eE.isPublic);
        eE.hasExcessCapacity = eE.hasExcessCapacity ? 'Yes' : 'No';
        oE.hasExcessCapacity = oE.hasExcessCapacity ? 'Yes' : 'No';
        eE.hasExcessCapacity = dW(oE.hasExcessCapacity, eE.hasExcessCapacity);
        eE.keywords = dW(oE.keywords, eE.keywords);
        e.equipment[i] = eE;
      }

      // Set up promise.
      e.$promise = $q.resolve(e);
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
      // Check that records are valid for comparison.
      if (editedFr.state != 'PENDING_EDIT_APPROVAL'
          || editedFr.facilityId != originalFr.facilityId
          || (originalFr.state != 'PUBLISHED' 
          && originalFr.state != 'PUBLISHED_EDIT')) {
        $scope._httpError(404);
        return;
      }

      var originalF = Repository.getFacility(originalFr);
      var editedF = Repository.getFacility(editedFr);

      $q.all([editedF.$promise, originalF.$promise]).then(function() {
        $scope.facility = $scope.compare(originalF, editedF);        
      });
    });
  }
]);
