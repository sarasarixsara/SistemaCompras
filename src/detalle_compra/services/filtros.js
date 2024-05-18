'use strict';
angular.module('app')
	.factory('Filtros', function ( $location, $rootScope, $http ) {
		var filtroslista = {
			filtrado: function ( ) {

				  return {
					filtro:{},
					fuente:""
					//dataenv: pregs
				  };
			}			
		}
		return filtroslista
	})