<?php 
require_once('conexion/db.php');
/*	if (!isset($_SESSION)) {
  session_start();
}
if(!isset($_SESSION['MM_AccesoCorrectoApp']) || $_SESSION['MM_AccesoCorrectoApp'] != 'ACTIVO'){
  exit('acceso restringido');
}*/
$tipoGuardar='';
if(isset($_GET['tipoGuardar'])){
$tipoGuardar=$_GET['tipoGuardar'];
}

if($tipoGuardar=='EnviarCorreoCotizacion'){
$ultima_cotizacion = $_GET['cotizacion'];
$proveedor_cotizar = $_GET['proveedor'];

    $query_RsProveedor = " SELECT L.PRLICOTI COTIZACION FROM PROVEEDOR_LINKS L WHERE L.PRLICOTI = '".$ultima_cotizacion."'";
	$RsProveedor = mysqli_query($conexion,$query_RsProveedor) or die(mysqli_error($conexion));
	$row_RsProveedor = mysqli_fetch_array($RsProveedor);
    $totalRows_RsProveedor = mysqli_num_rows($RsProveedor);
	if($totalRows_RsProveedor>0){
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
/*
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
	*/
										
if($totalRows_RsProveedor>0){
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
			<td colspan='2'><span class='style1'><h1>Reciba un cordial saludo de parte de la Corporaci&oacute;n  Colegio San Bonifacio de las lanzas:</h1></span></td>
		  </tr>
		  <tr>
		    <td colspan=''>Se&ntilde;or(a):</td>
		  </tr>
		   <tr>
		    <td colspan=''>".$row_RsProveedor['NOMBRE']."</td>
		  </tr>
		</table>
		<br>
		<br>
		<p class='style7'><strong>Por Favor, se solicita muy amablemente cotizar los siguientes items atravez del PORTAL WEB DE COMPRAS de la  Corporaci&oacute;n  Colegio San Bonifacio de las lanzas: </strong></p>
		 <br>
		
		";
			
		$cuerpo = $cuerpo.
		"
		</td>
	</tr>
</table>";

			$tabladetalle='';
			if($totalRows_RsDetallesCorreo){
			 $ini = "
			 
			 <table width='100%' border='1'>
			                    <tr>
								  <td><h2>Articulos A Cotizar</h2></td>
								</tr>
			  ";
			  $fin = "</table>
			  <br>
			  <p class='style7'><strong>Favor cotizar ingresando  al siguiente link:</strong></p>
			   <p><span class='style1'><h1>Favor los valores  a continuacion deben contener impuestos incluidos (iva, retencion, descuentos ,etc..).
			   </h1></span></p>
		<p><a href='http://190.107.23.165/compras/do_cotizacion.php?key=".$cadena."'>INGRESAR A COTIZAR</a></p>
			 
<p>Ante cualquier inquietud, puede comunicarse con la encargada del Proceso de 
Contrataci&oacute;n - EDNA CONSUELO CARVAJAL OYUELA, en el teléfono 2670226 Ext.116 ó 311 538 3277, 
o email edna.carvajal@sanboni.edu.co, quien le brindará su acompañamiento y asesoría 
necesaria.
<br> <br>
 

 </p>
			 ";
			   
			  do{
			    $tabladetalle = $tabladetalle." <tr><td>".$row_RsDetallesCorreo['DESCRIPCION']."</td></tr>";
			    }while($row_RsDetallesCorreo = mysqli_fetch_array($RsDetallesCorreo));
			 $tabladetalle = $ini.$tabladetalle.$fin;
			}

         $cuerpo=$cuerpo.$tabladetalle;
		 
		require "includes/PHPMailer/PHPMailerAutoload.php";
		require "includes/PHPMailer/class.phpmailer.php";
		

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
		//$mail->SMTPSecure= "ssl"; //  Used instead of TLS when only POP mail is selected
		$mail->SMTPSecure= "tls"; //  Used instead of TLS when only POP mail is selected
		//$mail->Port = 465;        //  Used instead of 587 when only POP mail is selected
		$mail->Port = 587;        //  Used instead of 587 when only POP mail is selected
		$mail->Username = "compras@sanboni.edu.co";  // SMTP username
		//$mail->Password = "mc501234"; // SMTP password	
		//$mail->Password = $_SESSION['MM_GMAIL']; // SMTP password
        $mail->Password = 'septiembre14';
        $_SESSION['MM_GMAIL'] = 'gatuelo';
		$mail->From = "compras@sanboni.edu.co";
		$mail->FromName = "PORTAL DE COMPRAS - COLEGIO SAN BONIFACIO";
		
		
		$dirigido =$row_RsProveedor['CORREO'];
		//$dirigido = 'dyamid@gmail.com';
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

		$mail->Subject = $v_error." Envio de cotizaci&oacute;n codigo ".$ultima_cotizacion." y fecha de enviado ".$fechacorreoenviar;

		$mail->Body = $cuerpo;	
		//$exito = $mail->Send();		
		if (!$mail->send()) {
         echo "no enviado " . $mail->ErrorInfo;
		} else {
		
		 $query_RsupdateCot = "UPDATE COTIZACION SET COTIFORE = '1' WHERE `COTIZACION`.`COTICODI` = ".$ultima_cotizacion.";";
	     $RsupdateCot = mysqli_query($conexion,$query_RsupdateCot) or die(mysqli_error($conexion)); 
		 
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
		

	//echo($cuerpo);	
	//exit();
   }
}
?>