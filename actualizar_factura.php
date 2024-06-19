<?php
require_once('conexion/db.php');

$idRequerimiento = $_POST['idRequerimiento'];
$idFactura = $_POST['idFactura'];
$urlFactura = $_POST['urlFactura'] ?? '';
$numeroFactura = $_POST['numeroFactura'] ?? '';

if (!$idRequerimiento || $idRequerimiento == '') {
    return;
}
echo "hola 2 ";
//CONSULTA DE AREAS
if ($idFactura == 0) {
    $query = "INSERT INTO detalle_factura (DEFADESC,DEFADETA,DEFANUM) VALUES('$urlFactura', '$idRequerimiento', '$numeroFactura')";
    echo "idFactura 0 ";
    echo $query;

} else {
    list($field, $value) = getFieldAndValue($urlFactura, $numeroFactura);
    $query = "UPDATE detalle_factura SET $field = '$value' WHERE DEFAID = '$idFactura'";
    echo "idFactura dif 0 ";
    echo $query;
}
$Result = mysqli_query($conexion, $query) or die(mysqli_connect_error());
echo $query;
print_r($query);
print_r($Result);

function getFieldAndValue($urlFactura, $numeroFactura)
{
    if ($urlFactura) {
        return ['DEFADESC', $urlFactura];
    }
    if ($numeroFactura) {
        return ['DEFANUM', $numeroFactura];
    }
}