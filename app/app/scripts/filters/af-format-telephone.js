'use strict';

/**
 * @fileoverview Formats a string of 10 characters into a telephone number.
 * 
 * Example:
 * '9024007678' --> '(902) 400-7678'
 * 
 * @see https://docs.angularjs.org/api/ng/filter/filter
 * @see https://docs.angularjs.org/guide/filter
 */

angular.module('afredApp').filter('afFormatTelephone',
  function() {
    return function(input) {
      if (input && typeof input == 'string' && input.length == 10) {
        return '(' + input.substr(0, 3) + ') ' + input.substr(3, 3) + '-'
          + input.substr(6, 4);
      }
  };
});