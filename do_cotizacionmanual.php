<?php 
require_once('conexion/db.php'); 
	if (!isset($_SESSION)) {
      session_start();
    }
if(!isset($_SESSION['MM_AccesoCorrectoApp']) || $_SESSION['MM_AccesoCorrectoApp'] != 'ACTIVO'){
  header("location: index.php");
}

$ingreso = '';	
$totalRows_RsConsultarKey = '';
$totalRows_RsDatosCotizacion = '';
$consecutivo       = '';
$codigo_cotizacion = $_GET['cotizacion'];
$link               = '';
$estado            = '';
$first = '';
$flete = '';
$forma_pago = '';
$garantia = '';
$sitio_entrega = '';
$tiempo_entrega = '';
$valor_agregado = '';
$observacion_general = '';



  		$query_RsDatosCotizacion = "SELECT C.COTICODI CODIGO_COTIZACION,
		                                   C.COTIPROV CODIGO_PROVEEDOR,
										   C.COTIOBSE OBSERVACION_GENERAL,
										   C.COTIFOPA FORMA_PAGO,
										   C.COTIGARA GARANTIA,
										   C.COTITIEN2 TIEMPO_ENTREGA,
										   C.COTISIEN SITIO_ENTREGA,
										   C.COTIFLET FLETE,
										   C.COTIVAAG VALOR_AGREGADO,
										   P.PROVNOMB NOMBRE_PROVEEDOR,
										   D.CODECOTI CODIGO_COTIZACIONN,
										   D.CODEDETA CODIGO_DETALLE,
                                           D.CODECODI CONSECUTIVO,										   
                                          DE.DEREDESC DESCRIPCION,
										  DE.DERECANT CANTIDAD,
										  (SELECT UNMENOMB FROM unidad_medida WHERE UNMECONS=DE.DEREUNME) U_MEDIDA,
										  DE.DEREOBSE OBSERVACION,
										  D.CODEVALO VALOR_UNITARIO,
										  D.CODEDESC DESCRIPCION_PROV,
										  D.CODEVAIV IVA,
										  D.CODEVIVA VALOR_IVA,
										  (D.CODEVALO * DE.DERECANT) VALOR_TOTAL
										   
										FROM 
										COTIZACION C,
										COTIZACION_DETALLE D,
										DETALLE_REQU      DE,
										PROVEEDORES         P
									WHERE C.COTICODI = '".$codigo_cotizacion."'
									  AND C.COTICODI = D.CODECOTI	
									  AND D.CODEDETA = DE.DERECONS
									  AND C.COTIPROV = P.PROVCODI
									  #AND C.COTIESTA = 0
								
									  
		                                 ";
								//echo($query_RsDatosCotizacion);
		$RsDatosCotizacion = mysqli_query($conexion,$query_RsDatosCotizacion) or die(mysqli_connect_error());
		$row_RsDatosCotizacion = mysqli_fetch_array($RsDatosCotizacion);
		$totalRows_RsDatosCotizacion = mysqli_num_rows($RsDatosCotizacion);
$opciones_tiempo_entrega = array();
for($i=0; $i<31; $i++){
   $opciones_tiempo_entrega[] = array('n'  => $i);	
}
$opciones_tiempo_entrega[]  = array('n'  => 'mas de 30 días');
//var_dump($opciones_tiempo_entrega);

?><!DOCTYPE HTML>
<head>
<title>Ingresar Datos Manualmente</title>
<meta charset="utf-8">
	<!--<link rel="stylesheet" type="text/css" href="css/page.css"/>-->
	<style type="text/css">
	</style>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->
	<script type="text/javascript" src="js/jquery.1.7.2.js"></script>
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
/*
$(document).ready(function() {	

});		
*/	
function mostrar(){
$('.descripciones_prov').each(function(index){
		  ($(this).toggle());		  
		});
}

function guardar(){
var t=0;
  $('.valorunitario').each(function(index){
          if($(this).val()==''){
		   t=1;
		  }		  
	});

 
 if(document.getElementById('forma_pago').value==''){
  alert('debe ingresar la  Forma de Pago.');
  return false;
 }
 
  if(document.getElementById('garantia').value==''){
  alert('debe ingresar la Garantia. ');
  return false;
 }
 
   if(document.getElementById('sitio_entrega').value==''){
  alert('debe ingresar el Sitio de Entrega. ');
  return false;
 }
 
   if(document.getElementById('tiempo_entrega').value==''){
  alert('debe ingresar el Tiempo Entrega. ');
  return false;
 }
 tiene_valores=0;
$('.valorunitario').each(function(index){
 if($(this).val() != ''){
	 tiene_valores = 1;
 }
});
if(tiene_valores == 0){
	alert("debe ingresar algun dato para los registros a cotizar");
	return false;
}
	if(confirm("Seguro que desea guardar estos valores?")){
      document.form1.action="do_cotizacion_guardar.php?tipoguardar=save1";
	}else{
		return false;
	}
}	

function unitario(cod){
vtotal   = '';
valorsum = 0;
valoriva = 0;
  if(cod!=''){
    //alert(document.getElementById('valor_'+cod).value);
	valor_unitario = parseInt(document.getElementById('valor_'+cod).value);
	cantidad_un    = parseInt(document.getElementById('cantidad_'+cod).value);
	vtotal = valor_unitario*cantidad_un;
	document.getElementById('valortotal_'+cod).value = vtotal;
		$('.input_cant2').each(function(index){
		  codigo_div = ($(this).attr('id'));
		  part = codigo_div.split("_");
		  cod_det = part[1];
		  //valoriva_469
		  //valorivapre_
		  //console.log('jajajja'+$(this).val());
		  if($(this).val()!='' && $(this).val()>0){
		    //valorsum = 0;
		    valorentrada = parseFloat($(this).val());
			valorsum = valorsum+valorentrada;
			ivatr = $("#valoriva_"+cod_det).val() | 0;
			valorivatr = ((ivatr*valorentrada)/100);
			$("#valorivapre_"+cod_det).val(valorivatr);
			//console.log(valorentrada);
		  }
		});
	if(valorsum!=''){
	   //valorsum = decimal2(valorsum/1.16);
	   $("#subtotal").val(valorsum);
	   //$("#iva").val(decimal2(parseFloat($("#subtotal").val())*0.16));
	   //$("#total").val(decimal2(parseFloat($("#subtotal").val()))+decimal2(parseFloat($("#iva").val())));
	   SumarIva();
	   valor_total = (parseFloat($("#subtotal").val()) + parseFloat($("#iva").val()));
	   valor_total = valor_total | 0;
	   $("#total").val(valor_total);
	 }
   }
}
//dias habiles calendario
function Fiva(){
	$('.valoriva').each(function(index){
		campo = ($(this).attr('id'));
		part = campo.split("_");
		cod_det = part[1];
		//console.log(cod_det);
		ivatr    = parseInt($("#valoriva_"+cod_det).val()) | 0;
		vtotaltr = parseFloat($("#valortotal_"+cod_det).val()) | 0;
		resultado= ((vtotaltr*ivatr)/100);
		$("#valorivapre_"+cod_det).val(resultado);
	});	
	SumarIva();
	   valor_total = (parseFloat($("#subtotal").val()) + parseFloat($("#iva").val()));
	   valor_total = valor_total | 0;
	   $("#total").val(valor_total);
	
}

function SumarIva(){
	  sumaivas = 0;
	  $('.valorivapre').each(function(index){
		  codigo_div = ($(this).attr('id'));
		  console.log(codigo_div);
		  valorentrada = parseFloat($(this).val())|0;
		  console.log(valorentrada);
		  sumaivas = sumaivas+valorentrada;
		  /*
		  if($(this).val()!=''){
		    //valorsum = 0;
		    valorentrada = parseInt($(this).val());
			valorsum = valorsum+valorentrada;
		  }
		  */
		});
		$("#iva").val(sumaivas);
}

function decimal2(numero)
 {
	var original=parseFloat(numero);
	var result=Math.round(original*100)/100;
	return result;
 }
	function acceptNum(event)
		{
			var key; 
			if(window.event)
			{ 
				key = event.keyCode;
			}
			else if(event.which)
			{ 
				key = event.which;
			} 
			//return (key == 45 || key == 13 || key == 8 || key == 9 || key == 189 || (key >= 48 && key <= 58) )
			//var nav4 = window.Event ? true : false;
			//var key = nav4 ? evt.which : evt.keyCode;
			return (key <= 13 || (key >= 48 && key <= 57));
		}
		

	function format(input)
	{
	var num = input.value.replace(/\./g,'');
	if(!isNaN(num)){
	num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
	num = num.split('').reverse().join('').replace(/^[\.]/,'');
	input.value = num;
	}

	else{ alert('Solo se permiten numeros');
	input.value = input.value.replace(/[^\d\.]*/g,'');
	}
	}		
</script>
<style type="text/css">
body{
font-family:verdana;
}
 #primary{
  padding:5px 5px;
  background:#FDFEFF;
  margin-top:10px;
  border-radius:4px;
  
 }
 #primary td{
 border-collapse:collapse;
 border-spacing:0;
 empty-cells:show;
 }

 .btnsave{
 background: none repeat scroll 0 0 #760000;
    border: medium none;
    border-radius: 4px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
    color: #eee;
    cursor: pointer;
    font-size: 15px;
    margin: 5px 0;
    padding: 5px 22px;
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.3);
    text-transform: uppercase;
 }
  .btnsave:hover{
    background:#a22626;
   }
#primary input[type="text"]{
border-radius:4px;
height:20px;
border:0;
}
/*
input[type="text"]:focus{
 background:#f5fcfe;
 color:#373737;
 box-shadow:0 0 5px rgba(4, 129, 177, 0.5);
 opacity:0.6;
 }
 */
 .Titulo{
  background:#760000;
  color:#ffffff;
  font-weight:bold;
  font-size:14px;
  line-height:2;
 }
 .SB{
 background:#EAEAEA;
 }
 .SB2{
 background:#D9D3FF;
 }
 .SB:hover, .SB2:hover{
  background:#D3EDB8;
 }
 .Titulo2{
 font-weight:500;
 color:#000000;
 font-size:21px;
 background:#d9d3ff;
 padding-left:12px;
 padding-right:12px;
 }
 .textbig{
 font-size:21px;
 text-align:center;
 width:100%;
 }
 .total{
 height:35px;
 background:#E0E6E1;
 color:#4A4748;
 font-size:20px;
 text-align:center;
 border-radius:8px;
 margin-left:5px;
 box-shadow: 1px 1px 3px 1px #D1D1D5;
 font-weight:bold;
 }
 .total2{
 height:45px;
 background:#FFFFFF;
 color:#4A4748;
 font-size:16px;
 text-align:center;
 border-radius:8px;
 margin-left:5px;
 box-shadow: 1px 1px 3px 1px #D1D1D5;
 font-weight:bold;
 padding:1px 2px;
 }
 .input_cant{
 background:inherit;
 text-align:center;
 }
 .input_cant2{
 background:inherit;
 text-align:right;
 }
 .valorivapre{
	 background:inherit;
 }
</style>
</head>
<form name="form1" id="form1" method="post" action="">
<div id="contenedor" style="overflow:hidden; width:100%;  margin: 0 auto; min-height:600px; background:#FCFCFC; border: solid 1px #ccc; border-radius:5px;">
  <table align="center" border="0" width="100%" id="primary" cellspacing="1" cellpadding="10"><?php
  	
    if($totalRows_RsDatosCotizacion >0){
	?>
	<tr>
	 <td colspan="6" STYLE="color:#91282f; background:#F9F7F7; font-style:italic" align="center"><?php echo($row_RsDatosCotizacion['NOMBRE_PROVEEDOR']);?></td>
	</tr>
	<tr>
	 <td colspan="2">
	  <input type="button" onclick="mostrar();" value="mostrar/ocultar observaciones">
	 </td>
	</tr>
	<tr class="Titulo" align="center">
	  <td>#</td>
	  <td width="350">detalle</td>
	  <td>Cantidad</td>
	  <td>U/med</td>
	  <td width="150">valor unitario</td>
	  <td>Iva</td>
	  <td>Valor Total</td>
	</tr>
	<?php
	   $i=0;
	   
	  do{
	     $estilo = "SB2";
	     $i++;
		 if($i==1){
			 $first = $row_RsDatosCotizacion['CONSECUTIVO'];
			 $flete = $row_RsDatosCotizacion['FLETE'];
			 $forma_pago = $row_RsDatosCotizacion['FORMA_PAGO'];
			 $garantia = $row_RsDatosCotizacion['GARANTIA'];
			 $sitio_entrega = $row_RsDatosCotizacion['SITIO_ENTREGA'];
			 $tiempo_entrega = $row_RsDatosCotizacion['TIEMPO_ENTREGA'];
			 $valor_agregado = $row_RsDatosCotizacion['VALOR_AGREGADO'];
			 $observacion_general = $row_RsDatosCotizacion['OBSERVACION_GENERAL'];
		 }
		 if($i%2==0){
		   $estilo="SB";
		 }
	  ?>
	 <tr class="<?php echo($estilo);?>" >
	  <td><?php echo($i);?></td>
	  <td><?php echo($row_RsDatosCotizacion['DESCRIPCION']);?> <BR><BR> <?php echo($row_RsDatosCotizacion['OBSERVACION']);?></td>
	  <td align="center"><input type="text" class="input_cant" size="10" name="cantidad_<?php echo($row_RsDatosCotizacion['CONSECUTIVO']);?>" id="cantidad_<?php echo($row_RsDatosCotizacion['CONSECUTIVO']);?>" value="<?php echo($row_RsDatosCotizacion['CANTIDAD']);?>" readonly></td>
	  <td align="center"><input type="text" class="input_unmed" size="10" name="unmed_<?php echo($row_RsDatosCotizacion['CONSECUTIVO']);?>" id="unmed_<?php echo($row_RsDatosCotizacion['CONSECUTIVO']);?>" value="<?php echo($row_RsDatosCotizacion['U_MEDIDA']);?>" readonly></td>
	  <td align="center"><span class="total2">$</span>&nbsp;<input size="14" class="valorunitario" type="text" name="valor_<?php echo($row_RsDatosCotizacion['CONSECUTIVO']);?>" id="valor_<?php echo($row_RsDatosCotizacion['CONSECUTIVO']);?>" onKeyPress="return acceptNum(event);" value="" onchange="unitario('<?php echo($row_RsDatosCotizacion['CONSECUTIVO']);?>');"></td>
	  <td><span class="total2">%</span>&nbsp;<input size="14" class="valoriva" type="text" name="valoriva_<?php echo($row_RsDatosCotizacion['CONSECUTIVO']);?>" id="valoriva_<?php echo($row_RsDatosCotizacion['CONSECUTIVO']);?>" onKeyPress="return acceptNum(event);" value=""  maxlength="2" onchange="Fiva('<?php echo($row_RsDatosCotizacion['CONSECUTIVO']);?>');">	<br>
		<input type="text" class="valorivapre" name="valorivapre_<?php echo($row_RsDatosCotizacion['CONSECUTIVO']);?>" id="valorivapre_<?php echo($row_RsDatosCotizacion['CONSECUTIVO']);?>" value="" size="14" readonly>
	  </td>
	  <td align="center"><span class="total2">$</span>&nbsp;<input class="input_cant2" readonly type="text" size="17" name="valortotal_<?php echo($row_RsDatosCotizacion['CONSECUTIVO']);?>" id="valortotal_<?php echo($row_RsDatosCotizacion['CONSECUTIVO']);?>" value="<?php echo($row_RsDatosCotizacion['VALOR_TOTAL']);?>"></td>
	 </tr>
	 <tr >
	  <td colspan="9" >
	   <div style="display:block" class="descripciones_prov">
        <table width="900" >	
         <tr>
           <td><textarea cols="45"  style=" width:97%; height:40px;" placeholder="Descripcion"  id="areaprov_<?php echo($row_RsDatosCotizacion['CONSECUTIVO']);?>" name="areaprov_<?php echo($row_RsDatosCotizacion['CONSECUTIVO']);?>"><?php echo($row_RsDatosCotizacion['DESCRIPCION_PROV']);?></textarea></td>
		 </tr>
		</table>
	  <div>
	  </td>
	 </tr>
	  <?php
	    }while($row_RsDatosCotizacion = mysqli_fetch_array($RsDatosCotizacion));
		?>
	
	 </table>
	 <table width="900" align="center" cellspacing="0" border="0" id="second">
	 <tr>
	    <td>
		 <table>
		  <tr>
		     <td width="900" align="center" class="Titulo2" height="35">Observaciones :</td>
		  </tr>
		  <tr>
		   <td>
		    <table>
				  <tr>
					  <td rowspan="5"><textarea name="observaciones" id="observaciones" cols="67" rows="6"><?php echo($observacion_general);?></textarea></td>
					  <td>
					  <table>
                        <tr>						 
						                         <tr>						 
						 <td align="right" class="Titulo2">Subtotal: <span class="total">$</span></td>
						  <td><input class="total" type="text" name="subtotal" id="subtotal" size="12" readonly></td>
					    </tr>
						<tr>
						  <td align="right" class="Titulo2">Iva: <span class="total">$</span></td>
						  <td><input class="total" type="text" name="iva" id="iva" size="12" readonly></td>
					    </tr>
						<tr>
						   <td colspan="" align="right" CLASS="Titulo2" align="center">Total: <span class="total">$</span></td>
						   <td align="center"  width="100"><input class="total" type="text" name="total" id="total" size="12" readonly ></td>
						</tr>
						<tr>
						<td align="right" class="Titulo2">Flete: <span class="total">$</span></td>
						  <td><input class="total" type="text" name="flete" id="flete" size="12" onKeyPress="return acceptNum(event);" value="<?php echo($flete);?>"></td>
					    </tr>
						</tr>
						</td>
					  </table>

			</table>
		   </td>
		  </tr>
		 </table>
		</td>
	 </tr>
      <tr>
	    <td colspan="9">
	 <table width="900">
	 <tr>
	  <td class="Titulo2" colspan="2" align="right">Forma de pago:</td>
	  <td>
	  <select  name="forma_pago" id="forma_pago" class="form-control" >				
					<option value="">- Seleccione -</option>
					<?php
					require_once("scripts/funcionescombo.php");		
					$estados = dameFormaDePago();
						foreach($estados as $indice => $registro){
						?>
							<option value="<?php echo($registro['CRMECONS'])?>" <?php if($forma_pago == $registro['CRMECONS']){ echo('selected');} ?> ><?php echo($registro['CRMEDESC']);?></option>
						<?php
						}
					
					?>
				</select>
	  </td>
	 </tr>
	 <tr>
	  <td class="Titulo2" colspan="2" align="right">Garantia:</td>
	  <td colspan="3">
	   <select  name="garantia" id="garantia" class="form-control" >				
					<option value="">- Seleccione -</option>
					<?php
					require_once("scripts/funcionescombo.php");		
					$estados = dameGarantia();
						foreach($estados as $indice => $registro){
						?>
							<option value="<?php echo($registro['CRMECONS'])?>"  <?php if($garantia == $registro['CRMECONS']){ echo('selected');} ?> ><?php echo($registro['CRMEDESC']);?></option>
						<?php
						}
					
					?>
				</select>
	  </td>
	  
	 </tr>
	 <tr>
	  <td class="Titulo2" colspan="2" align="right">Entrega:</td>
	  <td colspan="3">
	    <select  name="sitio_entrega" id="sitio_entrega" class="form-control" >				
					<option value="">- Sitio -</option>
					<?php
					require_once("scripts/funcionescombo.php");		
					$estados = dameSitioEntrega();
						foreach($estados as $indice => $registro){
						?>
							<option value="<?php echo($registro['CRMECONS'])?>" <?php if($sitio_entrega == $registro['CRMECONS']){ echo('selected');} ?> ><?php echo($registro['CRMEDESC']);?></option>
						<?php
						}
					
					?>
				</select>
				 <select  name="tiempo_entrega" id="tiempo_entrega" class="form-control" >				
					<option value="">- Tiempo en dias -</option>
					<?php
					/*
					require_once("scripts/funcionescombo.php");		
					$estados = dameTiempoEntrega();
						foreach($estados as $indice => $registro){
						?>
							<option value="<?php echo($registro['CRMECONS'])?>"><?php echo($registro['CRMEDESC']);?></option>
						<?php
						}
					*/
					for($k=1; $k<count($opciones_tiempo_entrega); $k++){
						?>
					<option value="<?php echo($k);?>" <?php if($tiempo_entrega == $k){ echo('selected');} ?>  ><?php echo($opciones_tiempo_entrega[$k]['n']);?></option>
						<?php
					}
					?>
				</select>
	  </td>
	
	 </tr>
	 <!--
	 <tr>
	 <td class="Titulo2" colspan="2" align="right">Valor Agregado :</td>
	   <td>
	  <select  name="v_agregado" id="v_agregado" class="form-control" >				
					<option value="">- Valor Agregado (servicios tecnicos, lineas de atencion, garantias etc) -</option>
					<?php
					require_once("scripts/funcionescombo.php");		
					$estados = dameValoragregado();
						foreach($estados as $indice => $registro){
						?>
							<option value="<?php echo($registro['CRMECONS'])?>"  <?php if($valor_agregado == $registro['CRMECONS']){ echo('selected');} ?>   ><?php echo($registro['CRMEDESC']);?></option>
						<?php
						}
					
					?>
				</select>
	  </td>
	 </tr> -->
	 <tr>
	   <td colspan="8" align="center">
	     <input type="hidden" name="codigo_cotizacion" id="codigo_cotizacion" value="<?php echo($codigo_cotizacion);?>">
	    
	     <input type="submit" name="" id="" class="btnsave" Value="Enviar" onclick="return guardar();">
	   </td>
	 </tr>		
		</td>
      </tr>	  
	 </table>
		<?php
		
	}else{
	?>
	<p style="margin:15px 0px 0px 20px; font-size:30px;">este registro no se encuentra disponible</p>
	<?php
	
	}
  ?>
  </table>
</div>
<script type="text/javascript">
unitario('<?php echo($first);?>');
</script>
</form>
</html>