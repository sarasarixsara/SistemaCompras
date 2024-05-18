<?php 
require_once('conexion/db.php');
	if (!isset($_SESSION)) {
  session_start();
}
if(!isset($_SESSION['MM_AccesoCorrectoApp']) || $_SESSION['MM_AccesoCorrectoApp'] != 'ACTIVO'){
 exit('no autorizado');
}
$fecha_inicio_encuesta = '';
if(isset($_POST['fecha_inicio_encuesta']) && $_POST['fecha_inicio_encuesta']!=''){
	$fecha_inicio_encuesta = $_POST['fecha_inicio_encuesta'];
}
$fecha_fin_encuesta = '';
if(isset($_POST['fecha_fin_encuesta']) && $_POST['fecha_fin_encuesta']!=''){
	$fecha_fin_encuesta = $_POST['fecha_fin_encuesta'];
}

$nopass = '1';
if($fecha_inicio_encuesta=='' && $fecha_fin_encuesta == ''){
	$nopass = -1;
}
    $query_RsListaPreguntas = "select P.PREGCODI PREGUNTA,
									  P.PREGDESC PREGUNTA_DES,
									  P.PREGTIPO TIPO_PREGUNTA,
									  (select COUNT(E.ENPECODI) 
									    from encuesta_pers E
 									   where E.ENPEENCU = '1'
									   AND E.ENPEESTA = '1'
									  ) TOTAL_ENCUESTAS
								 FROM preguntas P
								WHERE P.PREGENCU = '".$nopass."'
								";
	$RsListaPreguntas = mysqli_query($conexion,$query_RsListaPreguntas) or die(mysqli_error($conexion));
	$row_RsListaPreguntas = mysqli_fetch_assoc($RsListaPreguntas);
    $totalRows_RsListaPreguntas = mysqli_num_rows($RsListaPreguntas);
	$preguntas = array();
	if($totalRows_RsListaPreguntas>0){
		do{
			$preguntas[] = $row_RsListaPreguntas;
		}while($row_RsListaPreguntas = mysqli_fetch_assoc($RsListaPreguntas));
	}
?><?php /*<!DOCTYPE html>

<html>
<!-- inicio del html -->
	<head>
		<title>eval calif</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />*/?>

<script type="text/javascript">
$(document).ready(function(){
	$("#fecha_inicio_encuesta, #fecha_fin_encuesta").datepicker({
	   showOn: 'both',
	   buttonImage: 'images/calendar.png',
	   buttonImageOnly: true,
	   changeYear: true,
	   changeMonth:true,
	   showWeek: true,
	   dateFormat: 'dd/mm/yy',
	   //minDate: 0,

	   //regional:'es',
	   //numberOfMonths: 2,
	   onSelect: function(fech, objDatepicker){
		   
	   }
	});
});

function f_buscarEval(){
	if($("#fecha_inicio_encuesta").val() != '' || $("#fecha_fin_encuesta").val() !=''){
	document.form_calif.submit();
	}else{
		return false;
	}
}
</script>
<body>
<form name="form_calif" method="post" action="">
<table>
  <tr>
    <td>Fecha Inicio</td>
	<td><input type="text" name="fecha_inicio_encuesta" id="fecha_inicio_encuesta" value="<?php echo($fecha_inicio_encuesta);?>"></td>
  </tr>
  <tr>
	<td>Fecha Fin</td>
	<td><input type="text" name="fecha_fin_encuesta" id="fecha_fin_encuesta" value="<?php echo($fecha_fin_encuesta);?>"></td>
  </tr>
  <tr>
    <td colspan="2" align="center">
	 <input type="submit" name="Buscar" onclick="return f_buscarEval();" value="Buscar">
	</td>
  </tr>
</table>
</form>
<table>
   <tr>
    <td class="SB" align="center" colspan="8"><b>Calificaci&oacute;n de atenci&oacute;n a compras</b><br><span style="font-size:11px;">total requerimientos encuestados <b><?php if(count($preguntas)>0){ echo($preguntas[0]['TOTAL_ENCUESTAS']); }else{ echo('0');} ?></b></span></td>
   </tr><?php	
	if(count($preguntas>0)){
		$i=0;
		for($j=0; $j<count($preguntas); $j++){
			$i++;
			$query_RsRespuestas = "select count(O.PROPCODI) TOTAL,
										  O.PROPNOMB NOMBRE,
										  ((select count(O2.PROPCODI) TOTAL
										  from encuesta_pers     E,
										   preguntas_resp    R,
										   preguntas_opciones O2
									 WHERE E.ENPECODI = R.PRRECEPE
									   AND R.PRREPREG = '".$preguntas[$j]['PREGUNTA']."'
									   AND E.ENPEENCU = '1'
									   AND E.ENPEESTA = '1'
									   AND R.PRRERESP =  O2.PROPCODI)  ) TOTAL_RESP
									  from encuesta_pers     E,
										   preguntas_resp    R,
										   preguntas_opciones O,
										   requerimientos    RE
									 WHERE E.ENPECODI = R.PRRECEPE
									   AND E.ENPEREQU = RE.REQUCODI
									   AND R.PRREPREG = '".$preguntas[$j]['PREGUNTA']."'
									   AND E.ENPEENCU = '1'
									   AND E.ENPEESTA = '1'
									   AND R.PRRERESP =  O.PROPCODI
									  #AND RE.=1 aqui las fechas
									 group by O.PROPCODI
									";
			$RsRespuestas = mysqli_query($conexion,$query_RsRespuestas) or die(mysqli_error($conexion));
			$row_RsRespuestas = mysqli_fetch_assoc($RsRespuestas);
			$totalRows_RsRespuestas = mysqli_num_rows($RsRespuestas);	
			if($i%2 == 0){
				$estilo = "SB";
			}else{
				$estilo = "";
			}
			?>
		    <tr class="SB">
				<td  height="30"><?php echo($preguntas[$j]['PREGUNTA_DES']);?></td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td >
					<table>
					  <?php 
					  if($totalRows_RsRespuestas>0){
							do{
								$porcentaje = 0;
								$porcentaje = ($row_RsRespuestas['TOTAL']/$row_RsRespuestas['TOTAL_RESP'])*100;
						?>
						<tr>
							<td class="SB" width="120"><?php echo($row_RsRespuestas['NOMBRE']);?></td>
							<td class="SB" width="100"><div style="width:100px; border: solid 1px #45BA44"><div style="width:<?php echo($porcentaje);?>%; background:#D0E8AA;">&nbsp;</div></div></td>
							<td class="SB">&nbsp;<span style="font-size:11px;"><?php echo($porcentaje);?>%</span>&nbsp;</td>
							<?php /*<td class="SB">&nbsp;&nbsp;<span><?php echo($row_RsRespuestas['TOTAL']);?></span>&nbsp;</td> */?>
						</tr>
						<?php		
							}while($row_RsRespuestas = mysqli_fetch_assoc($RsRespuestas));
					  }
					  ?>
				  </table>
				</td>
			</tr>
			<?php
		}
	}
?><?php /*
</table>
	</body>
</html>
*/
?>