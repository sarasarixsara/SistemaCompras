/**
* BeneficiariosListController
* Controlador del listado de beneficiarios
*/
function BeneficiariosListController($scope, $routeParams, $http, Utils) {
	/*if($scope.Beneficiarios.length==0){
	  $http.get('beneficiarios/beneficiarios_lista.php').success(function(data) {
		$scope.beneficiarios = data[0].dato;
		$scope.paginacion    = data[0].info
	  });
	}else{
		//console.log($scope.Beneficiarios);
	}
	*/
	console.log("BeneficiariosListController vacio");
	/*	$scope.Newpagina = function( pag, $scope){
		Utils.Newpagina( pag,$scope );
	}
	*/
	/*
			$scope.Votar = function( action, val, index ){
		}
		*/
	$scope.beneficiario = {};
  	$scope.Search = function(){
		  /*$http.post('beneficiarios/beneficiarios_lista.php?nombre=alina', {msg:'hello word!'}).success(function(data) {
			$scope.beneficiarios = data[0].dato;
			$scope.paginacion    = data[0].info
		  });
		  */
		  
		  
			var req = {
			 method: 'POST',
			 url: 'beneficiarios/beneficiarios_lista.php',
			 headers: {
			   //'Content-Type': 'application/x-www-form-urlencoded'
			   'Content-Type': 'application/json'
			 },
			 data: { filtros: $scope.beneficiario },
			}

			$http(req).success(function(datos){
				
			}).error(function(){
				alert('se ha presentado un error');
			});	

					//$location.url("/");
				}			
		
		
	
}