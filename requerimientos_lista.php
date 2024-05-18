<?php
//CONEXION A BASE DE DATOS
	require_once('conexion/db.php');

//CONTROL DE VARIABLES DE SESSIONES 
	if (!isset($_SESSION)) {
	  session_start();
	}
	if(!isset($_SESSION['MM_AccesoCorrectoApp']) || $_SESSION['MM_AccesoCorrectoApp'] != 'ACTIVO'){
	  header("location: index.php");
	}

//DEFINICION DE VARIABLES

	$currentPage = $_SERVER["PHP_SELF"];
    
	//variable de la cedula de la directora administrativa
	$dir_administra ='39550544';

	$estado_filtro='';
	if(isset($_POST['estado_filtro']) && $_POST['estado_filtro'] !=''){
	 $estado_filtro = $_POST['estado_filtro'];
	}
	$codigo_filtro='';
	if(isset($_POST['codigo_filtro']) && $_POST['codigo_filtro'] !=''){
	 $codigo_filtro = $_POST['codigo_filtro'];
	}
	$area_filtro='';
	if(isset($_POST['area_filtro']) && $_POST['area_filtro'] !=''){
	 $area_filtro = $_POST['area_filtro'];
	}
	$fdetallesfirmar='';
	if(isset($_POST['fdetallesfirmar']) && $_POST['fdetallesfirmar'] !=''){
	 $fdetallesfirmar = $_POST['fdetallesfirmar'];
	}
	$todos='';
	if(isset($_GET['todos']) && $_GET['todos'] !=''){
	 $todos = $_GET['todos'];	
	}
	$desc_detalle='';
	if(isset($_POST['desc_detalle']) && $_POST['desc_detalle'] !=''){
	 $desc_detalle = $_POST['desc_detalle'];	
	}

	
//DEFINIR VARIABLE DE PAGINADOR
	$tamanoPagina = 20;
	$maxRows_RsListaRequerimientos = $tamanoPagina;
	$pageNum_RsListaRequerimientos = 0;

	if (isset($_GET['pageNum_RsListaRequerimientos'])) {
	  $pageNum_RsListaRequerimientos = $_GET['pageNum_RsListaRequerimientos'];
	}
	$startRow_RsListaRequerimientos = $pageNum_RsListaRequerimientos * $maxRows_RsListaRequerimientos;

//CONSULTA DE ESTADOS    
	$query_RsEstados="SELECT E.ESTACODI CODIGO,
	                         E.ESTANOMB NOMBRE
					   FROM ESTADOS E";
	$RsEstados = mysqli_query($conexion,$query_RsEstados) or die(mysqli_connect_error());
	$row_RsEstados = mysqli_fetch_array($RsEstados);
    $totalRows_RsEstados = mysqli_num_rows($RsEstados);

//CONSULTA DE AREAS
		$query_RsArea="SELECT A.AREAID 		CODIGO_AREA,
							  A.AREANOMB 	NOMBRE,
							  A.AREAESTA 
						FROM  area A 
						WHERE 1
						ORDER BY A.AREANOMB ASC";
	$RsArea = mysqli_query($conexion,$query_RsArea) or die(mysqli_connect_error());
	$row_RsArea = mysqli_fetch_array($RsArea);
    $totalRows_RsArea = mysqli_num_rows($RsArea);

//CONSULTA DE DETALLES
if($desc_detalle != ''){
	$query_RsListaDetallesRequerimientos="SELECT DEREDESC DESCRIPCION_DETALLE, 
												 REQUCORE REQUERIMIENTO_CODI
										  FROM   DETALLE_REQU,
												 REQUERIMIENTOS 
										  WHERE  DEREREQU=REQUCODI 
											AND `DEREDESC` LIKE '%$desc_detalle%'";	
}	
    
//CONSULTA DE REQUERIMIENTOS	
	$query_RsListaRequerimientos="SELECT R.REQUCODI CODIGO,
	                                     R.REQUCORE CODIGO_REQUERIMIENTO,
										 R.REQUIDUS USUARIO,
										 R.REQUAREA AREA,
										 date_format(R.REQUFEEN,'%d/%m/%Y') FECHA,
										  date_format(R.REQUFEFI,'%d/%m/%Y') FECHA_FINALIZADO,
										 R.REQUESTA ESTADO,
										 E.ESTANOMB ESTADO_DES,
										 E.ESTACOLO COLOR,
										 (SELECT SUBSTRING(REQUCORE,3,4)) A,
										 (SELECT SUBSTRING(REQUCORE,8,4)*2)B,
										 (SELECT A.AREANOMB 
										    FROM AREA A
										   WHERE A.AREAID =  R.REQUAREA LIMIT 1) AREA_DES,
										 R.REQUENCU ENCUESTA,						 
										
										 (SELECT ENPEESTA FROM encuesta_pers where ENPEREQU = R.REQUCODI limit 1) RESPUESTA_ENC
										 
								  FROM REQUERIMIENTOS R
								       LEFT JOIN ESTADOS E ON R.REQUESTA = E.ESTACODI
							    where 1 ";
 								
									   
if ($todos != 1 ){
 //CONDICIONES DE FILTRO DE USUARIO GENERAL
	  if($_SESSION['MM_RolID']==4){
		  //en caso de la entrada muestra todos los requerimientos que pertenecen al usuario donde  codigo y estado estan vacios
			$query_RsListaRequerimientos = $query_RsListaRequerimientos." and R.REQUIDUS = '".$_SESSION['MM_UserID']."'";
		
		  
		  if($codigo_filtro!=''){
		   $query_RsListaRequerimientos = $query_RsListaRequerimientos." AND R.REQUCORE = '".$codigo_filtro."' ";
		  }
		 
		 if($estado_filtro!=''){
		  $query_RsListaRequerimientos = $query_RsListaRequerimientos." AND R.REQUESTA = '".$estado_filtro."' ";	  
		 }
	  }	  
 
 
 //CONDICIONES DE FILTRO DE AUXILIAR ADMINISTRATIVO O DE DIRECTOR ADMINISTRATIVO  O DE GERENTE
	if($_SESSION['MM_RolID']==2 || $_SESSION['MM_RolID']==3 || $_SESSION['MM_RolID']==5){		
	   
	    // en caso de que todos sean vacios
		if($_SESSION['MM_RolID']==2)
		 {	if ($codigo_filtro =='' && $estado_filtro=='' && $area_filtro=='')
			{
			$query_RsListaRequerimientos = $query_RsListaRequerimientos." AND R.REQUESTA = 2 ";
			}			
	     }
		 $nuevoorderDA = 0;
		if($_SESSION['MM_RolID']==3)
		 {	if ($codigo_filtro =='' && $estado_filtro=='' && $area_filtro=='')
			{
			 $query_RsListaRequerimientos = $query_RsListaRequerimientos." AND R.REQUESTA IN (11) ";
			 $nuevoorderDA = 1;
			}			
	    }
		$nuevoorderRE = 0;

		if($_SESSION['MM_RolID']==5)
		 {	
		 	if ($codigo_filtro =='' && $estado_filtro=='' && $area_filtro=='' && $fdetallesfirmar =='')
			{
			 //$query_RsListaRequerimientos = $query_RsListaRequerimientos." and R.REQUIDUS = '".$_SESSION['MM_UserID']."'";
			 $nuevoorderRE = 1;
			}	
			if($fdetallesfirmar == '1'){
				$query_RsListaRequerimientos = $query_RsListaRequerimientos." and R.REQUCODI IN (
				 SELECT DISTINCT D2.DEREREQU FROM DETALLE_REQU D2 WHERE DEREAPRO in (17,25)
				)";
			}
	    }
		if($nuevoorderRE=='1'){
				$query_RsListaRequerimientos = $query_RsListaRequerimientos." and R.REQUCODI IN (
				 SELECT DISTINCT D2.DEREREQU FROM DETALLE_REQU D2 WHERE DEREAPRO in (17,25)
				)";			
		}
	    
        
       //en caso de que codigo este lleno y los demas esten vacios		
     	if($codigo_filtro!='' && $estado_filtro=='' && $area_filtro==''){			 
		$query_RsListaRequerimientos = $query_RsListaRequerimientos."  AND REQUCORE = '$codigo_filtro' ";
		}
      	
	    // en caso de que estado este lleno y los demas esten vacios		
		if($estado_filtro !='' && $codigo_filtro == ''  && $area_filtro==''){
		  if($estado_filtro ==  1){
				if($_SESSION['MM_RolID']==5)
					{
					$query_RsListaRequerimientos = $query_RsListaRequerimientos."AND R.REQUIDUS= '".$_SESSION['MM_UserID']."' AND R.REQUESTA = '".$estado_filtro."' "; 
                    }else{
						$query_RsListaRequerimientos = $query_RsListaRequerimientos."AND R.REQUIDUS= '".$dir_administra."' AND R.REQUESTA = '".$estado_filtro."' "; 						
					}
		  
		  }else{
			$query_RsListaRequerimientos = $query_RsListaRequerimientos." AND R.REQUESTA = '".$estado_filtro."' "; 
			  }	
		}

		// en caso de que area este lleno y los demas esten vacios		
		if($estado_filtro =='' && $codigo_filtro == ''  && $area_filtro != ''){
		//echo($area_filtro);
		  $query_RsListaRequerimientos = $query_RsListaRequerimientos."  AND REQUAREA = '".$area_filtro."' ";
		  $query_RsListaRequerimientos = $query_RsListaRequerimientos."  AND REQUCORE <> '' ";
		}		
		
		//en caso de que todos esten llenos
		if($estado_filtro !='' && $codigo_filtro != ''   && $area_filtro != '' ){
			
		  if($estado_filtro ==  1){
			$query_RsListaRequerimientos = $query_RsListaRequerimientos."AND R.REQUIDUS= '".$dir_administra."' AND R.REQUESTA = '".$estado_filtro."' "; 
          }else{			 
			  $query_RsListaRequerimientos = $query_RsListaRequerimientos." AND R.REQUESTA = '".$estado_filtro."' "; 
			  $query_RsListaRequerimientos = $query_RsListaRequerimientos."  AND REQUCORE = '".$codigo_filtro."' ";
			  $query_RsListaRequerimientos = $query_RsListaRequerimientos."  AND REQUAREA = '".$area_filtro."' ";
			  }	
		}
		
		//en caso de que codigo y area  esten llenos y estado vacio
		if($estado_filtro =='' && $codigo_filtro != ''   && $area_filtro != '' ){
			
		  if($estado_filtro ==  1){
			$query_RsListaRequerimientos = $query_RsListaRequerimientos."AND R.REQUIDUS= '".$dir_administra."' AND R.REQUESTA = '".$estado_filtro."' "; 
          }else{			  
			  $query_RsListaRequerimientos = $query_RsListaRequerimientos."  AND REQUCORE = '".$codigo_filtro."' ";
			  $query_RsListaRequerimientos = $query_RsListaRequerimientos."  AND REQUAREA = '".$area_filtro."' ";
			  }	
		}
		
		//en caso de que estado y area  esten llenos y codigo vacio
		if($estado_filtro !='' && $codigo_filtro == ''   && $area_filtro != '' ){
			
		  if($estado_filtro ==  1){
			$query_RsListaRequerimientos = $query_RsListaRequerimientos."AND R.REQUIDUS= '".$dir_administra."' AND R.REQUESTA = '".$estado_filtro."' "; 
          }else{			  
			  $query_RsListaRequerimientos = $query_RsListaRequerimientos." AND R.REQUESTA = '".$estado_filtro."' "; 
			  $query_RsListaRequerimientos = $query_RsListaRequerimientos."  AND REQUAREA = '".$area_filtro."' ";
			  }	
		}
		
		//en caso de que estado y codigo  esten llenos y area vacio
		if($estado_filtro !='' && $codigo_filtro != ''   && $area_filtro == '' ){
			
		  if($estado_filtro ==  1){
			$query_RsListaRequerimientos = $query_RsListaRequerimientos."AND R.REQUIDUS= '".$dir_administra."' AND R.REQUESTA = '".$estado_filtro."' "; 
          }else{			  
			 $query_RsListaRequerimientos = $query_RsListaRequerimientos." AND R.REQUESTA = '".$estado_filtro."' "; 
			  $query_RsListaRequerimientos = $query_RsListaRequerimientos."  AND REQUCORE = '".$codigo_filtro."' ";			  
			  }	
		}
			  
		     
    }
	
	 if($_SESSION['MM_RolID']==6){
		  //en caso de la entrada muestra todos los requerimientos que pertenecen al usuario donde  codigo y estado estan vacios
			$query_RsListaRequerimientos = $query_RsListaRequerimientos." and R.REQUAREA = '".$_SESSION['MM_Area'] ."'";
		
		  
		  if($codigo_filtro!=''){
		   $query_RsListaRequerimientos = $query_RsListaRequerimientos." AND R.REQUCORE = '".$codigo_filtro."' ";
		  }
		 
		 if($estado_filtro!=''){
		  $query_RsListaRequerimientos = $query_RsListaRequerimientos." AND R.REQUESTA = '".$estado_filtro."' ";	  
		 }
	  }	  
 
	//ordena lista de usuario general
	 if($_SESSION['MM_RolID']==4){
	  $query_RsListaRequerimientos = $query_RsListaRequerimientos." order by R.REQUFESO DESC";	  
	  }
	  
	 //ordena lista de rol auxiliar administrativo o director administrativo o gerente 
	  if($_SESSION['MM_RolID']==2 || $_SESSION['MM_RolID']==3){
		  if($nuevoorderDA=='1'){
	       $query_RsListaRequerimientos = $query_RsListaRequerimientos." ORDER BY A , B ASC"; 
       	  }else{
		   $query_RsListaRequerimientos = $query_RsListaRequerimientos." ORDER BY A , B ASC";			 
		  }
	  }
	  if($_SESSION['MM_RolID']==5){
		  if($nuevoorderRE=='0'){
			 $query_RsListaRequerimientos = $query_RsListaRequerimientos." ORDER BY A , B ASC"; 
		  }else{
			 $query_RsListaRequerimientos = $query_RsListaRequerimientos." ORDER BY R.REQUESTA asc";   
		  }
	  }
 }else{
	 $query_RsListaRequerimientos = $query_RsListaRequerimientos." AND R.REQUCORE IS NOT NULL ORDER BY A , B ASC";
 }
//EJECUCION DE LA CONSULTA DE REQUERIMIENTOS
   //echo($query_RsListaRequerimientos);
   $query_limit_RsListaRequerimientos = sprintf("%s LIMIT %d, %d", $query_RsListaRequerimientos, $startRow_RsListaRequerimientos, $maxRows_RsListaRequerimientos);
	$RsListaRequerimientos = mysqli_query($conexion,$query_limit_RsListaRequerimientos) or die(mysqli_connect_error());
	$row_RsListaRequerimientos = mysqli_fetch_array($RsListaRequerimientos);
    
	if (isset($_GET['totalRows_RsListaRequerimientos'])) {
	  $totalRows_RsListaRequerimientos = $_GET['totalRows_RsListaRequerimientos'];
	} else {
	  $all_RsListaRequerimientos = mysqli_query($conexion, $query_RsListaRequerimientos);
	  $totalRows_RsListaRequerimientos = mysqli_num_rows($all_RsListaRequerimientos);
	}
	
//EJECUCION DE LA CONSULTA DE DETALLES DE REQUERIMIENTO	
if($desc_detalle != ''){
    $RsListaDetallesRequerimientos = mysqli_query($conexion,$query_RsListaDetallesRequerimientos) or die(mysqli_connect_error());
	$row_RsListaDetallesRequerimientos = mysqli_fetch_array($RsListaDetallesRequerimientos);
	$totalRows_RsListaDetallesRequerimientos = mysqli_num_rows($RsListaDetallesRequerimientos);
}else{
	$totalRows_RsListaDetallesRequerimientos=0;
}
//EJECUCION DE LA PAGINACION
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
		
?>
<style type="text/css">
.contenttable{
 width:90%;
 overflow:hidden;
 min-height:150px;
 border-radius:12px;
}
</style>
 
<div id="pagina">
	<form name="form1" id="form1" method="post" action="">
		<div class="contenttable">
			<div id="divfiltros" style="display:none; border:solid 1px #ccc; width:99%;">
				<table width="100%">
					<tr>
						<td class="SLAB trtitle" colspan="7" align="center">Filtros de Busqueda</td>
				    </tr>	
					<tr>
						<td  align="center">
							<input type="submit" name="butonfiltro" id="butonfiltro" class="button2" value="Buscar" onclick="Busqueda();">
						</td>
						<td class="">Estado</td>
						<td> 
							<select name="estado_filtro" id="estado_filtro" >
								<option value="">Seleccione...</option>
									<?php
										if($totalRows_RsEstados>0)
										{
											do{
									?>
												<option value="<?php echo($row_RsEstados['CODIGO']);?>" <?php if($estado_filtro==$row_RsEstados['CODIGO']){ echo('selected'); } ?> ><?php echo($row_RsEstados['NOMBRE']);?></option>   
									<?php
											  }while($row_RsEstados = mysqli_fetch_array($RsEstados));
										}
									?>
							</select>
							<input  type="button" name="festado" id="festado" value="x" onclick="limpiarfiltros('estado_filtro');">
						</td>
						<?php if($_SESSION['MM_RolID']!=4)
						{
							?>
							<td class="">Area</td>
							<td>
								<select name="area_filtro" id="area_filtro" >
									<option value="">Seleccione...</option>
								<?php
									if($totalRows_RsArea>0)
									{
										do{
								?>
											<option value="<?php echo($row_RsArea['CODIGO_AREA']);?>" <?php if($area_filtro==$row_RsArea['CODIGO_AREA']){ echo('selected');} ?> ><?php echo($row_RsArea['NOMBRE']);?></option>   
								<?php
										  }while($row_RsArea = mysqli_fetch_array($RsArea));
									}
								?>
								</select>
								<input  type="button" name="farea" id="farea" value="x" onclick="limpiarfiltros('area_filtro');">
							</td>
							<?php 
						} 
						?>
						<td class="">Codigo Requerimiento</td>
						<td>
							<input type="text" name="codigo_filtro" id="codigo_filtro" value="<?php echo($codigo_filtro);?>">
							<input  type="button" name="fcodrequ" id="fcodrequ" value="x" onclick="limpiarfiltros('codigo_filtro');">
						</td>   
					</tr>
					<?php if($_SESSION['MM_RolID']==5){ ?>
					<tr>
					    <td></td>
						<td>Detalles por firma rector</td>
						<td>
						  <select name="fdetallesfirmar" id="fdetallesfirmar">
						    <option value="">Seleccione...</option>
							<option value="1" <?php if($fdetallesfirmar==1){ echo('selected');} ?> >con detalles por firmar</option>
						  </select>
						</td>
					</tr>
					<?php } ?>
					<?php if($_SESSION['MM_RolID']==2 || $_SESSION['MM_RolID']==3 || $_SESSION['MM_RolID']==5){ ?>
					<tr>
					<td>						
					<input  type="submit" name="listar_todos" id="listar_todos" class="button2" value="Todos" onclick="Buscar_todo();"  >
					</td>
					<td>Detalle</td>
					<td><input type="text" name="desc_detalle" id="desc_detalle" size="33" value="<?php //echo($desc_detalle);?>"></td>
					</tr>
					<?php } ?>
				</table>
			</div>
				<table border="0">
					<tr>
					  <td colspan="5">
					   <input  type="button" class="button2" name="consultar" id="consultar" value="consultar" onclick="mostrarfiltros();">
					  </td>
					   <td colspan="2">
						<input  type="button" class="button2" name="consultar" id="consultar" value="Crear Requerimiento" onclick="CrearRequerimiento('1');">  	
						</td>
						<td colspan="1" align="right">
						<?php if($_SESSION['MM_RolID']==2 or $_SESSION['MM_RolID']==3 ){ ?>
						<div  onclick="location.href=('home.php?page=detalle_compraangular')" ><span><i class="fa fa-list fa-2x" aria-hidden="true"></i></span></div>
						<?php }?>
						</td>
						
					</tr>
					
					<?php if($_SESSION['MM_RolID']==2 ){ ?>
					<tr class="SLAB trtitle" align="center">
					<?php 
					if($totalRows_RsListaDetallesRequerimientos >0){?>
						<td>Descripcion</td>
						<td>Requerimiento</td>						
					</tr>
					<?php
							$y=0;
							do{
								$y++;
								if($y%2==0){
									$estilo="SB";
								}else{
									$estilo="SB2";
								}
						?>
					<tr CLASS="<?php echo($estilo);?>">
						<td><?php echo($row_RsListaDetallesRequerimientos['DESCRIPCION_DETALLE']); ?></td>
					    <td><?php echo($row_RsListaDetallesRequerimientos['REQUERIMIENTO_CODI']);   ?></td>
					</tr>
						<?php
							}while($row_RsListaDetallesRequerimientos = mysqli_fetch_array($RsListaDetallesRequerimientos));
							}else{
						if($desc_detalle != ''){ ?>
						<tr>
						
						  <td colspan="4">No se encuentran registros en la busqueda por detalle</td>
						</tr>
							<?php
						}}
						?>		
			
					
					<?php }?>
					<tr>
					  <td colspan='3'>
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
					</tr>
					<tr>
						<td colspan="8"> 	 
							<?php if ($totalRows_RsListaRequerimientos > 0)
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
						<td></td>
						<td>Codigo Requerimiento</td>
						<?php if ($_SESSION['MM_RolID']!= 4){?>
						<td>Area</td>
						<?php }?>
						<td>Estado</td>		  
						<td>Fecha</td>										
                        <td>Encuesta</td>
                        <td>Detalles</td>
                        <td>Alertas</td>
                        						
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
								<tr CLASS="<?php echo($estilo);?>" height="30">
									<td>
					    		   <?php
							if(($row_RsListaRequerimientos['ESTADO']>2 ) && $_SESSION['MM_RolID']==2)
							{
								?>
								<a href="R.php?codreq=<?php echo($row_RsListaRequerimientos['CODIGO']);?>"  class="buttonazul"  target="_blank" ">Reporte</a>
								<a href="home.php?page=solicitud&codreq=<?php echo($row_RsListaRequerimientos['CODIGO']);?> " class="buttonazul" target="_blank">Ver</a>
								<?php
							}else{
								?> 
										<a href="home.php?page=solicitud&codreq=<?php echo($row_RsListaRequerimientos['CODIGO']);?>" class="buttonazul" target="_blank">Ver</a>
								<?php
										}
								?>
									</td>
									<td>
										<?php echo($row_RsListaRequerimientos['CODIGO_REQUERIMIENTO']);?>
									</td>
								<?php if($_SESSION['MM_RolID']!= 4)
									   {
								?>
									<td align="center">
												<?php echo($row_RsListaRequerimientos['AREA_DES']);?>
									</td>
								<?php  } 
								?>
									<td bgcolor="<?php echo($row_RsListaRequerimientos['COLOR']);?>">
										<?php echo($row_RsListaRequerimientos['ESTADO_DES']);?>
									</td>
									<td>
										<?php echo($row_RsListaRequerimientos['FECHA']);?>
									</td>
									<td></td>
									<td align="center">

										<?php 
                            				require_once("scripts/funcionescombo.php");		
											$estados = dameTotalDetalles($row_RsListaRequerimientos['CODIGO']);
													foreach($estados as $indice => $registro)
													{
													 echo($registro['TOTAL']);
													}
							           	?>
									</td>
									<?php 
                            				require_once("scripts/funcionescombo.php");		
											$estados = dameTotalEstadosDetalles($row_RsListaRequerimientos['CODIGO']);
													 ?>	
									 <td>
													    
														<?php foreach($estados as $indice => $registro)
													{ ?>   
                                                     <div style ='background-color: <?php echo($registro['COLOR']); ?>;'><?php echo($registro['TOTAL']); ?>- <?php echo($registro['ESTADO_DES']); ?></div>													
													 	
													<?php 
												}
							           	?>
									
									 </td>
								
					   

						<!-- <td>
						 <?php if($row_RsListaRequerimientos['ENCUESTA'] >0 && $row_RsListaRequerimientos['RESPUESTA_ENC'] == 0){
							 ?>
							 <a target="_blank" href="encuesta/#/enc/<?php echo($row_RsListaRequerimientos['ENCUESTA']); ?>">Ir a Encuesta</a>
							 <?php
						 }
						 if($row_RsListaRequerimientos['RESPUESTA_ENC'] == 1){
							 ?>
							 encuesta realizada
							 <?php
						 }
						 ?>
						</td>-->
						


						<td>


							
						</td>
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

function VerRequerimiento(req){
  window.location ="home.php?page=solicitud&codreq="+req;
}



function VerRequerimiento2(req){
   window.location="home.php?page=solicitud_director&codreq="+req;
}

//paginación

 function f_abrir_link(v_link)
	{
	  document.form1.action=v_link;
	  document.form1.submit();

    }

  function mostrarfiltros(){
   $( "#divfiltros" ).toggle();
  }
  
  function limpiarfiltros (campo){
  document.getElementById(''+campo).value=""; 
  }
  function Busqueda(){
   document.form1.action="home.php?page=requerimientos_lista";
  }
   function Buscar_todo(){
   document.form1.action="home.php?page=requerimientos_lista&todos=1";
  }
 </script>