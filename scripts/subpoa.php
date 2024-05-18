<?php
include("clases/class.mysql.php");
include("clases/class.combos.php");
$subpoa = new selects();
$subpoa->code = $_GET["code"];
$subpoa = $subpoa->SubPoa();
foreach($subpoa as $key=>$value)
{
		echo "<option value=\"$key\">$value</option>";
}
?>