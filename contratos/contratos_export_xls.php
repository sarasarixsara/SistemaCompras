<?php
include('../conexion/db.php');

function cellColor($cells,$color, $color_border){
    global $objPHPExcel;

    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => $color
        )	
    ));
	$fontheadertable = array(
		'font'  => array(
			'bold'  => false,
			'color' => array('rgb' => 'FFFFFF')
			//'size'  => 12,
			//'name'  => 'Verdana'
		));
	$objPHPExcel->getActiveSheet()->getStyle($cells)->applyFromArray($fontheadertable);
	$objPHPExcel->getActiveSheet()->getStyle($cells)->applyFromArray(
		array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('rgb' => $color_border)
				)
			)
		)
	);	
	
}


/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2015 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    ##VERSION##, ##DATE##
 */

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('America/Bogota');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
//require_once dirname(__FILE__) . '\..\includes\PHPExcel.php';
//require_once('C:/wamp64/www/SistemaCompras/includes/PHPExcel/PHPExcel.php');
require_once('../includes/PHPExcel/PHPExcel.php');

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Aplicativo de compras")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");

// consulta de la base de datos 
//(SELECT PROVNOMB FROM proveedores WHERE `CONTCOID`=PROVCODI )PROVEEDOR,
//`CONTCONO` PROVEEDOR,
$query_RsConsulatar="SELECT distinct CONTID,             
                IF(CONTID=COOTIDCO,COOTNUME,CONTNUME) NUMERO,
							  IF(CONTID=COOTIDCO,COOTCLAS,CONTCLAS) CLASE,
							  IF(CONTID=COOTIDCO,COOTOBJE,CONTOBJE) OBJETO, 
							  (SELECT PROVNOMB FROM proveedores WHERE `CONTCOID`=PROVCODI )PROVEEDOR,
							  date_format(`CONTFEIN`, '%d/%m/%Y') FECHA_INICIO,					
							  IF(CONTID=COOTIDCO,date_format(COOTFEFI, '%d/%m/%Y'),date_format(CONTFEFI, '%d/%m/%Y'))FECHA_FIN,
                              `CONTNHOR` NUMERO_HORAS,
							  `CONTFOPA` FORMA_PAGO,
							  IF(CONTID=COOTIDCO,COOTVACU,CONTVACU) VALOR_CUANTIA 
 FROM bdcompras.contratos,bdcompras.contratos_OTROSI
 ORDER BY CONTID,COOTID ASC;";					  
				//	 echo($query_RsConsulatar);
		$RsConsulatar = mysqli_query($conexion,$query_RsConsulatar) or die(mysqli_error($conexion));
		$row_RsConsulatar = mysqli_fetch_assoc($RsConsulatar);
		$totalRows_RsConsulatar = mysqli_num_rows($RsConsulatar);


// Add some data
$sheet=$objPHPExcel->setActiveSheetIndex(0);
$sheet->getRowDimension(30)->setRowHeight(-1);
$sheet->getStyle('C1')->getAlignment()->setWrapText(true);

$sheet->setCellValue('A1', 'Numero')
      ->setCellValue('B1', 'Clase');
$sheet->getColumnDimension('B')->setAutoSize(false);
$sheet->getColumnDimension('B')->setWidth(40);      

$sheet->setCellValue('C1', 'Descripción');
$sheet->getColumnDimension('C')->setAutoSize(false);
$sheet->getColumnDimension('C')->setWidth(60);

$sheet->setCellValue('D1', 'Proveedor');		
$sheet->getColumnDimension('D')->setAutoSize(false);
$sheet->getColumnDimension('D')->setWidth(40);

$sheet->setCellValue('E1', 'Fecha Incio');
$sheet->getColumnDimension('E')->setAutoSize(false);
$sheet->getColumnDimension('E')->setWidth(12);

$sheet->setCellValue('F1', 'Fecha Fin');
$sheet->getColumnDimension('F')->setAutoSize(false);
$sheet->getColumnDimension('F')->setWidth(12);

$sheet->setCellValue('G1', 'N° Horas')
      ->setCellValue('H1', 'Forma de Pago');
$sheet->getColumnDimension('H')->setAutoSize(false);
$sheet->getColumnDimension('H')->setWidth(40);	  
	  
$sheet->setCellValue('I1', 'Valor');
$sheet->getColumnDimension('I')->setAutoSize(false);
$sheet->getColumnDimension('I')->setWidth(20);	
cellColor('A1:M1','666666','DDDDDD');
			
//$sheet->getRowDimension(30)->setRowHeight(-1);
//$sheet->getStyle('C1')->getAlignment()->setWrapText(true);
		

$j=2;
do{
	$sheet->setCellValue("A".$j,$row_RsConsulatar['NUMERO']);
	$sheet->setCellValue("B".$j,$row_RsConsulatar['CLASE']);
	$sheet->setCellValue("C".$j,$row_RsConsulatar['OBJETO']);
	$sheet->setCellValue("D".$j,$row_RsConsulatar['PROVEEDOR']);
	$sheet->setCellValue("E".$j,$row_RsConsulatar['FECHA_INICIO']);
	$sheet->setCellValue("F".$j,$row_RsConsulatar['FECHA_FIN']);
	$sheet->setCellValue("G".$j,$row_RsConsulatar['NUMERO_HORAS']);
	$sheet->setCellValue("H".$j,$row_RsConsulatar['FORMA_PAGO']);
	$sheet->setCellValue("I".$j,$row_RsConsulatar['VALOR_CUANTIA']);
	//$sheet->setCellValue("I".$j,number_format($row_RsConsulatar['VALOR_CUANTIA'],0,'.','.')); 
	
	$j++;
}while($row_RsConsulatar = mysqli_fetch_array($RsConsulatar));



// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Hoja_Contratos');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);




$ext=date("Ymd_His")."contratos.xls";
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$ext);
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
