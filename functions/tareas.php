<?php

	include '../scripts/conexion.php';
	session_start();
	$id_user = $_SESSION['iduser'];
	$id=$_GET['id'];
	$id_gest=$_GET['id_gest'];
	$r_ges=$_GET['idre'];
	$r_ges=explode("-",$r_ges);
	$r_ges=$r_ges[1];
	$fec_t=$_GET['fec_t'];
	$hor_t=$_GET['hor_t'];
	$com_t=$_GET['com_t'];
	
	$sql="INSERT INTO tareas (idcliente,idresultado,idgestion,fecha,hora,tarea,usureg) 
		  VALUES ('$id','$r_ges','$id_gest','$fec_t','$hor_t','$com_t','$id_user')";
	
	$consulta =$db->Execute($sql);
	mysql_free_result($consulta->_queryID);
	$db->Close();

?>

