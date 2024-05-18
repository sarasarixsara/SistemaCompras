<?php
require_once('conexion/db.php');
//$infomacion_codi="dyamid";
//consulta de persona destinatario 

   $query_RsPersona = "SELECT REQUCORE CODIGO_REQUERIMIENTO 
						FROM DETALLE_REQU, REQUERIMIENTOS 
						WHERE REQUCODI='".$infomacion_codi."'";
	$RsPersona = mysqli_query($conexion,$query_RsPersona) or die(mysqli_error($conexion));
	$row_RsPersona = mysqli_fetch_array($RsPersona);
    $totalRows_RsPersona = mysqli_num_rows($RsPersona);

	$query_RsPersonaAprueba = "SELECT PERSNOMB PERSONA,
									  PERSCORR CORREO 
								FROM ROLES,USUARIOS,PERSONAS 
								WHERE USUAROL=ROLCODI 
								AND USUALOG=PERSUSUA 
								AND ROLCODI=3 ";
	$RsPersonaAprueba = mysqli_query($conexion,$query_RsPersonaAprueba) or die(mysqli_error($conexion));
	$row_RsPersonaAprueba = mysqli_fetch_array($RsPersonaAprueba);
    $totalRows_RsPersonaAprueba = mysqli_num_rows($RsPersonaAprueba);
	
	$cuerpo="
<style type='text/css'>
<!--
.style7 {font-weight: bold}
.style12 {font-family: Arial, Helvetica, sans-serif; font-size: 14px; }
.style18 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>

<!DOCTYPE html>
<html>
	
	<head>
	
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
</head>
<table width='100%'>
	<tr>
		<td width='613'>
		<style type='text/css'>
		<!--
		.style1 {
			font-size: 50px;
			font-weight: bold;
		}
		.style4 {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
		.style7 {font-size: 18px}
		-->
		</style>
		<table width='100%' border='0'>
		  <tr>
			<td width='41%'></td>
			<td width='59%'>&nbsp;</td>
		  </tr>
		  <tr>
			<td colspan='2'><span class='style1'><h2>Reciba un cordial saludo de parte de la Corporaci&oacute;n 
			Colegio San Bonifacio de las lanzas:<h2></span></td>
		  </tr>
		 </table>
		<p class='style7'><strong>BIENVENIDO ".$row_RsPersonaAprueba['PERSONA']." AL PORTAL DE COMPRAS,  le informamos que  el requerimiento N° ".$row_RsPersona['CODIGO_REQUERIMIENTO']."  a pasado a estado recibido
		Por favor ingrese a la plataforma  <a href='http://compras.sanboni.edu.co'>compras.sanboni.edu.co</a> para continuar con el proceso, Gracias por su atencion. 
      </p>
		
		";
			
		$cuerpo = $cuerpo.
		"
		</td>
	</tr>
</table>

</html>
	

";

require 'includes/PHPMailer/PHPMailerAutoload.php';

$mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Activación de la salida de depuración detallada

$mail->isSMTP();                                      // Gestor de correo configurado para utilizar SMTP
$mail->Host = 'smtp.gmail.com';                       // Especifique los servidores principales y de respaldo SMTP
$mail->SMTPAuth = true;                               // Habilitar la autenticación SMTP
$mail->Username = 'compras@sanboni.edu.co';           // nombre de usuario SMTP
$mail->Password = 'septiembre14';                     // contraseña SMTP 
$mail->SMTPSecure = 'ssl';                            // Habilitar el cifrado TLS, `también ssl` aceptado
$mail->Port = 465;                                    // Puerto TCP para conectarse a

$mail->From = 'compras@sanboni.edu.co';
$mail->FromName = 'compras.sanboni.edu.co';

$dirigido =$row_RsPersonaAprueba['CORREO'];

$mail->addAddress($dirigido, $row_RsPersonaAprueba['PERSONA']);     // Añadir un destinatario
$mail->addAddress('');     		             // Nombre es opcional
$mail->addReplyTo('', 'Information');
$mail->addCC('');
$mail->addBCC('');

$mail->WordWrap = 50;                                 // Set palabra envolver 50 caracteres
$mail->addAttachment('');   			      // Añadir archivos adjuntos
$mail->addAttachment('', '');    		     // nombre opcional
$mail->isHTML(true);                                  // Formato de correo electrónico Conjunto de HTML

$mail->Subject = 'Hola, Sr '.$row_RsPersonaAprueba['PERSONA'].' NUEVO REQUERIMIENTO POR APROBAR';
$mail->Body    = $cuerpo;
$mail->AltBody = $cuerpo;

if(!$mail->send()) {
    //echo 'El mensaje no pudo ser enviado.';
    //echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    //echo 'El Mensaje ha Sido Enviado';
}