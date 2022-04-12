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
	//return false;
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

/*for($o=1;$o<=getMonthDays($mes, $ano);$o++){
	if(date("l", mktime(0, 0, 0, $mes, $o,$ano)) == "Sunday" || date("l", mktime(0, 0, 0, $mes, $o, $ano)) == "Saturday")
	{
		++$domingo;
	}
}
$promedio=getMonthDays($mes, $ano)-$domingo;*/
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
		
		$fecha=$_GET['fecini'];
		$idcartera=$_GET['cart'];
		$cartera=$_GET['cart'];
		if(isset($_GET['idusuario'])){$usuG=$_GET['idusuario'];}else{$usuG="";}
		if($usuG!=""){
			$sql_usu=" AND g.usureg=$usuG";
		}else{ $sql_usu="";}
		$datos_res= array();	
		$usuarios = array();
			
			$sql=
				"
					SELECT 	
						CASE
							WHEN rc.hora=6 THEN '6 a 7'
							WHEN rc.hora=7 THEN '7 a 8'
							WHEN rc.hora=8 THEN '8 a 9'
							WHEN rc.hora=9 THEN '9 a 10'
							WHEN rc.hora=10 THEN '10 a 11'
							WHEN rc.hora=11 THEN '11 a 12'
							WHEN rc.hora=12 THEN '12 a 13'
							WHEN rc.hora=13 THEN '13 a 14'
							WHEN rc.hora=14 THEN '14 a 15'
							WHEN rc.hora=15 THEN '15 a 16'
							WHEN rc.hora=16 THEN '16 a 17'
							WHEN rc.hora=17 THEN '17 a 18'
							WHEN rc.hora=18 THEN '18 a 19'
							WHEN rc.hora=19 THEN '19 a 20'
							WHEN rc.hora=20 THEN '20 a 21'
							WHEN rc.hora=21 THEN '21 a 22'
							WHEN rc.hora=22 THEN '22 a 23'
							WHEN rc.hora=23 THEN '23 a 24'
						END AS hora,
						SUM(rc.idgrupogestion=1) CE,
						SUM(rc.idgrupogestion=2) CNE,
						SUM(rc.idgrupogestion=3) I,
						SUM(rc.idgrupogestion=4) NC,
						SUM(rc.idgrupogestion=5) B,
						SUM(rc.idgrupogestion=6) P

						FROM
						(
							SELECT HOUR(g.horges) hora,r.idgrupogestion,c.idcliente,cp.idcuenta,g.idresultado,g.idjustificacion,g.peso
								FROM cuentas c
								JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta AND cp.idperiodo=$peri AND cp.idestado=1
								JOIN gestiones g ON cp.idcuenta=g.idcuenta AND fecges='$fecha'
								JOIN resultados r ON g.idresultado=r.idresultado
								WHERE c.idcartera=$idcartera
								AND g.peso=(SELECT MIN(peso) FROM gestiones WHERE idcuenta=c.idcuenta AND fecges='$fecha')
								$sql_usu
								-- and r.idgrupogestion=4 and hour(g.horges)=22
							-- GROUP BY cp.idcuenta 
							-- ORDER BY g.peso 
						) AS rc
						JOIN grupo_gestiones gg ON rc.idgrupogestion=gg.idgrupogestion
						GROUP BY rc.hora
				";
			//echo $sql;
			//return false;
			$query=$db->Execute($sql);
		$car=$db->Execute("
							Select p.proveedor,c.cartera from carteras c
							join proveedores p on c.idproveedor=p.idproveedor
							where c.idcartera='$idcartera'
							
							");
		$ncartera=$car->fields['proveedor']." ".$car->fields['cartera'];
		$r=3;
		//$fecha=date("F",mktime(0, 0, 0, $mes, 1, $ano));
		
		$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->applyFromArray(
	 		array(
				'size'	  => '14',
	 			'name'	  => 'Arial',
	 			'bold'	  => true,
	 			'italic'	=> false,
	 			'underline' => PHPExcel_Style_Font::UNDERLINE_DOUBLE,
	 			'strike'	=> false,
	 			'color'	 => array(
	 				'rgb' => '808080'
	 			)
	 		)
		);
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('b1',"REPORTE  xxxxx  - $ncartera /   $fecha ");
		$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('c'.$r, "Hora")
							->setCellValue('d'.$r, "Contacto Efectivo")
							->setCellValue('e'.$r, "Contacto No Efectivo")
							->setCellValue('f'.$r, "No Contacto")
							->setCellValue('g'.$r, "Ilocalizado")
							->setCellValue('h'.$r, "Busqueda")
							->setCellValue('i'.$r, "Procedimientos")
							->setCellValue('j'.$r, "Total General")
							;
			
		$tot_cl=0;$tot_cta=0;$tot_capt=0;$tot_capg=0;$tot_capsg=0;$tot_gs=0;$tot_sgs=0;	
		while(!$query->EOF){
			++$r;
					
					$total_gg=	$query->fields['CE']+
								$query->fields['CNE']+
								$query->fields['I']+
								$query->fields['NC']+
								$query->fields['B']+
								$query->fields['P'];
								
					$objPHPExcel->setActiveSheetIndex(0)
							
							->setCellValue('c'.$r, $query->fields['hora'])
							->setCellValue('d'.$r, $query->fields['CE'])
							->setCellValue('e'.$r, $query->fields['CNE'])
							->setCellValue('f'.$r, $query->fields['I'])
							->setCellValue('g'.$r, $query->fields['NC'])
							->setCellValue('h'.$r, $query->fields['B'])
							->setCellValue('i'.$r, $query->fields['P'])
							->setCellValue('j'.$r, $total_gg)
							;
					$tot_cl=$tot_cl+$query->fields['CE'];
					$tot_cta=$tot_cta+$query->fields['CNE'];
					$tot_capt=$tot_capt+$query->fields['I'];
					$tot_capg=$tot_capg+$query->fields['NC'];
					$tot_capsg=$tot_capsg+$query->fields['B'];
					$tot_gs=$tot_gs+$cs_tot->fields['P'];
					$tot_sgs=$tot_sgs+$total_gg;
				
					$query->MoveNext();
		}	
		
		/*$objPHPExcel->getDefaultStyle()->getFont()
			->setName('Arila')
			->setSize(8);*/
			
		++$r;
		$objPHPExcel->setActiveSheetIndex(0)
							
							->setCellValue('c'.$r, "TOTAL")
							->setCellValue('d'.$r, $tot_cl)
							->setCellValue('e'.$r, $tot_cta)
							->setCellValue('f'.$r, $tot_capt)
							->setCellValue('g'.$r, $tot_capg)
							->setCellValue('h'.$r, $tot_capsg)
							->setCellValue('i'.$r, $tot_gs)
							->setCellValue('j'.$r, $tot_sgs)
							;
		$objPHPExcel->getActiveSheet()->getStyle('C'.$r.':J'.$r.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);				
		$objPHPExcel->getActiveSheet()->getStyle('C3:J3')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('C3:J3')->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setRGB('000000');								
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);		
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		
		$objPHPExcel->getActiveSheet()->setTitle('reporte_xxx');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

/* 
	Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="archivo.xlsx"');
	header('Cache-Control: max-age=0');
*/
header('Content-Disposition: attachment;filename="reporte_xxx.xls"');
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
//$objWriter->save('archivo.xls');  
$objWriter->save('php://output');
//header("Location: archivo.xls");
exit;

?>