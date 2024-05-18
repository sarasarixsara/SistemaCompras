<?php
require_once('../../conexion/db.php');
	if (!isset($_SESSION)) {
  session_start();
}
/*
if(!isset($_SESSION['MM_AccesoCorrectoApp']) || $_SESSION['MM_AccesoCorrectoApp'] != 'ACTIVO'){
  header("location: index.php");
}
*/
    $query_RsListaPreguntas = "
								SELECT P.PREGCODI PREGUNTA,
								   P.PREGDESC PREGUNTA_DES,
								   P.PREGENCU ENCUESTA,
								   P.PREGTIPO TIPO_PREGUNTA,
								   '0' RESPUESTA,
								   EP.ENPEREQU REQUERIMIENTO

							  FROM PREGUNTAS         P,
								   ENCUESTA          E,
								   PREGUNTAS_TIPO    PT,
								   PREGUNTASOPT_RESP O,
								   ENCUESTA_PERS     EP
							 WHERE P.PREGENCU = E.ENCUCODI
							   AND P.PREGTIPO = PT.PRTICODI
							   AND PT.PRTICODI = O.POREOPCI
							   AND EP.ENPECODI = '".$_GET['CodEnc']."'
							   AND EP.ENPEENCU = E.ENCUCODI
							   AND EP.ENPEESTA = '0'
							   order by P.PREGCODI";
	$RsListaPreguntas = mysqli_query($conexion,$query_RsListaPreguntas) or die(mysqli_error($conexion));
	$row_RsListaPreguntas = mysqli_fetch_assoc($RsListaPreguntas);
    $totalRows_RsListaPreguntas = mysqli_num_rows($RsListaPreguntas);
	$preguntas = array();
	$datos     = array();
	$response  = array();
	$requerimiento = '';
	if($totalRows_RsListaPreguntas>0){
		$requerimiento = $row_RsListaPreguntas['REQUERIMIENTO'];
		do{
			$opciones  = array();
			$query_RsOpciones = "SELECT R.PORECODI CODIGO,
										R.POREOPCI OPCION,
										O.PROPNOMB OPCION_DES
								FROM PREGUNTAS_OPCIONES O,
								     PREGUNTASOPT_RESP  R
								WHERE O.PROPCODI = R.POREOPCI
								  AND R.PORETIPR = '".$row_RsListaPreguntas['TIPO_PREGUNTA']."'";
			$RsOpciones = mysqli_query($conexion,$query_RsOpciones) or die(mysqli_error($conexion));
			$row_RsOpciones = mysqli_fetch_assoc($RsOpciones);
			$totalRows_RsOpciones = mysqli_num_rows($RsOpciones);			
			if($totalRows_RsOpciones>0){
				do{
					$opciones[] = $row_RsOpciones;
				}while($row_RsOpciones = mysqli_fetch_assoc($RsOpciones));
			}
			
			$datos[] = array('PREGUNTA'         => $row_RsListaPreguntas['PREGUNTA'],
							 'PREGUNTA_DES'     => $row_RsListaPreguntas['PREGUNTA_DES'],
							 'ENCUESTA'         => $row_RsListaPreguntas['ENCUESTA'],
							 'TIPO_PREGUNTA'    => $row_RsListaPreguntas['TIPO_PREGUNTA'],
							 'RESPUESTA'        => $row_RsListaPreguntas['RESPUESTA'],
							 'OPCIONES'         => $opciones,
							 'choice'			=> null,
			                 );
		}while($row_RsListaPreguntas = mysqli_fetch_assoc($RsListaPreguntas));
	}
	$response = array('pregs'       =>  $datos,
	                  'total_rtas'  =>  0,
					  'index'       =>  0,
					  'requ'        =>  $requerimiento, 
	               );
	echo(json_encode($response));
/*
	$paginas[] = array('name'  => 'primero',
	                   'value' => $primero,
					   'totalr'=> $totalRows_RsBeneficiarios,
					   'pag'   => 0,
					   );
    $paginas[] = array('name'  => 'anterior',
	                   'value' => $anterior,
					   'totalr'=> $totalRows_RsBeneficiarios,
					   'pag'   => $antpage,
					   );
    $paginas[] = array('name'  => 'siguiente',
	                   'value' => $siguiente,
					   'totalr'=> $totalRows_RsBeneficiarios,
					   'pag'   => $sigpag,
					   );	
    $paginas[] = array('name'  => 'ultimo',
	                   'value' => $ultimo,
					   'totalr'=> $totalRows_RsBeneficiarios,
					   'pag'   => $totalPages_RsBeneficiarios,
					   'start' => $startRow_RsBeneficiarios,
					   'hasta' => $paginaHasta,
					   );
	

	$array[] = array('dato'  => $data,
					 'info'  => $paginas);
	echo(json_encode($array)); */
?>