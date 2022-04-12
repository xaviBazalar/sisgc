<?php
session_start();
$id = $_SESSION['iduser'];
$pass = md5($_POST['clave']);
include '../define_con.php';
$result = $db->Execute("UPDATE usuarios SET clave='$pass' WHERE idusuario=$id");

if($result->EOF){
						$año=date("Y");
						$mes=date("m");
						$periodo = $db->Execute("SELECT idperiodo,periodo  FROM periodos WHERE fecini LIKE '$año-$mes%'");
						
						$_SESSION['iduser']=$query->fields['idusuario'];
						$_SESSION['user']= $user->user;
						$_SESSION['nivel']=$query->fields['idnivel'];
						$_SESSION['periodo']=$periodo->fields['idperiodo'];
						$_SESSION['prove']=$query->fields['idproveedor'];
echo "true";
}else{
echo "false";
}
mysql_free_result($result->_queryID);
$db->Close();

?>