<?php
include("clases/class.mysql.php");
include("clases/class.combos.php");
$clasificaciones = new selects();
$clasificaciones->code = $_GET["code"];
$clasificaciones = $clasificaciones->cargarClasificaciones();
foreach($clasificaciones as $key=>$value)
{
		echo "<option value='".$key."'>".utf8_encode($value)."</option>";
}
?>