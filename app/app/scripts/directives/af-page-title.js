'use strict';

/**
 * @fileoverview Updates the 'title' HTML element of a page.
 * 
 * Example usage:
 * <p data-af-page-title="about"></p>
 * 
 * @see https://docs.angularjs.org/guide/directive
 */

angular.module('afredApp').directive('afPageTitle', [
  '$rootScope',
  function($rootScope) {
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
       * 'data-base-title' - Attribute on the 'title' element.
       * 
       * If found, the title will be updated like this:
       * title + ' | ' + data-base-title
       * 
       * 'data-separator' - Attribute on the 'title' element. Default is ' | '.
       * This attribute requires that the 'data-base-title' attribute be set 
       * otherwise this attribute is ignored.
       * 
       * If found, the title will be updated like this:
       * title + separator + data-base-title
       */
      link: function($scope, element, attrs) {
        var titleElement = document.getElementsByTagName('title')[0];
        var baseTitle = titleElement.getAttribute('data-base-title');
        var separator = titleElement.getAttribute('data-separator');

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
          if (baseTitle) {
              if (separator) {
                  titleElement.innerHTML += separator + baseTitle;
              } else {
                  titleElement.innerHTML += ' | ' + baseTitle;
              }
          }
        };
      }
    };
  }
]);
