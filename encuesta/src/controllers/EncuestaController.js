/**
* EncuestaController
* Controlador
*/
app.controller("EncuestaController", function EncuestaController($scope, $http, Utils){
  /*
  $http.get('beneficiarios/consultar_preguntas.php').success(function(data) {
	//$scope.paginacion    = data[0].info
	$scope.preguntas    = data;
	//$scope.preguntas    = data.pregs;
	//$scope.total_rtas   = data.total_rtas;
	console.log(data);
  });
  */
  //console.log($scope.datos);
  		$scope.NewPagina = function( dest ){
		Utils.NewPagina( dest )
	}
	
	$scope.goTo = function( dest ){
		Utils.goTo( dest )
	}
})
