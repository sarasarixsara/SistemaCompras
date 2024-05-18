<?PHP
require_once('conexion/db.php');

$idRol="1";

$query_RsListadoRequerimiento = "SELECT
                                        R.REQUCODI ID,
										R.REQUIDUS ID_USUARIO,
										R.REQUFESO FECHA_SOLICITUD,
										R.REQUAREA ID_AREA,
                                        E.ESTANOMB ESTADO,
                                        E.ESTACOLO COLOR
										FROM Requerimientos R,estados E
										WHERE E.ESTAID=REQUESTA";

				// echo($query_RsListadoListadoRequerimiento);echo("<br>");
	$RsListadoRequerimiento = mysql_query($query_RsListadoRequerimiento, $conexion) or die(mysql_error());
	$row_RsListadoRequerimiento = mysql_fetch_assoc($RsListadoRequerimiento);
    $totalRows_RsListadoRequerimiento = mysql_num_rows($RsListadoRequerimiento);


?>
<!DOCTYPE html>
<html>

<head>
<title>Listados de Requerimientos</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/estilo_lista.css" />
</head>
<body>
<table class="bordered">
   <thead>

    <tr>
        <th>C&oacute;digo</th>
        <th>Solicitante</th>
        <th>Fecha de Solicitud</th>
        <th>POA</th>
		<th>Estado</th>
    </tr>
    </thead>
	<?php
$i = 0;
	if ($totalRows_RsListadoRequerimiento > 0) { // recorrer si no esta vacia
     do { ?>

    <tr>
        <td>
		<a href="solicitud.php?codReque=<?php echo($row_RsListadoRequerimiento['ID']);?>">
        <?php echo($row_RsListadoRequerimiento['ID']); ?>
      </a>
		</td>
        <td><?php echo('JUAN CAMILO DIAZ'); ?></td>
        <td><?php echo($row_RsListadoRequerimiento['FECHA_SOLICITUD']); ?></td>
        <td><?php echo('CIENCIAS NATURALES'); ?></td>
        <td bgcolor='<?php echo($row_RsListadoRequerimiento['COLOR']); ?>'><?php echo($row_RsListadoRequerimiento['ESTADO']); ?></td>
    </tr>
 <?php } while ($row_RsListadoRequerimiento = mysql_fetch_assoc($RsListadoRequerimiento));
     } //  ?>
</table>
</body>
</html>