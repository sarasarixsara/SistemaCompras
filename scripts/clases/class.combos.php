<?php

class selects extends MySQL
{
	var $code = "";
	
	function SubPoa()
	{
		$consulta = parent::consulta("SELECT PODENOMB Name,PODECODI Code FROM POADETA where PODEPOA = '".$this->code."'"." ORDER BY Code ASC");
		$num_total_registros = parent::num_rows($consulta);
		if($num_total_registros>0)
		{
			$subpoas = array();
			while($subpoa = parent::fetch_assoc($consulta))
			{
				$code = $subpoa["Code"];
				$name = $subpoa["Name"];				
				$subpoas[$code]=$name;
			}
			return $subpoas;
		}
		else
		{
			return false;
		}
	}
	function cargarModalidades()
	{
		$consulta = parent::consulta("SELECT PRODNOMB Name,PRODCONS Code FROM PRODUCTOS ORDER BY Name ASC");
		$num_total_registros = parent::num_rows($consulta);
		if($num_total_registros>0)
		{
			$modalidades = array();
			while($modalidad = parent::fetch_assoc($consulta))
			{
				$code = $modalidad["Code"];
				$name = $modalidad["Name"];				
				$modalidades[$code]=$name;
			}
			return $modalidades;
		}
		else
		{
			return false;
		}
	}
	function cargarClasificaciones()
	{
		$consulta = parent::consulta("SELECT TIPRCONS Code, TIPRNOMB Name FROM TIPO_PRODUCTO WHERE TIPRIDPR = '".$this->code."'");
		$num_total_registros = parent::num_rows($consulta);
		if($num_total_registros>0)
		{
			$clasificaciones = array();
			while($clasificacion = parent::fetch_assoc($consulta))
			{
				$code = $clasificacion["Code"];				
				$name = $clasificacion["Name"];				
				$clasificaciones[$code]=$name;
			}
			return $clasificaciones;
		}
		else
		{
			return false;
		}
	}
		
	function cargarCiudades()
	{
		$consulta = parent::consulta("SELECT Name FROM city WHERE Province = '".$this->code."'");
		$num_total_registros = parent::num_rows($consulta);
		if($num_total_registros>0)
		{
			$ciudades = array();
			while($ciudad = parent::fetch_assoc($consulta))
			{
				$name = $ciudad["Name"];				
				$ciudades[$name]=$name;
			}
			return $ciudades;
		}
		else
		{
			return false;
		}
	}		
}
?>