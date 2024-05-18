<?php
require_once('./../../conexion/db.php');
	if (!isset($_SESSION)) {
  session_start();
}


$tipoGuardar='';
if(isset($_GET['tipoGuardar']) && $_GET['tipoGuardar']!=''){
$tipoGuardar=$_GET['tipoGuardar'];
}

function getPost(){
	$request_body = file_get_contents('php://input');
	//$request_body = json_decode($request_body); /*CONVERTIDO SOLO A JSON Y DESPUES DE LEE CON $VAR->PROPIEDAD*/
	$request_body = json_decode($request_body, TRUE); /*ASI LO CONVIERTE EN ARRAY Y SE LEE $VAR['PROPIEDAD']*/
	//var_dump($request_body['filtro_lista']);
	//var_dump($request_body);
	//exit();	
	return ($request_body); 	
}

if($tipoGuardar == 'loaddetalleslista'){
    $getPost = getPost();
    $estado = '';
    $area = '';
	$nombre = '';
	$fecha_env_req = '';
    if(isset($getPost['info']['estado'])){        $estado  = $getPost['info']['estado'];  }
    if(isset($getPost['info']['area'])){   $area  = $getPost['info']['area'];    }   
    if(isset($getPost['info']['nombre'])){   $nombre  = $getPost['info']['nombre'];    }   
    if(isset($getPost['info']['fecha_env_req'])){   $fecha_env_req  = $getPost['info']['fecha_env_req'];    }   

    $query = "SELECT `DERECONS` CODIGO,	                           
                        (SELECT A.AREANOMB 
                        FROM   AREA A,
                                REQUERIMIENTOS R 
                        WHERE  AREAID=REQUAREA 
                        AND    R.REQUCODI=D.DEREREQU)AREA_DESC,
                        (SELECT R2.REQUCODI 
                        FROM   REQUERIMIENTOS R2 
                        WHERE  R2.REQUCODI=D.DEREREQU)CODIGO_REQ,
                        (SELECT R3.REQUFEEN  
                        FROM   REQUERIMIENTOS R3 
                        WHERE  R3.REQUCODI=D.DEREREQU)FECHAENVIO_REQ,
                        SUBSTRING(DEREDESC, 1, 55) DESCRP,
                        DERECANT CANTIDAD,
                        E.ESDECOLO COLOR_ESTADO,
                        E.ESDENOMB ESTADO_DESC,
						E.ESDECODI CODIGO_ESTADO,
						false MARCADO
                        
                    FROM detalle_requ D 
                    LEFT JOIN ESTADO_DETALLE E ON D.DEREAPRO = E.ESDECODI
                    WHERE 1	
                        And E.ESDECODI != '0'
                        ";
    /*$RsListaAnexosDet = mysqli_query($conexion,$query_RsEstados) or die(mysqli_error($conexion));
    $row_RsListaAnexosDet = mysqli_fetch_array($RsListaAnexosDet);
    $totalRows_RsListaAnexosDet = mysqli_num_rows($RsListaAnexosDet);	*/ 

    if($nombre != ''){
        $query = $query. " and D.DEREDESC like '%".$nombre."%'";
    }	
    if($estado != ''){
        $query = $query. " and E.ESDECODI = '".$estado."'";
    }
    if($area != ''){
        $query = $query. " and (SELECT A.AREAID 
                        FROM   AREA A,
                                REQUERIMIENTOS R 
                        WHERE  AREAID=REQUAREA 
                        AND    R.REQUCODI=D.DEREREQU) = '".$area."'";
    }
	
    if($fecha_env_req != ''){
        //$query = $query. " and E.DEREDESC >= '".$fecha_env_req."'";
    }	
    //echo($query);
	$total_query = "SELECT COUNT(1) TOTAL FROM (${query}) AS combined_table";
    /* $total = $wpdb->get_var( $total_query ); */
    $totalRsLista = mysqli_query($conexion,$total_query) or die(mysqli_error($conexion));
	$totalRs = mysqli_fetch_array($totalRsLista);
    $total = $totalRs['TOTAL'];

    $items_per_page = 10;
    $page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
	if($page==0){$page =1;}
    $offset = ( $page * $items_per_page ) - $items_per_page;
    /*$latestposts = $wpdb->get_results( $query . 

" ORDER BY id desc LIMIT ${offset}, ${items_per_page}" );	*/
$Rslistadetalles = mysqli_query( $conexion, $query . 

" ORDER BY FECHAENVIO_REQ DESC LIMIT ${offset}, ${items_per_page}" );
//echo($query);
$querylatestposts = mysqli_fetch_array($Rslistadetalles);
$latestposts = array();
    if(count($querylatestposts) >0){
        do{
        $latestposts[] = $querylatestposts;
        }while($querylatestposts = mysqli_fetch_array($Rslistadetalles));
    }



$responseProposals[] = array(
						 'Detalles' => $latestposts,
						 'Paginate'  => array(
											'current' => $page,
											'total'   => ceil($total / $items_per_page),
											'recordsPerPage' => $items_per_page,
											'totalrecords'    => $total
											)
						);
	echo(json_encode($responseProposals,JSON_NUMERIC_CHECK));    
}
if($tipoGuardar == 'loadEstadosAreas'){
    $response = array();
    $arrayestados = array();
    $arrayareas = array();
    $query_RsEstados = "SELECT `ESDECODI` CODIGO,
                        `ESDENOMB` NOMBRE,
                        `ESDECOLO` COLOR,
                        `ESDEFLAG`
                    FROM `ESTADO_DETALLE`E 
                    WHERE E.`ESDECODI` 
                    ORDER BY E.`ESDENOMB` ASC
                    ";
    
    $RsListaAnexosDet = mysqli_query($conexion,$query_RsEstados) or die(mysqli_error($conexion));
    $row_RsListaAnexosDet = mysqli_fetch_array($RsListaAnexosDet);
    $totalRows_RsListaAnexosDet = mysqli_num_rows($RsListaAnexosDet);	
    if($totalRows_RsListaAnexosDet>0){
        do{
            $arrayestados[] = $row_RsListaAnexosDet;
        }while($row_RsListaAnexosDet = mysqli_fetch_array($RsListaAnexosDet));
    }    

	$query_RsAreaLista = "SELECT R.AREAID CODIGO,
                                 R.AREANOMB NOMBRE
                                        FROM AREA R";
    $RsAreaLista = mysqli_query($conexion,$query_RsAreaLista) or die(mysqli_error($conexion));
    $row_RsAreaLista = mysqli_fetch_array($RsAreaLista);
    $totalRows_RsAreaLista = mysqli_num_rows($RsAreaLista);
    if($totalRows_RsAreaLista>0){
        do{
            $arrayareas[] = $row_RsAreaLista;
        }while($row_RsAreaLista = mysqli_fetch_array($RsAreaLista));
    }    
    $response = array('estados' => $arrayestados,
                      'areas'   => $arrayareas
                      );
 
    echo(json_encode($response,JSON_NUMERIC_CHECK));       
}
if($tipoGuardar == 'ChangeStatusDetail'){
    $getPost = getPost();
    $estado = '';
    $area = '';

    $estado = '';
  if(isset($_GET['estado']) && $_GET['estado'] != '') {
     $estado =  $_GET['estado'];                                                 
    }

	
	$response = array('codigos' => array(),
	                  'color' => ''
	);
    if($estado != '' && isset($getPost['info'])){
        if(is_array($getPost['info'])){
            for($i=0; $i<count($getPost['info']); $i++){
					/*aqui esta el codigo de cada detalle en el bucle y el estado viene var $estado*/	
					$query_RsAreaLista = "UPDATE DETALLE_REQU SET 
											DEREAPRO = '".$estado."'
										 where DERECONS = '".$getPost['info'][$i]['codigo']."'
										";
					$RsAreaLista = mysqli_query($conexion,$query_RsAreaLista) or die(mysqli_error($conexion));
				
					$response['codigos'][$i] = array(
											'codigo' => $getPost['info'][$i]['codigo']
											);
					
			}
					$query_RsInfoUpdate = "SELECT E.ESDECODI CODIGO,
												  ESDENOMB NOMBRE,
												  E.ESDECOLO COLOR
											FROM ESTADO_DETALLE E
											WHERE E.ESDECODI = '".$estado."'";
					$RsInfoUpdate= mysqli_query($conexion,$query_RsInfoUpdate) or die(mysqli_error($conexion));
					$row_RsInfoUpdate= mysqli_fetch_array($RsInfoUpdate);
					$totalRows_RsInfoUpdate = mysqli_num_rows($RsInfoUpdate);
					$response['color'] = $row_RsInfoUpdate;
					echo(json_encode($response,JSON_NUMERIC_CHECK));
			
        }
    }
}

if($tipoGuardar == 'SaveNewPoa'){
	$input = filter_input_array(INPUT_POST);
	
	$query_RsInfoUpdate = "insert into POA (POACODI, 
											POANOMB,
											POARESP,
											POAESTA											
											)
											values
											(
											null,
											'".$input['poa_nombre']."',
											'".$input['responsable']."',
											'".$input['poa_estado']."'											
											)
											";
	$RsInfoUpdate= mysqli_query($conexion,$query_RsInfoUpdate) or die(mysqli_error($conexion));
	
	$query_RsUltInsert = "SELECT LAST_INSERT_ID() DATO";
	$RsUltInsert = mysqli_query($conexion,$query_RsUltInsert) or die(mysqli_error($conexion));
	$row_RsUltInsert = mysqli_fetch_array($RsUltInsert);
	$ultimo_poa = $row_RsUltInsert['DATO'];	
	//$ultimo_poa = 900;
	$input['responsable_des'] = '';
	if($input['responsable']!= ''){
		$query_RsInfoUpdate = "select concat(P.PERSNOMB,' ',P.PERSAPEL) NOMBRE FROM PERSONAS P WHERE P.PERSID = '".$input['responsable']."'";
		$RsInfoUpdate= mysqli_query($conexion,$query_RsInfoUpdate) or die(mysqli_error($conexion));
		$row_RsInfoUpdate= mysqli_fetch_array($RsInfoUpdate);
		$totalRows_RsInfoUpdate = mysqli_num_rows($RsInfoUpdate);
		if($totalRows_RsInfoUpdate > 0){
			$input['responsable_des'] = $row_RsInfoUpdate['NOMBRE'];
		}
	}	
	
	$input['ultimo_poa'] = $ultimo_poa;
	$input['poa_estado_des'] = 'Inactivo';
	if($input['poa_estado'] == '1'){
		$input['poa_estado_des'] = 'Activo';
	}
	$response = array('ultimo_record' => $ultimo_poa,
					  'info'          => $input
					 );
					 
    echo(json_encode($response, JSON_NUMERIC_CHECK));					 	
}

if($tipoGuardar == 'SaveNewCentro'){
	$input = filter_input_array(INPUT_POST);

	$query_RsInfoUpdate = "insert into POADETA (PODECODI, 
											PODENOMB,
											PODEPOA,
											PODETIPO,
											PODEESTA
											)
											values
											(
											null,
											'".$input['centro_nombre']."',
											'1',
											'1',
											'".$input['centro_estado']."'											
											)
											";
	$RsInfoUpdate= mysqli_query($conexion,$query_RsInfoUpdate) or die(mysqli_error($conexion));
	
	$query_RsUltInsert = "SELECT LAST_INSERT_ID() DATO";
	$RsUltInsert = mysqli_query($conexion,$query_RsUltInsert) or die(mysqli_error($conexion));
	$row_RsUltInsert = mysqli_fetch_array($RsUltInsert);
	$ultimo_poa = $row_RsUltInsert['DATO'];	
	//$ultimo_poa = 900;	
	
	$input['ultimo_poa'] = $ultimo_poa;
	$input['centro_estado_des'] = 'Inactivo';
	if($input['centro_estado'] == '1'){
		$input['centro_estado_des'] = 'Activo';
	}
	$response = array('ultimo_record' => $ultimo_poa,
					  'info'          => $input
					 ); 
    echo(json_encode($response, JSON_NUMERIC_CHECK));					 	
}


if($tipoGuardar == 'SaveNewArea'){
	$input = filter_input_array(INPUT_POST);

	$query_RsInfoUpdate = "insert into AREA (AREAID, 
											AREANOMB,
											AREAESTA
											)
											values
											(
											null,
											'".$input['area_nombre']."',
											'".$input['area_estado']."'											
											)
											";
	$RsInfoUpdate= mysqli_query($conexion,$query_RsInfoUpdate) or die(mysqli_error($conexion));
	
	$query_RsUltInsert = "SELECT LAST_INSERT_ID() DATO";
	$RsUltInsert = mysqli_query($conexion,$query_RsUltInsert) or die(mysqli_error($conexion));
	$row_RsUltInsert = mysqli_fetch_array($RsUltInsert);
	$ultimo_poa = $row_RsUltInsert['DATO'];	
	//$ultimo_poa = 900;	
	
	$input['ultimo_poa'] = $ultimo_poa;
	$input['area_estado_des'] = 'Inactivo';
	if($input['area_estado'] == '1'){
		$input['area_estado_des'] = 'Activo';
	}
	$response = array('ultimo_record' => $ultimo_poa,
					  'info'          => $input
					 ); 
    echo(json_encode($response, JSON_NUMERIC_CHECK));					 	
}


if($tipoGuardar == 'SaveAreaEditinPlace'){
	$input = filter_input_array(INPUT_POST);
	$response = array();
	if ($input['action'] === 'Editar') {
		$query = "UPDATE AREA SET 
						AREANOMB   = '".$input['nombre']."',
						AREAESTA   = '".$input['estado']."'
					where AREAID = '".$input['id']."'
					";
					//echo($query);
		$RsInfoUpdate= mysqli_query($conexion,$query) or die(mysqli_error($conexion));
		$response = array('action'   => 'Editar',
						  'afectado' => 1,
						  'info'     => $input
		);
		echo(json_encode($response, JSON_NUMERIC_CHECK));

	} else if ($input['action'] === 'Eliminar') {
			$totalRows_RsInfoUpdate = 1;	
			if($totalRows_RsInfoUpdate > 0){
				$query = "UPDATE AREA SET AREAESTA = 2 WHERE AREAID = '".$input['id']."'";
				$RsInfoUpdate= mysqli_query($conexion,$query) or die(mysqli_error($conexion));
				$response = array('action'   => 'Eliminar',
								  'afectado' => 1,
								  'info'     => $input
				);
				echo(json_encode($response, JSON_NUMERIC_CHECK));						
			}else{
				$response = array('action'   => 'Eliminar',
								  'afectado' => 2,
								  'info'     => $input
				);
				echo(json_encode($response, JSON_NUMERIC_CHECK));						
			}
	} else if ($input['action'] === 'restore') {
		//$mysqli->query("UPDATE users SET deleted=0 WHERE id='" . $input['id'] . "'");
	}	
}
if($tipoGuardar == 'SaveSubPoaEditinPlace'){
	$input = filter_input_array(INPUT_POST);
	$response = array();
	if ($input['action'] === 'Editar') {
		$query = "UPDATE POADETA SET 
						PODENOMB   = '".$input['nombre']."',
						PODEESTA   = '".$input['estado']."'
					where PODECODI = '".$input['id']."'
					";
					//echo($query);
		$RsInfoUpdate= mysqli_query($conexion,$query) or die(mysqli_error($conexion));
		$response = array('action'   => 'Editar',
						  'afectado' => 1,
						  'info'     => $input
		);
		echo(json_encode($response, JSON_NUMERIC_CHECK));

	} else if ($input['action'] === 'Eliminar') {
			$query_RsInfoUpdate = "SELECT R.DERECONS FROM DETALLE_REQU R WHERE DERESUPO = '".$input['id']."' limit 1";
			$RsInfoUpdate= mysqli_query($conexion,$query_RsInfoUpdate) or die(mysqli_error($conexion));
			$row_RsInfoUpdate= mysqli_fetch_array($RsInfoUpdate);
			$totalRows_RsInfoUpdate = mysqli_num_rows($RsInfoUpdate);	
			if($totalRows_RsInfoUpdate == 0){
				$query = "UPDATE POADETA SET PODEESTA = 2 WHERE PODECODI = '".$input['id']."'";
				$RsInfoUpdate= mysqli_query($conexion,$query) or die(mysqli_error($conexion));
				$response = array('action'   => 'Eliminar',
								  'afectado' => 1,
								  'info'     => $input
				);
				echo(json_encode($response, JSON_NUMERIC_CHECK));						
			}else{
				$response = array('action'   => 'Eliminar',
								  'afectado' => 2,
								  'info'     => $input
				);
				echo(json_encode($response, JSON_NUMERIC_CHECK));						
			}
	} else if ($input['action'] === 'restore') {
		//$mysqli->query("UPDATE users SET deleted=0 WHERE id='" . $input['id'] . "'");
	}	
}
if($tipoGuardar == 'SavePoaEditinPlace'){
			$input = filter_input_array(INPUT_POST);
			$response = array();
			if ($input['action'] === 'Editar') {
				$query = "UPDATE POA SET 
								POANOMB = '".$input['descripcion']."',
								POARESP = '".$input['persona_responsable']."',
								POAESTA = '".$input['estado']."'
							where POACODI = '".$input['id']."'
							";
							//echo($query);
				$RsInfoUpdate= mysqli_query($conexion,$query) or die(mysqli_error($conexion));
				$response = array('action'   => 'Editar',
								  'afectado' => 1,
								  'info'     => $input
				);
				echo(json_encode($response, JSON_NUMERIC_CHECK));

			} else if ($input['action'] === 'Eliminar') {
					$query_RsInfoUpdate = "SELECT R.DERECONS FROM DETALLE_REQU R WHERE DEREPOA = '".$input['id']."' limit 1";
					$RsInfoUpdate= mysqli_query($conexion,$query_RsInfoUpdate) or die(mysqli_error($conexion));
					$row_RsInfoUpdate= mysqli_fetch_array($RsInfoUpdate);
					$totalRows_RsInfoUpdate = mysqli_num_rows($RsInfoUpdate);	
					if($totalRows_RsInfoUpdate == 0){
						$query = "UPDATE POA SET POAESTA = 0 WHERE POACODI = '".$input['id']."'";	
						$RsInfoUpdate= mysqli_query($conexion,$query) or die(mysqli_error($conexion));
						$response = array('action'   => 'Eliminar',
										  'afectado' => 1,
										  'info'     => $input
						);
						echo(json_encode($response, JSON_NUMERIC_CHECK));						
					}else{
						$response = array('action'   => 'Eliminar',
										  'afectado' => 2,
										  'info'     => $input
						);
						echo(json_encode($response, JSON_NUMERIC_CHECK));						
					}
			} else if ($input['action'] === 'restore') {
				//$mysqli->query("UPDATE users SET deleted=0 WHERE id='" . $input['id'] . "'");
			}	
}	
?>