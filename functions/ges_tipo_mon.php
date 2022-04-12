<?php 
	include '../define_con.php';
	$id_m=$_GET['moneda'];
	$query=$db->Execute("SELECT idmoneda,monedas FROM monedas WHERE idmoneda='$id_m'");
	 while(!$query->EOF){
		echo $query->fields['idmoneda'].",".$query->fields['monedas'];
		$query->MoveNext();
	 }
	 mysql_free_result($query->_queryID);
	$db->Close();
	
?>