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

$id_cartera=$_SESSION['cartera'];

//$id_cartera=6;
$camp=$_GET['campana'];
//$camp=3;
$campana= $db->Execute("SELECT fec_trans FROM campana WHERE idcampana='$camp'");
$fec_up=$campana->fields['fec_trans'];

$db_c=  &ADONewConnection('mysql');
$db_c->debug=true;
$con = $db_c->Connect("192.168.50.16", "kobbases", "kob_210911", "orionc7_bases");
$db_c->debug=false;
		/*$sql="SELECT *,DATE(FechaProceso) fec_pro,TIME(FechaProceso) hora  FROM ori_base WHERE id_ori_campana=$camp AND PreEstado BETWEEN 2 AND 3 and PreFlag!=0 
			AND fechaProceso>'$fec_up'
			order by FechaProceso";*/
		
	/*	$sql="SELECT *,DATE(FechaPre) fec_pro,TIME(FechaPre) hora  
			FROM ori_base WHERE id_ori_campana=$camp AND PreEstado BETWEEN 2 AND 3 AND PreFlag!=0 
			AND fechaPre>'$fec_up'
			ORDER BY FechaPre";*/

		$sql="SELECT *,DATE(FechaProceso) fec_pro,TIME(FechaProceso) hora  
			FROM ori_base WHERE id_ori_campana=$camp AND ProEstado BETWEEN 2 AND 3 AND ProFlag!=0 
			AND FechaProceso>'$fec_up'
			ORDER BY FechaProceso"	;
		$n=0;
		/*Resultado
		12  no_contacto
		*/
		
		/*Justificacion
		121 ocupado
		122 no_contesta
		*/
		
		
		$query=$db_c->Execute($sql);
		$db_g=  &ADONewConnection('mysql');
		$db_g->Connect("localhost", "root", "g3st1onkb", "sis_gestion"); 
		//$db_g->debug=true;
		while(!$query->EOF){
				
				++$n;
				$cli=$query->fields['Contacto'];
		//$db_g->debug=true;
				$rpta=$db_g->Execute("
							SELECT c.* FROM cuentas c
							JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta AND cp.idperiodo=22
							WHERE 
							idcliente='$cli' and idcartera='$id_cartera' limit 0,1
							");
				//return false;
				$fono=$query->fields['TelefonoMarcado'];
				while(!$rpta->EOF){
					$cliente_fono=$rpta->fields['idcliente'];
					$cta=$rpta->fields['idcuenta'];
					if($query->fields['ProEstado']==2){ $cont=13;$just=121;$peso=79;}// ocupado
					if($query->fields['ProEstado']==3){ $cont=12;$just=122;$peso=78;}// No contestan 
					$fec_g=$query->fields['fec_pro'];
					$hor_g=$query->fields['hora'];
					
					
					$db_t=  &ADONewConnection('mysql');
					$db_t->Connect("localhost", "root", "g3st1onkb", "sis_gestion"); 	
					$tel=$db_t->Execute("select idtelefono  from telefonos where telefono='$fono' and idcliente='$cliente_fono' ");
					$fon=$tel->fields['idtelefono'];
					
					$sql="INSERT INTO gestiones 
					(idcuenta,idcontactabilidad,idresultado,idjustificacion,observacion,fecges,horges,idactividad,idtelefono,usureg,peso)
					 VALUES
					 ('$cta','$cont','12','$just','Predictivo','$fec_g','$hor_g','1','$fon','999','$peso')
					";

					$db_t->Execute("Update cuentas set u_fecges='$fec_g' where idcuenta='$cta'");
					//$db_t->debug=true;
					$db_t->Execute($sql);
					//echo $sql." <br/>";
					$fecha_u=$fec_g." ".$hor_g;
					//echo $fecha_u."<br/>";
					$rpta->MoveNext(); 
				}
				
				$db_t->Execute("update campana set fec_trans='$fecha_u' where idcampana='$camp'");
				$query->MoveNext();
			}
		echo "<br><br>Total Gestiones Transferidas : ".$n;
		
	
?>

