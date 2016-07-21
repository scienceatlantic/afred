'use strict';

/**
 * @fileoverview Formats a datetime string into a JavaScript Date object.
 * 
 * The filter expects a string in this format: YYYY-MM-DD HH:MM:SS. The time
 * portion is optional.
 * 
 * Examples of valid strings:
 * '2016-01'
 * '2016-01-28'
 * '2016-01-28 13:15'
 * '2016-01-28 16:15:30'
 * 
 * @see https://docs.angularjs.org/api/ng/filter/filter
 * @see https://docs.angularjs.org/guide/filter
 * @see https://developer.mozilla.org/en/docs/Web/JavaScript/Reference/Global_Objects/Date
 */

angular.module('afredApp').filter('afStrToDate',
  function() {
    return function(datetimeString, returnParsedStr) {
      // If falsy, return empty string.
      if (!datetimeString) {
        return '';  
      }
      
      // Parse the string.
      // dt[0] = date, dt[1] = time
      var dt = datetimeString.split(' ');
      // d[0] = YYYY, d[1] = MM, d[2] = DD
      var d = dt[0].split('-');
      // t[0] = HH, t[1] = MM, t[2] = SS
      var t = dt.length > 1 ? dt[1].split(':') : [];

      // If string is invalid, return empty string (i.e year and month not 
      // provided).
      if (d.length < 2) {
        return '';
      }
      
      // Months start from 0 - 11 for the Date() constructor in JavaScript.
      d[1] = d[1] - 1;
      
      // Full datetime.
      if (d.length == 3 && t.length == 3) {
        return new Date(d[0], d[1], d[2], t[0], t[1], t[2]);  
      }

      // Datetime (without seconds). 
      if (d.length == 3 && t.length == 2) {
        return new Date(d[0], d[1], d[2], t[0], t[1]);
      } 
      
      // Full date
      if (d.length == 3) {
        return new Date(d[0], d[1], d[2]);
      }

      // Date (year and month only).
      return new Date(d[0], d[1]);
    };
  }
);
