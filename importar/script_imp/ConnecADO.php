<?php
include 'class/adodb_lite/adodb.inc.php';
    $db=  &ADONewConnection('mysqli');

    $con = $db->PConnect("127.0.0.1", "root", "kala@2022", "sis_gestion");
    $db2=  &ADONewConnection('mysqli');
	$db2->Connect("127.0.0.1", "root", "kala@2022", "sis_gestion"); 

	$db3=  &ADONewConnection('mysqli');
	$db3->Connect("127.0.0.1", "root", "kala@2022", "sis_gestion");

    $n = 0;
?>
