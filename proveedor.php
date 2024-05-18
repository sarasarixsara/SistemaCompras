<?php
require_once('conexion/db.php');
	
$codigo_proveedor="";
if(isset($_GET['cod_prov']) && $_GET['cod_prov']!=''){
$codigo_proveedor = $_GET['cod_prov'];
}



if($codigo_proveedor!=''){
	$query_RsLista_prov="SELECT R.REQUCORE REQUERIMIENTO_CODIGO,
							   R.REQUCODI REQUERIMIENTO,
							   DR.DEREPROV DETALLE_ID_PROVEEDOR,
							   DR.DEREDESC DETALLE_DESC,
							   DR.DERECOOC DETALLE_ORDEN
							   

						FROM REQUERIMIENTOS R ,
							 DETALLE_REQU  DR ,
							 PROVEEDORES P
							 

						WHERE P.PROVCODI='".$codigo_proveedor."'
						AND DR.DEREREQU=R.REQUCODI
						AND DR.DEREPROV=P.PROVCODI      
					";
	$RsLista_prov = mysqli_query($conexion,$query_RsLista_prov) or die(mysqli_error($conexion));
	$row_RsLista_prov = mysqli_fetch_array($RsLista_prov);
    $totalRows_RsLista_prov = mysqli_num_rows($RsLista_prov);


}

?>

<!DOCTYPE html>
<html>
<title>Proveedores</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="css/estilo_solicitud.css" />


<style type="text/css">
body{
background:#fff;	

}

#menu{
/*background:#26B826;*/
/*background:#4C954B;*/
height:50px;
font-size:13px;
margin-top:-10px;
border-radius:13px;
}
#menu_proveedores{
 /* background:#26B826; */
 padding-top:15px;
 color:#FDF8F8;
 font-weight:bold;
}
#menu_proveedores li{
display:inline;
padding-left:15px;
padding-right:15px;
padding-top:10px;
padding-bottom:10px;
background:#4C954B;
border-radius:13px;
list-style-type:none;
}

#menu_proveedores a{
width:260px;
/*background:#ff0000;*/
text-decoration:none;
color:#ffffff;
}

#menu_proveedores a:hover{
color:#000000;
}

#menu_proveedores li:hover{
background:#99F199;
font-size:13px;
border-radius:13px;
}
</style>

 <table class="tablalistado" cellspacing="2" border="0">
   <tr class="SLAB trtitle">
    <td>REQUERIMIENTO</td>
	<td>DESCRIPCION DETALLE</td>
	<td>ORDEN</td>
   </tr>
   <?php
    if($totalRows_RsLista_prov >0){
	  $j=0;
      do{
	   $j++;
	    $estilo="SB";
	    if($j%2==0){
		$estilo="SB2";
		}
	  ?>
	  <tr class="<?php echo($estilo);?>">
	   <td>  <a href="/home.php?page=solicitud&codreq=<?php echo($row_RsLista_prov['REQUERIMIENTO']);?>" target="_back"><?php echo($row_RsLista_prov['REQUERIMIENTO_CODIGO']);?></a></td>
	   <td class='text-justify'><?php echo($row_RsLista_prov['DETALLE_DESC']);?></td>
	   <td><?php echo($row_RsLista_prov['DETALLE_ORDEN']);?></td>
	   
	  </tr>
	  <?php
	   }while($row_RsLista_prov = mysqli_fetch_array($RsLista_prov));
	}
   ?>
  </table>


</html>