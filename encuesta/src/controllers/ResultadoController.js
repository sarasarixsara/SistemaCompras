/**
* BeneficiariosListPageController
* Controlador del listado de beneficiarios
*/
function ResultadoController($scope, $routeParams, $http, Datas, Utils,$location) {
$scope.IdEncuesta = $routeParams.IdEncuesta;
$("#IdEncuesta").val($routeParams.IdEncuesta);
/*if($scope.preguntas == undefined){
				setTimeout(function(){
					Utils.goTo('#/')
				}, 50)	
}
*/
//console.log('aaaaa'+Datas);	
//console.log(Datas);	
/*
	console.log("esto es preguntass "+$scope.preguntas)
	console.log("pregs "+$scope.preguntas);
	
	if($scope.preguntas.length ){
				if( _.filter( $scope.preguntas, function( v ){ return v.choice !== null } ).length < $scope.minResponses ){
					setTimeout(function(){
						Utils.goTo('resultado')
					}, 50)					
				}
	}
	*/
}