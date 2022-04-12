<?php
include '../scripts/conexion.php';
if(isset($_GET['id_cart'])){
	$id=$_GET['id'];+
	$cart=$_GET['id_cart'];
	$sql="SELECT co.idcontactabilidad,cc.idcontactabilidadcartera,co.contactabilidad,c.cartera  
			FROM contactabilidad_carteras cc
			JOIN contactabilidad co ON cc.idcontactabilidad=co.idcontactabilidad
			JOIN carteras c ON cc.idcartera=c.idcartera
			JOIN cuentas ct ON c.idcartera=ct.idcartera
			JOIN proveedores p ON c.idproveedor=p.idproveedor																																
			WHERE cc.idestado=1 AND co.idestado=1  AND c.idcartera='$cart' AND ct.idcliente='$id'
			GROUP BY cc.idcontactabilidad ";
	$result = $db->Execute($sql);
	$n=1;
	while(!$result->EOF){
		if($n!=1){ echo ",";}
		
		echo $result->fields['idcontactabilidad']."-".$result->fields['contactabilidad'];
		$result->MoveNext();

		$n++;
	}
	mysql_free_result($result->_queryID);
	$db->Close();
	
}


?>