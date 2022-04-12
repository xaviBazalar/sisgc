<?php
ini_set('memory_limit', '-1');
set_time_limit(1800);
 
include '../scripts/conexion.php';

echo "<pre>";
//$db->debug=true;
$hora=Date("H:i:s");
$archivo='xls_prosegur_'.$hora.'.txt';

$fp = fopen($archivo, 'w');

$peri=$_GET['peri'];
$fecha=$_GET['fecha'];
	$db=  &NewADOConnection('mysqli');
	$db->Connect("localhost", "root", "g3st1onkb", "sis_gestion"); 
				
	$sql="CALL xls_prosegur('$fecha',$peri)";

	$rp=$db->Execute($sql);
	$titulo="COD_ORACLE\tNOMBRE_CLIENTE\tNOMBRE_OPERADOR\tLocal\tFECHA\tESTATUS\tSUB_STATUS\tCONTACTO_DETALLE\tTOTAL\tACTUAL\t1-30DIAS\t31-60DIAS\t61-90DIAS\t91-120DIAS\t121-180DIAS\t181aMAS";
	fwrite($fp, $titulo);
	fwrite($fp , chr(13).chr(10));
	
	while(!$rp->EOF){
		$dm=$rp->fields['diasmora'];
		
		$dato="\"".$rp->fields['idcliente']."\"\t";
		$dato.=$rp->fields['cliente']."\t";
		$dato.=$rp->fields['usuario']."\t";
		
		$local=explode("*",$rp->fields['observacion2']);
		$local=$local[1];
		
		$dato.="\"".$local."\"\t";
		$dato.=$rp->fields['fecha']."\t";
		$dato.=$rp->fields['resultado']."\t";
		$dato.=utf8_decode($rp->fields['justificacion'])."\t";
		$dato.=utf8_decode($rp->fields['observacion'])."\t";
		$dato.=$rp->fields['impven']."\t";
		$dato.="-\t";
		
		if($dm>=0 and $dm<=30){ 	$dato.=$rp->fields['imptot']."\t\t\t\t\t";}
		if($dm>=31 and $dm<=60){ 	$dato.="\t".$rp->fields['imptot']."\t\t\t\t";}
		if($dm>=61 and $dm<=90){ 	$dato.="\t\t\t".$rp->fields['imptot']."\t\t";}
		if($dm>=91 and $dm<=180){ 	$dato.="\t\t\t\t".$rp->fields['imptot']."\t";}
		if($dm>=181){ 				$dato.="\t\t\t\t\t".$rp->fields['imptot'];}
		
		
		fwrite($fp, $dato);
		fwrite($fp , chr(13).chr(10));
		$rp->MoveNext();
		
	}	

$db->Close();
echo "</br></br></br>";
echo "<font color='red'>Inicio de reporte :</font>".$ti=date("H:i:s")." - <font color='blue'>Fin de Reporte:</font>".$tf=date("H:i:s")." - ";
echo "Guardar:<a href='functions/guardar_como.php?name=$archivo' target='blank'>$archivo</a>";	


?>
