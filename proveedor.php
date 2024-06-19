<?php
require_once('conexion/db.php');

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


$codigo_proveedor = "";
if (isset($_GET['cod_prov']) && $_GET['cod_prov'] != '') {
    $codigo_proveedor = $_GET['cod_prov'];
}

$codigo_filtro = '';
if (isset($_POST['codigo_filtro']) && $_POST['codigo_filtro'] != '') {
    $codigo_filtro = $_POST['codigo_filtro'];
}

$area_filtro = '';
$poa_filtro = '';
$subpoa_filtro = '';

if (isset($_POST['area_filtro']) && $_POST['area_filtro'] != '') {
    $area_filtro = $_POST['area_filtro'];
}

if (isset($_POST['poa_filtro']) && $_POST['poa_filtro'] != '') {
    $poa_filtro = $_POST['poa_filtro'];
}

if (isset($_POST['subpoa_filtro']) && $_POST['subpoa_filtro'] != '') {
    $subpoa_filtro = $_POST['subpoa_filtro'];
}

$query_RsLista_prov = "SELECT R.REQUCORE AS REQUERIMIENTO_CODIGO,
						R.REQUCODI AS REQUERIMIENTO,
						DR.DEREPROV AS DETALLE_ID_PROVEEDOR,
						DR.DEREDESC AS DETALLE_DESC,
						DR.DERECOOC AS DETALLE_ORDEN,
						P.PROVNOMB AS PROVEEDOR_NOMBRE,
						DR.DERECOOC AS ID_ORDEN,
						F.FIRMCONS AS ID_FIRMA,
						OC.ORCOFIRM AS ORDEN_FIRMA,
						DR.DERECONS AS DETALLE_REQU_ID,
						DF.DEFADESC AS URL_FACTURA,
						DF.DEFANUM AS NUM_FACTURA,
						DF.DEFAID AS ID_FACTURA,
						DR.DERECANT AS CANTIDAD,
						A.AREANOMB AS AREA,
						A.AREAID AS AREA_ID,
						POA.POANOMB AS POA,
						PD.PODENOMB AS SUBPOA
					FROM REQUERIMIENTOS R
					JOIN DETALLE_REQU DR ON DR.DEREREQU = R.REQUCODI
					JOIN PROVEEDORES P ON DR.DEREPROV = P.PROVCODI
					JOIN ORDEN_COMPRA OC ON DR.DERECOOC = OC.ORCOCONS
					JOIN FIRMAS F ON OC.ORCOFIRM = F.FIRMCONS
					JOIN AREA A ON R.REQUAREA = A.AREAID
					JOIN POA POA ON DR.DEREPOA = POA.POACODI
					JOIN POADETA PD ON DR.DERESUPO = PD.PODECODI
					LEFT JOIN DETALLE_FACTURA DF ON DR.DERECONS = DF.DEFADETA
					WHERE P.PROVCODI = '" . $codigo_proveedor . "'";

if ($codigo_filtro != '' || $area_filtro != '' || $poa_filtro != '' || $subpoa_filtro != '') {
    if ($area_filtro != '') {
        $query_RsLista_prov .= " AND A.AREAID = '" . $area_filtro . "'";
    }
    if ($codigo_filtro != '') {
        $query_RsLista_prov .= " AND R.REQUCODI = '" . $codigo_filtro . "'";
    }
    if ($poa_filtro != '') {
        $query_RsLista_prov .= " AND POA.POACODI = '" . $poa_filtro . "'";
    }
    if ($subpoa_filtro != '') {
        $query_RsLista_prov .= " AND PD.PODECODI = '" . $subpoa_filtro . "'";
    }
}


$RsLista_prov = mysqli_query($conexion, $query_RsLista_prov) or die(mysqli_error($conexion));
$row_RsLista_prov = mysqli_fetch_array($RsLista_prov);
$totalRows_RsLista_prov = mysqli_num_rows($RsLista_prov);

//CONSULTA DE DETALLES
// if ($desc_detalle != '') {
// 	$query_RsListaDetallesRequerimientos = "SELECT DEREDESC AS DESCRIPCION_DETALLE, 
//                                                    REQUCORE AS REQUERIMIENTO_CODI
//                                             FROM   DETALLE_REQU D
//                                             JOIN   REQUERIMIENTOS R ON D.DEREREQU = R.REQUCODI
//                                             WHERE  D.DEREDESC LIKE '%$desc_detalle%'
//                                             AND    R.REQUIDUS = '$userID'";
// }


?>

<!DOCTYPE html>
<html>

<head>
    <title>Proveedores</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/estilo_solicitud.css"/>

    <script src="https://unpkg.com/xlsx@0.16.9/dist/xlsx.full.min.js"></script>
    <script src="https://unpkg.com/file-saverjs@latest/FileSaver.min.js"></script>
    <script src="https://unpkg.com/tableexport@latest/dist/js/tableexport.min.js"></script>

</head>
<style type="text/css">
    body {
        background: #fff;

    }

    #menu {

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
        <select name="poa_filtro" id="POA_filtro">
            <option value="">Seleccione...</option>
            <?php
            if ($totalRows_filterPOA > 0) {
                do {
                    ?>
                    <option value="<?php echo($row_filterPOA['CODIGO_POA']); ?>" <?php if ($poa_filtro == $row_filterPOA['CODIGO_POA']) {
                        echo('selected');

                    } ?>>
                        <?php echo($row_filterPOA['NOMBRE']); ?>
                    </option>
                    <?php
                } while ($row_filterPOA = mysqli_fetch_array($filterPOA));
            }
            ?>
        </select>
        <input type="button" name="farea" id="farea" value="x" onclick="limpiarfiltros('POA_filtro');">
    </td>
    <td class=""><label for="subpoa_filtro">SUB POA</label></td>
    <td>
        <select name="subpoa_filtro" id="subpoa_filtro">
            <option value="">Seleccione...</option>
            <?php
            if ($totalRows_SUBPOA > 0) {
                do {
                    ?>
                    <option value="<?php echo($row_SUBPOA['CODIGO_SUBPOA']); ?>" <?php if ($subpoa_filtro == $row_SUBPOA['CODIGO_SUBPOA']) {
                        echo('selected');
                    } ?>>
                        <?php echo($row_SUBPOA['NOMBRE']); ?>
                    </option>
                    <?php
                } while ($row_SUBPOA = mysqli_fetch_array($SUBPOA));
            }
            ?>
        </select>
        <input type="button" name="farea" id="farea" value="x" onclick="limpiarfiltros('subpoa_filtro');">
    </td>
    <td class="">Area</td>
    <td>
        <select name="area_filtro" id="area_filtro">
            <option value="">Seleccione...</option>
            <?php
            if ($totalRows_RsArea > 0) {
                do {
                    ?>
                    <option value="<?php echo($row_RsArea['CODIGO_AREA']); ?>" <?php if ($area_filtro == $row_RsArea['CODIGO_AREA']) {
                        echo('selected');
                    } ?>>
                        <?php echo($row_RsArea['NOMBRE']); ?>
                    </option>
                    <?php
                } while ($row_RsArea = mysqli_fetch_array($RsArea));
            }
            ?>
        </select>
        <input type="button" name="farea" id="farea" value="x" onclick="limpiarfiltros('area_filtro');">
    </td>

    <td>
        <input type="submit" name="filtrar" value="Filtrar">
    </td>

</form>
<?php
if ($totalRows_RsLista_prov > 0) {
?>
<div style="margin-bottom: 10px;font-weight: bold;font-size: 15px">
    PROVEEDOR: <?php echo($row_RsLista_prov['PROVEEDOR_NOMBRE']); ?>
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
        <td width="50">NO. FACTURA</td>
        <td width="50">URL FACTURA</td>
    </tr>
    <?php
    $j = 0;
    do {


        $idTablaRequerimiento = $row_RsLista_prov['DETALLE_REQU_ID'];
        $idFactura = $row_RsLista_prov['ID_FACTURA'] ?? 0;
        $numFactura = $row_RsLista_prov['NUM_FACTURA'] ?? 0;
        $urlFactura = $row_RsLista_prov['URL_FACTURA'] ?? '';


        $j++;
        $estilo = "SB";
        if ($j % 2 == 0) {
            $estilo = "SB2";
        }
        ?>
        <tr class="<?php echo($estilo); ?>">
            <td><a href="./home.php?page=solicitud&codreq=<?php echo($row_RsLista_prov['REQUERIMIENTO']); ?>"
                   target="_back"><?php echo($row_RsLista_prov['REQUERIMIENTO_CODIGO']); ?></a></td>
            <td class='text-justify'><?php echo($row_RsLista_prov['DETALLE_DESC']); ?></td>
            <td class='text-justify'><?php echo($row_RsLista_prov['CANTIDAD']); ?></td>
            <td class='text-justify'><?php echo($row_RsLista_prov['POA']); ?></td>
            <td class='text-justify'><?php echo($row_RsLista_prov['SUBPOA']); ?></td>
            <td class='text-justify'><?php echo($row_RsLista_prov['AREA']); ?></td>
            <td>
                <a target="_blank"
                   href="O.php?codprov=<?php echo($row_RsLista_prov['DETALLE_ID_PROVEEDOR']); ?>&codcomp=<?php echo($row_RsLista_prov['ID_ORDEN']); ?>&%=2&f=<?php echo($row_RsLista_prov['ORDEN_FIRMA']); ?>"><?php echo($row_RsLista_prov['DETALLE_ORDEN']); ?></a>
            </td>
            <td>

                <input type="text"
                       data-id-requerimiento="<?php echo $idTablaRequerimiento ?>"
                       data-id-factura="<?php echo $idFactura ?>"
                       value="<?php echo $numFactura ?>"
                       onkeyup="actualizarFactura(event, 'numeroFactura')"/>
            </td>
            <td>
                <input type="text"
                       data-id-requerimiento="<?php echo $idTablaRequerimiento ?>"
                       data-id-factura="<?php echo $idFactura ?>"
                       value="<?php echo $urlFactura ?>"
                       onkeyup="actualizarFactura(event, 'urlFactura')"/>
            </td>

        </tr>
        <?php
    } while ($row_RsLista_prov = mysqli_fetch_array($RsLista_prov));
    } else {
        ?>
        <tr>
            <td colspan=" 6">No se encontraron resultados.
            </td>
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

    async function actualizarFactura(event, type) {
        const key = event.key
        console.log(event.target['data-id-factura'])
        if (key === 'Enter') {

            const value = event.target.value
            const idFactura = event.target.getAttribute('data-id-factura')
            const idRequerimiento = event.target.getAttribute('data-id-requerimiento')
            const formData = new FormData();
            formData.append(type, value);
            formData.append('idFactura', idFactura);
            formData.append('idRequerimiento', idRequerimiento);
            try{
                const response = await fetch('actualizar_factura.php', {
                    method: 'POST',
                    body: formData
                })
            alert('El registro ha sido actualizado exitosamente.')
            } catch (e) {
                alert(e.message)
            }

        }

    }

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


</script>