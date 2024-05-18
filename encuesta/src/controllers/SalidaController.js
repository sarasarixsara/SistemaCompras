/**
* SalidaController
* Controlador del listado de beneficiarios
*/
function SalidaController($scope, $routeParams, $http, Datas, Utils,$location) {
console.log(Datas.dataenv);	
$scope.IdEncuesta = $("#IdEncuesta").val();
//console.log(Datas);	
//console.log('as'+$scope.IdEncuesta);
if(Datas.dataenv == undefined){
	console.log("entra por el primero");
	if($scope.IdEncuesta == undefined){
		Utils.goTo('#/')
	}else{
		if($scope.IdEncuesta >0){
		Utils.goTo('iniciar/'+$scope.IdEncuesta)
		}
	}
}else{
	console.log("entra por el segundo");
	console.log("esto es scope.idencuesta "+$scope.IdEncuesta);
	 if($scope.IdEncuesta>0){
	    $.ajax({
	            type: "POST",
	            url: "beneficiarios/encuesta_guardar.php?tipoGuardar=saveEncuesta&codenc="+$scope.IdEncuesta,
	            dataType: 'json',
				success : function(r){
					 //alert('orden de cotizacion creada codigo '+r);
					 
				},
				error   : callback_error,	    
	            data: { json: JSON.stringify(Datas.dataenv) }
	        });
	 }
   }
}