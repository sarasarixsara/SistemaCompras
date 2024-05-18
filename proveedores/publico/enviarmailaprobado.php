<?php 
require_once('../../conexion/db.php');
/*var_dump($_POST);
exit();*/
$id_proveedor = '';
if(isset($_POST['id_proveedor']) && $_POST['id_proveedor'] != ''){
	$id_proveedor = $_POST['id_proveedor'];
}
$post_form = '';
if(isset($_POST['post_form']) && $_POST['post_form'] != ''){
	$post_form = $_POST['post_form'];
}
$incluye_archivo = '';
if(isset($_POST['incluye_archivo']) && $_POST['incluye_archivo'] != ''){
	$incluye_archivo = $_POST['incluye_archivo'];
}

if ($post_form == '101' && $id_proveedor != '')
{
	$query_RsGetDominio = "SELECT * FROM PARAMETROS WHERE PARACODI = '10'";
	$RsGetDominio = mysqli_query($conexion,$query_RsGetDominio) or die(mysqli_error($conexion));
	$row_RsGetDominio = mysqli_fetch_array($RsGetDominio);
	$dominio = $row_RsGetDominio['PARAVALOR'];
	
	$query_RsDetalleProveedor = "SELECT * FROM PROVEEDORES_TEMPORAL WHERE PROVCODI = '".$id_proveedor."'";
	$RsDetalleProveedor = mysqli_query($conexion,$query_RsDetalleProveedor) or die(mysqli_error($conexion));
	$row_RsDetalleProveedor = mysqli_fetch_array($RsDetalleProveedor);
	$totalRows_RsDetalleProveedor = mysqli_num_rows($RsDetalleProveedor);
	$ext=date("Ymd_His");
	
	if($incluye_archivo == '1'){
 	$query_RsParametroRuta = "SELECT PARAVALOR FROM PARAMETROS WHERE PARANOMB = 'RUTAARCHIVO_NAS'";
	$RsParametroRuta = mysqli_query($conexion,$query_RsParametroRuta) or die(mysqli_error($conexion));
	$row_RsParametroRuta = mysqli_fetch_array($RsParametroRuta);
	$carpeta = '/proveedores/publico/formatoproveedor/';
	//$rutaArchivos = 'A:/wamp64/www/SistemaCompras/';
	$rutaArchivos = $row_RsParametroRuta['PARAVALOR'].$carpeta;
	


	//$rutaArchivos = '//175.176.0.6/compras/';
	//$rutaArchivos = 'C:/wamp/www/compras/archivos_usuario_g/';

		if (is_uploaded_file($_FILES['archivo1']['tmp_name']))
		{
			$upload_archivo_dir = $rutaArchivos;
			$nombre_archivo = str_replace("Ñ", "N",$_FILES['archivo1']['name']);
			$nombre_archivo = str_replace("ñ", "n",$nombre_archivo);
			$nombre_archivo = $ext."-".$nombre_archivo;
			$tipo_archivo = $_FILES['archivo1']['type'];

			
				if($totalRows_RsDetalleProveedor > 0){		
					$nombre_completo	= $row_RsDetalleProveedor['PROVNOMB'];
					$asunto				="Registro de proveedor Creado ".$ext;
					$dirigido 			= $row_RsDetalleProveedor['PROVCORR'];
					$imagen_cabecera	= $dominio.'imagenes/9.png';

					$tema               = "Su registro de proveedor ha sido creado, favor responder este correo con el documento adjunto correctamente diligenciado, Su acceso ya se encuentra activo puede ingresar con su usuario y clave dando click en ingresar";
					$link				= $dominio.'proveedores/publico/proveedorpublico.php';
					$nombre_link		='Ingresar';	
					$RutaArchivoAdjunto	= $_FILES['archivo1']['tmp_name'];
					$nombre_archivo     = $_FILES['archivo1']['name'];		
					include_once("../../plantilla_correo.php");
					if(!$mail->send()) 
					{
						echo "no enviado " . $mail->ErrorInfo;
					} else {
						echo "exito!";
							}
			}
		}
	}else{
		
				if($totalRows_RsDetalleProveedor > 0){		
					$nombre_completo	= $row_RsDetalleProveedor['PROVNOMB'];
					$asunto				="Registro de proveedor Creado ".$ext;
					$dirigido 			= $row_RsDetalleProveedor['PROVCORR'];
					$imagen_cabecera	=$dominio.'imagenes/9.png';

					$tema               = "Su registro de proveedor ha sido creado, Su acceso ya se encuentra activo puede ingresar con su usuario y clave dando click en ingresar";
					$link				=$dominio.'proveedores/publico/proveedorpublico.php';
					$nombre_link		='Ingresar';	
					$RutaArchivoAdjunto	= '';
					$nombre_archivo     = '';		
					include_once("../../plantilla_correo.php");
					if(!$mail->send()) 
					{
						echo "no enviado " . $mail->ErrorInfo;
					} else {
						echo "exito!";
							}
			}		
		
	} 
	$redireccionar = "location: ".$dominio."home.php?page=proveedores_solicitados&incluye_archivo=".$incluye_archivo;
   	header($redireccionar);
}else{
	$redireccionar = "location: ".$dominio."home.php?page=proveedores_solicitados&errno=no_data";
   	header($redireccionar);
}
