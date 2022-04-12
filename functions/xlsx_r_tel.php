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

		/*$peri=$_GET['peri'];
		$mes_p=$db->Execute("SELECT fecini,MONTH(fecini) mes FROM periodos WHERE idperiodo='$peri'");
		$mes_a=$mes_p->fields['mes'];*/
		
/*Inicio Instrucciones*/
	$objPHPExcel->getProperties()->setCreator("Kobsa - Gestion")
									 ->setLastModifiedBy("Kobsa - Gestion")
									 ->setTitle("Office 2007 XLSX Test Document")
									 ->setSubject("Office 2007 XLSX Test Document")
									 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
									 ->setKeywords("office 2007 openxml php")
									 ->setCategory("Test result file");
		

		$sql="SELECT MIN(j.peso),c.idcliente,c.cliente,t.telefono,ot.origentelefono,v.validaciones,f.fuente,
				t.fecreg,a.actividad,gg.grupogestion,r.resultado,j.justificacion,g.observacion,g.fecges FROM clientes c
				JOIN telefonos t ON c.idcliente=t.idcliente
				LEFT JOIN origen_telefonos ot ON t.idorigentelefono=ot.idorigentelefono
				LEFT JOIN fuentes f ON t.idfuente=f.idfuente
				LEFT JOIN validaciones v ON t.idvalidacion=v.idvalidaciones
				JOIN gestiones g ON t.idtelefono=g.idtelefono
				JOIN actividades a ON g.idactividad=a.idactividad
				JOIN resultados r ON g.idresultado=r.idresultado
				JOIN grupo_gestiones gg ON r.idgrupogestion=gg.idgrupogestion
				JOIN justificaciones j ON g.idjustificacion=j.idjustificacion
				JOIN cuentas ct ON c.idcliente=ct.idcliente
				JOIN carteras cr ON ct.idcartera=cr.idcartera
				JOIN proveedores p ON cr.idproveedor=p.idproveedor
				WHERE  p.idproveedor='2'
				GROUP BY t.telefono
									";				 
		
		/*if(isset($_GET['peri'])){
			$peri=$_GET['peri'];
			$sql.=" AND cp.idperiodo='$peri'   ";
		}*/	

		if(isset($_GET['prove'])){
			$prov=$_GET['prove'];
			$sql.=" AND pr.idproveedor='$prov' ";
		}

		if(isset($_GET['cart'])){
			$cart=$_GET['cart'];
			$sql.=" AND cr.idcartera='$cart' ";
		}
		
		$total=0;
		
		/*if(isset($_GET['fecini']) and isset($_GET['fecfin'])){
			$ini=$_GET['fecini'];
			$fin=$_GET['fecfin'];
			$sql.=" AND g.fecges BETWEEN '$ini' AND '$fin' ";
		}*/

		$sql.=" GROUP BY t.telefono ORDER BY c.idcliente ";
		/*echo $sql;
		return false;*/
		$query=$db->Execute($sql);
			
		$n=1;
		
		$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('a1', "ID")
							->setCellValue('b1', "NOMBRE")
							->setCellValue('c1', "DIRECCION")
							->setCellValue('d1', "DEPARTAMENTO")
							->setCellValue('e1', "PROVINCIA")
							->setCellValue('f1', "DISTRITO")
							->setCellValue('g1', "ORIGEN_1")
							->setCellValue('h1', "ORIGEN_2")
							->setCellValue('i1', "VALIDACION")
							->setCellValue('j1', "FUENTE")
							->setCellValue('k1', "FECHA_REGISTRO")
							->setCellValue('l1', "UBICABILIDAD")
							->setCellValue('m1', "INDICADOR_MEJOR_DK")
							->setCellValue('n1', "TIPRESULT_MEJOR_DK")
							->setCellValue('o1', "RESULT_MEJOR_DK")
							->setCellValue('p1', "TIPO PREDIO-MATERIAL-NPISOS-COLOR-OBSERVACION")
							->setCellValue('q1', "FECHA_MEJOR_DK")
							;

			$objPHPExcel->getDefaultStyle()->getFont()
				->setName('Calibri')
				->setSize(9);
				while(!$query->EOF){
					++$n;
								$pos = strpos($query->fields['origendireccion'], "-");
								if($pos){
									$org_d = explode("-",$query->fields['origendireccion']);
								}
					
						
					/*$coddpto=$query->fields['coddpto'];
					$dpto=$db->Execute("SELECT nombre,coddpto FROM ubigeos WHERE codprov=00 AND coddist=00 AND coddpto='$coddpto'");
					$dpt=$dpto->fields['nombre'];
					
					$codprov=$query->fields['codprov'];
					$prov=$db->Execute("SELECT nombre,codprov FROM ubigeos WHERE codprov='$codprov' AND coddist=00 AND coddpto='$coddpto'");
					$provi=$prov->fields['nombre'];
					
					$coddist=$query->fields['coddist'];
					$dist=$db->Execute("SELECT nombre,coddist FROM ubigeos WHERE codprov='$codprov' AND coddist='$coddist' AND coddpto='$coddpto'");
					$distr=$dist->fields['nombre'];*/

					$validacion=$query->fields['validaciones'];
					if($validacion==""){ $validacion="No Validado";}

						
					$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('a'.$n, '=("'.$query->fields['idcliente'].'")')
							->setCellValue('b'.$n, utf8_encode($query->fields['cliente']))
							->setCellValue('c'.$n, $query->fields['direccion'])
							//->setCellValue('d'.$n, $dpt)
							//->setCellValue('e'.$n, $provi)
							//->setCellValue('f'.$n, $distr)
							->setCellValue('g'.$n, $org_d[0])
							->setCellValue('h'.$n, $org_d[1])
							->setCellValue('i'.$n, $validacion)
							->setCellValue('j'.$n, $query->fields['fuente'])
							->setCellValue('k'.$n, $query->fields['fecreg'])
							->setCellValue('l'.$n, $query->fields['ubicabilidad'])
							->setCellValue('m'.$n, $query->fields['actividad'])
							->setCellValue('n'.$n, $query->fields['grupogestion'])
							->setCellValue('o'.$n, $query->fields['resultado'])
							->setCellValue('p'.$n, $query->fields['tipo_predio']."-".$query->fields['material']."-".$query->fields['piso']."-".$query->fields['color'])
							->setCellValue('q'.$n, $query->fields['fbest'])
							;
					//$objPHPExcel->getActiveSheet()->getStyle('f'.$n)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
					$query->MoveNext();
						
				}
				mysql_free_result($query->_queryID);
				$db->Close();
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
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(22);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);


		
		$objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setARGB('00000000');
		$objPHPExcel->getActiveSheet()->setTitle('reporte_direcciones');
	
/*Fin Instrucciones*/

// Set active sheet index to the first sheet, so Excel opens this as the first sheet

$objPHPExcel->setActiveSheetIndex(0);


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="direcciones_reporte.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

//$objWriter->save('archivo.xls'); 

$objWriter->save('php://output');
//unset($objPHPExcel);
//header("Location: archivo.xls");
exit;

?>
