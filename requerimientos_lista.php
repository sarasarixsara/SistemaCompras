<?php
//CONEXION A BASE DE DATOS
require_once ('conexion/db.php');

//CONTROL DE VARIABLES DE SESSIONES 
if (!isset($_SESSION)) {
	session_start();
}
if (!isset($_SESSION['MM_AccesoCorrectoApp']) || $_SESSION['MM_AccesoCorrectoApp'] != 'ACTIVO') {
	header("location: index.php");
}

//DEFINICION DE VARIABLES

$currentPage = $_SERVER["PHP_SELF"];

//variable de la cedula de la directora administrativa
$dir_administra = '39550544';

$estado_filtro = '';
if (isset($_POST['estado_filtro']) && $_POST['estado_filtro'] != '') {
	$estado_filtro = $_POST['estado_filtro'];
}
$codigo_filtro = '';
if (isset($_POST['codigo_filtro']) && $_POST['codigo_filtro'] != '') {
	$codigo_filtro = $_POST['codigo_filtro'];
}
$area_filtro = '';
if (isset($_POST['area_filtro']) && $_POST['area_filtro'] != '') {
	$area_filtro = $_POST['area_filtro'];
}
$fdetallesfirmar = '';
if (isset($_POST['fdetallesfirmar']) && $_POST['fdetallesfirmar'] != '') {
	$fdetallesfirmar = $_POST['fdetallesfirmar'];
}
$todos = '';
if (isset($_GET['todos']) && $_GET['todos'] != '') {
	$todos = $_GET['todos'];
}
$desc_detalle = '';
if (isset($_POST['desc_detalle']) && $_POST['desc_detalle'] != '') {
	$desc_detalle = $_POST['desc_detalle'];
}

function printDescripcionDetalle($texto) {
    // Separar los registros usando "||"
    $registros = explode('||', $texto);

    // Inicializar la variable de salida HTML
    $html = '';

    // Recorrer cada registro
    foreach ($registros as $registro) {
        // Eliminar espacios en blanco al principio y final de cada registro
        $registro = trim($registro);

        // Separar cada parte del registro usando "$$"
        $partes = explode('$$', $registro);

        // Asegurarse de que haya al menos 3 partes para procesar
        if (count($partes) >= 3) {
            $descripcion = trim($partes[0]);
            $detalle = trim($partes[1]);
            $color = trim($partes[2]);

            // Generar el HTML para este registro
            $html .= '<div class="col-md-12">';
            $html .= $descripcion . ' - <span style="font-size:9px; background:' . $color . '">' . $detalle . '</span>';
            $html .= '</div>';
        }
    }

    // Devolver el HTML generado
    return $html;
}

// Ejemplo de uso





//DEFINIR VARIABLE DE PAGINADOR
$tamanoPagina = 15;
$maxRows_RsListaRequerimientos = $tamanoPagina;
$pageNum_RsListaRequerimientos = 0;

if (isset($_GET['pageNum_RsListaRequerimientos'])) {
	$pageNum_RsListaRequerimientos = $_GET['pageNum_RsListaRequerimientos'];
}
//fila de inicio para la consulta de la base de datos
$startRow_RsListaRequerimientos = $pageNum_RsListaRequerimientos * $maxRows_RsListaRequerimientos;
// Definir rol usuario y area
$rolID = $_SESSION['MM_RolID'];
$userID = $_SESSION['MM_UserID'];
$userArea = $_SESSION['MM_Area'];

//CONSULTA DE ESTADOS    
$query_RsEstados = "SELECT E.ESTACODI CODIGO,
	                         E.ESTANOMB NOMBRE
					   FROM ESTADOS E";
$RsEstados = mysqli_query($conexion, $query_RsEstados) or die(mysqli_connect_error());
$row_RsEstados = mysqli_fetch_array($RsEstados);
$totalRows_RsEstados = mysqli_num_rows($RsEstados);

//CONSULTA DE AREAS
$query_RsArea = "SELECT A.AREAID 		CODIGO_AREA,
							  A.AREANOMB 	NOMBRE,
							  A.AREAESTA 
						FROM  area A 
						WHERE 1
						ORDER BY A.AREANOMB ASC";
$RsArea = mysqli_query($conexion, $query_RsArea) or die(mysqli_connect_error());
$row_RsArea = mysqli_fetch_array($RsArea);
$totalRows_RsArea = mysqli_num_rows($RsArea);

if ($desc_detalle != '') {
	$query_RsListaDetallesRequerimientos = "SELECT DEREDESC AS DESCRIPCION_DETALLE, 
                                                   REQUCORE AS REQUERIMIENTO_CODI
                                            FROM   DETALLE_REQU D
                                            JOIN   REQUERIMIENTOS R ON D.DEREREQU = R.REQUCODI
                                            WHERE  D.DEREDESC LIKE '%$desc_detalle%'
                                            AND    R.REQUIDUS = '$userID'";
}

$query_RsListaRequerimientos = "SELECT DISTINCT
    R.REQUCODI CODIGO,
    R.REQUCORE CODIGO_REQUERIMIENTO,
    R.REQUIDUS USUARIO,
    R.REQUAREA AREA,
    DATE_FORMAT(R.REQUFEEN, '%d/%m/%Y') FECHA,
    DATE_FORMAT(R.REQUFEFI, '%d/%m/%Y') FECHA_FINALIZADO,
    R.REQUESTA ESTADO,
    E.ESTANOMB ESTADO_DES,
    E.ESTACOLO COLOR,
    (SELECT SUBSTRING(REQUCORE, 3, 4)) A,
    (SELECT SUBSTRING(REQUCORE, 8, 4) * 2) B,
    (SELECT A.AREANOMB 
     FROM AREA A
     WHERE A.AREAID = R.REQUAREA LIMIT 1) AREA_DES,
    R.REQUENCU ENCUESTA,
    (SELECT ENPEESTA FROM encuesta_pers WHERE ENPEREQU = R.REQUCODI LIMIT 1) RESPUESTA_ENC,
    (SELECT GROUP_CONCAT(CONCAT(D.DEREDESC, ' $$ ', ED.ESDENOMB, ' $$ ', ED.ESDECOLO) SEPARATOR ' || ')
     FROM DETALLE_REQU D
     LEFT JOIN ESTADO_DETALLE ED ON D.DEREAPRO = ED.ESDECODI
     WHERE R.REQUCODI = D.DEREREQU) AS DESCRIPCION_DETALLE
FROM REQUERIMIENTOS R
LEFT JOIN ESTADOS E ON R.REQUESTA = E.ESTACODI
WHERE 1 
 ";


if ($todos != 1) {
	//CONDICIONES DE FILTRO DE USUARIO GENERAL
	if ($desc_detalle != '') {
		$query_RsListaRequerimientos = $query_RsListaRequerimientos . " AND D.DEREDESC LIKE '%" . $desc_detalle . "%' ";
	}
	if ($rolID == 4) {
		//en caso de la entrada muestra todos los requerimientos que pertenecen al usuario donde  codigo y estado estan vacios
		$query_RsListaRequerimientos = $query_RsListaRequerimientos . " and R.REQUIDUS = '" . $userID . "'";


		if ($codigo_filtro != '') {
			$query_RsListaRequerimientos = $query_RsListaRequerimientos . " AND R.REQUCORE = '" . $codigo_filtro . "' ";
		}

		if ($estado_filtro != '') {
			$query_RsListaRequerimientos = $query_RsListaRequerimientos . " AND R.REQUESTA = '" . $estado_filtro . "' ";
		}

	}

	// Condiciones específicas según el rol del usuario
	if (in_array($rolID, [2, 3, 5])) {
		if ($codigo_filtro == '' && $estado_filtro == '' && $area_filtro == '') {
			if ($rolID == 2) {
				$query_RsListaRequerimientos .= " AND R.REQUESTA = 2";
				$nuevoorderDA = 0;
			} elseif ($rolID == 3) {
				$query_RsListaRequerimientos .= " AND R.REQUESTA IN (11)";
				$nuevoorderDA = 1;
			} elseif ($rolID == 5 && $fdetallesfirmar == '') {
				$nuevoorderRE = 1;
				$query_RsListaRequerimientos .= " AND R.REQUCODI IN (
                SELECT DISTINCT D2.DEREREQU FROM DETALLE_REQU D2 WHERE R.REQUCODI = D2.DEREREQU and D2.DEREAPRO IN (17, 25)
            )";
			}
		}

		if ($rolID == 5 && $fdetallesfirmar == '1') {
			$query_RsListaRequerimientos .= " AND R.REQUCODI IN (
            SELECT DISTINCT D2.DEREREQU FROM DETALLE_REQU D2 WHERE R.REQUCODI = D2.DEREREQU and D2.DEREAPRO IN (17, 25)
        )";
		}
	}

	// Condiciones basadas en los filtros recibidos
	if ($codigo_filtro != '') {
		$query_RsListaRequerimientos .= " AND REQUCORE = '$codigo_filtro'";
	}

	if ($estado_filtro != '') {
		if ($estado_filtro == 1 && $rolID == 5) {
			$query_RsListaRequerimientos .= " AND R.REQUIDUS = '$userID' AND R.REQUESTA = '$estado_filtro'";
		} elseif ($estado_filtro == 1) {
			$query_RsListaRequerimientos .= " AND R.REQUIDUS = '$dir_administra' AND R.REQUESTA = '$estado_filtro'";
		} else {
			$query_RsListaRequerimientos .= " AND R.REQUESTA = '$estado_filtro'";
		}
	}

	if ($area_filtro != '') {
		$query_RsListaRequerimientos .= " AND REQUAREA = '$area_filtro' AND REQUCORE <> ''";
	}

	// En caso de que todos los filtros estén llenos
	if ($codigo_filtro != '' && $estado_filtro != '' && $area_filtro != '') {
		if ($estado_filtro == 1) {
			$query_RsListaRequerimientos .= " AND R.REQUIDUS = '$dir_administra' AND R.REQUESTA = '$estado_filtro'";
		} else {
			$query_RsListaRequerimientos .= " AND R.REQUESTA = '$estado_filtro' AND REQUCORE = '$codigo_filtro' AND REQUAREA = '$area_filtro'";
		}
	}

	if ($estado_filtro == 1) {
		$query_RsListaRequerimientos .= " AND R.REQUIDUS = '$dir_administra' AND R.REQUESTA = '$estado_filtro'";
	} else {
		if ($estado_filtro != '') {
			$query_RsListaRequerimientos .= " AND R.REQUESTA = '$estado_filtro'";
		}
		if ($codigo_filtro != '') {
			$query_RsListaRequerimientos .= " AND REQUCORE = '$codigo_filtro'";
		}
		if ($area_filtro != '') {
			$query_RsListaRequerimientos .= " AND REQUAREA = '$area_filtro'";
		}
	}

	// Condiciones específicas para rolID 6
	if ($rolID == 6) {
		$query_RsListaRequerimientos .= " AND R.REQUAREA = '$userArea'";
		if ($codigo_filtro != '') {
			$query_RsListaRequerimientos .= " AND REQUCORE = '$codigo_filtro'";
		}
		if ($estado_filtro != '') {
			$query_RsListaRequerimientos .= " AND REQUESTA = '$estado_filtro'";
		}
	}

	// Ordenar lista según el rol
	switch ($rolID) {
		case 4:
			$query_RsListaRequerimientos .= " ORDER BY R.REQUFESO DESC";
			break;
		case 2:
		case 3:
			$query_RsListaRequerimientos .= " ORDER BY R.REQUFESO DESC";
			// $orderClause = $nuevoorderDA == '1' ? " ORDER BY A, B ASC" : " ORDER BY A, B ASC";
			// $query_RsListaRequerimientos .= $orderClause;
			break;
		case 5:
			// $orderClause = $nuevoorderRE == '0' ? " ORDER BY A, B ASC" : " ORDER BY R.REQUESTA ASC";
			// $query_RsListaRequerimientos .= $orderClause;
			$query_RsListaRequerimientos .= " ORDER BY R.REQUFESO DESC";
			break;
	}
} else {
	$query_RsListaRequerimientos = $query_RsListaRequerimientos . " AND R.REQUCORE IS NOT NULL ORDER BY A , B ASC";
}
//EJECUCION DE LA CONSULTA DE REQUERIMIENTOS
//echo($query_RsListaRequerimientos);
$query_limit_RsListaRequerimientos = sprintf("%s LIMIT %d, %d", $query_RsListaRequerimientos, $startRow_RsListaRequerimientos, $maxRows_RsListaRequerimientos);
$RsListaRequerimientos = mysqli_query($conexion, $query_limit_RsListaRequerimientos) or die(mysqli_connect_error());
$row_RsListaRequerimientos = mysqli_fetch_array($RsListaRequerimientos);

if (isset($_GET['totalRows_RsListaRequerimientos'])) {
	$totalRows_RsListaRequerimientos = $_GET['totalRows_RsListaRequerimientos'];
} else {
	$all_RsListaRequerimientos = mysqli_query($conexion, $query_RsListaRequerimientos);
	$totalRows_RsListaRequerimientos = mysqli_num_rows($all_RsListaRequerimientos);
}

//EJECUCION DE LA CONSULTA DE DETALLES DE REQUERIMIENTO	
if ($desc_detalle != '') {
	$RsListaDetallesRequerimientos = mysqli_query($conexion, $query_RsListaDetallesRequerimientos) or die(mysqli_connect_error());
	$row_RsListaDetallesRequerimientos = mysqli_fetch_array($RsListaDetallesRequerimientos);
	$totalRows_RsListaDetallesRequerimientos = mysqli_num_rows($RsListaDetallesRequerimientos);
} else {
	$totalRows_RsListaDetallesRequerimientos = 0;
}
//EJECUCION DE LA PAGINACION
$totalPages_RsListaRequerimientos = ceil($totalRows_RsListaRequerimientos / $maxRows_RsListaRequerimientos) - 1;

$queryString_RsListaRequerimientos = "";
if (!empty($_SERVER['QUERY_STRING'])) {
	$params = explode("&", $_SERVER['QUERY_STRING']);
	$newParams = array();
	foreach ($params as $param) {
		if (
			stristr($param, "pageNum_RsListaRequerimientos") == false &&
			stristr($param, "totalRows_RsListaRequerimientos") == false
		) {
			array_push($newParams, $param);
		}
	}
	if (count($newParams) != 0) {
		$queryString_RsListaRequerimientos = "&" . htmlentities(implode("&", $newParams));
	}
}

$queryString_RsListaRequerimientos = sprintf("&totalRows_RsListaRequerimientos=%d%s", $totalRows_RsListaRequerimientos, $queryString_RsListaRequerimientos);

$paginaHasta = 0;
if ($pageNum_RsListaRequerimientos == $totalPages_RsListaRequerimientos) {
	$paginaHasta = $totalRows_RsListaRequerimientos;
} else {
	$paginaHasta = ($pageNum_RsListaRequerimientos + 1) * $maxRows_RsListaRequerimientos;
}

?>
<style type="text/css">
	.contenttable {
		width: 100%;
		padding-right: 35px;
		padding-left: 35px;
		overflow: hidden;
		min-height: 150px;
		border-radius: 12px;
		display: flex;
		flex-direction: column;
		justify-content: center;
	}
	.tdcontent
	{
		text-align: center;

	}
</style>

<div id="pagina">
	<form name="form1" id="form1" method="post" action="">
		<div class="contenttable">
			<div id="divfiltros" style=" border:solid 1px #ccc; width:100%; margin-bottom:10px;border-radius: 20px;">
				<table width="100%" >
					<tr style="border-top-left-radius: 10px;border-top-right-radius: 10px;height:35px;">
						<td class="SLAB trtitle" colspan="7" align="center" >Filtros de Busqueda</td>
					</tr>
					<tr
						style="width:100%;display: flex;flex-direction: row;justify-content: flex-start;gap: 5px;align-items: center;flex-wrap: wrap; margin:10px;font-size: 15px;">

						<td class="">Estado</td>
						<td>
							<select name="estado_filtro" id="estado_filtro">
								<option value="">Seleccione...</option>
								<?php
								if ($totalRows_RsEstados > 0) {
									do {
										?>
										<option value="<?php echo ($row_RsEstados['CODIGO']); ?>" <?php if ($estado_filtro == $row_RsEstados['CODIGO']) {
											   echo ('selected');
										   } ?>>
											<?php echo ($row_RsEstados['NOMBRE']); ?>
										</option>
										<?php
									} while ($row_RsEstados = mysqli_fetch_array($RsEstados));
								}
								?>
							</select>
							<input type="button" name="festado" id="festado" value="x"
								onclick="limpiarfiltros('estado_filtro');">
						</td>
						<?php if ($rolID != 4) {
							?>
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
								<input type="button" name="farea" id="farea" value="x"
									onclick="limpiarfiltros('area_filtro');">
							</td>
							<?php
						}
						?>
						<td class="">Codigo Requerimiento</td>
						<td>
							<input type="text" name="codigo_filtro" id="codigo_filtro"
								value="<?php echo ($codigo_filtro); ?>" size="10">
							<input type="button" name="fcodrequ" id="fcodrequ" value="x"
								onclick="limpiarfiltros('codigo_filtro');">
						</td>
						<td>Detalle</td>
						<td><input type="text" name="desc_detalle" id="desc_detalle" size="10" </td>

							<?php if ($rolID == 5) { ?>

							<td>Detalles por firma rector</td>
							<td>
								<select name="fdetallesfirmar" id="fdetallesfirmar">
									<option value="">Seleccione...</option>
									<option value="1" <?php if ($fdetallesfirmar == 1) {
										echo ('selected');
									} ?>>con detalles
										por
										firmar</option>
								</select>
							</td>

						<?php } ?>
					</tr>
					<tr style="center width:50%;display: flex;flex-direction: row;justify-content: center;gap: 5px;align-items: center;flex-wrap: wrap; margin:10px;font-size: 15px;">
						<?php if ($rolID == 2 || $rolID == 3 || $rolID == 5) { ?>
							<td>
								<input type="submit" name="listar_todos" id="listar_todos" class="button2" value="Todos"
									onclick="Buscar_todo();">
							</td>
						<?php } ?>
						<td align="center">
							<input type="submit" name="butonfiltro" id="butonfiltro" class="button2" value="Buscar"
								onclick="Busqueda();">
						</td>
					</tr>
				</table>
			</div>
			<table border="0">
				<tr>
					<td colspan="2">
						<input type="button" class="button2" name="consultar" id="consultar" value="Crear Requerimiento"
							onclick="CrearRequerimiento('1');">
					</td>
					<td colspan="1" align="right">
						<?php if ($rolID == 2 or $rolID == 3) { ?>
							<div onclick="location.href=('home.php?page=detalle_compraangular')"><span><i
										class="fa fa-list fa-2x" aria-hidden="true"></i></span></div>
						<?php } ?>
					</td>

				</tr>
				<td colspan='8'>
					</tr>
					<tr>
						<td colspan="12" style="text-align:end;">
							<?php if ($totalRows_RsListaRequerimientos > 0) {
								?>
								Mostrando <b><?php echo ($startRow_RsListaRequerimientos + 1); ?></b> a
								<b><?php echo ($paginaHasta); ?></b> de <b><?php echo ($totalRows_RsListaRequerimientos);
								   ?></b> Registros
								<?php
							} else {
								?>
								Mostrando <b>0</b> a <b>0</b> de <b>0</b> Registros
								<?php
							}
							?>
						</td>
					</tr>

					<?php
					if ($totalRows_RsListaRequerimientos > 0) {
						?>
						<tr class="SLAB trtitle" align="center">
							<td width="30"></td>
							<td width="100" style="text-align:center">Codigo Requerimiento</td>
							<?php if ($rolID != 4) { ?>
								<td width="100">Area</td>
							<?php } ?>
							<td>Estado</td>
							<td>Fecha</td>
							<td>Encuesta</td>
							<td>Detalles</td>
							<td width="350">Descripcion</td>

						</tr>
						<?php
						$k = 0;
						do {
							$k++;
							if ($k % 2 == 0) {
								$estilo = "SB";
							} else {
								$estilo = "SB2";
							}
							?>
							<tr CLASS="<?php echo ($estilo); ?>" height="30">
								<td class="tdcontent">
									<?php
									if (($row_RsListaRequerimientos['ESTADO'] > 2) && $rolID == 2) {
										?>
										<a href="R.php?codreq=<?php echo ($row_RsListaRequerimientos['CODIGO']); ?>"
											class="buttonazul" target="_blank" ">Reporte</a>
																																																											<a href=" home.php?page=solicitud&codreq=<?php echo ($row_RsListaRequerimientos['CODIGO']); ?> " class=" buttonazul" target="_blank">Ver</a>
										<?php
									} else {
										?>
										<a href="home.php?page=solicitud&codreq=<?php echo ($row_RsListaRequerimientos['CODIGO']); ?>"
											class="buttonazul" target="_blank">Ver</a>
										<?php
									}
									?>
								</td>
								<td class="tdcontent">
									<?php echo ($row_RsListaRequerimientos['CODIGO_REQUERIMIENTO']); ?>
								</td>
								<?php if ($rolID != 4) {
									?>
									<td class="tdcontent">
										<?php echo ($row_RsListaRequerimientos['AREA_DES']); ?>
									</td>
								<?php }
								?>
								<td class="tdcontent" bgcolor="<?php echo ($row_RsListaRequerimientos['COLOR']); ?>">
									<?php echo ($row_RsListaRequerimientos['ESTADO_DES']); ?>
								</td>
								<td class="tdcontent">
									<?php echo ($row_RsListaRequerimientos['FECHA']); ?>
								</td>
								<td> </td>
								<td align="center">

									<?php
									require_once ("scripts/funcionescombo.php");
									$estados = dameTotalDetalles($row_RsListaRequerimientos['CODIGO']);
									foreach ($estados as $indice => $registro) {
										echo ($registro['TOTAL']);
									}
									?>
								</td>
								
								<td >
									<?php 
								echo printDescripcionDetalle($row_RsListaRequerimientos['DESCRIPCION_DETALLE']);
									?>
									
								</td>



								<!-- <td>
						 <?php if ($row_RsListaRequerimientos['ENCUESTA'] > 0 && $row_RsListaRequerimientos['RESPUESTA_ENC'] == 0) {
							 ?>
							 <a target="_blank" href="encuesta/#/enc/<?php echo ($row_RsListaRequerimientos['ENCUESTA']); ?>">Ir a Encuesta</a>
							 <?php
						 }
						 if ($row_RsListaRequerimientos['RESPUESTA_ENC'] == 1) {
							 ?>
							 encuesta realizada
							 <?php
						 }
						 ?>
						</td>-->
								
							</tr>
							<?php
						} while ($row_RsListaRequerimientos = mysqli_fetch_array($RsListaRequerimientos));
					} else {
						?>
						<tr>
							<td colspan="4">No existen registros</td>
						</tr>
						<?php
					}
					?>
			</table>
			<table border="0" align="left" class="datagrid">
				<tr>
					<td colspan="4">&nbsp;</td>
				</tr>
				<tr class="texto_gral">
					<td>
						<ul>
							<?php if ($pageNum_RsListaRequerimientos > 0) { // Show if not first page ?>
								<li>
									<a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListaRequerimientos=%d%s", $currentPage, 0, $queryString_RsListaRequerimientos); ?>')"
										class="submenus">Primero</a>
								</li>
							<?php } // Show if not first page ?>
							<?php if ($pageNum_RsListaRequerimientos > 0) { // Show if not first page ?>
								<li>
									<a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListaRequerimientos=%d%s", $currentPage, max(0, $pageNum_RsListaRequerimientos - 1), $queryString_RsListaRequerimientos); ?>')"
										class="submenus">Anterior</a>
								</li>
							<?php } // Show if not first page ?>
							<?php if ($pageNum_RsListaRequerimientos < $totalPages_RsListaRequerimientos) { // Show if not last page ?>
								<li>
									<a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListaRequerimientos=%d%s", $currentPage, min($totalPages_RsListaRequerimientos, $pageNum_RsListaRequerimientos + 1), $queryString_RsListaRequerimientos); ?>')"
										class="submenus">Siguiente</a>
								</li>
							<?php } // Show if not last page ?>
							<?php if ($pageNum_RsListaRequerimientos < $totalPages_RsListaRequerimientos) { // Show if not last page ?>
								<li>
									<a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListaRequerimientos=%d%s", $currentPage, $totalPages_RsListaRequerimientos, $queryString_RsListaRequerimientos); ?>')"
										class="submenus">&Uacute;ltimo</a>
								</li>
							<?php } // Show if not last page ?>
						</ul>
					</td>
				</tr>
			</table>
		</div>
	</form>
	<script type="text/javascript">
		function getDataServer(url, vars) {
			var xml = null;
			try {
				xml = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (expeption) {
				xml = new XMLHttpRequest();
			}
			xml.open("GET", url + vars, false);
			xml.send(null);
			if (xml.status == 404) alert("Url no valida");
			return xml.responseText;
		}

		function VerRequerimiento(req) {
			window.location = "home.php?page=solicitud&codreq=" + req;
		}



		function VerRequerimiento2(req) {
			window.location = "home.php?page=solicitud_director&codreq=" + req;
		}

		//paginación

		function f_abrir_link(v_link) {
			document.form1.action = v_link;
			document.form1.submit();

		}

		function mostrarfiltros() {
			$("#divfiltros").toggle();
		}

		function limpiarfiltros(campo) {
			document.getElementById('' + campo).value = "";
		}
		function Busqueda() {
			document.form1.action = "home.php?page=requerimientos_lista";
		}
		function Buscar_todo() {
			document.form1.action = "home.php?page=requerimientos_lista&todos=1";
		}
	</script>