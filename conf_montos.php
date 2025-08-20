<?php
//actualizado 2025
$salario_actual="1423500";

$valor_orden2=str_replace(',','',$valor_orden); 

$valor_salario=$valor_orden2/$salario_actual;
//echo($valor_salario);

//Aprueba dirección administrativa
$condicion1_min=0;
$condicion1_max=10;

//Aprueba gerencia
$condicion2_min=10;
$condicion2_max=20;

//Aprueba Comité
$condicion3_min=20;
$condicion3_max=60;

//Aprueba Consejo Superior
$condicion4_min=60;
$condicion4_max=INF;

$resultado = false;
//evaluar monto

switch ($valor_orden) {
	
    case  ($valor_salario > $condicion1_min && $valor_salario <= $condicion1_max) :
			
			if($_SESSION['MM_RolID']==3 && $row_RsListaRequerimientos['FIRMA']=='0'){
				$resultado="Aprueba Dirección";				
			}
		break;
		
    case ($valor_salario > $condicion2_min && $valor_salario <= $condicion2_max):
        
		if($_SESSION['MM_RolID']==5 && $row_RsListaRequerimientos['FIRMA']=='0'){
		$resultado="Aprueba Gerencia";		
		}
        break;

	case ($valor_salario > $condicion3_min && $valor_salario <= $condicion3_max):
       if($_SESSION['MM_RolID']==5 && $row_RsListaRequerimientos['FIRMA']=='0'){
		$resultado="Aprueba Comité";
	   }
        break;

	case ($valor_salario > $condicion4_min && $valor_salario < $condicion4_max):
		if($_SESSION['MM_RolID']==5 && $row_RsListaRequerimientos['FIRMA']=='0'){          
		  $resultado="Aprueba Concejo Superior";
}
        break;	
}




?>

 