<?php 
require_once('conexion/db.php');
	if (!isset($_SESSION)) {
  session_start();
}
if(!isset($_SESSION['MM_AccesoCorrectoApp']) || $_SESSION['MM_AccesoCorrectoApp'] != 'ACTIVO'){
  exit();
}
$consecutivo_requerimiento =97;

    $query_RsDetallesRequerimiento = "SELECT R.REQUCODI CONSECUTIVO_REQUERIMIENTO,
	                                  R.REQUCORE CODIGO_REQUERIMIENTO,
									  R.REQUIDUS USUARIO_SOLICITA
								 FROM REQUERIMIENTOS R
             				WHERE R.REQUCODI = '".$consecutivo_requerimiento."'
                                 ";
	$RsDetallesRequerimiento = mysqli_query($conexion,$query_RsDetallesRequerimiento) or die(mysqli_error($conexion));
	$row_RsDetallesRequerimiento = mysqli_fetch_array($RsDetallesRequerimiento);
    $totalRows_RsDetallesRequerimiento = mysqli_num_rows($RsDetallesRequerimiento);

   if($totalRows_RsDetallesRequerimiento>0){
		$query_RsDetallesCorreo = "SELECT P.PERSNOMB NOMBRE,
										  P.PERSCORR CORREO
									 FROM PERSONAS P
								  WHERE P.PERSID = '".$row_RsDetallesRequerimiento['USUARIO_SOLICITA']."'
									 ";
		$RsDetallesCorreo = mysqli_query($conexion,$query_RsDetallesCorreo) or die(mysqli_error($conexion));
		$row_RsDetallesCorreo = mysqli_fetch_array($RsDetallesCorreo);
		$totalRows_RsDetallesCorreo = mysqli_num_rows($RsDetallesCorreo);
	}
										
if($totalRows_RsDetallesCorreo>0){
echo('entra al cuerpo');
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

<table width='100%'>
	<tr>
		<td width='613'>
		<style type='text/css'>
		<!--
		.style1 {
			font-size: 24px;
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
			<td colspan='2'><span class='style1'>Colegio San Bonifacion de las Lanzas</span></td>
		  </tr>
		  <tr>
		    <td colspan=''>Se&ntilde;or(a);</td>
		    <td>".$row_RsDetallesCorreo['NOMBRE']."</td>
		  </tr>
		</table>
		<p class='style7'><strong>Se le informa que su requerimiento con c&oacute;digo de solicitud ".$row_RsDetallesRequerimiento['CODIGO_REQUERIMIENTO']." no fue recibido:</strong></p>
		<p class='style7'><strong>ingrese nuevamente al requerimiento con el c&oacute;digo anterior y realize los cambios solicitados:</strong></p>
		<p>Inicie sesi&oacute; con su respectivo usuario y contraseña en la plataforma de compras </p>
		<a href='sanboni.edu.co/micro_sitio/compras/'>Compras</a>
		
		";
			
		$cuerpo = $cuerpo.
		"
		</td>
	</tr>
</table>";

		 require 'includes/PHPMailerAutoload.php';
		require "includes/class.phpmailer.php";
		

		$cab  = "MIME-Version: 1.0\r\n";
		$cab .= 'Content-type: text/html; CHARSET=utf-8\r\n'.
					 'From: dyamid@gmail.com\r\n' .
					 'Reply-To: dyamid@gmail.com\r\n' .
					 'X-Mailer: PHP/' . phpversion();

		$dirigido = "";

		$v_error ="";


		$mail = new phpmailer();
		$mail->IsSMTP();
		$mail->SMTPDebug = 2;
		$mail->SMTPAuth = true;   // turn on SMTP authentication
		$mail->Host = "smtp.gmail.com"; // specify main and backup server
		$mail->SMTPSecure= "tls"; //  Used instead of TLS when only POP mail is selected
		$mail->Port = 587;        //  Used instead of  when only POP mail is selected 465
		$mail->Username = "dyamid@gmail.com";  // SMTP username
		//$mail->Password = "mc501234"; // SMTP password	
		//$mail->Password = $_SESSION['MM_GMAIL']; // SMTP password
        $mail->Password = 'gatuelo';
        $_SESSION['MM_GMAIL'] = 'gatuelo';
		$mail->From = "dyamid@gmail.com";
		$mail->FromName = "PORTAL DE COMPRAS - COLEGIO SAN BONIFACIO";
		
		
		$dirigido =$row_RsDetallesCorreo['CORREO'];
		//$dirigido =$dirigido.$row_RsDetalleCorreo['CORREO'];
		//$dirigido = $dirigido.";";

		$tok = strtok ($dirigido,";");
		while ($tok) {
		  $mail->AddAddress($tok);
		  $tok = strtok (";");
		}
		
		$fechacorreoE = time (); 
		//echo date ( "h:i:s" , $fechacorreo );
		$fechacorreoenviar=date ( "h:i:s" , $fechacorreoE);
				
		
		
		$mail->IsHTML(true);

		$mail->Subject = $v_error." Info sobre requerimiento no recibido c&oacute;digo ".$row_RsDetallesRequerimiento['CODIGO_REQUERIMIENTO'];

		$mail->Body = $cuerpo;	
		$exito = $mail->Send();		
		if ($exito) {
			echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
			echo "Message sent!";
		}
	echo($cuerpo);	
	//exit();
   }

?>