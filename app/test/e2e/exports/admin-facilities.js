var helper = require('./helper.js');

function AdminFacilities() {}

AdminFacilities.prototype.get = function(facility, state) {
  var deferred = protractor.promise.defer();
  find(1);

  function find(page) {
    browser.get(browser.baseUrl 
                + '/admin/facilities/index/'
                + '?state=' + state
                + '&page=' + page);

    element.all(by.cssContainingText('a', facility)).then(function(facilities) {
      if (!facilities.length) {
        return find(++page);
      }
      facilities[0].click().then(function() {
        deferred.fulfill();
      });
    });
  }

  return deferred.promise;
};

AdminFacilities.prototype.approve = function() {
  var deferred = protractor.promise.defer();

  var h1 = element.all(by.cssContainingText('div', 'Record Metadata'));
  browser.wait(protractor.ExpectedConditions.visibilityOf(h1.get(0)), 2000);

  helper.click($('button[data-ng-click="approve();"]'), 0, -400);

  var h2 = element.all(by.cssContainingText('div', 'Please confirm'));
  browser.wait(protractor.ExpectedConditions.visibilityOf(h2.get(0)), 2000);

  helper.click($('button[data-ng-click="modal.close()"]'), 0, -400);

  var h3 = element.all(by.cssContainingText('div', 'Info'));
  browser.wait(protractor.ExpectedConditions.visibilityOf(h3.get(0)), 2000);

  helper.click($('button[data-ng-click="modal.close()"]'), 0, -400)
    .then(function() {
      browser.getCurrentUrl().then(function(url) {
        var id = parseInt(url.substring(url.indexOf('=') + 1));
        deferred.fulfill(id);
      });
    });

  return deferred.promise;
};

AdminFacilities.prototype.reject = function() {

};

AdminFacilities.prototype.getEdit = function(dontWait) {
  helper.click($('button[data-ng-click="edit()"]'), 0, -400);

  var h1 = element.all(by.cssContainingText('div', 'Please confirm'));
  browser.wait(protractor.ExpectedConditions.visibilityOf(h1.get(0)), 2000);

  helper.click($('button[data-ng-click="modal.close()"]'), 0, -400);

  var h2 = element.all(by.cssContainingText('div', 'Info'));
  browser.wait(protractor.ExpectedConditions.visibilityOf(h2.get(0)), 2000);

  helper.click($('button[data-ng-click="modal.close()"]'), 0, -400);

  if (!dontWait) {
    var e = $('input[name="facilityName"]');
    browser.wait(protractor.ExpectedConditions.visibilityOf(e), 4000);
  }
};

module.exports = AdminFacilities;