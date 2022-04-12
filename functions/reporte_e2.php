<?php
//Reporte de Encuesta 

//include '../scripts/conexion.php';
include '../define_con.php';
	$archivo='encuesta_claro.txt';
	$fp = fopen($archivo, 'w');
	if($_GET['cartera']==37){
		$titulo="Preguntas\tRpt1\tRpt2\tRpt3\tRpt4\tRpt5\tRpt6\tRpt7\tRpt8\tRpt9";
	}else{
		$titulo="Preguntas\tRpt1\tRpt2\tRpt3\tRpt4\tRpt5\tRpt6\tRpt7\tRpt8";
	}
	$body=$titulo;
	fwrite($fp,$body);
	fwrite($fp , chr(13).chr(10));
	for($i=1;$i<=9;$i++){
		if($i==25 or $i==26 or $i==27 or $i==28 or $i==29 or $i==30){ continue;}
		
		$columna="";
		$nro_e="";
		
		if($_GET['cartera']==37){
			$columna=",SUM(p$i=8) `9` ";
			$nro_e="2";
		}
		
		$sql="SELECT 
			SUM(p$i=1) `1`,
			SUM(p$i=2) `2`,
			SUM(p$i=3) `3`,
			SUM(p$i=4) `4`,
			SUM(p$i=5) `5`,
			SUM(p$i=6) `6`,
			SUM(p$i=7) `7`,
			SUM(p$i=8) `8`$columna
			
		FROM 
		encuesta_claro$nro_e ";
		//echo $sql;
		//return false;
		$result = $db->Execute($sql);
		$te=$result->fields[5];
		while(!$result->EOF){
			if($_GET['cartera']==37 and $i==1 ){
				$cont="Pregunta Nro-$i:\t ".$result->fields[0]."\t".$result->fields[1]."\t".$result->fields[2]."\t".$result->fields[3]."\t".$result->fields[4]."\t".$result->fields[5]."\t".$result->fields[6];
			}
			
			if($_GET['cartera']==37 and $i==3 ){
				$cont="Pregunta Nro-$i:\t ".$result->fields[0]."\t".$result->fields[1]."\t".$result->fields[2]."\t".$result->fields[3]."\t".$result->fields[4]."\t".$result->fields[5]."\t".$result->fields[6]."\t".$result->fields[7]."\t".$result->fields[8];
			}
			
			if($_GET['cartera']==37 and ($i==7 or $i==8)){
				$cont="Pregunta Nro-$i:\t ".$result->fields[0]."\t".$result->fields[1]."\t".$result->fields[2]."\t".$result->fields[3]."\t".$result->fields[4];
			}
		
			if($_GET['cartera']==37 and $i==9 ){
				$cont="Pregunta Nro-$i:\t ".$result->fields[0]."\t".$result->fields[1]."\t".$result->fields[2]."\t".$result->fields[3]."\t".$result->fields[4]."\t".$result->fields[5]."\t".$result->fields[6];
			}
			if($_GET['cartera']==37 and ($i==2 or $i==4 or $i==5 or $i==6)){
				$cont="Pregunta Nro-$i:\t ".$result->fields[0]."\t".$result->fields[1]."\t".$result->fields[2]."\t".$result->fields[3];
			}
		
			if($i==2 and $_GET['cartera']!=37){
				$cont="Pregunta Nro-$i:\t ".$result->fields[0]."\t".$result->fields[1]."\t".$result->fields[2]."\t".$result->fields[3]."\t".$result->fields[4]."\t".$result->fields[5]."\t".$result->fields[6]."\t".$result->fields[7];

			}
			
			if($_GET['cartera']==34){
				$cont="Pregunta Nro-$i:\t ".$result->fields[0]."\t".$result->fields[1]."\t".$result->fields[2]."\t".$result->fields[3]."\t".$result->fields[4];
			}
			
			if($i==1 and $_GET['cartera']==34){
				$total=$result->fields[0]+$result->fields[1]+$result->fields[2]+$result->fields[3]+$result->fields[4];
			}
			fwrite($fp, $cont);
			fwrite($fp , chr(13).chr(10));	
			$result->MoveNext();
			
		}
	}
	
	$salto="\n\n";
	
	fwrite($fp,$salto);
	fwrite($fp , chr(13).chr(10));
	
	$pie="Total de encuestados:\t\t".$total;
	
	fwrite($fp , $pie);
	fwrite($fp , chr(13).chr(10));
	fclose($fp);
echo "Reporte Encuesta Claro:<a href='guardar_como.php?name=encuesta_claro.txt' target='blank'>Click para descargar</a><br/>";	

$db->Close();
?>