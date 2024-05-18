'use strict';
angular.module('app')
	.factory('Datas', function ( $location, $rootScope, $http ) {
		var servicedata = {
			EnviarEnc: function ( ) {

				  return {
					dataenv: {}
					//dataenv: pregs
				  };
			}			
		}
		return servicedata
	})