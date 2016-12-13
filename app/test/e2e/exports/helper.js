module.exports = {
  // Random functions obtained from:
  // https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Math/random
  // Returns a random integer between min (included) and max (excluded)
  getRandomInt: function (min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min)) + min;
  },

  // Returns a random integer between min (included) and max (included)
  getRandomIntInclusive: function(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min + 1)) + min;
  },

  // Similar to Python's `range` function.
  getRange: function(len, start, step) {
    start = Number.isInteger(start) ? start : 0;
    step = Number.isInteger(step) ? step : 1;

    if (start > len) {
      throw '`start` must be less than `len`';
    }

    if (step < 1) {
      throw '`step` must be more than 0';
    }

    var arr = [];
    for (var i = start; i < len; i += step) {
      arr.push(i);
    }
    return arr;
  },

  click: function(element, offsetX, offsetY) {
    var deferred = protractor.promise.defer();
    var promise = deferred.promise;

    element.getLocation()
      .then(function(loc) {
        loc.x += (offsetX !== undefined ? offsetX : 0);
        loc.y += (offsetY !== undefined ? offsetY : 0);

        var scriptStr = 'window.scrollTo(' + loc.x + ',' + loc.y + ');';
        return browser.executeScript(scriptStr);
      })
      .then(function() {
        return element.click();
      })
      .then(function() {
        deferred.fulfill();
      })
      .catch(function(err) {
        throw err;
      });

    return promise;
  },

  // Guaranteed to work.
  safeClick: function(element) {
    return browser.executeScript('arguments[0].click()', element);
  },

  telephone: function(str) {
    if (str && typeof str === 'string' && str.length == 10) {
      return '(' + str.substr(0, 3) + ') ' + str.substr(3, 3) + '-' 
        + str.substr(6, 4);
    }
    throw 'Invalid `str`';
  }
};
