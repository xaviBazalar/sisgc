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
		
		
		$idcartera=$_GET['cart'];
		$cartera=$_GET['cart'];
		
		$datos_res= array();	
		$usuarios = array();
			
			$sql="SELECT 	rc.idusuario,u.usuario,
							COUNT(DISTINCT(rc.idcliente)) tot_cli,
							COUNT(rc.idcuenta) tot_cta,
							SUM(rc.impcap) cap_t,
							SUM(rc.imp_g) cap_g
							-- SUM(rc.gestion=1) con_gestion,
							-- SUM(rc.gestion=0) sin_gestion
							FROM
							(
								SELECT cp.idusuario,c.idcliente,cp.idcuenta,cp.impcap,-- cp.impven,
								IF(g.idgestion,impcap,0) imp_g
								-- IF(g.idgestion,1,0) gestion
								FROM cuentas c
								JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta AND cp.idperiodo=$peri and cp.idestado=1
								LEFT JOIN gestiones g ON cp.idcuenta=g.idcuenta AND fecges LIKE '$ano-$mes-%' 
								WHERE c.idcartera=$cartera
								GROUP BY cp.idcuenta 
								ORDER BY 1,2,4 
							) AS rc
							join usuarios u ON rc.idusuario=u.idusuario
						GROUP BY rc.idusuario
						";
			
			$query=$db->Execute($sql);
		$car=$db->Execute("
							Select p.proveedor,c.cartera from carteras c
							join proveedores p on c.idproveedor=p.idproveedor
							where c.idcartera='$cartera'
							
							");
		$cartera=$car->fields['proveedor']." ".$car->fields['cartera'];
		$r=3;
		$fecha=date("F",mktime(0, 0, 0, $mes, 1, $ano));
		
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
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('b1',"REPORTE COBERTURA  - $cartera  /   $fecha $ano ");
		$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('c'.$r, "Usuario")
							->setCellValue('d'.$r, "Nro Clientes")
							->setCellValue('e'.$r, "Nro Ctas")
							->setCellValue('f'.$r, "Cap. Total S/.")
							->setCellValue('g'.$r, "Cap. Gest. S/.")
							->setCellValue('h'.$r, "Cap. No Gest. S/.")
							->setCellValue('i'.$r, "Clientes con Gestion")
							->setCellValue('j'.$r, "Clientes sin Gestion")
							;
			
		$tot_cl=0;$tot_cta=0;$tot_capt=0;$tot_capg=0;$tot_capsg=0;$tot_gs=0;$tot_sgs=0;	
		while(!$query->EOF){
			++$r;
					$sql_tw="
								SELECT  rt.idusuario,SUM(rt.gs=1) gs,SUM(rt.gs=0) ns
								FROM
								(
									SELECT cp.idusuario,c.idcartera,c.idcliente,
										IF(
											
											(
													SELECT MAX(fecges) FROM cuentas ct
													LEFT JOIN gestiones gt ON ct.idcuenta=gt.idcuenta AND  gt.fecges LIKE '$ano-$mes-%'
													AND ct.idcartera=$idcartera
													WHERE ct.idcliente=c.idcliente					
											),
											-- cl.u_fecges LIKE '$ano-$mes-%',
											1,0
											) gs 
									FROM clientes cl
									JOIN cuentas c ON cl.idcliente=c.idcliente
									JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta AND cp.idperiodo=$peri and cp.idestado=1
									WHERE c.idcartera=$idcartera AND cp.idusuario=".$query->fields['idusuario']."
									GROUP BY cl.idcliente
								) AS rt
								GROUP BY rt.idcartera,rt.idusuario
							";
							
					$cs_tot=$db->Execute($sql_tw);

					$objPHPExcel->setActiveSheetIndex(0)
							
							->setCellValue('c'.$r, $query->fields['idusuario']." - ".$query->fields['usuario'])
							->setCellValue('d'.$r, $query->fields['tot_cli'])
							->setCellValue('e'.$r, $query->fields['tot_cta'])
							->setCellValue('f'.$r, number_format($query->fields['cap_t'],2,'.',','))
							->setCellValue('g'.$r, number_format($query->fields['cap_g'],2,'.',','))
							->setCellValue('h'.$r, number_format(($query->fields['cap_t']-$query->fields['cap_g']),2,'.',','))
							->setCellValue('i'.$r, $cs_tot->fields['gs'])
							->setCellValue('j'.$r, $cs_tot->fields['ns'])
							;
					$tot_cl=$tot_cl+$query->fields['tot_cli'];
					$tot_cta=$tot_cta+$query->fields['tot_cta'];
					$tot_capt=$tot_capt+$query->fields['cap_t'];
					$tot_capg=$tot_capg+$query->fields['cap_g'];
					$tot_capsg=$tot_capsg+($query->fields['cap_t']-$query->fields['cap_g']);
					$tot_gs=$tot_gs+$cs_tot->fields['gs'];
					$tot_sgs=$tot_sgs+$cs_tot->fields['ns'];
				
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
							->setCellValue('f'.$r, number_format($tot_capt,2,'.',','))
							->setCellValue('g'.$r, number_format($tot_capg,2,'.',','))
							->setCellValue('h'.$r, number_format($tot_capsg,2,'.',','))
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
		
		$objPHPExcel->getActiveSheet()->setTitle('reporte_cobertura');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

/* 
	Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="archivo.xlsx"');
	header('Cache-Control: max-age=0');
*/
header('Content-Disposition: attachment;filename="reporte_cobertura.xls"');
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
//$objWriter->save('archivo.xls');  
$objWriter->save('php://output');
//header("Location: archivo.xls");
exit;

?>