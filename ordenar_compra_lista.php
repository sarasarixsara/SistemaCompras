<?php
require_once('conexion/db.php');
	if (!isset($_SESSION)) {
  session_start();
}
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

$nombre_filtro = '';
if(isset($_POST['nombre_filtro']) && $_POST['nombre_filtro'] !=''){
 $nombre_filtro = $_POST['nombre_filtro'];
}

$orden_filtro = '';
if(isset($_POST['orden_filtro']) && $_POST['orden_filtro'] !=''){
 $orden_filtro = $_POST['orden_filtro'];
}
$firmado_filtro = '';
if(isset($_POST['firmado_filtro']) && $_POST['firmado_filtro'] !=''){
 $firmado_filtro = $_POST['firmado_filtro'];
}

$dir_administra ='39550544';

//CONSULTA DE PARAMETRO DE CODIGO DE ORDEN
	$query_RsParametroCodigo = "SELECT PARADEFI FROM PARAMETROS WHERE PARANOMB = 'COD_ORDEN'";
	$RsParametroCodigo = mysqli_query($conexion,$query_RsParametroCodigo) or die(mysqli_error($conexion));
	$row_RsParametroCodigo = mysqli_fetch_array($RsParametroCodigo);

	$parametro= $row_RsParametroCodigo['PARADEFI'];

$tamanoPagina = 20;
$maxRows_RsListaRequerimientos = $tamanoPagina;
$pageNum_RsListaRequerimientos = 0;
if (isset($_GET['pageNum_RsListaRequerimientos'])) {
  $pageNum_RsListaRequerimientos = $_GET['pageNum_RsListaRequerimientos'];
}
$startRow_RsListaRequerimientos = $pageNum_RsListaRequerimientos * $maxRows_RsListaRequerimientos;

 
    
	$query_RsEstados="SELECT E.ESTACODI CODIGO,
	                         E.ESTANOMB NOMBRE
					   FROM ESTADOS E";
	$RsEstados = mysqli_query($conexion,$query_RsEstados) or die(mysqli_connect_error());
	$row_RsEstados = mysqli_fetch_array($RsEstados);
    $totalRows_RsEstados = mysqli_num_rows($RsEstados);

	$query_RsListaRequerimientos="SELECT `ORCOCONS` CODIGO,
										 `ORCOFECH` FECHA_ORDENADO,
										 `ORCOIDPR` ID_PROVEEDOR,
										  PROVNOMB  PROVEEDOR_DESC,
										 `ORCOFEEN` FECHA_ENTREGA,
										 `ORCOOBSE` OBSERVACIONES,
										 (SELECT `FIRMCODI`
										  FROM `firmas` 
										  WHERE `ORCOFIRM`=`FIRMCONS`) FIRMA_DESC,
										  ORCOFIRM  FIRMA,
										  (SELECT `TOCONOMB` 
										  FROM `tipoorden_compra` 
										  WHERE `TOCOCODI`=ORCOTIOR)  TIPO_ORDEN_DESC,
										  IFNULL(ORCOTOEN,'0') TODO_RECIBIDO,
										  ORCOFIRM2 AUTORIZA_DIRECTORADMIN,
										  ORCOENTR  ENTREGA_ORDEN,
										  ORCOANUL ANULADO,
										  CASE ORCOANUL
										   WHEN 1 THEN '#ff837a'
										   ELSE ''
										   END BG_ANULADO
							      FROM `orden_compra`,
    								    proveedores 
								  WHERE PROVCODI=ORCOIDPR
								  ";

	if($nombre_filtro!=''){
	  $query_RsListaRequerimientos .= " AND proveedores.PROVNOMB like '%".$nombre_filtro."%'  ";
	} 	
	if($orden_filtro!=''){
	  $query_RsListaRequerimientos .= " AND orden_compra.ORCOCONS = '".$orden_filtro."'  ";
	}  
	if($firmado_filtro!=''){
		if($firmado_filtro == '0'){
			$query_RsListaRequerimientos .= " AND orden_compra.ORCOFIRM = ''  ";
		}		
		if($firmado_filtro == '1'){
			$query_RsListaRequerimientos .= " AND orden_compra.ORCOFIRM != ''  ";
		}
	}  
	
	$query_RsListaRequerimientos .= " ORDER BY ORCOCONS desc";
  									   
 
   //echo($query_RsListaRequerimientos);
   $query_limit_RsListaRequerimientos = sprintf("%s LIMIT %d, %d", $query_RsListaRequerimientos, $startRow_RsListaRequerimientos, $maxRows_RsListaRequerimientos);
	$RsListaRequerimientos = mysqli_query($conexion,$query_limit_RsListaRequerimientos) or die(mysqli_connect_error());
	$row_RsListaRequerimientos = mysqli_fetch_array($RsListaRequerimientos);

$firmado = $row_RsListaRequerimientos['FIRMA'];
    
if (isset($_GET['totalRows_RsListaRequerimientos'])) {
  $totalRows_RsListaRequerimientos = $_GET['totalRows_RsListaRequerimientos'];
} else {
  $all_RsListaRequerimientos = mysqli_query($conexion, $query_RsListaRequerimientos);
  $totalRows_RsListaRequerimientos = mysqli_num_rows($all_RsListaRequerimientos);
}
	

//paginacion
$totalPages_RsListaRequerimientos = ceil($totalRows_RsListaRequerimientos/$maxRows_RsListaRequerimientos)-1;

$queryString_RsListaRequerimientos = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_RsListaRequerimientos") == false &&
        stristr($param, "totalRows_RsListaRequerimientos") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_RsListaRequerimientos = "&" . htmlentities(implode("&", $newParams));
  }
}

$queryString_RsListaRequerimientos = sprintf("&totalRows_RsListaRequerimientos=%d%s", $totalRows_RsListaRequerimientos, $queryString_RsListaRequerimientos);

$paginaHasta = 0;
if ($pageNum_RsListaRequerimientos == $totalPages_RsListaRequerimientos)
{
	$paginaHasta = $totalRows_RsListaRequerimientos;
}
else
{
	$paginaHasta = ($pageNum_RsListaRequerimientos+1)*$maxRows_RsListaRequerimientos;
}
	//Roles para ver firma

function existeRolFirma(){
	$rolesFirma = array(3,5); //2 direccion administrativa, 5 rector
	if(in_array( $_SESSION['MM_RolID'], $rolesFirma)){
		return true;
	} return false;
}

?>
<style type="text/css">

.info{
	color: #151313 !important;
	font-size: 9px;
  background: #F4EBEA;
  padding: 2px 2px;
  border: solid 1px #B8A1A1;
  border-radius: 5px;
  box-shadow: 1px 2px 3px 1px #D5D5D5;
}
.contenttable{
 width:100%;
 overflow:hidden;
 min-height:150px;
 border-radius:12px;
}
.TB_overlayBGcot {
  height: 100%;
  left: 0;
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 1500;
  background-color: #FFFFFF;
  opacity: 0.55;
}  
.no_visible {
  display: none;
}
#tbl_orden {
	font-size:11px;
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
  function close_popup(){
document.getElementById('TB_overlaycot').className="";
document.getElementById('popup_cot').className="no_visible";
}
function reiniciarBusqueda(){
	document.form1.action = "home.php?page=ordenar_compra_lista";
	document.form1.submit();
}
 </script>
</head>

<body>
<div id="TB_overlaycot" class="" onclick="close_popup();"></div>
	   <div id="popup_cot" style="float:left; border: solid 3px #780002; width:70%; position:absolute; z-index:9999; background:#f2f2f2; margin: 0 13%; padding:10px; border-radius:8px;" class="no_visible">
	   
	   </div>
<div id="pagina">
 <form name="form1" id="form1" method="post" action="">
<?php /*<div id="wrapper">*/ ?>

			<div id="divfiltros" style="display:none; border:solid 1px #ccc; width:100%;">
 				<table width="100%" class="table">
   					<tr>
     					<td class="SLAB trtitle" colspan="9" align="center">Filtros de Busqueda</td>
   					</tr>
						<tr>
							<td class="SB">Proveedor</td>
							<td>
	 							<input type="text" name="nombre_filtro" id="nombre_filtro" value="<?php echo($nombre_filtro);?>" class="form-control">
							</td>
							<td>
								<button type="button" class="btn btn-default "  name="fcategoria" id="fcategoria"  onclick="limpiarfiltros('nombre_filtro');"><i class="fa fa-close"></i></button>
							</td>
							<td class="SB">Firmado</td>
							<td>
								<select class="form-control form-control-sm" name="firmado_filtro">
									<option>...Todos...</option>
									<option value="0" <?php if($firmado_filtro == '0') { echo "selected"; }?> >Sin firmar</option>
									<option value="1"  <?php if($firmado_filtro == '1') { echo "selected"; }?> >Firmado</option>
								</select>
							</td>
   						</tr>
						<tr>
						<td class="SB">Código Orden Compra</td>
    						<td>
								<input type="number" name="orden_filtro" id="orden_filtro" value="<?php echo($orden_filtro);?>" class="form-control">
							</td>
							<td>
								<button type="button" class="btn btn-default "  name="fcategoria" id="fcategoria"  onclick="limpiarfiltros('orden_filtro');"><i class="fa fa-close"></i></button>
							</td>							
						</tr>
  			 			<tr>
    					<td colspan="6" align="left">
	 <button type="button" name="butonfiltro" id="butonfiltro" class="button2" value="Buscar" onclick="reiniciarBusqueda()"><i class="fa fa-search"></i> Buscar</button>
	</td>
   </tr>
 </table>
 </div>

 <div class="contenttable">
 <button type="button" name="consultar" id="consultar" value="consultar" class="button2" onclick="mostrarfiltros();"><i class="fa fa-filter"></i>  Consultar</button>
 	<input  type="button" name="" id="" class="button2" value="Elaborar Orden " onclick="window.open('home.php?page=ordenar_compra','_blank');"  >
 <table width="100%">
 <tr>
<td colspan="8"> 	 <?php
			if ($totalRows_RsListaRequerimientos > 0)
		{
					?>
		Mostrando <b><?php echo($startRow_RsListaRequerimientos+1); ?></b> a <b><?php echo($paginaHasta); ?></b> de <b><?php echo($totalRows_RsListaRequerimientos);
		 ?></b> Registros
		<?php
					}
		else
		{
		?>
		Mostrando <b>0</b> a <b>0</b> de <b>0</b> Registros
		<?php
		}
		?>
</td>
</tr>

<?php
	
    if($totalRows_RsListaRequerimientos >0){
	 ?>
	   <tr class="SLAB trtitle" align="center">
		   <td width="17%"></td>
		   <td width="3%">Entrega</td>
		   <td width="5%">Orden</td>
		   <td width="15%">Firma</td>
		   <td width="5%">Tipo</td>
		   <td width="25%">Proveedor</td>
		   <td width="15%">Fecha de Orden</td>		   
		   <td width="15%">Fecha de Entrega</td>
		   
		   
		</tr>
	 <?php
	  $k=0;
	  do{
	    $k++;
	     if($k%2==0){
		  $estilo="SB";
		 }else{
		  $estilo="SB2";
		 }
	  ?>
	  <tr CLASS="<?php echo($estilo);?>" id="trorden_<?php echo($row_RsListaRequerimientos['CODIGO']);?>"
	  
	 <?php 
	  if($row_RsListaRequerimientos['ANULADO'] == '1'){ ?>
		style="background: <?php echo($row_RsListaRequerimientos['BG_ANULADO']); ?>";
		<?php
	  }
	 ?>
	  >
	    <td width="17%"><?php //echo($_SESSION['MM_RolID'].' - '.$row_RsListaRequerimientos['FIRMA']); ?>
		
		<!--link de pdf orden de compra -->
				<a href="O.php?codprov=<?php echo($row_RsListaRequerimientos['ID_PROVEEDOR']);?>&codcomp=<?php echo($row_RsListaRequerimientos['CODIGO']);?>&%=2&f=<?php echo($row_RsListaRequerimientos['FIRMA']);?>"  class=""  target="_blank" ><img  width="15"  height="20" src='imagenes/compare.png' border='0'></a>
		
		
		<!--botón de persona que autoriza la orden -->
		
		<?php if(($_SESSION['MM_RolID']==5 OR $_SESSION['MM_RolID']==3) && $row_RsListaRequerimientos['FIRMA']=='0'){ ?>
		<?php require_once("scripts/funcionescombo.php");		
													//aca
													$valor_orden=0;
													$estados = dameAutorizacionOrden($row_RsListaRequerimientos['CODIGO']);
													echo($estados);
													foreach($estados as $indice => $registro)
													{
														$valorOrden=number_format($registro['TOTAL']);
														
														$valor_orden=$valorOrden;													
													}
													$resultado = false;
													if($valor_orden <> 0 ){
														
													include("conf_montos.php");
													}
														if($resultado){?>
															
														 <input type="button" id="btnfirma_<?php echo($row_RsListaRequerimientos['CODIGO']);?>" class="buttonazul" value="<?php echo($resultado);?>" onclick="Firma_director_admin('<?php echo($row_RsListaRequerimientos['ID_PROVEEDOR']); ?>','<?php echo($row_RsListaRequerimientos['CODIGO']);?>','<?php echo($_SESSION['MM_UserID']);?>');"/>
		   	
														<?php }
													
													 
													?>
													
		  
		<?php } ?>	
        
		<!-- botónes de auxiliar compras  -->			
		


	<?php 
	  if($row_RsListaRequerimientos['ANULADO'] != '1'){ ?>
		<?php if($_SESSION['MM_RolID']==2){

		if($row_RsListaRequerimientos['FIRMA_DESC']!= ''){
		?>
		<input type="button" id="btnenvcorr_<?php echo($row_RsListaRequerimientos['CODIGO']);?>" class="buttonazul" value="enviar" onclick="f_Enviar_correo('<?php echo($row_RsListaRequerimientos['ID_PROVEEDOR']); ?>','<?php echo($row_RsListaRequerimientos['CODIGO']);?>');"/>
		<?php
		}
		?>	  
		<input type="button" id="btnanular_<?php echo($row_RsListaRequerimientos['CODIGO']);?>" class="buttonazul" value="anular" onclick="f_AnularOrden('<?php echo($row_RsListaRequerimientos['ID_PROVEEDOR']); ?>','<?php echo($row_RsListaRequerimientos['CODIGO']);?>');"/>		
		<?php
		if($row_RsListaRequerimientos['FIRMA'] != '0'){
		?>
		<?php if($row_RsListaRequerimientos['TODO_RECIBIDO'] == 0){ ?>
		<input title="falta por recibir" type="button" id="btnreccorr_<?php echo($row_RsListaRequerimientos['CODIGO']);?>"  
		class="buttonazul" value="recibir" onclick="f_RecibiraProveedor('<?php echo($row_RsListaRequerimientos['ID_PROVEEDOR']); ?>','<?php echo($row_RsListaRequerimientos['CODIGO']);?>');"/>
		<?php 
		  }else{
			  ?>
		<input title="recibido" type="button" id="btnreccorr_<?php echo($row_RsListaRequerimientos['CODIGO']);?>"  
		class="" value="recibir" onclick="f_RecibiraProveedor('<?php echo($row_RsListaRequerimientos['ID_PROVEEDOR']); ?>','<?php echo($row_RsListaRequerimientos['CODIGO']);?>');"/>	  
			  <?php
		  }
		?>
		<?php 
		}}
		?>		
		<?php
	  }
	 ?>		
		

		</td>
		<td width="3%">
			
			<?php 
         
			if($_SESSION['MM_RolID']==2){
				if($row_RsListaRequerimientos['ENTREGA_ORDEN'] == null && $row_RsListaRequerimientos['ANULADO'] != '1')
				{
			?>
							<input type="button" id="btnEntrega_<?php echo($row_RsListaRequerimientos['CODIGO']);?>"  
							class="" value="No" onclick="f_EntregarProveedor('<?php echo($row_RsListaRequerimientos['CODIGO']);?>');"/>	  
			<?php 
				}}

				if($row_RsListaRequerimientos['ENTREGA_ORDEN'] == '1')
				{ 
					echo('<div class="info">Fisico</div>');
				}
				if($row_RsListaRequerimientos['ENTREGA_ORDEN'] == '2')
				{ 
					echo('<div class="info">Correo</div>');
				}				 
			?>
		</td>	
	    <td width="5%"><?php echo($row_RsListaRequerimientos['CODIGO']);?></td>
		<td width="15%"><span id="spanfirma_<?php echo($row_RsListaRequerimientos['CODIGO']);?>">
			<?php if($row_RsListaRequerimientos['FIRMA'] != '0' && existeRolFirma()){echo ($row_RsListaRequerimientos['FIRMA_DESC']);}?></span>
			<?php if($row_RsListaRequerimientos['FIRMA'] != '0' && !existeRolFirma()){echo ('firmado');}?></span>
		</td>
		<td width="5%"><?php echo($row_RsListaRequerimientos['TIPO_ORDEN_DESC']);?></td>
		<td width="25%"><?php echo($row_RsListaRequerimientos['PROVEEDOR_DESC']);?></td>
	    <td bgcolor="" width="15%"><?php echo($row_RsListaRequerimientos['FECHA_ORDENADO']);?></td>	    	
		<td align="center" width="15%"><?php echo($row_RsListaRequerimientos['FECHA_ENTREGA']);?></td>
		
		
		
	  </tr>
	  <?php
	  
	   }while($row_RsListaRequerimientos = mysqli_fetch_array($RsListaRequerimientos));
	}else{
	?>
	<tr>
	  <td colspan="4">No existen registros</td>
	</tr>
	<?php
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
			   <?php if ($pageNum_RsListaRequerimientos > 0) { // Show if not first page ?>
			   <li>
				  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListaRequerimientos=%d%s", $currentPage, 0, $queryString_RsListaRequerimientos); ?>')" class="submenus">Primero</a>
               </li>
				  <?php } // Show if not first page ?>
			   <?php if ($pageNum_RsListaRequerimientos > 0) { // Show if not first page ?>
			    <li>
				  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListaRequerimientos=%d%s", $currentPage, max(0, $pageNum_RsListaRequerimientos - 1), $queryString_RsListaRequerimientos); ?>')" class="submenus">Anterior</a>
				 </li>
				  <?php } // Show if not first page ?>
			<?php if ($pageNum_RsListaRequerimientos < $totalPages_RsListaRequerimientos) { // Show if not last page ?>
			     <li>
                  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListaRequerimientos=%d%s", $currentPage, min($totalPages_RsListaRequerimientos, $pageNum_RsListaRequerimientos + 1), $queryString_RsListaRequerimientos); ?>')" class="submenus">Siguiente</a>
				 </li>
				  <?php } // Show if not last page ?>
			<?php if ($pageNum_RsListaRequerimientos < $totalPages_RsListaRequerimientos) { // Show if not last page ?>
			      <li>
                  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListaRequerimientos=%d%s", $currentPage, $totalPages_RsListaRequerimientos, $queryString_RsListaRequerimientos); ?>')" class="submenus">&Uacute;ltimo</a>
				  </li>
				  <?php } // Show if not last page ?>
				</ul>
			</td>
		  </tr>
		</table>
</div>
</form>
<script type="text/template" id='ordenprod-template'>
<table cellspacing='0' cellpadding='6' border='1' id='tbl_orden' >
    <thead>
      <tr class="SLAB trtitle">
        <th width="35%" >Detalle</th>
		<th width="4%">Cantidad</th>
		<th width="4%">Medida</th>
		<th width="15%">Valor Unitario</th>
		<th width="15%">Iva Unitario</th>
		<th width="15%">Valor Total</th>
		<th width="10%">Cod Requ</th>
		</tr>
    </thead>
    <tbody>
      <%
        // repeat items 
        _.each(itemsdetalles,function(item,key,list){
      %>
	  <%
	   if(key==0){
	  %>
		<tr>
			<td colspan="10" align="center">
			 <input type="button" class="buttonazul" name="savedetailconv" id="savedetailconv" value="Guardar Productos" onclick="SaveDetailsCot('<%- item.ORDEN %>')">
			 <input type="hidden" name="sinrecibir" id="sinrecibir" value="">
			</td>
		</tr>
      <%
		}
	  %>
        <tr class="SB" id="trdetail_<%- item.DETALLE %>" >
            <td width="35%">&nbsp;&nbsp;<%
	   if(item.FECHA_REC_PROV == -1){
	  %><input class="adddetail" type="checkbox" name="cotizaciondetail_<%- item.DETALLE %>" id="cotizaciondetail_<%- item.DETALLE %>" ><%
		}
	  %>&nbsp;&nbsp;<span id="tddetaildes_<%- item.DETALLE %>"><%- item.DETALLE_DES %></span></td>
			<td width=""><span id="tdcantidad_<%- item.DETALLE %>"><%- item.CANTIDAD %></span><input type="hidden" name="tdcodorden_<%- item.DETALLE %>" id="tdcodorden_<%- item.DETALLE %>" value="<%- item.CODIGO %>"></td>
			<td width=""><span id="tdmedida_<%- item.DETALLE %>"><%- item.MEDIDA %></span></td>
			<td width=""><span id="tdvalorunitario_<%- item.DETALLE %>"><%- item.VALOR_UNITARIO %></span></td>
			<td width=""><span id="tdiva_<%- item.DETALLE %>"><%- item.IVA %>&nbsp;&nbsp;&nbsp; <%- item.PORC_IVA %>%</span></td>
			<td width=""><span id="tdtotal_<%- item.DETALLE %>"><%- item.VALOR_TOTAL %></span></td>
			<td width=""><span id="tdcodrequ_<%- item.DETALLE %>"><a target="_blank" href="home.php?page=solicitud&codreq=<%- item.CONS_REQU %>"><%- item.COD_REQU %></a></span></td>
          </td>
        </tr>
      <%
        });
      %>
    </tbody>
  </table>
</script>
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

function Firma_director_admin(prov,cod,pers){
  if(confirm('Esta seguro de firmar esta orden de compra?')){	
		    $.ajax({
	            type: "POST",
	            url: "tipo_guardar.php?tipoGuardar=firmarOrden&cod_prov="+prov+"&cod_orden="+cod+"&persona="+pers,
	            dataType: 'json',
				success : function(r){					
					if(r>0){
						
					generar_pdf(prov,cod,r, true);
                   console.log('aqui va el mensaje 1 -'+r);					
					} 
				},
				error   : callback_error
	            
	        });
	}
}	

function generar_pdf(prov,cod, f, modificararchivo=false){
$.ajax({
	            type: "POST",
	            url: "O.php?codprov="+prov+"&codcomp="+cod+"&f="+f+"&%=1",
	            dataType: 'json',
				success : function(r){		
					        console.log('aqui va el mensaje 2 -'+r);	
							
							var parametro='<?php echo($parametro); ?>';
			
							var nombre_archivo=parametro+cod;
						  if(modificararchivo){
							modificar_archivo(nombre_archivo,cod,prov,f);								 
						  }
				},
				error   : callback_error
	            //data: { json: ordendet }
	        });
}	

function modificar_archivo(a,c,p,f){
	
		$.ajax({
						type: "POST",
						url: "tipo_guardar.php?tipoGuardar=md5_orden&nomb_arch="+a+"&prov="+p+"&f="+f+"&cod_orden="+c,
						dataType: 'json',
						success : function(r){
						 console.log('aqui va el mensaje 3'+r.msj);	
						if(r.codigo != "")
						{  
							  $("#spanfirma_"+c).text(r.codigo);	
							  $("#btnfirma_"+c).remove(); 
						}else if(r.msj != "")
								{
									alert('El archivo no se a encontrado. Por favor informar a los administradores del aplicativo. GRACIAS');
								}
													
						
					  },	
						error   : callback_error,
				  
					
		});
}		
	
function callback_error(XMLHttpRequest, textStatus, errorThrown)
{
    alert("Respuesta del servidor "+XMLHttpRequest.responseText);
    alert("Error "+textStatus);
    alert(errorThrown);
} 
	
 function f_abrir_link(v_link)
	{
	  document.form1.action=v_link;
	  document.form1.submit();

    }
	
function f_Enviar_correo(prov,cod)
{
	if(confirm('Esta seguro de enviar este correo ?')){	
		    $.ajax({
	            type: "POST",
	            url: "tipo_guardar.php?tipoGuardar=Enviar_correo_Orden&prov="+prov+"&cod_orden="+cod,
	            dataType: 'json',
				success : function(r){
				alert(r);							
					if(r>0){						
						 alert('Su correo se a Enviado correctamente');					
					}
					if(r==0){						
						 alert('Su correo no se a Enviado, Posibles errores de Internet');
					}

				},
				error   : callback_error,
	            //data: { json: ordendet }
	        });
	}
	
}	

function f_RecibiraProveedor( prov, orden ){
	document.getElementById('TB_overlaycot').className="TB_overlayBGcot";	
	document.getElementById('popup_cot').className="";
	$('html, body').animate({ scrollTop: $('#popup_cot').offset().top }, 'slow');
			    $.ajax({
	            type: "POST",
	            url: "tipo_guardar.php?tipoGuardar=cargar_orden&orden="+orden,
	            dataType: 'json',
				success : function(r){	
				console.log("antes");
                console.log(r);	
				console.log("despues");
					if(r.length > 0){
						renderdetalles(r, orden);
						//alert('Su correo se a Enviado correctamente');					
					} 
				},
				error   : callback_error
	            //data: { json: ordendet }
	        });
}
var itemsdetalles = [];
 renderdetalles = function(r,orden){
  itemsdetalles = r;
  var template = $("#ordenprod-template").html();
  //console.log(items);
  $("#popup_cot").html(_.template(template,{itemsdetalles:itemsdetalles}));
  $("#sinrecibir").val("0");
  if(r.length>0){
	  _.each(r,function(item,key,list){
		  if(r[key].FECHA_REC_PROV == -1){
			  valor = (parseInt($("#sinrecibir").val())+1);
			  $("#sinrecibir").val(valor);
		  }
		  /*console.log(r[key]);
		  console.log(key);*/
	  });
	  if($("#sinrecibir").val()==0){
		  $('#savedetailconv').remove();
	  }
	  tiene_clase = ($("#btnreccorr_"+orden).attr("class"));
	  if(tiene_clase == 'buttonazul' && $("#sinrecibir").val()==0){
			$.ajax({
	            type: "POST",
	            url: "tipo_guardar.php?tipoGuardar=TodosRecibidoProv&orden="+orden,
	            dataType: 'json',
				success : function(r){
					//console.log(r);
					if(r>0){
					 $("#btnreccorr_"+orden).removeClass("buttonazul");
					}
					
				},
				error   : callback_error
	        });		  
	  }
  }  
}
function SaveDetailsCot(orden){
var newArrayD = new Array();	
                                   //each significa por cada es un bucle
	$("#popup_cot .adddetail").each(function(index){
		  if($(this).is(':checked')){
		  var ideta = $(this).attr('id');
		  var valdeta = ideta.split("_");
		  var detalle = valdeta[1];
		  var detalle_des = $("#tddetaildes_"+detalle).text();
		  var cantidad = $("#tdcantidad_"+detalle).text();
		  var precio      = $("#tdvalor_"+detalle).text();
		  var coddetorden = $("#tdcodorden_"+detalle).val();
		  	var odeta = { 'det': detalle, 'des': detalle_des, 'cant':cantidad , 'price':precio, 'detorden':coddetorden };
			//alert(ideta);
			newArrayD.push(odeta);	 
		  }			
	});	
	//console.log(newArrayD);
	if(newArrayD.length>0){
		if(confirm("esta seguro de agregar estos productos al proveedor")){
			$.ajax({
	            type: "POST",
	            url: "tipo_guardar.php?tipoGuardar=RecibirProductos&orden="+orden+"&sinrecibir="+$("#sinrecibir").val(),
	            dataType: 'json',
				success : function(r){
					//console.log(r);
					if(r.length>0){
						if(r[0].change == 1){
							$("#btnreccorr_"+orden).removeClass("buttonazul");
							$('#savedetailconv').remove();
						}
					 alert("se ha marcado como recibido "+r[0].afectado+" detalles");
					}
				},
				error   : callback_error,
	    
	            data: { json: JSON.stringify(newArrayD) }
	        });
		}
	}else{
		alert("debe marcar productos para agregar como recibidos");
	}
}

function visto_director_admin(orden)
{
	if(confirm("Esta a punto de autorizar esta orden para compra, Desea Continuar"))
	{
			$.ajax({
	            type: "POST",
	            url: "tipo_guardar.php?tipoGuardar=AutorizarOrden&orden="+orden,
	            dataType: 'json',
				success : function(r){
					$("#btnautoriza_"+orden).remove();
				},
				error   : callback_error,	           
	        });
	}
}



function f_EntregarProveedor(orden)
{
	if(confirm("Desea marcar la orden "+orden+" como Entregada"))
	{
			$.ajax({
	            type: "POST",
	            url: "tipo_guardar.php?tipoGuardar=EntregarOrden&orden="+orden,
	            dataType: 'json',
				success : function(r){
					$("#btnEntrega_"+orden).remove();

				},
				error   : callback_error,	           
	        });
	}
}
function f_AnularOrden(proveedor,orden)
{
	if(confirm("Desea anular la orden "+orden+" "))
	{
		
		$.ajax({
	            type: "POST",
	            url: "tipo_guardar.php?tipoGuardar=AnularOrden&orden="+orden,
	            dataType: 'json',
				success : function(r){
					console.log("Se anulo correctamente");
					$("#trorden_"+orden).css("background","#ff837a")
					$("#btnanular_"+orden).remove();
					$("#btnenvcorr_"+orden).remove();
					$("#btnreccorr_"+orden).remove();
					generar_pdf(proveedor,orden,r, false);


				},
				error   : callback_error,	           
	        });
	}

}
</script>