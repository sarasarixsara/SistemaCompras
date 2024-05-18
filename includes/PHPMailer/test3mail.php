<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; CHARSET=utf-8" />
<title>Documento sin t&iacute;tulo</title>
</head>

<body>

<?php
require 'PHPMailerAutoload.php';
require("class.phpmailer.php");
require("class.smtp.php");


$mail = new PHPMailer(); //Crea la clase
$mail->From = "correo@sanboni.edu.co"; //Email pop3 que envía el email
$mail->FromName = "hola prueba prueba"; //De parte de quien se ve el correo
$mail->Host = "smtp.gmail.com"; //El nombre de tu servidor de salida SMTP
$mail->Port = 587; //El puerto a utilizar para envíos. En México usualmente Infinitum bloquea el puerto 25, el alterno es el 587
$mail->Mailer = "smtp"; //El protocolo para envíos a usar
$mail->AddAddress("manuelf0710@gmail.com"); //La dirección de emai a la cual envías
$mail->Subject = "el asunto del mail"; //Asunto del email
$mail->Body = "todo el cuerpo del mensaje"; //Cuerpo del mensaje de email

$mail->SMTPAuth = "true";
$mail->Username = "correo@sanboni.edu.co"; //Un usuario válido de correo en el servidor SMTP
$mail->Password = "septiembre14"; //Un password válido del usuario en el servidor SMTP

if(!$mail->Send()){ //Revisa el resultado del envío
echo "There was an error sending the message"; //Escribe el error en caso que no se haya enviado bien el correo
exit; //Sale del s_cript sin ejecutar el resto del código siguiente
}?>
</body>
</html>
