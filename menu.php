
<ul >	
<?php
  			if($_SESSION['MM_RolID']==6)
  			{
  		?>  
  			<li <?php if(isset($_GET['page']) && $_GET['page']=='requerimientos_lista'){ echo('style="background:#438AC8"');} ?> >
		  		<a href="home.php?page=requerimientos_lista"><FONT SIZE="2">Requerimientos</FONT></a>
			</li>		  
		  <?php 
			  } 
		  ?>
		<?php
  			if($_SESSION['MM_RolID']==5)
  			{
  		?>     	
  			<li <?php if(isset($_GET['page']) && $_GET['page']=='requerimientos_lista'){ echo('style="background:#438AC8"');} ?> >
		  		<a href="home.php?page=requerimientos_lista"><FONT SIZE="2">Requerimientos</FONT></a>
			</li>
		 	<li <?php if(isset($_GET['page']) && $_GET['page']=='ordenar_compra_lista'){ echo('style="background:#438AC8"');} ?> >
		  		<a href="home.php?page=ordenar_compra_lista"><FONT SIZE="2">Ordenes</FONT></a>
			</li>		 	
		<?php
  			}
  		?>
  		<?php
		//Rol de usuario general 
   			if($_SESSION['MM_RolID']==4)
   			{
  		?>
	 	    <li <?php if(isset($_GET['page']) && $_GET['page']=='requerimientos_lista'){ echo('style="background:#438AC8"');} ?> >
				 <a href="home.php?page=requerimientos_lista"><FONT SIZE="2">Requerimientos</FONT></a>
			</li>
		<?php
	 		}
		?>
	
  		<?php
 		if($_SESSION['MM_RolID'] == 3 || $_SESSION['MM_RolID']==2)
 		{
  		?><li <?php if(isset($_GET['page']) && $_GET['page']=='detalle_compraangular'){ echo('style="background:#438AC8"');} ?> >
				 <a href="home.php?page=detalle_compraangular"><FONT SIZE="2">Compras</FONT></a>
			</li>
 	     	<li <?php if(isset($_GET['page']) && $_GET['page']=='requerimientos_lista'){ echo('style="background:#438AC8"');} ?> >
		  		<a href="home.php?page=requerimientos_lista"><FONT SIZE="2">Requerimientos</FONT></a>
			</li>
       	 	<li <?php if(isset($_GET['page']) && $_GET['page']=='proveedores_lista'){ echo('style="background:#438AC8"');} ?> >
		  		<a href="home.php?page=proveedores_lista"><FONT SIZE="2">Proveedores</FONT></a>
			</li>		
			<li <?php if(isset($_GET['page']) && $_GET['page']=='ordenes_lista'){ echo('style="background:#438AC8"');} ?> >
		 		<a href="home.php?page=ordenes_lista" ><FONT SIZE="2">Cotizaciones <span>(<?php echo($por_cotizar); ?>)</span></FONT></a>
			</li>			
		 	<li <?php if(isset($_GET['page']) && $_GET['page']=='ordenar_compra_lista'){ echo('style="background:#438AC8"');} ?> >
		  		<a href="home.php?page=ordenar_compra_lista"><FONT SIZE="2">Ordenes</FONT></a>
			</li>
			<li <?php if(isset($_GET['page']) && $_GET['page']=='contratos'){ echo('style="background:#438AC8"');} ?> >
		  		<a href="home.php?page=contratos/contratos_listar"><FONT SIZE="2">Contratos</FONT></a>
			</li>
			<!--
			<li <?php if(isset($_GET['page']) && $_GET['page']=='ordenar_convenio_lista'){ echo('style="background:#438AC8"');} ?> >
		  		<a href="home.php?page=ordenar_convenio_lista"><FONT SIZE="2">Listado de productos</FONT></a>
			</li>
			<li <?php if(isset($_GET['page']) && $_GET['page']=='ordenar_nocotiza_lista'){ echo('style="background:#438AC8"');} ?> >
		  		<a href="home.php?page=ordenar_nocotiza_lista"><FONT SIZE="2">Lista de Ordenes Compra Directa</FONT></a>
			</li>
			<li <?php if(isset($_GET['page']) && $_GET['page']=='evaluaciones_calificaciones'){ echo('style="background:#438AC8"');} ?> >
  				<a href="home.php?page=evaluaciones_calificaciones"><FONT SIZE="2">Evaluaciones y calificaciones</FONT></a>
			</li>			
 			
 			<li <?php if(isset($_GET['page']) && $_GET['page']=='valores'){ echo('style="background:#438AC8"');} ?> >
  				<a href="home.php?page=valores"><FONT SIZE="2">Datos</FONT></a>
			</li>  -->       		
		<?php
 		 }
 		?>

 		<?php
 		/*
 			if($_SESSION['MM_RolID']==2)
 			{
  		?>
 	     	<li <?php if(isset($_GET['page']) && $_GET['page']=='requerimientos_lista'){ echo('style="background:#438AC8"');} ?> >
		  		<a href="home.php?page=requerimientos_lista"><FONT SIZE="2">Requerimientos</FONT></a>
			</li>
       	 	<li <?php if(isset($_GET['page']) && $_GET['page']=='proveedores_lista'){ echo('style="background:#438AC8"');} ?> >
		  		<a href="home.php?page=proveedores_lista"><FONT SIZE="2">Proveedores</FONT></a>
			</li>		
			<li <?php if(isset($_GET['page']) && $_GET['page']=='cotizar'){ echo('style="background:#438AC8"');} ?> >
		 		<a href="cotizarjson.php" target="_blank"><FONT SIZE="2">Cotizaciones <span>(<?php echo($por_cotizar); ?>)</span></FONT></a>
			</li>
			<li <?php if(isset($_GET['page']) && $_GET['page']=='ordenes_lista'){ echo('style="background:#438AC8"');} ?> >
		  		<a href="home.php?page=ordenes_lista"><FONT SIZE="2">Listado Cotizaci&oacute;n</FONT></a>
			</li>
		 	<li <?php if(isset($_GET['page']) && $_GET['page']=='ordenar_compra_lista'){ echo('style="background:#438AC8"');} ?> >
		  		<a href="home.php?page=ordenar_compra_lista"><FONT SIZE="2">Ordenes</FONT></a>
			</li>
			<li <?php if(isset($_GET['page']) && $_GET['page']=='ordenar_convenio_lista'){ echo('style="background:#438AC8"');} ?> >
		  		<a href="home.php?page=ordenar_convenio_lista"><FONT SIZE="2">Listado de productos</FONT></a>
			</li>
			<li <?php if(isset($_GET['page']) && $_GET['page']=='ordenar_nocotiza_lista'){ echo('style="background:#438AC8"');} ?> >
		  		<a href="home.php?page=ordenar_nocotiza_lista"><FONT SIZE="2">Lista de Ordenes Compra Directa</FONT></a>
			</li>
			<li <?php if(isset($_GET['page']) && $_GET['page']=='evaluaciones_calificaciones'){ echo('style="background:#438AC8"');} ?> >
  				<a href="home.php?page=evaluaciones_calificaciones"><FONT SIZE="2">Evaluaciones y calificaciones</FONT></a>
			</li>			
 			<li <?php if(isset($_GET['page']) && $_GET['page']=='valores'){ echo('style="background:#438AC8"');} ?> >
  				<a href="home.php?page=valores"><FONT SIZE="2">Datos</FONT></a>
			</li>  
        <?php
 			} */
  		?>

   		<?php
 			if($_SESSION['MM_RolID']==1)
 			{
  		?>
			
		   	<li <?php if(isset($_GET['page']) && $_GET['page']=='admiistrar'){ echo('style="background:#438AC8"');} ?> >
		 		<a href="home.php?page=listar_usuarios">Usuarios</a>
			</li>
        	<li <?php if(isset($_GET['page']) && $_GET['page']=='admiistrar'){ echo('style="background:#438AC8"');} ?> >
		 		<a href="home.php?page=conf_poa&tipo=b">Poa</a>
			</li>
  			<li <?php if(isset($_GET['page']) && $_GET['page']=='admiistrar'){ echo('style="background:#438AC8"');} ?> >
		 		<a href="home.php?page=fechas_requerimiento">Parametro Fechas</a>
			</li>
 			<li <?php if(isset($_GET['page']) && $_GET['page']=='admiistrar'){ echo('style="background:#438AC8"');} ?> >
		 		<a href="home.php?page=persona_requerimiento_especial">Requerimiento especial</a>
			</li>
              <li <?php if(isset($_GET['page']) && $_GET['page']=='admiistrar'){ echo('style="background:#438AC8"');} ?> >
		 		<a href="home.php?page=ordenes/ordenar_compra">Ordenes</a>
			</li>			
  		<?php
  		}
  		?>  
		<li> 
			 	<a href="home.php?page=cambiar_password"><FONT SIZE="2">Cambiar Clave</FONT></a>
			</li> 
 
			<li> 
			 	<a href="logout.php"><FONT SIZE="2">Salir</FONT></a>
			</li> 
</ul>


