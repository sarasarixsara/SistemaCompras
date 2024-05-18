<?php 
require_once('conexion/db.php');
	if (!isset($_SESSION)) {
  session_start();
}
if(!isset($_SESSION['MM_AccesoCorrectoApp']) || $_SESSION['MM_AccesoCorrectoApp'] != 'ACTIVO'){
  header("location: index.php");
}
		$rem[]='"';
		$rem[]="'";
		$rem[]="’";
		$rem[]="‘";
		$rem[]="‚";
		$rem[]="“";
		$rem[]="”";
		$rem[]="„";
		$rem[]="´";
		$rem[]="`";
		$rem[]="{";
		$rem[]="}";
		$rem[]="[";
		$rem[]="]";
		$rem[]="\r\n";
		$rem[]="\n\r";
		$rem[]="\n";
		$rem[]="\r";
        $query_RsClasificacion ="SELECT C.CLASCODI CODIGO,
         		                        C.CLASNOMB NOMBRE,
										C.CLASESTA ESTADO
								 FROM CLASIFICACION C
								WHERE C.CLASESTA = 1
								order by C.CLASNOMB";
		$RsClasificacion = mysqli_query($conexion,$query_RsClasificacion) or die(mysqli_error($conexion));
		$row_RsClasificacion = mysqli_fetch_array($RsClasificacion);
		$totalRows_RsClasificacion = mysqli_num_rows($RsClasificacion);
		
$arrclasificaciones = array();		
		if($totalRows_RsClasificacion>0){
		 do{
		   	   $arrclasificaciones[] = array('CODIGO'             => $row_RsClasificacion["CODIGO"],
	                  'NOMBRE'         => str_replace($rem,"",stripslashes($row_RsClasificacion["NOMBRE"])),
					  );	
		   }while($row_RsClasificacion = mysqli_fetch_array($RsClasificacion));
		}
	$query_RsReqAprobados="SELECT R.REQUCODI CONSECUTIVO,
	                              R.REQUCORE CODIGO_REQUERIMIENTO,
	                              DATE_FORMAT(R.REQUFESO,'%d/%m/%Y') FECHA_SOLICITUD,
	                              DATE_FORMAT(R.REQUFEEN,'%d/%m/%Y') FECHA_ENVIADO,
								  R.REQUIDUS PERSONA_SOLICITA,
								  (SELECT P.PERSNOMB
								    FROM PERSONAS P
								   WHERE P.PERSID = R.REQUIDUS) PERSONA_SOLICITA_DES,
								   R.REQUPOA POA,
								   (SELECT A.POANOMB 
								     FROM POA A
									WHERE A.POACODI = R.REQUPOA) POA_DES
					          FROM REQUERIMIENTOS R
							WHERE R.REQUESTA = 5
							 AND (SELECT COUNT(D.DERECONS) FROM DETALLE_REQU D WHERE D.DEREREQU = R.REQUCODI AND D.DEREAPRO = 1 LIMIT 1) >0
					   ";
					   //echo($query_RsReqAprobados);
	$RsReqAprobados = mysqli_query($conexion,$query_RsReqAprobados) or die(mysqli_error($conexion));
	$row_RsReqAprobados = mysqli_fetch_array($RsReqAprobados);
    $totalRows_RsReqAprobados = mysqli_num_rows($RsReqAprobados);
		$arr=array();
		$arrproveedores = array();	
	if($totalRows_RsReqAprobados>0){
	 do{
    
	   $arr[] = array('ID'             => $row_RsReqAprobados["CONSECUTIVO"],
	                  'CODIGO'         => str_replace($rem,"",stripslashes($row_RsReqAprobados["CODIGO_REQUERIMIENTO"])),
	                  'FECHASOL'       => str_replace($rem,"",stripslashes($row_RsReqAprobados["FECHA_SOLICITUD"])),
	                  'FECHAENV'       => str_replace($rem,"",stripslashes($row_RsReqAprobados["FECHA_ENVIADO"])),
	                  'PERSONA'        => str_replace($rem,"",stripslashes($row_RsReqAprobados["PERSONA_SOLICITA"])),
	                  'PERSONA_DES'    => str_replace($rem,"",stripslashes($row_RsReqAprobados["PERSONA_SOLICITA_DES"])),
					  );	
	   }while($row_RsReqAprobados = mysqli_fetch_array($RsReqAprobados));
	 }
	//var_dump($arr);
	$query_RsProveedores="SELECT P.PROVCODI CODIGO,
	                             P.PROVNOMB NOMBRE,
								 P.PROVPWEB WEB, 
								 P.PROVFAVO FAVORITO
						    FROM PROVEEDORES P
						  ORDER BY PROVNOMB";
	$RsProveedores = mysqli_query($conexion,$query_RsProveedores) or die(mysqli_error($conexion));
	$row_RsProveedores = mysqli_fetch_array($RsProveedores);
    $totalRows_RsProveedores = mysqli_num_rows($RsProveedores);	
	if($totalRows_RsProveedores>0){
	  do{
	     $arrcategorias = array();
		$query_RsCategorias="SELECT  P.PRCLCODI CODIGO,
									 P.PRCLCLAS CLASIFICACION,
									 P.PRCLCALI CALIFICACION,
									 C.CLASNOMB CLASIFICACION_DES
								FROM PROVEEDOR_CLASIFICACION P,
								     CLASIFICACION C
							 WHERE  P.PRCLCLAS = C.CLASCODI
							    AND P.PRCLPROV = '".$row_RsProveedores["CODIGO"]."'";
								//echo($query_RsCategorias);
		$RsCategorias = mysqli_query($conexion,$query_RsCategorias) or die(mysqli_error($conexion));
		$row_RsCategorias = mysqli_fetch_array($RsCategorias);
		$totalRows_RsCategorias = mysqli_num_rows($RsCategorias);	
		if($totalRows_RsCategorias>0){
		  do{
		    $arrcategorias[] = array('IDCAT' => str_replace($rem,"",stripslashes($row_RsCategorias["CLASIFICACION"])),
			                          'NCAT' => str_replace($rem,"",stripslashes($row_RsCategorias["CLASIFICACION_DES"])),
									  'CALI' => str_replace($rem,"",stripslashes($row_RsCategorias["CALIFICACION"])),
									 );
		    }while($row_RsCategorias = mysqli_fetch_array($RsCategorias));
		}
		
	     $arrproveedores[] = array('label'   => str_replace($rem,"",stripslashes($row_RsProveedores["NOMBRE"])),
		                           'value'     => $row_RsProveedores["CODIGO"],
		                           'web'    => str_replace($rem,"",stripslashes($row_RsProveedores["WEB"])),
		                           'favo'    => str_replace($rem,"",stripslashes($row_RsProveedores["FAVORITO"])),
								   'cate'   => $arrcategorias,
		                           );
	    }while($row_RsProveedores = mysqli_fetch_array($RsProveedores));
	}

?><!DOCTYPE HTML>
<head>
<title>Cotizar requerimiento</title>
<meta charset="utf-8">
<!-- CSS Files -->

<link rel="stylesheet" type="text/css" media="screen" href="css/cotizacion/cotizacion.css">
<link rel="stylesheet" type="text/css" media="screen" href="messages.css">
	<link rel="stylesheet" type="text/css" href="css/jquery.ui.css"/>
	<link rel="stylesheet" type="text/css" href="css/jquery.ui.accordion.css"/>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/page.css"/>
	<style type="text/css">
	body{
	background-image: url("imagenes/Bottom_texture.png");
	}
	.deleteitem{
	 background-image: url("images/delete3.png");
	 width:17px;
	 height:16px;
	 float:left;
	 padding-left:2px;
	 }
	 .deleteitem:hover{
	  cursor:pointer;
	  background-image: url("images/delete.png");
	 }
	  .cotizado{
	   background:#C0F7CA;
	   cursor:move;
	  }
	  .loading{
	   color:blue;
	   padding:3px;
	   background:url("images/loading.gif") #ffffff no-repeat;
	   width:88px;
	   height:20px;
	   margin-bottom:5px;
	  }
	  .add_ficha{
	   /*border: solid 1px #ff0000;*/
	   width:100%;
	   overflow:hidden;
	   margin: 0 0 2px 0;
	  }
	  .proveedorview{
	  border-radius:4px;
	  background:#AA0000;
	  padding:2px 5px 3px 5px;
	  margin-top:6px;
	  border:solid 1px #000000;
	  }
	  .proveedorview h3{
	  background:#D1D1D1;
	  text-align:center;
	  padding:3px;
	  }
	  .detalleview{
	   color:#000000;
	   padding-left:9px;
	   background:#F2EFEF;
	   margin-top:1px;
	  }
	  .xxx{
	  font-size:12px; color:#ffffff; background:#ff0000; padding:1px 2px 1px 2px;
	  }
	  .fieldsfilter{ margin-right:3%;}
	  .filtercontainer{ background:#F5F2F2; color:#555; height:40px;}
	  .buttonset{background-color: #d9534f;
				border-color: #d43f3a;
				color: #fff;font-weight: 400; 
				padding: 1px 12px;
				white-space:nowrap;
				margin:5px;
				height:28px;
				font-size:14px;
				height:33px;
				border-radius:5px;
}
.buttonset:hover{
background-color: #c9302c;
    border-color: #ac2925;
    color: #fff;
}
.filtros{
background: none repeat scroll 0 0 #ffffff;
    border: 1px solid #ccc;
    display: block;
    float: left;
    position: absolute;
    width: 620px;
    z-index: 99999;
	border-radius:5px;
	box-shadow:0 8px 15px 0 #bb9696;
}
.inputtext{
 height:24px;
 border:1px solid #ccc;
 border-radius:4px;
 font-size:14px;
 color:#555;
 margin:3px;
 }
.inputtext:focus{
background:#EFFFE0;
border-color: #66afe9;
box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 8px rgba(102, 175, 233, 0.6);
outline: 0 none;
}
.labeltext{
font-family:Verdana,Arial,Helvetica,sans-serif;
font-weight:500;
font-size:11px;
color:#555;

}
h5{
 color:#ffffff; text-align:center; font-size:16px; font-weight:500; background:#bf2222; padding:7px 3px; border:solid 1px #EBCCD1;}
 
 #favoritos, #actuales , .adjustsize{
 /*border-bottom: dashed 2px #f14a4f;
 overflow:hidden;
 padding:10px 0;
 */
 height:auto !important;
 min-height:100px;
 max-height:350px;
 overflow:scroll;
 }
 #requerimientos_lista{
 margin-top:32px;
 }
 .star{
  background:url("images/star4.png")  no-repeat;
  width:17px;  
  height:17px;
margin: -15px 220px;
position:absolute;  
 }
 .group{
 height: auto !important;
 }
 .group {
 padding-left:3px;
 }
 .inputsearch{
 height:20px;
 font-size:9px;
 color:$555;
 margin-left:-20px;
 }
 
 .deletzoneactual a{
 background-image: url("images/delete3.png");margin-top:-4px; margin-bottom:2px; margin-left:207px; float: right;position: absolute; clear:both; width:17px;
	 height:16px;
 }
 
	 .deletzoneactual a:hover{
	  cursor:pointer;
	  background-image: url("images/delete.png");
	  }
	 
 .group h3{
 font-size:11px;
 }
 .closereq{
  background-image: url("images/delete3.png");
  position:absolute;
    border-radius: 3px;
    color: #777;
    font-size: 13px;
    margin-left: 97px;
    margin-top: -4px;
    padding: 2px 3px 1px 3px;
	width:17px;
	 height:16px;
	 z-index:2;
 }
 .closereq:hover{
  cursor:pointer;
  background-image: url("images/delete.png");
 }
 .princ{
 overflow:hidden;
 width:106px;
 float:left;
 }
	</style>
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
function Fshowfiltros(){
 $( "#filtros" ).toggle();
}
function detectinput(valor){
$("#actualsearch").val(valor);
}
</script>
</head>
<div id="contenedor" style="width:1000px; box-shadow: 1px 1px 3px 1px #BB9696; margin: 0 auto; position:relative; background:#ffffff; ">
<input type="hidden" name="actualsearch" id="actualsearch" value="">
<ul class="nav nav-pills" style="background:#FCF9F9; margin:1px 2px 1px 2px; min-width:998px; z-index:1; position:fixed;" id="menunav">
        <?php /*
		<li class="" id="home"><a href="#">Home <span class="badge">42</span></a></li>
        <li class="" id="home"><a href="#">Home <span class="badge">42</span></a></li>
        
		<li><a href="#profile">Profile</a></li>
        <li><a href="#messages">Messages <span class="badge">3</span></a></li>
        <li class=""><a href="#">Home <span class="badge">42</span></a></li>
        <li><a href="#profile">Profile</a></li>
        <li><a href="#messages">Messages <span class="badge">3</span></a></li>
		        <li class=""><a href="#">Home <span class="badge">42</span></a></li>
        <li><a href="#profile">Profile</a></li>
        <li><a href="#messages">Messages <span class="badge">3</span></a></li>
		*/?>	
		<li>
			<input type="button" class="btn btn-sm btn-danger" style="margin:8px 0px 0px 10px; display:none;" id="btnordencotizar" name="btnordencotizar" value="Generar Orden">
			<input type="button" class="btn btn-sm btn-success" style="margin:8px 0px 0px 10px; display:none;" id="btnlimpiar" name="btnlimpiar" value="Limpiar Orden">
		</li>
       <li style="float:right;"><input style="margin:8px 10px 0px 10px; float:right;" type="button" class="btn btn-sm btn-danger" onclick="ResetArray()" value="Agregar Proveedores"></li>		
      </ul>
<div id="ofertas" class="sombra2" style="min-height:600px;">
 <div class="add_ficha"> 
  <?php /*<input type="text" value="" id="" class="fieldsfilter" style="width:66%; font-size:12px;" ><input style="float:left; margin-left:3%; font-size:12px;" type="button" value="Agregar">
  */?>
<!-- Split button -->
<?php
/*<ul class="nav nav-pills nav-stacked">
  <li class="active">
    <a href="#" class="u-block js-tooltip js-nav" data-original-title="requerimientos aprobados" original-title="requerimientos aprobados">
      <span class="badge pull-right">42</span>
      Home
    </a>
  </li>
</ul>
*/
?>
<?php /*
<div class="btn-group">
  <button type="button" class="btn btn-danger">Action</button>
  <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
    <span class="caret"></span>
    <span class="sr-only">Toggle Dropdown</span>
  </button>
  <ul class="dropdown-menu" role="menu" style="position:relative;">
    <li><a href="#">Action</a></li>
    <li><a href="#">Another action</a></li>
    <li><a href="#">Something else here</a></li>
    <li class="divider"></li>
    <li><a href="#">Separated link</a></li>
  </ul>
</div>
*/ ?>
<?php /*
  <div id="filterbutton" class="filtercontainer" >
  
    <input type="button" class="buttonset" value="Filtro" onclick="Fshowfiltros();"></input>
  </div>
  */ ?>
   <h5 id="listarequerimientos">Lista de Requerimientos</h5>
  <div id="filtros" class="filtros dropdown-caret" style="display:none;">
  <table style="margin:20px 3px 7px 20px;">
   <tr>
     <td class="labeltext">codigo Requ:</td><td><input type="text" class="inputtext" value="" id="" class="fieldsfilter" size="25"   ></td>
     <td class="labeltext">Poa:</td><td><input type="text" class="inputtext" value="" id="" class="fieldsfilter" size="25"  ></td>
	</tr>
    <tr>
     <td class="labeltext">Fecha desde:</td><td><input type="text" class="inputtext" value="" id="" class="fieldsfilter" size="15"  ></td>
     <td class="labeltext">Fecha hasta:</td><td><input type="text" class="inputtext" value="" id="" class="fieldsfilter" size="15"  ></td>
    </tr>
    <tr>
	  <td class="labeltext">Otro poa:</td><td><input type="text" class="inputtext" value="" id="" class="fieldsfilter" size="25"  ></td>  
	  <td class="labeltext">Fue Reenviado:</td><td><input type="checkbox" class="inputtext" value="" id="" ></td>
     <tr>
    <tr>
	  <td class="labeltext">Persona Solicita:</td><td><input type="text" class="inputtext" value="" id="" class="fieldsfilter" size="25"  ></td>
     
	</tr>
	
    <tr>
     <td class="labeltext">Estado:</td><td><select class="inputtext" value="" id="" class="fieldsfilter" ><option>Seleccione...</option>  </select></td>
	</tr>

    </tr>
	<tr>
	 <td class="labeltext" colspan="4" align="center"><input type="button" name="buscar" id="buscar" value="Buscar" class=""></td>
    </tr>
  </table>
  </div>
 </div>
<?php
    if($totalRows_RsReqAprobados >0){
/*	
	$k=0;
      do{
	   $k++;
	  ?>
	   <div class="ficha" id="requerimiento_<?php echo($row_RsReqAprobados['CONSECUTIVO']);?>">
	    <div class="num_requerimiento" id="numreq_<?php echo($row_RsReqAprobados['CONSECUTIVO']);?>"><b><?php echo($k);?></b></div>
	    <p><span id="span_<?php echo($row_RsReqAprobados['CONSECUTIVO']);?>" style="font-size:12px; color:#ffffff; background:#ff0000; padding:1px 2px 1px 2px;"><b><?php echo($row_RsReqAprobados['CODIGO_REQUERIMIENTO']);?></b></span>
		  <br><b>Solicita:</b><br>
		  <?php echo($row_RsReqAprobados['PERSONA_SOLICITA_DES']);?>
		</p>
		<p>Fecha Solicitud
		  <br><?php echo($row_RsReqAprobados['FECHA_ENVIADO']);?>
		  <BR>POA<br>
		  <?php echo($row_RsReqAprobados['POA_DES']);?>
		</p>
	   </div>
	  <?php
	    }while($row_RsReqAprobados = mysqli_fetch_array($RsReqAprobados));	
  */		
	}
?>
</div>
<div class="add_cotizacion">
 <p style="margin:-5px 0px 2px 5px;"><span id="orden_cotizar" style=""><h5 id="Requerimientoscotizar" style=" margin-left:10px; width:450px; margin-top:0px; ">Requerimientos</h5></span>
 </p>
  <div class="cotizaciongeneral" id="requerimientos_lista">
	<ul class="nav nav-tabs" id="requerimientos_menu">
	  <?php /*<li class=""><a href="#opcion1" data-toggle="tab">1</a></li>	  */?>
	</ul>
  </div>
  <div class="tab-content" id="requerimiento_detalle" >
    <?php /*<div class="tab-pane" id="opcion1">
	  <h3>Opcion uno</h3>
    </div>
	*/
	?>
	
 </div>
 <?php /*<p style="margin-top:20px;"><span >Elaborar cotizaci&oacute;n</span></p>*/?>
  <div id="container_cotizacion" class="cotizaciongeneral" style="margin-top:25px; background:#ffffff; height: 500px; overflow: scroll;">
    
  </div> 
</div>
<div style="margin:-8px 0px 0px 1px;">
				 <input type="text" name="search_proveedor_des" id="search_proveedor_des" placeholder="Buscar" size="25">
				 <input type="hidden" name="search_proveedor" id="search_proveedor"  >
				</div>
<!--<h5 >Lista de Proveedores class="publicidad" </h5>-->			
<div id="mkt"  style="height:600px; overflow:scroll">
			    <div class="group">
				<h3>Actuales</h3>
				<div id="actuales">
				  <div>
				   <input type="text" class="inputsearch" name="search_actual_des" id="search_actual_des" placeholder="Buscar" size="25">
				   <input type="hidden" name="search_actual" id="search_actual"  >
				 </div>
				</div>
				</div>
				<div class="group">
				<h3>FAVORITOS<?php /*<div class="star"></div> */ ?></h3>
				<div id="favoritos">
				  <div>
				   <input type="text" class="inputsearch" name="search_favorito_des" id="search_favorito_des" placeholder="Buscar" size="25">
				   <input type="hidden" name="search_favorito" id="search_favorito"  >
				   <?php /*<input type="button" name="" id="" value="prueba" onclick="prueba()">*/?>
				 </div>
				</div>
				</div>
				<div class="group">
					<h3>SIN CLASIFICACION</h3>
					<div id="normales">
					  <div>
					   <input type="text" class="inputsearch" name="search_normal_des" id="search_normal_des" placeholder="Buscar" size="25">
					   <input type="hidden" name="search_normal" id="search_normal"  >
					 </div>					
					</div>
				</div>
				<?php 
				if(is_array($arrclasificaciones)){
				foreach($arrclasificaciones as $obj){
				 ?>
				 <div class="group">
					 <h3><?php echo($obj['NOMBRE']);?></h3>
					 <div id="categoriaproveedor_<?php echo($obj['CODIGO']);?>" class="adjustsize" >
					   <div>
					    <input type="text" class="inputsearchc" name="searchcategoriades_<?php echo($obj['CODIGO']);?>" id="searchcategoriades_<?php echo($obj['CODIGO']);?>" placeholder="Buscar" size="25" onKeypress="detectinput('<?php echo($obj['CODIGO']);?>');">
					    <input type="hidden" name="searchcategoria_<?php echo($obj['CODIGO']);?>" id="searchcategoria_<?php echo($obj['CODIGO']);?>"  >
					   </div>
					 </div>
				 </div>
				 <?php
				}
				}
				?>
				<?php /*
				<h3>Buscar por categoria</h3>
				<div id="normales">
				 <div>
				   <label  style="float:left; margin-left:-25px">Categoria</label>
				   <select name="categoria_buscar" id="categoria_buscar" style="float:left; margin-left:-25px">
				             <option value="">Seleccione..</option>
				   		
		                  <?php if($totalRows_RsClasificacion >0){
		                     do{
							 ?>
							 <option value="<?php echo($row_RsClasificacion['CODIGO']);?>"><?php echo($row_RsClasificacion['NOMBRE']);?></option>
							 <?php
							   }while($row_RsClasificacion = mysqli_fetch_array($RsClasificacion));
							}
							?>
					        </select>
				   <input type="text"  style="float:left; margin-left:-25px" name="search_categoria_des" id="search_categoria_des" placeholder="Buscar" size="25">
				   <input type="hidden" name="search_categoria" id="search_categoria"  >
				 </div>
				</div>
				*/?>
				
</div>
</div>
<div id="temporalreq" style="display:none;">
</div>
	<script type="text/javascript" src="js/jquery.1.7.2.js"></script>
	<!--<script type="text/javascript" src="js/jquery.min.js"></script>-->
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<!--<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>-->
	<script type="text/javascript" src="js/jquery.ui.1.8.16.js"></script>
	<script type="text/javascript" src="js/cotizarjson.js"></script>
	<script type="text/javascript" src="messages.js"></script>
     <script>
	  $(document).ready(function(){			
			$("#ofertas,#favoritos").sortable({
             placeholder: "ui-state-highlight"
			 
            });

	<?php echo "var cadenaDatos = '" . json_encode($arr) . "';"; ?>
	
	<?php echo "var ProveedoresDatos = '" . json_encode($arrproveedores) . "';"; ?>
	

	
try{
   var info = JSON.parse(cadenaDatos);
   	for(var i = 0; i < info.length; i++ ){
	     num = i+1;
	    div='<div id="princ_'+info[i].ID+'" class="princ"><div id="closer_'+info[i].ID+'" style="display:none;" class="closereq"></div><div id="requerimiento_'+info[i].ID+'" class="ficha"><div id="numreq_'+info[i].ID+'" class="num_requerimiento"><b>'+num+'</b></div><p><span class="xxx" id="span_'+info[i].ID+'"><b>'+info[i].CODIGO+'</b></span><br><b>Solicita:</b><br>'+info[i].PERSONA_DES+'<br> </div></div>';
	
	  $("#ofertas").append(div);
	}
}catch(ex){
   
}
/*
try{
   var infop = JSON.parse(ProveedoresDatos);
   	for(var i = 0; i < infop.length; i++ ){
	     //num = i+1;
		
	    div='<div class="box5" id="addprov_'+infop[i].value+'"><span><input type="checkbox" class="chkprov" name="chkprov_'+infop[i].value+'" id="chkprov_'+infop[i].value+'" >&nbsp;&nbsp;'+infop[i].label+'</span>&nbsp;<span class="label label-info" style=" float: right;position: relative;"><a href="http://'+infop[i].web+'" target="_blank">Visitar</a></span></div>';
	    if(infop[i].favo=='1'){
		 $("#favoritos").append(div);
		}else{
	     $("#normales").append(div);
	    }
	}
}catch(ex){
   
}*/
//categoriaproveedor_
try{
   var infop = JSON.parse(ProveedoresDatos);
   	for(var i = 0; i < infop.length; i++ ){
	     //num = i+1;
	    div='<div class="box5" id="addprov_'+infop[i].value+'"><span><input type="checkbox" class="chkprov" name="chkprov_'+infop[i].value+'" id="chkprov_'+infop[i].value+'" >&nbsp;&nbsp;'+infop[i].label+'</span>&nbsp;<span class="label label-info" style=" float: right;position: relative; clear:both;"><a href="http://'+infop[i].web+'" target="_blank">Visitar</a></span></div>';
	    if(infop[i].favo=='1'){
		 $("#favoritos").append(div);
		}else{
	     if(infop[i].cate.length>0)
	      for(k=0; k<infop[i].cate.length; k++){
		   //alert(infop[i].cate[k].IDCAT);
		   $("#categoriaproveedor_"+infop[i].cate[k].IDCAT).append(div);
		  }else{
		   $("#normales").append(div);
		  }
	    }
	}
}catch(ex){
   
}


$("#menunav>li").click(function(evento){
    $('#menunav li.active').each(function(index){
     $(this).removeClass('active');
	 })
	 $(this).addClass('active');
});
CompletarProveedor=JSON.parse(ProveedoresDatos);
//alert('pro'+CompletarProveedor);
			$("#search_actual_des").autocomplete({
			source: CompletarProveedor, 				
			minLength: 2,									
			select: function(event, ui){
					 $("#search_actual_des").val(ui.item.label);
					 $("#search_actual").val(ui.item.value);
					 addactuales(ui.item.value,CompletarProveedor,'actuales');
					 event.preventDefault();
					},
			focus: function(event, ui){
					  $("#search_actual_des").val(ui.item.label);
					 event.preventDefault();
					}
		});
/*
		$("#search_favorito_des").autocomplete({
			source: function( request, response ) {
				  availableTags='[{"label":"ACTIVOS","value":"1","web":"www.activos.com","favo":"0","cate":[]},{"label":"ALMACEN TIKE","value":"6","web":"www.almtike.com","favo":"0","cate":[]},{"label":"Bimbo","value":"8906543","web":"bimbo.com","favo":"0","cate":[{"IDCAT":"17","NCAT":"TIENDA ESCOLAR","CALI":"4"}]}]';
					response(
						JSON.parse(availableTags));
				},				
			minLength: 2,									
			select: function(event, ui){
					 $("#search_favorito_des").val(ui.item.label);
					 $("#search_favorito").val(ui.item.value);
					 addactuales(ui.item.value,CompletarProveedor);
					 event.preventDefault();
					},
			focus: function(event, ui){
					  $("#search_favorito_des").val(ui.item.label);
					 event.preventDefault();
					}
		});
		*/
		$("#search_normal_des").autocomplete({
			source: function( request, response ) {
				      var odeta = {};
				      var newArrayD = new Array();
				      var divs = $('#normales').find('.box5');
						divs.each(function(){
						      var div = $(this).attr('id');
							      id = div.split("_");
						          texto = ($("#"+div).text()).split("Visitar");
						      var odeta = { 'label': texto[0],'value':id[1],'web': $("#normales>#addprov_"+id[1]+" >span a").attr("href")};
						          newArrayD.push(odeta);
						})
					response( $.ui.autocomplete.filter(
						newArrayD, ( request.term ) ) );
				},				
			minLength: 2,									
			select: function(event, ui){
					 $("#search_normal_des").val(ui.item.label);
					 $("#search_normal").val(ui.item.value);
					 addactuales(ui.item.value,CompletarProveedor, 'normales');
					 event.preventDefault();
					},
			focus: function(event, ui){
					  $("#search_normal_des").val(ui.item.label);
					 event.preventDefault();
					}
		});
		
		$("#search_favorito_des").autocomplete({
			source: function( request, response ) {
				      var odeta = {};
				      var newArrayD = new Array();
				      var divs = $('#favoritos').find('.box5');
						divs.each(function(){
						      var div = $(this).attr('id');
							      id = div.split("_");
						          texto = ($("#"+div).text()).split("Visitar");
						      var odeta = { 'label': texto[0],'value':id[1],'web': $("#favoritos>#addprov_"+id[1]+" >span a").attr("href")};
						          newArrayD.push(odeta);
						})
					response( $.ui.autocomplete.filter(
						newArrayD, ( request.term ) ) );
				},				
			minLength: 2,									
			select: function(event, ui){
					 $("#search_favorito_des").val(ui.item.label);
					 $("#search_favorito").val(ui.item.value);
					 addactuales(ui.item.value,CompletarProveedor, 'favoritos');
					 event.preventDefault();
					},
			focus: function(event, ui){
					  $("#search_favorito_des").val(ui.item.label);
					 event.preventDefault();
					}
		});


		$(".inputsearchc").autocomplete({
			source: function( request, response ) {
			        idactual = $("#actualsearch").val();
				      var odeta = {};
				      var newArrayD = new Array();
				      var divs = $('#categoriaproveedor_'+idactual).find('.box5');
						divs.each(function(){
						      var div = $(this).attr('id');
							      id = div.split("_");
						          texto = ($("#"+div).text()).split("Visitar");
						      var odeta = { 'label': texto[0],'value':id[1],'web': $("#categoriaproveedor_"+idactual+">#addprov_"+id[1]+" >span a").attr("href")};
						          newArrayD.push(odeta);
						})
					response( $.ui.autocomplete.filter(
						newArrayD, ( request.term ) ) );
				},				
			minLength: 2,									
			select: function(event, ui){
			         idactual = $("#actualsearch").val();
					 $("#searchcategoriades_"+idactual).val(ui.item.label);
					 $("#searchcategoria_"+idactual).val(ui.item.value);
					 addactuales(ui.item.value,CompletarProveedor, idactual);
					 event.preventDefault();
					},
			focus: function(event, ui){
			         idactual = $("#actualsearch").val();
					  $("#searchcategoriades_"+idactual).val(ui.item.label);
					 event.preventDefault();
					}
		});
			
/*			
var people = ['Peter Bishop', 'Nicholas Brody', 'Gregory House', 'Hank Lawson', 'Tyrion Lannister', 'Nucky Thompson'];
    var cache = {};
    var drew = false;
    
    $(".inputsearchc").on("keyup", function(event){
        var query = $(this).val();
		var iddiv = ($(this).attr("id")).split("_");
		    id   = iddiv[1];
        if($(this).val().length > 2){
            
            //Check if we've searched for this term before
            if(query in cache){
                results = cache[query];
            }
            else{
                //Case insensitive search for our people array
                var results = $.grep(people, function(item){
                    return item.search(RegExp(query, "i")) != -1;
                });
                
                //Add results to cache
                cache[query] = results;
            }
            
            //First search
            if(drew == false){
                //Create list for results
                $("#searchcategoriades_"+id).after('<ul id="res"></ul>');
                
                //Prevent redrawing/binding of list
                drew = true;
                
                //Bind click event to list elements in results
                $("#res").on("click", "li", function(){
                    $("#search").val($(this).text());
                    $("#res").empty();
                 });
            }
            //Clear old results
            else{
                $("#res").empty();
            }
            
            //Add results to the list
            for(term in results){
                $("#res").append("<li>" + results[term] + "</li>");
            }
        }
        //Handle backspace/delete so results don't remain with less than 3 characters
        else if(drew){
            $("#res").empty();
        }
    });			
	
	*/

	
	/*	
$( "#mkt" ).accordion();		

});
*/

		$( "#mkt" )
			.accordion({
				header: "> div > h3",
				placeholder: "ui-state-highlight"
			})
			.sortable({
				axis: "y",
				handle: "h3",
				stop: function( event, ui ) {
					// IE doesn't register the blur when sorting
					// so trigger focusout handlers to remove .ui-state-focus
					ui.item.children( "h3" ).triggerHandler( "focusout" );
				}
			});
	});
	
function prueba(){
var odeta = {};
var newArrayD = new Array();
   var divs = $('#favoritos').find('.box5');
	divs.each(function(){
	var div = $(this).attr('id');
	     id = div.split("_");
	  texto = $("#addprov_"+id[1]).text();
	   name = texto.split('Visitar');
	var odeta = { 'label': name,'value':id[1],'web': $("#favoritos>#addprov_"+id[1]+" >span a").attr("href")};
	//alert($("#"+id).text());
	newArrayD.push(odeta);
	});
/*	alert( newArrayD.length);
	records= JSON.parse(newArrayD);*/
	for(i=0; i<newArrayD.length; i++){
	alert(newArrayD[i].label);
	}
}	
function removerXcategoria(cat){
    $("#"+cat+" .deletzoneactual").each(function(index){
     $(this).remove();
	 })
}
function quitar(valor){
    cat = $("#actuales > #addprov_"+valor).attr("data-categoria");
	cate = parseInt(cat);
	if(isNaN(cat)){
	divcate=cat;
	}else{
	divcate='categoriaproveedor_'+cate;
	}
	//alert(divcate);
    temp = "<div id='addprov_"+valor+"' data-categoria='"+cat+"' class='box5'>"+$("#addprov_"+valor).html()+"</div>";
   	$("#"+divcate).append(temp); 
	$("#actuales > #addprov_"+valor).remove();
	removerXcategoria(divcate);
}
function addactuales(valor,proveedores,grupo_categoria){
 temp   = ''
 for(var i = 0; i < proveedores.length; i++ ){
  if(proveedores[i].value==valor){
   temp = "<div id='addprov_"+valor+"' data-categoria='"+grupo_categoria+"' class='box5'><span class='label  deletzoneactual' ><a href='javascript:quitar(\""+valor+"\");' title='quitar de esta seccion'></a></span>"+$("#addprov_"+valor).html()+"</div>";
  }
 }
 /*if(document.getElementById("addprov_"+valor)==null){
 $("#actuales").append(temp); 
 }
 */
 vacioid='';
 vacioiddes='';
 divexiste = $("#actuales > #addprov_"+valor).length;
 if(isNaN(grupo_categoria)){
	 if(divexiste==0){
	  $("#"+grupo_categoria+"> #addprov_"+valor).remove();
	  }
   if(grupo_categoria=='favoritos'){
      vacioid='search_favorito';
      vacioiddes='search_favorito_des';
   }
   if(grupo_categoria=='normales'){
      vacioid='search_normal';
      vacioiddes='search_normal_des';
   }
    if(grupo_categoria=='actuales'){
      vacioid='search_actual';
      vacioiddes='search_actual_des';
   }
  }else{
 	 if(divexiste==0){
      $("#categoriaproveedor_"+grupo_categoria+"> #addprov_"+valor).remove();
	}
     vacioid='searchcategoria_'+grupo_categoria;
     vacioiddes='searchcategoriades_'+grupo_categoria;
  }
//$("#addprov_"+valor).remove(); 

	 if(divexiste==0){
       $("#actuales").append(temp); 
	   inlineMsg('Requerimientoscotizar','registro agregado a actuales.',3);
     }else{
       inlineMsg('Requerimientoscotizar','este proveedor ya se encuentra agregado a actuales.',3);	 
	 }
$("#"+vacioid).val("");
$("#"+vacioiddes).val("");
}
</script>
</html>