describe('Facility Submission Form', function() {
  var FacilityForm = require('./exports/facilities-form.js');
  var chance = new (require('chance'))();
  var timeout = 0; // In milliseconds.

  it('should see title', function() {
    (new FacilityForm()).get(true);
    expect(browser.getTitle()).toEqual('Submit Facility | AFRED');
  });

  timeout = 600000;
  it('should submit facility', function() {
    var form = new FacilityForm({ 
      numContacts: chance.natural({ min: 0, max: 5 }), 
      numEquipment: chance.natural({ min: 1, max: 8 }) 
    });

    form.get();
    form.input();
    form.preview();
    form.submit();
  }, timeout);

  timeout = 120000;
  it('should submit facility with only required data', function() {
    var form = new FacilityForm({
      numContacts: 0,
      numEquipment: 1,
      facility: {
        name: '',
        organizationId: '',
        provinceId: '',
        description: ''
      },
      primaryContact: {
        firstName: '',
        lastName: '',
        email: '',
        telephone: '',
        position: ''
      },
      equipment: {
        type: '',
        purpose: '',
        excessCapacity: '',
        isPublic: ''
      }
    });

    form.get();
    form.input();
    form.preview();
    form.submit();
  }, timeout);
  
  timeout = 600000;
  it('should submit facility while testing out each field\'s maximum length', 
    function() {
      var form = new FacilityForm({
        numContacts: chance.natural({ min: 2, max: 3 }),
        numEquipment: chance.natural({ min: 2, max: 3 }),
        facility: {
          name: 200,
          city: 150,
          organizationId: '',
          provinceId: '',
          website: 2083,
          description: 1999
        },
        primaryContact: {
          firstName: 50,
          lastName: 50,
          email: 254,
          telephone: '',
          position: 100,
          website: 2083,
          extension: chance.natural({ min: 1000000000, max: 9999999999 })
        },
        contact: {
          firstName: 50,
          lastName: 50,
          email: 254,
          telephone: '',
          position: 100,
          website: 2083,
          extension: chance.natural({ min: 1000000000, max: 9999999999 })
        },
        equipment: {
          type: 200,
          manufacturer: 100,
          model: 100,
          purpose: 1999,
          specifications: 1999,
          yearPurchased: '',
          yearManufactured: '',
          excessCapacity: '',
          isPublic: '',
          keywords: 500
        }
      });

      form.get();
      form.input();
      form.preview();
      form.submit();
    }, 
  timeout);
  
  timeout = 600000;
  it('should submit a new facility with a new organization', function() {
    var form = new FacilityForm({
      numContacts: chance.natural({ min: 1, max: 2 }),
      numEquipment: chance.natural({ min: 2, max: 3 }),
      facility: {
        name: '',
        city: '',
        organizationId: -1,
        provinceId: '',
        website: '',
        description: ''
      },
    });

    form.get();
    form.input();
    form.preview();
    form.submit();
  }, timeout);
});
