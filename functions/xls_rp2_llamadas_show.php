<?php
/** Error reporting */
ini_set('memory_limit', '-1');
set_time_limit(1800);
error_reporting(E_ALL);
session_start();
setlocale(LC_ALL,"es_ES@euro","es_ES");

/** PHPExcel */
require_once '../class/PHPExcel.php';
require_once '../class/PHPExcel/IOFactory.php';
include '../scripts/conexion.php';


function getMonthDays($Month, $Year)
{
  
   if( is_callable("cal_days_in_month"))
   {
      return cal_days_in_month(CAL_GREGORIAN, $Month, $Year);
   }
   else
   {

      return date("d",mktime(0,0,0,$Month+1,0,$Year));
   }
}

/*
for($o=1;$o<=getMonthDays($mes, $ano);$o++){
	if(date("l", mktime(0, 0, 0, $mes, $o,$ano)) == "Sunday" || date("l", mktime(0, 0, 0, $mes, $o, $ano)) == "Saturday")
	{
		++$domingo;
	}
}
$promedio=getMonthDays($mes, $ano)-$domingo;
*/
	
		$datos_res= array();	
		$usuarios = array();

			
		
			$query=$db->Execute("
								SELECT u.idusuario,u.usuario,fecha,horaentrada 
									FROM asistencias a
									JOIN usuarios u ON a.idusuario=u.idusuario
									WHERE u.idnivel=2 AND fecha BETWEEN '2012-07-21' AND '2012-08-20'
									GROUP BY u.idusuario,fecha
							");

				
				while(!$query->EOF){
					$usuarios[$query->fields['idusuario']]=$query->fields['usuario'];
					$datos_res[$query->fields['idusuario']][$query->fields['fecha']]=$query->fields['horaentrada'];
					$query->MoveNext();
				}
		//}
		
		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getProperties()->setCreator("Kobsa - Gestion")
									 ->setLastModifiedBy("Kobsa - Gestion")
									 ->setTitle("Office 2007 XLSX Test Document")
									 ->setSubject("Office 2007 XLSX Test Document")
									 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
									 ->setKeywords("office 2007 openxml php")
									 ->setCategory("Test result file");
		$r=2;
		
		$objPHPExcel->getDefaultStyle()->getFont()
			->setName('Calibri')
			->setSize(8);
			
			
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('c'.$r, "DATOS DEL TRABAJADOR");
		for($i=21;$i<=getMonthDays(07, 2012);$i++){
		
				if($i==21){$l="d$r";}
				if($i==22){$l="e$r";}
				if($i==23){$l="f$r";}
				if($i==24){$l="g$r";}
				if($i==25){$l="h$r";}
				if($i==26){$l="i$r";}
				if($i==27){$l="j$r";}
				if($i==28){$l="k$r";}
				if($i==29){$l="l$r";}
				if($i==30){$l="m$r";}
				if($i==31){$l="n$r";}
				
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($l, "$i-".strtoupper(strftime("%B", strtotime("07/$i/2012"))));	
		}
			
		for($i=1;$i<=getMonthDays(8, 2012);$i++){
				if($i==1){$l="o$r";}
				if($i==2){$l="p$r";}
				if($i==3){$l="q$r";}
				if($i==4){$l="r$r";}
				if($i==5){$l="s$r";}
				if($i==6){$l="t$r";}
				if($i==7){$l="u$r";}
				if($i==8){$l="v$r";}
				if($i==9){$l="w$r";}
				if($i==10){$l="x$r";}
				if($i==11){$l="y$r";}
				if($i==12){$l="z$r";}
				if($i==13){$l="aa$r";}
				if($i==14){$l="ab$r";}
				if($i==15){$l="ac$r";}
				if($i==16){$l="ad$r";}
				if($i==17){$l="ae$r";}
				if($i==18){$l="af$r";}
				if($i==19){$l="ag$r";}
				if($i==20){$l="ah$r";}
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($l, "$i-".strtoupper(strftime("%B", strtotime("08/$i/2012"))));
				if($i==20){break;}
		}
		
		/*echo "<pre>";
		echo "<div id='design1'><table width='100%' border='1' cellspacing='0'>
				<tr>
					<th>GESTOR</th>
					";
		for($i=21;$i<=getMonthDays(05, 2012);$i++){
				echo "<th>$i-".strtoupper(strftime("%B", strtotime("05/$i/2012")))."</th>";
			}
			
		for($i=1;$i<=getMonthDays(06, 2012);$i++){
				echo "<th>$i-".strtoupper(strftime("%B", strtotime("06/$i/2012")))."</th>";
				if($i==20){break;}
		}
		
		echo "<tr>";

		$n=3;*/
		
		foreach ($datos_res as $clave => $valor){
			++$r;
			++$n;
			$pri=$n;	
			++$n;
			
				$usr=$usuarios[$clave];
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('c'.$r, "$usr");
				for($i=21;$i<=getMonthDays(07, 2012);$i++){
						$f=$i;
						if($f<10){$f=(string)"0".$f;}
						if($i==21){$l="d$r";}
						if($i==22){$l="e$r";}
						if($i==23){$l="f$r";}
						if($i==24){$l="g$r";}
						if($i==25){$l="h$r";}
						if($i==26){$l="i$r";}
						if($i==27){$l="j$r";}
						if($i==28){$l="k$r";}
						if($i==29){$l="l$r";}
						if($i==30){$l="m$r";}
						if($i==31){$l="n$r";}
						
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue($l,$valor["2012-07-$f"]);
				}
					
				for($i=1;$i<=getMonthDays(8, 2012);$i++){
						//echo "ok";
						$f=$i;
						if($f<10){$f=(string)"0".$f;}
						if($i==1){$l="o$r";}
						if($i==2){$l="p$r";}
						if($i==3){$l="q$r";}
						if($i==4){$l="r$r";}
						if($i==5){$l="s$r";}
						if($i==6){$l="t$r";}
						if($i==7){$l="u$r";}
						if($i==8){$l="v$r";}
						if($i==9){$l="w$r";}
						if($i==10){$l="x$r";}
						if($i==11){$l="y$r";}
						if($i==12){$l="z$r";}
						if($i==13){$l="aa$r";}
						if($i==14){$l="ab$r";}
						if($i==15){$l="ac$r";}
						if($i==16){$l="ad$r";}
						if($i==17){$l="ae$r";}
						if($i==18){$l="af$r";}
						if($i==19){$l="ag$r";}
						if($i==20){$l="ah$r";}
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue($l,$valor["2012-08-$f"]);
						if($i==20){break;}
				}
				/*echo "<tr>";
				echo "<td>$usr</td>";
				
				for($i=21;$i<=getMonthDays(05, 2012);$i++){
					$f=$i;
					if($f<10){$f=(string)"0".$f;}
					echo "<td>".$valor["2012-05-$f"]."</td>";
				}
				
				for($i=1;$i<=getMonthDays(06, 2012);$i++){
					$f=$i;
					if($f<10){$f=(string)"0".$f;}
					echo "<td>".$valor["2012-06-$f"]."</td>";
					if($i==20){break;}
				}
				
				echo "</tr>";*/
				
					$objPHPExcel->getActiveSheet()->getStyle("C$r:AH$r")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					$objPHPExcel->getActiveSheet()->getStyle("C$r:AH$r")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle("C$r:AH$r")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle("C$r:AH$r")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle("C$r:AH$r")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle("C$r:AH$r")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);			
				++$n;
		}
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(2);		
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(2);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
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
		
		
		$objPHPExcel->getActiveSheet()->getStyle('D2:AH'.$r.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);				
		$objPHPExcel->getActiveSheet()->getStyle('C2:AH2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('C2:AH2')->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setRGB('000000');	
		$objPHPExcel->getActiveSheet()->setTitle('REPORTE_INGRESO');	
			
		$objPHPExcel->setActiveSheetIndex(0);	
		//return false;
		header('Content-Disposition: attachment;filename="reporte_cobertura.xls"');
		//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
		//$objWriter->save('archivo.xls');  
		$objWriter->save('php://output');
		//header("Location: archivo.xls");
		exit;
		//echo "</table></div>";
						
						


?>