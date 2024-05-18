<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname = "localhost";
$database = "REPSO2";
$username = "root";
$password = "";
$conexion = mysqli_connect($hostname, $username, $password,$database); 
//mysqli_select_db($conexion,$database);
$conexion->set_charset("utf8"); 

//if($conexion){echo"Conexion Realizada";}

/*
function inactivity() {
    $v_minutos = 20;
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > (60*$v_minutos))) {
        // last request was more than 30 minutes ago
		$v_redirect = 'http://'. $_SERVER[HTTP_HOST] . '/pruebas/index.php';
        session_unset();     // unset $_SESSION variable for the run-time 
        session_destroy();   // destroy session data in storage
        //header("index.php");		
		
		?>
		 <html>
		  <script>
			alert('La conexion ha caducado por inactividad durante =<?php echo $v_minutos;?> minuto(s)');
		    window.location.href="<?php echo $v_redirect; ?>";
		  </script>
		 </html>
		<?php
		//header($v_redirect);
    }

    $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
}

*/
?>