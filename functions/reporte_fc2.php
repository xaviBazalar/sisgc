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
		$query=$db->Execute("SELECT idusuario,usuario,documento FROM usuarios WHERE idproveedor='10'");
		$n=1;

		$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('a1', "Id")
							->setCellValue('b1', "Usuario")
							->setCellValue('C1', "Dni")
							;

		$objPHPExcel->getDefaultStyle()->getFont()
			->setName('Arila')
			->setSize(10);
				while(!$query->EOF){
					++$n;
					$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('a'.$n, $query->fields['idusuario'])
							->setCellValue('b'.$n, $query->fields['usuario'])
							->setCellValue('c'.$n, $query->fields['documento'])
							;
					$query->MoveNext();
				}
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

		$objPHPExcel->getActiveSheet()->setTitle('Usuarios+Fono');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

/* Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="archivo.xlsx"');
header('Cache-Control: max-age=0');*/
header('Content-Disposition: attachment;filename="usuarios_ted.xls"');
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
//$objWriter->save('archivo.xls');  
$objWriter->save('php://output');
//header("Location: archivo.xls");
exit;

?>