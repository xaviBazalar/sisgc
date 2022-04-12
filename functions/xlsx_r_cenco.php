<?php
ini_set('memory_limit', '-1');
set_time_limit(1800);
/** Error reporting */
error_reporting(E_ALL);

date_default_timezone_set('Europe/London');

/** PHPExcel */
require_once '../class/PHPExcel.php';
require_once '../class/PHPExcel/IOFactory.php';
include '../scripts/conexion.php';
//include 'rango_hora.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

		$peri=$_GET['peri'];
		
		$mes_p=$db->Execute("SELECT MONTH(fecini) mes FROM periodos WHERE idperiodo='$peri'");
		$mes_a=$mes_p->fields['mes'];
/*Inicio Instrucciones*/
	$objPHPExcel->getProperties()->setCreator("Kobsa - Gestion")
									 ->setLastModifiedBy("Kobsa - Gestion")
									 ->setTitle("Office 2007 XLSX Test Document")
									 ->setSubject("Office 2007 XLSX Test Document")
									 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
									 ->setKeywords("office 2007 openxml php")
									 ->setCategory("Test result file");
		
		$sql="SELECT cp.observacion,observacion2,v.validaciones,r.idresultado,j.idjustificacion,CONCAT(r.resultado,'-',j.justificacion) llamada,
				g.observacion obs_g,t.telefono
			FROM cuentas c
			JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta
			JOIN gestiones g ON cp.idcuenta=g.idcuenta
			JOIN resultados r ON g.idresultado=r.idresultado
			JOIN justificaciones j ON g.idjustificacion=j.idjustificacion
			JOIN contactabilidad ct ON g.idcontactabilidad=ct.idcontactabilidad
			LEFT JOIN telefonos t ON g.idtelefono=t.idtelefono
			LEFT JOIN validaciones v ON t.idvalidacion=v.idvalidaciones
			WHERE c.idcartera='09'";
		
		
		
		/*if(isset($_GET['peri'])){
			$peri=$_GET['peri'];
			$sql.=" AND cp.idperiodo='$peri'   ";
		}	
		

		if(isset($_GET['prove'])){
			$prov=$_GET['prove'];
			$sql.=" AND pr.idproveedor='$prov' ";
		}

		if(isset($_GET['cart'])){
			$cart=$_GET['cart'];
			$sql.=" AND cra.idcartera='$cart' ";
		}*/
		/*$total=0;
		if(isset($_GET['actv'])){
			$pos = strpos($_GET['actv'], "-");
			if($pos){
				$acts = explode("-",$_GET['actv']);
				
				$total=count($acts);
				$act= array();
				for($i=0;$i<$total;$i++){
					$act[$i+1]=$acts[$i];
				}
				
			}else{
				$total=0;
				$actv=$_GET['actv'];	
				$sql.=" AND a.idactividad='$actv' ";
			}
		}*/
		
		/*if(isset($_GET['fecini']) and isset($_GET['fecfin'])){
			$ini=$_GET['fecini'];
			$fin=$_GET['fecfin'];
			$sql.=" AND g.fecges BETWEEN '$ini' AND '$fin' ";
		}*/

		$sql.=" ORDER BY g.fecreg ";
		$query=$db->Execute($sql);
			
		$n=1;
		
		$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('a1', "SEGMENTO")
							->setCellValue('b1', "PRIORIDAD")
							->setCellValue('c1', "CUENTA")
							->setCellValue('d1', "PAN")
							->setCellValue('e1', "TIPO PAGO")
							->setCellValue('f1', "FACTURACION")
							->setCellValue('g1', "DIAS MORA")
							->setCellValue('h1', "RIESGO")
							->setCellValue('i1', "CALIFICACION LLAMADA")
							->setCellValue('j1', "LLAMADA")
							->setCellValue('k1', "MOTIVO NO PAGO")
							->setCellValue('l1', "TELEFONO")
							;

		$objPHPExcel->getDefaultStyle()->getFont()
			->setName('Calibri')
			->setSize(9);
				while(!$query->EOF){
					++$n;
					switch($query->fields['observacion']){
			case 9:
				$prioridad=1;
				$tipo_p="PAGO MINIMO >=10";
				$fact="ES SU PRIMERA FACTURACION";
				$d_mora="DIAS MORA <=30";
				$riesgo="Riesgo Alto";
				break;
			case 10:
				$prioridad=2;
				$tipo_p="PAGO MINIMO >=10";
				$fact="ES SU PRIMERA FACTURACION";
				$d_mora="DIAS MORA <=30";
				$riesgo="NB";
				break;
			case 11:
				$prioridad=3;
				$tipo_p="PAGO MINIMO >=10";
				$fact="ES SU PRIMERA FACTURACION";
				$d_mora="DIAS MORA <=30";
				$riesgo="Riesgo Aceptable";
				break;
			case 12:
				$prioridad=4;
				$tipo_p="PAGO MINIMO >=10";
				$fact="ES SU PRIMERA FACTURACION";
				$d_mora="DIAS MORA <=30";
				$riesgo="Riesgo Bajo";
				break;
			case 1:
				$prioridad=5;
				$tipo_p="PAGO MINIMO >=10";
				$fact="TIENE FACTURACION ANTERIOR";
				$d_mora="DIAS MORA >30";
				$riesgo="Riesgo Alto";
				break;
			case 5:
				$prioridad=6;
				$tipo_p="PAGO MINIMO >=10";
				$fact="TIENE FACTURACION ANTERIOR";
				$d_mora="DIAS MORA <=30";
				$riesgo="Riesgo Alto";
				break;
			case 2:
				$prioridad=7;
				$tipo_p="PAGO MINIMO >=10";
				$fact="TIENE FACTURACION ANTERIOR";
				$d_mora="DIAS MORA >30";
				$riesgo="NB";
				break;
			case 6:
				$prioridad=8;
				$tipo_p="PAGO MINIMO >=10";
				$fact="TIENE FACTURACION ANTERIOR";
				$d_mora="DIAS MORA <=30";
				$riesgo="NB";
				break;
			case 3:
				$prioridad=9;
				$tipo_p="PAGO MINIMO >=10";
				$fact="TIENE FACTURACION ANTERIOR";
				$d_mora="DIAS MORA >30";
				$riesgo="Riesgo Aceptable";
				break;
			case 7:
				$prioridad=10;
				$tipo_p="PAGO MINIMO >=10";
				$fact="TIENE FACTURACION ANTERIOR";
				$d_mora="DIAS MORA <=30";
				$riesgo="Riesgo Aceptable";
				break;
			case 4:
				$prioridad=11;
				$tipo_p="PAGO MINIMO >=10";
				$fact="TIENE FACTURACION ANTERIOR";
				$d_mora="DIAS MORA >30";
				$riesgo="Riesgo Bajo";
				break;
			case 8:
				$prioridad=12;
				$tipo_p="PAGO MINIMO >=10";
				$fact="TIENE FACTURACION ANTERIOR";
				$d_mora="DIAS MORA <=30";
				$riesgo="Riesgo Bajo";
				break;
			case 21:
				$prioridad=13;
				$tipo_p="PAGO MINIMO <10";
				$fact="ES SU PRIMERA FACTURACION";
				$d_mora="DIAS MORA <=30";
				$riesgo="Riesgo Alto";
				break;
			case 22:
				$prioridad=14;
				$tipo_p="PAGO MINIMO <10";
				$fact="ES SU PRIMERA FACTURACION";
				$d_mora="DIAS MORA <=30";
				$riesgo="NB";
				break;
			case 23:
				$prioridad=15;
				$tipo_p="PAGO MINIMO <10";
				$fact="ES SU PRIMERA FACTURACION";
				$d_mora="DIAS MORA <=30";
				$riesgo="Riesgo Aceptable";
				break;
			case 24:
				$prioridad=16;
				$tipo_p="PAGO MINIMO <10";
				$fact="ES SU PRIMERA FACTURACION";
				$d_mora="DIAS MORA <=30";
				$riesgo="Riesgo Bajo";
				break;
			case 13:
				$prioridad=17;
				$tipo_p="PAGO MINIMO <10";
				$fact="TIENE FACTURACION ANTERIOR";
				$d_mora="DIAS MORA >30";
				$riesgo="Riesgo Alto";
				break;
			case 17:
				$prioridad=18;
				$tipo_p="PAGO MINIMO <10";
				$fact="TIENE FACTURACION ANTERIOR";
				$d_mora="DIAS MORA <=30";
				$riesgo="Riesgo Alto";
				break;
			case 14:
				$prioridad=19;
				$tipo_p="PAGO MINIMO <10";
				$fact="TIENE FACTURACION ANTERIOR";
				$d_mora="DIAS MORA >30";
				$riesgo="NB";
				break;
			case 18:
				$prioridad=20;
				$tipo_p="PAGO MINIMO <10";
				$fact="TIENE FACTURACION ANTERIOR";
				$d_mora="DIAS MORA <=30";
				$riesgo="NB";
				break;
			case 15:
				$prioridad=21;
				$tipo_p="PAGO MINIMO <10";
				$fact="TIENE FACTURACION ANTERIOR";
				$d_mora="DIAS MORA >30";
				$riesgo="Riesgo Aceptable";
				break;
			case 19:
				$prioridad=22;
				$tipo_p="PAGO MINIMO <10";
				$fact="TIENE FACTURACION ANTERIOR";
				$d_mora="DIAS MORA <=30";
				$riesgo="Riesgo Aceptable";
				break;
            case 16:
				$prioridad=23;
				$tipo_p="PAGO MINIMO <10";
				$fact="TIENE FACTURACION ANTERIOR";
				$d_mora="DIAS MORA >30";
				$riesgo="Riesgo Bajo";
				break;
			case 20:
				$prioridad=24;
				$tipo_p="PAGO MINIMO <10";
				$fact="TIENE FACTURACION ANTERIOR";
				$d_mora="DIAS MORA <=30";
				$riesgo="Riesgo Bajo";
				break;					
		}
		
					$pos = strpos($query->fields['observacion2'], "-");
								if($pos){
									$ctas = explode("-",$query->fields['observacion2']);
									$cta=$ctas[1];
									$pan=$ctas[2];
								}
					
					$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('a'.$n, $query->fields['observacion'])
							->setCellValue('b'.$n, $prioridad)
							->setCellValue('c'.$n, '="'.$cta.'"')
							->setCellValue('d'.$n, $pan)
							->setCellValue('e'.$n, $tipo_p)
							->setCellValue('f'.$n, $fact)
							->setCellValue('g'.$n, $d_mora)
							->setCellValue('h'.$n, $riesgo)
							->setCellValue('i'.$n, $query->fields['validaciones'])
							->setCellValue('j'.$n, $query->fields['llamada'])
							->setCellValue('k'.$n, $query->fields['obs_g'])
							->setCellValue('l'.$n, '=("'.$query->fields['telefono'].'")')
							;
					//$objPHPExcel->getActiveSheet()->getStyle('f'.$n)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$query->MoveNext();
				}
	
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
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

		
		$objPHPExcel->getActiveSheet()->getStyle('A1:Z1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('A1:Z1')->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setARGB('00000000');
		$objPHPExcel->getActiveSheet()->setTitle('Cencosud_Detalle_Prioridad');
	
/*Fin Instrucciones*/

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

header('Content-Disposition: attachment;filename="cencosud_dp.xls"');
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
//$objWriter->save('archivo.xls');  
$objWriter->save('php://output');

//header("Location: archivo.xls");
exit;

?>
