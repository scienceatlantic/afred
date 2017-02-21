describe('Facility Edit Form', function() {
  var FacilityForm = require('./exports/facilities-form.js');
  var chance = new (require('chance'))();
  var admin = new (require('./exports/admin.js'));
  var adminFacilities = new (require('./exports/admin-facilities.js'));
  var timeout = 0; // In milliseconds.

  timeout = 120000;
  it('should submit a facility, approve it, and then edit it', function() {
    var form = new FacilityForm({
      numContacts: 0,
      numEquipment: 1
    });
    form.get();
    form.input();
    form.preview();
    form.submit();

    admin.login();

    adminFacilities.get(form.facility.name, 'PENDING_APPROVAL');
    adminFacilities.approve();
    adminFacilities.getEdit();

    expect(browser.getTitle()).toEqual('Update Facility | AFRED');

    form.preview();
    form.submit();
  }, timeout);
});
