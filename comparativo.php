<?php
require_once('conexion/db.php');
	if (!isset($_SESSION)) {
  session_start();
}

// Validacion de seguridad
if(!isset($_SESSION['MM_AccesoCorrectoApp']) || $_SESSION['MM_AccesoCorrectoApp'] != 'ACTIVO'){
  header("location: index.php");
}

$currentPage = $_SERVER["PHP_SELF"];

$estado_filtro='';
if(isset($_POST['estado_filtro']) && $_POST['estado_filtro'] !=''){
 $estado_filtro = $_POST['estado_filtro'];
}
$codigo_filtro='';
if(isset($_POST['codigo_filtro']) && $_POST['codigo_filtro'] !=''){
 $codigo_filtro = $_POST['codigo_filtro'];
}
$codorden='';
if(isset($_GET['codorden']) && $_GET['codorden'] !=''){
 $codorden = $_GET['codorden'];
}




 
    

    
	$query_RsListaRequerimientos="SELECT O.COORCODI CODIGO_ORDEN,
	                                     DATE_FORMAT(O.COORFECR, '%d/%m/%Y') FECHA_CREACION,
	                                     DATE_FORMAT(O.COORFEEN, '%d/%m/%Y') FECHA_ENVIO,
										 O.COORPERE PERSONA_REGISTRA,
										 (SELECT P.PERSNOMB 
										    FROM PERSONAS P
										  WHERE P.PERSID = O.COORPERE )PERSONA_REGISTRA_DES,
										 O.COORESTA ESTADO,
										 CASE O.COORESTA 
										  WHEN 0
										   THEN 'CREADO'
										  WHEN 1
										   THEN 'ENVIADO' 
										  ELSE ''
										 END ESTADO_DES
								   FROM COTIZACION_ORDEN   O
							    where 1 ";
									   	  
if($codorden!=''){
  $query_RsListaRequerimientos = $query_RsListaRequerimientos." AND O.COORCODI = '".$codorden."'";	
}  
  if($_SESSION['MM_RolID']==2){	

	      
  }
 
	$RsListaRequerimientos = mysqli_query($conexion,$query_RsListaRequerimientos) or die(mysqli_error($conexion));
	$row_RsListaRequerimientos = mysqli_fetch_array($RsListaRequerimientos);
    $totalRows_RsListaRequerimientos = mysqli_num_rows($RsListaRequerimientos); 

    $query_RsListaComparar ="SELECT DE.DEREDESC DESCRIPCION,
	                                DE.DERECANT CANTIDAD,
									 IFNULL(D.CODEVALO,'-') VALOR,
									 IFNULL(D.CODEDESC,'-') DESCRIPCION_PROVEEDOR,
									 C.COTIPROV PROVEEDOR,
									 C.COTICODI CODIGO_COTIZACION,
									 IFNULL(C.COTIOBSE,'-') OBSERVACIONES,
									 IFNULL(C.COTIGARA,'-') GARANTIA,
									 IFNULL(C.COTITIEN,'-') TIEMPO_ENTREGA,
									 (SELECT P.PROVNOMB 
									   FROM PROVEEDORES P
									  WHERE P.PROVCODI = C.COTIPROV LIMIT 1) PROVEEDOR_DES
	                          FROM COTIZACION          C,
							       COTIZACION_ORDEN    O,
								   COTIZACION_DETALLE  D,
								   DETALLE_REQU        DE
							WHERE C.COTIORDE = O.COORCODI
							  AND C.COTICODI = D.CODECOTI
                              AND D.CODEDETA = DE.DERECONS							  
							  AND O.COORCODI = '".$codorden."'
							 order by DE.DERECONS";
							  //echo $query_RsListaComparar;
	$RsListaComparar = mysqli_query($conexion,$query_RsListaComparar) or die(mysqli_error($conexion));
	$row_RsListaComparar = mysqli_fetch_array($RsListaComparar);
    $totalRows_RsListaComparar = mysqli_num_rows($RsListaComparar); 

?>
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



//paginación

 function f_abrir_link(v_link)
	{
	  document.form1.action=v_link;
	  document.form1.submit();

    }
</script>
<link rel="stylesheet" type="text/css" href="css/estilo_solicitud.css" />
<style type="text/css">
.contenttable{
 width:750px;
 overflow:hidden;
 min-height:150px;
 border-radius:12px;
}
.labeltext {
    color: #333333;
    font-family: Verdana,Arial,Helvetica,sans-serif;
    font-size: 13px;
    font-weight: 600;
}
</style>
 <script type="text/javascript">
  function mostrarfiltros(){
   $( "#divfiltros" ).toggle();
  }
  
  function limpiarfiltros (campo){
  document.getElementById(''+campo).value=""; 
  }
  function Busqueda(){
   document.form1.action="home.php?page=requerimientos_lista";
  }
  function FcrearOrden(){
   alert('en desarrollo');
   return false;
  }
 </script>
</head>

<body>
<div id="pagina">
<body>
 <form name="form1" id="form1" method="post" action="">
<?php /*<div id="wrapper">*/ ?>

 <div class="contenttable" style="min-width:1000px; width:100%;">
           
 <table>


<?php
	
    if($totalRows_RsListaRequerimientos >0){
	 ?>
	   <tr >
		   <td class="labeltext">Codigo Orden</td>
		   <td><?php echo($row_RsListaRequerimientos['CODIGO_ORDEN']);?></td>
		 </tr>
		<tr>
		   <td class="labeltext">estado</td>
		   <td><?php echo($row_RsListaRequerimientos['ESTADO_DES']);?></td>
		 </tr>
		<tr>
		   <td class="labeltext">Fecha Creacion</td>
		   <td><?php echo($row_RsListaRequerimientos['FECHA_CREACION']);?></td>
		 </tr>
		 <tr>
		   <td class="labeltext">Fecha Envio</td>
		   <td><?php echo($row_RsListaRequerimientos['FECHA_ENVIO']);?></td>
		</tr>
		<tr>
		   <td class="labeltext">Fecha Envio</td>
		   <td><?php echo($row_RsListaRequerimientos['PERSONA_REGISTRA_DES']);?></td>
		</tr>
	
	  <tr CLASS="<?php //echo($estilo);?>">
	    <td>
		</td>
	    
	  </tr>
	  <?php
	  
	}
	?>

    

</table>
<div style="margin:11px 0 11px 0;">
 <input type="submit" onclick="return FcrearOrden();" value="Crear Orden de Compra" class="button2">
</div>
<table class="bordered" style="color:#555; min-width:1000px; width:100%;" border="0">
  <thead style="color:#ffffff">
    <th></th>
    <th>Cod cotizacion</th>
    <th>Proveedor</th>
    <th>Detalle</th>
	<th>Cantidad</th>
	<th>valor</th>
	<th>Descripcion del Proveedor</th>
	<th>Garantia</th>
	<th>Tiempo de Entrega</th>
	<th>Observaciones</th>
  </thead>
  <?php
    if($totalRows_RsListaComparar >0){
	  do{
	?>
     <tr>
	   <td><input type="checkbox"></td>
	   <td><?php echo($row_RsListaComparar['CODIGO_COTIZACION']);?></td>
	   <td><b><?php echo($row_RsListaComparar['PROVEEDOR_DES']);?></b></td>
	   <td><?php echo($row_RsListaComparar['DESCRIPCION']);?></td>
	   <td><?php echo($row_RsListaComparar['CANTIDAD']);?></td>
	   <td><?php if($row_RsListaComparar['VALOR']!='-'){echo '$'.(number_format($row_RsListaComparar['VALOR'],0,'.',',')); }else{ echo('-');} ?></td>
	   <td><?php echo($row_RsListaComparar['DESCRIPCION_PROVEEDOR']);?></td>
	   <td><?php echo($row_RsListaComparar['GARANTIA']);?></td>
	   <td><?php echo($row_RsListaComparar['TIEMPO_ENTREGA']);?></td>
	   <td><?php echo($row_RsListaComparar['OBSERVACIONES']);?></td>
	 </tr>
	<?php	
	    }while($row_RsListaComparar = mysqli_fetch_array($RsListaComparar));
	} 

  ?>
</table>

</div>
</form>