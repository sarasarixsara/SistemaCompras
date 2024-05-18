<?php 
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
ob_start();
function downloadFile( $fullPath ){ 

  // Must be fresh start 
  if( headers_sent() ) 
    die('Headers Sent'); 

  // Required for some browsers 
  if(ini_get('zlib.output_compression')) 
    ini_set('zlib.output_compression', 'Off'); 

  // File Exists? 
  if( file_exists($fullPath) ){ 
    
    // Parse Info / Get Extension 
    $fsize = filesize($fullPath); 
    $path_parts = pathinfo($fullPath); 
    $ext = strtolower($path_parts["extension"]); 
    
    // Determine Content Type 
    switch ($ext) { 
      case "pdf": $ctype="application/pdf"; break; 
      case "exe": $ctype="application/octet-stream"; break; 
      case "zip": $ctype="application/zip"; break; 
      case "doc": $ctype="application/msword"; break; 
      case "xls": $ctype="application/vnd.ms-excel"; break; 
      case "ppt": $ctype="application/vnd.ms-powerpoint"; break; 
      case "gif": $ctype="image/gif"; break; 
      case "png": $ctype="image/png"; break; 
      case "jpeg": 
      case "jpg": $ctype="image/jpg"; break; 
      default: $ctype="application/force-download"; 
    } 

    header("Pragma: public"); // required 
    header("Expires: 0"); 
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
    header("Cache-Control: private",false); // required for certain browsers 
    header("Content-Type: $ctype"); 
    header("Content-Disposition: attachment; filename=\"".basename($fullPath)."\";" ); 
    header("Content-Transfer-Encoding: binary"); 
    header("Content-Length: ".$fsize); 
    ob_clean(); 
    flush(); 
    readfile( $fullPath ); 

  } else 
    die('File Not Found'); 

} 

	if (!isset($_SESSION)) {
  session_start();
}

if(!isset($_SESSION['MM_AccesoCorrectoApp']) || $_SESSION['MM_AccesoCorrectoApp'] != 'ACTIVO'){
header("location: ../index.php");
//exit('acceso no autorizado');
}
if(isset($_GET["doc"])&& $_GET["doc"]!=''){
    $vBarras = array("/", "\\"); 	
    $sDirectorio = '/archivos_compras/'; 
    //$sDirectorio = '/pruebas/rein/archivos/'; 
	//$sUrlDescargas = $_SERVER["DOCUMENT_ROOT"].$sDirectorio;
	$sDocumento = $sDirectorio.str_replace($vBarras, "_", $_GET["doc"]); 
    echo($sDocumento);
    if (file_exists($sDocumento)) 
    { 
		downloadFile($sDocumento);
		
	
    }else{
	
	echo('fichero no encontrado'.$sDocumento);
	}
}else{
echo('file no exist');
}
?>