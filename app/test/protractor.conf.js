exports.config = {
  specs: ['e2e/*_test.js'],
  baseUrl: 'http://localhost:9001', //default test port with Yeoman

  // Required so that buttons are clickable.
  // @see http://stackoverflow.com/a/27543468
  onPrepare: function() {
    browser.manage().window().setSize(1280, 720);
  }
}
