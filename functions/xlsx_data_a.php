<?php

session_start();
$iduser=$_SESSION['iduser'];
ini_set('memory_limit', '-1');
set_time_limit(1800);
/** Error reporting */
error_reporting(E_ALL);

date_default_timezone_set('Europe/London');

/** PHPExcel */

include '../define_con.php';

	$id_camp=$_GET['campana'];

		$db_c=  &ADONewConnection('mysql');
		$con = $db_c->Connect("192.168.50.16", "kobbases", "kob_210911", "orionc7_bases");
		//$db_c->debug=true;
		$sql="SELECT SUM(Proestado=0) faltante, SUM(Proestado!=0) recorrido FROM ori_base WHERE id_ori_campana='$id_camp' ";
		$rpta=$db_c->Execute($sql);
		//var_dump($db_c->fields);
	echo "Total por Recorrer: ".$rpta->fields['faltante']."<br/>";
	echo "Total Recorrido: ".$rpta->fields['recorrido']."<br/>";
		
	
?>
