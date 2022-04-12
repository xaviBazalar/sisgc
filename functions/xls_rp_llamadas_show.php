<?php
/** Error reporting */
ini_set('memory_limit', '-1');
set_time_limit(1800);
error_reporting(E_ALL);
session_start();
date_default_timezone_set('Europe/London');



/** PHPExcel */
//require_once '../class/PHPExcel.php';
//require_once '../class/PHPExcel/IOFactory.php';
include '../scripts/conexion.php';

$db->debug=true;
if(isset($_GET['peri']) and  $_GET['peri']!=""){
	$peri=$_GET['peri'];
	
	$fc=$db->Execute("SELECT year(fecini) ano, month(fecini) mes from periodos where idperiodo=$peri");	
	$ano=$fc->fields['ano'];
	$mes=$fc->fields['mes'];
	if($mes<10){$mes=(string) "0".$mes;}
}else{
	return false;
}

function getMonthDays($Month, $Year)
{
  
   if( is_callable("cal_days_in_month"))
   {
      return cal_days_in_month(CAL_GREGORIAN, $Month, $Year);
   }
   else
   {
      // Obtenemos el ultima dia del mes
      return date("d",mktime(0,0,0,$Month+1,0,$Year));
   }
}
//Obtenemos la cantidad de días 
//echo getMonthDays(4, 2012);

for($o=1;$o<=getMonthDays($mes, $ano);$o++){
	if(date("l", mktime(0, 0, 0, $mes, $o,$ano)) == "Sunday" || date("l", mktime(0, 0, 0, $mes, $o, $ano)) == "Saturday")
	{
		++$domingo;
	}
}
$promedio=getMonthDays($mes, $ano)-$domingo;
// Create new PHPExcel object




//$desde=$_GET['cod'];
// Set properties
		
		

		$cartera=$_GET['cart'];

		
		$datos_res= array();	
		$usuarios = array();
		for($i=1;$i<=getMonthDays($mes, $ano);$i++){
			
			if($i<10){
				$nro=(string)"0".$i;
			}else{
				$nro=$i;
			}
						
			$query=$db->Execute("
								SELECT u.usuario,rs.usureg idusuario,rs.fecges,COUNT(*) total,
								SUM(rs.idresultado=1) YP,
								SUM(rs.idresultado=2) PP,
								SUM(rs.idresultado=17) Renu,
								SUM(rs.idresultado=7) DP,
								SUM(rs.idresultado=9) Recl,
								SUM(rs.idresultado=10) F,
								SUM(rs.idresultado=11) MT,
								SUM(rs.idresultado=12) NC,
								SUM(rs.idresultado=13) IC,
								SUM(rs.idresultado=6) Seg
								FROM
								(
									SELECT g.usureg,c.idcliente,g.idresultado,r.resultado,g.fecges 
									FROM gestiones g
									JOIN cuentas c ON g.idcuenta=c.idcuenta 
									JOIN resultados r ON g.idresultado=r.idresultado
									WHERE g.fecges LIKE '$ano-$mes-$nro'  AND c.idcartera IN (1)
									AND g.peso=(SELECT MIN(peso) FROM gestiones WHERE idcuenta=c.idcuenta  AND fecges LIKE '$ano-$mes-$nro')
									GROUP BY g.usureg,g.fecreg ORDER BY g.usureg,g.fecges,r.idresultado
								) AS rs
								JOIN usuarios u ON rs.usureg=u.idusuario	
								
								GROUP BY rs.usureg ORDER BY 1
		
							");

				
				while(!$query->EOF){
					$usuarios[$query->fields['idusuario']]=$query->fields['usuario'];
					$datos_res[$query->fields['idusuario']][$query->fields['fecges']]['YP']=$query->fields['YP'];
					$datos_res[$query->fields['idusuario']][$query->fields['fecges']]['PP']=$query->fields['PP'];
					$datos_res[$query->fields['idusuario']][$query->fields['fecges']]['Renu']=$query->fields['Renu'];
					$datos_res[$query->fields['idusuario']][$query->fields['fecges']]['DP']=$query->fields['DP'];
					$datos_res[$query->fields['idusuario']][$query->fields['fecges']]['Recl']=$query->fields['Recl'];
					$datos_res[$query->fields['idusuario']][$query->fields['fecges']]['F']=$query->fields['F'];
					$datos_res[$query->fields['idusuario']][$query->fields['fecges']]['MT']=$query->fields['MT'];
					$datos_res[$query->fields['idusuario']][$query->fields['fecges']]['NC']=$query->fields['NC'];
					$datos_res[$query->fields['idusuario']][$query->fields['fecges']]['IC']=$query->fields['IC'];
					$datos_res[$query->fields['idusuario']][$query->fields['fecges']]['Seg']=$query->fields['Seg'];
					$datos_res[$query->fields['idusuario']][$query->fields['fecges']]['TOTAL']=$query->fields['total'];
					$query->MoveNext();
				}
		}
		echo "<pre>";
		//var_dump($datos_res);
		//return false;
		echo "<div id='design1'><table width='100%' border='1' cellspacing='0'>
				<tr>
					<th>GESTOR</th>
					<th>TIPO</th>";
		for($r=1;$r<=31;$r++){
			echo "<th>$r</th>";
		}
		
		echo "<th>TOTAL</th><tr>";

			$n=3;

		foreach ($datos_res as $clave => $valor){
			++$n;
			$pri=$n;	
			/*var_dump($valor);
			return false;*/			
			++$n;
			for($x=1;$x<=10;$x++){
				if($x==1){$le="Ya Pago";$le2='YP';}
				if($x==2){$le="Promesa de Pago";$le2='PP';}
				if($x==3){$le="Renuente";$le2='Renu';}
				if($x==4){$le="Dificultad de Pago";$le2='DP';}
				if($x==5){$le="Reclamo";$le2='Recl';}
				if($x==6){$le="Fallecido / Invalidez";$le2='F';}
				if($x==7){$le="Mensaje a Terceros";$le2='MT';}
				if($x==8){$le="No Contacto";$le2='NC';}
				if($x==9){$le="Ilocalizado Call";$le2='IC';}
				if($x==10){$le="Seguimiento";$le2='Seg';}
	
				
				if($x==1){$usr=$usuarios[$clave];}else{$usr="";}
				
				echo "
							<tr>
								<td align='left'>".$usr."</td>
								<td align='center'>$le</td>
								<td>".$valor["$ano-$mes-01"][$le2]."</td>
								<td>".$valor["$ano-$mes-02"][$le2]."</td>
								<td>".$valor["$ano-$mes-03"][$le2]."</td>
								<td>".$valor["$ano-$mes-04"][$le2]."</td>
								<td>".$valor["$ano-$mes-05"][$le2]."</td>
								<td>".$valor["$ano-$mes-06"][$le2]."</td>
								<td>".$valor["$ano-$mes-07"][$le2]."</td>
								<td>".$valor["$ano-$mes-08"][$le2]."</td>
								<td>".$valor["$ano-$mes-09"][$le2]."</td>
								<td>".$valor["$ano-$mes-10"][$le2]."</td>
								<td>".$valor["$ano-$mes-11"][$le2]."</td>
								<td>".$valor["$ano-$mes-12"][$le2]."</td>
								<td>".$valor["$ano-$mes-13"][$le2]."</td>
								<td>".$valor["$ano-$mes-14"][$le2]."</td>
								<td>".$valor["$ano-$mes-15"][$le2]."</td>
								<td>".$valor["$ano-$mes-16"][$le2]."</td>
								<td>".$valor["$ano-$mes-17"][$le2]."</td>
								<td>".$valor["$ano-$mes-18"][$le2]."</td>
								<td>".$valor["$ano-$mes-19"][$le2]."</td>
								<td>".$valor["$ano-$mes-20"][$le2]."</td>
								<td>".$valor["$ano-$mes-21"][$le2]."</td>
								<td>".$valor["$ano-$mes-22"][$le2]."</td>
								<td>".$valor["$ano-$mes-23"][$le2]."</td>
								<td>".$valor["$ano-$mes-24"][$le2]."</td>
								<td>".$valor["$ano-$mes-25"][$le2]."</td>
								<td>".$valor["$ano-$mes-26"][$le2]."</td>
								<td>".$valor["$ano-$mes-27"][$le2]."</td>
								<td>".$valor["$ano-$mes-28"][$le2]."</td>
								<td>".$valor["$ano-$mes-29"][$le2]."</td>
								<td>".$valor["$ano-$mes-30"][$le2]."</td>
								<td>".$valor["$ano-$mes-31"][$le2]."</td>";
							
					$tot_fill=$valor["$ano-$mes-01"]['TOTAL'];
					
					/*for($p=2;$p<=31;$p++){
						if($p<10){$nro="0".$p;}else{$nro=$p;}
						$tot_fill=$tot_fill+$valor["$ano-$mes-$nro"][$le];
					}*/
					
					echo    "<td align='center'>$tot_fill</td><tr>";
							
				++$n;
			}
			/*Totales*/
			
			echo "
							<tr style='color:red;font-weight:bold;'>
								<td align='left'></td>
								<td align='center'>TOTAL</td>";
			$tot="";					
			/*for($p=1;$p<=31;$p++){
						if($p<10){$nro="0".$p;}else{$nro=$p;}
							echo "<td>".($valor["$ano-$mes-$nro"]['A']+$valor["$ano-$mes-$nro"]['B']+$valor["$ano-$mes-$nro"]['C']+$valor["$ano-$mes-$nro"]['D'])."</td>";
						$tot=$tot+$valor["$ano-$mes-$nro"]['A']+$valor["$ano-$mes-$nro"]['B']+$valor["$ano-$mes-$nro"]['C']+$valor["$ano-$mes-$nro"]['D'];	
			}*/					
				
			echo    "<td align='center'>$tot</td><tr>";
			
			/*FIn totales*/
	//---------------------------------------------------------------------------------------------------------
			/*Porcentajes*/
			
			echo "
							<tr style='color:blue;font-weight:bold;'>
								<td align='left'></td>
								<td align='center'>EFECTIVIDAD</td>";
			$tot="";					
			/*for($p=1;$p<=31;$p++){
						if($p<10){$nro="0".$p;}else{$nro=$p;}
							echo "<td>".round(($valor["$ano-$mes-$nro"]['A']*1.6+$valor["$ano-$mes-$nro"]['B']*1.4+$valor["$ano-$mes-$nro"]['C']*1.2+$valor["$ano-$mes-$nro"]['D']*0.5))."%</td>";
						$tot=($tot+round($valor["$ano-$mes-$nro"]['A']*1.6+$valor["$ano-$mes-$nro"]['B']*1.4+$valor["$ano-$mes-$nro"]['C']*1.2+$valor["$ano-$mes-$nro"]['D']*0.5));	
			}*/					
				
			echo    "<td align='center'>".round($tot/$promedio)."%"."</td><tr>";
			
			/*Fin Porcentajes*/
			
			echo "<tr><td colspan='34' style='background-color:gainsboro;'>&nbsp;</td></tr>";
		} 		
		
		echo "</table></div>";
						
						


?>