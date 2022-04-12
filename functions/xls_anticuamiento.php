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

	function restaFechas($dFecIni, $dFecFin)
	{
		$dFecIni = str_replace("-","",$dFecIni);
		$dFecIni = str_replace("/","",$dFecIni);
		$dFecFin = str_replace("-","",$dFecFin);
		$dFecFin = str_replace("/","",$dFecFin);
	 
		ereg( "([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})", $dFecIni, $aFecIni);
		ereg( "([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})", $dFecFin, $aFecFin);
	 
		$date1 = mktime(0,0,0,$aFecIni[2], $aFecIni[1], $aFecIni[3]);
		$date2 = mktime(0,0,0,$aFecFin[2], $aFecFin[1], $aFecFin[3]);
	 

		return round(($date2 - $date1) / (60 * 60 * 24));
	}
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
		
		$fec_ini=$_GET['fecini'];
		$fec_fin=$_GET['fecfin'];
		$cartera=$_GET['cartera'];
		$peri=$_GET['periodo'];
							
		$query=$db->Execute("
								SELECT c.idcliente,cl.cliente,p.producto,c.idcuenta,
								c.feccon fecmis,cp.fecven,cp.fecentfac fec_recep,c.idmoneda,m.simbolo,cp.diasmora,
								cp.imptot monto_factura,cp.impcap saldo,cp.impmin deuda,cp.grupo,cp.impfraini TC,
								CASE
									WHEN cp.fracuo=1 THEN 'Areq'
									WHEN cp.fracuo=2 THEN 'Cajam'
									WHEN cp.fracuo=3 THEN 'Chicl'
									WHEN cp.fracuo=4 THEN 'Cusc'
									WHEN cp.fracuo=5 THEN 'Hyo'
									WHEN cp.fracuo=6 THEN 'Ica'
									WHEN cp.fracuo=7 THEN 'Lima'
									WHEN cp.fracuo=8 THEN 'Piur'
									WHEN cp.fracuo=9 THEN 'Tacn'
									WHEN cp.fracuo=10 THEN 'Truj'
									WHEN cp.fracuo=11 THEN 'Tumb'
									WHEN cp.fracuo=12 THEN 'Otros'
								END provincia
								FROM cuentas c
								join monedas m on c.idmoneda=m.idmoneda
								JOIN clientes cl ON c.idcliente=cl.idcliente
								JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta AND cp.idestado=1
								JOIN productos p ON c.idproducto=p.idproducto
								JOIN carteras cr ON c.idcartera=cr.idcartera		
								JOIN proveedores pr ON cr.idproveedor=pr.idproveedor
								WHERE cr.idcartera=$cartera  and cp.idperiodo=$peri
								GROUP BY cp.idcuenta order by c.idcliente
		
							");
		$n=4;
		/*$datos_res= array();
				for($i=52;$i<=72;$i++){
					$datos_res[$i]=0;
				}
				while(!$query->EOF){
					$datos_res[$query->fields['idresultado']]=$query->fields['total'];
					$query->MoveNext();
				}*/
		/*echo "<pre>";
		var_dump($datos_res);
		return false;*/
	
		$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('b4', "RUC")
							->setCellValue('c4', "Cliente")
							->setCellValue('d4', "Tipo Documento")
							->setCellValue('e4', "Documento")
							->setCellValue('f4', "Fecha Emision")
							->setCellValue('g4', "Fecha Vencto")
							->setCellValue('h4', "Fecha Recepcion")
							->setCellValue('i4', "Mon")
							->setCellValue('j4', "TC")
							->setCellValue('k4', "Dias Mora")
							->setCellValue('l4', "Monto Facturado (Mon.Fun)")
							->setCellValue('m5', "Saldo (Mon.Fun)")
							->setCellValue('n4', "% Deuda")
							->setCellValue('o4', "Dpto.")
							->setCellValue('p4', "Central de Riesgo")
							->setCellValue('q4', "OBS")
							;
		$objPHPExcel->getActiveSheet()->getStyle('B4:Q4')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('B4:Q4')->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setRGB('808080');
		$total=0;
		while(!$query->EOF){
			++$n;
			if($n==5){
				$_SESSION['idcli']=$query->fields['idcliente'];
			}else if($query->fields['idcliente']!=$_SESSION['idcli']){
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('i'.$n, "SUB TOTAL")
							->setCellValue('m'.$n, number_format($total, 2));
						
				$objPHPExcel->getActiveSheet()->getStyle('B'.$n.':Q'.$n.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					$objPHPExcel->getActiveSheet()->getStyle('B'.$n.':Q'.$n.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('B'.$n.':Q'.$n.'')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('B'.$n.':Q'.$n.'')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('B'.$n.':Q'.$n.'')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				++$n;	
				
				$total=0;
				$_SESSION['idcli']=$query->fields['idcliente'];
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('b'.($n+1), "RUC")
							->setCellValue('c'.($n+1), "Cliente")
							->setCellValue('d'.($n+1), "Tipo Documento")
							->setCellValue('e'.($n+1), "Documento")
							->setCellValue('f'.($n+1), "Fecha Emision")
							->setCellValue('g'.($n+1), "Fecha Vencto")
							->setCellValue('h'.($n+1), "Fecha Recepcion")
							
							->setCellValue('j'.($n+1), "TC")
							->setCellValue('k'.($n+1), "Dias Mora")
							->setCellValue('l'.($n+1), "Monto Facturado (Mon.Fun)")
							->setCellValue('m'.($n+1), "Saldo (Mon.Fun)")
							->setCellValue('n'.($n+1), "% Deuda")
							->setCellValue('o'.($n+1), "Dpto.")
							->setCellValue('p'.($n+1), "Central de Riesgo")
							->setCellValue('q'.($n+1), "OBS")
							;
			$objPHPExcel->getActiveSheet()->getStyle('B'.($n+1).':Q'.($n+1))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
			$objPHPExcel->getActiveSheet()->getStyle('B'.($n+1).':Q'.($n+1))->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setRGB('808080');			
							
				$n=$n+2;
			}
			
			
			$idcliente=$query->fields['idcliente'];
			$cliente=$query->fields['cliente'];
			$tipo_doc=$query->fields['producto'];
			$doc=$query->fields['idcuenta'];
			$fec_e=$query->fields['fecmis'];
			$fec_v=$query->fields['fecven'];
			$fec_rp=$query->fields['fec_recep'];
			$moneda=$query->fields['simbolo'];
			$diasmora=$query->fields['diasmora'];
			$mon_fac=$query->fields['monto_factura'];
			$saldo=$query->fields['saldo'];
			$deuda=$query->fields['deuda'];
			$grup=$query->fields['grupo'];
			$TC=$query->fields['TC'];
			$prov=$query->fields['provincia'];
			
				$fi1=explode("-",date("Y-m-d"));
				$ff2=explode("-",$query->fields['fecven']);
				$fi=$fi1[2]."-".$fi1[1]."-".$fi1[0];
				$ff=$ff2[2]."-".$ff2[1]."-".$ff2[0];
				$diasmora=restaFechas($ff,$fi);
			
			if($fec_rp==0 || $fec_rp=="0000-00-00"){$fec_rp="";}
			
			$total=$total+$saldo;

			$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('b'.$n, $idcliente)
							->setCellValue('c'.$n, $cliente)
							->setCellValue('d'.$n, $tipo_doc)
							->setCellValue('e'.$n, $doc)
							->setCellValue('f'.$n, $fec_e)
							->setCellValue('g'.$n, $fec_v)
							->setCellValue('h'.$n, $fec_rp)
							->setCellValue('i'.$n, $moneda)
							->setCellValue('j'.$n, $TC)
							->setCellValue('k'.$n, $diasmora)
							->setCellValue('l'.$n, number_format($mon_fac,2))
							->setCellValue('m'.$n, number_format($saldo,2))
							->setCellValue('n'.$n, $deuda)
							->setCellValue('o'.$n, $prov)
						//	->setCellValue('p'.$n, " ")
							->setCellValue('q'.$n, $grup);
		
			$query->MoveNext();
		}	
		$objPHPExcel->getActiveSheet()->getStyle('B4:Q'.$n.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					$objPHPExcel->getActiveSheet()->getStyle('B4:Q'.$n.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('B4:Q'.$n.'')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('B4:Q'.$n.'')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('B4:Q'.$n.'')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);			
		/*$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('c4', ($datos_res[67]+$datos_res[52]+$datos_res[53]+$datos_res[68]+$datos_res[69]) )
							->setCellValue('c5', $datos_res[67])//encuesta completa
							->setCellValue('c6', $datos_res[52]) // no desea
							->setCellValue('c7', $datos_res[68]) // no se comunico
							->setCellValue('c8', $datos_res[69]) // no acerca a cat
							->setCellValue('c9', $datos_res[53]) // cita telefonica
							->setCellValue('c10', ($datos_res[70]+$datos_res[54]))
							->setCellValue('c11', $datos_res[70])
							->setCellValue('c12', $datos_res[54])
							->setCellValue('c13', ($datos_res[60]+$datos_res[57]+$datos_res[58]+$datos_res[59]+$datos_res[61]+$datos_res[62]+$datos_res[63]+$datos_res[72]))
							->setCellValue('c14',  $datos_res[60]) //ocupado
							->setCellValue('c15',  $datos_res[57]) // cortaron
							->setCellValue('c16',  $datos_res[58]) // grabadora
							->setCellValue('c17',  $datos_res[59]) //no contestan 
							->setCellValue('c18',  $datos_res[72]) // Fuera de servicio
							->setCellValue('c19',  $datos_res[61]) // Telfs / Lineas Inoperativas
							->setCellValue('c20',  $datos_res[62]) //Telfs no corresponden
							->setCellValue('c21',  $datos_res[63]) //Telfs no existen
							->setCellValue('c23',array_sum($datos_res))
							;*/
		

		
		/*$objPHPExcel->getActiveSheet()->getStyle('B2:D2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('B2:D2')->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setRGB('FF7F50');	
								
		$objPHPExcel->getActiveSheet()->getStyle('B4:D4')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('B4:D4')->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setRGB('FF7F50');

		$objPHPExcel->getActiveSheet()->getStyle('B10:D10')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('B10:D10')->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setRGB('FF7F50');

		$objPHPExcel->getActiveSheet()->getStyle('B13:D13')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('B13:D13')->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setRGB('FF7F50');

		$objPHPExcel->getActiveSheet()->getStyle('B23:D23')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('B23:D23')->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setRGB('FF7F50');	*/							
									
				

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

		$objPHPExcel->getActiveSheet()->setTitle('rpp_anticuamiento');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

/* Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="archivo.xlsx"');
header('Cache-Control: max-age=0');*/
header('Content-Disposition: attachment;filename="rpp_anticuamiento.xls"');
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
//$objWriter->save('archivo.xls');  
$objWriter->save('php://output');
//header("Location: archivo.xls");
exit;

?>