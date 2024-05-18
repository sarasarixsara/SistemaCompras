<?php 
//var_dump(getConfig("dev"));
function getConfig($entorno){
	$response = array();
	if($entorno == 'dev'){
		$filejson = file_get_contents("http://localhost/SistemaCompras/config_dev.json");
		$response = json_decode($filejson, true);
	}	
	if($entorno == 'env'){
		$filejson = file_get_contents("http://localhost/SistemaCompras/config_env.json");
		$response = json_decode($filejson, true);
	}
	return($response);
}