<?php

	include '../scripts/conexion.php';
	
	$id=$_GET['id'];
	
	$sql="Select login from usuarios where idusuario='$id'";
	$consulta =$db->Execute($sql);
	
			$login=$consulta->fields['login'];
			$sql2="UPDATE usuarios set login='$login',clave=md5('$login') where idusuario='$id'";
			$db->Execute($sql2);							
			
	

?>
