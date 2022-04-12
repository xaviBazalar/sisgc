<?php
session_start();
ini_set('memory_limit', '-1');
set_time_limit(1800);
/** Error reporting */
error_reporting(E_ALL);

date_default_timezone_set('Europe/London');

/** PHPExcel */
require_once '../class/PHPExcel.php';
require_once '../class/PHPExcel/IOFactory.php';
include '../scripts/conexion.php';

	$iduser=$_SESSION['iduser'];	
	$ip=$_SERVER['REMOTE_ADDR'];
	$fecha=date("Y-m-d");
	$hora=date("H:i:s");
	//$db->_query("INSERT into detalle_reportes (`idreporte`,`idusuario`,`fecha`,`hora_ini`,`host`) VALUES('4','$iduser','$fecha','$hora','$ip')");

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

/*Inicio Instrucciones*/
	$objPHPExcel->getProperties()->setCreator("Kobsa - Gestion")
									 ->setLastModifiedBy("Kobsa - Gestion")
									 ->setTitle("Office 2007 XLSX Test Document")
									 ->setSubject("Office 2007 XLSX Test Document")
									 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
									 ->setKeywords("office 2007 openxml php")
									 ->setCategory("Test result file");
					
		$n=1;

		$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('a1', "TIPO DE CONTACTO")
							->setCellValue('b1', "DESCRIPCION")
							->setCellValue('c1', "CANTIDAD_REGISTROS")
							->setCellValue('d1', "%")
							;
						
				
						
		//****				
		$objPHPExcel->getDefaultStyle()->getFont()
			->setName('Calibri')
			->setSize(8);
		//Inicio------------------------------------------------------------------------------------------	
				
				$sql="SELECT g.idresultado,(SELECT resultado FROM resultados WHERE idresultado=g.idresultado) tipo,COUNT(g.idresultado) total
				FROM cuentas  c
				JOIN gestiones g ON c.idcuenta=g.idcuenta 
				WHERE c.idcartera=24 AND g.fecges BETWEEN  '2011-12-01' AND '2011-12-31' 
				GROUP BY g.idresultado 
				";
				
				/*if(isset($_GET['prove'])){
					$prov=$_GET['prove'];
					$sql.=" AND p.idproveedor='$prov' ";
				}

				if(isset($_GET['cart'])){
					$cart=$_GET['cart'];
					$sql.=" AND c.idcartera='$cart' ";
				}

				$fi=$_GET['fi'];
				$fn=$_GET['fn'];
				$sql.="AND g.idactividad BETWEEN '1' AND '2' and fecges BETWEEN '$fi' AND '$fn' GROUP BY u.idusuario ";
				$sql2=$sql;*/
				//$sql2.=" GROUP BY u.idusuario ";
				
				
				/*echo $sql;
				return false*/;
				$z=1;
				$a=1;
				$b=1;
				$v=2;
				//echo "<pre>";
				//$db->debug=true;
				$tipo=array();
				$t_tipo=array();
				
				$cd=array(51,52,53);
				$ci=array(54,55,56);
				$nc=array(57,58,59,60,61,62,63,12);
				$total=0;
				
				$query=$db->Execute($sql);
				while(!$query->EOF){
					$rst=$query->fields['idresultado'];
					
					if(in_array($rst,$cd)){
						$tipo['CD'][$a]['NOM']=$query->fields['tipo'];
						$tipo['CD'][$a]['TOT']=$query->fields['total'];
						$total=$total+$query->fields['total'];
						++$a;
					}
					
					if(in_array($rst,$ci)){
						$tipo['CI'][$b]['NOM']=$query->fields['tipo'];
						$tipo['CI'][$b]['TOT']=$query->fields['total'];
						$total=$total+$query->fields['total'];
						++$b;
					}
					
					if(in_array($rst,$nc)){
						$tipo['NC'][$z]['NOM']=$query->fields['tipo'];
						$tipo['NC'][$z]['TOT']=$query->fields['total'];
						$total=$total+$query->fields['total'];
						++$z;
					}

					$query->MoveNext();
					
				}
			
				mysql_free_result($query->_queryID);
				
				
				
				for($i=1;$i<=count($tipo['CD']);$i++){
					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('a'.$v, "")
					->setCellValue('b'.$v, $tipo['CD'][$i]['NOM'])
					->setCellValue('c'.$v, $tipo['CD'][$i]['TOT'])
					->setCellValue('d'.$v, '');
					$total1=$total1+$tipo['CD'][$i]['TOT'];
					
					++$v;
				}
					$ct=round((($total1*100)/$total),2);
					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('a'.$v, "Total: Contacto CD")
					->setCellValue('b'.$v, "")
					->setCellValue('c'.$v, $total1)
					->setCellValue('d'.$v, $ct);
					
					$objPHPExcel->getActiveSheet()->getStyle("A$v:D$v")->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setRGB('C0C0C0');
					++$v;
					
				for($i=1;$i<=count($tipo['CI']);$i++){
					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('a'.$v, "")
					->setCellValue('b'.$v, $tipo['CI'][$i]['NOM'])
					->setCellValue('c'.$v, $tipo['CI'][$i]['TOT'])
					->setCellValue('d'.$v, '');
					$total2=$total2+$tipo['CI'][$i]['TOT'];
					++$v;
				}
					$ct=round((($total2*100)/$total),2);
					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('a'.$v, "Total: Contacto CI")
					->setCellValue('b'.$v, "")
					->setCellValue('c'.$v, $total2)
					->setCellValue('d'.$v, $ct);
					
					$objPHPExcel->getActiveSheet()->getStyle("A$v:D$v")->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setRGB('C0C0C0');
					++$v;
					
				for($i=1;$i<=count($tipo['NC']);$i++){
					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('a'.$v, "")
					->setCellValue('b'.$v, $tipo['NC'][$i]['NOM'])
					->setCellValue('c'.$v, $tipo['NC'][$i]['TOT'])
					->setCellValue('d'.$v, '');
					$total3=$total3+$tipo['NC'][$i]['TOT'];
					++$v;
				}
					$ct=round((($total3*100)/$total),2);
					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('a'.$v, "Total: NC")
					->setCellValue('b'.$v, "")
					->setCellValue('c'.$v, $total3)
					->setCellValue('d'.$v, $ct);
					
					$objPHPExcel->getActiveSheet()->getStyle("A$v:D$v")->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setRGB('C0C0C0');
					++$v;

					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('a'.$v, "Total General")
					->setCellValue('b'.$v, "")
					->setCellValue('c'.$v, $total)
					->setCellValue('d'.$v, '100%');	
					$objPHPExcel->getActiveSheet()->getStyle("A$v:D$v")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
					$objPHPExcel->getActiveSheet()->getStyle("A$v:D$v")->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setRGB('696969');

					
					
					$ant=$v-1;

					$objPHPExcel->getActiveSheet()->getStyle('d'.$ant)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('d'.$v)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					
					$objPHPExcel->getActiveSheet()->getStyle('A1:D'.$v.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					$objPHPExcel->getActiveSheet()->getStyle('A1:D'.$v.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('A1:D'.$ant.'')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('A1:D'.$v.'')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('A1:D'.$v.'')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('A1:D'.$v.'')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

					$objPHPExcel->getActiveSheet()->getStyle('d'.$ant)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('d'.$v)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
					$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setRGB('4682B4');
	///-------------------------------------------------	

		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	
		$objPHPExcel->getActiveSheet(0)->setTitle('reporte_gestion');

		/*Fin Instrucciones*/

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);

		$db->Close();
		header('Content-Disposition: attachment;filename="reporte_ges_cenco_tv.xls"');
		//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
		//$objWriter->save('archivo.xls');  
		$objWriter->save('php://output');

		//header("Location: archivo.xls");
		exit;

?>
