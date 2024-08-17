<?php
require_once('conexion/db.php');
	if (!isset($_SESSION)) {
  session_start();
}

$tipoGuardar='';
if(isset($_GET['tipoGuardar']) && $_GET['tipoGuardar']!=''){
$tipoGuardar=$_GET['tipoGuardar'];
}

$estado_requerimiento='';
if(isset($_GET['estado_requerimiento']) && $_GET['estado_requerimiento']!=''){
$estado_requerimiento=$_GET['estado_requerimiento'];
}

if($tipoGuardar == 'usuarioOnOff'){
	$user='';
	if(isset($_GET['user']) && $_GET['user'] !=''){
		$user = $_GET['user']; /* nombre de usuario */
	}
	$status = '0';
	if(isset($_GET['status']) && $_GET['status'] !=''){
		$status = $_GET['status']; /*valores true or false */
	}	
	if($status == 'true'){
		$status = 1;
	}else{ $status = 0;}

	$query_RsUpdateProducto = "UPDATE USUARIOS SET USUAESTA = '".$status."'
		where USUALOG = '".$user."'
						";
						
	$RsUpdateProducto = mysqli_query($conexion,$query_RsUpdateProducto) or die(mysqli_connect_error($conexion));
	$afectado = mysqli_affected_rows($conexion);
	echo($afectado);	

}

if($tipoGuardar == 'eliminarcodigoevento'){
	$codigo_requerimiento = '';
	$codigo_evento = '';
	if(isset($_GET['codigo_requerimiento']) && $_GET['codigo_requerimiento'] !=''){
		$codigo_requerimiento = $_GET['codigo_requerimiento'];
	}	
	if(isset($_GET['codevento']) && $_GET['codevento'] !=''){
		$codevento = $_GET['codevento'];
	}	
		$query_RsUpdateProducto = "UPDATE requerimientos SET REQUEVEN = NULL
									where REQUCODI = '".$codigo_requerimiento."'
														";
		$RsUpdateProducto = mysqli_query($conexion,$query_RsUpdateProducto) or die(mysqli_connect_error($conexion));
		$afectado = mysqli_affected_rows($conexion);
		echo($afectado);	
	
}
if($tipoGuardar == 'guardarcodigoevento'){
	$codigo_requerimiento = '';
	$codigo_evento = '';
	if(isset($_GET['codigo_requerimiento']) && $_GET['codigo_requerimiento'] !=''){
		$codigo_requerimiento = $_GET['codigo_requerimiento'];
	}	
	if(isset($_GET['codevento']) && $_GET['codevento'] !=''){
		$codevento = $_GET['codevento'];
	}	
		$query_RsUpdateProducto = "UPDATE requerimientos SET REQUEVEN = '".$codevento."'
									where REQUCODI = '".$codigo_requerimiento."'
														";
		$RsUpdateProducto = mysqli_query($conexion,$query_RsUpdateProducto) or die(mysqli_connect_error($conexion));
		$afectado = mysqli_affected_rows($conexion);
		echo($afectado);	
	
}
function ComprobarUnicoProveedor($registro, $conexion ){
	$response = array(
					'status'       => 'ok',
					'data_temp'    => '',
					'prov'         => ''
				);	
	$query_RsDatoRegistroUnico = "SELECT * FROM PROVEEDORES WHERE PROVREGI = '".$registro."'";
	$RsDatoRegistroUnico = mysqli_query($conexion,$query_RsDatoRegistroUnico) or die(mysqli_error($conexion));
	$row_RsDatoRegistroUnico = mysqli_fetch_array($RsDatoRegistroUnico);
	$totalRows_RsDatoRegistroUnico = mysqli_num_rows($RsDatoRegistroUnico);
	if($totalRows_RsDatoRegistroUnico > 0){
		$response = array(
						'status'       => 'erroruser',
						'data_temp'    => '',
						'prov'         => $row_RsDatoRegistroUnico['PROVNOMB']
					);
	}
	return $response;
}

function VerificarExisteDirectorio($carpeta_prov){
	$existe = 'failed'; /* inicia no existe directorio */
		if (file_exists($carpeta_prov)) {
			echo "El fichero existe";
			$existe = 'ok'; /* directorio existe*/
		} else {
			if(!mkdir($carpeta_prov, 0777, true)) {
				die('Fallo al crear las carpetas...');
				$existe = 'failed';
			}else{
				$existe = 'ok';
			}
		}
	return $existe;	
}

function procesarArchivosCategorias($codigo_prov_temporal,$codigo_proveedor_oficial, $dir_origen, $dir_destino, $conexion){
	
	$query_RsArchivosTemp = "SELECT * FROM  PROVEEDOR_DATATEMP WHERE PDTEPROV = '".$codigo_prov_temporal."'";
	$RsArchivosTemp = mysqli_query($conexion,$query_RsArchivosTemp) or die(mysqli_error($conexion));
	$row_RsArchivosTemp = mysqli_fetch_array($RsArchivosTemp);
	$totalRows_RsArchivosTemp = mysqli_num_rows($RsArchivosTemp);	
	$status = 'failed';
	
	
	if($totalRows_RsArchivosTemp > 0){
		do{
			//echo($row_RsArchivosTemp['PDTECATE']."<br></br>");
			$categorias_prov = json_decode($row_RsArchivosTemp['PDTECATE']);
			$archivos_prov    = json_decode($row_RsArchivosTemp['PDTEARCH']);
			foreach($categorias_prov as $categoria){
				$query_RsInsertarCategoriasClasificacion="INSERT INTO PROVEEDOR_CLASIFICACION 
											 (
												PRCLCODI,
												PRCLPROV,
												PRCLCLAS,
												PRCLCALI
											 )
											 VALUES
											 (
												NULL,
												'".$codigo_proveedor_oficial."',
												'".$categoria."',
												'0'
											 )
											 
											 ";
				$RsInsertarCategoriasClasificacion = mysqli_query($conexion,$query_RsInsertarCategoriasClasificacion) or die(mysqli_error($conexion));
			}		
			foreach($archivos_prov as $proveedor){
				//echo($proveedor->archivo.'<br>');
				if($proveedor->archivo){
						$query_RsInsertarArchivo="INSERT INTO PROVEEDORESARCH 
													 (
														PRARCODI,
														PRARPROV,
														PRARARCH,
														PRARTICA
													 )
													 VALUES
													 (
														NULL,
														'".$codigo_proveedor_oficial."',
														'".$proveedor->archivo."',
														'1'
													 )
													 
													 ";
						$RsInsertarArchivo = mysqli_query($conexion,$query_RsInsertarArchivo) or die(mysqli_error($conexion));
					try {
						$pasa = copy("$dir_origen/$proveedor->archivo", "$dir_destino/$proveedor->archivo");
					} catch (Exception $e) {
						
					}
				}
			}
		}while($row_RsArchivosTemp = mysqli_fetch_array($RsArchivosTemp));
		$status = 'ok';
	}
	return $status;	
}

if($tipoGuardar == 'CopiarArchivosProveedor'){
	$codigo_proveedor_oficial = '';
	if(isset( $_GET['cod_prov_oficial']) &&  $_GET['cod_prov_oficial'] != ''){
		$codigo_proveedor_oficial = $_GET['cod_prov_oficial'];
	}	
	$codigo_prov_temporal = '';
	if(isset( $_GET['cod_prov_temp']) &&  $_GET['cod_prov_temp'] != ''){
		$codigo_prov_temporal = $_GET['cod_prov_temp'];
	}
	$response = array(
			'status'       => 'failed',
			'data_temp'    => '',
			'prov'         => ''
		);
		
	$dir_destino = 'archivos_compras/PROVEEDORES/'.$codigo_proveedor_oficial;
	$dir_origen  = 'proveedores/publico/php/temporalfiles/';
	$carpeta_prov = "archivos_compras/PROVEEDORES/".$codigo_proveedor_oficial;		
	
	$proveedores_data_relacionada = procesarArchivosCategorias($codigo_prov_temporal,$codigo_proveedor_oficial, $dir_origen, $dir_destino, $conexion);
	if($proveedores_data_relacionada == 'ok'){
		/*Archivos y categorias insertadas correctamente*/	
		$response = array(
				'status'       => 'ok',
				'data_temp'    => '',
				'prov'         => ''
			);		
	}
	echo(json_encode($response));
	
}

if($tipoGuardar == 'CrearDirectorioProveedor'){
	$codigo_proveedor_oficial = '';
	if(isset( $_GET['cod_prov_oficial']) &&  $_GET['cod_prov_oficial'] != ''){
		$codigo_proveedor_oficial = $_GET['cod_prov_oficial'];
	}	
	$codigo_prov_temporal = '';
	if(isset( $_GET['cod_prov_temp']) &&  $_GET['cod_prov_temp'] != ''){
		$codigo_prov_temporal = $_GET['cod_prov_temp'];
	}
			$response = array(
						'status'       => 'failed',
						'data_temp'    => '',
						'prov'         => ''
					);	

		$dir_destino = 'archivos_compras/PROVEEDORES/'.$codigo_proveedor_oficial;
		$dir_origen  = 'proveedores/publico/php/temporalfiles/';
		$carpeta_prov = "archivos_compras/PROVEEDORES/".$codigo_proveedor_oficial;
		$verificar_directorio = VerificarExisteDirectorio($dir_destino);
		if($verificar_directorio == 'ok'){
			$response = array(
						'status'       => 'ok',
						'data_temp'    => '',
						'prov'         => '',
						'cod_prov_oficial' => $codigo_proveedor_oficial,
						'cod_prov_temporal' => $codigo_prov_temporal						
					);			
		}
		echo(json_encode($response));
		
}

if($tipoGuardar == 'ProveedorTemporalaOficial'){
	$codigo_prov = '';
	if(isset($_GET['codigo_prov']) && $_GET['codigo_prov'] !=''){
		$codigo_prov = $_GET['codigo_prov'];
	}	
	$accion = '';
	if(isset($_GET['accion']) && $_GET['accion'] !=''){
		$accion = $_GET['accion'];
	}
	/*====================
	consulta a tabla temporal para verificar si existe el registro con el codigo enviado,
	 recordar las acciones 1, aprobar se debe pasar la data de proveedortemporal a proveedores oficial
	 2 rechazar solo marcar el registro en temporal como rechazado.
	=====================*/
	//validaciones se deben hace antes
	
	if($accion == '2'){
			$query_RsUpdateProducto = "UPDATE PROVEEDORES_TEMPORAL SET PROVESSO = 2
										where PROVCODI = '".$codigo_prov."'";
			$RsUpdateProducto = mysqli_query($conexion,$query_RsUpdateProducto) or die(mysqli_connect_error($conexion));
			$afectado = mysqli_affected_rows($conexion);
			if($afectado > 0){
				$response = array(
					'status'       => 'rechazado',
					'data_temp'    => '',
					'cod_prov_temporal' => $codigo_prov
				);	
			}else{
				$response = array(
					'status'       => 'norechazado',
					'data_temp'    => '',
					'cod_prov_temporal' => $codigo_prov
				);
			}
			echo(json_encode($response));		
	}else{
	
		$query_RsDatoUnico2 = "SELECT * FROM PROVEEDORES_TEMPORAL WHERE PROVCODI = '".$codigo_prov."'";
		$RsDatoUnico2 = mysqli_query($conexion,$query_RsDatoUnico2) or die(mysqli_error($conexion));
		$row_RsDatoUnico2 = mysqli_fetch_array($RsDatoUnico2);
		$totalRows_RsDatoUnico2 = mysqli_num_rows($RsDatoUnico2);
		$response = array(
						'status'       => 'ok',
						'data_temp'    => '',
						'prov'         => ''
					);	

		if($totalRows_RsDatoUnico2 > 0){  /*Verificar que el proveedor exista en la tabla y que el estado sea = 0*/
			if($row_RsDatoUnico2['PROVESSO'] == 0){ /**verficiar que sea estado no revizado */
				$response = ComprobarUnicoProveedor($row_RsDatoUnico2['PROVREGI'], $conexion );
			}
		}
	if($response['status'] == 'erroruser'){
		echo(json_encode($response));
		exit;
	}

	if($response['status'] == 'ok'){
		$query_RsListaAnexosDet = "
									INSERT INTO PROVEEDORES (
													  `PROVREGI`,
													  `PROVDIVE`,
													  `PROVNOMB`,
													  `PROVNOCO`,
													  `PROVTELE`,
													  `PROVPWEB`,
													  `PROVDIRE`,
													  `PROVCON1`,
													  `PROVTEC1`,
													  `PROVCCO1`,
													  `PROVCON2`,
													  `PROVTEC2`,
													  `PROVCCO2`,
													  `PROVCOME`,
													  `PROVPERE`,
													  `PROVFERE`,
													  `PROVESTA`,
													  `PROVCORR`,
													  `PROVIDCA`,
													  `PROVCALI`,
													  `PROVFAVO`,
													  `PROVCONV`,
													  `PROVIDCI`,
													  `PROVUSUA`,
													  `PROVPASS`,
													  `PROVTIID`,
													  `PROVTIPE`,
													  `PROVREGM`,
													  `PROVAURE`,
													  `PROVGRCO`,
													  `PROVCICA`,
													  `PROVAUTO`
									)
									SELECT

													  `PROVREGI`,
													  `PROVDIVE`,
													  `PROVNOMB`,
													  `PROVNOCO`,
													  `PROVTELE`,
													  `PROVPWEB`,
													  `PROVDIRE`,
													  `PROVCON1`,
													  `PROVTEC1`,
													  `PROVCCO1`,
													  `PROVCON2`,
													  `PROVTEC2`,
													  `PROVCCO2`,
													  `PROVCOME`,
													  `PROVPERE`,
													  `PROVFERE`,
													  `PROVESTA`,
													  `PROVCORR`,
													  `PROVIDCA`,
													  `PROVCALI`,
													  `PROVFAVO`,
													  `PROVCONV`,
													  `PROVIDCI`,
													  `PROVUSUA`,
													  `PROVPASS`,
													  `PROVTIID`,
													  `PROVTIPE`,
													  `PROVREGM`,
													  `PROVAURE`,
													  `PROVGRCO`,
													  `PROVCICA`,
													  `PROVAUTO`
													  
												   from PROVEEDORES_TEMPORAL
												   
												   WHERE PROVCODI = '".$codigo_prov."'";
		$RsListaAnexosDet = mysqli_query($conexion,$query_RsListaAnexosDet) or die(mysqli_error($conexion));
		$query_RsUltInsert = "SELECT LAST_INSERT_ID() DATO";
		$RsUltInsert = mysqli_query($conexion,$query_RsUltInsert) or die(mysqli_error($conexion));
		$row_RsUltInsert = mysqli_fetch_array($RsUltInsert);
		$ultimo_proveedor_creado = $row_RsUltInsert['DATO'];
		if($ultimo_proveedor_creado > 0){ /*actualizar el valor de la tabla temporal a 1 aprobado y pasar los archivos temporales proveedores y la categoria lista del proveedor*/
			$query_RsUpdateProducto = "UPDATE PROVEEDORES_TEMPORAL SET PROVESSO = 1,
																	   PROVPROF = '".$ultimo_proveedor_creado."'
										where PROVCODI = '".$codigo_prov."'";
			$RsUpdateProducto = mysqli_query($conexion,$query_RsUpdateProducto) or die(mysqli_connect_error($conexion));

			$query_RsDatoUnico     = "SELECT * FROM PROVEEDORES WHERE PROVCODI = '".$ultimo_proveedor_creado."'";
			$RsDatoUnico           = mysqli_query($conexion,$query_RsDatoUnico) or die(mysqli_error($conexion));
			$row_RsDatoUnico       = mysqli_fetch_array($RsDatoUnico);
			$totalRows_RsDatoUnico = mysqli_num_rows($RsDatoUnico);
			if($totalRows_RsDatoUnico > 0){
				if($row_RsDatoUnico['PROVUSUA'] != ''){
					$query_RsInsertarUsuario = "INSERT INTO USUARIOS (
																USUACODI,
																USUALOG,
																USUAPASS,
																USUAROL,
																USUAPASSBK,
																USUAPASPOBK,
																USUAESTA
																) 
																VALUES 
																(
																NULL,
																'".$row_RsDatoUnico['PROVUSUA']."',
																AES_ENCRYPT( '".$row_RsDatoUnico['PROVPASS']."', 'mc$90ui1' ),
																'7',
																'".$row_RsDatoUnico['PROVPASS']."',
																'".$row_RsDatoUnico['PROVPASS']."',
																'0'
																)";

															// exit($query_RsInsertarInsertarUsuario);
					$RsInsertarUsuario = mysqli_query($conexion,$query_RsInsertarUsuario) or die(mysqli_error($conexion));

					$query_RsUltInsert = "SELECT LAST_INSERT_ID() DATO";
					$RsUltInsert = mysqli_query($conexion,$query_RsUltInsert) or die(mysqli_error($conexion));
					$row_RsUltInsert = mysqli_fetch_array($RsUltInsert);

					$ultimo_usuario_creado = $row_RsUltInsert['DATO'];
					if($ultimo_usuario_creado > 0){
						$query_RsDatosUsuario     = "SELECT * FROM USUARIOS WHERE USUACODI = '".$ultimo_usuario_creado."'";
						$RsDatosUsuario           = mysqli_query($conexion,$query_RsDatosUsuario) or die(mysqli_error($conexion));
						$row_RsDatosUsuario       = mysqli_fetch_array($RsDatosUsuario);
						$totalRows_RsDatosUsuario = mysqli_num_rows($RsDatosUsuario);
						if($totalRows_RsDatosUsuario > 0){
							/*$query_RsInsertarPersona = "INSERT INTO PERSONAS (
																		PERSID,
																		PERSNOMB,
																		PERSAPEL,
																		PERSTELE,
																		PERSUSUA,
																		PERSCORR
																		) 
																		VALUES 
																		(
																		'".$row_RsDatoUnico['PROVREGI']."',
																		'".$row_RsDatoUnico['PROVNOMB']."',
																		'',
																		'".$row_RsDatoUnico['PROVTELE']."',
																		'".$row_RsDatoUnico['PROVUSUA']."',
																		'".$row_RsDatoUnico['PROVCORR']."'
																		)";

							$RsInsertarPersona = mysqli_query($conexion,$query_RsInsertarPersona) or die(mysqli_error($conexion));*/
						}
					}
				}
			}
			
			$afectado = mysqli_affected_rows($conexion);
			if($afectado > 0){
				$response = array(
					'status'       => 'creado',
					'data_temp'    => '',
					'cod_prov_oficial' => $ultimo_proveedor_creado,
					'cod_prov_temporal' => $codigo_prov
				);	
			}else{
				$response = array(
					'status'       => 'errprovnocreado',
					'data_temp'    => '',
					'cod_prov_oficial' => $ultimo_proveedor_creado,
					'cod_prov_temporal' => $codigo_prov
				);
			}
			echo(json_encode($response));
		}
	}
	}
	
}	
if($tipoGuardar == 'buscardetallesmultiple'){
	$codigo_detalle = '';
	$codigo_requerimiento = '';
	if(isset($_GET['codigo_detalle']) && $_GET['codigo_detalle'] !=''){
		$codigo_detalle = $_GET['codigo_detalle'];
	}	
	if(isset($_GET['codigo_requerimiento']) && $_GET['codigo_requerimiento'] !=''){
		$codigo_requerimiento = $_GET['codigo_requerimiento'];
	}
	$response = array();
	if($codigo_detalle != '' && $codigo_requerimiento == ''){
		/*Busqueda por solo codigo de detalle*/
		$query_RsListaAnexosDet="SELECT `DEANCODI` CODIGO,
										`DEANDETA` DETALLE,
										`DEANARCH` ARCHIVO_RUTA,
										DATE_FORMAT(`DEANFECH`, '%d/%m/%Y') FECHA,
										DEREDESC DESCR,
										DEANDESC DESCR_ARCHIVO
									FROM `detalle_anexos`,
									detalle_requ 
								WHERE `DERECONS`=`DEANDETA`
								AND	   DERECONS=".$codigo_detalle."";
					 // echo($query_RsListaAnexosDet);
		$RsListaAnexosDet = mysqli_query($conexion,$query_RsListaAnexosDet) or die(mysqli_error($conexion));
		$row_RsListaAnexosDet = mysqli_fetch_array($RsListaAnexosDet);
		$totalRows_RsListaAnexosDet = mysqli_num_rows($RsListaAnexosDet);		
		if($totalRows_RsListaAnexosDet>0){
			do{
				$response[] = $row_RsListaAnexosDet;
			}while($row_RsListaAnexosDet = mysqli_fetch_array($RsListaAnexosDet));
		}
		
	}
	if($codigo_requerimiento != ''){
		/*busqueda por todos los detalles que tienen un requerimiento*/
	}
	echo(json_encode($response));
}	
if($tipoGuardar == 'deleteanexomultiple'){
	 $query_RsEliminar="DELETE FROM detalle_anexos WHERE DEANCODI = '".$_GET['codigo_anexo']."'";							
     $RsEliminar = mysqli_query($conexion,$query_RsEliminar) or die(mysqli_error($conexion));
 
 echo('1');
}
if($tipoGuardar == 'addarchivodetalleseleccionados'){
	$codigo_archivo = '';
	$detallesadd = '';
	$response = array();
	if(isset($_GET['codigo_archivo']) && $_GET['codigo_archivo'] != ''){
		$codigo_archivo = $_GET['codigo_archivo'];
	}	
	if(isset($_GET['detallesadd']) && $_GET['detallesadd'] != ''){
		$detallesadd = $_GET['detallesadd'];
	}
	if($detallesadd != '' && $codigo_archivo != ''){
		$query_RsListaAnexosDet="SELECT `DEANCODI` CODIGO,
										`DEANDETA` DETALLE,
										`DEANARCH` ARCHIVO_RUTA,
										DATE_FORMAT(`DEANFECH`, '%d/%m/%Y') FECHA,
										DEANDESC DESCR_ARCHIVO
									FROM `detalle_anexos`
								WHERE DEANCODI ='".$codigo_archivo."'";
					 // echo($query_RsListaAnexosDet);
		$RsListaAnexosDet = mysqli_query($conexion,$query_RsListaAnexosDet) or die(mysqli_error($conexion));
		$row_RsListaAnexosDet = mysqli_fetch_array($RsListaAnexosDet);
		$totalRows_RsListaAnexosDet = mysqli_num_rows($RsListaAnexosDet);		

		$archivo = '';
		$descripcion_archivo = '';
		if($totalRows_RsListaAnexosDet>0){
			$archivo = $row_RsListaAnexosDet['ARCHIVO_RUTA'];
			$descripcion_archivo = $row_RsListaAnexosDet['DESCR_ARCHIVO'];
		}
		if($archivo != ''){
			$alldetalles = explode(",",$detallesadd);
			for($i=0; $i<count($alldetalles); $i++){
				$query_RsInsertarAnexosDet="INSERT INTO `detalle_anexos` (
																	`DEANCODI`,	
																	`DEANDETA`,
																	`DEANARCH`,
																	`DEANFECH`,
																	`DEANDESC`
																	) 
																	VALUES 
																	(NULL,
																	'".$alldetalles[$i]."',
																	'".$archivo."',
																	sysdate(),
																	'".$descripcion_archivo."'
																	)";

																// exit($query_RsInsertarInsertarAnexosDet);
				$RsInsertarAnexosDet = mysqli_query($conexion,$query_RsInsertarAnexosDet) or die(mysqli_error($conexion));
				$query_RsUltInsert = "SELECT LAST_INSERT_ID() DATO";
				$RsUltInsert = mysqli_query($conexion,$query_RsUltInsert) or die(mysqli_error($conexion));
				$row_RsUltInsert = mysqli_fetch_array($RsUltInsert);
				$ultimo_archivo = $row_RsUltInsert['DATO'];					
				$response[] = array(
									"codigo_detalle" => $alldetalles[$i],
									"codigo_archivo" => $ultimo_archivo
									);
				
				
			}
		
		}
	}
	echo(json_encode($response));
}
if($tipoGuardar == 'ActualizarNameProducto'){
	
		if(isset($_POST['json']) && $_POST['json']!=''){
		$response = array();
		$afectado = 0;
		$changebutton = 0;
		$tojson = json_decode($_POST['json']);
		//var_dump($tojson);
		}
		$query_RsUpdateProducto = "UPDATE PRODUCTOS SET PRODDESC = '".$tojson->nombre."',
		                                                PRODPREC = '".$tojson->price_un."',
		                                                PRODSTOK = '".$tojson->stock."',
		                                                PRODCANT = '".$tojson->cant."',
		                                                PRODIDUM = '".$tojson->um."'
									where PRODCONS = '".$_GET['producto']."'
														";
		$RsUpdateProducto = mysqli_query($conexion,$query_RsUpdateProducto) or die(mysqli_connect_error($conexion));
		$afectado = mysqli_affected_rows($conexion);
		echo($afectado);
		
}
if($tipoGuardar == 'CargarCotizaciones'){
	$retorno = array();
	$query_RsVerificarProveedorIgual = "SELECT C.COTICODI CODIGO_COTIZACION,
											   DATE_FORMAT(C.COTIFECH, '%d/%m/%Y') FECHA_COTIZACION
										from cotizacion C
									    where COTIPROV = '".$_GET['proveedor']."'
										order by C.COTICODI DESC LIMIT 50
	";
	$RsVerificarProveedorIgual = mysqli_query($conexion,$query_RsVerificarProveedorIgual) or die(mysqli_connect_error());
	$row_RsVerificarProveedorIgual = mysqli_fetch_assoc($RsVerificarProveedorIgual);
	$totalRows_RsVerificarProveedorIgual = mysqli_num_rows($RsVerificarProveedorIgual);	
	if($totalRows_RsVerificarProveedorIgual>0){
		do{
			$retorno[] = $row_RsVerificarProveedorIgual;
		}while($row_RsVerificarProveedorIgual = mysqli_fetch_assoc($RsVerificarProveedorIgual));
	}
	echo(json_encode($retorno));
}
if($tipoGuardar == 'HabilitarReqEspecial'){ 
    $query_RsListadoRequerimiento = "SELECT P.PERSPERE PERMISO FROM personas P where P.PERSID = '".$_GET['persona']."'"; 
	$RsListadoRequerimiento = mysqli_query($conexion, $query_RsListadoRequerimiento) or die(mysqli_error($conexion));
	$row_RsListadoRequerimiento = mysqli_fetch_assoc($RsListadoRequerimiento);
    $totalRows_RsListadoRequerimiento = mysqli_num_rows($RsListadoRequerimiento);
	if($totalRows_RsListadoRequerimiento>0){
		if($row_RsListadoRequerimiento['PERMISO']==1){
			$query_RsTodoRecibido = "UPDATE personas  set PERSPERE = '2' WHERE PERSID = '".$_GET['persona']."'";
			$RsTodoRecibido = mysqli_query($conexion,$query_RsTodoRecibido) or die(mysqli_connect_error($conexion));
			echo('2');
		}else{
			$query_RsTodoRecibido = "UPDATE personas  set PERSPERE = '1' WHERE PERSID = '".$_GET['persona']."'";
			$RsTodoRecibido = mysqli_query($conexion,$query_RsTodoRecibido) or die(mysqli_connect_error($conexion));
			echo('1');
		}
	}

	
}	
if($tipoGuardar == 'TodosRecibidoProv'){
			$query_RsTodoRecibido = "UPDATE orden_compra set ORCOTOEN = '1' WHERE ORCOCONS = '".$_GET['orden']."'";
			$RsTodoRecibido = mysqli_query($conexion,$query_RsTodoRecibido) or die(mysqli_connect_error($conexion));
			$afectado = mysqli_affected_rows($conexion);
			echo($afectado);
}
if($tipoGuardar == 'RecibirProductosConvenio'){
	if(isset($_POST['json']) && $_POST['json']!=''){
		$response = array();
		$afectado = 0;
		$changebutton = 0;
		$tojson = json_decode($_POST['json']);
		//print_r($tojson);
		for($i=0; $i<count($tojson); $i++){
			//echo($tojson[$i]->det.'_');
			$query_RsMarcarRecibido = "UPDATE detalle_requ set DEREFERE = SYSDATE(),
															   DEREAPRO = 20 
														 WHERE DERECONS = '".$tojson[$i]->det."'";
			$RsMarcarRecibido = mysqli_query($conexion,$query_RsMarcarRecibido) or die(mysqli_connect_error());
			
			$query_RsMarcarRecibido = "UPDATE orden_compconv_detalle set ORCDFERE = SYSDATE() WHERE ORCDCODI = '".$tojson[$i]->detorden."'";
			$RsMarcarRecibido = mysqli_query($conexion,$query_RsMarcarRecibido) or die(mysqli_connect_error());
		$afectado = $afectado+1;
		}
		if((count($tojson) == $_GET['sinrecibir'])  || $_GET['sinrecibir'] == 0){
			$query_RsTodoRecibido = "UPDATE orden_compra_convenio set ORCOTOEN = '1' WHERE ORCOCONS = '".$_GET['orden']."'";
			$RsTodoRecibido = mysqli_query($conexion,$query_RsTodoRecibido) or die(mysqli_connect_error());
			$changebutton = 1;
		}
		$response[] = array(
		                    'afectado'  => $afectado,
							'change'    => $changebutton,
							);
		echo(json_encode($response));
	}
}
if($tipoGuardar == 'RecibirProductos'){
	if(isset($_POST['json']) && $_POST['json']!=''){
		$response = array();
		$afectado = 0;
		$changebutton = 0;
		$tojson = json_decode($_POST['json']);
		//print_r($tojson);
		for($i=0; $i<count($tojson); $i++){
			//echo($tojson[$i]->det.'_');
			$query_RsMarcarRecibido = "UPDATE detalle_requ set DEREFERE = SYSDATE(),
															   DEREAPRO = 20
														 WHERE DERECONS = '".$tojson[$i]->det."'";
			$RsMarcarRecibido = mysqli_query($conexion,$query_RsMarcarRecibido) or die(mysqli_connect_error());
			
			$query_RsMarcarRecibido = "UPDATE orden_compradet set ORCDFERE = SYSDATE() WHERE ORCDCODI = '".$tojson[$i]->detorden."'";
			$RsMarcarRecibido = mysqli_query($conexion,$query_RsMarcarRecibido) or die(mysqli_connect_error());
		$afectado = $afectado+1;
		}
		if((count($tojson) == $_GET['sinrecibir'])  || $_GET['sinrecibir'] == 0){
			$query_RsTodoRecibido = "UPDATE orden_compra set ORCOTOEN = '1' WHERE ORCOCONS = '".$_GET['orden']."'";
			$RsTodoRecibido = mysqli_query($conexion,$query_RsTodoRecibido) or die(mysqli_connect_error());
			$changebutton = 1;
		}
		$response[] = array(
		                    'afectado'  => $afectado,
							'change'    => $changebutton,
							);
		echo(json_encode($response));
	}
}	
if($tipoGuardar == 'cargar_orden'){
	$query_RsVerificarProveedorIgual = "SELECT O.ORCDCODI CODIGO,
	                                           O.ORCDORCO ORDEN,
											   O.ORCDDETA DETALLE,
											   O.ORCDPROV PROVEEDOR,
											   D.DEREDESC DETALLE_DES,
											   D.DERECANT CANTIDAD,
											   CD.CODEVALO VALOR_UNITARIO,
											   U.UNMESIGL MEDIDA,
											   (((CD.CODEVALO*CD.CODEVAIV)/100)) IVA,
											   CD.CODEVAIV PORC_IVA,
											   ((((CD.CODEVALO*CD.CODEVAIV)/100)+CD.CODEVALO)*D.DERECANT) VALOR_TOTAL,
											   '".$_GET['orden']."' ORDEN,
											   ifnull(O.ORCDFERE,'-1') FECHA_REC_PROV,
											   R.REQUCORE COD_REQU,
											   R.REQUCODI CONS_REQU
											   
	                                     FROM orden_compradet O,
										      orden_compra    OC,
										      detalle_requ    D,
											  unidad_medida   U,
											  requerimientos  R,
											  proveedores     P,
											  cotizacion      C,
											  cotizacion_detalle CD
									    WHERE O.ORCDORCO = OC.ORCOCONS
										  AND O.ORCDDETA = D.DERECONS
										  and D.DEREUNME = U.UNMECONS
										  and D.DEREREQU = R.REQUCODI
										  and O.ORCDORCO = '".$_GET['orden']."'
										  and OC.ORCOIDPR  = P.PROVCODI
										  AND D.DEREPROV  = C.COTIPROV
										  AND D.DERECONS  = CD.CODEDETA
										  and C.COTICODI  = CD.CODECOTI
										";
	$RsVerificarProveedorIgual = mysqli_query($conexion,$query_RsVerificarProveedorIgual) or die(mysqli_connect_error());
	$row_RsVerificarProveedorIgual = mysqli_fetch_assoc($RsVerificarProveedorIgual);
	$totalRows_RsVerificarProveedorIgual = mysqli_num_rows($RsVerificarProveedorIgual);
	$response = array();
	if($totalRows_RsVerificarProveedorIgual>0){
		do{
			$response[] =  $row_RsVerificarProveedorIgual;
		}while($row_RsVerificarProveedorIgual = mysqli_fetch_assoc($RsVerificarProveedorIgual));
	}
	echo(json_encode($response));
}

if($tipoGuardar == 'cargar_orden_convenio'){
	$query_RsVerificarProveedorIgual = "select OC.ORCOCONS CODIGO,
											   OD.ORCDORCC ORDEN, 
											   OD.ORCDDETA DETALLE,
												P.PROVCODI PROVEEDOR,
												D.DEREDESC DETALLE_DES,
												D.DERECANT CANTIDAD,
												CP.COPRPREC VALOR_UNITARIO,
												U.UNMESIGL MEDIDA,
												'' IVA,
												'' PORC_IVA,
												(D.DERECANT*CP.COPRPREC) VALOR_TOTAL,
												'".$_GET['orden']."' ORDEN,
												OD.ORCDCODI COD_DETORD,
												   ifnull(OD.ORCDFERE,'-1') FECHA_REC_PROV,
											   R.REQUCORE COD_REQU,
												   R.REQUCODI CONS_REQU
										  from orden_compconv_detalle OD, 
												orden_compra_convenio OC,
												conve_produc          CP,
												convenios             C,
												proveedores            P,
												detalle_requ           D,
												unidad_medida          U,
												requerimientos         R
										 where OC.ORCOCONS = '".$_GET['orden']."'
										   AND OD.ORCDORCC = OC.ORCOCONS
										   AND OD.ORCdCONV = CP.COPRID
										   AND CP.COPRIDCO = C.CONVCONS
										   AND  C.CONVIDPR = P.PROVCODI
										   AND OD.ORCDDETA = D.DERECONS
										   AND  D.DEREUNME = U.UNMECONS
										   and  D.DEREREQU = R.REQUCODI   
										   
										 order by D.DERECONS 
										";
	$RsVerificarProveedorIgual = mysqli_query($conexion,$query_RsVerificarProveedorIgual) or die(mysqli_connect_error());
	$row_RsVerificarProveedorIgual = mysqli_fetch_assoc($RsVerificarProveedorIgual);
	$totalRows_RsVerificarProveedorIgual = mysqli_num_rows($RsVerificarProveedorIgual);
	$response = array();
	if($totalRows_RsVerificarProveedorIgual>0){
		do{
			$response[] =  array('ORDEN'           => $row_RsVerificarProveedorIgual['ORDEN'],
			                     'DETALLE'         => $row_RsVerificarProveedorIgual['DETALLE'],
			                     'PROVEEDOR'       => $row_RsVerificarProveedorIgual['PROVEEDOR'],
			                     'DETALLE_DES'     => $row_RsVerificarProveedorIgual['DETALLE_DES'],
			                     'CANTIDAD'        => $row_RsVerificarProveedorIgual['CANTIDAD'],
							     'VALOR_UNITARIO'  => "$".number_format($row_RsVerificarProveedorIgual['VALOR_UNITARIO'],1,'.',','),
								 'MEDIDA'          => $row_RsVerificarProveedorIgual['MEDIDA'],
								 'IVA'             => $row_RsVerificarProveedorIgual['IVA'],
								 'PORC_IVA'        => $row_RsVerificarProveedorIgual['PORC_IVA'],
								 'VALOR_TOTAL'     => "$".number_format($row_RsVerificarProveedorIgual['VALOR_TOTAL'],1,'.',','),
								 'ORDEN'           => $row_RsVerificarProveedorIgual['ORDEN'],
								 'COD_DETORD'      => $row_RsVerificarProveedorIgual['COD_DETORD'],
								 'FECHA_REC_PROV'  => $row_RsVerificarProveedorIgual['FECHA_REC_PROV'],
								 'COD_REQU'        => $row_RsVerificarProveedorIgual['COD_REQU'],
								 'CONS_REQU'       => $row_RsVerificarProveedorIgual['CONS_REQU'],
						
						   );
						   
			
			
		}while($row_RsVerificarProveedorIgual = mysqli_fetch_assoc($RsVerificarProveedorIgual));
	}
	echo(json_encode($response));
	
}

	
if($tipoGuardar == 'CargarProductosConvCotizacion'){
	$query_RsVerificarProveedorIgual = "SELECT C.COTICODI CODIGO_COTIZACION,
											   C.COTIORDE CODIGO_ORDEN,
											   C.COTIPROV PROVEEDOR,
											  CD.CODEDETA DETALLE,
											  CD.CODEVALO VALOR,
											  CD.CODEVAIV IVA,
											  CD.CODEVIVA VALOR_IVA,
											   D.DEREDESC DETALLE_DES,
											   D.DERECANT CANTIDAD,
											   D.DEREUNME MEDIDA,
											   U.UNMENOMB MEDIDA_DES,
											   U.UNMESIGL SIGLA      
										from cotizacion          C,
											 cotizacion_detalle CD,
											 detalle_requ        D,
											 unidad_medida       U
										WHERE C.COTIPROV  = '".$_GET['proveedor']."'
										  AND C.COTICODI  = '".$_GET['cotizacion']."'
										  AND C.COTICODI  = CD.CODECOTI
										  AND  CD.CODEVALO != ''
										  AND CD.CODEDETA = D.DERECONS 
										  AND D.DEREUNME  = U.UNMECONS";
	$RsVerificarProveedorIgual = mysqli_query($conexion,$query_RsVerificarProveedorIgual) or die(mysqli_connect_error());
	$row_RsVerificarProveedorIgual = mysqli_fetch_assoc($RsVerificarProveedorIgual);
	$totalRows_RsVerificarProveedorIgual = mysqli_num_rows($RsVerificarProveedorIgual);
	$retorno = array();
	if($totalRows_RsVerificarProveedorIgual>0){
		do{
			$retorno[] = $row_RsVerificarProveedorIgual;
		}while($row_RsVerificarProveedorIgual = mysqli_fetch_array($RsVerificarProveedorIgual));
	}
	echo(json_encode($retorno));
	
}	
if($tipoGuardar == 'FirmarRector'){
$estado      = '';
$color       = '';
$estado_des  = '';	
$afectado = 0;	
$igual    = 0;	
	
	$query_RsVerificarProveedorIgual = "SELECT D.DERECONS 
	                                     FROM detalle_requ D
										WHERE D.DERECONS = '".$_GET['codigo_detalle']."'
										  AND D.DEREPROV = D.DEREPRRE";
	$RsVerificarProveedorIgual = mysqli_query($conexion,$query_RsVerificarProveedorIgual) or die(mysqli_connect_error());
	$row_RsVerificarProveedorIgual = mysqli_fetch_array($RsVerificarProveedorIgual);
	$totalRows_RsVerificarProveedorIgual = mysqli_num_rows($RsVerificarProveedorIgual);
	if($totalRows_RsVerificarProveedorIgual>0){
		$igual= 1;
			$query_RsUpdateFirma = "UPDATE detalle_requ set DEREAPRO = '18',
															DEREFIRM = '2',
															DEREFFRE = SYSDATE()	
									 where DERECONS = '".$_GET['codigo_detalle']."'";
			$RsUpdateFirma = mysqli_query($conexion,$query_RsUpdateFirma) or die(mysqli_error($conexion));	
			$afectado = mysqli_affected_rows($conexion);
			
			$query_RsObtenerEstado = "SELECT D.DEREAPRO ESTADO,
											 E.ESDECOLO COLOR,
											 E.ESDENOMB ESTADO_DES
										 FROM detalle_requ   D,
											  estado_detalle E
										where D.DERECONS = '".$_GET['codigo_detalle']."'
										  AND D.DEREAPRO = E.ESDECODI
											
										 
										 ";
			$RsObtenerEstado = mysqli_query($conexion,$query_RsObtenerEstado) or die(mysqli_connect_error());
			$row_RsObtenerEstado = mysqli_fetch_array($RsObtenerEstado);
			$totalRows_RsObtenerEstado = mysqli_num_rows($RsObtenerEstado);	
			if($totalRows_RsObtenerEstado>0){
				$estado      = $row_RsObtenerEstado['ESTADO'];
				$color       = $row_RsObtenerEstado['COLOR'];
				$estado_des  = $row_RsObtenerEstado['ESTADO_DES'];
			}
	}else{
	$query_RsVerificarProveedorIgual = "SELECT D.DERECONS 
	                                     FROM detalle_requ D
										WHERE D.DERECONS = '".$_GET['codigo_detalle']."'
										  AND D.DEREPROV != D.DEREPRRE
										  and D.DEREPROV != ''";
	$RsVerificarProveedorIgual = mysqli_query($conexion,$query_RsVerificarProveedorIgual) or die(mysqli_connect_error());
	//$row_RsVerificarProveedorIgual = mysqli_fetch_array($RsVerificarProveedorIgual);
	$totalRows_RsVerificarProveedorIgual = mysqli_num_rows($RsVerificarProveedorIgual);		
	if($totalRows_RsVerificarProveedorIgual>0){
		$igual = 2;
	}
		
	}
	echo($afectado.'|'.$estado.'|'.$color.'|'.$estado_des.'|'.$igual);

}
if($tipoGuardar == 'FirmarDirectorioAdm'){
	$query_RsUpdateFirma = "UPDATE detalle_requ set DEREAPRO = '18',
	                                                DEREFIRM = '1',
                                                    DEREFFDA = SYSDATE()	
	                         where DERECONS = '".$_GET['codigo_detalle']."'";
    $RsUpdateFirma = mysqli_query($conexion,$query_RsUpdateFirma) or die(mysqli_error($conexion));	
	
	$query_RsObtenerEstado = "SELECT D.DEREAPRO ESTADO,
	                                 E.ESDECOLO COLOR,
									 E.ESDENOMB ESTADO_DES
	                             FROM detalle_requ   D,
								      estado_detalle E
							    where D.DEREAPRO = E.ESDECODI
								  AND D.DERECONS = '".$_GET['codigo_detalle']."'	
								 
								 ";
	$RsObtenerEstado = mysqli_query($conexion,$query_RsObtenerEstado) or die(mysqli_connect_error());
	$row_RsObtenerEstado = mysqli_fetch_array($RsObtenerEstado);
	$totalRows_RsObtenerEstado = mysqli_num_rows($RsObtenerEstado);	
	$afectado = 0;
	$afectado = mysqli_affected_rows($conexion);
	echo($afectado.'|'.$row_RsObtenerEstado['ESTADO'].'|'.$row_RsObtenerEstado['COLOR'].'|'.$row_RsObtenerEstado['ESTADO_DES']);
}
if($tipoGuardar=='Ordenarcompra'){
	//tipo_orden
	if(isset($_POST['json']) && $_POST['json']!=''){
		//$tojson = $_POST['json'];
		$tojson = json_decode($_POST['json']);
	/*	$nuevo=array();
		$arreglo = get_object_vars( $tojson ); 
		//print_r($arreglo);
		foreach( $arreglo as $indice=>$valor ) 
        {
			 $nuevo[]=$valor;
		}	
*/		
		//echo($tojson->datos[0]->REQUERIMIENTO);
	$query_RsInsertarOrden="INSERT INTO orden_compra (
												 `ORCOCONS`,
												 `ORCOFECH`,
												 `ORCOIDPR`,
												 `ORCOFEEN`,
												 `ORCOOBSE`,
												  ORCODIVA,
												  ORCOTIOR
												 ) 
												 VALUES (
														 NULL,
														 SYSDATE(), 
														 '".$_GET['provasigdet']."', 
														 str_to_date('".$_GET['fecha_entrega']."','%d/%m/%Y'), 
														 '".$_GET['observacion']."',
														 '".$_GET['iva_desc']."',
														 '".$_GET['tipo_orden']."'
														 )";
 			//echo($query_RsInsertarOrden);		
    $RsInsertarOrden = mysqli_query($conexion,$query_RsInsertarOrden) or die(mysqli_error($conexion));

	$query_RsUltInsert = "SELECT LAST_INSERT_ID() DATO";
	$RsUltInsert = mysqli_query($conexion,$query_RsUltInsert) or die(mysqli_error($conexion));
	$row_RsUltInsert = mysqli_fetch_array($RsUltInsert);
	$codigo_orden_compra = $row_RsUltInsert['DATO'];	

	if(is_array($tojson->datos)){
		for($i=0; $i<count($tojson->datos); $i++){
			if($tojson->datos[$i]->DEVOLVER == 0 && $tojson->datos[$i]->TIPO_ORD == $_GET['tipo_orden']){
				 $query_RsInsertarOrdenDet = "INSERT INTO orden_compradet(
																		  ORCDCODI,
																		  ORCDORCO,
																		  ORCDDETA,
																		  ORCDPROV
																		 )
																		 VALUES
																		 (
																		 NULL,
																		 '".$codigo_orden_compra."',
																		 '".$tojson->datos[$i]->COD_DETALLE."',
																		 '".$tojson->datos[$i]->PROVEEDOR."'
																		 )
																		 ";
																		 //echo($query_RsInsertarOrdenDet."<br>");
				$RsInsertarOrdenDet = mysqli_query($conexion,$query_RsInsertarOrdenDet) or die(mysqli_error($conexion));
				
				$query_RsUpdateOrdenCompraDet = "UPDATE detalle_requ SET DEREAPRO = '16',
				                                                         DERECOOC = '".$codigo_orden_compra."'
												 WHERE DERECONS = '".$tojson->datos[$i]->COD_DETALLE."'
												 ";
				$RsUpdateOrdenCompraDet = mysqli_query($conexion,$query_RsUpdateOrdenCompraDet) or die(mysqli_error($conexion));
		    }
			/*
			if($tojson->datos[$i]->DEVOLVER == 1 && $tojson->datos[$i]->TIPO_ORD == $_GET['tipo_orden']){
				$query_RsDatosDetalle = "SELECT * from detalle_requ where DERECONS ='".$tojson->datos[$i]->COD_DETALLE."'";
				$RsDatosDetalle = mysqli_query($conexion,$query_RsDatosDetalle) or die(mysqli_error($conexion));
				$row_RsDatosDetalle = mysqli_fetch_array($RsDatosDetalle);
				$totalRows_RsDatosDetalle = mysqli_num_rows($RsDatosDetalle);				
				if($totalRows_RsDatosDetalle>0){
					$veces = $row_RsDatosDetalle['DERECARE']+1;
					$query_RsUpdateOrdenCompraDet = "UPDATE detalle_requ SET DEREAPRO = '10',
																			 DEREPROV = '',
					                                                         DEREPROV2 = '".$row_RsDatosDetalle['DEREPROV']."',
																			 DERECARE  = '".$veces."'
													 WHERE DERECONS = '".$tojson->datos[$i]->COD_DETALLE."'
													 ";
					$RsUpdateOrdenCompraDet = mysqli_query($conexion,$query_RsUpdateOrdenCompraDet) or die(mysqli_error($conexion));
					$query_RsInsertDetalleRecotizado = "INSERT INTO detalle_recotizado
					                                           (
															   DERECODI,
															   DEREDETA,
															   DERECOTI,
															   DEREPROV,
															   DEREVAUN
															   )
															   VALUES(
															   NULL,
															   '".$tojson->datos[$i]->COD_DETALLE."',
															   '".$tojson->datos[$i]->COTIZACION."',
															   '".$tojson->datos[$i]->PROVEEDOR."',
															   '".$tojson->datos[$i]->PRECIO_UNREAL."'
															   )
					
					";
					$RsInsertDetalleRecotizado = mysqli_query($conexion,$query_RsInsertDetalleRecotizado) or die(mysqli_error($conexion));					
				}
			}
			*/
		}
	}
		
	}
	echo($codigo_orden_compra);
}

if($tipoGuardar=='DevolverDetalleOrdenCompra'){
	$afectado = 0;
				$query_RsDatosDetalle = "SELECT * from detalle_requ where DERECONS ='".$_GET['codigo_detalle']."'";
				$RsDatosDetalle = mysqli_query($conexion,$query_RsDatosDetalle) or die(mysqli_error($conexion));
				$row_RsDatosDetalle = mysqli_fetch_array($RsDatosDetalle);
				$totalRows_RsDatosDetalle = mysqli_num_rows($RsDatosDetalle);				
				if($totalRows_RsDatosDetalle>0){
					$veces = $row_RsDatosDetalle['DERECARE']+1;
					$query_RsUpdateOrdenCompraDet = "UPDATE detalle_requ SET DEREAPRO = '10',
																			 DEREPROV = '',
					                                                         DEREPROV2 = '".$row_RsDatosDetalle['DEREPROV']."',
																			 DERECARE  = '".$veces."'
													 WHERE DERECONS = '".$_GET['codigo_detalle']."'
													 ";
					$RsUpdateOrdenCompraDet = mysqli_query($conexion,$query_RsUpdateOrdenCompraDet) or die(mysqli_error($conexion));
					$afectado = mysqli_affected_rows($conexion);
					$query_RsInsertDetalleRecotizado = "INSERT INTO detalle_recotizado
					                                           (
															   DERECODI,
															   DEREDETA,
															   DERECOTI,
															   DEREPROV,
															   DEREVAUN
															   )
															   VALUES(
															   NULL,
															   '".$_GET['codigo_detalle']."',
															   '".$_GET['cotizacion']."',
															   '".$_GET['proveedor']."',
															   '".$_GET['precio_unreal']."'
															   )
					
					";
					$RsInsertDetalleRecotizado = mysqli_query($conexion,$query_RsInsertDetalleRecotizado) or die(mysqli_error($conexion));	
					
				}
				
				//echo($_GET['codigo_detalle'].'//'.$_GET['cotizacion']."//".$_GET['proveedor']."//".$_GET['precio_unreal']);
				echo($afectado);
}
function getDataByOrdenId($ordenId, $conexion, $estadoId = null){
	$query_RsDetalleCompra=" 
SELECT dr.DERECONS codigo_detalle,
           oc.ORCOCONS orden_compra_id,
           dr.DEREAPRO estado_detalle,
           dr.DERECOOC orden_compra_detalle
     FROM orden_compra oc,
	      orden_compradet ocd,
		  detalle_requ dr
		  
     where oc.ORCOCONS = ocd.ORCDORCO
	      and ocd.ORCDDETA = dr.DERECONS
          and oc.ORCOCONS = '".$ordenId."'";

if($estadoId != null ){ $query_RsDetalleCompra .= " and dr.DEREAPRO = '".$estadoId."'"; }

//ESTADO 18 APROBADO COMPRA
//echo ($query_RsDetalleCompra);
$RsDetalleCompra = mysqli_query($conexion,$query_RsDetalleCompra) or die(mysqli_error($conexion));
$row_RsDetalleCompra = mysqli_fetch_assoc($RsDetalleCompra);
$totalRows_RsDetalleCompra = mysqli_num_rows($RsDetalleCompra);
//$response = array();
$datos = array();
if($totalRows_RsDetalleCompra>0){
do{
$datos[] = array(
		'codigo_detalle'       => $row_RsDetalleCompra['codigo_detalle'],
		'orden_compra_id'       => $row_RsDetalleCompra['orden_compra_id'],
		'estado_detalle'       => $row_RsDetalleCompra['estado_detalle'],
		'orden_compra_detalle'       => $row_RsDetalleCompra['orden_compra_detalle'],
	   );	
}while($row_RsDetalleCompra = mysqli_fetch_array($RsDetalleCompra));
}

  return $datos;
}
if($tipoGuardar=='ConsultarDataOrden'){
   $query_RsDetalleCompra="SELECT `PROVCODI`,
				        `PROVNOMB` NOMBRE_PROVEEDOR,
						 DERECANT  CANTIDAD,
						 (DERECANT*CODEVALO)  VALOR_TOTAL,
						 DEREDESC  DESCRIPCION_DETALLE,
						 CODEDESC  DESCRIPCION_PROVEEDOR,
						 CODEVALO  PRECIO_UNITARIO,
	                     PROVCODI  PROVEEDOR,						 
						 REQUCORE REQUERIMIENTO,
						 DERECONS COD_DETALLE,
						 COTICODI COTIZACION,
						 DERETIPO TIPO_ORDEN,
						(SELECT T.TOCONOMB
						  FROM tipoorden_compra T
						 WHERE T.TOCOCODI = DERETIPO
						  LIMIT 1) TIPO_ORDEN_DES
				FROM   `proveedores`,
                        detalle_requ,
						requerimientos,
						cotizacion_detalle,
						cotizacion,
						cotizacion_orden
						
				WHERE `DEREPROV`='".$_GET['provasigdet']."'
                AND    COTIPROV=DEREPROV				
				AND    DEREPROV=PROVCODI
				AND    REQUCODI=DEREREQU
				AND    CODECOTI=COTICODI
				AND    COTIORDE=COORCODI
				AND    CODEDETA=DERECONS
				and    DEREAPRO=18 
				";
				//ESTADO 18 APROBADO COMPRA
				//echo ($query_RsDetalleCompra);
	$RsDetalleCompra = mysqli_query($conexion,$query_RsDetalleCompra) or die(mysqli_error($conexion));
	$row_RsDetalleCompra = mysqli_fetch_assoc($RsDetalleCompra);
    $totalRows_RsDetalleCompra = mysqli_num_rows($RsDetalleCompra);
	//$response = array();
	$datos = array();
	$valores = array();
	$valorestipotrabajo  = array();
	$valorestiposervicio = array();
	$valorestipoproducto = array();
	if($totalRows_RsDetalleCompra>0){
		do{
	       $datos[] = array('CODIGO_PROV'       => $row_RsDetalleCompra['PROVCODI'],
							'NOMBRE_PROVEEDOR'  => $row_RsDetalleCompra['NOMBRE_PROVEEDOR'],
							'CANTIDAD'          => $row_RsDetalleCompra['CANTIDAD'],
							'VALOR_TOTAL'       => "$".number_format($row_RsDetalleCompra['VALOR_TOTAL'],1,'.',','),
							'DESCR_DET'         => $row_RsDetalleCompra['DESCRIPCION_DETALLE'],
							'DESCR_PROV'        => $row_RsDetalleCompra['DESCRIPCION_PROVEEDOR'],
							'PRECIO_UN'         => "$".number_format($row_RsDetalleCompra['PRECIO_UNITARIO'],1,'.',','),
							'PRECIO_UNREAL'     => $row_RsDetalleCompra['PRECIO_UNITARIO'],
							'PROVEEDOR'         => $row_RsDetalleCompra['PROVEEDOR'],
							'REQUERIMIENTO'     => $row_RsDetalleCompra['REQUERIMIENTO'],
							'COD_DETALLE'       => $row_RsDetalleCompra['COD_DETALLE'],
							'COD_DETALLE'       => $row_RsDetalleCompra['COD_DETALLE'],
							'DEVOLVER'          => 0,
							'TIPO_ORD'          => $row_RsDetalleCompra['TIPO_ORDEN'],
							'TIPO_ORD_DES'      => $row_RsDetalleCompra['TIPO_ORDEN_DES'],
							'COTIZACION'         => $row_RsDetalleCompra['COTIZACION'],
						   );	
		   $valores[] = $row_RsDetalleCompra['VALOR_TOTAL'];
		   if($row_RsDetalleCompra['TIPO_ORDEN'] == 1){
			   $valorestipoproducto[] = $row_RsDetalleCompra['VALOR_TOTAL'];
		   }
		   if($row_RsDetalleCompra['TIPO_ORDEN'] == 2){
			   $valorestiposervicio[] = $row_RsDetalleCompra['VALOR_TOTAL'];
		   }
		   if($row_RsDetalleCompra['TIPO_ORDEN'] == 3){
			   $valorestipotrabajo[] = $row_RsDetalleCompra['VALOR_TOTAL'];
		   }
		}while($row_RsDetalleCompra = mysqli_fetch_array($RsDetalleCompra));
	}
	$response = array('datos'  => $datos,
					  'sum'    => "$".number_format(array_sum($valores),0,'.',','),
					  'sumpr'  => "$".number_format(array_sum($valorestipoproducto),0,'.',','),
					  'sumser' => "$".number_format(array_sum($valorestiposervicio),0,'.',','),
					  'sumtra' => "$".number_format(array_sum($valorestipotrabajo),0,'.',','),
					  );
echo(json_encode($response));
}
if($tipoGuardar=='AsignarProveedorRector'){
	$query_RsRequerimientosDetalle = " UPDATE detalle_requ SET  DEREPRRE = '".$_GET['proveedor']."'
										WHERE DERECONS = '".$_GET['codigo_detalle']."'
									  ";
	$RsRequerimientosDetalle       = mysqli_query($conexion,$query_RsRequerimientosDetalle) or die(mysqli_error());
	
	
	//echo($query_RsRequerimientosDetalle);
	echo(mysqli_affected_rows($conexion));		
}	
if($tipoGuardar=='AsignarProveedor'){
	
	
	$query_RsRequerimientosDetalle = " UPDATE detalle_requ SET  DEREAPRO = '6',
															    DEREPROV = '".$_GET['proveedor']."',
																DEREDCOM ='".$_GET['descripcion']."'
										WHERE DERECONS = '".$_GET['codigo_detalle']."'
									  ";
	$RsRequerimientosDetalle       = mysqli_query($conexion,$query_RsRequerimientosDetalle) or die(mysqli_error());
	
	
	//echo($query_RsRequerimientosDetalle);
	echo(mysqli_affected_rows($conexion));	
}
if($tipoGuardar=='GuardarProdConv'){
	//var_dump($_POST['json']); exit();
	$afectado = 0;
	if(isset($_POST['json']) && $_POST['json']!=''){
		//$tojson = $_POST['json'];
		$tojson = json_decode($_POST['json']);
		if(is_array($tojson)){
			for($i=0; $i<count($tojson); $i++){
				//echo($tojson[$i]->price.'<br>');
					$query_RsRequerimientosDetalle = "INSERT INTO productos (
																			PRODCONS,
																			PRODCODI,
																			PRODCOSI,
																			PRODDESC,
																			PRODIDCAT,
																			PRODPREC,																			
																			PRODSTOK,
																			PRODFERE,
																			PRODCANT,
																			PRODIDUM,
																			PRODORIG,
																			PRODCOCO,
																			PRODCODE
																			
																			)
																			VALUES
																			(
																			NULL,
																			'".$tojson[$i]->det."',
																			'".$tojson[$i]->det."',
																			'".$tojson[$i]->des."',
																			'-1',
																			'".$tojson[$i]->price."',
																			'0',
																			sysdate(),
																			'".$tojson[$i]->cant."',
																			'".$tojson[$i]->um."',
																			'1',
																			'".$_GET['cotizacion']."',
																			'".$tojson[$i]->det."'
																			)
									  ";
					$RsRequerimientosDetalle       = mysqli_query($conexion,$query_RsRequerimientosDetalle) or die(mysqli_error());
					$query_RsUltInsert = "SELECT LAST_INSERT_ID() DATO";
					$RsUltInsert = mysqli_query($conexion,$query_RsUltInsert) or die(mysqli_error($conexion));
					$row_RsUltInsert = mysqli_fetch_array($RsUltInsert);
					$codigo_nuevo_producto = $row_RsUltInsert['DATO'];	
					
					$query_RsUsuario_Nuevo = "INSERT INTO conve_produc (
																		COPRID,
																		COPRIDPC,
																		COPRIDCO,
																		COPRPREC,
																		COPRCANT
																		)
																		VALUES
																		(
																		NULL,
																		'".$codigo_nuevo_producto."',
																		'".$_GET['convenio']."',
																		'".$tojson[$i]->price."',
																		'".$tojson[$i]->cant."'
																		)
																		";
					$RsUsuario_Nuevo = mysqli_query($conexion,$query_RsUsuario_Nuevo) or die(mysqli_error($conexion));	
					$afect = mysqli_affected_rows($conexion);
					$afectado = $afectado + $afect;
			}
		}
		print_r($afectado);
	}
}
if($tipoGuardar=='GuardarNuevoProducto'){
	$query_RsInsertProducto = "INSERT INTO productos (
													PRODCONS,
													PRODCODI,
													PRODCOSI,
													PRODDESC,
													PRODIDCAT,
													PRODIDUM,
													PRODPREC,
													PRODCANT,
													PRODSTOK,
													PRODFERE
													)
													values
													(
													NULL,
													'".$_GET['codigo_general']."',
													'".$_GET['codigo_sigo']."',
													'".$_GET['nombre_producto']."',
													'".$_GET['categoria']."',
													'".$_GET['unidad_medida']."',
													'".$_GET['precio']."',
													'".$_GET['cantidad']."',
													'".$_GET['stock']."',
													sysdate()
													)";
	$RsInsertProducto       = mysqli_query($conexion,$query_RsInsertProducto) or die(mysqli_error());
	$query_RsUltInsert = "SELECT LAST_INSERT_ID() DATO";
	$RsUltInsert = mysqli_query($conexion,$query_RsUltInsert) or die(mysqli_error($conexion));
	$row_RsUltInsert = mysqli_fetch_array($RsUltInsert);
	$codigo_nuevo_producto = $row_RsUltInsert['DATO'];
	echo($codigo_nuevo_producto);
}

if($tipoGuardar=='CargarProductosConvenido'){
	$query_RsProductosConvenio = "SELECT P.PRODCONS CODIGO,
										 P.PRODDESC NOMBRE,
										 P.PRODPREC PRECIO,
										 ifnull((select U.UNMESIGL FROM UNIDAD_MEDIDA U WHERE U.UNMECONS = P.PRODIDUM),'') UM,
										 C.COPRID   CODI_CONVE_PRODUC,
										 P.PRODCANT CANT
	                               FROM productos P,
								        conve_produc C
								 where P.PRODCONS = C.COPRIDPC
								   AND COPRIDCO = '".$_GET['convenio']."'
								  order by P.PRODDESC
	";
	$RsProductosConvenio = mysqli_query($conexion,$query_RsProductosConvenio) or die(mysqli_error($conexion));
	$row_RsProductosConvenio = mysqli_fetch_array($RsProductosConvenio);
    $totalRows_RsProductosConvenio = mysqli_num_rows($RsProductosConvenio);
	$productos = array();
	
    if($totalRows_RsProductosConvenio >0){
		do{
			$productos[] = array(
			                     'CODIGO'  			  => $row_RsProductosConvenio['CODIGO'],
			                     'CODI_CONVE_PRODUC'  => $row_RsProductosConvenio['CODI_CONVE_PRODUC'],
			                     'NOMBRE'  			  => $row_RsProductosConvenio['NOMBRE'],
			                     'PRECIO'  			  => '$'.number_format($row_RsProductosConvenio['PRECIO'],0,'.',','),
			                     'UM'  			      => $row_RsProductosConvenio['UM'],
			                     'CANT'  			  => $row_RsProductosConvenio['CANT'],
			                     );
		}while($row_RsProductosConvenio = mysqli_fetch_array($RsProductosConvenio));
	}
	echo(json_encode($productos));
}
if($tipoGuardar=='NuevoProductoConvenio'){
	$query_RsInsertProductoConvenio = "INSERT INTO conve_produc (
																COPRID,
																COPRIDPC,
																COPRIDCO,
																COPRPREC,
																COPRCANT
																)
																VALUES
																(
																NULL,
																'".$_GET['producto']."',
																'".$_GET['convenio']."',
																'".$_GET['precio']."',
																'".$_GET['cantidad']."'
																)
	
	";
	$RsInsertProductoConvenio       = mysqli_query($conexion,$query_RsInsertProductoConvenio) or die(mysqli_error());
	$query_RsUltInsert = "SELECT LAST_INSERT_ID() DATO";
	$RsUltInsert = mysqli_query($conexion,$query_RsUltInsert) or die(mysqli_error($conexion));
	$row_RsUltInsert = mysqli_fetch_array($RsUltInsert);
	$nuevo_producto_convenio = $row_RsUltInsert['DATO'];
	echo($nuevo_producto_convenio);	
	
}

if($tipoGuardar=='marcarEstadoCot'){
	//SELECT ifnull(C.COTIFORE,'-1') CAMPO  FROM COTIZACION C WHERE COTICODI 
	$query_RsRequerimientosDetalle = "SELECT COTITOTA CAMPO FROM COTIZACION C WHERE COTICODI= '".$_GET['cotizacion']."'";
	$RsRequerimientosDetalle       = mysqli_query($conexion,$query_RsRequerimientosDetalle) or die(mysqli_error());
	$row_RsRequerimientosDetalle   = mysqli_fetch_assoc($RsRequerimientosDetalle);
    
	if($row_RsRequerimientosDetalle['CAMPO']=='0'){
		exit('-1');
	}
	//return 2;
	//exit($row_RsRequerimientosDetalle['CAMPO']);
	
	
	$query_RsRequerimientosDetalle = "SELECT  C.COTIPROV PROVEEDOR,
									P.PROVNOMB PROVEEDOR_DES,
									C.COTICODI COTIZACION,
									C.COTIESTA ESTADO_D,
									D.DERECONS DETALLE,
									R.REQUCODI REQUERIMIENTO
						   FROM COTIZACION C,
						       COTIZACION_DETALLE CD,
							   DETALLE_REQU    D,
							   PROVEEDORES     P,
							   REQUERIMIENTOS  R
						  WHERE C.COTIORDE = '".$_GET['orden']."'
						    AND C.COTICODI = CD.CODECOTI
							AND CD.CODEDETA = D.DERECONS
							AND C.COTIPROV = P.PROVCODI
							AND D.DEREREQU = R.REQUCODI
							AND C.COTIPROV = '".$_GET['proveedor']."'
							AND C.COTICODI = '".$_GET['cotizacion']."'
									 ";
 	$RsRequerimientosDetalle       = mysqli_query($conexion,$query_RsRequerimientosDetalle) or die(mysqli_error());
	$row_RsRequerimientosDetalle   = mysqli_fetch_assoc($RsRequerimientosDetalle);
    $totalRows_RsRequerimientosDetalle = mysqli_num_rows($RsRequerimientosDetalle);	
	//echo($query_RsRequerimientosDetalle);
	if($totalRows_RsRequerimientosDetalle>0){
		$query_RsaCotizado="UPDATE cotizacion_orden SET COORESTA = 1 WHERE COORCODI = '".$_GET['orden']."'";
 	    $RsaCotizado     = mysqli_query($conexion,$query_RsaCotizado) or die(mysqli_error());
		do{
			$query_RsActualizar = "UPDATE DETALLE_REQU SET DEREAPRO = '9' WHERE DERECONS =  '".$row_RsRequerimientosDetalle['DETALLE']."'";
			//echo($query_RsActualizar.'<br>');
			$RsActualizar  = mysqli_query($conexion,$query_RsActualizar) or die(mysqli_error());
			
			$query_RsActualizar = "UPDATE REQUERIMIENTOS SET REQUESTA = '5', 
													         REQUFECO = sysdate(),
															 REQUPECO = '".$_SESSION['MM_UserID']."'
			                                             WHERE REQUCODI =  '".$row_RsRequerimientosDetalle['REQUERIMIENTO']."'";
			//echo($query_RsActualizar.'<br>');
			$RsActualizar  = mysqli_query($conexion,$query_RsActualizar) or die(mysqli_error());
		}while($row_RsRequerimientosDetalle   = mysqli_fetch_assoc($RsRequerimientosDetalle));
	}
echo('2');	
}


if($tipoGuardar=='validarFechasRequerimiento'){
$response = 0;
 if(isset($_GET['fecha_desde'])  && $_GET['fecha_desde']!= ''){
    $query_RsFechasLista="SELECT F.FEREFEHA 
	                        FROM FECHAS_REQ F
						  WHERE F.FERECODI =(SELECT MAX(F2.FERECODI)
						                      FROM FECHAS_REQ F2
											 )";
 	$RsFechasLista     = mysqli_query($conexion,$query_RsFechasLista) or die(mysqli_error());
	$row_RsFechasLista = mysqli_fetch_assoc($RsFechasLista);
    //$totalRows_RsFechasLista = mysqli_num_rows($RsFechasLista);
    if($row_RsFechasLista['FEREFEHA']>0){
	$query_RsDiferencia = "SELECT DATEDIFF(str_to_date('".$_GET['fecha_desde']."','%d/%m/%Y'),'".$row_RsFechasLista['FEREFEHA']."') DIFERENCIA;";
 	$RsDiferencia       = mysqli_query($conexion,$query_RsDiferencia) or die(mysqli_error());
	$row_RsDiferencia   = mysqli_fetch_assoc($RsDiferencia);	
		if($row_RsDiferencia['DIFERENCIA']>0){
		 $response = '1';
		}else{
		 $response = '0';
		}
	}else{
		$response='1';
	}
 }
 echo($response);
}

if($tipoGuardar=='MarcarNoRecibido'){
 	// consulta de numero total de detalles 
	$query_RsTodosLosDetalles = "SELECT COUNT(D.DERECONS) TOTAL
 	                              FROM DETALLE_REQU D
								 WHERE D.DEREREQU = '".$_GET['codreq']."'";
	$RsTodosLosDetalles = mysqli_query($conexion,$query_RsTodosLosDetalles) or die(mysqli_error($conexion));
	$row_RsTodosLosDetalles = mysqli_fetch_array($RsTodosLosDetalles);
	$tdeta=$row_RsTodosLosDetalles['TOTAL'];
	// consulta de numero total de detalles devueltos
	$query_RsTodosLosDetallesDevuelto = "SELECT COUNT(D.DERECONS) TOTAL
 	                              FROM DETALLE_REQU D
								 WHERE D.DEREREQU = '".$_GET['codreq']."'
								   AND D.DEREAPRO = 2 ";
	$RsTodosLosDetallesDevuelto = mysqli_query($conexion,$query_RsTodosLosDetallesDevuelto) or die(mysqli_error($conexion));
	$row_RsTodosLosDetallesDevuelto = mysqli_fetch_array($RsTodosLosDetallesDevuelto);
	$tdevu=$row_RsTodosLosDetallesDevuelto['TOTAL'];
	//condicion de igualdad para saber si se ejecuta el estado no recibido
	if($tdeta==$tdevu){
	  echo($row_RsTodosLosDetalles['TOTAL']);
	}else{
	 echo('none');
	}
}

if($tipoGuardar=='TodosAprobDetalle'){
    $query_RsTodosLosDetalles = "SELECT COUNT(D.DERECONS) TOTAL
 	                              FROM DETALLE_REQU D
								 WHERE D.DEREREQU = '".$_GET['codreq']."'";
	$RsTodosLosDetalles = mysqli_query($conexion,$query_RsTodosLosDetalles) or die(mysqli_error($conexion));
	$row_RsTodosLosDetalles = mysqli_fetch_array($RsTodosLosDetalles);
	$tdeta=$row_RsTodosLosDetalles['TOTAL'];
	// consulta de numero total de detalles devueltos
	$query_RsTodosLosDetallesDevuelto = "SELECT COUNT(D.DERECONS) TOTAL
 	                              FROM DETALLE_REQU D
								 WHERE D.DEREREQU = '".$_GET['codreq']."'
								   AND D.DEREAPRO = 2 ";
	$RsTodosLosDetallesDevuelto = mysqli_query($conexion,$query_RsTodosLosDetallesDevuelto) or die(mysqli_error($conexion));
	$row_RsTodosLosDetallesDevuelto = mysqli_fetch_array($RsTodosLosDetallesDevuelto);
	$tdevu=$row_RsTodosLosDetallesDevuelto['TOTAL'];
	
 	$query_RsTodosLosDetalles2 = "SELECT COUNT(D.DERECONS) TOTAL
 	                              FROM DETALLE_REQU D
								 WHERE D.DEREREQU = '".$_GET['codreq']."'
								   AND D.DEREAPRO = 0";
	$RsTodosLosDetalles2 = mysqli_query($conexion,$query_RsTodosLosDetalles2) or die(mysqli_error($conexion));
	$row_RsTodosLosDetalles2 = mysqli_fetch_array($RsTodosLosDetalles2);
    $totalRows_RsTodosLosDetalles2 = mysqli_num_rows($RsTodosLosDetalles2);
	
	if($tdevu==$tdeta){
	echo("1");
	}else if($row_RsTodosLosDetalles2['TOTAL'] != 0){
	echo("2");
	}else{echo ("none");}
}

if($tipoGuardar=='AprobarReq'){
   $query_RsUsuario_Nuevo="UPDATE REQUERIMIENTOS SET REQUESTA = 5
                            where REQUCODI = '".$_GET['codreq']."'";
   $RsUsuario_Nuevo = mysqli_query($conexion,$query_RsUsuario_Nuevo) or die(mysqli_error($conexion));  
   $afectado=mysqli_affected_rows($conexion);
   if($afectado!=''){
    echo($afectado);
   }else{
   echo('none');
   }
}


if($tipoGuardar=='ModificarOtro'){
 $query_RsPersona_Nueva="UPDATE DETALLE_REQU SET DEREOTRO  = '".$_GET['otro']."',
                                                 DEREREOT  = '1'
							where DERECONS = '".$_GET['codigo_detalle']."' 
							";
    $RsPersona_Nueva = mysqli_query($conexion,$query_RsPersona_Nueva) or die(mysqli_error($conexion));
	echo(mysqli_affected_rows($conexion));
}

if($tipoGuardar=='ModificarPoaSubpoa'){
 $query_RsPersona_Nueva="UPDATE DETALLE_REQU SET DEREPOA  = '".$_GET['poa']."',
                                                 DERESUPO = '".$_GET['subpoa']."',
												 DEREREOT = 0,
												 DEREOTRO = ''
							where DERECONS = '".$_GET['codigo_detalle']."' 
							";
    $RsPersona_Nueva = mysqli_query($conexion,$query_RsPersona_Nueva) or die(mysqli_error($conexion));
	echo(mysqli_affected_rows($conexion));
}

if($tipoGuardar=='ModificarCantidad'){
$query_RsPersona_Nueva="UPDATE DETALLE_REQU SET DERECANT  = '".$_GET['newcantidad']."'
							where DERECONS = '".$_GET['codigo_detalle']."' 
							";
    $RsPersona_Nueva = mysqli_query($conexion,$query_RsPersona_Nueva) or die(mysqli_error($conexion));
    $query_RsListadoDeta_Requ="SELECT D.DERECONS CONSECUTIVO,
	                                  D.DEREMODA MODALIDAD,
									  D.DERECLAS CLASIFICACION,
									  D.DEREDESC DESCRIPCION,
									  D.DERECANT CANTIDAD,
									  D.DEREJUST JUSTIFICACION,
									  D.DEREOBSE OBSERVACIONES,
									  D.DERETISE TIPO_PRODUCTO,
									  D.DEREREQU CODIGO_REQUERIMIENTO,
									  D.DEREAPRO APROBADO,
									  D.DEREUNME UNIDAD_MEDIDA
							     FROM DETALLE_REQU D
								WHERE D.DERECONS = '".$_GET['codigo_detalle']."'";
    $RsListadoDeta_Requ = mysqli_query($conexion, $query_RsListadoDeta_Requ) or die(mysqli_error($conexion));
	$row_RsListadoDeta_Requ = mysqli_fetch_array($RsListadoDeta_Requ);
    $totalRows_RsListadoDeta_Requ = mysqli_num_rows($RsListadoDeta_Requ);
	echo(mysqli_affected_rows($conexion));
	if($totalRows_RsListadoDeta_Requ >0){
	  $query_RsInsertCorreccion ="INSERT INTO DETALLE_REQUHIST (
	                                                            DERECONS,
																DEREMODA,
																DERECLAS,
																DEREDESC,
																DERECANT,
																DEREJUST,
																DEREOBSE,
																DERETISE,
																DEREREQU,
																DEREAPRO,
																DEREUNME,
																DEREPERS,
																DEREFERE,
																DEREESTA
															   )
															   VALUES
															   (
															    NULL,
																'".$row_RsListadoDeta_Requ['MODALIDAD']."',
																'".$row_RsListadoDeta_Requ['CLASIFICACION']."',
																'".$row_RsListadoDeta_Requ['DESCRIPCION']."',
																'".$row_RsListadoDeta_Requ['CANTIDAD']."',
																'".$row_RsListadoDeta_Requ['JUSTIFICACION']."',
																'".$row_RsListadoDeta_Requ['OBSERVACIONES']."',
																'".$row_RsListadoDeta_Requ['TIPO_PRODUCTO']."',
																'".$row_RsListadoDeta_Requ['CODIGO_REQUERIMIENTO']."',
																'".$row_RsListadoDeta_Requ['APROBADO']."',
																'".$row_RsListadoDeta_Requ['UNIDAD_MEDIDA']."',
																'".$_SESSION['MM_UserID']."',
																sysdate(),
																'".$estado_requerimiento."'
															   )"; 
	  $RsInsertCorreccion = mysqli_query($conexion, $query_RsInsertCorreccion) or die(mysqli_error($conexion));
	}
	
}

if($tipoGuardar=='ComprobarNotCero'){
     $query_RsPoa="SELECT D.DERECONS CODIGO,
                          D.DEREAPRO ESTADO	 
	               FROM DETALLE_REQU D
				  WHERE D.DEREREQU = '".$_GET['codreq']."'
				    AND D.DEREAPRO = 0";
	$RsPoa = mysqli_query($conexion,$query_RsPoa) or die(mysqli_error($conexion));
	$row_RsPoa = mysqli_fetch_array($RsPoa);
    $totalRows_RsPoa = mysqli_num_rows($RsPoa); 
	if($totalRows_RsPoa>0){
	  echo('si');
	}else{
	echo('none');
	}
}

if($tipoGuardar=='CargarDatos'){

	//if($_GET['tipo']==0){
		$query_RsListadoDeta_Requ="SELECT `DERECONS`,
										  `DEREMODA`,
										  `DERECLAS`,
										  `DEREDESC`,
										  `DERECANT`,
										  `DEREJUST`,
										  `DEREOBSE`,
										  `DERETISE`,
										  `DEREREQU`,
										   DEREUNME ,
										  '' PRODCONS ,
										  '' TIPRCONS,
										  '' TIPRNOMB
										  FROM
									 `DETALLE_REQU` d
									WHERE 1
									and DERECONS ='".$_GET['codigo_detalle']."'
									";
	//}
/*
	if($_GET['tipo']==1){
		$query_RsListadoDeta_Requ="SELECT `DERECONS`,
										  `DEREMODA`,
										  `DERECLAS`,
										  `DEREDESC`,
										  `DERECANT`,
										  `DEREJUST`,
										  `DEREOBSE`,
										  `DERETISE`,
										  `DEREREQU`,
										  p.PRODCONS ,
										  t.TIPRCONS,
										  t.TIPRNOMB
										  FROM
									 `DETALLE_REQU` d,
									  PRODUCTOS p,
									  TIPO_PRODUCTO t

									WHERE 1
									and d.DEREMODA = p.PRODCONS
									and d.DERECLAS = t.TIPRCONS
									and t.TIPRIDPR = p.PRODCONS
									and DERECONS ='".$_GET['codigo_detalle']."'
									";
	}
	*/
    $RsListadoDeta_Requ = mysqli_query($conexion, $query_RsListadoDeta_Requ) or die(mysqli_error($conexion));
	$row_RsListadoDeta_Requ = mysqli_fetch_array($RsListadoDeta_Requ);
    $totalRows_RsListadoDeta_Requ = mysqli_num_rows($RsListadoDeta_Requ);
	
$response = array();


$response[] = array(
						'codigo'   		=> $row_RsListadoDeta_Requ['DERECONS'],
						'modalidad' 		=> $row_RsListadoDeta_Requ['DEREMODA'],
						'clasificacion' 	=> $row_RsListadoDeta_Requ['DERECLAS'],
						'descripcion' 	=> $row_RsListadoDeta_Requ['DEREDESC'],
						'cantidad' 		=> $row_RsListadoDeta_Requ['DERECANT'],
						'justificacion' 	=> $row_RsListadoDeta_Requ['DEREJUST'],
						'observacion' 	=> $row_RsListadoDeta_Requ['DEREOBSE'],
						'tise' 		=> $row_RsListadoDeta_Requ['DERETISE'],
						'requerimiento' 	=> $row_RsListadoDeta_Requ['DEREREQU'],
						'tipo_producto' 	=> $row_RsListadoDeta_Requ['TIPRNOMB'],
						'unidad' 		=> $row_RsListadoDeta_Requ['DEREUNME']
	                   );
	echo(json_encode($response));




}

if($tipoGuardar=='CargarCampos'){
 /*obs
 just
 desc*/
  if(isset($_GET['codigo_detalle']) && $_GET['codigo_detalle']!='' && isset($_GET['campo']) && $_GET['campo']!=''){
    $query_RsDatosCampos="SELECT D.DEREDESC DESCRIPCION,
	                             D.DEREJUST JUSTIFICACION,
								 D.DEREOBSE OBSERVACION
						   FROM DETALLE_REQU D
						  WHERE D.DERECONS = '".$_GET['codigo_detalle']."'";
    $RsDatosCampos = mysqli_query($conexion, $query_RsDatosCampos) or die(mysqli_error($conexion));
	$row_RsDatosCampos = mysqli_fetch_array($RsDatosCampos);
    $totalRows_RsDatosCampos = mysqli_num_rows($RsDatosCampos); 
	if($totalRows_RsDatosCampos>0){
	     echo($row_RsDatosCampos['DESCRIPCION'].'|'.$row_RsDatosCampos['JUSTIFICACION'].'|'.$row_RsDatosCampos['OBSERVACION']);
	}else{
	echo('none');
	}
  }

}

if($tipoGuardar=='CancelarDetalle'){
$query_RsPersona_Nueva="UPDATE DETALLE_REQU SET DEREAPRO  = '3'
							where DERECONS = '".$_GET['coddet']."' 
							";
    $RsPersona_Nueva = mysqli_query($conexion,$query_RsPersona_Nueva) or die(mysqli_error($conexion));
	echo(mysqli_affected_rows($conexion));
}


if($tipoGuardar=='DetallesCotizacion'){
$vars =array();
 if(isset($_GET['detalles'])  && $_GET['detalles']!=''){
     $vars = $_GET['detalles'];
	 //var_dump($vars);
	 //echo(gettype($vars));

	 echo('es el valor que llega'.$_GET['detalles']);
	 echo('el tamano es '.count($vars));
   
  }
 if(isset($_GET['proveedor'])  && $_GET['proveedor']!=''){
  
 }
}

if($tipoGuardar=='mostrarTab'){
    $query_RsListadoDeta_Requ = "SELECT P.PODETIPO TIPO
	                               FROM POADETA P 
								 WHERE P.PODECODI = '".$_GET['subpoa']."'";
    $RsListadoDeta_Requ = mysqli_query($conexion, $query_RsListadoDeta_Requ) or die(mysqli_error($conexion));
	$row_RsListadoDeta_Requ = mysqli_fetch_array($RsListadoDeta_Requ);
    $totalRows_RsListadoDeta_Requ = mysqli_num_rows($RsListadoDeta_Requ);
	if($totalRows_RsListadoDeta_Requ>0){
	 echo($row_RsListadoDeta_Requ['TIPO']);
	}else{
	echo('none');
	}

}



if($tipoGuardar=='Crear_usuario'){
$s=" ";
//Insertar persona
$query_RsPersona_Nueva="INSERT INTO  PERSONAS (
								`PERSID` ,
								`PERSNOMB` ,
								`PERSDIRE` ,
								`PERSTELE` ,
								`PERSUSUA` ,
								`PERSREGI` ,
								`PERSTARI` ,
								`PERSCIUD` ,
								`PERSCINO` ,
								`PERSCORR` ,
								`PERSFIRMA` ,
								`PERSSUIS` ,
								`PERSENCO` ,
								`PERSEST`
								)
								VALUES (
								'".$id_persona."',
								'".$nombre.$s.$apellido."',
								'',
								'',
								'".$usuario."',
								'0',
								'0',
								'',
								'',
								'".$email."',
								'blanco.JPG',
								'',
								'0',
								'0'
								)";
    $RsPersona_Nueva = mysqli_query($conexion,$query_RsPersona_Nueva) or die(mysqli_error($conexion));

//Ingresar el registro de Usuario

		$query_RsUsuario_Nuevo ="INSERT INTO  `USUARIOS` (
											`USUALOG` ,
											 USUAPASS,
											`USUAPASPO`,
											`USUAROL` ,
											`USUAPASSBK` ,
											`USUAPASPOBK`
											)
											VALUES (
											'".$usuario."',
											AES_ENCRYPT( '".$contrasena2."', 'mc$90ui1' ),
											'',
											'".$rol."',
											'".$contrasena2."',
											'".$contrasena2."'
											)";
    $RsUsuario_Nuevo = mysqli_query($conexion,$query_RsUsuario_Nuevo) or die(mysqli_error($conexion));

	$redireccionar = "location: home.php?page=listar_usuarios";
    header($redireccionar);

}

if($tipoGuardar=='eliminar_archivo'){

 $query_RsEliminar="DELETE FROM requerimientosarch WHERE REARCODI = '".$_GET['codigo_arch']."'";							
 $RsEliminar = mysqli_query($conexion,$query_RsEliminar) or die(mysqli_error($conexion));
 
 echo('1');

}

if($tipoGuardar=='Guardarmensprovasig'){
$query_RsRequerimientosDetalle = " UPDATE detalle_requ SET DEREMPAS = '".$_GET['msj']."'
										WHERE DERECONS = '".$_GET['codigo_detalle']."'
									  ";
	$RsRequerimientosDetalle       = mysqli_query($conexion,$query_RsRequerimientosDetalle) or die(mysqli_error());
	//echo($query_RsRequerimientosDetalle);
	echo(mysqli_affected_rows($conexion));	
}

if($tipoGuardar=='det_vol_cot'){
$query_RsRequerimientosDetalle = " UPDATE detalle_requ SET DEREAPRO = ''
										WHERE DERECONS = '".$_GET['codigo_detalle']."'
									  ";
	$RsRequerimientosDetalle       = mysqli_query($conexion,$query_RsRequerimientosDetalle) or die(mysqli_error());
	//echo($query_RsRequerimientosDetalle);
	echo(mysqli_affected_rows($conexion));	
}

if($tipoGuardar=='GenerarEncuesta'){
	$query_RsGenerarEncuesta = "SELECT R.REQUIDUS PERSONA, R.REQUENCU ENCUESTA FROM requerimientos R WHERE R.REQUCODI = '".$_GET['requerimiento']."'";
	$RsGenerarEncuesta = mysqli_query($conexion,$query_RsGenerarEncuesta) or die(mysqli_error($conexion));
	$row_RsGenerarEncuesta = mysqli_fetch_assoc($RsGenerarEncuesta);
	$totalRows_RsGenerarEncuesta = mysqli_num_rows($RsGenerarEncuesta);
	$afectado= 0;
	if($totalRows_RsGenerarEncuesta>0){
		if($row_RsGenerarEncuesta['ENCUESTA'] == 0){
			$query_RsGenerarEncuesta = "INSERT INTO encuesta_pers 
													     (
														  ENPECODI,
														  ENPEPERS,
														  ENPEENCU,
														  ENPEROL,
														  ENPEREQU
														 )
													values(
													       NULL,
														   '".$row_RsGenerarEncuesta['PERSONA']."',
														   '1',
														   '4',
														   '".$_GET['requerimiento']."'
														  )";
			$RsGenerarEncuesta = mysqli_query($conexion,$query_RsGenerarEncuesta) or die(mysqli_error($conexion));		
			//$afectado = mysqli_affected_rows($conexion);
			$afectado = 1;
				$query_RsUltInsert = "SELECT LAST_INSERT_ID() DATO";
				$RsUltInsert = mysqli_query($conexion,$query_RsUltInsert) or die(mysqli_error($conexion));
				$row_RsUltInsert = mysqli_fetch_array($RsUltInsert);
				$ultima_encuesta = $row_RsUltInsert['DATO'];
			
			$query_RsUpdateRequerimiento = "UPDATE requerimientos set REQUENCU = '".$ultima_encuesta."' WHERE REQUCODI = '".$_GET['requerimiento']."'";
			$RsUpdateRequerimiento = mysqli_query($conexion,$query_RsUpdateRequerimiento) or die(mysqli_error($conexion));
		}
	}
	echo($afectado);
}	
if($tipoGuardar=='marcarentregado'){
	$response = array();
	$color = '';
	$query_RsDetallesCompra = "SELECT E.ESDECOLO COLOR FROM estado_detalle E where E.ESDECODI = '7'";
	$RsDetallesCompra = mysqli_query($conexion,$query_RsDetallesCompra) or die(mysqli_error($conexion));
	$row_RsDetallesCompra = mysqli_fetch_assoc($RsDetallesCompra);
	$totalRows_RsDetallesCompra = mysqli_num_rows($RsDetallesCompra);
	if($totalRows_RsDetallesCompra>0){
		$color = $row_RsDetallesCompra['COLOR'];
	}
    $query_RsConsultaCorreo = "SELECT DERECOOC COMPRA,
       DEREPRRE ID_PROVE,
       DEREIOCC CONVENIO,
       DERECONV ID_CONV_PRO      

      FROM `detalle_requ` 
	  WHERE `DERECONS`='".$_GET['codigo_e']."'";
	$RsConsultaCorreo = mysqli_query($conexion,$query_RsConsultaCorreo) or die(mysqli_error($conexion));
	$row_RsConsultaCorreo = mysqli_fetch_assoc($RsConsultaCorreo);
	$totalRows_RsConsultaCorreo = mysqli_num_rows($RsConsultaCorreo);
	
	$compra_normal = $row_RsConsultaCorreo['COMPRA'];
	$convenio_corr = $row_RsConsultaCorreo['CONVENIO'];
	$convenio_id = $row_RsConsultaCorreo['ID_CONV_PRO'];
	$proveedor_id= $row_RsConsultaCorreo['ID_PROVE'];
	
	
	if($totalRows_RsConsultaCorreo>0){
		
        
			if($convenio_corr != '' or $compra_normal > 0 ){				
				$query_RsCorreo_proveedor= "SELECT `PERSID`,
												   `PERSNOMB` NOMBRE,
												   `PERSAPEL`,
												   `PERSDIRE`,
												   `PERSTELE`,
												   `PERSUSUA`,
												   `PERSREGI`,
												   `PERSTARI`,
												   `PERSCIUD`,
												   `PERSCINO`,
												   `PERSCORR` CORREO,
												   `PERSFIRMA`,
												   `PERSSUIS`,
												   `PERSENCO`,
												   `PERSEST`,
												   `PERSPERE`,
													DERECONS,
													DEREDESC DESCRIPCION,
													DERECANT CANTIDAD

											FROM `personas` P,
											detalle_requ DR,
											REQUERIMIENTOS R

											WHERE DEREREQU=REQUCODI
											AND DERECONS='".$_GET['codigo_e']."'
											AND REQUIDUS=PERSID";
							$RsCorreo_proveedor = mysqli_query($conexion,$query_RsCorreo_proveedor) or die(mysqli_error($conexion));
							$row_RsCorreo_proveedor = mysqli_fetch_array($RsCorreo_proveedor);
							$totalRows_RsCorreo_proveedor = mysqli_num_rows($RsCorreo_proveedor);

                if($totalRows_RsCorreo_proveedor>0){
				    $nombre_completo	= $row_RsCorreo_proveedor['NOMBRE']; //"Nombre a quien va dirigido"
				    //$nombre_completo	= 'DIEGO YAMID CRUZ'; //"Nombre a quien va dirigido"
					$asunto				="Nuevo Entrega - Compras San Bonifacio";// lo que se desae en asunto
			        $dirigido 			= $row_RsCorreo_proveedor['CORREO'];//Correo a quien se va a enviar
					//$dirigido 			= 'dyamid@gmail.com';//Correo a quien se va a enviar
					$imagen_cabecera	='http://190.107.23.165/compras/imagenes/9.png';//imagen de cabecera
					$RutaArchivoAdjunto =''; //ruta del archivo adjunto
					$tema               ='<p>&#33;Enhorabuena&#161; has recibido, '.$row_RsCorreo_proveedor['CANTIDAD'].' - '.$row_RsCorreo_proveedor['DESCRIPCION'].' por parte del departamento de compras 
					                       del colegio san bonifacio de las lanzas. 
										  
										  </p>';
			
					$tema = $tema.'     	<p>											
											
											<h2>Favor Ingresar  al siguiente link:</h2>
							</font>
										
											
											
											';//el tema del correo
					$link				='http://compras.sanboni.edu.co/';
					$nombre_link		='RECIBIR';
					
				require_once('plantilla_correo.php');	
				//!$mail->send()
					if(!$mail->send()) 
					{
						echo "no enviado " . $mail->ErrorInfo;
					} else {
						//echo "exito!";
							}
		   }							
			}
			
		
	}
	
	$Ncotiza=$_GET['ncot'];
	if($Ncotiza==1){
		
	 $query_RsNcotiz = "UPDATE detalle_requ 
										SET  DEREFERE = sysdate() 
										WHERE `detalle_requ`.`DERECONS` = '".$_GET['codigo_e']."'
									  ";
	$RsNcotiz       = mysqli_query($conexion,$query_RsNcotiz) or die(mysqli_error($conexion));
	}
	
    $query_RsEntregado = "UPDATE detalle_requ 
										SET `DEREAPRO` = '7',
										DEREFEMU = sysdate()
										WHERE `detalle_requ`.`DERECONS` = '".$_GET['codigo_e']."'
									  ";
	$RsEntregado       = mysqli_query($conexion,$query_RsEntregado) or die(mysqli_error($conexion));
    $afectado = 0;
	$afectado = mysqli_affected_rows($conexion);
	
	
	
	$response[] = array(
						'afectado'   => $afectado,
						'background' => $color,
	                   );
	echo(json_encode($response));
}
if($tipoGuardar=='marcarRecibidousuariog'){
	$response = array();
	$color = '';
	$query_RsDetallesCompra = "SELECT E.ESDECOLO COLOR FROM estado_detalle E where E.ESDECODI = '8'";
	$RsDetallesCompra = mysqli_query($conexion,$query_RsDetallesCompra) or die(mysqli_error($conexion));
	$row_RsDetallesCompra = mysqli_fetch_assoc($RsDetallesCompra);
	$totalRows_RsDetallesCompra = mysqli_num_rows($RsDetallesCompra);
	if($totalRows_RsDetallesCompra>0){
		$color = $row_RsDetallesCompra['COLOR'];
	}
		
    $query_RsEntregado = "UPDATE detalle_requ 
										SET `DEREAPRO` = '8' ,
										     DEREFRSO  = sysdate()
										WHERE `detalle_requ`.`DERECONS` = '".$_GET['codigo_e']."'
									  ";
	$RsEntregado       = mysqli_query($conexion,$query_RsEntregado) or die(mysqli_error());
	$afectado = 0;
	$afectado = mysqli_affected_rows($conexion);
	
	//echo(mysqli_affected_rows($conexion));
	$response[] = array(
						'afectado'   => $afectado,
						'background' => $color,
	                   );
	echo(json_encode($response));				   
}

if($tipoGuardar=='firmarOrden'){

	$codigo_orden=$_GET['cod_orden'];
	$codigo_prov=$_GET['cod_prov'];
	$codigo_pers=$_GET['persona'];
    $ip=$_SERVER['REMOTE_ADDR'];
    //generar codigo unico
	$caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890-"; //posibles caracteres a usar
		$numerodeletras=15; //numero de letras para generar el texto
		$cadena = ""; //variable para almacenar la cadena generada
			for($i=0;$i<$numerodeletras;$i++)
			{
				$cadena .= substr($caracteres,rand(0,strlen($caracteres)),1); /*Extraemos 1 caracter de los caracteres 
			   entre el rango 0 a Numero de letras que tiene la cadena */
			}	
		$cadena=$cadena.'_'.$codigo_orden;
		
		
    //inserta la firma 
		$query_RsInsertarFirma="INSERT INTO `firmas`
												(`FIRMCONS`,
												 `FIRMCODI`,												 
												 `FIRMFECH`,
												 `FIRMPERS`,
												  FIRMDIIP
												  
												) 
												VALUES 
												(
												NULL,
												'".$cadena."',												
												SYSDATE(),
												'".$codigo_pers."',
												'".$ip."'
												)";
 			//echo($query_RsInsertarFirma);		
    $RsInsertarFirma = mysqli_query($conexion,$query_RsInsertarFirma) or die(mysqli_error($conexion));

	//obtener id de la firma 
	$query_RsUltInsert = "SELECT LAST_INSERT_ID() DATO";
	$RsUltInsert = mysqli_query($conexion,$query_RsUltInsert) or die(mysqli_error($conexion));
	$row_RsUltInsert = mysqli_fetch_array($RsUltInsert);
	$ultima_firma = $row_RsUltInsert['DATO'];

	/*
	//Consultar firma
	$query_RsConsultarFirma = "SELECT FIRMCODI FROM FIRMAS WHERE FIRMCONS='".$ultima_firma."'";
	$RsConsultarFirma = mysqli_query($conexion,$query_RsConsultarFirma) or die(mysqli_error($conexion));
	$row_RsConsultarFirma = mysqli_fetch_array($RsConsultarFirma);
	$codigo_firma = $row_RsConsultarFirma['FIRMCODI'];
	*/
	
		
    if($ultima_firma != ''){
		echo($ultima_firma);
	}else{
		echo(0);
	}	
	
		
}

if($tipoGuardar=='md5_orden'){
	
	$codigo_orden=$_GET['cod_orden'];
	$nomb_arch=$_GET['nomb_arch'];
	$f=$_GET['f'];
	
	$msj="";
	$codigo_unico_firma = '';
	
	$nomb_arch=$nomb_arch.'.pdf';
  	$query_RsParametroRuta = "SELECT PARAVALOR FROM PARAMETROS WHERE PARANOMB = 'RUTAARCHIVO_NAS'";
	$RsParametroRuta = mysqli_query($conexion,$query_RsParametroRuta) or die(mysqli_error($conexion));
	$row_RsParametroRuta = mysqli_fetch_array($RsParametroRuta);

	$rutaArchivos = $row_RsParametroRuta['PARAVALOR'];
	$carpeta = 'archivos_compras/ORDENES/';
	$rutaArchivos = $row_RsParametroRuta['PARAVALOR'].$carpeta;
	
	$query_RsConsulta = "SELECT `FIRMCODI` CODIGO_UNICO,
								 FIRMCONS  CONSECUTIVO
						 FROM `firmas` 
						 WHERE FIRMCONS='".$f."'
						 ";
	$RsConsulta = mysqli_query($conexion,$query_RsConsulta) or die(mysqli_error($conexion));
	$row_RsConsulta = mysqli_fetch_array($RsConsulta);
	$totalRows_RsConsulta = mysqli_num_rows($RsConsulta);
	
	
	$codigo			   =$row_RsConsulta['CONSECUTIVO'];
	

	if (file_exists($rutaArchivos.$nomb_arch)) 
    { 
		$query_RsUpdateFirmaCompra = "UPDATE `firmas` 
									  SET `FIRMDOCU` = '".$nomb_arch."',
									       FIRMCMD5  = '' 
								    WHERE `FIRMCONS`  = '".$codigo."'
									";
		$RsUpdateFirmaCompra       = mysqli_query($conexion,$query_RsUpdateFirmaCompra) or die(mysqli_error());
		
		//relacionar firma con orden de compra
		$query_RsUpdateFirmaCompra = "UPDATE `orden_compra` SET `ORCOFIRM` = '".$codigo."' WHERE `ORCOCONS`  = '".$codigo_orden."'";
		$RsUpdateFirmaCompra       = mysqli_query($conexion,$query_RsUpdateFirmaCompra) or die(mysqli_error());
		   
	   $codigo_unico_firma=$row_RsConsulta['CODIGO_UNICO'];
	   
	$query_RsDetallesOrden = "SELECT D.ORCDDETA DETALLE
							   FROM orden_compradet D
							  WHERE D.ORCDORCO = '".$codigo_orden."'";
	$RsDetallesOrden = mysqli_query($conexion,$query_RsDetallesOrden) or die(mysqli_error($conexion));
	$row_RsDetallesOrden = mysqli_fetch_array($RsDetallesOrden);
	$totalRows_RsDetallesOrden = mysqli_num_rows($RsDetallesOrden);	 
	if($totalRows_RsDetallesOrden>0){
		do{
		$query_RsUpdateFirmaOrdenCompraRector = "UPDATE detalle_requ SET `DEREFOCR` = sysdate(), DEREAPRO=28 WHERE DERECONS  = '".$row_RsDetallesOrden['DETALLE']."'";
		$RsUpdateFirmaOrdenCompraRector       = mysqli_query($conexion,$query_RsUpdateFirmaOrdenCompraRector) or die(mysqli_error());
		}while($row_RsDetallesOrden = mysqli_fetch_array($RsDetallesOrden));
	}
	
	
	       }else{
	$msj='fichero no encontrado'.$nomb_arch;
	
	}
	
$response = array("codigo" => $codigo_unico_firma,
				  "msj"    => $msj,
				  );
echo(json_encode($response));				  

}

if($tipoGuardar=='ValidarEnvioManualCot'){
	
	$query_RsConsulta = "SELECT * 
	                     FROM `cotizacion` 
						 WHERE `COTIFORE`= 1
						 AND `COTICODI`='".$_GET['cot']."'
						 ";
	$RsConsulta = mysqli_query($conexion,$query_RsConsulta) or die(mysqli_error($conexion));
	$row_RsConsulta = mysqli_fetch_array($RsConsulta);
	$totalRows_RsConsulta = mysqli_num_rows($RsConsulta);
	
	if($totalRows_RsConsulta > 0){
		
		echo('1');
	}else{
		
		echo('0');
	}
	
	
	
}




if($tipoGuardar=='req_est_pendiente'){
	

	
	$query_RsUpdate = "UPDATE `requerimientos` SET `REQUESTA` = '19' WHERE `REQUCODI` = '".$_GET['req']."'";
			$RsUpdate = mysqli_query($conexion,$query_RsUpdate) or die(mysqli_error($conexion));	
			$afecado = mysqli_affected_rows($conexion);
			echo ($afecado);
		
}

if($tipoGuardar=='devol_admitido'){
	
	$query_RsUpdate = "UPDATE `requerimientos` SET `REQUESTA` = '5' WHERE `REQUCODI` = '".$_GET['req']."'";
			$RsUpdate = mysqli_query($conexion,$query_RsUpdate) or die(mysqli_error($conexion));	
			$afecado = mysqli_affected_rows($conexion);
			echo ($afecado);
		
}


if($tipoGuardar=='req_est_firmaAdm'){
	

	
	$query_RsUpdate = "UPDATE `requerimientos` SET `REQUESTA` = '11' WHERE `REQUCODI` = '".$_GET['req']."'";
			$RsUpdate = mysqli_query($conexion,$query_RsUpdate) or die(mysqli_error($conexion));	
			$afecado = mysqli_affected_rows($conexion);
			echo ($afecado);
		
}


if($tipoGuardar=='req_est_firmaRect'){
	

	
	$query_RsUpdate = "UPDATE `requerimientos` SET `REQUESTA` = '12' WHERE `REQUCODI` = '".$_GET['req']."'";
			$RsUpdate = mysqli_query($conexion,$query_RsUpdate) or die(mysqli_error($conexion));	
			$afecado = mysqli_affected_rows($conexion);
			echo ($afecado);
		
}


if($tipoGuardar=='req_est_Cancelado'){
	
	$query_RsCantidadDetalles = "SELECT count(derecons) TOTAL_DETALLES
						 FROM `detalle_requ` 
						 where dererequ='".$_GET['req']."'
							";
	$RsCantidadDetalles = mysqli_query($conexion,$query_RsCantidadDetalles) or die(mysqli_error($conexion));
	$row_RsCantidadDetalles = mysqli_fetch_array($RsCantidadDetalles);
	$totalRows_RsCantidadDetalles = mysqli_num_rows($RsCantidadDetalles);
	
	$total_detalles=$row_RsCantidadDetalles['TOTAL_DETALLES'];
	
	
	$query_RsConsulta = "SELECT DERECONS DETALLES
						  FROM DETALLE_REQU
						  WHERE DEREREQU='".$_GET['req']."' 
						  AND DEREAPRO=3 
							";
	$RsConsulta = mysqli_query($conexion,$query_RsConsulta) or die(mysqli_error($conexion));
	$row_RsConsulta = mysqli_fetch_array($RsConsulta);
	$totalRows_RsConsulta = mysqli_num_rows($RsConsulta);
	
	$total_detalles_cancelados=$totalRows_RsConsulta;
	

	if($total_detalles == $total_detalles_cancelados){
		$query_RsUpdate = "UPDATE `requerimientos` SET `REQUESTA` = '7' WHERE `REQUCODI` = '".$_GET['req']."'";
			$RsUpdate = mysqli_query($conexion,$query_RsUpdate) or die(mysqli_error($conexion));	
			$afecado = mysqli_affected_rows($conexion);
			
			echo ('1');
	}else{
		
		echo ('0');
	}
			
		
}




if($tipoGuardar=='ConsultarCategoriasProveedor'){
	
	$proveedor =$_GET['proveedor'];
	$categoria  =$_GET['categoria'];
	
	$query_RsCategProv="SELECT P.PRCLCODI CODIGO,
							   P.PRCLPROV PROVEEDOR, 
							   P.PRCLCLAS CLASIFICACION,
							   C.CLASNOMB CLASIFICACION_DES
						FROM   PROVEEDOR_CLASIFICACION P,
                               CLASIFICACION C						
					    WHERE P.PRCLPROV = '".$proveedor."'
						 AND  C.CLASCODI = '".$categoria."'
						  AND P.PRCLCLAS = C.CLASCODI";
				 //echo($query_RsCategProv);echo("<br>");
	$RsCategProv = mysqli_query($conexion,$query_RsCategProv) or die(mysqli_error($conexion));
	$row_RsCategProv = mysqli_fetch_assoc($RsCategProv);
    $totalRows_RsCategProv = mysqli_num_rows($RsCategProv); 

	if($totalRows_RsCategProv > 0){
	    echo('1');
	}else{
		echo('0');	}
		
}	


if($tipoGuardar=='ConsultarDataOrdenConvenio'){
   $query_RsDetalleCompra="	SELECT DR.DERECONS CODIGO_DETALLE,
								   R.REQUCORE  REQUERIMIENTO,
								   DR.DERECONV ID_CONV_PROD,
								   CP.COPRIDPC ID_PRODUCTO,
								   CP.COPRIDCO CONVENIO,
								   DR.DERETIPO TIPO_ORDEN,
								   DR.DERECANT CANTIDAD,
								   DR.DEREDESC DESCRIPCION_DETALLE,
								   DR.DEREUNME UM,
								   (SELECT U.UNMESIGL FROM UNIDAD_MEDIDA U WHERE U.UNMECONS = DR.DEREUNME) UM_DES,
								   PR.PRODDESC PRODUCTO_DESC,
								   CP.COPRPREC PRECIO_CONVENIDO,
								   CP.COPRCANT CANTIDAD_CONVENIDA,
								   C.CONVIDPR  ID_PROVEEDOR,
								   PV.PROVNOMB PROVEEDOR_DES,
								   CP.COPRPREC PRECIO_UNITARIO,
								   (CP.COPRPREC/DR.DERECANT) VALOR_TOTAL,
								   CP.COPRID   ID_CONVENIO_PRODUCTO

							FROM DETALLE_REQU DR,
								 REQUERIMIENTOS R,
								 CONVE_PRODUC CP,
								 PRODUCTOS PR,
								 CONVENIOS C,
								 PROVEEDORES PV

							WHERE DR.DEREAPRO=12
							AND  PV.PROVCODI='".$_GET['provasigdet']."'
							AND DR.DEREREQU=R.REQUCODI
							AND DR.DERECONV=CP.COPRID
							AND PR.PRODCONS=CP.COPRIDPC
							AND C.CONVCONS=CP.COPRIDCO
							AND PV.PROVCODI=C.CONVIDPR
				";
				//echo ($query_RsDetalleCompra);
	$RsDetalleCompra = mysqli_query($conexion,$query_RsDetalleCompra) or die(mysqli_error($conexion));
	$row_RsDetalleCompra = mysqli_fetch_assoc($RsDetalleCompra);
    $totalRows_RsDetalleCompra = mysqli_num_rows($RsDetalleCompra);
	//$response = array();
	$datos = array();
	$valores = array();
	$convenio ='';
	$convenio2 ='';
	$valorestipotrabajo  = array();
	$valorestiposervicio = array();
	$valorestipoproducto = array();
	if($totalRows_RsDetalleCompra>0){
		do{
	       $datos[] = array('CODIGO_PROV'       => $row_RsDetalleCompra['ID_PROVEEDOR'],
							'NOMBRE_PROVEEDOR'  => $row_RsDetalleCompra['PROVEEDOR_DES'],
							'CANTIDAD'          => $row_RsDetalleCompra['CANTIDAD'],
							'CANTIDAD_C'          => $row_RsDetalleCompra['CANTIDAD_CONVENIDA'],
							'VALOR_TOTAL'       => "$".number_format($row_RsDetalleCompra['PRECIO_CONVENIDO'],1,'.',','),							
							'DESCR_DET'         => $row_RsDetalleCompra['DESCRIPCION_DETALLE'],
							
							'DESCR_PROV'        => $row_RsDetalleCompra['PRODUCTO_DESC'],
							'PRECIO_UN'         => "$".number_format($row_RsDetalleCompra['PRECIO_UNITARIO'],1,'.',','),
							'PRECIO_UNREAL'     => $row_RsDetalleCompra['PRECIO_UNITARIO'],
							'PROVEEDOR'         => $row_RsDetalleCompra['ID_PROVEEDOR'],
							'REQUERIMIENTO'     => $row_RsDetalleCompra['REQUERIMIENTO'],
							'COD_DETALLE'       => $row_RsDetalleCompra['CODIGO_DETALLE'],	
                            'CONVE_PRODUC'		=> $row_RsDetalleCompra['ID_CONVENIO_PRODUCTO'],						
                            'UM'		        => $row_RsDetalleCompra['UM'],						
                            'UM_DES'            => $row_RsDetalleCompra['UM_DES'],						
							'DEVOLVER'          => 0,
							'TIPO_ORD'          => "4",//$row_RsDetalleCompra['TIPO_ORDEN'],
							'TIPO_ORD_DES'      => "CONVENIO",//$row_RsDetalleCompra['TIPO_ORDEN_DES'],
							'COTIZACION'         => "",//$row_RsDetalleCompra['COTIZACION'],
						   );	
		   $valores[] = $row_RsDetalleCompra['VALOR_TOTAL'];
		   //$convenio=$row_RsDetalleCompra['ID_CONVENIO_PRODUCTO'];
		   $convenio2=$row_RsDetalleCompra['CONVENIO'];
		   if($row_RsDetalleCompra['TIPO_ORDEN'] == 1){
			   $valorestipoproducto[] = $row_RsDetalleCompra['VALOR_TOTAL'];
		   }
		   if($row_RsDetalleCompra['TIPO_ORDEN'] == 2){
			   $valorestiposervicio[] = $row_RsDetalleCompra['VALOR_TOTAL'];
		   }
		   if($row_RsDetalleCompra['TIPO_ORDEN'] == 3){
			   $valorestipotrabajo[] = $row_RsDetalleCompra['VALOR_TOTAL'];
		   }
		}while($row_RsDetalleCompra = mysqli_fetch_array($RsDetalleCompra));
	}
	$response = array('datos'  => $datos,
					  'sum'    => "$".number_format(array_sum($valores),0,'.',','),
					  'sumpr'  => "$".number_format(array_sum($valorestipoproducto),0,'.',','),
					  'sumser' => "$".number_format(array_sum($valorestiposervicio),0,'.',','),
					  'convenio2'=>$convenio2,
					  'sumtra' => "$".number_format(array_sum($valorestipotrabajo),0,'.',','),
					  );
echo(json_encode($response));
}

if($tipoGuardar=='OrdenarcompraConvenio'){
	//tipo_orden
	if(isset($_POST['json']) && $_POST['json']!=''){
		//$tojson = $_POST['json'];
		$tojson = json_decode($_POST['json']);
	/*	$nuevo=array();
		$arreglo = get_object_vars( $tojson ); 
		//print_r($arreglo);
		foreach( $arreglo as $indice=>$valor ) 
        {
			 $nuevo[]=$valor;
		}	
*/		
//		echo($tojson->convenio);
	
	$query_RsInsertarOrden="INSERT INTO orden_compra_convenio (
												 `ORCOCONS`,
												 `ORCOFECH`,
												 `ORCOIDCO`,
												 `ORCOFEEN`,
												 `ORCOOBSE`,
												  ORCODIVA
												  
												 ) 
												 VALUES (
														 NULL,
														 SYSDATE(), 
														 '".$tojson->convenio2."', 
														 str_to_date('".$_GET['fecha_entrega']."','%d/%m/%Y'), 
														 '".$_GET['observacion']."',
														 '".$_GET['iva_desc']."'
														 
														 )";
 			//echo($query_RsInsertarOrden);		
    $RsInsertarOrden = mysqli_query($conexion,$query_RsInsertarOrden) or die(mysqli_error($conexion));

	$query_RsUltInsert = "SELECT LAST_INSERT_ID() DATO";
	$RsUltInsert = mysqli_query($conexion,$query_RsUltInsert) or die(mysqli_error($conexion));
	$row_RsUltInsert = mysqli_fetch_array($RsUltInsert);
	$codigo_orden_compra = $row_RsUltInsert['DATO'];	

	if(is_array($tojson->datos)){
		for($i=0; $i<count($tojson->datos); $i++){
			if($tojson->datos[$i]->DEVOLVER == 0 && $tojson->datos[$i]->TIPO_ORD == $_GET['tipo_orden']){
				
				 $query_RsInsertarOrdenDet = "INSERT INTO orden_compconv_detalle(
																		  ORCDCODI,
																		  ORCDORCC,
																		  ORCDDETA,
																		  ORCDCONV
																		 )
																		 VALUES
																		 (
																		 NULL,
																		 '".$codigo_orden_compra."',
																		 '".$tojson->datos[$i]->COD_DETALLE."',
																		 '".$tojson->datos[$i]->CONVE_PRODUC."'
																		 )
																		 ";
																		 //echo($query_RsInsertarOrdenDet."<br>");
				$RsInsertarOrdenDet = mysqli_query($conexion,$query_RsInsertarOrdenDet) or die(mysqli_error($conexion));
				
				$query_RsUpdateOrdenCompraDet = "UPDATE detalle_requ SET DEREAPRO = '19',
				                                                         DEREIOCC = '".$codigo_orden_compra."'
												 WHERE DERECONS = '".$tojson->datos[$i]->COD_DETALLE."'
												 ";
				$RsUpdateOrdenCompraDet = mysqli_query($conexion,$query_RsUpdateOrdenCompraDet) or die(mysqli_error($conexion));
				
		    }
			/*
			if($tojson->datos[$i]->DEVOLVER == 1 && $tojson->datos[$i]->TIPO_ORD == $_GET['tipo_orden']){
				$query_RsDatosDetalle = "SELECT * from detalle_requ where DERECONS ='".$tojson->datos[$i]->COD_DETALLE."'";
				$RsDatosDetalle = mysqli_query($conexion,$query_RsDatosDetalle) or die(mysqli_error($conexion));
				$row_RsDatosDetalle = mysqli_fetch_array($RsDatosDetalle);
				$totalRows_RsDatosDetalle = mysqli_num_rows($RsDatosDetalle);				
				if($totalRows_RsDatosDetalle>0){
					$veces = $row_RsDatosDetalle['DERECARE']+1;
					$query_RsUpdateOrdenCompraDet = "UPDATE detalle_requ SET DEREAPRO = '10',
																			 DEREPROV = '',
					                                                         DEREPROV2 = '".$row_RsDatosDetalle['DEREPROV']."',
																			 DERECARE  = '".$veces."'
													 WHERE DERECONS = '".$tojson->datos[$i]->COD_DETALLE."'
													 ";
					$RsUpdateOrdenCompraDet = mysqli_query($conexion,$query_RsUpdateOrdenCompraDet) or die(mysqli_error($conexion));
					$query_RsInsertDetalleRecotizado = "INSERT INTO detalle_recotizado
					                                           (
															   DERECODI,
															   DEREDETA,
															   DERECOTI,
															   DEREPROV,
															   DEREVAUN
															   )
															   VALUES(
															   NULL,
															   '".$tojson->datos[$i]->COD_DETALLE."',
															   '".$tojson->datos[$i]->COTIZACION."',
															   '".$tojson->datos[$i]->PROVEEDOR."',
															   '".$tojson->datos[$i]->PRECIO_UNREAL."'
															   )
					
					";
					$RsInsertDetalleRecotizado = mysqli_query($conexion,$query_RsInsertDetalleRecotizado) or die(mysqli_error($conexion));					
				}
			}
			*/
		}
	}
		
	}
	echo($codigo_orden_compra);
}



if($tipoGuardar=='Enviar_correo_Orden_conv'){
	
	$proveedor 			=$_GET['prov'];
	$codigo_o  			=$_GET['cod_orden_conv'];
	$nombre_archivo		=$_GET['nomb_archivo'];
	$response_mail		="";
	$msj				="";
	
	$query_RsParametroRuta = "SELECT PARAVALOR FROM PARAMETROS WHERE PARANOMB = 'RUTAARCHIVO_NAS'";
	$RsParametroRuta = mysqli_query($conexion,$query_RsParametroRuta) or die(mysqli_error($conexion));
	$row_RsParametroRuta = mysqli_fetch_array($RsParametroRuta);

	$rutaArchivos = $row_RsParametroRuta['PARAVALOR'];
	$carpeta = 'archivos_compras/ORDENES_CONVENIO/';
	$rutaArchivos = $row_RsParametroRuta['PARAVALOR'].$carpeta.$nombre_archivo.'.pdf';
	
		
	$query_Rsproveedor = "SELECT  `PROVNOMB` NOMBRE,
								  `PROVCORR` CORREO
							 FROM `proveedores` 
							WHERE PROVCODI= '".$proveedor."' ";
	$Rsproveedor = mysqli_query($conexion,$query_Rsproveedor) or die(mysqli_error($conexion));
	$row_Rsproveedor = mysqli_fetch_array($Rsproveedor);
	$totalRows_Rsproveedor = mysqli_num_rows($Rsproveedor);
	
	 if($totalRows_Rsproveedor>0){
				    $nombre_completo	= $row_Rsproveedor['NOMBRE']; //"Nombre a quien va dirigido"
					$asunto				="Orden de Convenio # CONV-".$codigo_o;// lo que se desae en asunto
				    $dirigido 			= $row_Rsproveedor['CORREO'];//Correo a quien se va a enviar
					//$dirigido			="dyamid@gmail.com";
					$imagen_cabecera	='http://compras.sanboni.edu.co/imagenes/9.png';//imagen de cabecera
					//$RutaArchivoAdjunto ='A:/wamp/www/SANBONIFACIO/compras/archivos_compras/ORDENES/O-2015-22.pdf';
					$RutaArchivoAdjunto =$rutaArchivos.'';
					$tema               ='Usted a sido seleccionado por la Corporaci&oacute;n  Colegio San Bonifacio, sea muy amablemente realizar la entrega
										  de la siguiente orden de CONVENIO que se encuentra adjunta, muchas gracias por su participación 
										  <br>
										  <br>
										  </p>';
			
					$tema = $tema.'     	<p>											
											<br>
											<br>
											<font color="#666666">
							
							<!--<h2 align="justify">Por seguridad las ordenes de compra que genera la Corporaci&oacute;n  Colegio San Bonifacio tienen contraseña se pueden verificar en el portal de compras  para abrir el documento.</h2>
							<br>
											<h2>Para verificar la firma del documento de clic al siguiente link:</h2>
							</font>-->
										
											
											
											';//el tema del correo
					$link				='';
					$nombre_link		='INFORMACION';
					
				require_once('plantilla_correo.php');	
				//!$mail->send()
					  if(!$mail->send()) 
					{
						echo "no enviado " . $mail->ErrorInfo;
						$response_mail="no enviado " . $mail->ErrorInfo;
					} else {
						$query_Rsupdate = "UPDATE orden_compra_convenio SET ORCOCORR = '1' WHERE ORCOCONS = ".$codigo_o."";
					         $Rsupdate = mysqli_query($conexion,$query_Rsupdate) or die(mysqli_error($conexion));
						
						
							$response_mail= "exito";
						
		   }
	
	
	
	if($response_mail == "exito"){
		
		echo('1');
	}else{
		
		echo($response_mail);
	}
	
	
	
}
}

if($tipoGuardar=='cargar_detalles_nocotiza'){
	
		$query_RsVerificarProveedorIgual = "SELECT O.ORCDCODI CODIGO,
	                                           O.ORCDORCO ORDEN,
											   O.ORCDDETA DETALLE,
											   O.ORCDPROV PROVEEDOR,
											   D.DEREDESC DETALLE_DES,
											   D.DERECANT CANTIDAD,
											   CD.CODEVALO VALOR_UNITARIO,
											   U.UNMESIGL MEDIDA,
											   (((CD.CODEVALO*CD.CODEVAIV)/100)) IVA,
											   CD.CODEVAIV PORC_IVA,
											   ((((CD.CODEVALO*CD.CODEVAIV)/100)+CD.CODEVALO)*D.DERECANT) VALOR_TOTAL,
											   '".$_GET['orden']."' ORDEN,
											   ifnull(O.ORCDFERE,'-1') FECHA_REC_PROV,
											   R.REQUCORE COD_REQU,
											   R.REQUCODI CONS_REQU
											   
	                                     FROM orden_compradet O,
										      orden_compra    OC,
										      detalle_requ    D,
											  unidad_medida   U,
											  requerimientos  R,
											  proveedores     P,
											  cotizacion      C,
											  cotizacion_detalle CD
									    WHERE O.ORCDORCO = OC.ORCOCONS
										  AND O.ORCDDETA = D.DERECONS
										  and D.DEREUNME = U.UNMECONS
										  and D.DEREREQU = R.REQUCODI
										  and O.ORCDORCO = '".$_GET['orden']."'
										  and OC.ORCOIDPR  = P.PROVCODI
										  AND D.DEREPROV  = C.COTIPROV
										  AND D.DERECONS  = CD.CODEDETA
										  and C.COTICODI  = CD.CODECOTI
										";
	$RsVerificarProveedorIgual = mysqli_query($conexion,$query_RsVerificarProveedorIgual) or die(mysqli_connect_error());
	$row_RsVerificarProveedorIgual = mysqli_fetch_assoc($RsVerificarProveedorIgual);
	$totalRows_RsVerificarProveedorIgual = mysqli_num_rows($RsVerificarProveedorIgual);
	$response = array();
	if($totalRows_RsVerificarProveedorIgual>0){
		do{
			$response[] =  $row_RsVerificarProveedorIgual;
		}while($row_RsVerificarProveedorIgual = mysqli_fetch_assoc($RsVerificarProveedorIgual));
	}
	echo(json_encode($response));
	
	}
	
if($tipoGuardar=='GuardarOrdenNoCotiza'){ 
	$afectado = 0;
	if(isset($_POST['json']) && $_POST['json']!=''){	
		$tojson = json_decode($_POST['json']);	
			//var_dump($tojson->estatico->p_d);
		  $query_RsProveedorNocotiza = "INSERT INTO `proveedores_nocotiza` (
													 `PROVCODI`,
													 `PROVREGI`,
													 `PROVNOMB`, 
													 `PROVTELE`,																			  
													 `PROVDIRE`,
													  PROVCORR
													) 
													VALUES (  
													NULL,
													'".$tojson->estatico->p_n."',
													'".$tojson->estatico->p_d."',
													'".$tojson->estatico->p_t."',																			  
													'".$tojson->estatico->p_dir."',																			  
													'".$tojson->estatico->p_e."'																			  
													)";
	//echo($query_RsInsertarOrden);		
    $RsProveedorNocotiza = mysqli_query($conexion,$query_RsProveedorNocotiza) or die(mysqli_error($conexion));

	$query_RsUltInsert = "SELECT LAST_INSERT_ID() DATO";
	$RsUltInsert = mysqli_query($conexion,$query_RsUltInsert) or die(mysqli_error($conexion));
	$row_RsUltInsert = mysqli_fetch_array($RsUltInsert);
	$codigo_proveedornocotiza = $row_RsUltInsert['DATO'];	
	
	 $query_RsOrdenNocotiza = "INSERT INTO `orden_compra_ncotiza` (
															`ORNCCONS`,
															 ORNCIDPN ,																		
															`ORNCFECH`,																		
															`ORNCFEEN`, 
															`ORNCOBSE`,
															 ORNCFLET																		
															) 
													VALUES (
															 NULL,
															 '".$codigo_proveedornocotiza."',
															 sysdate(),																		
															'".$tojson->estatico->p_fe."',
															'".$tojson->estatico->p_o."',
															'".$tojson->estatico->flete."'
															)
																		";
	//echo($query_RsInsertarOrden);		
    $RsOrdenNocotiza = mysqli_query($conexion,$query_RsOrdenNocotiza) or die(mysqli_error($conexion));
	
    $query_RsUltInsert = "SELECT LAST_INSERT_ID() DATO";
	$RsUltInsert = mysqli_query($conexion,$query_RsUltInsert) or die(mysqli_error($conexion));
	$row_RsUltInsert = mysqli_fetch_array($RsUltInsert);
	$codigo_OrdenCompraNocotiza = $row_RsUltInsert['DATO'];
  if (is_array($tojson->dinamico)){
	  for($i=0; $i<count($tojson->dinamico); $i++){
		  //exit($tojson->dinamico[$i]->det);
		 $query_RsOrdenDetalleNocotiza = "INSERT INTO `orden_compradet_nocotiza` (
																		   `OCDNCODI`, 
																		   `OCDNORCO`, 
																		   `OCDNDETA`,
																			OCDNDESC ,																		   
																		   `OCDNVAUN`,
																		    OCDNCANT,
																			OCDNPOIV,
																			OCDNVAIV
																		   ) 
																   VALUES (
																			NULL, 
																			'".$codigo_OrdenCompraNocotiza."', 
																			'".$tojson->dinamico[$i]->det."', 
																			'".$tojson->dinamico[$i]->desc."',
																			'".$tojson->dinamico[$i]->vunit."',
																			'".$tojson->dinamico[$i]->cant."',
																			'".$tojson->dinamico[$i]->porc_iva."',
																			'".$tojson->dinamico[$i]->valor_iva."'
																			)	
																			";
		//echo($query_RsInsertarOrden);		
		$RsOrdenDetalleNocotiza = mysqli_query($conexion,$query_RsOrdenDetalleNocotiza) or die(mysqli_error($conexion));
		
		 $query_RsupdateEstaDet = "UPDATE DETALLE_REQU SET DEREAPRO = '20' WHERE DERECONS = ".$tojson->dinamico[$i]->det."";
					         $RsupdateEstaDet = mysqli_query($conexion,$query_RsupdateEstaDet) or die(mysqli_error($conexion));
       }
	
	}																	 
			
			
			
			
		
		print_r($codigo_OrdenCompraNocotiza);
	}
	
}	

if($tipoGuardar == 'cargar_orden_nocotiza'){
	$query_RsVerificarProveedorIgual = "select OC.ORNCCONS CODIGO,
											   OD.OCDNORCO ORDEN,											   
											   OD.OCDNDETA DETALLE,											   
												P.PROVCODI PROVEEDOR,
												D.DEREDESC DETALLE_DES,
												D.DERECANT CANTIDAD,
												OD.OCDNVAUN VALOR_UNITARIO,
												U.UNMESIGL MEDIDA,
												(OD.OCDNVAUN*0.16) IVA,
												'16' PORC_IVA,
												(OD.OCDNVAUN*OD.OCDNCANT) VALOR_TOTAL,
												'".$_GET['orden']."' ORDEN,
												OD.OCDNCODI COD_DETORD,
												   ifnull(OD.OCDNFERE,'-1') FECHA_REC_PROV,
											   R.REQUCORE COD_REQU,
												   R.REQUCODI CONS_REQU
										  from  orden_compradet_nocotiza OD, 
												orden_compra_ncotiza   OC,
												proveedores_nocotiza   P,
												detalle_requ           D,
												unidad_medida          U,
												requerimientos         R
										 where OC.ORNCCONS = '".$_GET['orden']."'
										   AND OD.OCDNORCO = OC.ORNCCONS										   
										   AND  OC.ORNCIDPN = P.PROVCODI
										   AND  OD.OCDNDETA = D.DERECONS
										   AND  D.DEREUNME = U.UNMECONS
										   and  D.DEREREQU = R.REQUCODI   
										   
										 order by D.DERECONS 
										";
	$RsVerificarProveedorIgual = mysqli_query($conexion,$query_RsVerificarProveedorIgual) or die(mysqli_connect_error());
	$row_RsVerificarProveedorIgual = mysqli_fetch_assoc($RsVerificarProveedorIgual);
	$totalRows_RsVerificarProveedorIgual = mysqli_num_rows($RsVerificarProveedorIgual);
	$response = array();
	if($totalRows_RsVerificarProveedorIgual>0){
		do{
			$response[] =  array('CODIGO'           =>  $row_RsVerificarProveedorIgual['CODIGO'],
			                     'ORDEN'            =>  $row_RsVerificarProveedorIgual['ORDEN'],
			                     'DETALLE'          =>  $row_RsVerificarProveedorIgual['DETALLE'],
			                     'PROVEEDOR'        =>  $row_RsVerificarProveedorIgual['PROVEEDOR'],
			                     'DETALLE_DES'      =>  $row_RsVerificarProveedorIgual['DETALLE_DES'],
			                     'CANTIDAD'         =>  $row_RsVerificarProveedorIgual['CANTIDAD'],
			                     'VALOR_UNITARIO'   =>  "$".number_format($row_RsVerificarProveedorIgual['VALOR_UNITARIO'],1,'.',','),
			                     'MEDIDA'           =>  $row_RsVerificarProveedorIgual['MEDIDA'],
			                     'IVA'              =>  "$".number_format($row_RsVerificarProveedorIgual['IVA'],1,'.',','),
			                     'PORC_IVA'         =>  $row_RsVerificarProveedorIgual['PORC_IVA'],
			                     'VALOR_TOTAL'      =>  "$".number_format($row_RsVerificarProveedorIgual['VALOR_TOTAL'],1,'.',','),
			                     'ORDEN'            =>  $row_RsVerificarProveedorIgual['ORDEN'],
			                     'COD_DETORD'       =>  $row_RsVerificarProveedorIgual['COD_DETORD'],
			                     'FECHA_REC_PROV'   =>  $row_RsVerificarProveedorIgual['FECHA_REC_PROV'],
			                     'COD_REQU'         =>  $row_RsVerificarProveedorIgual['COD_REQU'],
			                     'CONS_REQU'         =>  $row_RsVerificarProveedorIgual['CONS_REQU'],
			                    );
											
		}while($row_RsVerificarProveedorIgual = mysqli_fetch_assoc($RsVerificarProveedorIgual));
	}
	echo(json_encode($response));
	
}

	
if($tipoGuardar == 'RecibirProductosNocotiza'){
	if(isset($_POST['json']) && $_POST['json']!=''){
		$response = array();
		$afectado = 0;
		$changebutton = 0;
		$tojson = json_decode($_POST['json']);
		//print_r($tojson);
		for($i=0; $i<count($tojson); $i++){
			//echo($tojson[$i]->det.'_');
			$query_RsMarcarRecibido = "UPDATE detalle_requ set DEREFERE = SYSDATE(),
															   DEREAPRO = 20	
														 WHERE DERECONS = '".$tojson[$i]->det."'";
			$RsMarcarRecibido = mysqli_query($conexion,$query_RsMarcarRecibido) or die(mysqli_connect_error());
			
			$query_RsMarcarRecibido = "UPDATE orden_compradet_nocotiza set OCDNFERE = SYSDATE() WHERE OCDNCODI = '".$tojson[$i]->detorden."'";
			$RsMarcarRecibido = mysqli_query($conexion,$query_RsMarcarRecibido) or die(mysqli_connect_error());
		$afectado = $afectado+1;
		}
		if((count($tojson) == $_GET['sinrecibir'])  || $_GET['sinrecibir'] == 0){
			$query_RsTodoRecibido = "UPDATE orden_compra_ncotiza set ORNCTOEN = '1' WHERE ORNCCONS = '".$_GET['orden']."'";
			$RsTodoRecibido = mysqli_query($conexion,$query_RsTodoRecibido) or die(mysqli_connect_error());
			$changebutton = 1;
		}
		$response[] = array(
		                    'afectado'  => $afectado,
							'change'    => $changebutton,
							);
		echo(json_encode($response));
	}
}

if($tipoGuardar == 'correoordennocotiza'){
	$query_RsDatosCorreo = "SELECT P.PROVNOMB NOMBRE,
	                               P.PROVDIRE DIRECCION,
								   P.PROVCORR CORREO
							 FROM proveedores_nocotiza P
							where P.PROVCODI = '".$_GET['proveedor']."'
								   ";
	$RsDatosCorreo = mysqli_query($conexion,$query_RsDatosCorreo) or die(mysqli_connect_error());
	$row_RsDatosCorreo = mysqli_fetch_assoc($RsDatosCorreo);
	$totalRows_RsDatosCorreo = mysqli_num_rows($RsDatosCorreo);	
	
	$query_RsParametroRuta = "SELECT PARAVALOR FROM PARAMETROS WHERE PARANOMB = 'RUTAARCHIVO_NAS'";
	$RsParametroRuta = mysqli_query($conexion,$query_RsParametroRuta) or die(mysqli_error($conexion));
	$row_RsParametroRuta = mysqli_fetch_array($RsParametroRuta);

	$rutaArchivos = $row_RsParametroRuta['PARAVALOR'];
	$carpeta = 'archivos_compras/ORDENES_MENORCUANTIA/';
	$rutaArchivos = $row_RsParametroRuta['PARAVALOR'].$carpeta;	
	
	if($totalRows_RsDatosCorreo>0){
	$nombre_completo	= $row_RsDatosCorreo['NOMBRE'];
	$asunto				= "Orden de Compra NCOT-".$_GET['orden'];
	$dirigido 			= $row_RsDatosCorreo['CORREO'];;
	$imagen_cabecera	='http://compras.sanboni.edu.co/imagenes/9.png';
	$tema               = 'Usted a sido seleccionado por la Corporaci&oacute;n  Colegio San Bonifacio, sea 
							muy	amablemente	realizar la entrega de la siguiente orden  que se encuentra adjunta, muchas gracias por su participación 
										  ';
	$link				= '';
	$nombre_link		= '';	
	$RutaArchivoAdjunto = $rutaArchivos.'MC-2015-'.$_GET['orden'].'.pdf';
    
	include_once("plantilla_correo.php");	
	if(!$mail->send()) {
		echo 'El mensaje no pudo ser enviado.';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
		echo '1';
	}	
	}
}	

if($tipoGuardar == 'descargar_archivo_orden_convenio'){
	
	
	$codigo_orden=$_GET['cod_orden'];
	$nomb_arch=$_GET['nomb_arch'];
	$f=$_GET['f'];
	
	$msj="";

	$nomb_arch=$nomb_arch.'.pdf';

	$query_RsParametroRuta = "SELECT PARAVALOR FROM PARAMETROS WHERE PARANOMB = 'RUTAARCHIVO_NAS'";
	$RsParametroRuta = mysqli_query($conexion,$query_RsParametroRuta) or die(mysqli_error($conexion));
	$row_RsParametroRuta = mysqli_fetch_array($RsParametroRuta);

	$rutaArchivos = $row_RsParametroRuta['PARAVALOR'];
	$carpeta = 'archivos_compras/ORDENES_CONVENIO/';
	$rutaArchivos = $row_RsParametroRuta['PARAVALOR'].$carpeta;
	

	
	
	if (file_exists($rutaArchivos.$nomb_arch)) 
    { 	
	$msj='si';
	       }else{
	$msj='no';
	
	}
	
$response = array(
				  "msj"    => $msj,
				  );
echo(json_encode($response));				  


}

if($tipoGuardar == 'ActualizarCriterioPrecio'){
	//$p	=$_GET['proveedor'];
	$cd	=$_GET['detalle_cotizado'];
	$c	=$_GET['criterio'];
	$response=array();
	//echo($p);
	$query_RsCriterioPrecio= "UPDATE cotizacion_detalle SET CODEPREC = '".$c."' WHERE `cotizacion_detalle`.`CODECODI` = '".$cd."'";
	$RsCriterioPrecio= mysqli_query($conexion,$query_RsCriterioPrecio) or die(mysqli_connect_error());
	
	$response[] = array(
		                    //'proveedor'  		=> $p,
							'detalle_cot'    	=> $cd,
							'criterio'			=> $c
							);
	echo(json_encode($response));
}

if($tipoGuardar == 'ActualizarCriterioCaractProducto'){
	//$p	=$_GET['proveedor'];
	$cd	=$_GET['detalle_cotizado'];
	$c	=$_GET['criterio'];
	$response=array();
	//echo($p);
	$query_RsCriterioPrecio= "UPDATE cotizacion_detalle SET CODECAPR = '".$c."' WHERE `cotizacion_detalle`.`CODECODI` = '".$cd."'";
	$RsCriterioPrecio= mysqli_query($conexion,$query_RsCriterioPrecio) or die(mysqli_connect_error());
	
	$response[] = array(
		                    //'proveedor'  		=> $p,
							'detalle_cot'    	=> $cd,
							'criterio'			=> $c
							);
	echo(json_encode($response));
}

if($tipoGuardar == 'RecibeAuxiliarAdmin'){
	$detalle	=$_GET['det_req'];
	$query_RsUpdate= "UPDATE detalle_requ SET DEREAPRO = '8',
											  DEREFRSO=SYSDATE() 
										WHERE DERECONS = '".$detalle."'";
	$RsUpdate= mysqli_query($conexion,$query_RsUpdate) or die(mysqli_connect_error());
}


if($tipoGuardar == 'ValidarDetalles'){
	$cod_requerimiento = $_GET['codreq'];
	
	//consulta la cantidad de detalles que tiene el requerimiento
	$query_RsConsulta = "SELECT count(derecons) CANTIDAD_DET FROM detalle_requ where dererequ = '".$cod_requerimiento."'";
	$RsConsulta = mysqli_query($conexion,$query_RsConsulta) or die(mysqli_error($conexion));
	$row_RsConsulta = mysqli_fetch_array($RsConsulta);
	
	//consulta la cantidad de detalles que estan en estado devueltos
    $query_RsConsulta2 = "SELECT count(derecons)CANTIDAD_DEVUELTOS FROM detalle_requ where dererequ = '".$cod_requerimiento."' AND dereapro = '2' ";
	$RsConsulta2 = mysqli_query($conexion,$query_RsConsulta2) or die(mysqli_error($conexion));
	$row_RsConsulta2 = mysqli_fetch_array($RsConsulta2);
	
	// compara las cantidades
	if($row_RsConsulta['CANTIDAD_DET'] == $row_RsConsulta2['CANTIDAD_DEVUELTOS']){
		$resultado='1';
	}else{
		$resultado='0';
	}
	
	echo($resultado);
}
















//Seccion de condiciones de manipulacion de datos de detalle 

function getDetallesMultiplesSeleccionados($conexion){
	$arrayDetalles = [];
		if (isset($_POST['detallesSeleccionados'])) {
			$detalles = $_POST['detallesSeleccionados'];
			$detail_ids = [];
			foreach ($detalles as $detalle) {
				if (isset($detalle['detail'])) {
					$detail_ids[] = $detalle['detail'];
				}
			}
			if (count($detail_ids) > 0) {
				$detail_ids_string = implode(',', $detail_ids);			
				
				$arrayDetalles = explode(',', $detail_ids_string);
			}

		
	}	
	return $arrayDetalles;	
}

	//Marca el tipo de compra en cada detalle
		if($tipoGuardar=='GuardarTipoCompraMultiple'){
			$arrayDetalles	= [];
			$response = array("filasAfectadas"=> 0 ,"detallesLista" => $arrayDetalles);			

			$arrayDetalles = getDetallesMultiplesSeleccionados($conexion);
			if(count($arrayDetalles) > 0){
				$detail_ids_string = implode(',', $arrayDetalles);
				$query_RsRequerimientosDetalle = " UPDATE detalle_requ SET DERETIPO = '".$_GET['tipocompra']."'
				WHERE DERECONS in (". $detail_ids_string .") ";
				$RsRequerimientosDetalle       = mysqli_query($conexion,$query_RsRequerimientosDetalle) or die(mysqli_error());				
				$response = array("filasAfectadas"=>mysqli_affected_rows($conexion), "detallesLista"=>$arrayDetalles);
			}
			
			echo json_encode($response);
			
		}
		if($tipoGuardar=='GuardarTipoCompra'){
			
			$query_RsRequerimientosDetalle = " UPDATE detalle_requ SET DERETIPO = '".$_GET['tipocompra']."'
												WHERE DERECONS = '".$_GET['codigo_detalle']."'
											  ";
			$RsRequerimientosDetalle       = mysqli_query($conexion,$query_RsRequerimientosDetalle) or die(mysqli_error());
			//echo($query_RsRequerimientosDetalle);
			echo(mysqli_affected_rows($conexion));
		}
	//fin

	//Marca la clasificacion de compra en cada detalle
		if($tipoGuardar=='GuardarTipoClasCompra'){
			$tipo=$_GET['tipoclascomp'];
			$arrayDetalles	= [];
			$response = array("filasAfectadas"=> 0 ,"detallesLista" => $arrayDetalles);			

			$arrayDetalles = getDetallesMultiplesSeleccionados($conexion);
			if(count($arrayDetalles) == 0){
				if($_GET['codigo_detalle'] != 'multiple'){
					$arrayDetalles = [$_GET['codigo_detalle']];
				}

			}
			$detail_ids_string = implode(',', $arrayDetalles);
			
			switch ($tipo) {
				case '1':
					$estado='1';
					break;
				case '2':
					$estado='32';
					if(count($arrayDetalles) > 0){
					$query_RsCompraDirecta = " UPDATE detalle_requ SET DERENCOT = '1'
												WHERE DERECONS in (". $detail_ids_string .") ";
					$RsCompraDirecta       = mysqli_query($conexion,$query_RsCompraDirecta) or die(mysqli_error());
					}
					break;
				case '3':
					$estado='22';
					break;
				case '4':
					$estado='23';
					break;
				case '5':
					$estado='24';
					if(count($arrayDetalles) > 0){
					$query_RsCompraDirecta = " UPDATE detalle_requ SET DEREVIAT = '1'
												WHERE DERECONS in (". $detail_ids_string .") ";
					$RsCompraDirecta       = mysqli_query($conexion,$query_RsCompraDirecta) or die(mysqli_error());
					}
					break;
				case '6':
					$estado='29';
					if(count($arrayDetalles) > 0){
					$query_RsCompraDirecta = " UPDATE detalle_requ SET DEREELEC = '1'
												WHERE DERECONS  in (". $detail_ids_string .") ";
					$RsCompraDirecta       = mysqli_query($conexion,$query_RsCompraDirecta) or die(mysqli_error());
					}
					break;
				case '7':
					$estado='23';
					break;			
				case '8':
					$estado='1';
					break;					
				default:
					$estado='0';
					break;
			}
			if(count($arrayDetalles) > 0){
			$query_RsRequerimientosDetalle = " UPDATE detalle_requ SET DERECLAS = '".$_GET['tipoclascomp']."',
			                                                           DEREAPRO = '".$estado."'
												WHERE DERECONS in (". $detail_ids_string .") ";
			$RsRequerimientosDetalle       = mysqli_query($conexion,$query_RsRequerimientosDetalle) or die(mysqli_error());
			$response = array("filasAfectadas"=>mysqli_affected_rows($conexion), "detallesLista"=>$arrayDetalles);
			}

			
			//echo($query_RsRequerimientosDetalle);
			echo(json_encode($response));
			}
	//fin

	//Marca el Convenio y producto asociado a la compra en el detalle
		if($tipoGuardar=='GuardarConvProdDetalle'){		
			    
					$query_RsConvenio="UPDATE  detalle_requ 
												 SET DERECONV   = '".$_GET['conv_prod']."',
												     DEREFPCO   = SYSDATE()
											   WHERE DERECONS   = '".$_GET['coddet']."' 
											";
					$RsConvenio = mysqli_query($conexion,$query_RsConvenio) or die(mysqli_error($conexion));
				 
    
			echo(mysqli_affected_rows($conexion));
		}
	//fin

	//Marca el Poa asignado para comprar el detalle
		if($tipoGuardar=='GuardarPoaMultiple'){		
			$arrayDetalles	= [];
			$response = array("filasAfectadas"=> 0 ,"detallesLista" => $arrayDetalles);
			if (isset($_POST['detallesSeleccionados'])) {
				$detalles = $_POST['detallesSeleccionados'];
				$detail_ids = [];
				foreach ($detalles as $detalle) {
					if (isset($detalle['detail'])) {
						$detail_ids[] = $detalle['detail'];
					}
				}
				if (count($detail_ids) > 0) {
					$detail_ids_string = implode(',', $detail_ids);			
					$query_RsPoa="UPDATE  detalle_requ 
					SET DEREPOA   = '".$_GET['poa']."'												     
				  WHERE DERECONS   in (". $detail_ids_string .") ";
				  $arrayDetalles = explode(',', $detail_ids_string);
					$RsPoa = mysqli_query($conexion,$query_RsPoa) or die(mysqli_error($conexion));						
					$response = array("filasAfectadas"=>mysqli_affected_rows($conexion), "detallesLista"=>$arrayDetalles);
				}

			echo(json_encode($response));
		}
	}

		if($tipoGuardar=='GuardarPoa'){		
			    
			$query_RsPoa="UPDATE  detalle_requ 
										 SET DEREPOA   = '".$_GET['poa']."'												     
									   WHERE DERECONS   = '".$_GET['codigo_detalle']."' 
									";
			$RsPoa = mysqli_query($conexion,$query_RsPoa) or die(mysqli_error($conexion));
		 

	echo(mysqli_affected_rows($conexion));
}		
	//fin	


	
	//Marca el Poa asignado para comprar el detalle 
		if($tipoGuardar=='GuardarSubPoaMultiple'){

			$arrayDetalles	= [];
			$response = array("filasAfectadas"=> 0 ,"detallesLista" => $arrayDetalles);
			if (isset($_POST['detallesSeleccionados'])) {
				$detalles = $_POST['detallesSeleccionados'];
				$detail_ids = [];
				foreach ($detalles as $detalle) {
					if (isset($detalle['detail'])) {
						$detail_ids[] = $detalle['detail'];
					}
				}
				if (count($detail_ids) > 0) {
					$detail_ids_string = implode(',', $detail_ids);			
					$query_RsPoa="UPDATE  detalle_requ 
												 SET DERESUPO = '".$_GET['subpoa']."'												     
											   WHERE DERECONS in (". $detail_ids_string .") ";
				  $arrayDetalles = explode(',', $detail_ids_string);
					$RsPoa = mysqli_query($conexion,$query_RsPoa) or die(mysqli_error($conexion));						
					$response = array("filasAfectadas"=>mysqli_affected_rows($conexion), "detallesLista"=>$arrayDetalles);
				}

			echo(json_encode($response));
		}

			}
		if($tipoGuardar=='GuardarSubPoa'){$query_RsPoa="UPDATE  detalle_requ 
												 SET DERESUPO   = '".$_GET['subpoa']."'												     
											   WHERE DERECONS   = '".$_GET['codigo_detalle']."' 
											";
					$RsPoa = mysqli_query($conexion,$query_RsPoa) or die(mysqli_error($conexion));
				 
    
			echo(mysqli_affected_rows($conexion));
			}
	//fin

	//Marca el Poa asignado para comprar el detalle 
		if($tipoGuardar=='GuardarOtroPoa'){$query_RsPoa="UPDATE  detalle_requ 
												 SET DEREOTRO   = '".$_GET['otropoa']."'												     
											   WHERE DERECONS   = '".$_GET['codigo_detalle']."' 
											";
					$RsPoa = mysqli_query($conexion,$query_RsPoa) or die(mysqli_error($conexion));
				 
    
			echo(mysqli_affected_rows($conexion));
			}
	//fin
	//reiniciar el detalle a 0 
		if($tipoGuardar=='reiniciardetalle'){
		          $query_RsUpdate="UPDATE  detalle_requ 
												 SET 	`DEREVIAT` 	= 	'',
  														`DERECLAS` 	=	'',
   														`DEREAPRO` 	=	'0',
   														`DERENCOT` 	= 	'',
  														`DERECONV` 	=	'',
   														`DEREELEC`  =	'',
   														 DERETIPO	=	''												     
											   WHERE DERECONS   = '".$_GET['codigo_detalle']."' 
											";
					$RsUpdate = mysqli_query($conexion,$query_RsUpdate) or die(mysqli_error($conexion));
				 
    
			echo(mysqli_affected_rows($conexion));
						
			}
	//fin		
			

	//Validar Cambio de cantidad de un detalle 
		if($tipoGuardar=='Validar_cantidad'){

    	$query_Rsvalidar_cantidad = "SELECT DERECANT CANTIDAD 
									FROM `detalle_requ` 
									WHERE `DERECONS`='".$_GET['codigo_detalle']."'";
    	$Rsvalidar_cantidad = mysqli_query($conexion, $query_Rsvalidar_cantidad) or die(mysqli_error($conexion));
		$row_Rsvalidar_cantidad = mysqli_fetch_array($Rsvalidar_cantidad);
    	$totalRows_Rsvalidar_cantidad = mysqli_num_rows($Rsvalidar_cantidad);
	
		if($row_Rsvalidar_cantidad['CANTIDAD'] != $_GET['cantidad']){
		echo('1');
		}else{
		echo('0');
		}
	}
	//fin

	//Marcar el  Cambio de la cantidad de un detalle 
	if($tipoGuardar=='MarcarCambioCantidad'){$query_RsUpdate="UPDATE  detalle_requ 
												 SET DERECANT   = '".$_GET['cantidad']."'												     
											   WHERE DERECONS   = '".$_GET['codigo_detalle']."' 
											";
					$RsUpdate = mysqli_query($conexion,$query_RsUpdate) or die(mysqli_error($conexion));
				 
    
			echo(mysqli_affected_rows($conexion));
			}
	//fin

	//Marca el Poa asignado para comprar el detalle 
		if($tipoGuardar=='GuardarOtroPoa'){$query_RsPoa="UPDATE  detalle_requ 
												 SET DEREOTRO   = '".$_GET['otropoa']."'												     
											   WHERE DERECONS   = '".$_GET['codigo_detalle']."' 
											";
					$RsPoa = mysqli_query($conexion,$query_RsPoa) or die(mysqli_error($conexion));
				 
    
			echo(mysqli_affected_rows($conexion));
			}
	//fin
	//Marca el Poa asignado para comprar el detalle 
		if($tipoGuardar=='EliminarDetalle'){
			 $query_RsDeleteDet="DELETE 	FROM DETALLE_REQU 	
			 								WHERE DERECONS = '".$_GET['codigo_detalle']."'
			 								";
 			 $RsDeleteDet = mysqli_query($conexion,$query_RsDeleteDet) or die(mysqli_error($conexion));
     		  			
			echo(mysqli_affected_rows($conexion));
			}
	//fin		

    //Aprobar un detalle para la compra
		if($tipoGuardar=='AprobarDetalle'){
 	      
 	      	$query_RsAprobarDetalle = "SELECT DEREAPRO 
 	      						       FROM   detalle_requ 
 	      						       WHERE  DERECONS='".$_GET['codigo_detalle']."'
 	      						       AND 	  DEREAPRO=22
 	      							  ";
    	   	$RsAprobarDetalle = mysqli_query($conexion, $query_RsAprobarDetalle) or die(mysqli_error($conexion));
		   	$row_RsAprobarDetalle = mysqli_fetch_array($RsAprobarDetalle);
    		$totalRows_RsAprobarDetalle = mysqli_num_rows($RsAprobarDetalle);
	
			if($totalRows_RsAprobarDetalle>0){
		
			 	$query_RsAprobar_detalle="UPDATE  detalle_requ 
											 SET DEREAPRO	= '12',
											     DERECANT	= '".$_GET['cantidad']."'
										   WHERE DERECONS   = '".$_GET['codigo_detalle']."' 
									";
				$RsAprobar_detalle = mysqli_query($conexion,$query_RsAprobar_detalle) or die(mysqli_error($conexion));	

			}
	
			echo(mysqli_affected_rows($conexion));
		}

		//Aprobar un detalle para la compra electronica
		if($tipoGuardar=='marcarcompraelectronica'){

		     if($_GET['parm'] == 0){
		      $autoriza=1;
		      $estado=29;
		      $query_RsRequerimientosDetalle = " UPDATE detalle_requ SET DERECLAS = '6'			                                                          
												WHERE DERECONS = '".$_GET['codigo_detalle']."'
											  ";
			  $RsRequerimientosDetalle       = mysqli_query($conexion,$query_RsRequerimientosDetalle) or die(mysqli_error());
		     } 	
		     if($_GET['parm'] == 1){
		      $autoriza=2;
		      $estado=30;
		     } 

		     if($_GET['parm'] == 2){
		      $autoriza=3;
		      $estado=31;
		     } 

		
			 	$query_RsAprobar_detalle="UPDATE  detalle_requ 
											 SET DEREAPRO	= '".$estado."',
											     DEREELEC	= '".$autoriza."'
										   WHERE DERECONS   = '".$_GET['codigo_detalle']."' 
									";
				$RsAprobar_detalle = mysqli_query($conexion,$query_RsAprobar_detalle) or die(mysqli_error($conexion));	
	
			echo(mysqli_affected_rows($conexion));
		}

		//Aprobar un detalle para la autorizacion de viaticos
		if($tipoGuardar=='marcarviatico'){

		     if($_GET['parm'] == 0){
		      $autoriza=1;
		      $estado=24;
		      $query_RsRequerimientosDetalle = " UPDATE detalle_requ SET DERECLAS = '5'
			                                                          
												WHERE DERECONS = '".$_GET['codigo_detalle']."'
											  ";
			  $RsRequerimientosDetalle       = mysqli_query($conexion,$query_RsRequerimientosDetalle) or die(mysqli_error());
		     } 	
		     if($_GET['parm'] == 1){
		      $autoriza=2;
		      $estado=25;
		     } 

		     if($_GET['parm'] == 2){
		      $autoriza=3;
		      $estado=26;
		     } 

		
			 	$query_RsAprobar_detalle="UPDATE  detalle_requ 
											 SET DEREAPRO	= '".$estado."',
											     DEREVIAT	= '".$autoriza."'
										   WHERE DERECONS   = '".$_GET['codigo_detalle']."' 
									";
				$RsAprobar_detalle = mysqli_query($conexion,$query_RsAprobar_detalle) or die(mysqli_error($conexion));	
	
			echo(mysqli_affected_rows($conexion));
		}
		//Aprobar un detalle para la autorizacion de viaticos
		if($tipoGuardar=='marcarcompraMinimaCuantia'){

		     
		      $query_RsRequerimientosDetalle = " UPDATE detalle_requ SET DEREAPRO = '11'			                                                          
												WHERE DERECONS = '".$_GET['codigo_detalle']."'
											  ";
			  $RsRequerimientosDetalle       = mysqli_query($conexion,$query_RsRequerimientosDetalle) or die(mysqli_error());
		     
		    
	
			echo(mysqli_affected_rows($conexion));
		}
		
        //Guardar Informacion de detalles autorizados de menor cuantia
        if($tipoGuardar=='GuardarMenorCuantia'){

			$afectado = 0;
			if(isset($_POST['json']) && $_POST['json']!=''){	
				$tojson = json_decode($_POST['json']);	
			//var_dump($tojson->estatico->p_d);
		  $query_RsProveedorNocotiza = "INSERT INTO `proveedores_nocotiza` (
													 `PROVCODI`,
													 `PROVREGI`,
													 `PROVNOMB`, 
													 `PROVTELE`,																			  
													 `PROVDIRE`,
													  PROVCORR
													) 
													VALUES (  
													NULL,
													'".$tojson->estatico->p_n."',
													'".$tojson->estatico->p_d."',
													'".$tojson->estatico->p_t."',																			  
													'".$tojson->estatico->p_dir."',																			  
													'".$tojson->estatico->p_e."'																			  
													)";
	//echo($query_RsInsertarOrden);		
    $RsProveedorNocotiza = mysqli_query($conexion,$query_RsProveedorNocotiza) or die(mysqli_error($conexion));

	
	
	
	
    $query_RsUltInsert = "SELECT LAST_INSERT_ID() DATO";
	$RsUltInsert = mysqli_query($conexion,$query_RsUltInsert) or die(mysqli_error($conexion));
	$row_RsUltInsert = mysqli_fetch_array($RsUltInsert);
	$codigo_OrdenCompraNocotiza = $row_RsUltInsert['DATO'];
	
  if (is_array($tojson->dinamico)){
	  for($i=0; $i<count($tojson->dinamico); $i++){
		  //exit($tojson->dinamico[$i]->det);
		 $query_RsOrdenDetalleNocotiza = "INSERT INTO `orden_compradet_nocotiza` (
																		   `OCDNCODI`, 
																		   `OCDNORCO`, 
																		   `OCDNDETA`,
																			OCDNDESC ,																		   
																		   `OCDNVAUN`,
																		    OCDNCANT,
																			OCDNPOIV,
																			OCDNVAIV
																		   ) 
																   VALUES (
																			NULL, 
																			'".$codigo_OrdenCompraNocotiza."', 
																			'".$tojson->dinamico[$i]->det."', 
																			'".$tojson->dinamico[$i]->desc."',
																			'".$tojson->dinamico[$i]->vunit."',
																			'".$tojson->dinamico[$i]->cant."',
																			'".$tojson->dinamico[$i]->porc_iva."',
																			'".$tojson->dinamico[$i]->valor_iva."'
																			)	
																			";
		//echo($query_RsInsertarOrden);		
		$RsOrdenDetalleNocotiza = mysqli_query($conexion,$query_RsOrdenDetalleNocotiza) or die(mysqli_error($conexion));
		
		 $query_RsupdateEstaDet = "UPDATE DETALLE_REQU SET DEREAPRO = '20' WHERE DERECONS = ".$tojson->dinamico[$i]->det."";
					         $RsupdateEstaDet = mysqli_query($conexion,$query_RsupdateEstaDet) or die(mysqli_error($conexion));
       }
	
	}																	 
			
			
			
			
		
		print_r($codigo_OrdenCompraNocotiza);
	
	
}
		}
			
			
		
// Seccion de condiciones de manipulacion de datos de Requerimiento

if($tipoGuardar == 'AprobarCoordinador'){
	$codigo_detalle='';
	if(isset($_GET['codigo_detalle']) && $_GET['codigo_detalle'] !=''){
		$codigo_detalle = $_GET['codigo_detalle']; /* nombre de usuario */
	}

	$query_RsUpdateProducto = "UPDATE  detalle_requ 
					SET DEREAPRO	= '34'											     
				WHERE DERECONS   = '".$_GET['codigo_detalle']."' 
				";
						
	$RsUpdateProducto = mysqli_query($conexion,$query_RsUpdateProducto) or die(mysqli_connect_error($conexion));

	$afectado = mysqli_affected_rows($conexion);
	if($afectado == ''){ $afectado = 0;}
	echo($afectado);	


}
		
        //marcar Requerimiento Bandera Edicion
		if($tipoGuardar=='marcarBanderaEdit'){

			$query_Rsbandera="SELECT R.REQUFLED
			               FROM REQUERIMIENTOS R
						  WHERE R.REQUCODI = '".$_GET['codreq']."'";
			$Rsbandera = mysqli_query($conexion,$query_Rsbandera) or die(mysqli_error($conexion));
			$row_Rsbandera = mysqli_fetch_array($Rsbandera);
		    if($row_Rsbandera['REQUFLED']== 1){
                $query_RsCambiarBandera="UPDATE  REQUERIMIENTOS
											 SET REQUFLED = 0	
										   WHERE REQUCODI = ".$_GET['codreq']."
									";
				$RsCambiarBandera = mysqli_query($conexion,$query_RsCambiarBandera) or die(mysqli_error($conexion));
		    }else if($row_Rsbandera['REQUFLED']== 0){
                 $query_RsCambiarBandera="UPDATE  REQUERIMIENTOS
											 SET REQUFLED = 1	
										   WHERE REQUCODI = ".$_GET['codreq']."
									";
				$RsCambiarBandera = mysqli_query($conexion,$query_RsCambiarBandera) or die(mysqli_error($conexion));
		    }

				
	
			echo(mysqli_affected_rows($conexion));
		}

		//marcar Requerimiento Visto
		if($tipoGuardar=='marcarReqVisto'){
			$query_RsVisto_requerimiento="UPDATE  REQUERIMIENTOS
											 SET REQUFERE =	SYSDATE()
										   WHERE REQUCODI = ".$_GET['codreq']."
									";
				$RsVisto_requerimiento = mysqli_query($conexion,$query_RsVisto_requerimiento) or die(mysqli_error($conexion));	
	
			echo(mysqli_affected_rows($conexion));
		}

		//Recibir un requerimiento
		if($tipoGuardar=='RecibirRequ'){
		 if(isset($_GET['parm']) && ($_GET['parm'])!=''){
		 	//actualiza el estado
			   $query_RsUsuario_Nuevo="UPDATE REQUERIMIENTOS SET REQUESTA = '".$_GET['parm']."'
			                            where REQUCODI = '".$_GET['codreq']."'";
			   $RsUsuario_Nuevo = mysqli_query($conexion,$query_RsUsuario_Nuevo) or die(mysqli_error($conexion));  
		   // actualiza la persona que realiza el registro 
			   if($_GET['parm']=='5'){
			    $query_RsUpdateRecibido = "UPDATE REQUERIMIENTOS SET REQUFEAP = SYSDATE(),
				                                                     REQUPERE = '".$_SESSION['MM_UserID']."'
											WHERE REQUCODI = '".$_GET['codreq']."'";
			    $RsUpdateRecibido = mysqli_query($conexion,$query_RsUpdateRecibido) or die(mysqli_error($conexion));
			 /*   
		    $infomacion_codi=$_GET['codreq'];
			include("correo_recibir.php");
			
			$query_RsPoa="SELECT D.DERECONS CODIGO,
								 D.DEREAPRO APROB
			               FROM DETALLE_REQU D
						  WHERE D.DEREREQU = '".$_GET['codreq']."'";
			$RsPoa = mysqli_query($conexion,$query_RsPoa) or die(mysqli_error($conexion));
			$row_RsPoa = mysqli_fetch_array($RsPoa);
		    $totalRows_RsPoa = mysqli_num_rows($RsPoa);
			  if($totalRows_RsPoa>0){
			     do{if($row_RsPoa['APROB'] != 2){
				   $query_RsUpdateTodos="UPDATE DETALLE_REQU SET DEREAPRO = '0'
											where DERECONS = '".$row_RsPoa['CODIGO']."'";
				   $RsUpdateTodos = mysqli_query($conexion,$query_RsUpdateTodos) or die(mysqli_error($conexion));	
				   }
				   }while($row_RsPoa = mysqli_fetch_array($RsPoa));
			  }*/
			}
			
		   if($_GET['parm']=='4'){
		    $query_RsUpdateNoRecibido = "UPDATE REQUERIMIENTOS SET REQUFENR = SYSDATE(),
			                                                     REQUPENR = '".$_SESSION['MM_UserID']."'
										WHERE REQUCODI = '".$_GET['codreq']."'";
		    $RsUpdateNoRecibido = mysqli_query($conexion,$query_RsUpdateNoRecibido) or die(mysqli_error($conexion));   
		    //$consecutivo_requerimiento = $_GET['codreq'];
		    //include("correo_norecibido.php");
		   }   
			
		   $afectado=mysqli_affected_rows($conexion);
		   if($afectado!=''){
		    echo($afectado);
		   }else{
		   echo('none');
		   }
		 }
		}


// Seccion de condiciones de manipulacion de datos de Proveedores
	//Marca el Poa asignado para comprar el detalle 
		if($tipoGuardar=='ValidarProveedor'){
			 $query_RsProvedor="SELECT * 
			 				FROM `proveedores` 
			 				WHERE provregi='".$_GET['nit']."'


			 								";
 			 $RsProvedor = mysqli_query($conexion,$query_RsProvedor) or die(mysqli_error($conexion));
     		  			
			echo(mysqli_affected_rows($conexion));
			}
	//fin	
	//Marca el Poa asignado para comprar el detalle 
		if($tipoGuardar=='GuardarCompraElectronica'){
			 $query_RsProvedor="SELECT * 
			 				FROM `proveedores` 
			 				WHERE provregi='".$_GET['nit']."'


			 								";
 			 $RsProvedor = mysqli_query($conexion,$query_RsProvedor) or die(mysqli_error($conexion));
 			 
 			$query_RsAprobar_detalle="UPDATE  detalle_requ 
											 SET DEREAPRO	= '22'											     
										   WHERE DERECONS   = '".$_GET['deta']."' 
									";
				$RsAprobar_detalle = mysqli_query($conexion,$query_RsAprobar_detalle) or die(mysqli_error($conexion));	
     		  			
			echo(mysqli_affected_rows($conexion));
			}
	//fin

	//Consultar el estado de un detalle 
		if($tipoGuardar=='ConsultarEstadoDet'){
			 $query_RsConsulta="SELECT dereapro
			 				FROM `detalle_requ` 
			 				WHERE derecons='".$_GET['cd']."'


			 								";
 			 $RsConsulta = mysqli_query($conexion,$query_RsConsulta) or die(mysqli_error($conexion));
 			 $row_RsConsulta = mysqli_fetch_array($RsConsulta);
 			  			
			echo($row_RsConsulta['dereapro']);
			}
	//fin
	//Consultar el estado de un detalle 		
		if($tipoGuardar=='CorregirDet'){
			

	   $query_Rsdta_devuelto="UPDATE DETALLE_REQU 
									  SET DEREAPRO = '0' 
									WHERE DERECONS = '".$_GET['codigo_detalle']."'";
	    $Rsdta_devuelto = mysqli_query($conexion,$query_Rsdta_devuelto) or die(mysql_error());
		
		if($Rsdta_devuelto){
			$query_RsCrearRequerimiento="INSERT INTO OBSERVACIONESDET (
	                                                         OBDECODI,
															 OBDEPERS,
															 OBDEFECH,
															 OBDECODE,
															 OBDEOBSE,
															 OBDEAPROB
															 )
															 VALUES
															 (
															 NULL,
															 '".$_SESSION['MM_UserID']."',
															 sysdate(),
															 '".$_GET['codigo_detalle']."',
															 'Se realizo la correccion solicitada',
															 '2'

															 )";
															 //exit($query_RsCrearRequerimiento);
  			$RsCrearRequerimiento = mysqli_query($conexion,$query_RsCrearRequerimiento) or die(mysqli_error($conexion));
		 echo('1');
		}else{
		echo('none');
		}

		}			
    //fin
//seccion de manipulacion de datos de ordenes de compra
	
	//autorizar una compra director de compras	desde cada detalle	
	if($tipoGuardar=='AutorizarOrden')
	{	
      $detalle=$_GET['detalle'];
		$query_RsConsultaOrdenAutorizaAdm = "	SELECT orcofirm,
												   orcofirm2  
											FROM   orden_compra,
												  `orden_compradet`, 
												   detalle_requ 
										    where  orcocons='".$_GET['orden']."'
										    and    orcddeta=derecons 
										    and    orcdorco=orcocons 
										    and    orcofirm = '0' 
										    and    orcofirm2 = '0'
										    and    dereapro = 16

	
											
						 ";
		$RsConsultaOrdenAutorizaAdm = mysqli_query($conexion,$query_RsConsultaOrdenAutorizaAdm) or die(mysqli_error($conexion));
		$row_RsConsultaOrdenAutorizaAdm = mysqli_fetch_array($RsConsultaOrdenAutorizaAdm);
		$totalRows_RsConsultaOrdenAutorizaAdm = mysqli_num_rows($RsConsultaOrdenAutorizaAdm);	


    	if($totalRows_RsConsultaOrdenAutorizaAdm > '1'){
      	$query_RsAprobar_detalle="UPDATE  detalle_requ 
											 SET  DEREAPRO	= '27'											     
										   WHERE DERECONS   = '".$detalle."' 
									";
				$RsAprobar_detalle = mysqli_query($conexion,$query_RsAprobar_detalle) or die(mysqli_error($conexion));	
		echo('1');
    	}else {
    	echo('2');
     	$query_RsAprobar_Orden="UPDATE  orden_compra 
											 SET ORCOFIRM2	= '1'											  												     
										   WHERE ORCOCONS   = '".$_GET['orden']."' 
									";
				$RsAprobar_Orden = mysqli_query($conexion,$query_RsAprobar_Orden) or die(mysqli_error($conexion));	

				$query_RsAprobar_detalle="UPDATE  detalle_requ 
											 SET  DEREAPRO	= '27'											     
										   WHERE DERECONS   = '".$detalle."' 
									";
				$RsAprobar_detalle = mysqli_query($conexion,$query_RsAprobar_detalle) or die(mysqli_error($conexion));	

    	}
			//echo(mysqli_affected_rows($conexion));
	}
   //fin
   //Marca la entrega de la orden de compra se a manual o electronicamente	 
		if($tipoGuardar=='EntregarOrden')
		{
					$query_RsAprobar_Orden="UPDATE  orden_compra 
												 SET ORCOENTR	= '1'											  												     
											   WHERE ORCOCONS   = '".$_GET['orden']."' 
										";
					$RsAprobar_Orden = mysqli_query($conexion,$query_RsAprobar_Orden) or die(mysqli_error($conexion));	

					echo(mysqli_affected_rows($conexion));	
		}
		
		
	function copiarRegistroDetalle($detalleId, $conexion, $tipoHistorial)	{
		$query_RsDatoRegistroUnico = "INSERT INTO detalle_actividad_historial
											  (DERECONS,
											   DEREVIAT,
											   DEREMODA,
											   DERECLAS,
											   DEREDESC,
											   DERECANT,
											   DEREJUST,
											   DEREOBSE,
											   DERETISE,
											   DEREREQU,
											   DEREAPRO,
											   DEREUNME,
											   DERECOSU,
											   DERECOTE,
											   DERECOIN,
											   DEREPOA,
											   DERESUPO,
											   DEREOTRO,
											   DEREREOT,
											   DEREPRES,
											   DERETIPO,
											   DERECOOC,
											   DEREIOCC,
											   DERECOMC,
											   DERENCOT,
											   DEREPROV,
											   DERECONV,
											   DEREMPAS,
											   DEREPROV2,
											   DEREDCOM,
											   DERECARE,
											   DEREFIRM,
											   DEREFFDA,
											   DEREFFRE,
											   DEREPRRE,
											   DEREFERE,
											   DEREFRSO,
											   DEREFOCR,
											   DEREFEMU,
											   DEREFPCO,
											   DEREFECO,
											   DEREFEEC,
											   DEREFERC,
											   DEREFEAP,
											   DEREFEPA,
											   DEREFEEO,
											   DEREFEOC,
											   DEREFOAP,
											   DEREELEC,
											   DERETIAC)
											  SELECT DERECONS,
													 DEREVIAT,
													 DEREMODA,
													 DERECLAS,
													 DEREDESC,
													 DERECANT,
													 DEREJUST,
													 DEREOBSE,
													 DERETISE,
													 DEREREQU,
													 DEREAPRO,
													 DEREUNME,
													 DERECOSU,
													 DERECOTE,
													 DERECOIN,
													 DEREPOA,
													 DERESUPO,
													 DEREOTRO,
													 DEREREOT,
													 DEREPRES,
													 DERETIPO,
													 DERECOOC,
													 DEREIOCC,
													 DERECOMC,
													 DERENCOT,
													 DEREPROV,
													 DERECONV,
													 DEREMPAS,
													 DEREPROV2,
													 DEREDCOM,
													 DERECARE,
													 DEREFIRM,
													 DEREFFDA,
													 DEREFFRE,
													 DEREPRRE,
													 DEREFERE,
													 DEREFRSO,
													 DEREFOCR,
													 DEREFEMU,
													 DEREFPCO,
													 DEREFECO,
													 DEREFEEC,
													 DEREFERC,
													 DEREFEAP,
													 DEREFEPA,
													 DEREFEEO,
													 DEREFEOC,
													 DEREFOAP,
													 DEREELEC,
													 '".$tipoHistorial."' DERETIAC
												FROM detalle_requ
											   WHERE detalle_requ.DERECONS = '".$detalleId."'";
		$RsDatoRegistroUnico = mysqli_query($conexion,$query_RsDatoRegistroUnico) or die(mysqli_error($conexion));
		return 1;
		
	}
    //fin
	 //Anular la orden de compra 
	 if($tipoGuardar=='AnularOrden')
	 {
				 $query_RsAnularOrden="UPDATE  orden_compra 
											  SET ORCOANUL	= '1'											  												     
											WHERE ORCOCONS   = '".$_GET['orden']."' 
									 ";
				 $RsAnularOrden = mysqli_query($conexion,$query_RsAnularOrden) or die(mysqli_error($conexion));	
				 
				 $datos = getDataByOrdenId($_GET['orden'], $conexion);
				 foreach($datos as $item){
					 
				 $copiado = copiarRegistroDetalle($item['codigo_detalle'], $conexion, 'ANULAR_COMPRA');
					 if($copiado){
						 $query_RsUpdateDetalle="UPDATE  detalle_requ 
												  SET DEREAPRO	= '18',
													  DERECOOC =  0
												WHERE DERECONS   = '".$item['codigo_detalle']."' 
										 ";
					 $RsUpdateDetalle = mysqli_query($conexion,$query_RsUpdateDetalle) or die(mysqli_error($conexion));	
					 }				 
				 }
				 
					$query_RsConsultarF = "SELECT F.* 
					                        FROM 
											 orden_compra oc,
											 firmas F 
										  WHERE oc.ORCOFIRM = F.FIRMCONS										  
										   and oc.ORCOCONS = '".$_GET['orden']."'";
					$RsConsultarF = mysqli_query($conexion,$query_RsConsultarF) or die(mysqli_error($conexion));
					$row_RsConsultarF = mysqli_fetch_assoc($RsConsultarF);
					$totalRows_RsConsultarF = mysqli_num_rows($RsConsultarF);				 
				 if($totalRows_RsConsultarF == 0){
					echo(mysqli_affected_rows($conexion));	
				 }else{
					echo($row_RsConsultarF['FIRMCONS']);	
				 }
	 }
 //fin
	//Realiza el envio de la orden de compra electronicamente, verificando si esta firmada, enviando directamente al proveedor,
	// marcando cada detalle de la orden con la fecha de envio	
	if($tipoGuardar=='Enviar_correo_Orden'){
	
	$proveedor =$_GET['prov'];
	$codigo_o  =$_GET['cod_orden'];
	$response_mail="";
	$msj="";
	
	$query_RsConsulta = "SELECT `FIRMCODI` CODIGO_UNICO,
								 FIRMCONS  CONSECUTIVO,
								 FIRMDOCU,
								 (SELECT `TOCONOMB` 
										  FROM `tipoorden_compra` 
										  WHERE `TOCOCODI`=ORCOTIOR)  TIPO_ORDEN_DESC
						 FROM `orden_compra` ,`firmas` 
						 WHERE ORCOCONS='".$codigo_o."'
						 AND `ORCOFIRM`=`FIRMCONS`";
	$RsConsulta = mysqli_query($conexion,$query_RsConsulta) or die(mysqli_error($conexion));
	$row_RsConsulta = mysqli_fetch_array($RsConsulta);
	$totalRows_RsConsulta = mysqli_num_rows($RsConsulta);
	
	$codigo_firma = $row_RsConsulta['CONSECUTIVO'];
	$orden_desc   = $row_RsConsulta['TIPO_ORDEN_DESC'];
	
	$query_Rsproveedor = "SELECT  `PROVNOMB` NOMBRE,
								  `PROVCORR` CORREO
							 FROM `proveedores` 
							WHERE PROVCODI= '".$proveedor."' ";
	$Rsproveedor = mysqli_query($conexion,$query_Rsproveedor) or die(mysqli_error($conexion));
	$row_Rsproveedor = mysqli_fetch_array($Rsproveedor);
	$totalRows_Rsproveedor = mysqli_num_rows($Rsproveedor);

	$query_RsParametroRuta = "SELECT PARAVALOR FROM PARAMETROS WHERE PARANOMB = 'RUTAARCHIVO_NAS'";
	$RsParametroRuta = mysqli_query($conexion,$query_RsParametroRuta) or die(mysqli_error($conexion));
	$row_RsParametroRuta = mysqli_fetch_array($RsParametroRuta);

	$rutaArchivos = $row_RsParametroRuta['PARAVALOR'];
	$carpeta = 'archivos_compras/ORDENES/';
	$rutaArchivos = $row_RsParametroRuta['PARAVALOR'].$carpeta;

	//$RutaArchivoAdjunto = $rutaArchivos.$row_RsConsulta['FIRMDOCU'];
	//echo($RutaArchivoAdjunto);

	 if($totalRows_Rsproveedor>0)
	 	{
				    $nombre_completo	= $row_Rsproveedor['NOMBRE']; //"Nombre a quien va dirigido"
					$asunto				="Orden de ".$row_RsConsulta['TIPO_ORDEN_DESC'];// lo que se desae en asunto
				    $dirigido 			= $row_Rsproveedor['CORREO'];//Correo a quien se va a enviar
					//$dirigido			="dyamid@gmail.com";
					$imagen_cabecera	='http://compras.sanboni.edu.co/imagenes/9.png';//imagen de cabecera					
					$RutaArchivoAdjunto = $rutaArchivos.$row_RsConsulta['FIRMDOCU'];

                     
					$tema               ='Usted ha sido seleccionado por la Corporaci&oacute;n Colegio San Bonifacio de las Lanzas para
                                          proveer lo indicado en la orden de compra adjunta a este correo.
										  <br>
                                          Agradecemos su participación.
										  <br>
                                          Por seguridad las órdenes generadas por la Corporaci&oacute;n Colegio San Bonifacio de las Lanzas
                                          tienen contraseña, esta se entregará haciendo clic en “verifica firma”.	
					
					
					';	//el tema del correo
					$link				='http://compras.sanboni.edu.co/validar_firma.php?key='.$codigo_firma;
					$nombre_link		='VERIFICAR FIRMA';					
					require_once('plantilla_correo.php');	
					if(!$mail->send()) 
					{						
						$response_mail="no enviado " . $mail->ErrorInfo;
					} else {							
								$response_mail= "exito";
							}
		}
		if($response_mail == "exito")
		{
			$query_RsDetallesOrden = "SELECT D.ORCDDETA DETALLE
									   FROM orden_compradet D
									  WHERE D.ORCDORCO = '".$codigo_o."'";
			$RsDetallesOrden = mysqli_query($conexion,$query_RsDetallesOrden) or die(mysqli_error($conexion));
			$row_RsDetallesOrden = mysqli_fetch_array($RsDetallesOrden);
			$totalRows_RsDetallesOrden = mysqli_num_rows($RsDetallesOrden);	 
			if($totalRows_RsDetallesOrden>0){
				do{
				$query_RsUpdateFirmaOrdenCompraRector = "UPDATE detalle_requ SET `DEREFOAP` = sysdate() WHERE DERECONS  = '".$row_RsDetallesOrden['DETALLE']."'";
				$RsUpdateFirmaOrdenCompraRector       = mysqli_query($conexion,$query_RsUpdateFirmaOrdenCompraRector) or die(mysqli_error());
				}while($row_RsDetallesOrden = mysqli_fetch_array($RsDetallesOrden));
			}
            $query_RsOrden="UPDATE  orden_compra 
												 SET ORCOENTR	= '2'											  												     
											   WHERE ORCOCONS   = '".$codigo_o."' 
										";
			$RsOrden = mysqli_query($conexion,$query_RsOrden) or die(mysqli_error($conexion));	

			echo('1');
		}else{
			
				echo('0');
			}	
			
	}
   //fin	

// verificar el proveedor si existe cuando auxiliar crea nuevo

if($tipoGuardar=='VerificarProveedorExiste')
	{
		$response = ComprobarUnicoProveedor($_GET['identificacion'], $conexion );
		echo(json_encode($response));
	}
		  
?>