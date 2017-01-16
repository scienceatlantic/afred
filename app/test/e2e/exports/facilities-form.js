var chance = new (require('chance'))();
var helper = require('./helper.js');

const FACILITY_NAME = 200;
const FACILITY_CITY = 150;
const FACILITY_ORGANIZATION = 50; 
const FACILITY_WEBSITE = 2083;
const FACILITY_DESCRIPTION = 2000;

const CONTACTS_MIN = 0; 
const CONTACTS_MAX = 9; // Does not include mandatory primary contact.
const CONTACTS_FIRST_NAME = 50;
const CONTACTS_LAST_NAME = 50;
const CONTACTS_EMAIL = 254;
const CONTACTS_TELEPHONE = 10;
const CONTACTS_EXTENSION = 10;
const CONTACTS_POSITION = 100;
const CONTACTS_WEBSITE = 2083;

const EQUIPMENT_MIN = 1;
const EQUIPMENT_MAX = 50;
const EQUIPMENT_TYPE = 200;
const EQUIPMENT_MANUFACTURER = 100;
const EQUIPMENT_MODEL = 100;
const EQUIPMENT_PURPOSE = 2000;
const EQUIPMENT_SPECIFICATIONS = 2000;
const EQUIPMENT_MIN_YEAR = 1900;
const EQUIPMENT_MAX_YEAR = 2099;
const EQUIPMENT_KEYWORDS = 500;

function FacilityForm(opts) {
  opts = opts || {};
  var self = this;
  var deferred = protractor.promise.defer();
  self.isReady = deferred.promise;

  self.organization = {};

  self.facility = {};
  self.facility.id = null;
  self.facility.name = get(opts, 'facility', 'name', 'word');
  self.facility.organizationId = get(opts, 'facility', 'organizationId', 'id');
  if (self.facility.organizationId === -1) {
    self.organization.name = get(opts, 'organization', 'name', 'word');
  }
  self.facility.provinceId = get(opts, 'facility', 'provinceId', 'id');
  self.facility.city = get(opts, 'facility', 'city', 'word');
  self.facility.website = get(opts, 'facility', 'website', 'url');
  self.facility.description = get(opts, 'facility', 'description', 'paragraph');
  
  self.primaryContact = {};
  self.primaryContact.id = null;
  self.primaryContact.facilityId = null;
  self.primaryContact.firstName = get(opts, 'primaryContact', 'firstName', 'firstName');
  self.primaryContact.lastName = get(opts, 'primaryContact', 'lastName', 'lastName');
  self.primaryContact.email = get(opts, 'primaryContact', 'email', 'email');
  self.primaryContact.telephone = get(opts, 'primaryContact', 'telephone', 'number', 1000000000, 9999999999);
  self.primaryContact.extension = get(opts, 'primaryContact', 'extension', 'number', 1, 9999999999);
  self.primaryContact.position = get(opts, 'primaryContact', 'position', 'word');
  self.primaryContact.website = get(opts, 'primaryContact', 'website', 'url');

  self.contacts = [];
  if (!opts.hasOwnProperty('numContacts')) {
    self.numContacts = chance.natural({ min: 0, max: CONTACTS_MAX });
  } else {
    self.numContacts = opts.numContacts;
  }
  helper.getRange(self.numContacts).forEach(function(i) {
    self.contacts.push({
      id: null,
      facilityId: null,
      firstName: get(opts, 'contact', 'firstName', 'firstName'),
      lastName: get(opts, 'contact', 'lastName', 'lastName'),
      email: get(opts, 'contact', 'email', 'email'),
      telephone: get(opts, 'contact', 'telephone', 'number', 1000000000, 9999999999),
      extension: get(opts, 'contact', 'extension', 'number', 1, 9999999999),
      position: get(opts, 'contact', 'position', 'word'),
      website: get(opts, 'contact', 'website', 'url')      
    });
  });

  self.equipment = [];
  if (!opts.hasOwnProperty('numEquipment')) {
    self.numEquipment = chance.natural({ min: 1, max: EQUIPMENT_MAX });
  } else {
    self.numEquipment = opts.numEquipment
  }
  helper.getRange(self.numEquipment).forEach(function(i) {
    self.equipment.push({
      id: null,
      facilityId: null,
      type: get(opts, 'equipment', 'type', 'word'),
      manufacturer: get(opts, 'equipment', 'manufacturer', 'word'),
      model: get(opts, 'equipment', 'model', 'word'),
      purpose: get(opts, 'equipment', 'purpose', 'paragraph'),
      specifications: get(opts, 'equipment', 'specifications', 'paragraph'),
      yearPurchased: get(opts, 'equipment', 'yearPurchased', 'number', 1900, 2099),
      yearManufactured: get(opts, 'equipment', 'yearManufactured', 'number', 1900, 2099),
      excessCapacity: get(opts, 'equipment', 'excessCapacity', 'number', 0, 1),
      isPublic: get(opts, 'equipment', 'isPublic', 'number', 0, 1),
      keywords: get(opts, 'equipment', 'keywords', 'sentence')
    });
  });

  deferred.fulfill();
}

FacilityForm.prototype.get = function(dontWait) {
  browser.get(browser.baseUrl + '/facilities/form/create');

  // Wait until form is ready.
  if (!dontWait) {
    var e = $('input[name="facilityName"]');
    browser.wait(protractor.ExpectedConditions.visibilityOf(e));
  }
};

FacilityForm.prototype.input = function() {
  this.inputFacility();
  this.inputContacts();
  this.inputEquipment();
};

FacilityForm.prototype.inputFacility = function() {
  var self = this; // Preserve context for callbacks.

  browser.wait(self.isReady, 5000);

  var rich = ['description'];
  var ignore = rich.concat(['id', 'organizationId', 'provinceId', 'disciplines', 
    'sectors']);
  input(self.facility, 'facility', null, 'input', null, ignore);

  input(self.facility, 'facility', null, 'rich', rich);

  if (self.facility.organizationId !== '') {
    var selector = 'select[name="facilityOrganization"]';
    var index = 1; // Remember that the 0-index of a dropdown is blank.
    var iterations = 0;

    $(selector).$$('option').then(function(organizations) {
      callback(false);

      function selectOrganization(index, getOther) {
        var deferred = protractor.promise.defer(); 
        var promise = deferred.promise;

        organizations[index].getText().then(function(name) {
          if (getOther ? name === 'Other' : name !== 'Other') {
            organizations[index].click().then(function() {
              if (name === 'Other') {
                expect($('input[name="facilityOrganizationName"')
                  .sendKeys(self.organization.name).getAttribute('value'))
                  .toEqual(self.organization.name);
              }
            });
            return deferred.fulfill(true);
          }
          deferred.fulfill(false);
        });

        return promise;
      }

      function callback(found) {
        if (!found) {
          if (self.facility.organizationId === -1) {
            index++;
          } else {
            index = helper.getRandomInt(1, organizations.length);
          }

          if (iterations++ > 100) {
            throw 'Maximum number of `iterations` reached';
          }

          selectOrganization(index, self.facility.organizationId === -1)
            .then(callback);
        }
      }
    });
  }

  if (self.facility.provinceId !== '') {
    $('select[name="facilityProvince"]').$$('option').then(function(provinces) {
      provinces[helper.getRandomInt(1, provinces.length)].click();
    });
  }

  ['Disciplines', 'Sectors'].forEach(function(item, index) {
    var selector = 'input[name^="facility' + item + 'C"]';
    $$(selector).then(function(cb) {
      var len = helper.getRandomIntInclusive(1, cb.length);

      helper.getRange(len).forEach(function(i) {
        cb[i].click();
      });

      expect($$(selector + ':checked').count()).toEqual(len);
    });
  });
};

FacilityForm.prototype.inputContacts = function() {
  var self = this; // Preserve context for callbacks.
  var ignore = ['id', 'facilityId'];

  browser.wait(self.isReady, 5000);

  input(self.primaryContact, 'contact', 0, 'input', null, ignore);

  self.contacts.forEach(function(contact, i) {
    $('button[data-ng-click="form.addContact()"]').click();

    input(contact, 'contact', (i + 1), 'input', null, ignore);
  });
};

FacilityForm.prototype.inputEquipment = function() {
  var self = this; // Save context for promise callbacks.

  browser.wait(self.isReady, 5000);

  var rich = ['purpose', 'specifications'];
  var radio = ['excessCapacity', 'isPublic'];
  var ignore = ['id', 'facilityId'].concat(rich).concat(radio);
  self.equipment.forEach(function(equipment, i) {
    input(equipment, 'equipment', i, 'input', null, ignore);
    input(equipment, 'equipment', i, 'radio', radio);
    input(equipment, 'equipment', i, 'rich', rich);

    if (i < (self.numEquipment - 1)) {
      helper.click($('button[data-ng-click="form.addEquipment()"]'), 0, -400);
    }
  });
};

FacilityForm.prototype.preview = function() {
  var self = this;

  browser.wait(self.isReady, 5000);

  browser.wait(helper.click($('button[data-ng-click="preview()"]')), 100);

  ['facility', 'primaryContact', 'contacts', 'equipment'].forEach(function(i) {
    function checkPreview(obj) {
      for (var prop in obj) {
        if (obj[prop] !== '' && obj[prop] !== null && obj[prop] !== -1) {
          var text = '';

          switch (prop) {
            case 'telephone':
              text = helper.telephone(obj[prop]);
              break;

            case 'excessCapacity':
              text = obj[prop] ? 'Yes' : 'No';
              break;

            case 'isPublic':
              text = obj[prop] ? 'Public' : 'Private';
              break;

            default:
              text = obj[prop];
          }

          expect(element.all(by.cssContainingText('div', text.trim()))
            .count())
            .not
            .toBeLessThan(1);
        }
      }
    }

    if (Array.isArray(self[i])) {
      self[i].forEach(function(obj) {
        checkPreview(obj);
      });
    } else {
      checkPreview(self[i]);
    }
  });

  var submitBtn = protractor.ExpectedConditions
    .visibilityOf($('button[data-ng-click="submit()"]'));

  browser.wait(submitBtn, 5000);
};

FacilityForm.prototype.submit = function() {
  var self = this;

  browser.wait(self.isReady, 5000);

  browser.wait(helper.click($('input[name="termsOfService"]')), 100);

  browser.wait(helper.click($('input[name="privacyPolicy"]')), 100);

  browser.wait(helper.click($('button[data-ng-click="submit()"]')), 100);

  var successMsg = element(by.cssContainingText('p',
    'Thank you! Your information has been successfully submitted.'));

  browser.wait(successMsg, 6000);
};

function input(obj, prefix, index, type, only, ignore) {
  only = only || [];
  ignore = ignore || [];

  // We have to put the code in a separate function to so that each `prop`
  // instance is preserved in the promise callback.
  for (prop in obj) {
    insert(prop);
  }

  function insert(prop) {
    if ((only.length && only.indexOf(prop) < 0) || ignore.indexOf(prop) >= 0) {
      return;
    }

    var selector = prefix 
                  + (prop.substr(0, 1).toUpperCase() + prop.substr(1)) 
                  + (Number.isInteger(index) ? index : '') 
                  + '"]';
    var val = obj[prop];

    switch (type) {
      // For Text Angular fields.
      case 'rich':
        selector = 'text-angular[name="' + selector;
        $(selector).click().then(function() {
          browser.actions().sendKeys(val).perform().then(function() {
            expect($(selector).getText()).toEqual(val);
          });
        });
        break;
      case 'radio':
        selector = 'input[name^="' + selector;
        if (val !== '') {
          $$(selector).then(function(radios) {
            radios[val].click();
            expect($$(selector + ':checked').count()).toEqual(1);
          });
        }
        break;
      case 'input':
      default:
        selector = 'input[name="' + selector;
        expect($(selector).sendKeys(val).getAttribute('value')).toEqual(val);
    }
  }
}

function get(obj, prop, subProp, type, min, max) {
  function value(type, val) {
    switch (type) {
      case 'word':
        if (val) {
          var text = chance.sentence({ words: 2000 });
          return text.substr(0, val)
        }
        return chance.word();

      case 'sentence':
        if (val) {
          var text = chance.sentence({ words: 2000 });
          return text.substr(0, val);
        }
        return chance.sentence();

      case 'paragraph':
        if (val) {
          var text = chance.paragraph({ sentences: 500 });
          return text.substr(0, val);
        }
        return chance.paragraph();
      
      case 'firstName':
        if (val) {
          var text = chance.sentence({ sentences: 500 });
          return text.substr(0, val);
        }
        return chance.first();

      case 'lastName':
        if (val) {
          var text = chance.sentence({ sentences: 500 });
          return text.substr(0, val);
        }
        return chance.last();

      case 'url':
        if (val) {
          var text = chance.url() + chance.word({ length: 3000 });
          return text.substr(0, val);
        }
        return chance.url();

      case 'email':
        if (val) {
          var text = chance.word({length: 300 });
          var email = chance.email();
          return text.substr(0, val - email.length) + email;
        }
        return chance.email();

      case 'number':
        if (val) {
          return String(val);
        }
        return String(chance.natural({ min: min, max: max }));

      case 'id':
        if (val) {
          return val;
        }
        return null;

      default:
        throw 'Invalid `type`';
    }
  }

  if (!obj) {
    return value(type);
  } else if (!obj.hasOwnProperty(prop)) {
    return value(type);
  } else if (!obj[prop].hasOwnProperty(subProp)) {
    return '';
  } else if (!Number.isInteger(obj[prop][subProp])) {
    return value(type);
  } else {
    return value(type, obj[prop][subProp]);
  }
}

module.exports = FacilityForm;
