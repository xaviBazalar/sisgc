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
$db_t=  &ADONewConnection('mysql');
$db_t->Connect("localhost", "root", "g3st1onkb", "sis_gestion"); 	

	$id_cartera=$_SESSION['cartera'];
	//$id_cartera=9;
	$id_periodo=$_SESSION['periodo'];
	//$id_periodo=12;
	$id_camp=$_GET['campana'];
	//$id_camp=5;
	//$db_t->debug=true;
	$up=$db_t->Execute("SELECT c.idcuenta,c.idcliente,cp.idestado FROM cuentas c
						JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta
						WHERE c.idcartera=$id_cartera AND cp.idestado=0 AND cp.idperiodo=$id_periodo
						GROUP BY c.idcuenta");
	
	while(!$up->EOF){
		++$n;
		
		$db_c=  &ADONewConnection('mysql');
		$con = $db_c->Connect("192.168.50.16", "kobbases", "kob_210911", "orionc7_bases");
		//$db_c->debug=true;
		$cli=$up->fields['idcliente'];
		$sql="UPDATE ori_base set activo=0 where Contacto='$cli' and id_ori_campana='$id_camp' ";
		
		$query=$db_c->Execute($sql);
		$up->MoveNext();
	}
	
	echo "Datos Actualizados Correctamente";
		
	
?>
