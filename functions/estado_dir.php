<?php
//include '../scripts/conexion.php';
include '../define_con.php';
$db->debug=true;
		$iddir=$_GET['iddir'];
		$idcli=$_GET['idcli'];
		
	   	$result = $db->Execute("UPDATE direcciones SET prioridad='1' WHERE iddireccion='$iddir'");
		$result = $db->Execute("UPDATE direcciones SET prioridad='0' WHERE idcliente='$idcli' AND iddireccion!='$iddir'");
           
		//mysql_free_result($result->_queryID);
	
		$db->Close();
?>