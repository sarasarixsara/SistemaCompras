<!DOCTYPE HTML>
<head>
<title>info</title>
<meta charset="utf-8">
	<!--<link rel="stylesheet" type="text/css" href="css/page.css"/>-->
	<style type="text/css">
	</style>
</head>
<?php
require_once('conexion/db.php');  
$msj ="Su envio de cotización ha sido procesada correctamente";
$cotizacion = $_GET['cot'];

$query_RsProveedor = "SELECT `COTIORDE` ORDEN,
							 `PROVNOMB` NOMBRE
					  FROM   `cotizacion`
					         ,proveedores 
					  WHERE `COTICODI`=".$cotizacion."
					    and `PROVCODI`=`COTIPROV` ";
			$RsProveedor = mysqli_query($conexion,$query_RsProveedor) or die(mysqli_error($conexion));
			$row_RsProveedor = mysqli_fetch_array($RsProveedor);
			$totalRows_RsProveedor = mysqli_num_rows($RsProveedor);
			

			
			$query_RsRol = "SELECT `PERSCORR` CORREO,
                      PERSNOMB NOMBRE
                 FROM `roles`,
				 usuarios,
				 personas 
				 where rolcodi=2 
				 and rolcodi=`USUAROL` 
				 and USUALOG=`PERSUSUA` ";
			$RsRol = mysqli_query($conexion,$query_RsRol) or die(mysqli_error($conexion));
			$row_RsRol = mysqli_fetch_array($RsRol);
			$totalRows_RsRol = mysqli_num_rows($RsRol);			


if($cotizacion != ''){
				    $nombre_completo	= "KAROL VILLALOBOS"; //" $row_RsRol['NOMBRE'] Nombre a quien va dirigido"
					$asunto				="Llego nueva cotizacion- Compras";// lo que se desae en asunto
					$dirigido 			= "karol.villalobos@sanboni.edu.co";//$row_RsRol['CORREO'] Correo a quien se va a enviar
					$imagen_cabecera	='compras.sanboni.edu.co/imagenes/9.png';//imagen de cabecera
					$RutaArchivoAdjunto =''; //ruta del archivo adjunto
					$tema               ='Por Favor, se solicita muy amablemente revisar la nueva Cotizacion de '.$row_RsProveedor['NOMBRE'].' a través
										  del PORTAL WEB DE COMPRAS de la  Corporaci&oacute;n  Colegio San Bonifacio
										  de las lanzas: 
										  <br>
										  <br>
										  </p>';
		
					$tema = $tema.'     	<p>											
											
											<h2>Favor ingresar  al siguiente link:</h2>
							</font>
										
											
											
											';//el tema del correo
					$link				='compras.sanboni.edu.co/home.php?page=orden&codorden='.$row_RsProveedor['ORDEN'];
					$nombre_link		='REVISAR COTIZACION';
					
				require_once('plantilla_correo.php');	
				//!$mail->send()
					if(!$mail->send()) 
					{
						echo "no enviado " . $mail->ErrorInfo;
					} else {?>
							<div id="contenedor" style=" width:967px;  margin: 0 auto; min-height:400px; box-shadow: 1px 1px 3px 1px #BB9696; background:#FCFCFC; border: solid 1px #ccc; border-radius:5px;">
<p style="margin:15px 25px 20px 25px; font-size:30px;"><?php echo($msj);?></p>
</div>
						<?php	}
		   }

?>

</form>
</html>