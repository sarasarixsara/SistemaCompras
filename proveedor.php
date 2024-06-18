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
$area_filtro = '';
if (isset($_POST['area_filtro']) && $_POST['area_filtro'] != '') {
	$area_filtro = $_POST['area_filtro'];
}
$desc_detalle = '';
if (isset($_POST['desc_detalle']) && $_POST['desc_detalle'] != '') {
	$desc_detalle = $_POST['desc_detalle'];
}
$poa_filtro = '';
if (isset($_POST['poa_filtro']) && $_POST['poa_filtro'] != '') {
	$poa_filtro = $_POST['poa_filtro'];
}
$subpoa_filtro = '';
if (isset($_POST['subpoa_filtro']) && $_POST['subpoa_filtro'] != '') {
	$subpoa_filtro = $_POST['subpoa_filtro'];
}
$area_filtro = '';
if (isset($_POST['area_filtro']) && $_POST['area_filtro'] != '') {
	$area_filtro = $_POST['area_filtro'];
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
							A.AREANOMB AS AREA

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

if ($desc_detalle != '') {
	$query_RsLista_prov = $query_RsLista_prov . " AND DR.DEREDESC LIKE '%" . $desc_detalle . "%' ";
}
if ($desc_detalle != '') {
	$RsListaDetallesRequerimientos = mysqli_query($conexion, $query_RsListaDetallesRequerimientos) or die(mysqli_connect_error());
	$row_RsListaDetallesRequerimientos = mysqli_fetch_array($RsListaDetallesRequerimientos);
	$totalRows_RsListaDetallesRequerimientos = mysqli_num_rows($RsListaDetallesRequerimientos);
} else {
	$totalRows_RsListaDetallesRequerimientos = 0;
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

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
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
<table>
	<tr>
		<td>Detalle</td>
		<td><input type="text" name="desc_detalle" id="desc_detalle" size="10" </td>
	</tr>
	<td align="center">
		<input type="submit" name="butonfiltro" id="butonfiltro" class="button2" value="Buscar" onclick="Busqueda();">
	</td>
</table>
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
	if ($totalRows_RsLista_prov > 0) {
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
				} else { ?> <input
							type="text" id="factura"> <?php }
				; ?> </td>
			</tr>
			<?php
		} while ($row_RsLista_prov = mysqli_fetch_array($RsLista_prov));
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
	// function tableToCSV() {

	// 	// Variable to store the final csv data
	// 	let csv_data = [];

	// 	// Get each row data
	// 	let rows = document.getElementsByTagName('tr');
	// 	for (let i = 0; i < rows.length; i++) {

	// 		// Get each column data
	// 		let cols = rows[i].querySelectorAll('td,th');

	// 		// Stores each csv row data
	// 		let csvrow = [];
	// 		for (let j = 0; j < cols.length; j++) {

	// 			// Check if the cell contains an <a> element
	// 			let aElement = cols[j].querySelector('a');
	// 			if (aElement) {
	// 				// Get the text content of the <a> element
	// 				csvrow.push(aElement.textContent.trim());
	// 			} else {
	// 				// Get the text content of the cell
	// 				csvrow.push(cols[j].textContent.trim());
	// 			}
	// 		}

	// 		// Combine each column value with comma
	// 		csv_data.push(csvrow.join(","));
	// 	}

	// 	// Combine each row data with new line character
	// 	csv_data = csv_data.join('\n');

	// 	// Call this function to download csv file  
	// 	downloadCSVFile(csv_data);
	// }

	// function downloadCSVFile(csv_data) {
	// 	// Create a Blob object with the CSV data
	// 	let csvBlob = new Blob([csv_data], { type: 'text/csv' });

	// 	// Create a link element
	// 	let downloadLink = document.createElement('a');

	// 	// Set the download attribute with a filename
	// 	downloadLink.download = 'proveedor.csv';

	// 	// Create a URL for the Blob and set it as the href attribute
	// 	downloadLink.href = window.URL.createObjectURL(csvBlob);

	// 	// Append the link to the document body and trigger a click to start the download
	// 	document.body.appendChild(downloadLink);
	// 	downloadLink.click();

	// 	// Remove the link from the document
	// 	document.body.removeChild(downloadLink);
	// }
	function Busqueda() {
		document.form1.action = "home.php?page=requerimientos_lista";
	}
</script>