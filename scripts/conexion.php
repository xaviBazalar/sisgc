<?php
/*session_start();
if (!isset($_SESSION["user"]) || $_SESSION["user"] == "") {
	header("Location: login.php");
}*/
//include '../class/adodb_lite/adodb.inc.php';
include '../class/adodb5/adodb.inc.php';

$db=  &ADONewConnection('mysqli');
$db->Connect("127.0.0.1", "root", "kala@2022", "sis_gestion"); 

$db_li=  &NewADOConnection('mysqli');
$db_li->Connect("127.0.0.1", "root", "kala@2022", "sis_gestion"); 

?>
