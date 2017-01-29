function Admin() {}

Admin.prototype.login = function(username, password) {
  var deferred = protractor.promise.defer();

  username = username || 'prasad@scienceatlantic.ca';
  password = password || 'password';

  browser.get(browser.baseUrl + '/login');

  $('input[name="email"]').sendKeys(username);
  $('input[name="password"]').sendKeys(password);
  $('button[type="submit"]').click();

  var e = element.all(by.cssContainingText('div', 'Admin / Dashboard'));
  browser.wait(protractor.ExpectedConditions.visibilityOf(e.get(0)), 5000)
    .then(function() {
      deferred.fulfill();
    }
  );

  return deferred.promise;
};

/*Admin.prototype.logout = function() {
  var deferred = protractor.promise.defer();

  $('').click().then(function() {
    deferred.fulfill();
  });

  return deferred.promise;
};*/

module.exports = Admin;
