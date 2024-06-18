<?php
require_once ('conexion/db.php');

$codigo_proveedor = "";
if (isset($_GET['cod_prov']) && $_GET['cod_prov'] != '') {
	$codigo_proveedor = $_GET['cod_prov'];
}

$codigo_filtro = '';
if (isset($_POST['codigo_filtro']) && $_POST['codigo_filtro'] != '') {
	$codigo_filtro = $_POST['codigo_filtro'];
}


if ($codigo_proveedor != '') {
	$query_RsLista_prov = "SELECT R.REQUCORE AS REQUERIMIENTO_CODIGO,
							R.REQUCODI AS REQUERIMIENTO,
							DR.DEREPROV AS DETALLE_ID_PROVEEDOR,
							DR.DEREDESC AS DETALLE_DESC,
							DR.DERECOOC AS DETALLE_ORDEN,
							P.PROVNOMB AS PROVEEDOR_NOMBRE,
							DR.DERECOOC AS ID_ORDEN,
							F.FIRMCONS AS ID_FIRMA,
							OC.ORCOFIRM AS ORDEN_FIRMA,
							DF.DEFADETA AS FACTURA,
							DR.DERECANT AS CANTIDAD,
							A.AREANOMB AS AREA,
							A.AREAID AS AREA_ID

						FROM REQUERIMIENTOS R
						JOIN DETALLE_REQU DR ON DR.DEREREQU = R.REQUCODI
						JOIN PROVEEDORES P ON DR.DEREPROV = P.PROVCODI
						JOIN ORDEN_COMPRA OC ON DR.DERECOOC = OC.ORCOCONS
						JOIN FIRMAS F ON OC.ORCOFIRM = F.FIRMCONS
						JOIN AREA A ON R.REQUAREA = A.AREAID
						LEFT JOIN DETALLE_FACTURA DF ON DR.DERECONS = DF.DEFADETA

						WHERE P.PROVCODI = '" . $codigo_proveedor . "';

				
					";
	$RsLista_prov = mysqli_query($conexion, $query_RsLista_prov) or die(mysqli_error($conexion));
	$row_RsLista_prov = mysqli_fetch_array($RsLista_prov);
	$totalRows_RsLista_prov = mysqli_num_rows($RsLista_prov);

}
//CONSULTA DE AREAS
$query_RsArea = "SELECT A.AREAID CODIGO_AREA,
						A.AREANOMB NOMBRE
						
						FROM AREA A 
						WHERE 1
						ORDER BY A.AREANOMB ASC";
$RsArea = mysqli_query($conexion, $query_RsArea) or die(mysqli_connect_error());
$row_RsArea = mysqli_fetch_array($RsArea);
$totalRows_RsArea = mysqli_num_rows($RsArea);

//CONSULTA DE POAS
$query_filterPOA = "SELECT P.POACODI CODIGO_POA,
					   P.POANOMB NOMBRE
						
						FROM POA P 
						WHERE 1
						ORDER BY P.POACODI ASC";
$filterPOA = mysqli_query($conexion, $query_filterPOA) or die(mysqli_connect_error());
$row_filterPOA = mysqli_fetch_array($filterPOA);
$totalRows_filterPOA = mysqli_num_rows($filterPOA);


//CONSULTA DE SUBPOAS
$query_SUBPOA = "SELECT PD.PODECODI CODIGO_SUBPOA,
						PD.PODENOMB NOMBRE
						
						FROM POADETA PD
						WHERE 1
						ORDER BY PD.PODECODI ASC";
$SUBPOA = mysqli_query($conexion, $query_SUBPOA) or die(mysqli_connect_error());
$row_SUBPOA = mysqli_fetch_array($SUBPOA);
$totalRows_SUBPOA = mysqli_num_rows($SUBPOA);

//CONSULTA DE DETALLES
// if ($desc_detalle != '') {
// 	$query_RsListaDetallesRequerimientos = "SELECT DEREDESC AS DESCRIPCION_DETALLE, 
//                                                    REQUCORE AS REQUERIMIENTO_CODI
//                                             FROM   DETALLE_REQU D
//                                             JOIN   REQUERIMIENTOS R ON D.DEREREQU = R.REQUCODI
//                                             WHERE  D.DEREDESC LIKE '%$desc_detalle%'
//                                             AND    R.REQUIDUS = '$userID'";
// }

if ($codigo_proveedor != '') {
	$query_RsPOA = " SELECT PO.POANOMB AS POA_NOM,
							PD.PODENOMB AS SUBPOA_NOM
					FROM REQUERIMIENTOS R,
						DETALLE_REQU DR,
						PROVEEDORES P,
						POA PO,
						POADETA PD
					WHERE P.PROVCODI = '" . $codigo_proveedor . "'
					AND DR.DEREREQU = R.REQUCODI
					AND DR.DEREPROV = P.PROVCODI
					AND DR.DEREPOA = PO.POACODI
					AND PD.PODECODI = DR.DEREPOA
    ";
	$RsPOA = mysqli_query($conexion, $query_RsPOA) or die(mysqli_error($conexion));
	$row_RsPOA = mysqli_fetch_array($RsPOA);
	$totalRows_RsPOA = mysqli_num_rows($RsPOA);
}

$area_filtro = '';
$desc_detalle = '';
$poa_filtro = '';
$subpoa_filtro = '';

if (isset($_POST['area_filtro']) && $_POST['area_filtro'] != '') {
	$area_filtro = $_POST['area_filtro'];
}

if (isset($_POST['desc_detalle']) && $_POST['desc_detalle'] != '') {
	$desc_detalle = $_POST['desc_detalle'];
}

if (isset($_POST['poa_filtro']) && $_POST['poa_filtro'] != '') {
	$poa_filtro = $_POST['poa_filtro'];
}

if (isset($_POST['subpoa_filtro']) && $_POST['subpoa_filtro'] != '') {
	$subpoa_filtro = $_POST['subpoa_filtro'];
}



if ($area_filtro = !'') {
	$query_RsLista_prov .= " AND AREA_ID = '" . $area_filtro . "' ";
	// $RsLista_prov = mysqli_query($conexion, $query_RsLista_prov) or die(mysqli_error($conexion));
	// $row_RsLista_prov = mysqli_fetch_array($RsLista_prov);
	// $totalRows_RsLista_prov = mysqli_num_rows($RsLista_prov);
}

?>

<!DOCTYPE html>
<html>

<head>
	<title>Proveedores</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="css/estilo_solicitud.css" />

	<script src="https://unpkg.com/xlsx@0.16.9/dist/xlsx.full.min.js"></script>
	<script src="https://unpkg.com/file-saverjs@latest/FileSaver.min.js"></script>
	<script src="https://unpkg.com/tableexport@latest/dist/js/tableexport.min.js"></script>
	<!-- 
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous"> -->
</head>
<style type="text/css">
	body {
		background: #fff;

	}

	#menu {
		/*background:#26B826;*/
		/*background:#4C954B;*/
		height: 50px;
		font-size: 13px;
		margin-top: -10px;
		border-radius: 13px;
	}

	#menu_proveedores {
		/* background:#26B826; */
		padding-top: 15px;
		color: #FDF8F8;
		font-weight: bold;
	}

	#menu_proveedores li {
		display: inline;
		padding-left: 15px;
		padding-right: 15px;
		padding-top: 10px;
		padding-bottom: 10px;
		background: #4C954B;
		border-radius: 13px;
		list-style-type: none;
	}

	#menu_proveedores a {
		width: 260px;
		/*background:#ff0000;*/
		text-decoration: none;
		color: #ffffff;
	}

	#menu_proveedores a:hover {
		color: #000000;
	}

	#menu_proveedores li:hover {
		background: #99F199;
		font-size: 13px;
		border-radius: 13px;
	}
</style>
<form name="form1" id="form1" method="post" action="">

	<td class="">POA</td>
	<td>
		<select name="POA_filtro" id="POA_filtro">
			<option value="">Seleccione...</option>
			<?php
			if ($totalRows_filterPOA > 0) {
				do {
					?>
					<option value="<?php echo ($row_filterPOA['CODIGO_POA']); ?>" <?php if ($poa_filtro == $row_filterPOA['CODIGO_POA']) {
						   echo ('selected');
					   } ?>>
						<?php echo ($row_filterPOA['NOMBRE']); ?>
					</option>
					<?php
				} while ($row_filterPOA = mysqli_fetch_array($filterPOA));
			}
			?>
		</select>
		<input type="button" name="farea" id="farea" value="x" onclick="limpiarfiltros('POA_filtro');">
	</td>


	<td class="">SUB POA</td>
	<td>
		<select name="SUBPOA_filtro" id="SUBPOA_filtro">
			<option value="">Seleccione...</option>
			<?php
			if ($totalRows_SUBPOA > 0) {
				do {
					?>
					<option value="<?php echo ($row_SUBPOA['CODIGO_SUBPOA']); ?>" <?php if ($subpoa_filtro == $row_SUBPOA['CODIGO_SUBPOA']) {
						   echo ('selected');
					   } ?>>
						<?php echo ($row_SUBPOA['NOMBRE']); ?>
					</option>
					<?php
				} while ($row_SUBPOA = mysqli_fetch_array($SUBPOA));
			}
			?>
		</select>
		<input type="button" name="farea" id="farea" value="x" onclick="limpiarfiltros('SUBPOA_filtro');">
	</td>
	<td class="">Area</td>
	<td>
		<select name="area_filtro" id="area_filtro">
			<option value="">Seleccione...</option>
			<?php
			if ($totalRows_RsArea > 0) {
				do {
					?>
					<option value="<?php echo ($row_RsArea['CODIGO_AREA']); ?>" <?php if ($area_filtro == $row_RsArea['CODIGO_AREA']) {
						   echo ('selected');
					   } ?>>
						<?php echo ($row_RsArea['NOMBRE']); ?>
					</option>
					<?php
				} while ($row_RsArea = mysqli_fetch_array($RsArea));
			}
			?>
		</select>
		<input type="button" name="farea" id="farea" value="x" onclick="limpiarfiltros('area_filtro');">
	</td>
	<!-- <td align="center">
		<input type="submit" name="butonfiltro" id="butonfiltro" class="button2" value="Buscar" onclick="Busqueda();">
	</td> -->
	<td>
		<input type="submit" name="filtrar" value="Filtrar">
	</td>
</form>
<div style="margin-bottom: 10px;font-weight: bold;font-size: 15px">
	PROVEEDOR: <?php echo ($row_RsLista_prov['PROVEEDOR_NOMBRE']); ?>
</div>
<table class="tablalistado" cellspacing="2" border="0" id="tabla">
	<tr class="SLAB trtitle">
		<td>REQUERIMIENTO</td>
		<td>DESCRIPCION DETALLE</td>
		<td>CANTIDAD</td>
		<td width="150">POA</td>
		<td width="150">SUB POA</td>
		<td width="150">AREA</td>
		<td>ORDEN</td>
		<td>FACTURA</td>

	</tr>
	<?php
	if (isset($RsLista_prov) && $totalRows_RsLista_prov > 0) {
		while ($row_RsLista_prov = mysqli_fetch_assoc($RsLista_prov)) {
			$j = 0;
			do {
				$j++;
				$estilo = "SB";
				if ($j % 2 == 0) {
					$estilo = "SB2";
				}
				?>
				<tr class="<?php echo ($estilo); ?>">
					<td> <a href="./home.php?page=solicitud&codreq=<?php echo ($row_RsLista_prov['REQUERIMIENTO']); ?>"
							target="_back"><?php echo ($row_RsLista_prov['REQUERIMIENTO_CODIGO']); ?></a></td>
					<td class='text-justify'><?php echo ($row_RsLista_prov['DETALLE_DESC']); ?></td>
					<td class='text-justify'><?php echo ($row_RsLista_prov['CANTIDAD']); ?></td>
					<td class='text-justify'><?php echo ($row_RsPOA['POA_NOM']); ?></td>
					<td class='text-justify'><?php echo ($row_RsPOA['SUBPOA_NOM']); ?></td>
					<td class='text-justify'><?php echo ($row_RsLista_prov['AREA']); ?></td>
					<td>
						<a target="_blank"
							href="O.php?codprov=<?php echo ($row_RsLista_prov['DETALLE_ID_PROVEEDOR']); ?>&codcomp=<?php echo ($row_RsLista_prov['ID_ORDEN']); ?>&%=2&f=<?php echo ($row_RsLista_prov['ORDEN_FIRMA']); ?>"><?php echo ($row_RsLista_prov['DETALLE_ORDEN']); ?></a>
					</td>
					<td><?php if (($row_RsLista_prov['FACTURA'])) {
						echo ($row_RsLista_prov['FACTURA']);
					} else { ?> <input type="text" id="factura"> <?php }
					; ?> </td>
				</tr>
				<?php
			} while ($row_RsLista_prov = mysqli_fetch_array($RsLista_prov));
		}
	} else {
		?>
		<tr>
			<td colspan="X">No se encontraron resultados.</td> <!-- Reemplaza 'X' con el número de columnas -->
		</tr>
		<?php
	}
	?>
</table>
<button id="btnExportar" style="padding: 6px;margin-top:10px">
	Exportar a Excel
</button>

</html>
<script>
	const $btnExportar = document.querySelector("#btnExportar"),
		$tabla = document.querySelector("#tabla");

	$btnExportar.addEventListener("click", function () {
		let tableExport = new TableExport($tabla, {
			exportButtons: false, // No queremos botones
			filename: "Proveedor", //Nombre del archivo de Excel
			sheetname: "Proveedor", //Título de la hoja
		});
		let datos = tableExport.getExportData();
		let preferenciasDocumento = datos.tabla.xlsx;
		tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);
	});

	function limpiarfiltros(campo) {
		document.getElementById('' + campo).value = "";
	}

	function Busqueda() {
		
	}
</script>