<?php
//include 'class/adodb_lite/adodb.inc.php';
include 'class/adodb5/adodb.inc.php';
	
$db=  NewADOConnection('mysqli');
$db->Connect("127.0.0.1", "root", "kala@2022", "sis_gestion"); 


$db2=  NewADOConnection('mysqli');
$db2->Connect("127.0.0.1", "root", "kala@2022", "sis_gestion"); 


?>