describe('AFRED homepage', function() {
  var EC = protractor.ExpectedConditions;

  it('should see title', function() {
    browser.get(browser.baseUrl + '/');

    expect(browser.getTitle()).toEqual('Home | AFRED');
  });

  it('should try to search for something and redirect to the search page', 
    function() {
      var query = 'microscopes';         

      element(by.model('search.q')).sendKeys(query);

      $('button[type="submit"]').click();

      expect(browser.getTitle()).toEqual('Search | AFRED');

      $('p[data-ng-if="search.results.length"], ' + 
        'p[data-ng-if="!search.results.length"]').getText()
        .then(function(text) {
          var results = '';
          var total = parseInt(text.replace(/^\D+/g, ''));
          
          if (total == 1) {
            results = EC.textToBePresentInElement(
              $('p[data-ng-if="search.results.length"]'),
              'We found a total of ' + total + ' piece of equipment for \'' +
              query + '\'');
          } else if (total > 1) {
            results = EC.textToBePresentInElement(
              $('p[data-ng-if="search.results.length"]'),
              'We found a total of ' + total + ' pieces of equipment for \'' +
              query + '\'');
          } else {
            results = EC.textToBePresentInElement(
              $('p[data-ng-if="!search.results.length"]'), 
              'Sorry, we couldn\'t find anything for \'' + query + '\'');
          }

          browser.wait(results, 5000);          
        });      
    }
  );
});
