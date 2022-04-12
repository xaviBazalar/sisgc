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
//$db->debug=true;



$camp=$_GET['campana'];

$db_c=  &ADONewConnection('mysql');
$con = $db_c->Connect("192.168.50.16", "kobbases", "kob_210911", "orionc7_bases");


		$sql="DELETE from ori_base where id_ori_campana=$camp"	;
		$n=0;
		//echo $sql;
		
		$query=$db_c->Execute($sql);
		
		if($query){
		  echo "La Base se elimino correctamente";
		}
		
	
?>

