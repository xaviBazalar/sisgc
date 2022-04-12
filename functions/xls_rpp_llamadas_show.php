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


//$db->debug=true;

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
			
			$sql="SELECT u.usuario,rs.usureg idusuario,rs.fecges,rs.tipo,COUNT(*) total
								FROM
								(SELECT g.usureg,c.idcliente,g.idresultado,r.resultado,g.fecges, 
								CASE
									WHEN r.idresultado=46 THEN 'A'
									WHEN r.idresultado=2 THEN 'A'
									WHEN r.idresultado=47 THEN 'B'
									WHEN r.idresultado=45 THEN 'B'
									WHEN r.idresultado=11 THEN 'B'
									WHEN r.idresultado=23 THEN 'B'
									WHEN r.idresultado=12 THEN 'C'
									WHEN r.idresultado=18 THEN 'C'
									WHEN r.idresultado=17 THEN 'C'
									WHEN r.idresultado=13 THEN 'D'
									WHEN r.idresultado=6 THEN 'D'
								END tipo
								FROM gestiones g
								JOIN cuentas c ON g.idcuenta=c.idcuenta 
								JOIN resultados r ON g.idresultado=r.idresultado
								WHERE g.fecges LIKE '$ano-$mes-$nro'  AND c.idcartera IN ($cartera)
								AND g.peso=(SELECT MIN(peso) FROM gestiones WHERE idcuenta=c.idcuenta  AND fecges LIKE '$ano-$mes-$nro')
								GROUP BY g.usureg,c.idcliente,g.fecges ORDER BY g.usureg,g.fecges,r.idresultado) AS rs
								JOIN usuarios u ON rs.usureg=u.idusuario	
								WHERE tipo IS NOT NULL
								GROUP BY rs.usureg,rs.fecges,rs.tipo ORDER BY 1
						";
			
			$query=$db->Execute("
								SELECT u.usuario,rs.usureg idusuario,rs.fecges,rs.tipo,COUNT(*) total
								FROM
								(SELECT g.usureg,c.idcliente,g.idresultado,r.resultado,g.fecges, 
								CASE
									WHEN r.idresultado=46 THEN 'A'
									WHEN r.idresultado=2 THEN 'A'
									WHEN r.idresultado=47 THEN 'B'
									WHEN r.idresultado=45 THEN 'B'
									WHEN r.idresultado=11 THEN 'B'
									WHEN r.idresultado=23 THEN 'B'
									WHEN r.idresultado=12 THEN 'C'
									WHEN r.idresultado=18 THEN 'C'
									WHEN r.idresultado=17 THEN 'C'
									WHEN r.idresultado=13 THEN 'D'
									WHEN r.idresultado=6 THEN 'D'
								END tipo
								FROM gestiones g
								JOIN cuentas c ON g.idcuenta=c.idcuenta 
								JOIN resultados r ON g.idresultado=r.idresultado
								WHERE g.fecges LIKE '$ano-$mes-$nro'  AND c.idcartera IN ($cartera)
								AND g.peso=(SELECT MIN(peso) FROM gestiones WHERE idcuenta=c.idcuenta  AND fecges LIKE '$ano-$mes-$nro')
								GROUP BY g.usureg,c.idcliente,g.fecges ORDER BY g.usureg,g.fecges,r.idresultado) AS rs
								JOIN usuarios u ON rs.usureg=u.idusuario	
								WHERE tipo IS NOT NULL
								GROUP BY rs.usureg,rs.fecges,rs.tipo ORDER BY 1
		
							");

				
				while(!$query->EOF){
					$usuarios[$query->fields['idusuario']]=$query->fields['usuario'];
					$datos_res[$query->fields['idusuario']][$query->fields['fecges']][$query->fields['tipo']]=$query->fields['total'];
					$query->MoveNext();
				}
		}
	
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
					
			++$n;
			for($x=1;$x<=4;$x++){
				if($x==1){$le="A";}
				if($x==2){$le="B";}
				if($x==3){$le="C";}
				if($x==4){$le="D";}
				
				if($le=="A"){$usr=$usuarios[$clave];}else{$usr="";}
				
				echo "
							<tr>
								<td align='left'>".$usr."</td>
								<td align='center'>$le</td>
								<td>".$valor["$ano-$mes-01"][$le]."</td>
								<td>".$valor["$ano-$mes-02"][$le]."</td>
								<td>".$valor["$ano-$mes-03"][$le]."</td>
								<td>".$valor["$ano-$mes-04"][$le]."</td>
								<td>".$valor["$ano-$mes-05"][$le]."</td>
								<td>".$valor["$ano-$mes-06"][$le]."</td>
								<td>".$valor["$ano-$mes-07"][$le]."</td>
								<td>".$valor["$ano-$mes-08"][$le]."</td>
								<td>".$valor["$ano-$mes-09"][$le]."</td>
								<td>".$valor["$ano-$mes-10"][$le]."</td>
								<td>".$valor["$ano-$mes-11"][$le]."</td>
								<td>".$valor["$ano-$mes-12"][$le]."</td>
								<td>".$valor["$ano-$mes-13"][$le]."</td>
								<td>".$valor["$ano-$mes-14"][$le]."</td>
								<td>".$valor["$ano-$mes-15"][$le]."</td>
								<td>".$valor["$ano-$mes-16"][$le]."</td>
								<td>".$valor["$ano-$mes-17"][$le]."</td>
								<td>".$valor["$ano-$mes-18"][$le]."</td>
								<td>".$valor["$ano-$mes-19"][$le]."</td>
								<td>".$valor["$ano-$mes-20"][$le]."</td>
								<td>".$valor["$ano-$mes-21"][$le]."</td>
								<td>".$valor["$ano-$mes-22"][$le]."</td>
								<td>".$valor["$ano-$mes-23"][$le]."</td>
								<td>".$valor["$ano-$mes-24"][$le]."</td>
								<td>".$valor["$ano-$mes-25"][$le]."</td>
								<td>".$valor["$ano-$mes-26"][$le]."</td>
								<td>".$valor["$ano-$mes-27"][$le]."</td>
								<td>".$valor["$ano-$mes-28"][$le]."</td>
								<td>".$valor["$ano-$mes-29"][$le]."</td>
								<td>".$valor["$ano-$mes-30"][$le]."</td>
								<td>".$valor["$ano-$mes-31"][$le]."</td>";
							
					$tot_fill=$valor["$ano-$mes-01"][$le];
					
					for($p=2;$p<=31;$p++){
						if($p<10){$nro="0".$p;}else{$nro=$p;}
						$tot_fill=$tot_fill+$valor["$ano-$mes-$nro"][$le];
					}
					
					echo    "<td align='center'>$tot_fill</td><tr>";
							
				++$n;
			}
			/*Totales*/
			
			echo "
							<tr style='color:red;font-weight:bold;'>
								<td align='left'></td>
								<td align='center'>TOTAL</td>";
			$tot="";					
			for($p=1;$p<=31;$p++){
						if($p<10){$nro="0".$p;}else{$nro=$p;}
							echo "<td>".($valor["$ano-$mes-$nro"]['A']+$valor["$ano-$mes-$nro"]['B']+$valor["$ano-$mes-$nro"]['C']+$valor["$ano-$mes-$nro"]['D'])."</td>";
						$tot=$tot+$valor["$ano-$mes-$nro"]['A']+$valor["$ano-$mes-$nro"]['B']+$valor["$ano-$mes-$nro"]['C']+$valor["$ano-$mes-$nro"]['D'];	
			}					
				
			echo    "<td align='center'>$tot</td><tr>";
			
			/*FIn totales*/
	//---------------------------------------------------------------------------------------------------------
			/*Porcentajes*/
			
			echo "
							<tr style='color:blue;font-weight:bold;'>
								<td align='left'></td>
								<td align='center'>EFECTIVIDAD</td>";
			$tot="";					
			for($p=1;$p<=31;$p++){
						if($p<10){$nro="0".$p;}else{$nro=$p;}
							echo "<td>".round(($valor["$ano-$mes-$nro"]['A']*1.6+$valor["$ano-$mes-$nro"]['B']*1.4+$valor["$ano-$mes-$nro"]['C']*1.2+$valor["$ano-$mes-$nro"]['D']*0.5))."%</td>";
						$tot=($tot+round($valor["$ano-$mes-$nro"]['A']*1.6+$valor["$ano-$mes-$nro"]['B']*1.4+$valor["$ano-$mes-$nro"]['C']*1.2+$valor["$ano-$mes-$nro"]['D']*0.5));	
			}					
				
			echo    "<td align='center'>".round($tot/$promedio)."%"."</td><tr>";
			
			/*Fin Porcentajes*/
			
			echo "<tr><td colspan='34' style='background-color:gainsboro;'>&nbsp;</td></tr>";
		} 		
		
		echo "</table></div>";
						
						


?>