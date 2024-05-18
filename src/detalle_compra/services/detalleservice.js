'use strict';
angular.module('app')
	.factory('detalleservice', function ( /*$location, $rootScope, */$http,  $resource, $q, $routeParams ) {
		var UrlPath = 'src/detalle_compra/api.php';
		var UrlDetalles = 'src/detalle_compra/api.php?tipoGuardar=loaddetalleslista'; 
		function loadEstadosAreas(){
			var defered = $q.defer();
			var promise = defered.promise;			
        $http({
                method: 'POST', 
                url: UrlPath+'?tipoGuardar=loadEstadosAreas',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                data: {} ,
            }).success(function(data) {
                defered.resolve(data);
            })
			.error(function(err) {
                defered.reject(err)
            });
			
			return promise;
		}
		function getDetallesLista(info){
			var cpage='';
			if($routeParams.cpage=='undefined'){
				cpage=1;
			}else{
				cpage=$routeParams.cpage;
			}
			var defered = $q.defer();
			var promise = defered.promise;
			
        $http({
                method: 'POST', 
                url: UrlDetalles+'&cpage='+cpage,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                data: { info:info } ,
            }).success(function(data) {
                defered.resolve(data);
            })
			.error(function(err) {
                defered.reject(err)
            });
			
			return promise;			
		}
		function ChangeStatusDetail(info, estado){
			var defered = $q.defer();
			var promise = defered.promise;			
        $http({
                method: 'POST', 
                url: UrlPath+'?tipoGuardar=ChangeStatusDetail&estado='+estado,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                data: {info:info} ,
            }).success(function(data) {
                defered.resolve(data);
            })
			.error(function(err) {
                defered.reject(err)
            });
			
			return promise;
		}
		
		return {
			loadEstadosAreas    : loadEstadosAreas,
			getDetallesLista    : getDetallesLista,
			ChangeStatusDetail  : ChangeStatusDetail
			
		}
	});		