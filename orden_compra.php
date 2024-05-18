<?php
require_once('conexion/db.php');



session_cache_limiter( 'nocache' );
ini_set ('memory_limit', '2000M');
ini_set('max_execution_time', 300);
set_time_limit(0);
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0"); // HTTP/1.1
ob_start();


$url="http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

$a=$_FILES['archivo1'];
//echo($tipo_cargue);

//mysql_select_db($database_repso, $conexion);
@mysql_query("SET collation_connection = utf8_general_ci;");
mysql_query ("SET NAMES 'utf8'");

/*
$query_RsParametroRuta = "SELECT PARAVALOR FROM PARAMETROS WHERE PARANOMB = 'RUTAARCHIVOADMONCARG'";
$RsParametroRuta = mysql_query($query_RsParametroRuta, $repso) or die(mysql_error());
$row_RsParametroRuta = mysql_fetch_assoc($RsParametroRuta);

$rutaArchivos = $row_RsParametroRuta['PARAVALOR'];
$CARPETA="archivoscarguehoras";*/
//rutaArchivos=$rutaArchivos.$CARPETA.'/';
//$rutaArchivos = '/var/www/vhosts/ecosaludocupacional.com/httpdocs/pruebas/graficos/goo/archivos/';
//$rutaArchivos = '/home/ecosalud/public_html/pruebas/admon/'.$CARPETA.'/';
$rutaArchivos = 'C://wamp/www/archivos/1compras/archivos';
//echo($rutaArchivos);


if (is_uploaded_file($_FILES['archivo1']['tmp_name']))
{	
				$upload_archivo1_dir = $rutaArchivos;
				$nombre_archivo1 = pathinfo($_FILES['archivo1']['name'], PATHINFO_BASENAME);
				$tipo_archivo = pathinfo($_FILES['archivo1']['name'], PATHINFO_EXTENSION);
	//echo("direeccion para mover el archivo");echo("<br>");
	//echo $upload_archivo1_dir.$nombre_archivo1;


					$nombre_archivo1 = str_replace("Ñ", "N",$_FILES['archivo1']['name']);
					$nombre_archivo1 = str_replace("ñ", "n",$nombre_archivo1);
					$nombre_archivo1 = date("Ymd_His")."-".$nombre_archivo1;

		if ( move_uploaded_file($_FILES['archivo1']['tmp_name'],$upload_archivo1_dir.$nombre_archivo1) )
		{
/*		
						require_once("../includes/PHPExcel.php");
						require_once("../includes/PHPExcel/IOFactory.php");
						require_once("../includes/PHPExcel/Reader/Excel2007.php");
*/
					    require_once("/includes/PHPExcel/PHPExcel.php");
						require_once("/includes/PHPExcel/PHPExcel/IOFactory.php");
						require_once("/includes/PHPExcel/PHPExcel/Reader/Excel2007.php");
				         
                        $archivo_loc = "";
						$lista_errores = "";
						$archivo_nom = $rutaArchivos.$nombre_archivo1;
						
						if ($tipo_archivo != '')
						{
							
							if ($tipo_archivo == 'xls')
							{
								$objReader = new PHPExcel_Reader_Excel2007();		
								//$objReader = new PHPExcel_Reader_Excel5();
								$objPHPExcel = PHPExcel_IOFactory::load($archivo_loc.$archivo_nom);
								$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); //objeto de PHPExcel, para escribir en el excel
								//$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
							}
							else if ($tipo_archivo == 'xlsx')
							{
								$objReader = PHPExcel_IOFactory::createReader('Excel2007');
								$objReader->setReadDataOnly(true);
								$objPHPExcel = $objReader->load($archivo_loc.$archivo_nom);
							}
							
							$objPHPExcel->setActiveSheetIndex(0); //para activar la pagina 1 y escribir en la plantilla
							
								$i=6;
								$lista_errores_datos = "";
		                         //echo(trim($objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue()));
								while($objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue() != '')
								{
								
								 $dependencia        = trim($objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue());
								 $sigla              = trim($objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue());
								 $codigo             = trim($objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue());
								 $area               = trim($objPHPExcel->getActiveSheet()->getCell("E".$i)->getValue());
								 $hhtotales          = trim($objPHPExcel->getActiveSheet()->getCell("F".$i)->getValue());
								 $numfuncionarios    = trim($objPHPExcel->getActiveSheet()->getCell("G".$i)->getValue());
                                 
								 $query_RsInsertHhprimerhoja="INSERT INTO VI_CARHORASHF (
								                                                         VIHFNOPE,
																						 VIHFSIGL,
																						 VIHFCODE,
																						 VIHFAREA,
																						 VIHFHHTO,
																						 VIHFNUFU
																						 )
																						 VALUES
																						 (
																						 '".$dependencia."',
																						 '".$sigla."',
																						 '".$codigo."',
																						 '".$area."',
																						 '".$hhtotales."',
																						 '".$numfuncionarios."'
																						 )
								 ";
								 echo($query_RsInsertHhprimerhoja.'<br>');
								 $RsInsertHhprimerhoja = mysql_query($query_RsInsertHhprimerhoja, $conexion) or die(mysql_error());
								 
                               $i++;
							   }	
                          /*     
							   $j=2;
                             $objPHPExcel->setActiveSheetIndex(1);	//ACTIVAR LA HOJA 2
							 while($objPHPExcel->getActiveSheet()->getCell("A".$j)->getValue() != '')
							  {
								 $numero_personal        = trim($objPHPExcel->getActiveSheet()->getCell("A".$j)->getValue());							  
								 $nombre_personal        = trim($objPHPExcel->getActiveSheet()->getCell("B".$j)->getValue());							  
								 $unorg                  = trim($objPHPExcel->getActiveSheet()->getCell("C".$j)->getValue());							  
								 $unorgdes               = trim($objPHPExcel->getActiveSheet()->getCell("D".$j)->getValue());							  
								 $sexo                   = trim($objPHPExcel->getActiveSheet()->getCell("E".$j)->getValue());							  
								 $fecha_nacimiento       = trim($objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue());							  
								 $denominacion_posicion  = trim($objPHPExcel->getActiveSheet()->getCell("G".$j)->getValue());							  
								 $division_personal      = trim($objPHPExcel->getActiveSheet()->getCell("H".$j)->getValue());							  
								 $subdivision_personal   = trim($objPHPExcel->getActiveSheet()->getCell("I".$j)->getValue());							  
								 $clase_contrato         = trim($objPHPExcel->getActiveSheet()->getCell("J".$j)->getValue());							  
								 $sucursal               = trim($objPHPExcel->getActiveSheet()->getCell("K".$j)->getValue());							  
								 $htregular              = trim($objPHPExcel->getActiveSheet()->getCell("L".$j)->getValue());							  
								 $htsobretiempo          = trim($objPHPExcel->getActiveSheet()->getCell("M".$j)->getValue());							  
								 $hhnvo                  = trim($objPHPExcel->getActiveSheet()->getCell("N".$j)->getValue());							  
								 $cod_ciudsmedico        = trim($objPHPExcel->getActiveSheet()->getCell("O".$j)->getValue());							  
								 $ciudsmedico            = trim($objPHPExcel->getActiveSheet()->getCell("P".$j)->getValue());							  
								 $unis                   = trim($objPHPExcel->getActiveSheet()->getCell("Q".$j)->getValue());							  
								 $regional               = trim($objPHPExcel->getActiveSheet()->getCell("R".$j)->getValue());
								 
								 $query_RsInsertHhsegundahoja="INSERT INTO VI_CARHORASHFH2 (
								                                                         VICHNUPE,
																						 VICHNOPE,
																						 VICHCUNO,
																						 VICHNUNO,
																						 VICHSEXO,
																						 VICHFENA,
																						 VICHDEPO,
																						 VICHDIPE,
																						 VICHSUPE,
																						 VICHCLCO,
																						 VICHSUCU,
																						 VICHHTRE,
																						 VICHSOTI,
																						 VICHHNVO,
																						 VICHCOSM,
																						 VICHCIUSM,
																						 VICHUNIS,
																						 VICHREG
																						 )
																						 VALUES
																						 (
																						 '".$numero_personal."',
																						 '".$nombre_personal."',
																						 '".$unorg."',
																						 '".$unorgdes."',
																						 '".$sexo."',
																						 '".$fecha_nacimiento."',
																						 '".$denominacion_posicion."',
																						 '".$division_personal."',
																						 '".$subdivision_personal."',
																						 '".$clase_contrato."',
																						 '".$sucursal."',
																						 '".$htregular."',
																						 '".$htsobretiempo."',
																						 '".$hhnvo."',
																						 '".$cod_ciudsmedico."',
																						 '".$ciudsmedico."',
																						 '".$unis."',
																						 '".$regional."'
																						 )
								 ";
								 //echo($RsInsertHhsegundahoja.'<br>');
								 $RsInsertHhsegundahoja = mysql_query($query_RsInsertHhsegundahoja, $repso) or die(mysql_error());
								 
							     $j++;
							  }
							
							*/
   
         }


header("location: home.php?doc=cargar_horashombre&feed=1");
}

}




//$redireccionar = "location: home.php?doc=anexos_observaciones&codProyecto=" .$proyecto;
//header($redireccionar);
?>