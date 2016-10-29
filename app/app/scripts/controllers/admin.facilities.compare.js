'use strict';

angular.module('afredApp').controller('AdminFacilitiesCompareController', [
  '$scope',
  'JsDiff',
  '$q',
  '$filter',
  function($scope,
           JsDiff,
           $q,
           $filter) {
    /* ---------------------------------------------------------------------
     * Functions.
     * --------------------------------------------------------------------- */
    
    /**
     * TODO: comments
     */
    $scope.compare = function(original, edit) {  
      // Aliases to shorten code.
      var o = original, e = edit, dW = JsDiff.words, dL = JsDiff.lines;

      // Facility section.
      e.name = dW(o.name, e.name);
      e.organization.name = dW(o.organization.name, e.organization.name);
      e.city = dW(o.city, e.city);
      e.province.name = dW(o.province.name, e.province.name);
      e.website = dW(o.website, e.website);
      e.description = dW(o.description, e.description, true);

      // Disciplines section.
      e.disciplines = $filter('orderBy')(e.disciplines, 'id');
      o.disciplines = $filter('orderBy')(o.disciplines, 'id');
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
      e.sectors = $filter('orderBy')(e.sectors, 'id');
      o.sectors = $filter('orderBy')(o.sectors, 'id');
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
        eE.yearPurchase = dW(oE.yearPurchase, eE.yearPurchase);
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

      return e;
    };

    /* ---------------------------------------------------------------------
     * Initialisation code.
     * --------------------------------------------------------------------- */
    
    /**
     * TODO: comments.
     */
    $scope.facility = {};

    // Get facility repository records.
    var originalFr = $scope.facilities.getRevision(
        $scope._stateParams.originalFrId);
    var editedFr = $scope.facilities.getRevision(
        $scope._stateParams.editedFrId);

    $q.all([originalFr.$promise, editedFr.$promise]).then(function() {
      if (editedFr.state != 'PENDING_EDIT_APPROVAL') {
        $scope._httpError(404);
        return;
      } else if (originalFr.state != 'PUBLISHED' 
                 && originalFr.state != 'PUBLISHED_EDIT') {
        $scope._httpError(404);
        return;
      } else if (editedFr.facilityId != originalFr.facilityId) {
        $scope._httpError(404);
        return;
      }

      var originalF = $scope.facilities.getFromRevisionData(originalFr);
      var editedF = $scope.facilities.getFromRevisionData(editedFr);

      $q.all([editedF.$promise, originalF.$promise]).then(function() {
        $scope.facility = $scope.compare(originalF, editedF);
      });
    });
  }
]);
