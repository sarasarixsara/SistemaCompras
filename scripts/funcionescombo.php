<?php

/* Archivo para funciones */

function conectaBaseDatos(){
	try{
		$servidor 	= "localhost";		
		$puerto 	= "3306";
		$basedatos 	= "bdcompras";		
		$usuario 	= "compras";		
		$contrasena     = "S4nv0n1f4c10";
		
	
		$conexion = new PDO("mysql:host=$servidor;port=$puerto;dbname=$basedatos",
							$usuario,
							$contrasena,
							array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		
		$conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		return $conexion;
	}
	catch (PDOException $e){
		die ("No se puede conectar a la base de datos". $e->getMessage());
	}
}

function dameCategoria(){
	$resultado = false;
	$consulta = "SELECT * FROM CLASIFICACION  order by CLASNOMB";
	
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	
	try {
		if(!$sentencia->execute()){
			print_r($sentencia->errorInfo());
		}
		$resultado = $sentencia->fetchAll();
		//$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$sentencia->closeCursor();
	}
	catch(PDOException $e){
		echo "Error al ejecutar la sentencia: \n";
			print_r($e->getMessage());
	}
	
	return $resultado;
}

function damePoa(){
	$resultado = false;
	$consulta = "SELECT * FROM POA WHERE POAESTA=1 ";
	
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	
	try {
		if(!$sentencia->execute()){
			print_r($sentencia->errorInfo());
		}
		$resultado = $sentencia->fetchAll();
		//$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$sentencia->closeCursor();
	}
	catch(PDOException $e){
		echo "Error al ejecutar la sentencia: \n";
			print_r($e->getMessage());
	}
	
	return $resultado;
}

function dameCentroCosto(){
	$resultado = false;
	$consulta = "SELECT * FROM POADETA
	             WHERE PODEESTA=1";
	
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	
	try {
		if(!$sentencia->execute()){
			print_r($sentencia->errorInfo());
		}
		$resultado = $sentencia->fetchAll();
		//$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$sentencia->closeCursor();
	}
	catch(PDOException $e){
		echo "Error al ejecutar la sentencia: \n";
			print_r($e->getMessage());
	}
	
	return $resultado;
}

function dameFormaDePago(){
	$resultado = false;
	$consulta = "SELECT *
				FROM criterio_medicion, 
				     criterio, 
					 tipo_criterio 
				WHERE   CRMETICR=CRITCONS 
				AND     TICRID=CRITTICR
				AND     CRMETICR=1";
	
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	
	try {
		if(!$sentencia->execute()){
			print_r($sentencia->errorInfo());
		}
		$resultado = $sentencia->fetchAll();
		//$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$sentencia->closeCursor();
	}
	catch(PDOException $e){
		echo "Error al ejecutar la sentencia: \n";
			print_r($e->getMessage());
	}
	
	return $resultado;
}

function dameGarantia(){
	$resultado = false;
	$consulta = "SELECT *
				FROM criterio_medicion, 
				     criterio, 
					 tipo_criterio 
				WHERE   CRMETICR=CRITCONS 
				AND     TICRID=CRITTICR
				AND     CRMETICR=2";
	
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	
	try {
		if(!$sentencia->execute()){
			print_r($sentencia->errorInfo());
		}
		$resultado = $sentencia->fetchAll();
		//$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$sentencia->closeCursor();
	}
	catch(PDOException $e){
		echo "Error al ejecutar la sentencia: \n";
			print_r($e->getMessage());
	}
	
	return $resultado;
}

function dameSitioEntrega(){
	$resultado = false;
	$consulta = "SELECT *
				FROM criterio_medicion, 
				     criterio, 
					 tipo_criterio 
				WHERE   CRMETICR=CRITCONS 
				AND     TICRID=CRITTICR
				AND     CRMETICR=3";
	
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	
	try {
		if(!$sentencia->execute()){
			print_r($sentencia->errorInfo());
		}
		$resultado = $sentencia->fetchAll();
		//$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$sentencia->closeCursor();
	}
	catch(PDOException $e){
		echo "Error al ejecutar la sentencia: \n";
			print_r($e->getMessage());
	}
	
	return $resultado;
}

function dameTiempoEntrega(){
	$resultado = false;
	$consulta = "SELECT *
				FROM criterio_medicion, 
				     criterio, 
					 tipo_criterio 
				WHERE   CRMETICR=CRITCONS 
				AND     TICRID=CRITTICR
				AND     CRMETICR=4";
	
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	
	try {
		if(!$sentencia->execute()){
			print_r($sentencia->errorInfo());
		}
		$resultado = $sentencia->fetchAll();
		//$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$sentencia->closeCursor();
	}
	catch(PDOException $e){
		echo "Error al ejecutar la sentencia: \n";
			print_r($e->getMessage());
	}
	
	return $resultado;
}

function dameValoragregado(){
	$resultado = false;
	$consulta = "SELECT *
				FROM criterio_medicion, 
				     criterio, 
					 tipo_criterio 
				WHERE   CRMETICR=CRITCONS 
				AND     TICRID=CRITTICR
				AND     CRMETICR=5";
	
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	
	try {
		if(!$sentencia->execute()){
			print_r($sentencia->errorInfo());
		}
		$resultado = $sentencia->fetchAll();
		//$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$sentencia->closeCursor();
	}
	catch(PDOException $e){
		echo "Error al ejecutar la sentencia: \n";
			print_r($e->getMessage());
	}
	
	return $resultado;
}

function dameConvenio(){
	$resultado = false;
	$consulta = "SELECT C.CONVCONS ID,
						C.CONVIDPR PROVEEDOR,
						P.PROVNOMB PROVEEDOR_DES,
						C.CONVCOCO COD_CONVENIO,
						C.CONVCONT COD_CONTRATO,
						C.CONVFEIN FECH_INICIO,
						C.CONVFEFI FECH_FIN,
						C.CONVID COD_PARAMETRO
				 FROM convenios C,
					  proveedores P 
				 WHERE C.CONVIDPR = P.PROVCODI
				 AND  '".date('Y-m-d')."' >= C.CONVFEIN 
				 AND  '".date('Y-m-d')."' <= C.CONVFEFI";	
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	
	try {
		if(!$sentencia->execute()){
			print_r($sentencia->errorInfo());
		}
		$resultado = $sentencia->fetchAll();
		//$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$sentencia->closeCursor();
	}
	catch(PDOException $e){
		echo "Error al ejecutar la sentencia: \n";
			print_r($e->getMessage());
	}
	
	return $resultado;
}

function dameProveedorCompra(){
	$resultado = false;
    $consulta = "SELECT DISTINCT `PROVCODI`,
				        `PROVNOMB` 						 
				FROM   `proveedores`,
                        detalle_requ						
				WHERE                 				
				   DEREPROV = PROVCODI
			   AND DERECOOC = '0'
               AND DEREAPRO = '18'
			
				";	
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	
	try {
		if(!$sentencia->execute()){
			print_r($sentencia->errorInfo());
		}
		$resultado = $sentencia->fetchAll();
		//$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$sentencia->closeCursor();
	}
	catch(PDOException $e){
		echo "Error al ejecutar la sentencia: \n";
			print_r($e->getMessage());
	}
	
	return $resultado;
}

function dameProveedorConvenioCompra(){
	$resultado = false;
    $consulta = "SELECT DISTINCT  P.PROVCODI ,
								  P.PROVNOMB  						 
				FROM   proveedores P,
                        detalle_requ DR,
						convenios C,
                        conve_produc CP
				WHERE  DR.DEREAPRO = '12'                 				
				AND    DR.DERECOOC = '0'
				AND    DR.DEREIOCC = '0'
				AND    C.CONVIDPR=P.PROVCODI
				AND    CP.COPRIDCO=C.CONVCONS
				AND    DR.DERECONV=CP.COPRID
			
				";	
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	
	try {
		if(!$sentencia->execute()){
			print_r($sentencia->errorInfo());
		}
		$resultado = $sentencia->fetchAll();
		//$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$sentencia->closeCursor();
	}
	catch(PDOException $e){
		echo "Error al ejecutar la sentencia: \n";
			print_r($e->getMessage());
	}
	
	return $resultado;
}

function dameEstadosDetalle(){
	$resultado = false;
    $consulta = "SELECT `ESDECODI` CODIGO,
						`ESDENOMB` DESCRIPCION,
						`ESDECOLO`,
						`ESDEFLAG`
				FROM `estado_detalle` 
				WHERE 
				ESDECODI IN(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21)
			
				";	
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	
	try {
		if(!$sentencia->execute()){
			print_r($sentencia->errorInfo());
		}
		$resultado = $sentencia->fetchAll();
		//$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$sentencia->closeCursor();
	}
	catch(PDOException $e){
		echo "Error al ejecutar la sentencia: \n";
			print_r($e->getMessage());
	}
	
	return $resultado;
}

function dameTipoCompra(){
	$resultado = false;
    $consulta = "SELECT `TICOCODI` CODIGO,
					    `TIPONOMB` DESCRIPCION 
				FROM    `tipo_compra`
				WHERE  TICOCODI IN(1,2,3,4)
			
				";	
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	
	try {
		if(!$sentencia->execute()){
			print_r($sentencia->errorInfo());
		}
		$resultado = $sentencia->fetchAll();
		//$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$sentencia->closeCursor();
	}
	catch(PDOException $e){
		echo "Error al ejecutar la sentencia: \n";
			print_r($e->getMessage());
	}
	
	return $resultado;
}

function dameTipoOrdenCompra(){
	$resultado = false;
    $consulta = "SELECT `TOCOCODI` CODIGO,
						`TOCONOMB` DESCRIPCION 
				FROM  `tipoorden_compra` 
						
				";	
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	
	try {
		if(!$sentencia->execute()){
			print_r($sentencia->errorInfo());
		}
		$resultado = $sentencia->fetchAll();
		//$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$sentencia->closeCursor();
	}
	catch(PDOException $e){
		echo "Error al ejecutar la sentencia: \n";
			print_r($e->getMessage());
	}
	
	return $resultado;
}

function dameUnidadMedida(){
	$resultado = false;
    $consulta = "SELECT UNMECONS CODIGO,
					     UNMENOMB DESCRIPCION,
							   UNMESIGL SG,
							   UNMECONV 
						FROM   UNIDAD_MEDIDA
						ORDER BY UNMENOMB
				";	
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	
	try {
		if(!$sentencia->execute()){
			print_r($sentencia->errorInfo());
		}
		$resultado = $sentencia->fetchAll();
		//$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$sentencia->closeCursor();
	}
	catch(PDOException $e){
		echo "Error al ejecutar la sentencia: \n";
			print_r($e->getMessage());
	}
	
	return $resultado;
}

function dameTotalDetalles($req){
	$resultado = false;
    $consulta = "SELECT COUNT(DERECONS) TOTAL 
    			 FROM  DETALLE_REQU 
    			 WHERE DEREREQU = '".$req."'
				";	
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	
	try {
		if(!$sentencia->execute()){
			print_r($sentencia->errorInfo());
		}
		$resultado = $sentencia->fetchAll();
		//$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$sentencia->closeCursor();
	}
	catch(PDOException $e){
		echo "Error al ejecutar la sentencia: \n";
			print_r($e->getMessage());
	}
	
	return $resultado;
}

function dameTotalEstadosDetalles($req){
	$resultado = false;
    $consulta = "SELECT ESDECODI CODIGO,
				        ESDENOMB ESTADO_DES,
				        ESDECOLO COLOR,
						COUNT(DEREAPRO) TOTAL 
				 FROM   DETALLE_REQU D,
				        ESTADO_DETALLE ED 
				 WHERE  DEREREQU = '".$req."'
				   AND  DEREAPRO=ESDECODI 
				   AND  DEREAPRO <> '0' 
			  GROUP BY  DEREAPRO
 
				";	
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	
	try {
		if(!$sentencia->execute()){
			print_r($sentencia->errorInfo());
		}
		$resultado = $sentencia->fetchAll();
		//$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$sentencia->closeCursor();
	}
	catch(PDOException $e){
		echo "Error al ejecutar la sentencia: \n";
			print_r($e->getMessage());
	}
	
	return $resultado;
}

function dameTotalEstadosPorAutorizar(){
	$resultado = false;
    $consulta = "SELECT COUNT(DERECONS) TOTAL 
    			 FROM 	ESTADO_DETALLE, 
    			 		DETALLE_REQU, 
    			 		REQUERIMIENTOS 
    			 WHERE  DEREAPRO=ESDECODI 
    			 AND    DEREAPRO=22 
    			 AND    REQUCODI=DEREREQU 
    			 AND    REQUESTA=5
 
				";	
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	
	try {
		if(!$sentencia->execute()){
			print_r($sentencia->errorInfo());
		}
		$resultado = $sentencia->fetchAll();
		//$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$sentencia->closeCursor();
	}
	catch(PDOException $e){
		echo "Error al ejecutar la sentencia: \n";
			print_r($e->getMessage());
	}
	
	return $resultado;
}

function dameAutorizacionOrden($cod_orden){
	$resultado = false;
    $consulta = "SELECT DERECONS, SUM((CODEVALO*DERECANT)+CODEVIVA)TOTAL 
				FROM orden_compra,
					 orden_compradet,
					 cotizacion,
					 cotizacion_detalle,
					 detalle_requ
				WHERE  ORCOCONS= '".$cod_orden."' 
				AND ORCOCONS=ORCDORCO
				AND COTICODI=CODECOTI
				AND COTIPROV=ORCDPROV
				AND ORCDDETA=CODEDETA
				AND DERECONS=ORCDDETA
				AND DERECOOC=ORCDORCO
 
				";	
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	
	try {
		if(!$sentencia->execute()){
			print_r($sentencia->errorInfo());
		}
		$resultado = $sentencia->fetchAll();
		//$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$sentencia->closeCursor();
	}
	catch(PDOException $e){
		echo "Error al ejecutar la sentencia: \n";
			print_r($e->getMessage());
	}
	
	return $resultado;
}

function dameProveedores($cod_orden){
	$resultado = false;
    $consulta = "SELECT DERECONS, SUM((CODEVALO*DERECANT)+CODEVIVA)TOTAL 
				FROM orden_compra,
					 orden_compradet,
					 cotizacion,
					 cotizacion_detalle,
					 detalle_requ
				WHERE  ORCOCONS= '".$cod_orden."' 
				AND ORCOCONS=ORCDORCO
				AND COTICODI=CODECOTI
				AND COTIPROV=ORCDPROV
				AND ORCDDETA=CODEDETA
				AND DERECONS=ORCDDETA
 
				";	
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	
	try {
		if(!$sentencia->execute()){
			print_r($sentencia->errorInfo());
		}
		$resultado = $sentencia->fetchAll();
		//$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$sentencia->closeCursor();
	}
	catch(PDOException $e){
		echo "Error al ejecutar la sentencia: \n";
			print_r($e->getMessage());
	}
	
	return $resultado;
}

function dameProveedorContratos(){
	$resultado = false;
    $consulta = "SELECT               P.PROVCODI CODIGO,
                                      P.PROVNOMB NOMBRE								  
							 FROM PROVEEDORES P  
							 WHERE  1
                             ORDER BY NOMBRE							 
				";	
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	
	try {
		if(!$sentencia->execute()){
			print_r($sentencia->errorInfo());
		}
		$resultado = $sentencia->fetchAll();
		
		$sentencia->closeCursor();
	}
	catch(PDOException $e){
		echo "Error al ejecutar la sentencia: \n";
			print_r($e->getMessage());
	}
	
	return $resultado;
}

function dameTotalProveedoresUpdate(){
	$resultado = false;
    $consulta = "SELECT COUNT(DERECONS) TOTAL 
    			 FROM 	ESTADO_DETALLE, 
    			 		DETALLE_REQU, 
    			 		REQUERIMIENTOS 
    			 WHERE  DEREAPRO=ESDECODI 
    			 AND    DEREAPRO=22 
    			 AND    REQUCODI=DEREREQU 
    			 AND    REQUESTA=5
 
				";	
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	
	try {
		if(!$sentencia->execute()){
			print_r($sentencia->errorInfo());
		}
		$resultado = $sentencia->fetchAll();
		//$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$sentencia->closeCursor();
	}
	catch(PDOException $e){
		echo "Error al ejecutar la sentencia: \n";
			print_r($e->getMessage());
	}
	
	return $resultado;
}

?>
