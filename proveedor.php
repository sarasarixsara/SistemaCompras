<?php
require_once ('conexion/db.php');

$codigo_proveedor = "";
if (isset($_GET['cod_prov']) && $_GET['cod_prov'] != '') {
	$codigo_proveedor = $_GET['cod_prov'];
}



if ($codigo_proveedor != '') {
	$query_RsLista_prov = "SELECT R.REQUCORE REQUERIMIENTO_CODIGO,
							   R.REQUCODI REQUERIMIENTO,
							   DR.DEREPROV DETALLE_ID_PROVEEDOR,
							   DR.DEREDESC DETALLE_DESC,
							   DR.DERECOOC DETALLE_ORDEN,
							   P.PROVNOMB PROVEEDOR_NOMBRE,
							   DR.DERECOOC ID_ORDEN
							   

						FROM REQUERIMIENTOS R ,
							 DETALLE_REQU  DR ,
							 PROVEEDORES P,
							 ORDEN_COMPRA OC

							
						WHERE P.PROVCODI='" . $codigo_proveedor . "'
						AND DR.DEREREQU=R.REQUCODI
						AND DR.DEREPROV=P.PROVCODI  
						AND DR.DERECOOC=OC.ORCOCONS 
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


?>

<!DOCTYPE html>
<html>
<title>Proveedores</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="css/estilo_solicitud.css" />


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
<div style="margin-bottom: 10px;font-weight: bold;font-size: 15px">
	PROVEEDOR: <?php echo ($row_RsLista_prov['PROVEEDOR_NOMBRE']); ?>
</div>
<table class="tablalistado" cellspacing="2" border="0">
	<tr class="SLAB trtitle">
		<td>REQUERIMIENTO</td>
		<td>DESCRIPCION DETALLE</td>
		<td width="150">POA</td>
		<td width="150">SUB POA</td>
		<td>ORDEN</td>
		<td>ID PROV</td>
		<td>ID ORDEN</td>
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
				<td> <a href="/home.php?page=solicitud&codreq=<?php echo ($row_RsLista_prov['REQUERIMIENTO']); ?>"
						target="_back"><?php echo ($row_RsLista_prov['REQUERIMIENTO_CODIGO']); ?></a></td>
				<td class='text-justify'><?php echo ($row_RsLista_prov['DETALLE_DESC']); ?></td>
				<td class='text-justify'><?php echo ($row_RsPOA['POA_NOM']); ?></td>
				<td class='text-justify'><?php echo ($row_RsPOA['SUBPOA_NOM']); ?></td>
				<td>
					<a target="_blank" href="O.php?codprov=<?php echo($row_RsLista_prov['DETALLE_ID_PROVEEDOR']);?>&codcomp=<?php echo($row_RsLista_prov['ID_ORDEN']);?>&%=2&f=<?php echo($row_RsListaRequerimientos['FIRMA']);?>"><?php echo ($row_RsLista_prov['DETALLE_ORDEN']); ?></a></td>
				<td class='text-justify'><?php echo ($row_RsLista_prov['DETALLE_ID_PROVEEDOR']); ?></td>
				<td class='text-justify'><?php echo ($row_RsLista_prov['ID_ORDEN']); ?></td>
			</tr>
			<?php
		} while ($row_RsLista_prov = mysqli_fetch_array($RsLista_prov));
	}
	?>
</table>
<button type="button" onclick="tableToCSV()">
	download CSV
</button>

</html>
<script>
	function tableToCSV() {

		// Variable to store the final csv data
		let csv_data = [];

		// Get each row data
		let rows = document.getElementsByTagName('tr');
		for (let i = 0; i < rows.length; i++) {

			// Get each column data
			let cols = rows[i].querySelectorAll('td,th');

			// Stores each csv row data
			let csvrow = [];
			for (let j = 0; j < cols.length; j++) {

				// Get the text data of each cell
				// of a row and push it to csvrow
				csvrow.push(cols[j].innerHTML);
			}

			// Combine each column value with comma
			csv_data.push(csvrow.join(","));
		}

		// Combine each row data with new line character
		csv_data = csv_data.join('\n');

		// Call this function to download csv file  
		downloadCSVFile(csv_data);

	}
	function downloadCSVFile(csv_data) {

		// Create CSV file object and feed
		// our csv_data into it
		CSVFile = new Blob([csv_data], {
			type: "text/csv"
		});

		// Create to temporary link to initiate
		// download process
		let temp_link = document.createElement('a');

		// Download csv file
		temp_link.download = "GfG.csv";
		let url = window.URL.createObjectURL(CSVFile);
		temp_link.href = url;

		// This link should not be displayed
		temp_link.style.display = "none";
		document.body.appendChild(temp_link);

		// Automatically click the link to
		// trigger download
		temp_link.click();
		document.body.removeChild(temp_link);
	}
</script>