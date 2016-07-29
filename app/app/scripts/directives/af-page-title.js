'use strict';

/**
 * @fileoverview Updates the 'title' HTML element of a page.
 * 
 * Example usage:
 * In the "head" section:
 * <title data-af-page-title-base="AFRED" data-af-page-title-separator=" | ">AFRED</title>
 * 
 * And somewhere in the "body":
 * <p data-af-page-title="About"></p>
 * 
 * @see https://docs.angularjs.org/guide/directive
 */

angular.module('afredApp').directive('afPageTitle', [
  function() {
    return {
      restrict: 'A',
      replace: true,
      scope: {
        /**
         * Page title.
         * 
         * @type {string} 
         */
        title: '@afPageTitle'
      },
      /**
       * This directive expects that the following be true:
       * 'title' HTML element must exist.
       * 
       * Optionally:
       * 'af-page-title-base' - Attribute on the 'title' element.
       * 
       * If found, the title will be updated like this:
       * title + ' | ' + af-page-title-base
       * 
       * 'af-page-title-separator' - Attribute on the 'title' element. Default 
       * is ' | '. This attribute requires that the 'af-page-title-base' 
       * attribute be set otherwise this attribute is ignored.
       * 
       * If found, the title will be updated like this:
       * title + af-page-title-separator + af-page-title-base
       */
      link: function($scope, element, attrs) {
        var p = 'af-page-title';
        var titleElement = document.getElementsByTagName('title')[0];
        var base = titleElement.getAttribute('data-' + p + '-base');
        var separator = titleElement.getAttribute('data-' + p + '-separator');

        // The attributes can also be retrieved without the 'data-' prefix.
        if (!base) {
          base = titleElement.getAttribute(p + '-base'); 
        }
        if (!separator) {
          separator = titleElement.getAttribute(p + '-separator');
        }

        // First method for updating the page title checks if the 'afPageTitle'
        // property has changed.
        attrs.$observe('afPageTitle', function(pageTitle) {
          if (pageTitle) {
            $scope.updateTitle(pageTitle);
          }
        });

        // Second method for updating the page title checks if the state has
        // has changed (this is required when we are moving back and forth
        // between child states).
        $scope.$on('$stateChangeSuccess', function() {
          if ($scope.title) {
            $scope.updateTitle($scope.title);
          }
        });

        /**
         * Updates the 'title' HTML element.
         * 
         * @param {string} pageTitle Title
         */
        $scope.updateTitle = function(pageTitle) {
          titleElement.innerHTML = pageTitle;
          if (base) {
              if (separator) {
                  titleElement.innerHTML += separator + base;
              } else {
                  titleElement.innerHTML += ' | ' + base;
              }
          }
        };
      }
    };
  }
]);
