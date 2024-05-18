<?php
//inicio del php

require_once('conexion/db.php');
	if (!isset($_SESSION)) {
  session_start();
}
//Declaracion de variables
$codigo='';
if(isset($_GET['codigo'])&&$_GET['codigo']!='')
{
$codigo=$_GET['codigo'];
}
$fecha_desde = '';
$fecha_hasta = '';

$currentPage = $_SERVER["PHP_SELF"];
$tamanoPagina = 30;
$maxRows_RsFechasLista = $tamanoPagina;
$pageNum_RsFechasLista = 0;
if (isset($_GET['pageNum_RsFechasLista'])) {
  $pageNum_RsFechasLista = $_GET['pageNum_RsFechasLista'];
}
$startRow_RsFechasLista = $pageNum_RsFechasLista * $maxRows_RsFechasLista;

    $query_RsFechasLista="SELECT P.PERSID PERSONA,
							   CONCAT(P.PERSNOMB,' ',P.PERSAPEL) PERSONA_DES,
							   CASE P.PERSPERE
								WHEN 1
								 THEN 'NO'
								WHEN 2
								 THEN 'SI'
								ELSE 'NO'
							   END PERMITE_REQ_ESPECIAL
						FROM personas P,
							 usuarios U
						where P.PERSUSUA = U.USUALOG
						 AND  P.PERSEST = '0'
						 AND  U.USUAROL = '4'";
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
?><script type="text/javascript">

  function f_abrir_link(v_link)
	{
	  document.form1.action=v_link;
	  document.form1.submit();

    }


function estadoreq_especial(ced){ 
	if(confirm("seguro que desea cambiar el estado para solicitar requerimiento especial a esta persona?")){	
      var date = new Date();
      var timestamp = date.getTime();	
		$.ajax({
			type: "POST",
			url: "tipo_guardar.php?tipoGuardar=HabilitarReqEspecial&persona="+ced+"&times="+timestamp,
			success : function(r){
				if(r>0){
					if(r == 1){
						$("#aa_"+ced).html(' NO ');
					}
					if(r == 2){
						$("#aa_"+ced).text(' SI ');
					}	
				}else{
					$("#aa_"+ced).html(' NO ');
				}
				
			},
			error   : callback_error
		});
	}
}
function callback_error(XMLHttpRequest, textStatus, errorThrown)
{
    alert("Respuesta del servidor "+XMLHttpRequest.responseText);
    alert("Error "+textStatus);
    alert(errorThrown);
}
</script>
</head>

<body>
<form name="form1" id="form1" action="" method="post">

  <table>
    <tr class="SLAB trtitle" align="center" style="font-size:11px;">
	 <td>PERSONA</td>
	 <td>PERMITIR ENVIAR <br>REQUERIMIENTO<br> ESPECIAL</td>
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
	    <td><?php echo($row_RsFechasLista['PERSONA_DES']);?></td>
	    <td align="center">
		<a id="aa_<?php echo($row_RsFechasLista['PERSONA']);?>"  href="javascript: estadoreq_especial('<?php echo($row_RsFechasLista['PERSONA']);?>')" class="buttonazul"> <?php echo($row_RsFechasLista['PERMITE_REQ_ESPECIAL']);?> <a>
		</td>
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