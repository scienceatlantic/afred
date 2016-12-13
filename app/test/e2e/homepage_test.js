describe('AFRED homepage', function() {
  it('should see title', function() {
    browser.get(browser.baseUrl + '/home');

    expect(browser.getTitle()).toEqual('Home | AFRED');
  });
});
