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


echo "\nSending Update Email \n";

$mail = new PHPMailer();  // Instantiate your new class
$mail->IsSMTP();          // set mailer to use SMTP
//$mail->isSendMail();
$mail->SMTPDebug = 0;
$mail->SMTPAuth = true;   // turn on SMTP authentication
$mail->Host = "smtp.gmail.com"; // specify main and backup server
$mail->SMTPSecure= "ssl"; //  Used instead of TLS when only POP mail is selected
$mail->Port = 25;        //  Used instead of 587 when only POP mail is selected
$mail->AddAddress("manuelf0710@gmail.com","ManuelF");

$mail->Username = "dyamid@gmail.com";  // SMTP username, you could use your google apps address too.
$mail->Password = "gatuelo"; // SMTP password

$mail->From = "dyamig@gmail"; //Aparently must be the same as the UserName
$mail->FromName = "Diego";
$mail->Subject = "enviado desde server sanboni con debug cero";
$mail->Body = "tambien debe funcionar es el modo normal ssl port 25 desde el servidor de sanboni";
$mail->AddBcc("manuelf07101@gmail.com", "Manuelf");

if(!$mail->Send())
{
  echo "There was an error sending the message:" . $mail->ErrorInfo;
  exit;
}
echo "Done…\n";
?>
</body>
</html>
