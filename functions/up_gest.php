<?php

	include '../scripts/conexion.php';
	session_start();
	$id_user = $_SESSION['iduser'];
	$id_gest=$_GET['id_gest'];
	
	$sql="UPDATE gestiones SET impcomp='0' where idgestion='$id_gest'";
	
	$consulta =$db->Execute($sql);
	mysql_free_result($consulta->_queryID);
	$db->Close();
?>
