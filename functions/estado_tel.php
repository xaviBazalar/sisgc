<?php
//include '../scripts/conexion.php';
include '../define_con.php';
		$db->debug=true;
		$iddir=$_GET['idtel'];
		$idcli=$_GET['idcli'];
		
	   	$result = $db->_query("UPDATE telefonos SET prioridad='1' WHERE idtelefono='$iddir'");
		$result = $db->_query("UPDATE telefonos SET prioridad='0' WHERE idcliente='$idcli' AND idtelefono!='$iddir'");
           
		mysql_free_result($result->_queryID);
	
		$db->Close();
?>