<?php
require_once('../../conexion/db.php');

//Variable Crear Usuarios
$nueva_clave='';
if(isset($_POST['nueva_clave'])&&$_POST['nueva_clave']!='')
{
$nueva_clave=$_POST['nueva_clave'];
}

$nueva_clave_confirm='';
if(isset($_POST['nueva_clave_confirm'])&&$_POST['nueva_clave_confirm']!='')
{
$nueva_clave_confirm=$_POST['nueva_clave_confirm'];
}

$tipo_reset='';
if(isset($_GET['v']) && $_GET['v']!=''){
	$tipo_reset=$_GET['v'];
}

$codigo_proveedor='';
if(isset($_GET['n']) && $_GET['n']!=''){
	$codigo_proveedor=$_GET['n'];
}

//tipo de manejo de datos
$tipoGuardar='';
if(isset($_GET['tipoGuardar']) && $_GET['tipoGuardar']!=''){
$tipoGuardar=$_GET['tipoGuardar'];
}




$afectado = 0;
if($tipoGuardar=='Actualizar'){
	
	    $query_RsConsultaProveedor     = "SELECT * FROM PROVEEDORES WHERE PROVCODI = '".$codigo_proveedor."'";
		$RsConsultaProveedor           = mysqli_query($conexion,$query_RsConsultaProveedor) or die(mysqli_error($conexion));
		$row_RsConsultaProveedor       = mysqli_fetch_array($RsConsultaProveedor);
		$totalRows_RsConsultaProveedor = mysqli_num_rows($RsConsultaProveedor);

		if($tipo_reset==2 && ($nueva_clave == $nueva_clave_confirm))
		{ 
			
		  if($row_RsConsultaProveedor['PROVUSUA'] == '')
		    {  
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
															'".$row_RsConsultaProveedor['PROVREGI']."',
															AES_ENCRYPT( '".$nueva_clave_confirm."', 'mc$90ui1' ),
															'7',
															'".$nueva_clave_confirm."',
															'".$nueva_clave_confirm."',
															'0'
															)";

													// exit($query_RsInsertarInsertarUsuario);
				$RsInsertarUsuario = mysqli_query($conexion,$query_RsInsertarUsuario) or die(mysqli_error($conexion));

				$query_RsUltInsert = "SELECT LAST_INSERT_ID() DATO";
				$RsUltInsert = mysqli_query($conexion,$query_RsUltInsert) or die(mysqli_error($conexion));
				$row_RsUltInsert = mysqli_fetch_array($RsUltInsert);
				$ultimo_usuario_creado = $row_RsUltInsert['DATO'];
                   
					if($ultimo_usuario_creado > 0){
					
						$query_RsUpdateProveedor = "UPDATE PROVEEDORES SET PROVESSO = 1,
																		   PROVUSUA = '".$row_RsConsultaProveedor['PROVREGI']."'																		 
												where PROVCODI = '".$codigo_proveedor."'";
												//echo($query_RsUpdateProveedor);
					    $RsUpdateProveedor = mysqli_query($conexion,$query_RsUpdateProveedor) or die(mysqli_connect_error($conexion)); 

						$query_RsDatosUsuario     = "SELECT PROVNOMB NOMBRE_PROVEEDOR, PROVCORR EMAIL, PROVUSUA USUARIO, 
						                               CASE PROVTIID
                                                           WHEN  1 THEN 'Cédula de Ciudadania'
                                                           WHEN  2 THEN 'Número de identificación tributario'
														   ELSE 'No tiene tipologia favor informar a compras'
														END TIPO_NIT
													   FROM USUARIOS, PROVEEDORES
													  WHERE USUACODI = '".$ultimo_usuario_creado."'
						                                AND USUALOG  = PROVUSUA";
						$RsDatosUsuario           = mysqli_query($conexion,$query_RsDatosUsuario) or die(mysqli_error($conexion));
						$row_RsDatosUsuario       = mysqli_fetch_array($RsDatosUsuario);
						$totalRows_RsDatosUsuario = mysqli_num_rows($RsDatosUsuario);
						
                        if($totalRows_RsDatosUsuario > 0){
						
							$nombre_completo	= $row_RsDatosUsuario['NOMBRE_PROVEEDOR'];
							$asunto				= $row_RsDatosUsuario['NOMBRE_PROVEEDOR'].", tu contraseña se ha restablecido satisfactoriamente";
							$dirigido 			= $row_RsDatosUsuario['EMAIL'];
							$imagen_cabecera	='http://compras.sanboni.edu.co/imagenes/9.png';
							$tema               ='Has cambiado tu contraseña de proveedor  satisfactoriamente.<br>
							                      Agradecemos ingrese a el portal para actualizar los datos almacenados <br>
							                      <b>Usuario:</b>'.$row_RsDatosUsuario['USUARIO'].'<br>
							 					  ¡Gracias por ser parte de nuestra institución!';							
							$link				='http://compras.sanboni.edu.co/proveedores/publico/proveedorpublico.php';
							$nombre_link		='Ingresar a actualizar';
							$RutaArchivoAdjunto =''; 			

							include_once("../../plantilla_correo.php");

							if(!$mail->send()) {
								$afectado='No_se_envio';
								echo 'Mailer Error: ' . $mail->ErrorInfo;
								} else {			
									$afectado='ok';	
									//echo($afectado);			
									}	
										}else{
									  $status='Sin_registros';
								}
						}
			}

   }

   if($tipo_reset==1 && ($nueva_clave == $nueva_clave_confirm))
   {
	   $key_usuario=$codigo_proveedor;
	   $cod_proveedor=""; 
	   $key=explode("_",$codigo_proveedor);
	   if(count($key)==2){
          $cod_proveedor=$key[1];
	   }
	   
	   $query_RsDatosUsuario     = "SELECT PROVNOMB NOMBRE_PROVEEDOR, PROVCORR EMAIL, PROVUSUA USUARIO, USUACODI CODIGO_USUARIO						                              
													   FROM USUARIOS, PROVEEDORES
													  WHERE PROVCODI= '".$cod_proveedor."'
													    AND USUAKEY = '".$key_usuario."'
														AND USUALOG = PROVUSUA
														AND USUAROL	= 7"  ;
		                             //echo($query_RsDatosUsuario);
		$RsDatosUsuario           = mysqli_query($conexion,$query_RsDatosUsuario) or die(mysqli_error($conexion));
		$row_RsDatosUsuario       = mysqli_fetch_array($RsDatosUsuario);
		$totalRows_RsDatosUsuario = mysqli_num_rows($RsDatosUsuario);

		if($totalRows_RsDatosUsuario > 0)
		{
			
			$nombre_completo	= $row_RsDatosUsuario['NOMBRE_PROVEEDOR'];
							$asunto				= $row_RsDatosUsuario['NOMBRE_PROVEEDOR'].", tu contraseña se ha restablecido satisfactoriamente";
							$dirigido 			= $row_RsDatosUsuario['EMAIL'];
							$imagen_cabecera	='http://compras.sanboni.edu.co/imagenes/9.png';
							$tema               ='Has cambiado tu contraseña de proveedor  satisfactoriamente.<br>							                      
							                      <b>Usuario:</b>'.$row_RsDatosUsuario['USUARIO'].'<br>
							 					  ¡Gracias por ser parte de nuestra institución!';
							$link				='http://compras.sanboni.edu.co/proveedores/publico/proveedorpublico.php';
							//$link				='http://localhost/sistemacompras/proveedores/publico/proveedorpublico.php';
							$nombre_link		='Ingresar como proveedor';
							$RutaArchivoAdjunto =''; 			

							include_once("../../plantilla_correo.php");

							if(!$mail->send()) {
								$afectado='No_se_envio';
								echo 'Mailer Error: ' . $mail->ErrorInfo;
								} else {	
									$query_RsUpdateResetUsuario = "UPDATE USUARIOS  SET  USUAPASS = AES_ENCRYPT( '".$nueva_clave_confirm."', 'mc$90ui1' ),
															                             USUAKEY  = NULL																	 
										                                          WHERE  USUACODI = '".$row_RsDatosUsuario['CODIGO_USUARIO']."'";
																//echo($query_RsUpdateResetUsuario);
			                        $RsUpdateResetUsuario = mysqli_query($conexion,$query_RsUpdateResetUsuario) or die(mysqli_connect_error($conexion)); 
					
									$afectado='ok';	
									//echo($afectado);			
									}	
			
		}else{
			$afectado='No_se_envio';
		}
		

   }
   


	
           			//exit($query_RsEditar);
 if($afectado == 'ok'){ 
?>
<body>
<script type="text/javascript">
window.location="cambiar_password.php?msg=1";
</script>
</body>
<?php
 }

 if($afectado == 'No_se_envio'){
	 ?>
<body>
<script type="text/javascript">
window.location="cambiar_password.php?msg=2";
</script>
</body>	 
	 <?php
 }


}
?>