<?php
require_once('../../../conexion/db.php');
$tipo_guardar='';
if(isset($_GET['tipoGuardar'])&& $_GET['tipoGuardar']!='')
{
$tipo_guardar=$_GET['tipoGuardar'];
}

$codigo_proveedor_oficial = '';
if(isset($_REQUEST['codigo_proveedor_oficial']) && $_REQUEST['codigo_proveedor_oficial'] != ''){
	$codigo_proveedor_oficial = $_REQUEST['codigo_proveedor_oficial'];
}
$tipo_identificacion = '';
if(isset($_POST['tipo_identificacion']) && $_POST['tipo_identificacion'] != ''){
	$tipo_identificacion = $_POST['tipo_identificacion'];
}
$numero_identificacion = '';
if(isset($_POST['numero_identificacion']) && $_POST['numero_identificacion'] != ''){
	$numero_identificacion = $_POST['numero_identificacion'];
}
$div = '';
if(isset($_POST['div']) && $_POST['div'] != ''){
	$div = $_POST['div'];
}
$tipo_persona = '';
if(isset($_POST['tipo_persona']) && $_POST['tipo_persona'] != ''){
	$tipo_persona = $_POST['tipo_persona'];
}
$razon_social = '';
if(isset($_POST['razon_social']) && $_POST['razon_social'] != ''){
	$razon_social = $_POST['razon_social'];
}
$nombre = '';
if(isset($_POST['nombre']) && $_POST['nombre'] != ''){
	$nombre = $_POST['nombre'];
}
$apellidos = '';
if(isset($_POST['apellidos']) && $_POST['apellidos'] != ''){
	$apellidos = $_POST['apellidos'];
}
$nombre_comercial = '';
if(isset($_POST['nombre_comercial']) && $_POST['nombre_comercial'] != ''){
	$nombre_comercial = $_POST['nombre_comercial'];
}
$email = '';
if(isset($_POST['email']) && $_POST['email'] != ''){
	$email = $_POST['email'];
}
$pagina_web = '';
if(isset($_POST['pagina_web']) && $_POST['pagina_web'] != ''){
	$pagina_web = $_POST['pagina_web'];
}
$regimen = '';
if(isset($_POST['regimen']) && $_POST['regimen'] != ''){
	$regimen = $_POST['regimen'];
}
$autoretenedor = '';
if(isset($_POST['autoretenedor']) && $_POST['autoretenedor'] != ''){
	$autoretenedor = $_POST['autoretenedor'];
}
$gran_contribuyente = '';
if(isset($_POST['gran_contribuyente']) && $_POST['gran_contribuyente'] != ''){
	$gran_contribuyente = $_POST['gran_contribuyente'];
}
$contribuyente_ica = '';
if(isset($_POST['contribuyente_ica']) && $_POST['contribuyente_ica'] != ''){
	$contribuyente_ica = $_POST['contribuyente_ica'];
}
$telefono = '';
if(isset($_POST['telefono']) && $_POST['telefono'] != ''){
	$telefono = $_POST['telefono'];
}
$autorizacion = '';
if(isset($_POST['autorizacion']) && $_POST['autorizacion'] != ''){
	$autorizacion = $_POST['autorizacion'];
}
$usuario = '';
if(isset($_POST['usuario']) && $_POST['usuario'] != ''){
	$usuario = $_POST['usuario'];
}
$password = '';
if(isset($_POST['password']) && $_POST['password'] != ''){
	$password = $_POST['password'];
}
$categorias = '';
if(isset($_POST['categorias']) && $_POST['categorias'] != ''){
	$categorias = $_POST['categorias'];
}


	if($tipo_guardar=='BorrarUploadFileProveedor')
	{
	  $impri=0;
				$query_RsParametroRuta = "SELECT PARAVALOR FROM PARAMETROS WHERE PARANOMB = 'RUTAARCHIVOPLAN'";
				$RsParametroRuta = mysqli_query($conexion, $query_RsParametroRuta) or die(mysqli_error($conexion));
				$row_RsParametroRuta = mysqli_fetch_array($RsParametroRuta);
				//$rutaArchivos = $row_RsParametroRuta['PARAVALOR'];
				//$rutaArchivos=explode("archivos",$rutaArchivos);
                //$rutaArchivos=$rutaArchivos[0].'php/files/';
                //$rutaArchivos = 'C://wamp2i/www/ECOSALUDO2012/pruebas/planes/php/files/';
				if(isset($_GET['name_archivo']) && $_GET['name_archivo']!=''){
				$impri=1;
				$nombre_ficheroexcel = 'temporalfiles/'.$_GET['name_archivo'];
						if (file_exists($nombre_ficheroexcel)) {
			              unlink($nombre_ficheroexcel);
		                  $impri=2;
						 }

				}
	   echo($impri);
	}
	
	function ComprobarUnicoUsuario($usuario, $conexion){
		$query_RsDatoUnico = "SELECT * FROM USUARIOS WHERE USUALOG = '".$usuario."'";
		$RsDatoUnico = mysqli_query($conexion,$query_RsDatoUnico) or die(mysqli_error($conexion));
	    $row_RsDatoUnico = mysqli_fetch_array($RsDatoUnico);
	    $totalRows_RsDatoUnico = mysqli_num_rows($RsDatoUnico);
	  
		$query_RsDatoUnico2 = "SELECT * FROM PROVEEDORES_TEMPORAL WHERE PROVUSUA = '".$usuario."'";
		$RsDatoUnico2 = mysqli_query($conexion,$query_RsDatoUnico2) or die(mysqli_error($conexion));
		$row_RsDatoUnico2 = mysqli_fetch_array($RsDatoUnico2);
		$totalRows_RsDatoUnico2 = mysqli_num_rows($RsDatoUnico2);
		$response = array(
						'status'       => 'ok',
						'data_temp'    => '',
						'prov'         => ''
					);		

		
		if($totalRows_RsDatoUnico > 0 || $totalRows_RsDatoUnico2 >0){
			$ingresa = 0;
			if($totalRows_RsDatoUnico2 > 0 ){
				$response = array(
													'status'       => 'errortblprovtemporal',
													'data_temp'    => $row_RsDatoUnico2['PROVESSO'],
													'prov'         => ''
				);
				$ingresa = 1;
			}			
			if($totalRows_RsDatoUnico > 0 && $ingresa == 0){
			$response = array(
												'status'       => 'errortbluser',
												'data_temp'    => '',
												'prov'         => ''
			);
			}			
		}
			return $response;
	}
	if($tipo_guardar=='NoAprobarProveedor')
	{
		$motivo = '';
		$response = array(
						'status'       => 'failed',
						'actualizado'  => '0',
						'msg'          => "Sin Revizar",
						'prov'         => $_GET['codigo_prov']
						);

		if(isset($_POST['motivo']) && $_POST['motivo']!=''){
			
			$afectado = 0;
			$changebutton = 0;
			$motivo = $_POST['motivo'];
		//var_dump($tojson);
		}

		if($motivo != ''){
			$query_RsUpdateProducto = "UPDATE PROVEEDORES_TEMPORAL SET PROVESSO = 2,
																	   PROVJUST = '".$motivo."'
										where PROVCODI = '".$_GET['codigo_prov']."'";
										//echo($query_RsUpdateProducto);
			$RsUpdateProducto = mysqli_query($conexion,$query_RsUpdateProducto) or die(mysqli_error($conexion));
			$response = array(
							'status'       => 'ok',
							'actualizado'  => '1',
							'msg'          => "No Aprobado",
							'prov'         => $_GET['codigo_prov']
							);

		$query_RsDetalleProveedor = "SELECT * FROM PROVEEDORES_TEMPORAL WHERE PROVUSUA = '".$usuario."'";
		$RsDetalleProveedor = mysqli_query($conexion,$query_RsDetalleProveedor) or die(mysqli_error($conexion));
		$row_RsDetalleProveedor = mysqli_fetch_array($RsDetalleProveedor);
		$totalRows_RsDetalleProveedor = mysqli_num_rows($RsDetalleProveedor);			
			if($totalRows_RsDetalleProveedor > 0){
				$nombre_completo	= $row_RsDetalleProveedor['PROVNOMB'];
				$asunto				="No Aprobación de usuario como proveedor";
				$dirigido 			= $row_RsDetalleProveedor['PROVCORR'];
				$imagen_cabecera	='http://190.107.23.165/compras/pruebas/imagenes/9.png';
				$tema               = $motivo;
				//$link				='www.sena.edu.co';
				//$nombre_link		='cotizar';			

				include_once("../../plantilla_correo.php");
			}

		}
		echo(json_encode($response));




	}
		if($tipo_guardar=='VerificarUsuarioExiste')
	{	
		$response = ComprobarUnicoUsuario($_GET['usuario'], $conexion ); /*verificar que el nombre de usuario sea unico en la tabla proveedores temporal y en la tabla de usuarios oficial*/
		echo(json_encode($response));	
	}
	if($tipo_guardar=='VerificarIdentificacionExiste')
	{
		$response = ComprobarUnicoProveedor($_GET['identificacion'], $conexion );
		echo(json_encode($response));
	}
	function ComprobarUnicoProveedor($numero_identidad, $conexion ){
		$query_RsDatoUnico = "SELECT * FROM PROVEEDORES WHERE PROVREGI = '".$numero_identidad."'";
		$RsDatoUnico = mysqli_query($conexion,$query_RsDatoUnico) or die(mysqli_error($conexion));
	    $row_RsDatoUnico = mysqli_fetch_array($RsDatoUnico);
	    $totalRows_RsDatoUnico = mysqli_num_rows($RsDatoUnico);
	  
		$query_RsDatoUnico2 = "SELECT * FROM PROVEEDORES_TEMPORAL WHERE PROVREGI = '".$numero_identidad."'";
		$RsDatoUnico2 = mysqli_query($conexion,$query_RsDatoUnico2) or die(mysqli_error($conexion));
		$row_RsDatoUnico2 = mysqli_fetch_array($RsDatoUnico2);
		$totalRows_RsDatoUnico2 = mysqli_num_rows($RsDatoUnico2);
		$response = array(
						'status'       => 'ok',
						'data_temp'    => '',
						'prov'         => ''
					);		

		
		if($totalRows_RsDatoUnico > 0 || $totalRows_RsDatoUnico2 >0){
			$ingresa = 0;
			if($totalRows_RsDatoUnico > 0){
			$response = array(
												'status'       => 'erroruser',
												'data_temp'    => '',
												'prov'         => ''
			);
			}
			if($totalRows_RsDatoUnico2 > 0 && $ingresa == 0){
			$response = array(
												'status'       => 'errorusertemporal',
												'data_temp'    => $row_RsDatoUnico2['PROVESSO'],
												'prov'         => ''
			);
			}			
		}
			return $response;
	}

	if($tipo_guardar=='saveproveedor')
	{ 
		$query_RsDatoUnico = "SELECT * FROM PROVEEDORES WHERE PROVREGI = '".$numero_identificacion."'";
		$RsDatoUnico = mysqli_query($conexion,$query_RsDatoUnico) or die(mysqli_error($conexion));
		$row_RsDatoUnico = mysqli_fetch_array($RsDatoUnico);
		$totalRows_RsDatoUnico = mysqli_num_rows($RsDatoUnico);
		
		$query_RsDatoUnico2 = "SELECT * FROM PROVEEDORES_TEMPORAL WHERE PROVREGI = '".$numero_identificacion."'";
		$RsDatoUnico2 = mysqli_query($conexion,$query_RsDatoUnico2) or die(mysqli_error($conexion));
		$row_RsDatoUnico2 = mysqli_fetch_array($RsDatoUnico2);
		$totalRows_RsDatoUnico2 = mysqli_num_rows($RsDatoUnico2);	

		$dataConsulta = ComprobarUnicoProveedor($numero_identificacion, $conexion );
		if($dataConsulta['status'] != 'ok'){
			$dataConsulta = ComprobarUnicoUsuario($usuario, $conexion );
		}
		
		if($dataConsulta['status'] != 'ok'){
			
			echo(json_encode($dataConsulta));	
		  }else{
			$nombre_razon_social = ($tipo_persona == 1) ? $nombre.' '.$apellidos : $razon_social; 
		$query_RsInsertProveedor = "INSERT INTO PROVEEDORES_TEMPORAL (
																	PROVCODI,
																	PROVREGI,
																	PROVDIVE,
																	PROVNOMB,
																	PROVNOCO,
																	PROVTELE,
																	PROVPWEB,
																	PROVDIRE,
																	PROVCON1,
																	PROVTEC1,
																	PROVCCO1,
																	PROVCON2,
																	PROVTEC2,
																	PROVCCO2,
																	PROVCOME,
																	PROVPERE,
																	PROVFERE,
																	PROVESTA,
																	PROVCORR,
																	PROVIDCA,
																	PROVCALI,
																	PROVFAVO,
																	PROVCONV,
																	PROVIDCI,
																	PROVUSUA,
																	PROVPASS,
																	PROVTIID,
																	PROVTIPE,
																	PROVREGM,
																	PROVAURE,
																	PROVGRCO,
																	PROVCICA,
																	PROVAUTO

																	)
																	VALUES
																	(
																		NULL,
																		'".$numero_identificacion."',
																		'".$div."',
																		'".$nombre_razon_social."',
																		'".$nombre_comercial."',
																		'".$telefono."',
																		'".$pagina_web."',
																		'',
																		'".$nombre.' '.$apellidos."',
																		'',
																		'',
																		'',
																		'',
																		'',
																		'',
																		'',
																		sysdate(),
																		'0',
																		'".$email."',
																		'1',
																		'0',
																		'0',
																		'0',
																		'',
																		'".$usuario."',
																		'".$password."',
																		'".$tipo_identificacion."',
																		'".$tipo_persona."',
																		'".$regimen."',
																		'".$autoretenedor."',
																		'".$gran_contribuyente."',
																		'".$contribuyente_ica."',
																		'".$autorizacion."'
																	)
																	";
																//	echo($query_RsInsertProveedor);
		$RsInsertProveedor = mysqli_query($conexion, $query_RsInsertProveedor) or die(mysqli_error($conexion));

		$query_RsUltInsert = "SELECT LAST_INSERT_ID() DATO";
		$RsUltInsert = mysqli_query($conexion,$query_RsUltInsert) or die(mysqli_error($conexion));
		$row_RsUltInsert = mysqli_fetch_array($RsUltInsert);
		$ultimo_prov = $row_RsUltInsert['DATO'];

		$query_RsLista_adjuntos="SELECT * FROM CONF_PROVEEDOR WHERE COPRTITU = 'adjuntos'
	            and COPRESTA = 1
	          ";
	  $RsLista_adjuntos = mysqli_query($conexion,$query_RsLista_adjuntos) or die(mysqli_error($conexion));
	  $row_RsLista_adjuntos = mysqli_fetch_array($RsLista_adjuntos);
	  $totalRows_RsLista_adjuntos = mysqli_num_rows($RsLista_adjuntos);
		$array_adjuntos = array();
		if($totalRows_RsLista_adjuntos){
			$t=0;
			do{
				$array_adjuntos[$t] = array(
																		'codigo'  => $row_RsLista_adjuntos['COPRCODI'],
																		'archivo' => $_POST['nombre_delarchivo_'.$row_RsLista_adjuntos['COPRCODI']]
				);
				$t++;
			}while($row_RsLista_adjuntos = mysqli_fetch_array($RsLista_adjuntos));
		}
		//echo(json_encode($array_adjuntos));

		$query_RsInsertProveedor = "INSERT INTO PROVEEDOR_DATATEMP
																	(
																		PDTECODI,
																		PDTEPROV,
																		PDTECATE,
																		PDTEARCH
																	)
																	values
																	(
																		NULL,
																		'".$ultimo_prov."',
																		'".$_POST['categorias']."',
																		'".json_encode($array_adjuntos)."'
																	)
		";
		$RsInsertProveedor = mysqli_query($conexion, $query_RsInsertProveedor) or die(mysqli_error($conexion));
		$query_RsUltInsert = "SELECT LAST_INSERT_ID() DATO";
		$RsUltInsert = mysqli_query($conexion,$query_RsUltInsert) or die(mysqli_error($conexion));
		$row_RsUltInsert = mysqli_fetch_array($RsUltInsert);
		$ultimo_data = $row_RsUltInsert['DATO'];

		$status = 'failed';
		if($ultimo_data > 0 && $ultimo_prov > 0){
			$status = 'ok';
		}
		$response = array(
											'status'       => $status,
											'data_temp'    => $ultimo_data,
											'prov'         => $ultimo_prov
		);
		echo(json_encode($response));
	}
}

	// Tipo recuperar pasword se utiliza para enviar correo de instrucción de recuperacion desde recuperarpassword.php

if($tipo_guardar == 'recuperar_password'){

		$status = 'failed';
		$query_RsConsulta = "   SELECT 	P.PROVUSUA USUARIO_PROVEEDOR, 
										P.PROVCODI CODIGO_PROVEEDOR, 
										P.PROVNOMB NOMBRE_PROVEEDOR, 
										P.PROVREGI REGISTRO, 
										P.PROVCORR EMAIL

								FROM PROVEEDORES P
								WHERE P.PROVCODI = '".$codigo_proveedor_oficial."'
								
								";
		$RsConsulta = mysqli_query($conexion,$query_RsConsulta) or die(mysqli_error($conexion));
		$row_RsConsulta = mysqli_fetch_array($RsConsulta);
		$totalRows_RsConsulta = mysqli_num_rows($RsConsulta);

		if($totalRows_RsConsulta > 0)
		{
            $validado='';
			if ($row_RsConsulta['USUARIO_PROVEEDOR'] == '')
			{
				//no tiene usuario creado verificacion 2
				$validado='2';
				$com_id=$row_RsConsulta['CODIGO_PROVEEDOR'];
				

			} elseif ($row_RsConsulta['USUARIO_PROVEEDOR'] == $row_RsConsulta['REGISTRO']) 
			{ 
				 //si tiene usuario creado				 			
				        $query_RsDatosUsuario     = "SELECT PROVNOMB NOMBRE_PROVEEDOR, PROVCORR EMAIL, PROVUSUA USUARIO,USUALOG LOG,USUACODI				                               
													   FROM USUARIOS, PROVEEDORES
													  WHERE USUALOG = '".$row_RsConsulta['USUARIO_PROVEEDOR']."'
													    AND USUALOG=PROVUSUA
													    AND USUAKEY IS NULL ";
														// echo($query_RsDatosUsuario);
						$RsDatosUsuario           = mysqli_query($conexion,$query_RsDatosUsuario) or die(mysqli_error($conexion));
						$row_RsDatosUsuario       = mysqli_fetch_array($RsDatosUsuario);
						$totalRows_RsDatosUsuario = mysqli_num_rows($RsDatosUsuario);
						
						
						if($totalRows_RsDatosUsuario > 0){
							
							
							$cod_proveedor=$row_RsConsulta['CODIGO_PROVEEDOR'];
                            	
							$caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890"; //posibles caracteres a usar
							$numerodeletras=15; //numero de letras para generar el texto
							$cadena = ""; //variable para almacenar la cadena generada
								for($i=0;$i<$numerodeletras;$i++)
								{
									$cadena .= substr($caracteres,rand(0,strlen($caracteres)),1); /*Extraemos 1 caracter de los caracteres 
								entre el rango 0 a Numero de letras que tiene la cadena */
								}	
									
							$cadena=$cadena.'_'.$cod_proveedor;
							
                          
					        $query_RsUpdateProducto = "UPDATE USUARIOS SET  USUAKEY   = '".$cadena."'														       
																	 WHERE  USUALOG   = '".$row_RsDatosUsuario['LOG']."'
																	 AND    USUACODI  = '".$row_RsDatosUsuario['USUACODI']."'
																	   ";
												//echo($query_RsUpdateProducto);												
					        $RsUpdateProducto = mysqli_query($conexion,$query_RsUpdateProducto) or die(mysqli_connect_error($conexion));
							
							$validado='1';								
							$com_id=$cadena;
							
							
						}else
								{
									//ya existe una key
									$validado='3';								
									$com_id='';
									
								}
			 } else {
					$validado='0';
					$com_id='';				
					//error de registro
			         }	 
		
			
			 
			 $nombre_completo	= $row_RsConsulta['NOMBRE_PROVEEDOR'];
			$asunto				="Instrucciones para restablecer contraseña de usuario como proveedor";
			$dirigido 			= $row_RsConsulta['EMAIL'];
			$imagen_cabecera	='http://compras.sanboni.edu.co/imagenes/9.png';
			$tema               ='Ingrese al siguiente link para restablecer su contraseña';
			//$link				='http://compras.sanboni.edu.co/proveedores/publico/cambiar_password.php';			
			$link				='http://compras.sanboni.edu.co/proveedores/publico/cambiar_password.php?v='.encoded($validado).'&u='.encoded($com_id);
			$nombre_link		='Restablecer Contraseña';
			$RutaArchivoAdjunto =''; 			
      
		     include_once("../../../plantilla_correo.php");
			
			 if(!$mail->send()) 
			    {
					 $status='No_se_envio';
					echo 'Mailer Error: ' . $mail->ErrorInfo;
				} else {$status='ok';}
			
			
		}else{
			$status='Sin_registros';
		}

	$response = array(
					'status'       => $status,						
					'prov'         => $codigo_proveedor_oficial				
					);
	echo(json_encode($response)); 	
}


function encoded($ses){     
  $sesencoded = $ses;  
  $num = mt_rand(4,4);  
  for($i=1;$i<=$num;$i++)  
  {  
     $sesencoded = base64_encode($sesencoded);  
  }  
   
  $alpha_array =  array('Y','D','U','R','P','S','B','M','A','T','H');  
  $sesencoded = $sesencoded."+".$alpha_array[$num];  
  $sesencoded = base64_encode($sesencoded);  
  return $sesencoded;  
}//end of encoded function  

?>