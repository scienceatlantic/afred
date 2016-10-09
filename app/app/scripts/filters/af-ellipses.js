'use strict';

/**
 * @fileoverview Trims a string based on a specified `limit` and returns the
 * substring + '...'.
 * 
 * @see https://docs.angularjs.org/api/ng/filter/filter
 * @see https://docs.angularjs.org/guide/filter
 */

angular.module('afredApp').filter('afEllipses', function() {
  return function(str, limit, ellipsesText) {
    str = String(str);
    if (limit && str.length > limit) {
      return str.substring(0, limit) + (ellipsesText || '...');
    }
    return str;
  };
});
