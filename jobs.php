<?php
require_once('conexion/db.php');
 
 $totalRows_RsProveedor=4;
 
 	if($totalRows_RsProveedor>0)
	{
				    $nombre_completo	= "diego yamid cruz"; //"Nombre a quien va dirigido"
					$asunto				="Solicitud de Cotizacion";// lo que se desae en asunto
					$dirigido 			= "dyamid@gmail.com";//Correo a quien se va a enviar
					$imagen_cabecera	='http://190.107.23.165/compras/imagenes/9.png';//imagen de cabecera
					$RutaArchivoAdjunto =''; //ruta del archivo adjunto
					$tema               ='Por Favor, se solicita muy amablemente cotizar los siguientes items a través
										  del JAJAJJAJA LO LOGRASTEde la  Corporaci&oacute;n  Colegio San Bonifacio
										  de las lanzas: 
										  <br>
										  <br>
										  </p>';
		
					$tema = $tema.'     	<p>											
											<br>
											<br>
											<font color="#666666">
							
							<h2 align="justify">Favor los valores  a continuacion deben contener impuestos incluidos (Iva, Retencion, Descuentos ,etc..).</h2>
							<br>
											<h2>Favor cotizar ingresando  al siguiente link:</h2>
							</font>
										
											
											
											';//el tema del correo
					$link				='http://190.107.23.165/compras/do_cotizacion.php?key=';
					$nombre_link		='INGRESAR A COTIZAR';
					
				require_once('plantilla_correo.php');	
				//!$mail->send()
					if(!$mail->send()) 
					{
						echo "no enviado " . $mail->ErrorInfo;
					} else {
							 
							echo "exito!";
							}
		   
}

?>