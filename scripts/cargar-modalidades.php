<?php
include("clases/class.mysql.php");
include("clases/class.combos.php");
$selects = new selects();
$modalidades = $selects->cargarModalidades();
foreach($modalidades as $key=>$value)
{
		echo "<option value=\"$key\">$value</option>";
}
?>