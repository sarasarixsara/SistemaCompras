/**
* BeneficiariosListPageController
* Controlador del listado de beneficiarios
*/
function WelcomeController($scope, $routeParams, $http, Datas, Utils,$location) {
$scope.CodEnc = $routeParams.IdEncuesta;
console.log("idencuesta enc  = "+$scope.CodEnc)
if($scope.CodEnc>0){	
  $http.get('beneficiarios/consultar_preguntas.php?CodEnc='+$scope.CodEnc).success(function(data) {
	if(data.pregs.length>0){
	$scope.preguntas    = data;
	$scope.preguntas.IdEncuesta = $routeParams.IdEncuesta;
	$("#IdEncuesta").val($routeParams.IdEncuesta);
	console.log("en wellcomecontroller");
	console.log($scope.preguntas);
	}else{
    $("#IdEncuesta").val('');		
	Utils.goTo('#/')
	}
  });	
}else{
	Utils.goTo('#/')
	$("#IdEncuesta").val('');		
}  

}