<?php
/*    function get_server_var($id) {
        return isset($_SERVER[$id]) ? $_SERVER[$id] : '';
    }
	
     function get_full_url() {//buena funcion
        $https = !empty($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'], 'on') === 0;
        return
            ($https ? 'https://' : 'http://').
            (!empty($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'].'@' : '').
            (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ($_SERVER['SERVER_NAME'].
            ($https && $_SERVER['SERVER_PORT'] === 443 ||
            $_SERVER['SERVER_PORT'] === 80 ? '' : ':'.$_SERVER['SERVER_PORT']))).
            substr($_SERVER['SCRIPT_NAME'],0, strrpos($_SERVER['SCRIPT_NAME'], '/'));
    }	
// A list of permitted file extensions
$allowed = array('png', 'jpg', 'gif','zip','xls','xlsx','docx','doc');

if(isset($_FILES['files']) && $_FILES['files']['error'] == 0){

	$extension = pathinfo($_FILES['files']['name'], PATHINFO_EXTENSION);
/*
	if(!in_array(strtolower($extension), $allowed)){
		echo '{"status":"error"}';
		exit;
	}
*/
/*	if(move_uploaded_file($_FILES['files']['tmp_name'], 'files/'.$_FILES['files']['name'])){
		echo '{"status":"success"}';
		exit;
	}
}

/
//echo '{"status":"error"}';
$var=get_server_var('REQUEST_METHOD');//obtiene el metodo post get de la peticion
$contenido=get_server_var('CONTENT_TYPE');
$script=get_server_var('SCRIPT_FILENAME'); // trae C:/wamp2i/www/uploads/uploads/server/php/index.php
$tamano=get_server_var('CONTENT_LENGTH'); // tamaño del archivo  en bytes
$disposition=get_server_var('HTTP_CONTENT_DISPOSITION'); //retorno vacio
echo '{"status":"'.$disposition.'"}';
exit;
*/

error_reporting(E_ALL | E_STRICT);
if(isset($_FILES['files'])){
$archivosubir='files';
require('UploadHandler.php');
$upload_handler = new UploadHandler();
}
if(isset($_FILES['newarchivo10'])){
$archivosubir='newarchivo10';
require('UploadHandler.php');
$upload_handler = new UploadHandler();
}


?>