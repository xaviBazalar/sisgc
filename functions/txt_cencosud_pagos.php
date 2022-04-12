<?php
//return false;


ini_set('memory_limit', '-1');
set_time_limit(1800);
 
include '../scripts/conexion.php';

echo "<pre>";

//$db->debug=true;

//$fecha=date("Ymd");

$fecha=explode("-",$_GET['fec']);

if($fecha[2]<10){$fecha[2]=(string)"0".$fecha[2];}

$fecha=$fecha[0].$fecha[1].$fecha[2];

$fecha_g=$_GET['fec'];
$id_periodo=$_GET['peri'];

if($fecha_g=="" and $id_periodo==""){
	$fecha_g=date("Y-m-d");
	$fecha=date("Ymd");
	$año=date("Y");
	$mes=date("m");
	$periodo = $db->Execute("SELECT idperiodo,periodo  FROM periodos WHERE fecini LIKE '$año-$mes%'");
	$id_periodo=$periodo->fields['idperiodo'];
}

$archivo="600AE7_$fecha.txt";
$fp = fopen($archivo, 'w');
	$db2=  $db;
	$db=  $db_li;
				
	$sql="CALL txt_pdp_reg600('$fecha_g','$id_periodo')";


	$rp=$db->Execute($sql);
	
	while(!$rp->EOF){
		$linea=$rp->fields[0].$rp->fields[1].$rp->fields[2].$rp->fields[3].$rp->fields[4].$rp->fields[5].$rp->fields[6].$rp->fields[7].$rp->fields[8].$rp->fields[9].$rp->fields[10];
		$total=strlen($linea);
		if($total==78){
			fwrite($fp, $linea);
			fwrite($fp , chr(13).chr(10));
		}else{
			$db2->Execute("insert into debug_interfaces (idinterface,linea,longitud,fecha) values (2,'$linea',$total,'$fecha_g')");
		}
		$rp->MoveNext();
		
	}	





$db->Close();
echo "</br></br></br>";
echo "<font color='red'>Inicio de reporte :</font>".$ti=date("H:i:s")." - <font color='blue'>Fin de Reporte:</font>".$tf=date("H:i:s")." - ";
echo "Guardar:<a href='functions/guardar_como.php?name=$archivo' target='blank'>$archivo</a>";	


?>
