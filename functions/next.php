<?php
	session_start();
	
	if($_GET['idCl']==$_SESSION['cli_actual']){
		$nr=$_GET['mn'];
		$next=$_SESSION['next'][$nr];

		for($ns=$_GET['mn'];$next==$_SESSION['cli_actual'];$nr++){
			$next=$_SESSION['next'][$nr];
		}
		
		$ruta="../index.php?gestion=1&idCl=$next&mn=$nr";
		header("Location: $ruta");
	}else{
		$nr=$_GET['mn'];
		
		$ruta="../index.php?gestion=1&idCl=".$_GET['idCl']."&mn=$nr";
		
		header("Location: $ruta");
	}
?>