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
		$cartera=$_GET['cart'];
							
		$query=$db->Execute("
									SELECT  rp.result,SUM(rp.total) totales FROM
				(SELECT CASE 
					WHEN g.idresultado=1 THEN 'YA PAGO'
					WHEN g.idresultado=6 AND g.idjustificacion!=85 THEN 'SEGUIMIENTO' 
					WHEN g.idresultado=6 AND g.idjustificacion=85 THEN 'REALIZARA FINANCIAMIENTO'
					WHEN g.idresultado=17 AND g.idjustificacion!=99 THEN 'RENUENTE'
					WHEN g.idresultado=17 AND g.idjustificacion=99 THEN 'NO RECONOCE DEUDA'
					WHEN g.idresultado=9 THEN 'TIENE RECLAMO'
					WHEN g.idresultado=2 AND g.idjustificacion!=77 THEN 'PROMESA DE PAGO' 
					WHEN g.idresultado=2 AND g.idjustificacion=77 THEN 'PROMESA DE PAGO PARCIAL' 
					WHEN g.idresultado=12 AND g.idjustificacion=112 THEN 'NO CONTESTA' 
					WHEN g.idresultado=12 AND g.idjustificacion=119 THEN 'COLGO' 
					WHEN g.idresultado=12 AND g.idjustificacion=120 THEN 'GRABADORA' 
					WHEN g.idresultado=12 AND g.idjustificacion=121 THEN 'OCUPADO' 
					WHEN g.idresultado=12 AND g.idjustificacion=122 THEN 'NO CONTESTA' 
					WHEN g.idresultado=11 AND g.idjustificacion=109 THEN 'TERCERO OFICINA' 
					WHEN g.idresultado=11 AND g.idjustificacion=110 THEN 'TERCERO CASA' 
					WHEN g.idresultado=11 AND g.idjustificacion=111 THEN 'TERCERO CASA' 
					WHEN g.idresultado=11 AND g.idjustificacion=114 THEN 'DE VIAJE' 
					WHEN g.idresultado=11 AND g.idjustificacion=115 THEN 'DE VIAJE' 
					WHEN g.idresultado=13 AND g.idjustificacion=124 THEN 'RADICA EN EL EXTRANJERO ' 
					WHEN g.idresultado=13 AND g.idjustificacion=125 THEN 'SE MUDO/NO TRABAJA' 
					WHEN g.idresultado=13 AND g.idjustificacion=211 THEN 'SE MUDO/NO TRABAJA' 
					WHEN g.idresultado=13 AND g.idjustificacion=118 THEN 'NO LO CONOCEN' 
					WHEN g.idresultado=13 AND g.idjustificacion=127 THEN 'FUERA DE SERVICIO' 
					WHEN g.idresultado=13 AND g.idjustificacion=128 THEN 'TEL NO EXISTE' 
					WHEN g.idresultado=13 AND g.idjustificacion=129 THEN 'SIN TELEFONO' 
					WHEN g.idresultado=13 AND g.idjustificacion=123 THEN 'FAX' 
					WHEN g.idresultado=13 AND g.idjustificacion=126 THEN 'NO LO CONOCEN' /*Recientemente Agregado*/
					WHEN g.idresultado=10 THEN 'FALLECIO'
					WHEN g.idresultado=7 AND g.idjustificacion=90 THEN 'PAGA OTRO PRODUCTO' 
					WHEN g.idresultado=7 AND g.idjustificacion=91 THEN 'INSOLVENTE' 
					WHEN g.idresultado=7 AND g.idjustificacion=93 THEN 'INSOLVENTE' 
					WHEN g.idresultado=7 AND g.idjustificacion=94 THEN 'SIN TRABAJO' 
					WHEN g.idresultado=7 AND g.idjustificacion=95 THEN 'INSOLVENTE' 
					WHEN g.idresultado=7 AND g.idjustificacion=96 THEN 'INSOLVENTE' 
					WHEN g.idresultado=7 AND g.idjustificacion=97 THEN 'INSOLVENTE' 
					WHEN g.idresultado=15 THEN ''	
			END result,
				g.idresultado,g.idjustificacion,COUNT(*) total
			
			FROM gestiones g
			JOIN cuentas c ON g.idcuenta=c.idcuenta
			WHERE c.idcartera=$cartera AND g.fecges BETWEEN  '$fec_ini' AND '$fec_fin'
			GROUP BY g.idresultado,g.idjustificacion) AS rp
			GROUP BY rp.result
							");
		$n=1;
		$datos_res= array();
				
				while(!$query->EOF){
					$datos_res[$query->fields['result']]=$query->fields['totales'];
					$query->MoveNext();
				}
		/*echo "<pre>";
		var_dump($datos_res);
		return false;*/
		$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('b2', "RESULTADO DE LLAMADAS del $fec_ini al $fec_fin");


		$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('b4', "CONTACTO DIRECTO")/**/
							->setCellValue('b5', "NO RECONOCE DEUDA")
							->setCellValue('b6', "PROMESA DE PAGO")
							->setCellValue('b7', "RENUENTE")
							->setCellValue('b8', "SEGUIMIENTO")
							->setCellValue('b9', "SIN TRABAJO")
							->setCellValue('b10', "TIENE RECLAMO")
							->setCellValue('b11', "INSOLVENTE")
							->setCellValue('b12', "YA PAGO")
							->setCellValue('b13', "CONTACTO INDIRECTO")/**/
							->setCellValue('b14', "COLGO")
							->setCellValue('b15', "DE VIAJE")
							->setCellValue('b16', "SE MUDO / NO TRABAJA")
							->setCellValue('b17', "TERCERO  CASA")
							->setCellValue('b18', "TERCERO  OFICINA")
							->setCellValue('b19', "NO CONTACTO")/**/
							->setCellValue('b20', "FAX")
							->setCellValue('b21', "FUERA DE SERVICIO")
							->setCellValue('b22', "GRABADORA")
							->setCellValue('b23', "NO CONTESTA")
							->setCellValue('b24', "NO LO CONOCEN")
							->setCellValue('b25', "SIN TELEFONO")
							->setCellValue('b26', "OCUPADO")
							->setCellValue('b27', "TEL EXISTE")
							->setCellValue('b28', "TOTAL GENERAL")
							;
		$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('c4', ($datos_res['INSOLVENTE']+$datos_res['NO RECONOCE DEUDA']+$datos_res['PROMESA DE PAGO']+$datos_res['RENUENTE']+$datos_res['SEGUIMIENTO']+$datos_res['SIN TRABAJO']+$datos_res['TIENE RECLAMO']+$datos_res['PROMESA DE PAGO PARCIAL']+$datos_res['YA PAGO']) )
							->setCellValue('c5', $datos_res['NO RECONOCE DEUDA'])//encuesta completa
							->setCellValue('c6', $datos_res['PROMESA DE PAGO']+$datos_res['PROMESA DE PAGO PARCIAL']) // no desea
							->setCellValue('c7', $datos_res['RENUENTE']) // no se comunico
							->setCellValue('c8', $datos_res['SEGUIMIENTO']) // no acerca a cat
							->setCellValue('c9', $datos_res['SIN TRABAJO']) // cita telefonica
							->setCellValue('c10', $datos_res['TIENE RECLAMO']) // cita telefonica
							->setCellValue('c11', $datos_res['INSOLVENTE']) // cita telefonica
							->setCellValue('c12', $datos_res['YA PAGO']) // cita telefonica
							->setCellValue('c13', ($datos_res['COLGO']+$datos_res['DE VIAJE']+$datos_res['SE MUDO/NO TRABAJA']+$datos_res['TERCERO CASA']+$datos_res['TERCERO OFICINA']))
							->setCellValue('c14', $datos_res['COLGO'])
							->setCellValue('c15', $datos_res['DE VIAJE'])
							->setCellValue('c16', $datos_res['SE MUDO/NO TRABAJA'])
							->setCellValue('c17', $datos_res['TERCERO CASA'])
							->setCellValue('c18', $datos_res['TERCERO OFICINA'])
							->setCellValue('c19', ($datos_res['TEL NO EXISTE']+$datos_res['OCUPADO']+$datos_res['FAX']+$datos_res['FUERA DE SERVICIO']+$datos_res['GRABADORA']+$datos_res['NO CONTESTA']+$datos_res['NO LO CONOCEN']+$datos_res['SIN TELEFONO']))
							->setCellValue('c20',  $datos_res['FAX']) //ocupado
							->setCellValue('c21',  $datos_res['FUERA DE SERVICIO']) // cortaron
							->setCellValue('c22',  $datos_res['GRABADORA']) // grabadora
							->setCellValue('c23',  $datos_res['NO CONTESTA']) //no contestan 
							->setCellValue('c24',  $datos_res['NO LO CONOCEN']) // Fuera de servicio
							->setCellValue('c25',  $datos_res['SIN TELEFONO']) // Telfs / Lineas Inoperativas
							->setCellValue('c26',  $datos_res['OCUPADO']) // Telfs / Lineas Inoperativas
							->setCellValue('c27',  $datos_res['TEL NO EXISTE']) // Telfs / Lineas Inoperativas
							->setCellValue('c28',  array_sum($datos_res)-( $datos_res[''])) //Telfs no corresponden
							;
		$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('d4', (round(($datos_res['INSOLVENTE']+$datos_res['NO RECONOCE DEUDA']+$datos_res['PROMESA DE PAGO']+$datos_res['PROMESA DE PAGO PARCIAL']+$datos_res['RENUENTE']+$datos_res['SEGUIMIENTO']+$datos_res['SIN TRABAJO']+$datos_res['TIENE RECLAMO']+$datos_res['TITULAR SOLICITA NUEVA LLAMADA']+$datos_res['YA PAGO'])/(array_sum($datos_res)) ,2)*100).'%')
							//->setCellValue('d5', )//encuesta completa
							//->setCellValue('d6', ) // no desea
							//->setCellValue('d7', ) // no se comunico
							//->setCellValue('d8', ) // no acerca a cat
							//->setCellValue('d9', ) // cita telefonica
							->setCellValue('d13', (round(($datos_res['COLGO']+$datos_res['DE VIAJE']+$datos_res['SE MUDO/NO TRABAJA']+$datos_res['TERCERO CASA']+$datos_res['TERCERO OFICINA'])/(array_sum($datos_res)),2)*100).'%')
							//->setCellValue('d11', )
							//->setCellValue('d12', )
							->setCellValue('d19', (round(($datos_res['TEL NO EXISTE']+$datos_res['OCUPADO']+$datos_res['FAX']+$datos_res['FUERA DE SERVICIO']+$datos_res['GRABADORA']+$datos_res['NO CONTESTA']+$datos_res['NO LO CONOCEN']+$datos_res['SIN TELEFONO'])/(array_sum($datos_res)),2)*100).'%')
							//->setCellValue('d14',  ) //ocupado
							//->setCellValue('d15',  ) // cortaron
							//->setCellValue('d16',  ) // grabadora
							//->setCellValue('d17',  ) //no contestan 
							//->setCellValue('d18',  ) // Fuera de servicio
							//->setCellValue('d19',  ) // Telfs / Lineas Inoperativas
							//->setCellValue('d20',  ) //Telfs no corresponden
							//->setCellValue('d21',  ) //Telfs no existen
							->setCellValue('d28', '100%')
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

		$objPHPExcel->getActiveSheet()->getStyle('B13:D13')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('B13:D13')->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setRGB('FF7F50');

		$objPHPExcel->getActiveSheet()->getStyle('B19:D19')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('B19:D19')->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setRGB('FF7F50');

		$objPHPExcel->getActiveSheet()->getStyle('B28:D28')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('B28:D28')->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setRGB('FF7F50');								
									
				
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

		$objPHPExcel->getActiveSheet()->setTitle('resumen_rip_f');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

/* Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="archivo.xlsx"');
header('Cache-Control: max-age=0');*/
header('Content-Disposition: attachment;filename="resumen_rip_f.xls"');
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
//$objWriter->save('archivo.xls');  
$objWriter->save('php://output');
//header("Location: archivo.xls");
exit;

?>