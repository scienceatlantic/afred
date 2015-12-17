'use strict';

angular.module('afredApp').filter('afFormatTelephone',
  function() {
    return function(input) {
      if (input && typeof input == 'string' && input.length == 10) {
        return '(' + input.substr(0, 3) + ') ' + input.substr(3, 3) + '-'
          + input.substr(6, 4);
      }
  };
});