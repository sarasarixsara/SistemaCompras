<?php
require_once('/includes/tcpdf_min/tcpdf.php');
if (!isset($_SESSION)) {
  session_start();
}

//$firmado=$_SESSION['MM_UserID'];
$accion=$_GET['%'];

require_once("conexion/db.php");
  $query_RsDetallesCompra ="select OCC.ORCOCONS ORDEN_CONV_CODIGO,
                                   OCCD.ORCDCONV CODIGO_CONVENIO,
								   PV.PROVNOMB  PROVEEDOR_DES,
								   PV.PROVREGI  PROVEEDOR_NIT,
								   PV.PROVTELE  PROVEEDOR_TELEFONO,
								   PV.PROVIDCI	PROVEEDOR_CIUDAD,
								   DR.DERECONS  DETALLE_CODIGO,
								   PV.PROVDIRE  PROVEEDOR_DIRECCION,
								   PV.PROVCON1  PROVEEDOR_CONTACTO,
								   DR.DEREDESC  DETALLE_DES,
								   DR.DERECANT  DETALLE_CANTIDAD,
								   PD.PRODCONS  CODIGO_PRODUCTO,
								   PD.PRODDESC  PRODUCTO_DES,
								  (CP.COPRPREC/PD.PRODCANT)VALOR_UNITARIO_CONVENIDO,
								   CP.COPRPREC V_PRODUCTO,
								  (SELECT R.REQUCORE FROM REQUERIMIENTOS R WHERE R.REQUCODI=DR.DEREREQU) REQUERIMIENTO,
								   'CONVENIO'TIPO_ORDEN_DES
								   

							from	orden_compra_convenio	OCC,
									orden_compconv_detalle	OCCD,
									conve_produc			CP,
									productos				PD,
									convenios				C,
									detalle_requ			DR,
									proveedores				PV

							where   OCC.ORCOCONS='".$_GET['codcomp']."'
							#AND     DR.DEREAPRO=19
							AND 	OCC.ORCOCONS=OCCD.ORCDORCC
							AND		OCCD.ORCDCONV=CP.COPRID
							AND		CP.COPRIDPC=PD.PRODCONS
							AND		CP.COPRIDCO=C.CONVCONS
							AND     OCCD.ORCDDETA=DR.DERECONS
							AND     C.CONVIDPR=PV.PROVCODI";
		$RsDetallesCompra = mysqli_query($conexion,$query_RsDetallesCompra) or die(mysqli_error($conexion));
		$row_RsDetallesCompra = mysqli_fetch_assoc($RsDetallesCompra);
		$totalRows_RsDetallesCompra = mysqli_num_rows($RsDetallesCompra);
//exit('EL VALOR RESULTADO ES('.$row_RsDetallesCompra["COFICOM"].')');
//$p=$row_RsDetallesCompra['PROVCODI'];
$p ="123";
if($totalRows_RsDetallesCompra>0){
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = K_PATH_IMAGES.$this->header_logo;
        $this->Image($image_file, 40, 15, 34, '10', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', '', 8);
        // Title
        //$this->Cell(0, 15, $this->header_title, 0, false, 'C', 0, '', 0, false, 'M', 'M');
		$tbl='<table border="0" width="100%" border="0">
				 <tr style="line-height:25px;">
				   <td align="center" rowspan="2" width="50%"><FONT SIZE="9">FORMATO ORDEN DE CONVENIO '.$this->header_title.'</FONT></td>
				   <td width="50%" align="right">&nbsp;&nbsp;&nbsp;<FONT SIZE="9"> Codigo: FR ADM - 052</FONT></td>
				 </tr>	
				 <tr>
				   <td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<FONT SIZE="9"> version: 05</FONT></td>
				</tr>					 
			</table>	 
		';		
		$this->writeHTML($tbl, true, false, false, false, '');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Pagina '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
	
}

// create new PDF document
//$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf = new MYPDF('', PDF_UNIT, 'A4', true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Compras San Bonifacio');
$pdf->SetTitle('orden de compra convenio');
$pdf->SetSubject('San Bonifacio orden convenio');
$pdf->SetKeywords('Orden convenio, PDF, Sanbonifacio, Detalle');



// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 048', PDF_HEADER_STRING);
$pdf->SetHeaderData('logo.jpg', 40, $row_RsDetallesCompra['TIPO_ORDEN_DES'], '');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetMargins(15, 30, 15);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', 'B', 20);

// add a page
$pdf->AddPage();

//$pdf->Write(0, 'Reporte de Requerimiento San Bonifacio', '', 0, 'L', true, 0, false, false, 0);

$pdf->SetFont('helvetica', '', 8);

// -----------------------------------------------------------------------------

		
       
		
		$query_RsOrdenCom=" SELECT `ORCOCONS` CODIGO,
								   `ORCOFECH` FECHA,
								   `ORCOIDCO` CONVENIO,
								   `ORCOFEEN` FECHA_ENTREGA,
								   `ORCOOBSE` OBSERVACION,
								    ORCODIVA  D_IVA
						   FROM `orden_compra_convenio` 
						   WHERE `ORCOCONS`='".$_GET['codcomp']."'";
							   
     //echo $query_RsReporteOrdenCom;	
	$RsOrdenCom = mysqli_query($conexion,$query_RsOrdenCom) or die(mysqli_error());
	$row_RsOrdenCom = mysqli_fetch_array($RsOrdenCom);
    $totalRows_RsOrdenCom = mysqli_num_rows($RsOrdenCom);
	
		$query_RsCriterio=" SELECT COTIPROV,
								   COTIFOPA FORMA_PAGO,
						     (SELECT `CRMEDESC` 
							  FROM criterio_medicion 
							  WHERE `CRMECONS`=COTIFOPA 
							        ) FORMA_PAGO_DESC, 
									`COTIGARA` GARANTIA,
							 (SELECT `CRMEDESC` 
							  FROM criterio_medicion 
								WHERE `CRMECONS`=COTIGARA 
								) GARANTIA_DESC,
								COTITIEN TIEMPO_ENTREGA,
							(SELECT `CRMEDESC` 
								FROM criterio_medicion 
								WHERE `CRMECONS`=COTITIEN 
							) TIEMPO_ENTREGA_DESC,
							 COTISIEN SITIO_ENTRE,
							(SELECT `CRMEDESC` 
							FROM criterio_medicion 
							WHERE `CRMECONS`=COTISIEN 
							) SITIO_ENTREGA_DESC,
						   `COTICODI` CODIGO_COTIZACION 
							FROM `cotizacion`,
							     `cotizacion_detalle`,
								 detalle_requ  
							WHERE COTIPROV='".$_GET['codprov']."'
								AND `CODEDETA`= `DERECONS` 
								AND  `CODECOTI`= `COTICODI` 
							GROUP BY COTICODI
							ORDER BY COTICODI DESC
                            LIMIT 1
							";
							   
     //echo $query_RsCriterio;	
	$RsCriterio = mysqli_query($conexion,$query_RsCriterio) or die(mysqli_error());
	$row_RsCriterio = mysqli_fetch_array($RsCriterio);
    $totalRows_RsCriterio = mysqli_num_rows($RsCriterio);	

		
$tbl='<table border="0" width="100%">
         <tr align=""><td width="10%"><FONT SIZE="9">Fecha: 		</FONT></td><td width="40%"><FONT SIZE="9">'.$row_RsOrdenCom['FECHA'].'</FONT></td>
		              <td width="10%"><FONT SIZE="9">Numero: 		</FONT></td><td width="40%"><FONT SIZE="9">CONV-2015-'.$row_RsOrdenCom['CODIGO'].'</FONT></td>
		 </tr>
		 <tr align=""><td width="10%"><FONT SIZE="9">Señor(es): 	</FONT></td><td width="90%"><FONT SIZE="9">'.$row_RsDetallesCompra['PROVEEDOR_DES'].'</FONT></td></tr>
		  <tr align=""><td width="10%"><FONT SIZE="9">Nit: 	    </FONT></td><td width="90%"><FONT SIZE="9">'.$row_RsDetallesCompra['PROVEEDOR_NIT'].'</FONT></td></tr>
		 <tr align=""><td width="10%"><FONT SIZE="9">Ciudad: 		</FONT></td><td width="90%"><FONT SIZE="9">'.$row_RsDetallesCompra['PROVEEDOR_CIUDAD'].'</FONT></td></tr>
		 <tr align=""><td width="10%"><FONT SIZE="9">Telefono: 	</FONT></td><td width="90%"><FONT SIZE="9">'.$row_RsDetallesCompra['PROVEEDOR_TELEFONO'].'</FONT></td></tr>
		
		
		 
		 
</table>';		
$pdf->writeHTML($tbl, true, false, false, false, '');
	
$tbldata = '
<table cellspacing="0" cellpadding="1" border="1">
	<tr style="background-color:#EFEDED;">      
		<td width="60%" 	align="center"	><FONT SIZE="9">DESCRIPCION		</FONT ></td>
		<td width="13%" 	align="center"	><FONT SIZE="9">PRECIO UNITARIO	</FONT ></td>
	    <td width="10%" 	align="center"	><FONT SIZE="9">CANTIDAD			</FONT ></td>
	    <td width="17%" 	align="center"	><FONT SIZE="9">VALOR TOTAL		</FONT ></td>
    </tr>

';
$bodydata='';	
$arraytotal = array();	
$array_requ = array();

		if($totalRows_RsDetallesCompra>0){
		 $i=0;
		 $color='';
		 do{
		    $i++;
			$array_requ[] = $row_RsDetallesCompra['REQUERIMIENTO'];
			$valortotal=  ($row_RsDetallesCompra['VALOR_UNITARIO_CONVENIDO'] * $row_RsDetallesCompra['DETALLE_CANTIDAD']);
			$arraytotal[] = $valortotal;
		    $tbldata =$tbldata.'
			 <tr nobr="true" '.$color.'>		   
			   <td align="justify"><FONT SIZE="7"> '.$row_RsDetallesCompra['PRODUCTO_DES'].'<BR>'.$row_RsDetallesCompra['DETALLE_CODIGO'].'-'.$row_RsDetallesCompra['DETALLE_DES'].'</FONT></td>
			   <td align="right"><FONT SIZE="9"> $'.number_format($row_RsDetallesCompra['V_PRODUCTO'],0,'.',',').'</FONT></td>			   
			   <td align="center"><FONT SIZE="9"> '.$row_RsDetallesCompra['DETALLE_CANTIDAD'].'</FONT></td>
			   <td align="right"><FONT SIZE="9"> $'.number_format($valortotal,0,'.',',').'</FONT></td>
			   
			 </tr>
			 
			';
		   }while($row_RsDetallesCompra = mysqli_fetch_assoc($RsDetallesCompra));
		   
		}
$tbldata=$tbldata.$bodydata.'

</table>';		
$pdf->writeHTML($tbldata, true, false, false, false, '');

$tbl='<table width="100%" border="0">
       
		<tr>
		 <td  width="80%" align="right"><FONT SIZE="9">TOTAL:</FONT></td>
		 <td  width="20%" align="right"><FONT SIZE="9">$'.number_format(array_sum($arraytotal),0,'.',',')  .'</FONT></td>
		</tr>
		<tr>
		 <td style="background-color:#EFEDED;" width="25%" align="Left" ><FONT SIZE="9">FECHA DE ENTREGA:</FONT ></td>
		 <td width="25%"><FONT SIZE="9">'.$row_RsOrdenCom['FECHA_ENTREGA'].'</FONT></td>	
		 <td style="background-color:#EFEDED;" width="25%" align="Left" ><FONT SIZE="9">IVA:</FONT ></td>
		 <td width="25%"><FONT SIZE="9">'.$row_RsOrdenCom['D_IVA'].'</FONT></td>			 
		</tr>
		<tr>
		<td style="background-color:#EFEDED;" width="25%" align="Left"><FONT SIZE="9">GARANTIA:</FONT></td>	
		<td width="25%"><FONT SIZE="9">'.$row_RsCriterio['GARANTIA_DESC'].'</FONT></td>
		 <td style="background-color:#EFEDED;" width="25%"  align="Left"><FONT SIZE="9">TIEMPO DE ENTREGA:</FONT></td>
		 <td width="25%"><FONT SIZE="9">'.$row_RsCriterio['TIEMPO_ENTREGA_DESC'].'</FONT></td>			
		</tr>
		<tr>
		<td style="background-color:#EFEDED;" width="25%"  align="Left"><FONT SIZE="9">FORMA DE PAGO:</FONT></td>	
		<td width="25%"><FONT SIZE="9">'.$row_RsCriterio['FORMA_PAGO_DESC'].'</FONT></td>
		</tr>
		<tr >
		 <td style="background-color:#EFEDED;" width="25%"  align="Left"><FONT SIZE="9">SITIO DE ENTREGA:</FONT></td>
		<td width="25%"><FONT SIZE="9">'.$row_RsCriterio['SITIO_ENTREGA_DESC'].'</FONT></td>	
		</tr>
		<tr>
		<td style="background-color:#EFEDED;" width="25%"  align="Left"><FONT SIZE="9">DESCUENTOS:</FONT></td>
		<td width="25%" colspan="3"><FONT SIZE="9"></FONT></td>	
		</tr>
	  </table>';
$pdf->writeHTML($tbl, true, false, false, false, '');

$tbl='<table width="100%" border="0">
        <tr>
		 <td colspan="12" align="center" style="color:#000"><FONT SIZE="9">Favor factura a nombre de la Corporacion Colegio San Bonifacio de las lanzas</FONT ></td>
		</tr>
		
		<tr nobr="true">
		 <td width="100%"><FONT SIZE="9">Observaciones:</FONT></td>
		 </tr>
		 <tr>
		 <td width="100%"><FONT SIZE="9">'.$row_RsOrdenCom['OBSERVACION'].' </FONT></td>
		 </tr>
	  </table>';
$pdf->writeHTML($tbl, true, false, false, false, '');
$requerimientosdetalle = implode(", ", array_unique($array_requ));
$tbl='<table width="100%" border="0">
        <tr>
		 <td ><FONT SIZE="9">Requerimientos:</FONT >&nbsp;&nbsp;&nbsp;<FONT SIZE="9">'.$requerimientosdetalle.'</FONT></td>
		</tr>
		
		
		 
	  </table>';
$pdf->writeHTML($tbl, true, false, false, false, '');
$tbl='<table border="0" width="100%" >
         <tr valign="top" nobr="true">
		    <td height="20"><FONT SIZE="9">Cordial Saludo,</FONT></td>
		   <td height="20"><FONT SIZE="9"></FONT></td>
		 </tr>
		</table>
		 ';
$pdf->writeHTML($tbl, true, false, false, false, '');		 
//if(($accion == 1 || $accion == 2 )&& $_GET['f'] != ''){
//$pdf->Image('imagenes/'.$firmado.'.png', '', '', 70, 30, '', '', 'T', false, 200, '', false, false, 0, false, false, false);
//}

$tbl='<table border="0" width="100%" height="70">
		 <tr valign="top" nobr="true">		 
		    <td height="70"><FONT SIZE="9"></FONT></td>			
		   <td height="70"><FONT SIZE="9"></FONT></td>
		   
		 </tr>
</table>';

$pdf->writeHTML($tbl, true, false, false, false, '');
$tbl='<table border="0" width="100%" >
		 <tr valign="top" nobr="true">		 
		    <td height="30"><FONT SIZE="9"></FONT></td>			
		   <td height="30"><FONT SIZE="9"></FONT></td>
		   
		 </tr>
		 <tr nobr="true">
        <td height="70"><FONT SIZE="9">MARTHA GUZMAN GONZALEZ</FONT > <br> <FONT SIZE="9">Directora Administrativa</FONT ></td>
		<td height="70"></td>
		
		 </tr>
		 </table>';

$pdf->writeHTML($tbl, true, false, false, false, '');

// -----------------------------------------------------------------------------


//Close and output PDF document
	$codigo_orden=$row_RsOrdenCom['CODIGO'];
	$nombre_archivo='CONV-2015-'.$codigo_orden;
	
	$query_RsParametroRuta = "SELECT PARAVALOR FROM PARAMETROS WHERE PARANOMB = 'RUTAARCHIVO_NAS'";
	$RsParametroRuta = mysqli_query($conexion,$query_RsParametroRuta) or die(mysqli_error($conexion));
	$row_RsParametroRuta = mysqli_fetch_array($RsParametroRuta);

	$rutaArchivos = $row_RsParametroRuta['PARAVALOR'];
	$carpeta = 'archivos_compras/ORDENES_CONVENIO/';
	$rutaArchivos = $row_RsParametroRuta['PARAVALOR'].$carpeta.$nombre_archivo;
	
	//$rutaArchivos='C:/wamp/www/compras/archivos_compras/ORDENESCONVENIOS/'.;
	
	
	//$pdf->Output($rutaArchivos.'.pdf', 'F');
	//$p= (string)$row_RsDetallesCompra['PROVCODI'];
	//$p= $_GET['f'];
	//echo($p);
	    

		
	
	
	 if($accion == 1)
	 {
		 /*
					$query_RsConsultarF = "SELECT F.* FROM firmas F WHERE F.FIRMCONS =1";
					$RsConsultarF = mysqli_query($conexion,$query_RsConsultarF) or die(mysqli_error($conexion));
					$row_RsConsultarF = mysqli_fetch_assoc($RsConsultarF);
					$totalRows_RsConsultarF = mysqli_num_rows($RsConsultarF);	
				
				if($totalRows_RsConsultarF>0){
					
					
								$p=$row_RsConsultarF['FIRMCODI'];
				}*/
				//$pdf->SetProtection($permissions=array('print', 'copy'), $user_pass=$p, $owner_pass=null, $mode=1);		 
					
				$pdf->Output($rutaArchivos.'.pdf', 'F');	
	}else  {
		
		$pdf->Output($nombre_archivo.'.pdf', 'I');
	}
	
	
}	
?>