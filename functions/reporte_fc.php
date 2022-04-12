<?php
/** Error reporting */
ini_set('memory_limit', '-1');
set_time_limit(1800);
error_reporting(E_ALL);

date_default_timezone_set('Europe/London');

/** PHPExcel */
require_once '../class/PHPExcel.php';
require_once '../class/PHPExcel/IOFactory.php';
include '../scripts/conexion.php';

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
		$fec_ini=$_GET['fecini'];
		$fec_fin=$_GET['fecfin'];
		$cartera=$_GET['cartera'];
							
		$query=$db->Execute("
							SELECT rs.idresultado,rs.resultado,rs.Tipo_Contacto,COUNT(rs.idresultado) total
							FROM (
									SELECT g.idresultado,(SELECT resultado FROM resultados WHERE idresultado=g.idresultado) resultado,
									(SELECT grupogestion FROM grupo_gestiones WHERE idgrupogestion=
										(SELECT idgrupogestion FROM resultados WHERE idresultado=g.idresultado)
									 ) Tipo_Contacto
									
									FROM cuentas  c
									JOIN gestiones g ON c.idcuenta=g.idcuenta 
									JOIN resultado_carteras rc ON g.idresultado=rc.idresultado
									WHERE c.idcartera=$cartera AND g.fecges BETWEEN  '$fec_ini' AND '$fec_fin' 
									AND g.peso=(
											SELECT MIN(peso) FROM gestiones WHERE idcuenta=g.idcuenta
											)
									GROUP BY c.idcuenta
									ORDER BY 3
							)  rs
							GROUP BY rs.idresultado
							ORDER BY 3
		
							");
		$n=1;
		$datos_res= array();
				for($i=52;$i<=72;$i++){
					$datos_res[$i]=0;
				}
				while(!$query->EOF){
					$datos_res[$query->fields['idresultado']]=$query->fields['total'];
					$query->MoveNext();
				}
		/*echo "<pre>";
		var_dump($datos_res);
		return false;*/
		$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('b2', "RESULTADO DE LLAMADAS del $fec_ini al $fec_fin");
		
		$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('b4', "CONTACTO DIRECTO")
							->setCellValue('b5', "Encuesta Completa")
							->setCellValue('b6', "No Desea")
							->setCellValue('b7', "No se comunico")
							->setCellValue('b8', "No se acerco a CAT")
							->setCellValue('b9', "Cita Telefónica")
							->setCellValue('b10', "CONTACTO INDIRECTO")
							->setCellValue('b11', "Contesto un tercero")
							->setCellValue('b12', "Volver a contactar")
							->setCellValue('b13', "NO CONTACTO")
							->setCellValue('b14', "Telf Ocupado")
							->setCellValue('b15', "Cortaron")
							->setCellValue('b16', "Grabadora")
							->setCellValue('b17', "No Contesta")
							->setCellValue('b18', "Fuera de servicio")
							->setCellValue('b19', "Telfs / Lineas Inoperativas")
							->setCellValue('b20', "Telfs no corresponden")
							->setCellValue('b21', "Telfs no existen")
							->setCellValue('b23', "TOTAL BASE RECORRIDA")
							;
		$objPHPExcel->setActiveSheetIndex(0)
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
							;
		$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('d4', (round(($datos_res[67]+$datos_res[52]+$datos_res[53])/(array_sum($datos_res)) ,2)*100).'%')
							//->setCellValue('d5', )//encuesta completa
							//->setCellValue('d6', ) // no desea
							//->setCellValue('d7', ) // no se comunico
							//->setCellValue('d8', ) // no acerca a cat
							//->setCellValue('d9', ) // cita telefonica
							->setCellValue('d10', (round(($datos_res[70]+$datos_res[54])/(array_sum($datos_res)),2)*100).'%')
							//->setCellValue('d11', )
							//->setCellValue('d12', )
							->setCellValue('d13', (round(($datos_res[60]+$datos_res[57]+$datos_res[58]+$datos_res[59]+$datos_res[61]+$datos_res[62]+$datos_res[63]+$datos_res[72])/(array_sum($datos_res)),2)*100).'%')
							//->setCellValue('d14',  ) //ocupado
							//->setCellValue('d15',  ) // cortaron
							//->setCellValue('d16',  ) // grabadora
							//->setCellValue('d17',  ) //no contestan 
							//->setCellValue('d18',  ) // Fuera de servicio
							//->setCellValue('d19',  ) // Telfs / Lineas Inoperativas
							//->setCellValue('d20',  ) //Telfs no corresponden
							//->setCellValue('d21',  ) //Telfs no existen
							->setCellValue('d23', '100%')
							;

		$objPHPExcel->getDefaultStyle()->getFont()
			->setName('Calibri')
			->setSize(9);
		
		$objPHPExcel->getActiveSheet()->getStyle('B2:D2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
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
								->getStartColor()->setRGB('FF7F50');								
									
				
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

		$objPHPExcel->getActiveSheet()->setTitle('reporte_claro_tv');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

/* Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="archivo.xlsx"');
header('Cache-Control: max-age=0');*/
header('Content-Disposition: attachment;filename="reporte_claro_tv.xls"');
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
//$objWriter->save('archivo.xls');  
$objWriter->save('php://output');
//header("Location: archivo.xls");
exit;

?>