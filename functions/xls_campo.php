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
		$objPHPExcel->getDefaultStyle()->getFont()
			->setName('Arila')
			->setSize(8);
		

		$cartera=$_GET['cart'];

		
		$datos_res= array();	
		$usuarios = array();
			
			$sql="SELECT rs.usureg,u.idnivel,u.usuario,pr.idproveedor,rs.idcartera,pr.proveedor,cr.cartera,
							(
								SELECT COUNT(DISTINCT(c.idcliente)) tot_clientes 
								FROM cuentas c
								JOIN gestiones gs ON c.idcuenta=gs.idcuenta 
								WHERE gs.idactividad=4 
								AND gs.usureg=rs.usureg 
								AND gs.fecreg LIKE '2012-05-%'
								GROUP BY gs.usureg
							)tot_clientes,
							COUNT(DISTINCT(rs.idcliente)) tot_x_dia,
							SUM(rs.idcontactabilidad IN (3,16)) cont_dir,
							SUM(rs.idcontactabilidad=1) cont_tit,	
							SUM(rs.idresultado=39) tot_pro
							FROM (
								SELECT c.idcartera,c.idcliente,c.idcuenta,idcontactabilidad,idresultado,fecges,idagente,g.usureg,g.fecreg 
								FROM gestiones g
								JOIN cuentas c ON g.idcuenta=c.idcuenta
								WHERE idactividad=4 
								AND idagente  
								AND g.fecreg LIKE '2012-05-14%' GROUP BY c.idcliente,c.idcartera,g.fecreg 
							) AS rs
						JOIN usuarios u ON rs.usureg=u.idusuario
						JOIN carteras cr ON rs.idcartera=cr.idcartera
						JOIN proveedores pr ON cr.idproveedor=pr.idproveedor
						GROUP BY rs.usureg,rs.idcartera
						ORDER BY 4
						";
			
			$query=$db->Execute($sql);

		
		$r=3;
		$objPHPExcel->setActiveSheetIndex(0)
						
							->setCellValue('b'.$r, "Jefe")
							->setCellValue('c'.$r, "Supervisor")
							->setCellValue('d'.$r, "Gestor")
							->setCellValue('e'.$r, "Clientes Visitados")
							->setCellValue('f'.$r, "Total Visitas")
							->setCellValue('g'.$r, "Visitas Diarias")
							->setCellValue('h'.$r, "% Contacto Directo")
							->setCellValue('i'.$r, "% Contacto Titular")
							->setCellValue('j'.$r, "%Promesas /C.Directo")
							->setCellValue('k'.$r, "% Promesas Cumplidas")
							->setCellValue('l'.$r, "% Cobertura de Clientes")
							
							;
			$n=3;
			
		while(!$query->EOF){
			++$n;
				$proveedor=$query->fields['proveedor'];
				if($n==4){
					$idpro=$query->fields['idproveedor'];
				}
				
				if($idpro!=$query->fields['idproveedor'] or $n==4){
					$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B6', 'SGRANDEZ');
					$objPHPExcel->getActiveSheet()->mergeCells('B6:B8');
					$objPHPExcel->getActiveSheet()->getStyle('B6:B8')
					->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

					++$n;
					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('B'.$n, strtoupper($proveedor));
					
					$objPHPExcel->getActiveSheet()->getStyle('B'.$n.':B'.$n)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
					$objPHPExcel->getActiveSheet()->getStyle('B'.$n.':B'.$n)->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setRGB('808080');	
					++$n;
					$idpro=$query->fields['idproveedor'];
				}
					//$pri=$n;
					$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('d'.$n, $query->fields['usuario'])
							->setCellValue('e'.$n, $query->fields['tot_clientes'])
							->setCellValue('g'.$n, $query->fields['tot_x_dia'])
							->setCellValue('h'.$n, $query->fields['cont_dir'])
							->setCellValue('i'.$n, $query->fields['cont_tit'])
							->setCellValue('j'.$n, $query->fields['tot_pro'])
							;
							
					$objPHPExcel->getActiveSheet()->getRowDimension($n)->setOutlineLevel(2);
					
					$objPHPExcel->getActiveSheet()->getRowDimension($n)->setVisible(false);

						
					//$usuarios[$query->fields['idusuario']]=$query->fields['usuario'];
					//$datos_res[$query->fields['idusuario']][$query->fields['fecges']][$query->fields['tipo']]=$query->fields['total'];
					$query->MoveNext();
		}	
		
						
			$objPHPExcel->getActiveSheet()->getStyle('A3:L3')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
			$objPHPExcel->getActiveSheet()->getStyle('A3:L3')->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setRGB('000000');								
				
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
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

		
		$objPHPExcel->getActiveSheet()->setTitle('reporte_campo');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

/* Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="archivo.xlsx"');
header('Cache-Control: max-age=0');*/
header('Content-Disposition: attachment;filename="reporte_campo.xls"');
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
//$objWriter->save('archivo.xls');  
$objWriter->save('php://output');
//header("Location: archivo.xls");
exit;

?>