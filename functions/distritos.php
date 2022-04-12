<?php
session_start();
if (!isset($_SESSION["user"]) || $_SESSION["user"] == "") {
	header("Location: login.php");
}
require '../define_con.php';

$id=$_GET['id_dpto'];
$id2=$_GET['id_prov'];
$vSQL ="SELECT * FROM ubigeos WHERE coddpto = '$id' AND codprov = '$id2' AND coddist != '00'";
$rs = $db->Execute($vSQL);

$distritos = $rs->GetArray();

foreach($distritos as $k => $v) {
	$_distritos[] = "new Array('".$distritos[$k]["coddist"]."', '".utf8_encode($distritos[$k]["nombre"])."')";
}
 if (count($distritos) > 0) {
	$distritos = "new Array(".implode(", ", $_distritos).");";
	echo $distritos; 
 } else {
	echo "new Array(0);";
}
mysql_free_result($rs->_queryID);
$db->Close();
?>