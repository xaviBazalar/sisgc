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

$archivo="dirAE7_$fecha.txt";
$fp = fopen($archivo, 'w');
	
	$db2= $db;
	$db=  $db_li; 
				
	$sql="CALL txt_dir_reg800('$fecha_g','$id_periodo')";


	$rp=$db->Execute($sql);
	
	while(!$rp->EOF){
		$linea=$rp->fields[0].$rp->fields[1].$rp->fields[2].$rp->fields[3].$rp->fields[4];
		
		$d1=utf8_decode($rp->fields[5]);
		if(strlen($d1)<80){
			$tot=80-(strlen($d1));
			$c="";
			for($i=1;$i<=$tot;$i++){
				$c.=' ';
			}
			$d1=(string)$d1.$c;
		}
		
		$d2=utf8_decode($rp->fields[6]);
		if(strlen($d2)<80){
			$tot=80-(strlen($d2));
			$c="";
			for($i=1;$i<=$tot;$i++){
				$c.=' ';
			}
			$d2=(string)$d2.$c;
		}
		
		$dist=$rp->fields[7];
		if(strlen($dist)<80){
			$tot=80-(strlen($dist));
			$c="";
			for($i=1;$i<=$tot;$i++){
				$c.=' ';
			}
			$dist=(string)$dist.$c;
		}
		
		$prov=$rp->fields[8];
		if(strlen($prov)<40){
			$tot=40-(strlen($prov));
			$c="";
			for($i=1;$i<=$tot;$i++){
				$c.=' ';
			}
			$prov=(string)$prov.$c;
		}
		
		$dpto=$rp->fields[9];
		if(strlen($dpto)<5){
			$tot=5-(strlen($dpto));
			$c="";
			for($i=1;$i<=$tot;$i++){
				$c.=' ';
			}
			$dpto=(string)$dpto.$c;
		}
		
		if(strlen($d1)<80){
			$tot=80-(strlen($d1));
			$c="";
			for($i=1;$i<=$tot;$i++){
				$c.=' ';
			}
			$d1=(string)$d1.$c;
		}
		$linea.=$d1.$d2.$dist.$prov.$dpto.$rp->fields[10].$rp->fields[11].$rp->fields[12];
		$total=strlen($linea);
		if($total==359){
			fwrite($fp, $linea);
			fwrite($fp , chr(13).chr(10));
		}else{
			$db2->Execute("insert into debug_interfaces (idinterface,linea,longitud,fecha) values (4,'$linea',$total,'$fecha_g')");
		}

		$rp->MoveNext();
		
	}	





$db->Close();
echo "</br></br></br>";
echo "<font color='red'>Inicio de reporte :</font>".$ti=date("H:i:s")." - <font color='blue'>Fin de Reporte:</font>".$tf=date("H:i:s")." - ";
echo "Guardar:<a href='functions/guardar_como.php?name=$archivo' target='blank'>$archivo</a>";	


?>
