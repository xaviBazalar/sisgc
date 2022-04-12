<?php
//include 'class/adodb_lite/adodb.inc.php';
include 'class/adodb5/adodb.inc.php';
	
$db=  NewADOConnection('mysql');
$db->Connect("localhost", "root", "g3st1onkb", "sis_gestion"); 
//$db->debug=true;
$db2=  NewADOConnection('mysql');
$db2->Connect("localhost", "root", "g3st1onkb", "sis_gestion"); 



?>