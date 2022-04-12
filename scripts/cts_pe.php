<?php
/*session_start();
if (!isset($_SESSION["user"]) || $_SESSION["user"] == "") {
	header("Location: login.php");
}*/
include '../class/adodb_lite/adodb.inc.php';
$db->debug=true;
$db=  &ADONewConnection('mysql');
$db->debug= true;
$db->Connect("localhost", "root", "g3st1onkb", "sis_gestion");



	

	$n=1;
	echo "<pre>";
	//$db->debug=true;
	$id=array();
	//while(!$sql->EOF){
	//$id=$sql->fields['idcuenta'];
		//$id[$n]=$sql->fields['idcuenta'];
	//$db->Execute("DELETE from cuenta_periodos where idcuenta='$id'");
		//$sql->MoveNext();
		//$n++;
	//}
	
	/*$sql=$db->Execute(" ");
				
	while(!$sql->EOF){
		$id[$n]=$sql->fields['idcuenta'];
		$sql->MoveNext();
		$n++;
	}*/
	
	/*$sql=$db->Execute("SELECT c.* FROM cuentas c
					JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta
					WHERE c.idcartera='8' AND cp.idperiodo='7' ");
					
	while(!$sql->EOF){
		if(in_array($sql->fields['idcuenta'],$id)){
		
		}else{
			echo $sql->fields['idcuenta']."<br />";
		}
		$sql->MoveNext();
		
	}*/
				
	//echo $total=count($id);
	//for($i=0;$i<=$total;$i++){
		//$db->Execute("DELETE FROM cuentas  WHERE idcartera='9'");
//	}
	//for($i=0;$i<=$total;$i++){
		//$id_cl=$id[$i+1];
		//$db->Execute("DELETE FROM clientes  WHERE idcliente='$id_cl'");
//	}
	//echo $total;
	//var_dump($id);

	
?>
