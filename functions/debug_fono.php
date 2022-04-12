<?php
//include '../scripts/conexion.php';
include '../define_con.php';

$db->debug=true;
		
		
	   	$result = $db->Execute("SELECT idtelefono,telefono FROM telefonos WHERE telefono LIKE ' %'");
		while(!$result->EOF){
			$tel=$result->fields['telefono'];
			$id=$result->fields['idtelefono'];
			$tel_changed = str_replace(" ","",$tel);
			$db->Execute("UPDATE telefonos set telefono='$tel_changed' where idtelefono='$id'");
			$result->MoveNext();
		}
		
           
		mysql_free_result($result->_queryID);
	
		$db->Close();
?>