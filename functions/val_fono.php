<?php

	include '../scripts/conexion.php';
	
	$id_val=$_GET['val'];
	$id_fono=$_GET['idfono'];
	//$sql="UPDATE telefonos SET idvalidacion='$id_val' where idtelefono='$id_fono'";
	$sql1="select idtelefono from telefono_validacion where idtelefono=$id_fono";
	$flag=$db->Execute($sql1);
	if($flag->fields['idtelefono']==""){
		$sql="INSERT into telefono_validacion (idtelefono,idvalidacion) values('$id_fono','$id_val')";
	}else{
		$sql="UPDATE telefono_validacion SET idvalidacion='$id_val' where idtelefono='$id_fono'";
	}
	$consulta =$db->Execute($sql);
	//mysql_free_result($consulta->_queryID);
	$db->Close();
?>
