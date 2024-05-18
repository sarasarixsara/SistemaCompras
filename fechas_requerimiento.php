<?php
//inicio del php

require_once('conexion/db.php');

//Declaracion de variables
$codigo='';
if(isset($_GET['codigo'])&&$_GET['codigo']!='')
{
$codigo=$_GET['codigo'];
}
$fecha_desde = '';
$fecha_hasta = '';

$tipoGuardar='Guardar';

$currentPage = $_SERVER["PHP_SELF"];
$tamanoPagina = 12;
$maxRows_RsFechasLista = $tamanoPagina;
$pageNum_RsFechasLista = 0;
if (isset($_GET['pageNum_RsFechasLista'])) {
  $pageNum_RsFechasLista = $_GET['pageNum_RsFechasLista'];
}
$startRow_RsFechasLista = $pageNum_RsFechasLista * $maxRows_RsFechasLista;

if($codigo!=''){
$query_RsFechas="SELECT F.FERECODI CODIGO,
                        DATE_FORMAT(F.FEREFEDE, '%d/%m/%Y') FECHA_DESDE,
                        DATE_FORMAT(F.FEREFEHA, '%d/%m/%Y') FECHA_HASTA
					from FECHAS_REQ F
				   WHERE F.FERECODI = '".$codigo."'";
				// echo($query_RsFechas);echo("<br>");
	$RsFechas = mysqli_query($conexion,$query_RsFechas) or die(mysqli_error());
	$row_RsFechas = mysqli_fetch_assoc($RsFechas);
    $totalRows_RsFechas = mysqli_num_rows($RsFechas);
	if($totalRows_RsFechas>0){
	 $fecha_desde = $row_RsFechas['FECHA_DESDE'];
	 $fecha_hasta = $row_RsFechas['FECHA_HASTA'];
	 $tipoGuardar = 'Editar';
	}
}
    $query_RsFechasLista="SELECT F.FERECODI CODIGO,
                        DATE_FORMAT(F.FEREFEDE, '%d/%m/%Y') FECHA_DESDE,
                        DATE_FORMAT(F.FEREFEHA, '%d/%m/%Y') FECHA_HASTA,
						case F.FEREESTA
						when 0
						      THEN 'ACTIVO'
						 when 1
							  THEN 'INACTIVO'
						    ELSE 'INACTIVO'
							END ESTADO	 
					from FECHAS_REQ F
				   WHERE 1
				     order by F.FERECODI DESC";
				// echo($query_RsFechasLista);echo("<br>");
	$RsFechasLista = mysqli_query($conexion,$query_RsFechasLista) or die(mysqli_error());
	$row_RsFechasLista = mysqli_fetch_assoc($RsFechasLista);
    $totalRows_RsFechasLista = mysqli_num_rows($RsFechasLista);

   $query_limit_RsFechasLista = sprintf("%s LIMIT %d, %d", $query_RsFechasLista, $startRow_RsFechasLista, $maxRows_RsFechasLista);
	$RsFechasLista = mysqli_query($conexion,$query_limit_RsFechasLista) or die(mysqli_connect_error());
	$row_RsFechasLista = mysqli_fetch_array($RsFechasLista);
    
if (isset($_GET['totalRows_RsFechasLista'])) {
  $totalRows_RsFechasLista = $_GET['totalRows_RsFechasLista'];
} else {
  $all_RsFechasLista = mysqli_query($conexion, $query_RsFechasLista);
  $totalRows_RsFechasLista = mysqli_num_rows($all_RsFechasLista);
}
	

//paginacion
$totalPages_RsFechasLista = ceil($totalRows_RsFechasLista/$maxRows_RsFechasLista)-1;

$queryString_RsFechasLista = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_RsFechasLista") == false &&
        stristr($param, "totalRows_RsFechasLista") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_RsFechasLista = "&" . htmlentities(implode("&", $newParams));
  }
}

$queryString_RsFechasLista = sprintf("&totalRows_RsFechasLista=%d%s", $totalRows_RsFechasLista, $queryString_RsFechasLista);

$paginaHasta = 0;
if ($pageNum_RsFechasLista == $totalPages_RsFechasLista)
{
	$paginaHasta = $totalRows_RsFechasLista;
}
else
{
	$paginaHasta = ($pageNum_RsFechasLista+1)*$maxRows_RsFechasLista;
}	
?><!DOCTYPE html>

<html>
<!-- inicio del html -->
<head>

<title>Sanboni-Compras</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/jquery.ui.css"/>
<script type="text/javascript" src="js/jquery.ui.1.8.16.js"></script>
<script type="text/javascript">
	function getDataServer(url, vars){
		 var xml = null;
		 try{
			 xml = new ActiveXObject("Microsoft.XMLHTTP");
		 }catch(expeption){
			 xml = new XMLHttpRequest();
		 }
		 xml.open("GET",url + vars, false);
		 xml.send(null);
		 if(xml.status == 404) alert("Url no valida");
		 return xml.responseText;
	}
// inicio del javascript
//crear usuario
function validarCampos(){

//validacion de nuevo usuario
 if($("#fecha_desde").val() == '')
  {
   inlineMsg('fecha_desde','debe ingresar la fecha desde',3);
		return false;
  }
 if($("#fecha_hasta").val() == '')
  {
   inlineMsg('fecha_hasta','debe ingresar la fecha hasta',3);
		return false;
  }  
  
 if($("#fecha_desde").val() != "" && $("#fecha_hasta").val() != "" )
{
			fd = $("#fecha_desde").val().split("/");
			fh = $("#fecha_hasta").val().split("/");
			fd1 = new Date(fd[2], (fd[1] - 1), fd[0]);
			fh1 = new Date(fh[2], (fh[1] - 1), fh[0]);
			if (fh1 < fd1) {
				alert("La Fecha desde no puede ser menor que la Fecha hasta.");
				return false;
			}
}
  
  <?php 
    if($tipoGuardar=='Guardar'){
	?>
	var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=validarFechasRequerimiento&fecha_desde="+$("#fecha_desde").val());	
	//alert(v_dato);
	if(v_dato!=''){
	 if(v_dato=='0')
	 {
	   alert('la fecha desde debe ser mayor que la fecha hasta del ultimo periodo ingresado');
	   return false;
	 }
	}
	<?php
	}
  ?>
			if(confirm('Esta seguro de guardar estos datos?'))
			{
				document.form1.action="fechas_requerimiento_guardar.php?tipoGuardar=<?php echo($tipoGuardar);?>";
				document.form1.submit(); 
			}
}	
	


 
  function volveraListado(){
   document.form1.action ="home.php?page=listar_usuarios";
 document.form1.submit();
 }
 
  function f_abrir_link(v_link)
	{
	  document.form1.action=v_link;
	  document.form1.submit();

    }
</script>
 <script type="text/javascript">
function upperCase() {
   var x=document.getElementById("nombre").value;
   var y=document.getElementById("apellido").value;
   document.getElementById("nombre").value=x.toUpperCase();
   document.getElementById("apellido").value=y.toUpperCase();
}
$(document).ready(function() {
jQuery(function($){
   $.datepicker.regional['es'] = {
      closeText: 'Cerrar',
      prevText: '<Ant',
      nextText: 'Sig>',
      currentText: 'Hoy',
      monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
      monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
      dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
      dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
      dayNamesMin: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
      weekHeader: 'Sm',
      dateFormat: 'dd/mm/yy',
      firstDay: 1,
      isRTL: false,
      showMonthAfterYear: false,
      yearSuffix: ''};
   $.datepicker.setDefaults($.datepicker.regional['es']);
});		
	
			
$("#fecha_desde, #fecha_hasta").datepicker({
   showOn: 'both',
   buttonImage: 'images/calendar.png',
   buttonImageOnly: true,
   changeYear: true,
   dateFormat: 'dd/mm/yy',
   //regional:'es',
   //numberOfMonths: 2,
   onSelect: function(fech, objDatepicker){
      //alert("fecha seleccionada: " + fech);
   }
});			

});
</script>
</head>

<body>
<form name="form1" id="form1" action="" method="post">
 <table border="0" class="tableadmin">
    <tr>
	  <td width="100" class="SLAB2" colspan="2" ><h4>FECHAS REQUERIMIENTO</h4></td>	  
	</tr>
	<tr>
	  <td class="SLAB2">Fecha Desde</td>
	  <td class="SLAB2 ">Fecha hasta</td>
	</tr>
	<tr>
	  <td width="180"><input type="text" placeholder="Fecha desde" name="fecha_desde" id="fecha_desde" value="<?php echo($fecha_desde);?>" readonly></td>
	  <td width="180"><input type="text" placeholder="Fecha hasta" name="fecha_hasta" id="fecha_hasta" value="<?php echo($fecha_hasta);?>" readonly></td>
	</tr>
	<tr><td >
	  <input type="hidden" name="codigo_fecha" id="codigo_fecha" value="<?php echo($codigo);?>">
	  <input class="button2" type="button" name="guardarprov" value="Guardar" onclick="validarCampos();">
	  </td>
	</tr>
	<tr>
	 <td><br></td>
	</tr>
  </table>
  <table>
    <tr class="SLAB trtitle">
	 <td></td>
	 <td>Fecha Desde</td>
	 <td>Fecha hasta</td>
	 <td>Estado</td>
	</tr>
	<?php 
    if($totalRows_RsFechasLista>0){
	  $i = 0;
	  do{
	     $i++;
		 if($i%2==0){
		  $clase="SB";
		 }else{
		  $clase="SB2";
		 }
	  ?>
	  <tr class="<?php echo($clase);?>">
	    <td><a  href="home.php?page=fechas_requerimiento&codigo=<?php echo($row_RsFechasLista['CODIGO']);?>" class="buttonazul"> Editar <a></td>
	    <td><?php echo($row_RsFechasLista['FECHA_DESDE']);?></td>
	    <td><?php echo($row_RsFechasLista['FECHA_HASTA']);?></td>
	    <td><?php echo($row_RsFechasLista['ESTADO']);?></td>
	  </tr>
	  <?php
	    }while($row_RsFechasLista = mysqli_fetch_assoc($RsFechasLista));
	}
	?>
  </table>
  <table border="0" align="left" class="datagrid" >
		 <tr>
		  <td colspan="4">&nbsp;</td>
		 </tr>
		  <tr class="texto_gral">
			<td>
			 <ul>
			   <?php if ($pageNum_RsFechasLista > 0) { // Show if not first page ?>
			   <li>
				  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsFechasLista=%d%s", $currentPage, 0, $queryString_RsFechasLista); ?>')" class="submenus">Primero</a>
               </li>
				  <?php } // Show if not first page ?>
			   <?php if ($pageNum_RsFechasLista > 0) { // Show if not first page ?>
			    <li>
				  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsFechasLista=%d%s", $currentPage, max(0, $pageNum_RsFechasLista - 1), $queryString_RsFechasLista); ?>')" class="submenus">Anterior</a>
				 </li>
				  <?php } // Show if not first page ?>
			<?php if ($pageNum_RsFechasLista < $totalPages_RsFechasLista) { // Show if not last page ?>
			     <li>
                  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsFechasLista=%d%s", $currentPage, min($totalPages_RsFechasLista, $pageNum_RsFechasLista + 1), $queryString_RsFechasLista); ?>')" class="submenus">Siguiente</a>
				 </li>
				  <?php } // Show if not last page ?>
			<?php if ($pageNum_RsFechasLista < $totalPages_RsFechasLista) { // Show if not last page ?>
			      <li>
                  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsFechasLista=%d%s", $currentPage, $totalPages_RsFechasLista, $queryString_RsFechasLista); ?>')" class="submenus">&Uacute;ltimo</a>
				  </li>
				  <?php } // Show if not last page ?>
				</ul>
			</td>
		  </tr>
		</table>
 </form>
 
</body>

</html>