<?php

session_start();

ini_set('memory_limit', '-1');
set_time_limit(1800);
/** Error reporting */
error_reporting(E_ALL);

date_default_timezone_set('Europe/London');


include '../define_con.php';

		$peri=$_GET['peri'];
		
		$db=  &NewADOConnection('mysqli');
		$db->Connect("localhost", "root", "g3st1onkb", "sis_gestion"); 
		
		if(isset($_GET['fecini'])){
			$ini=$_GET['fecini'];
		}
		
		if(isset($_GET['t_cartera'])){
			$tp_cartera=$_GET['t_cartera'];
		}
		$sql="CALL reporte_gest_diario_tn($peri,'$ini',$tp_cartera)	";
		//echo $sql;
		$query=$db->Execute($sql);

		$fp=fopen('f_gestion_tn.txt','w');


		//idllam
		

		require_once 'equivalencias_tn.php';
			while(!$query->EOF){
				++$n;
				$contenido="";
				$ctas = explode("-",$query->fields['idcuenta']);
				$cta=$ctas[0];
				$res=10-(strlen($cta));
				for($i=0;$i<$res;$i++){
				 $contenido=$contenido."0";
				}
				
				$cont=$contenido.$cta;
				//$cont.="=\"".$cta."\"\t";
				$cont.=detalle_tn($query->fields['idresultado'],$query->fields['idjustificacion']);
				
				if(strlen($query->fields['observacion'])>100){
					$obs=substr($query->fields['observacion'],0,99);
				}else{$obs=$query->fields['observacion'];}
				
				$cont.=$obs."\t";
				//echo strlen($cont)."<br/>";
				//$cont.=strlen($cont)
				fwrite($fp , $cont);
				fwrite($fp , chr(13).chr(10));
				$query->MoveNext();
			}
			fclose($fp);
			echo "<br/><br/>";
			if($n==0){
				echo "No tiene Cuentas en el Periodo";
			}else{
				echo "Reporte Gestion TN:&nbsp;&nbsp;<a href='functions/guardar_como.php?name=f_gestion_tn.txt' target='blank'>Click para descargar</a><br/>
				  Total de Registros ($n)";	
			}
				
	$db->Close();
	
?>
