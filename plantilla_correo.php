<?php
require_once('conexion/db.php');


// Variables de correo electronico
/*
	$nombre_completo	="alexandra baron";
	$asunto				="Solicitud de Cotizacion";
	$dirigido 			='dyamid@gmail.com';
	$imagen_cabecera	='http://compras.sanboni.edu.co/imagenes/9.png';
	$tema               ='el tema a describir';
	$link				='www.sena.edu.co';
	$nombre_link		='cotizar';
	$RutaArchivoAdjunto = '';
    $nombre_archivo     = '';
*/


//Plantilla de correo electronico colegio san bonifacio de las lanzas

	$inicio_html='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			   <html xmlns="http://www.w3.org/1999/xhtml">';
	
	$head='  <head>
				<meta name="viewport" content="width=device-width" />
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
				<title>email</title>
				<link rel="stylesheet" type="text/css" href="" />
				</head>';
	
	
	$body_abre='<body bgcolor="#3BAECB">
	             
	             <div style="font-family:sans-serif;text-align:left;font-size:16px;color:#4d4d4d" marginheight="0" marginwidth="0">
				 <table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#cccccc" align="center">
                 <tbody>
                 <tr>
                 <td valign="top" style="padding:0;text-align:center;color:#ffffff">				 
				 <table width="570" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff" align="center">
				  <tbody>
				  <tr bgcolor="#ffffff">
						<td valign="top" style="padding:0;text-align:center;color:#ffffff">
							<img width="100%" src="'.$imagen_cabecera.'"  alt="Corporacion Colegio San Bonifacio de las Lanzas"
							style="vertical-align:top tabindex="0""></img>
						</td>
					</tr>
					<tr bgcolor="#ffffff">
						<td height="15px"></td>
					</tr>
					<tr>
						<td style="padding:0px 40px 0px 40px">
				
				 ';
	
	$cabeza='
				<!-- HEADER -->
					
							<p>
							<font color="#666666">
							
							<h3>Reciba un cordial saludo: '.$nombre_completo.'</h3>
							</font>
							</p>
			     <!-- /HEADER -->
				';
	$tronco='<!-- BODY -->
	                       <p style="text-align:justify;">
						   <font color="#666666">
							'.$tema.'
							</font>
							</p>
							<table width="60%" align="center">
								<tbody>
									<tr>
										<td style="background:#8B1314;padding:10px 20px;font-weight:bold;text-align:center;">
											<a target="_blank" href="'.$link.'" style="color:#ffffff">
                                                                               '.$nombre_link.'
											</a>
										</td>
									</tr>
								</tbody>
							</table>
							<p>
							</p>
						
								   
			<!-- /BODY -->
			';
    $pies='<!-- FOOTER -->
	</td>
					</tr>
	<tr bgcolor="#ffffff">
						<td height="20px"> </td>
					</tr>
					<tr bgcolor="#f4f3ea">
						<td>
							<table width="90%" cellspacing="0" cellpadding="0" border="0" align="center">
								<tbody>
									<tr>
										<td align="justify">
										<font color="#666666">
										Ante cualquier inquietud, puede comunicarse con el encargado  del Proceso de 
										Contrataci&oacute;n - KAROL VILLALOBOS, en el tel&#233;fono 2770770 
										Ext.116 &#243; 3115383277, o   email   compras@sanboni.edu.co, quien le 
										brindar&#225; su acompa&#241;amiento y asesor&#237;a 
										necesaria.
										<br>
								        <br>
										Este mensaje se envia en forma automatica por el Sistema de Compras
										de La Corporacion Colegio San bonifacio de las lanzas Ibague.
										<br>
										<br>
										Correo desatendido: Por favor no responda a la direccion de correo 
										electronico que envia este mensaje, dicha cuenta no es revisada
										por ningun funcionario de nuestra entidad. Este mensaje es
										informativo.
										<br>
										<br>
										Los acentos y tildes de este correo han sido omitidos intencionalmente
	:								con el objeto de evitar inconvenientes en la lectura del mismo. 
										<font 
										</td>
									</tr>
									
								</tbody>
							</table>
						</td>
					</tr>
					
					</tbody>
				</table>
                <!-- /FOOTER -->
			'; 
	
    $body_cierra='<table width="570" height="50" cellspacing="0" cellpadding="0" border="0" bgcolor="#eeeeed" align="center">www.sanboni.edu.co</table>
				<div style="min-height:25px"></div>				
				</td>
				</tr>
				</tbody>
				</table>
				</div>
				</body>';
    $fin_html='</html>';

	$cuerpo=$inicio_html.$head.$body_abre.$cabeza.$tronco.$pies.$body_cierra.$fin_html ;

// Configuracion de envio correo electronico	

	require 'C:/inetpub/wwwroot/SistemaCompras/includes/PHPMailer/PHPMailerAutoload.php';

	$mail = new PHPMailer;

	//$mail->SMTPDebug = 3;                               			// Activación de la salida de depuración detallada

	$mail->isSMTP();                                      			// Gestor de correo configurado para utilizar SMTP
	$mail->Host 		= "smtp.gmail.com";                       	// Especifique los servidores principales y de respaldo SMTP
	$mail->SMTPAuth 	= true;                              	    // Habilitar la autenticación SMTP
	$mail->Username 	= "compras@sanboni.edu.co";           		// nombre de usuario SMTP
	$mail->Password 	= "Sanboni2022";                     		// contraseña SMTP 
	$mail->SMTPSecure 	= 'ssl';                            		// Habilitar el cifrado TLS, `también ssl` aceptado
	$mail->Port 		= 465;                                    	// Puerto TCP para conectarse a

	$mail->From 		= 'compras@sanboni.edu.co';
	$mail->FromName 	= 'San Bonifacio De Las Lanzas';

	$mail->addAddress($dirigido, $nombre_completo);     			// Añadir un destinatario
	$mail->addAddress('');     		             					// Nombre es opcional
	$mail->addReplyTo('', 'Information');
	$mail->addCC('');
	$mail->addBCC('');

	$mail->WordWrap = 50;                                 			// Set palabra envolver 50 caracteres
	if($RutaArchivoAdjunto != ''){
		$mail->addAttachment($RutaArchivoAdjunto, $nombre_archivo);   			      	// Añadir archivos adjuntos
	}
										// nombre opciona
    $mail->CharSet = 'UTF-8';										
	$mail->isHTML(true);                                  			// Formato de correo electrónico Conjunto de HTML

	$mail->Subject = $asunto;
	$mail->Body    = $cuerpo;
	$mail->AltBody = $cuerpo;

	//echo($cuerpo);
	/*
	if(!$mail->send()) {
		echo 'El mensaje no pudo ser enviado.';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
		echo 'El Mensaje ha Sido Enviado';
	}
	*/