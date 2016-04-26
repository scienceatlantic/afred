'use strict';

// Input format: YYYY-MM-DD HH:MM:SS (Note: that time portion is optional).
angular.module('afredApp').filter('afStrToDate',
  function() {
    return function(dateString) {
      var dt = dateString.split(' ');
      var d = dt[0].split('-');
      var t = dt.length > 1 ? dt[1].split(':') : null;
      
      if (d && t) {
        return new Date(d[0], d[1], d[2], t[0], t[1], t[2]);  
      } else {
        return new Date(d[0], d[1], d[2]);
      }
    };
  }
);
