<?php
require_once('../../conexion/db.php');
	if (!isset($_SESSION)) {
  session_start();
}
//Variable Crear Usuarios
$tipoGuardar='';
if(isset($_GET['tipoGuardar']) && $_GET['tipoGuardar']!=''){
$tipoGuardar =$_GET['tipoGuardar'];
}

if($tipoGuardar=='saveEncuesta'){
	if(isset($_POST['json']) && $_POST['json']!=''){
	$codigo_orden ='';
     $array = json_decode($_POST['json']);
	 if(count($array)>0){
		//echo($array->pregs[0]->PREGUNTA); /*buena*/
		//print_r($array[0]->pregs[0].PREGUNTA);	
		if(isset($_GET['codenc']) && $_GET['codenc'] != ''){
			$query_RsListaPreguntas = "SELECT E.ENPEPERS PERSONA, E.ENPEESTA ESTADO FROM encuesta_pers E WHERE E.ENPECODI = '".$_GET['codenc']."'";
			$RsListaPreguntas = mysqli_query($conexion,$query_RsListaPreguntas) or die(mysqli_error($conexion));
			$row_RsListaPreguntas = mysqli_fetch_assoc($RsListaPreguntas);
			//$totalRows_RsListaPreguntas = mysqli_num_rows($RsListaPreguntas);			
			if($row_RsListaPreguntas['ESTADO'] == 0){
			$query_RsListaPreguntas = "update encuesta_pers set ENPEESTA = '1' where ENPECODI = '".$_GET['codenc']."'";
			$RsListaPreguntas = mysqli_query($conexion,$query_RsListaPreguntas) or die(mysqli_error($conexion));
			
			$query_RsListaPreguntas = "update requerimientos set REQUESTA = '17' where REQUCODI = '".$array->requ."'";
			$RsListaPreguntas = mysqli_query($conexion,$query_RsListaPreguntas) or die(mysqli_error($conexion));			
			
				for($j=0; $j<count($array->pregs); $j++){
					//$array->pregs[$j]->PREGUNTA			
					$query_RsInsertarOrden ="INSERT INTO preguntas_resp (
																		  PRRECODI,
																		  PRREENPE,
																		  PRRECEPE,
																		  PRRERESP,
																		  PRREPREG
																		  )
																		  VALUES
																		  (
																		   NULL,
																		   '".$row_RsListaPreguntas['PERSONA']."',
																		   '".$_GET['codenc']."',
																		   '".$array->pregs[$j]->RESPUESTA."',
																		   '".$array->pregs[$j]->PREGUNTA."'
																		  )";
				   $RsInsertarOrden = mysqli_query($conexion,$query_RsInsertarOrden) or die(mysqli_error($conexion));
				}

			}
		}
		
	 }else{ 
	 
	 }
   }
   //print_r($array);
   //echo($_GET['codenc']);
}   

?>