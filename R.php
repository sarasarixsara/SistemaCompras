<?php
require_once('/includes/tcpdf_min/tcpdf.php');

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = K_PATH_IMAGES.$this->header_logo;
        $this->Image($image_file, 20, 8, 34, '10', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', '', 8);
        // Title
        //$this->Cell(0, 15, $this->header_title, 0, false, 'C', 0, '', 0, false, 'M', 'M');
		$tbl='<table border="1" width="700" border-color:"#ccc">
				 <tr style="line-height:25px;">
				   <td align="center" rowspan="2">REQUERIMIENTO</td>
				   <td>&nbsp;&nbsp;&nbsp;Codigo: FR GAD - 001</td>
				 </tr>	
				 <tr>
				   <td >&nbsp;&nbsp;&nbsp;version: 10</td>
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
$pdf = new MYPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Compras San Bonifacio');
$pdf->SetTitle('Reporte Requerimiento');
$pdf->SetSubject('San Bonifacio Reporte');
$pdf->SetKeywords('Reporte, PDF, Sanbonifacio, Detalle');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 048', PDF_HEADER_STRING);
$pdf->SetHeaderData('logo.jpg', 40, 'REQUERIMIENTO'.'Codigo: FR GAD - 001 ', '');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
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
require_once("conexion/db.php");
        $query_RsClasificacion ="SELECT D.DERECANT CANTIDAD,
		                                (SELECT UM.UNMENOMB
										FROM UNIDAD_MEDIDA UM 
										WHERE UM.UNMECONS = D.DEREUNME) UND_MEDIDA,
										D.DEREDESC DESCRIPCION,
										D.DEREJUST JUSTIFICACION,
										D.DEREAPRO APROBACION,										
										D.DEREPOA CENTRO,
										D.DERESUPO SUBCENTRO,
										D.DEREOBSE OBSERVACIONES,
										'' PRESUPUESTO,
										SYSDATE() FECHA
								FROM DETALLE_REQU D,
									  REQUERIMIENTOS R
								WHERE D.DEREREQU = R.REQUCODI
								 AND R.REQUCODI='".$_GET['codreq']."'";
		$RsClasificacion = mysqli_query($conexion,$query_RsClasificacion) or die(mysqli_error($conexion));
		$row_RsClasificacion = mysqli_fetch_array($RsClasificacion);
		$totalRows_RsClasificacion = mysqli_num_rows($RsClasificacion);
		
		$query_RsReque=" SELECT  REQUCORE CODIGO,
		                                    (SELECT A.AREANOMB 
										    FROM AREA A
										   WHERE A.AREAID =  R.REQUAREA LIMIT 1) AREA_DES,
	                         DATE_FORMAT(REQUFEEN,'%d/%m/%Y') FECHA_ENVIO,
							 CONCAT(PERSNOMB, ' ', PERSAPEL) NOMBRE_COMPLETO 
								   FROM requerimientos R,
								   PERSONAS P
							   WHERE R.REQUIDUS=P.PERSID
							   AND REQUCODI='".$_GET['codreq']."'";
							   
     //echo $query_RsReporteReque;	
	$RsReque = mysqli_query($conexion,$query_RsReque) or die(mysqli_error());
	$row_RsReque = mysqli_fetch_array($RsReque);
    $totalRows_RsReque = mysqli_num_rows($RsReque);
/*$tbl='<table border="1" width="840">
         <tr style="line-height:25px;">
			 <td rowspan="2"></td>
			 <td align="center" rowspan="2">REQUERIMIENTO</td>
			 <td>Codigo: FR GAD - 001</td>
			 <td>version: 10</td>
		 </tr>
		 
</table>';		
$pdf->writeHTML($tbl, true, false, false, false, '');	
*/

		
$tbl='<table border="1" width="840">
         <tr align="center">
			 <td width="50">Fecha<br></td>
			 <td width="70" style="background-color:#EFEDED;">'.$row_RsReque['FECHA_ENVIO'].'</td>
			 <td width="100"><b>Solicitante:</b></td>
			 <td width="300" style="background-color:#EFEDED;"><B>'.$row_RsReque['NOMBRE_COMPLETO'].'</B></td>
			 <td width="50">POA:</td>
			 <td width="270" style="background-color:#EFEDED;">'.$row_RsReque['AREA_DES'].'</td>
		 </tr>
		 
</table>';		
$pdf->writeHTML($tbl, true, false, false, false, '');
	
$tbldata = '
<table cellspacing="0" cellpadding="1" border="1">
	<tr style="background-color:#EFEDED;">
	   <td width="30" align="center">No</td>
       <td width="60" align="center"><FONT SIZE="6">CANTIDAD</font></td>
       <td width="200" align="center">DESCRIPCION</td>
	   <td width="200" align="center">JUSTIFICACION</td>
	   <td width="70"  align="center"><FONT SIZE="6">APROBACION</font></td>
	   <td width="70" align="center"><FONT SIZE="6">CENTRO DE COSTO</font></td>
	   <td width="70" align="center"><FONT SIZE="6">SUBCENTRO DE COSTO</font></td>
	   <td width="70" align="center"><FONT SIZE="6">PRESUPUESTO</FONT></td>
	   <td width="70" align="center"><FONT SIZE="6">FIRMA Y FECHA DE RECIBIDO</FONT></td>
    </tr>

';
$bodydata='';		

		if($totalRows_RsClasificacion>0){
		 $i=0;
		 $color='';
		 do{
		    $i++;
			if($row_RsClasificacion['APROBACION']!= '0' ){
			$apro_valor='';
			if($row_RsClasificacion['APROBACION']== '1' ){$apro_valor='SI';}else if($row_RsClasificacion['APROBACION']== '2' ){$apro_valor='NO';}
			  if($i%2==0){
			  $color='style="background-color:#F2F7FF"';
			  }else{
			   $color='';
			  }
		    $tbldata =$tbldata.'
			 <tr nobr="true" '.$color.'>
			   <td align="center">'.$i.'</td>
			   <td align="center">'.$row_RsClasificacion['CANTIDAD'].'</td>
			   <td align="justify">-'.$row_RsClasificacion['UND_MEDIDA'].'-<br>'.$row_RsClasificacion['DESCRIPCION'].'</td>
			   <td align="justify">'.$row_RsClasificacion['JUSTIFICACION'].'<br><br>Observacion: '.$row_RsClasificacion['OBSERVACIONES'].'</td>
			   <td align="center">'.$apro_valor.'</td>
			   <td align="center">'.$row_RsClasificacion['CENTRO'].'</td>
			   <td align="center">'.$row_RsClasificacion['SUBCENTRO'].'</td>
			   <td>'.$row_RsClasificacion['PRESUPUESTO'].'</td>
			   <td></td>
			 </tr> 
			';
		   }}while($row_RsClasificacion = mysqli_fetch_array($RsClasificacion));
		}
$tbldata=$tbldata.$bodydata.'</table>';		
$pdf->writeHTML($tbldata, true, false, false, false, '');
$pdf->Write(0, 'Observaciones:', '', 0, 'L', true, 0, false, false, 0);
$tbl='<table width="840" border="1">
        <tr>
		 <td colspan="8" align="center"><b>ESPACIO EXCLUSIVO PARA COMPRAS</b></td>
		</tr>
		<tr align="center">
		 <td height="20">No requerimiento</td>
		 <td style="background-color:#EFEDED"><b>'.$row_RsReque['CODIGO'].'</b></td>
		 <td>No orden de pedido</td>
		 <td style="background-color:#EFEDED"></td>
		 <td>No orden de trabajo</td>
		 <td style="background-color:#EFEDED"></td>
		 <td>No orden de servicio</td>
		 <td style="background-color:#EFEDED"></td>
		</tr>
	  </table>';
$pdf->writeHTML($tbl, true, false, false, false, '');

$tbl='<table width="840" border="1">
        <tr>
		 <td colspan="12" align="center" style="color:#ccc">APROBACION</td>
		</tr>
		<tr align="center" nobr="true">
		 <td width="110">Consejo superior</td>
		 <td style="color:#ccc" width="50">SI</td>
		 <td style="color:#ccc" width="50">NO</td>
		 <td width="110">Comite de compras</td>
		 <td style="color:#ccc" width="50">SI</td>
		 <td style="color:#ccc" width="50">NO</td>
		 <td width="110">Comite de tecnologico</td>
		 <td style="color:#ccc" width="50">SI</td>
		 <td style="color:#ccc" width="50">NO</td>
		 <td width="110">Comite de infraestructura</td>
		 <td style="color:#ccc" width="50">SI</td>
		 <td style="color:#ccc" width="50">NO</td>
		</tr>
		<tr nobr="true">
		 <td colspan="3">&nbsp;&nbsp;Fecha:<br></td>
		 <td colspan="3">&nbsp;&nbsp;Fecha:<br></td>
		 <td colspan="3">&nbsp;&nbsp;Fecha:<br></td>
		 <td colspan="3">&nbsp;&nbsp;Fecha:<br></td>
		</tr>
	  </table>';
$pdf->writeHTML($tbl, true, false, false, false, '');
$tbl='<table border="1" width="840" height="70">
         <tr valign="top" nobr="true">
		    <td height="70">&nbsp;&nbsp;&nbsp;Instancia de aprobacion</td>
		    <td>&nbsp;&nbsp;&nbsp;RECTOR</td>
		    <td>&nbsp;&nbsp;&nbsp;DIRECCION ADMINISTRATIVA</td>
		 </tr>
</table>';
$pdf->writeHTML($tbl, true, false, false, false, '');
/*
$tbl = <<<EOD
<table cellspacing="0" cellpadding="1" border="1">
    <tr>
        <td rowspan="3">COL 1 - ROW 1<br />COLSPAN 3</td>
        <td>COL 2 - ROW 1alinavacariu</td>
        <td>COL 3 - ROW 1</td>
    </tr>
    <tr>
        <td rowspan="2">COL 2 - ROW 2 - COLSPAN 2<br />text line<br />text line<br />text line<br />text line</td>
        <td>COL 3 - ROW 2</td>
    </tr>
    <tr>
       <td>COL 3 - ROW 3</td>
    </tr>

</table>
EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');

// -----------------------------------------------------------------------------

$tbl = <<<EOD
<table cellspacing="0" cellpadding="1" border="1">
    <tr>
        <td rowspan="3">COL 1 - ROW 1<br />COLSPAN 3<br />text line<br />text line<br />text line<br />text line<br />text line<br />text line</td>
        <td>COL 2 - ROW 1</td>
        <td>COL 3 - ROW 1</td>
    </tr>
    <tr>
        <td rowspan="2">COL 2 - ROW 2 - COLSPAN 2<br />text line<br />text line<br />text line<br />text line</td>
         <td>COL 3 - ROW 2</td>
    </tr>
    <tr>
       <td>COL 3 - ROW 3</td>
    </tr>

</table>
EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');

// -----------------------------------------------------------------------------

$tbl = <<<EOD
<table cellspacing="0" cellpadding="1" border="1">
    <tr>
        <td rowspan="3">COL 1 - ROW 1<br />COLSPAN 3<br />text line<br />text line<br />text line<br />text line<br />text line<br />text line</td>
        <td>COL 2 - ROW 1</td>
        <td>COL 3 - ROW 1</td>
    </tr>
    <tr>
        <td rowspan="2">COL 2 - ROW 2 - COLSPAN 2<br />text line<br />text line<br />text line<br />text line</td>
         <td>COL 3 - ROW 2<br />text line<br />text line</td>
    </tr>
    <tr>
       <td>COL 3 - ROW 3</td>
    </tr>

</table>
EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');

// -----------------------------------------------------------------------------

$tbl = <<<EOD
<table border="1">
<tr>
<th rowspan="3">Left column</th>
<th colspan="5">Heading Column Span 5</th>
<th colspan="9">Heading Column Span 9</th>
</tr>
<tr>
<th rowspan="2">Rowspan 2<br />This is some text that fills the table cell.</th>
<th colspan="2">span 2</th>
<th colspan="2">span 2</th>
<th rowspan="2">2 rows</th>
<th colspan="8">Colspan 8</th>
</tr>
<tr>
<th>1a</th>
<th>2a</th>
<th>1b</th>
<th>2b</th>
<th>1</th>
<th>2</th>
<th>3</th>
<th>4</th>
<th>5</th>
<th>6</th>
<th>7</th>
<th>8</th>
</tr>
</table>
EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');

// -----------------------------------------------------------------------------

// Table with rowspans and THEAD
$tbl = <<<EOD
<table border="1" cellpadding="2" cellspacing="2">
<thead>
 <tr style="background-color:#FFFF00;color:#0000FF;">
  <td width="30" align="center"><b>A</b></td>
  <td width="140" align="center"><b>XXXX</b></td>
  <td width="140" align="center"><b>XXXX</b></td>
  <td width="80" align="center"> <b>XXXX</b></td>
  <td width="80" align="center"><b>XXXX</b></td>
  <td width="45" align="center"><b>XXXX</b></td>
 </tr>
 <tr style="background-color:#FF0000;color:#FFFF00;">
  <td width="30" align="center"><b>B</b></td>
  <td width="140" align="center"><b>XXXX</b></td>
  <td width="140" align="center"><b>XXXX</b></td>
  <td width="80" align="center"> <b>XXXX</b></td>
  <td width="80" align="center"><b>XXXX</b></td>
  <td width="45" align="center"><b>XXXX</b></td>
 </tr>
</thead>
 <tr>
  <td width="30" align="center">1.</td>
  <td width="140" rowspan="6">XXXX<br />XXXX<br />XXXX<br />XXXX<br />XXXX<br />XXXX<br />XXXX<br />XXXX</td>
  <td width="140">XXXX<br />XXXX</td>
  <td width="80">XXXX<br />XXXX</td>
  <td width="80">XXXX</td>
  <td align="center" width="45">XXXX<br />XXXX</td>
 </tr>
 <tr>
  <td width="30" align="center" rowspan="3">2.</td>
  <td width="140" rowspan="3">XXXX<br />XXXX</td>
  <td width="80">XXXX<br />XXXX</td>
  <td width="80">XXXX<br />XXXX</td>
  <td align="center" width="45">XXXX<br />XXXX</td>
 </tr>
 <tr>
  <td width="80">XXXX<br />XXXX<br />XXXX<br />XXXX</td>
  <td width="80">XXXX<br />XXXX</td>
  <td align="center" width="45">XXXX<br />XXXX</td>
 </tr>
 <tr>
  <td width="80" rowspan="2" >RRRRRR<br />XXXX<br />XXXX<br />XXXX<br />XXXX<br />XXXX<br />XXXX<br />XXXX</td>
  <td width="80">XXXX<br />XXXX</td>
  <td align="center" width="45">XXXX<br />XXXX</td>
 </tr>
 <tr>
  <td width="30" align="center">3.</td>
  <td width="140">XXXX1<br />XXXX</td>
  <td width="80">XXXX<br />XXXX</td>
  <td align="center" width="45">XXXX<br />XXXX</td>
 </tr>
 <tr>
  <td width="30" align="center">4.</td>
  <td width="140">XXXX<br />XXXX</td>
  <td width="80">XXXX<br />XXXX</td>
  <td width="80">XXXX<br />XXXX</td>
  <td align="center" width="45">XXXX<br />XXXX</td>
 </tr>
</table>
EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');

$pdf->writeHTML($tbl, true, false, false, false, '');

// -----------------------------------------------------------------------------

// NON-BREAKING TABLE (nobr="true")

$tbl = <<<EOD
<table border="1" cellpadding="2" cellspacing="2" nobr="true">
 <tr>
  <th colspan="3" align="center">NON-BREAKING TABLE</th>
 </tr>
 <tr>
  <td>1-1</td>
  <td>1-2</td>
  <td>1-3</td>
 </tr>
 <tr>
  <td>2-1</td>
  <td>3-2</td>
  <td>3-3</td>
 </tr>
 <tr>
  <td>3-1</td>
  <td>3-2</td>
  <td>3-3</td>
 </tr>
</table>
EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');

// -----------------------------------------------------------------------------

// NON-BREAKING ROWS (nobr="true")

$tbl = <<<EOD
<table border="1" cellpadding="2" cellspacing="2" align="center">
 <tr nobr="true">
  <th colspan="3">NON-BREAKING ROWS</th>
 </tr>
 <tr nobr="true">
  <td>ROW 1<br />COLUMN 1</td>
  <td>ROW 1<br />COLUMN 2</td>
  <td>ROW 1<br />COLUMN 3</td>
 </tr>
 <tr nobr="true">
  <td>ROW 2<br />COLUMN 1</td>
  <td>ROW 2<br />COLUMN 2</td>
  <td>ROW 2<br />COLUMN 3</td>
 </tr>
 <tr nobr="true">
  <td>ROW 3<br />COLUMN 1</td>
  <td>ROW 3<br />COLUMN 2</td>
  <td>ROW 3<br />COLUMN 3</td>
 </tr>
</table>
EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');
*/
// -----------------------------------------------------------------------------

//Close and output PDF document
$pdf->Output('reporterequerimiento.pdf', 'I');
?>