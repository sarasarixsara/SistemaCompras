<?php 
require_once('conexion/db.php');
	if (!isset($_SESSION)) {
  session_start();
}
if(!isset($_SESSION['MM_AccesoCorrectoApp']) || $_SESSION['MM_AccesoCorrectoApp'] != 'ACTIVO'){
  exit('acceso restringido');
}
	
	$query_RsPersona = "SELECT R.REQUCORE REQUERIMIENTO,
							   P.PERSNOMB NOMBRE,
                               P.PERSCORR CORREO	
						FROM PERSONAS P, 
						REQUERIMIENTOS R 
						WHERE R.REQUCODI = '".$infomacion_codi."' 
						AND P.PERSID = R.REQUIDUS";
	$RsPersona = mysqli_query($conexion,$query_RsPersona) or die(mysqli_error($conexion));
	$row_RsPersona = mysqli_fetch_array($RsPersona);
    $totalRows_RsPersona = mysqli_num_rows($RsPersona);

$caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890"; //posibles caracteres a usar
$numerodeletras=15; //numero de letras para generar el texto
$cadena = ""; //variable para almacenar la cadena generada
for($i=0;$i<$numerodeletras;$i++)
{
    $cadena .= substr($caracteres,rand(0,strlen($caracteres)),1); /*Extraemos 1 caracter de los caracteres 
   entre el rango 0 a Numero de letras que tiene la cadena */
}	
$cadena=$cadena.'_'.$ultima_cotizacion;
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
										
if($totalRows_RsPersona>0){
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
			<td colspan='2'><span class='style1'><h2>Reciba un cordial saludo de parte de la Corporaci&otilde;n Colegio San Bonifacio de las lanzas:<h2></span></td>
		  </tr>
		  <tr>
		    <td colspan=''>Se&ntilde;or(a):".$row_RsPersona['NOMBRE']."</td>
		  </tr>
		</table>
		<p class='style7'><strong>Se le informa que su requrimiento N°".$row_RsPersona['REQUERIMIENTO']."  enviado el dia se encuentra en estado no recibido Por:</strong></p>
		<p class='style7'><strong>Favor  ingresar y realizar las correciones correspondientes
		puede hacerlo ingresando al siguiente link:</strong></p>
		<p><a href='http://www.sanboni.edu.co/micro_sitio/compras/home.php?page=solicitud&codreq=67'>Ingresar a cotizar</a></p>
		
		";
			
		$cuerpo = $cuerpo.
		"
		</td>
	</tr>
</table>";
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
		$mail->SMTPAuth = true;   // turn on SMTP authentication
		$mail->Host = "smtp.gmail.com"; // specify main and backup server
		$mail->SMTPSecure= "ssl"; //  Used instead of TLS when only POP mail is selected
		$mail->Port = 465;        //  Used instead of 587 when only POP mail is selected
		$mail->Username = "dyamid@gmail.com";  // SMTP username
		//$mail->Password = "mc501234"; // SMTP password	
		//$mail->Password = $_SESSION['MM_GMAIL']; // SMTP password
        $mail->Password = 'gatuelo';
        $_SESSION['MM_GMAIL'] = 'gatuelo';
		$mail->From = "dyamid@gmail.com";
		$mail->FromName = "PORTAL DE COMPRAS - COLEGIO SAN BONIFACIO";
		
		
		$dirigido =$row_RsPersona['CORREO'];
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

		$mail->Subject = $v_error." Envio de cotización codigo  y fecha de enviado ";

		$mail->Body = $cuerpo;	
		$exito = $mail->Send();		

	//echo($cuerpo);	
	//exit();
   }

?>