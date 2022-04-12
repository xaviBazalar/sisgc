<?php
/** Error reporting */
ini_set('memory_limit', '-1');
set_time_limit(1800);
error_reporting(E_ALL);
session_start();
date_default_timezone_set('Europe/London');

/** PHPExcel */
require_once '../class/PHPExcel.php';
require_once '../class/PHPExcel/IOFactory.php';
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
$objPHPExcel = new PHPExcel();



//$desde=$_GET['cod'];
// Set properties
		$objPHPExcel->getProperties()->setCreator("Kobsa - Gestion")
									 ->setLastModifiedBy("Kobsa - Gestion")
									 ->setTitle("Office 2007 XLSX Test Document")
									 ->setSubject("Office 2007 XLSX Test Document")
									 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
									 ->setKeywords("office 2007 openxml php")
									 ->setCategory("Test result file");

	 // Add some data
		$objPHPExcel->getDefaultStyle()->getFont()
			->setName('Arila')
			->setSize(8);
		

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
	
		$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('a3', "GESTOR")
							->setCellValue('b3', "TIPO")
							->setCellValue('c3', 1)
							->setCellValue('d3', 2)
							->setCellValue('e3', 3)
							->setCellValue('f3', 4)
							->setCellValue('g3', 5)
							->setCellValue('h3', 6)
							->setCellValue('i3', 7)
							->setCellValue('j3', 8)
							->setCellValue('k3', 9)
							->setCellValue('l3', 10)
							->setCellValue('m3', 11)
							->setCellValue('n3', 12)
							->setCellValue('o3', 13)
							->setCellValue('p3', 14)
							->setCellValue('q3', 15)
							->setCellValue('r3', 16)
							->setCellValue('s3', 17)
							->setCellValue('t3', 18)
							->setCellValue('u3', 19)
							->setCellValue('v3', 20)
							->setCellValue('w3', 21)
							->setCellValue('x3', 22)
							->setCellValue('y3', 23)
							->setCellValue('z3', 24)
							->setCellValue('aa3', 25)
							->setCellValue('ab3', 26)
							->setCellValue('ac3', 27)
							->setCellValue('ad3', 28)
							->setCellValue('ae3', 29)
							->setCellValue('af3', 30)
							->setCellValue('ag3', 31)
							->setCellValue('ah3', "TOTAL")
							;
			$n=3;

		foreach ($datos_res as $clave => $valor){
			++$n;
			$pri=$n;	
			$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('a'.$n, $usuarios[$clave])->setCellValue('b'.$n, "TOTAL");
			$objPHPExcel->getActiveSheet()->getStyle("A$n")->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setRGB('D3D3D3')	;				
			++$n;
			for($x=1;$x<=4;$x++){
				if($x==1){$le="A";}
				if($x==2){$le="B";}
				if($x==3){$le="C";}
				if($x==4){$le="D";}
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('b'.$n, $le)
							->setCellValue('c'.$n, $valor["$ano-$mes-01"][$le])
							->setCellValue('d'.$n, $valor["$ano-$mes-02"][$le])
							->setCellValue('e'.$n, $valor["$ano-$mes-03"][$le])
							->setCellValue('f'.$n, $valor["$ano-$mes-04"][$le])
							->setCellValue('h'.$n, $valor["$ano-$mes-06"][$le])
							->setCellValue('j'.$n, $valor["$ano-$mes-08"][$le])
							->setCellValue('k'.$n, $valor["$ano-$mes-09"][$le])
							->setCellValue('l'.$n, $valor["$ano-$mes-10"][$le])
							->setCellValue('m'.$n, $valor["$ano-$mes-11"][$le])
							->setCellValue('n'.$n, $valor["$ano-$mes-12"][$le])
							->setCellValue('o'.$n, $valor["$ano-$mes-13"][$le])
							->setCellValue('p'.$n, $valor["$ano-$mes-14"][$le])
							->setCellValue('q'.$n, $valor["$ano-$mes-15"][$le])
							->setCellValue('r'.$n, $valor["$ano-$mes-16"][$le])
							->setCellValue('s'.$n, $valor["$ano-$mes-17"][$le])
							->setCellValue('t'.$n, $valor["$ano-$mes-18"][$le])
							->setCellValue('u'.$n, $valor["$ano-$mes-19"][$le])
							->setCellValue('v'.$n, $valor["$ano-$mes-20"][$le])
							->setCellValue('w'.$n, $valor["$ano-$mes-21"][$le])
							->setCellValue('x'.$n, $valor["$ano-$mes-22"][$le])
							->setCellValue('y'.$n, $valor["$ano-$mes-23"][$le])
							->setCellValue('z'.$n, $valor["$ano-$mes-24"][$le])
							->setCellValue('aa'.$n, $valor["$ano-$mes-25"][$le])
							->setCellValue('ab'.$n, $valor["$ano-$mes-26"][$le])
							->setCellValue('ac'.$n, $valor["$ano-$mes-27"][$le])
							->setCellValue('ad'.$n, $valor["$ano-$mes-28"][$le])
							->setCellValue('ae'.$n, $valor["$ano-$mes-29"][$le])
							->setCellValue('af'.$n, $valor["$ano-$mes-30"][$le])
							->setCellValue('ag'.$n, $valor["$ano-$mes-31"][$le])
							->setCellValue('ah'.$n, '=SUM(C'.$n.':AG'.$n.')')
							;
				++$n;
			}
			//--$n;
			$j=$pri+1;
			$k=$pri+2;
			$g=$pri+3;
			$h=$pri+4;
			$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('b'.$n, "EFECTIVIDAD")
							->setCellValue('c'.$n, "=(C$j*1.6+C$k*1.4+C$g*1.2+C$h*0.5)/100")
							->setCellValue('d'.$n, "=(D$j*1.6+D$k*1.4+D$g*1.2+D$h*0.5)/100")
							->setCellValue('e'.$n, "=(E$j*1.6+E$k*1.4+E$g*1.2+E$h*0.5)/100")
							->setCellValue('f'.$n, "=(F$j*1.6+F$k*1.4+F$g*1.2+F$h*0.5)/100")
							->setCellValue('g'.$n, "=(G$j*1.6+G$k*1.4+G$g*1.2+G$h*0.5)/100")
							->setCellValue('h'.$n, "=(H$j*1.6+H$k*1.4+H$g*1.2+H$h*0.5)/100")
							->setCellValue('i'.$n, "=(I$j*1.6+I$k*1.4+I$g*1.2+I$h*0.5)/100")
							->setCellValue('j'.$n, "=(J$j*1.6+J$k*1.4+J$g*1.2+J$h*0.5)/100")
							->setCellValue('k'.$n, "=(K$j*1.6+K$k*1.4+K$g*1.2+K$h*0.5)/100")
							->setCellValue('l'.$n, "=(L$j*1.6+L$k*1.4+L$g*1.2+L$h*0.5)/100")
							->setCellValue('m'.$n, "=(M$j*1.6+M$k*1.4+M$g*1.2+M$h*0.5)/100")
							->setCellValue('n'.$n, "=(N$j*1.6+N$k*1.4+N$g*1.2+N$h*0.5)/100")
							->setCellValue('o'.$n, "=(O$j*1.6+O$k*1.4+O$g*1.2+O$h*0.5)/100")
							->setCellValue('p'.$n, "=(P$j*1.6+P$k*1.4+P$g*1.2+P$h*0.5)/100")
							->setCellValue('q'.$n, "=(Q$j*1.6+Q$k*1.4+Q$g*1.2+Q$h*0.5)/100")
							->setCellValue('r'.$n, "=(R$j*1.6+R$k*1.4+R$g*1.2+R$h*0.5)/100")
							->setCellValue('s'.$n, "=(S$j*1.6+S$k*1.4+S$g*1.2+S$h*0.5)/100")
							->setCellValue('t'.$n, "=(T$j*1.6+T$k*1.4+T$g*1.2+T$h*0.5)/100")
							->setCellValue('u'.$n, "=(U$j*1.6+U$k*1.4+U$g*1.2+U$h*0.5)/100")
							->setCellValue('v'.$n, "=(V$j*1.6+V$k*1.4+V$g*1.2+V$h*0.5)/100")
							->setCellValue('w'.$n, "=(W$j*1.6+W$k*1.4+W$g*1.2+W$h*0.5)/100")
							->setCellValue('x'.$n, "=(X$j*1.6+X$k*1.4+X$g*1.2+X$h*0.5)/100")
							->setCellValue('y'.$n, "=(Y$j*1.6+Y$k*1.4+Y$g*1.2+Y$h*0.5)/100")
							->setCellValue('z'.$n, "=(Z$j*1.6+Z$k*1.4+Z$g*1.2+Z$h*0.5)/100")
							->setCellValue('aa'.$n, "=(AA$j*1.6+AA$k*1.4+AA$g*1.2+AA$h*0.5)/100")
							->setCellValue('ab'.$n, "=(AB$j*1.6+AB$k*1.4+AB$g*1.2+AB$h*0.5)/100")
							->setCellValue('ac'.$n, "=(AC$j*1.6+AC$k*1.4+AC$g*1.2+AC$h*0.5)/100")
							->setCellValue('ad'.$n, "=(AD$j*1.6+AD$k*1.4+AD$g*1.2+AD$h*0.5)/100")
							->setCellValue('ae'.$n, "=(AE$j*1.6+AE$k*1.4+AE$g*1.2+AE$h*0.5)/100")
							->setCellValue('af'.$n, "=(AF$j*1.6+AF$k*1.4+AF$g*1.2+AF$h*0.5)/100")
							->setCellValue('ag'.$n, "=(AG$j*1.6+AG$k*1.4+AG$g*1.2+AG$h*0.5)/100")
							->setCellValue('ah'.$n, '=SUM(C'.$n.':AG'.$n.')/'.$promedio)
							;
			//$objPHPExcel->getActiveSheet()->getStyle("B$n:AH$n")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
			$objPHPExcel->getActiveSheet()->getStyle("B$n:AH$n")->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setRGB('D3D3D3')	;			
			$objPHPExcel->getActiveSheet()->getStyle('c'.$n.':ah'.$n)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);
			
			
			$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('c'.$pri, '=SUM(C'.($pri+1).':C'.($n-1).')')
							->setCellValue('d'.$pri, '=SUM(D'.($pri+1).':D'.($n-1).')')
							->setCellValue('e'.$pri, '=SUM(E'.($pri+1).':E'.($n-1).')')
							->setCellValue('f'.$pri, '=SUM(F'.($pri+1).':F'.($n-1).')')
							->setCellValue('g'.$pri, '=SUM(G'.($pri+1).':G'.($n-1).')')
							->setCellValue('h'.$pri, '=SUM(H'.($pri+1).':H'.($n-1).')')
							->setCellValue('i'.$pri, '=SUM(I'.($pri+1).':I'.($n-1).')')
							->setCellValue('j'.$pri, '=SUM(J'.($pri+1).':J'.($n-1).')')
							->setCellValue('k'.$pri, '=SUM(K'.($pri+1).':K'.($n-1).')')
							->setCellValue('l'.$pri, '=SUM(L'.($pri+1).':L'.($n-1).')')
							->setCellValue('m'.$pri, '=SUM(M'.($pri+1).':M'.($n-1).')')
							->setCellValue('n'.$pri, '=SUM(N'.($pri+1).':N'.($n-1).')')
							->setCellValue('o'.$pri, '=SUM(O'.($pri+1).':O'.($n-1).')')
							->setCellValue('p'.$pri, '=SUM(P'.($pri+1).':P'.($n-1).')')
							->setCellValue('q'.$pri, '=SUM(Q'.($pri+1).':Q'.($n-1).')')
							->setCellValue('r'.$pri, '=SUM(R'.($pri+1).':R'.($n-1).')')
							->setCellValue('s'.$pri, '=SUM(S'.($pri+1).':S'.($n-1).')')
							->setCellValue('t'.$pri, '=SUM(T'.($pri+1).':T'.($n-1).')')
							->setCellValue('u'.$pri, '=SUM(U'.($pri+1).':U'.($n-1).')')
							->setCellValue('v'.$pri, '=SUM(V'.($pri+1).':V'.($n-1).')')
							->setCellValue('w'.$pri, '=SUM(W'.($pri+1).':W'.($n-1).')')
							->setCellValue('x'.$pri, '=SUM(X'.($pri+1).':X'.($n-1).')')
							->setCellValue('y'.$pri, '=SUM(Y'.($pri+1).':Y'.($n-1).')')
							->setCellValue('z'.$pri, '=SUM(Z'.($pri+1).':Z'.($n-1).')')
							->setCellValue('aa'.$pri, '=SUM(AA'.($pri+1).':AA'.($n-1).')')
							->setCellValue('ab'.$pri, '=SUM(AB'.($pri+1).':AB'.($n-1).')')
							->setCellValue('ac'.$pri, '=SUM(AC'.($pri+1).':AC'.($n-1).')')
							->setCellValue('ad'.$pri, '=SUM(AD'.($pri+1).':AD'.($n-1).')')
							->setCellValue('ae'.$pri, '=SUM(AE'.($pri+1).':AE'.($n-1).')')
							->setCellValue('af'.$pri, '=SUM(AF'.($pri+1).':AF'.($n-1).')')
							->setCellValue('ag'.$pri, '=SUM(AG'.($pri+1).':AG'.($n-1).')')
							->setCellValue('ah'.$pri, '=SUM(AH'.($pri+1).':AH'.($n-1).')')
							;
			$objPHPExcel->getActiveSheet()->getRowDimension($pri+1)->setOutlineLevel(2);
			$objPHPExcel->getActiveSheet()->getRowDimension($pri+2)->setOutlineLevel(2);
			$objPHPExcel->getActiveSheet()->getRowDimension($pri+3)->setOutlineLevel(2);
			$objPHPExcel->getActiveSheet()->getRowDimension($pri+4)->setOutlineLevel(2);
			
			$objPHPExcel->getActiveSheet()->getRowDimension($pri+1)->setVisible(false);
			$objPHPExcel->getActiveSheet()->getRowDimension($pri+2)->setVisible(false);	
			$objPHPExcel->getActiveSheet()->getRowDimension($pri+3)->setVisible(false);	
			$objPHPExcel->getActiveSheet()->getRowDimension($pri+4)->setVisible(false);
						
			$objPHPExcel->getActiveSheet()->getStyle("B$pri:AH$pri")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
			$objPHPExcel->getActiveSheet()->getStyle("B$pri:AH$pri")->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setRGB('808080'); 
			
			++$n;				
			$objPHPExcel->getActiveSheet()->getStyle("A$n:AH$n")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
			$objPHPExcel->getActiveSheet()->getStyle("A$n:AH$n")->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setRGB('FFFFFF');	

		} 		
						
			$objPHPExcel->getActiveSheet()->getStyle('A3:AH3')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
			$objPHPExcel->getActiveSheet()->getStyle('A3:AH3')->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setRGB('000000');								
				
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setAutoSize(true);
		
		$objPHPExcel->getActiveSheet()->setTitle('rpp_llamadas');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

/* Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="archivo.xlsx"');
header('Cache-Control: max-age=0');*/
header('Content-Disposition: attachment;filename="rpp_llamadas.xls"');
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
//$objWriter->save('archivo.xls');  
$objWriter->save('php://output');
//header("Location: archivo.xls");
exit;

?>