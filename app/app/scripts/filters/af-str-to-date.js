'use strict';

/**
 * @fileoverview Formats a datetime string into a JavaScript Date object.
 * 
 * The filter expects a string in this format: YYYY-MM-DD HH:MM:SS. The time
 * portion is optional.
 * 
 * Examples of valid strings:
 * '2016-01-28'
 * '2016-01-28 12:15:30'
 * 
 * @see https://docs.angularjs.org/api/ng/filter/filter
 * @see https://docs.angularjs.org/guide/filter
 * @see https://developer.mozilla.org/en/docs/Web/JavaScript/Reference/Global_Objects/Date
 */

// Input format: YYYY-MM-DD HH:MM:SS (Note: that time portion is optional).
angular.module('afredApp').filter('afStrToDate',
  function() {
    return function(dateString) {
      // If falsy, return empty string.
      if (!dateString) {
        return '';  
      }
      
      var dt = dateString.split(' ');
      var d = dt[0].split('-');
      var t = dt.length > 1 ? dt[1].split(':') : null;
      
      // Months start from 0 - 11 for the Date() constructor in JavaScript.
      d[1] = d[1] - 1;
      
      if (d && t) {
        return new Date(d[0], d[1], d[2], t[0], t[1], t[2]);  
      } else {
        return new Date(d[0], d[1], d[2]);
      }
    };
  }
);
