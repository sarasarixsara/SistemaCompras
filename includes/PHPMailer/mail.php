<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>PHPMailer - mail() test</title>
</head>
<body>
<?php
require 'PHPMailerAutoload.php';
require("class.phpmailer.php");
require("class.smtp.php");

//Create a new PHPMailer instance
$mail = new PHPMailer();
//Set who the message is to be sent from
$mail->setFrom('dyamid@gmail.com', 'Diego Yamid');
//Set an alternative reply-to address
$mail->addReplyTo('manuelf0710@gmail.com', 'Jose Manuel');
//Set who the message is to be sent to
$mail->addAddress('manuelf07101@gmail.com', 'Manuelf07101');
//Set the subject line
$mail->Subject = 'otra prueba';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
//$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
$cuerpo="esto es el mensaje cruzo dedos que se envie que si";
$mail->msgHTML($cuerpo);
//Replace the plain text body with one created manually
$mail->AltBody = 'Esto es un mensaje de prueba';
//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.png');

//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
}
?>
</body>
</html>