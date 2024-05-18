<?php 
require_once('conexion/db.php');

$tipoGuardar='';
if(isset($_GET['tipoGuardar'])){
$tipoGuardar=$_GET['tipoGuardar'];
}

if($tipoGuardar=='EnviarCorreoCotizacion'){
		$ultima_cotizacion = $_GET['cotizacion'];
		$proveedor_cotizar = $_GET['proveedor'];

			$query_RsproveedorLink = " SELECT L.PRLICOTI COTIZACION FROM PROVEEDOR_LINKS L WHERE L.PRLICOTI = '".$ultima_cotizacion."'";
			$RsproveedorLink = mysqli_query($conexion,$query_RsproveedorLink) or die(mysqli_error($conexion));
			$row_RsproveedorLink = mysqli_fetch_array($RsproveedorLink);
			$totalRows_RsproveedorLink = mysqli_num_rows($RsproveedorLink);
				if($totalRows_RsproveedorLink>0)
				{
					exit('existe');
				}
			
			$query_RsDetallesCorreo = "SELECT D.CODECODI CODIGO,
											  D.CODECOTI CODIGO_COTIZACION,
											  D.CODEDETA CODIGO_DETALLE,
											  T.DEREDESC DESCRIPCION
										 FROM COTIZACION_DETALLE D,
											  COTIZACION          C,
											  DETALLE_REQU      T
									   WHERE  C.COTICODI = D.CODECOTI
										  AND D.CODEDETA = T.DERECONS							   
										  AND C.COTICODI = '".$ultima_cotizacion."'
										 ";
			$RsDetallesCorreo = mysqli_query($conexion,$query_RsDetallesCorreo) or die(mysqli_error($conexion));
			$row_RsDetallesCorreo = mysqli_fetch_array($RsDetallesCorreo);
			$totalRows_RsDetallesCorreo = mysqli_num_rows($RsDetallesCorreo);
			
			$query_RsProveedor = "SELECT P.PROVNOMB NOMBRE,
										 P.PROVCORR CORREO
									FROM PROVEEDORES P
								   WHERE P.PROVCODI = '".$proveedor_cotizar."'";
			$RsProveedor = mysqli_query($conexion,$query_RsProveedor) or die(mysqli_error($conexion));
			$row_RsProveedor = mysqli_fetch_array($RsProveedor);
			$totalRows_RsProveedor = mysqli_num_rows($RsProveedor);

		$caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890"; //posibles caracteres a usar
		$numerodeletras=15; //numero de letras para generar el texto
		$cadena = ""; //variable para almacenar la cadena generada
			for($i=0;$i<$numerodeletras;$i++)
			{
				$cadena .= substr($caracteres,rand(0,strlen($caracteres)),1); /*Extraemos 1 caracter de los caracteres 
			   entre el rango 0 a Numero de letras que tiene la cadena */
			}	
		$cadena=$cadena.'_'.$ultima_cotizacion;
												
		if($totalRows_RsProveedor>0){
				    $nombre_completo	= $row_RsProveedor['NOMBRE']; //"Nombre a quien va dirigido"
					$asunto				="Solicitud de Cotizacion";// lo que se desae en asunto
					$dirigido 			= $row_RsProveedor['CORREO'];//Correo a quien se va a enviar
					$imagen_cabecera	='compras.sanboni.edu.co/imagenes/9.png';//imagen de cabecera
					$RutaArchivoAdjunto =''; //ruta del archivo adjunto
					$tema               ='Por Favor, se solicita muy amablemente cotizar los siguientes items a través
										  del PORTAL WEB DE COMPRAS de la  Corporaci&oacute;n  Colegio San Bonifacio
										  de las lanzas: 
										  <br>
										  <br>
										  </p>';
			if($totalRows_RsDetallesCorreo>0)
			{							  
					$tema = $tema.'		  <table width="100%" align="center" style="background:#F3F3F3;padding:10px 20px;font-weight:bold; text-align:center;">
											<tbody>
												<tr>
													<td >
														<a style="color:#666666;">Articulos A Cotizar </a>
													</td>
												</tr>
												';
												
												
												 do{
					$tema = $tema.'              <tr>
													<td style="background:#FFFFFF;padding:10px 20px;font-weight:bold; text-align:justify;">
														<a style="color:#666666;">'.$row_RsDetallesCorreo['DESCRIPCION'].'</a>
													</td>
												</tr>';
			                                       }while($row_RsDetallesCorreo = mysqli_fetch_array($RsDetallesCorreo));
			        $tema = $tema.'      </tbody>
										  </table>';
			 }
					$tema = $tema.'     	<p>											
											<br>
											<br>
											<font color="#666666">
							
							<h2 align="justify">Favor los valores  a continuacion deben contener impuestos incluidos (Iva, Retencion, Descuentos ,etc..).</h2>
							<br>
											<h2>Favor cotizar ingresando  al siguiente link:</h2>
							</font>
										
											
											
											';//el tema del correo
					$link				='http://compras.sanboni.edu.co/do_cotizacion.php?key='.$cadena;
					$nombre_link		='INGRESAR A COTIZAR';
					
				require_once('plantilla_correo.php');	
				//!$mail->send()
					if(!$mail->send()) 
					{
						echo "no enviado " . $mail->ErrorInfo;
					} else {
							 $query_RsupdateCot = "UPDATE COTIZACION SET COTIFORE = '1' WHERE `COTICODI` = ".$ultima_cotizacion."";
							 $RsupdateCot = mysqli_query($conexion,$query_RsupdateCot) or die(mysqli_error($conexion)); 
							 
							  do{							 
							 $query_RsupdateEstaDet = "UPDATE DETALLE_REQU SET DEREAPRO = '15' WHERE DERECONS = '".$row_RsDetallesCorreo['CODIGO_DETALLE']."'";
					         $RsupdateEstaDet = mysqli_query($conexion,$query_RsupdateEstaDet) or die(mysqli_error($conexion));
							 }while($row_RsDetallesCorreo = mysqli_fetch_array($RsDetallesCorreo));
				 
							 $query_RsInsertLink = "INSERT INTO PROVEEDOR_LINKS (
																		PRLICODI,
																		PRLICOTI,
																		PRLILINK
																	   )
																	   VALUES
																	   (
																		NULL,
																		'".$ultima_cotizacion."',
																		'".$cadena."'
																	   )
																		";
						    $RsInsertLink = mysqli_query($conexion,$query_RsInsertLink) or die(mysqli_error($conexion)); 
							echo "exito!";
							}
		   }
}


?>