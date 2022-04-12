<?php
ini_set('memory_limit', '-1');
set_time_limit(1800);
/** Error reporting */
error_reporting(E_ALL ^ E_NOTICE);

date_default_timezone_set('Europe/London');

/** PHPExcel */
require_once '../class/PHPExcel.php';
require_once '../class/PHPExcel/IOFactory.php';
include '../scripts/conexion.php';
include 'rango_hora.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
	$periodo=$_GET['peri'];
	$proveedor=$_GET['prove'];
	
	/*$fecini=$_GET['fecini'];
	$fecfin=$_GET['fecfin'];*/
	
/*Inicio Instrucciones*/
	$objPHPExcel->getProperties()->setCreator("Kobsa - Gestion")
									 ->setLastModifiedBy("Kobsa - Gestion")
									 ->setTitle("Office 2007 XLSX Test Document")
									 ->setSubject("Office 2007 XLSX Test Document")
									 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
									 ->setKeywords("office 2007 openxml php")
									 ->setCategory("Test result file");
		
		$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
			JOIN cuentas c ON cp.idcuenta=c.idcuenta
			JOIN clientes cl ON c.idcliente=cl.idcliente
			JOIN carteras ct ON c.idcartera=ct.idcartera
			
			JOIN proveedores p ON ct.idproveedor=p.idproveedor
			WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND cp.idestado='1'
 
			";
		/*if(isset($_GET['peri'])){
			$peri=$_GET['peri'];
			$sql.=" WHERE cp.idperiodo='$peri' ";
		}		

		if(isset($_GET['prove'])){
			$prov=$_GET['prove'];
			$sql.=" AND pr.idproveedor='$prov' ";
		}
		*/
		if(isset($_GET['cart'])){
			$cartera=$_GET['cart'];
			$sql.=" AND ct.idcartera='$cartera' ";
		}

		/*if(isset($_GET['fecini']) and isset($_GET['fecfin'])){
			$ini=$_GET['fecini'];
			$fin=$_GET['fecfin'];
			$sql.=" AND DATE(g.fecreg) BETWEEN '$ini' AND '$fin' ";
		}*/

		
	//	$sql.=" ORDER BY fecreg ";
		$query=$db->Execute($sql);
							
						
		$n=1;
		$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('e1', "CANTIDAD / IMPORTE")
							->setCellValue('h1', "% DEL TOTAL")
							->setCellValue('k1', "% DEL GRUPO GESTION")
							->setCellValue('n1', "% DEL RESULTADO")
							;	
							
		$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('a2', "ID")
							->setCellValue('b2', "GRUPO GESTION")
							->setCellValue('c2', "RESULTADO")
							->setCellValue('d2', "JUSTIFICACION ")
							->setCellValue('e2', "Nctes")
							->setCellValue('f2', "Nctas")
							->setCellValue('g2', "ImpDeuda")
							->setCellValue('h2', "%Nctes")
							->setCellValue('i2', "%Nctas")
							->setCellValue('j2', "%ImpDeuda")
							->setCellValue('k2', "%Nctes")
							->setCellValue('l2', "%Nctas")
							->setCellValue('m2', "%ImpDeuda")
							->setCellValue('n2', "%Nctes")
							->setCellValue('o2', "%Nctas")
							->setCellValue('p2', "%ImpDeuda")
							->setCellValue('q2', "Exp")
							;
		$x=4;					
		$objPHPExcel->getDefaultStyle()->getFont()
			->setName('Calibri')
			->setSize(9);
				while(!$query->EOF){
					
					$objPHPExcel->setActiveSheetIndex(0)
							
							->setCellValue('b4', "Total General")
							->setCellValue('e4', $query->fields['t_ctes'])
							->setCellValue('f4', $query->fields['t_ctas'])
							->setCellValue('g4', $query->fields['t_imp'])
							->setCellValue('h4', "100.0%")
							->setCellValue('i4', "100.0%")
							->setCellValue('j4', "100.0%")
							
							;
					$objPHPExcel->getActiveSheet()->getStyle('g4')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
							
					$query->MoveNext();
				}
				
		$x=$x+2;		
		$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
				JOIN cuentas c ON cp.idcuenta=c.idcuenta
				JOIN clientes cl ON c.idcliente=cl.idcliente
				JOIN carteras ct ON c.idcartera=ct.idcartera
				JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
				JOIN resultados rs ON gt.idresultado=rs.idresultado
				JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
				JOIN proveedores p ON ct.idproveedor=p.idproveedor
				WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor'  AND gg.idgrupogestion='1'
			";	// CONTACTO EFECTIVO	
		$query=$db->Execute($sql);	
		while(!$query->EOF){
					if($query->fields['t_ctes']!=0){
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('b'.$x, "Contacto Efectivo")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, "100.0%")
								->setCellValue('L'.$x, "100.0%")
								->setCellValue('M'.$x, "100.0%")
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('b'.$x, "Contacto Efectivo")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0.00')
								->setCellValue('i'.$x, '0.00')
								->setCellValue('j'.$x, '0.00')
								->setCellValue('K'.$x, "100.0%")
								->setCellValue('L'.$x, "100.0%")
								->setCellValue('M'.$x, "100.0%")
								;

					}
					$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);					
					$query->MoveNext();
		}
		$x=$x+2;
		//**********************************************************************************
		
		$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
				JOIN cuentas c ON cp.idcuenta=c.idcuenta
				JOIN clientes cl ON c.idcliente=cl.idcliente
				JOIN carteras ct ON c.idcartera=ct.idcartera
				JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
				JOIN resultados rs ON gt.idresultado=rs.idresultado
				JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
				JOIN proveedores p ON ct.idproveedor=p.idproveedor
				WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor'  AND gg.idgrupogestion='1' AND rs.idresultado='46'
			";	// CONTACTO EFECTIVO - compromiso en tramite
		$query=$db->Execute($sql);
		while(!$query->EOF){
					if($query->fields['t_ctes']!=0){
					$objPHPExcel->setActiveSheetIndex(0)
							
							->setCellValue('c'.$x, "Compromiso en Tramite")
							->setCellValue('e'.$x, $query->fields['t_ctes'])
							->setCellValue('f'.$x, $query->fields['t_ctas'])
							->setCellValue('g'.$x, $query->fields['t_imp'])
							->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
							->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
							->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
							->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E6')
							->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F6')
							->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G6')
							->setCellValue('N'.$x, "100.0%")
							->setCellValue('o'.$x, "100.0%")
							->setCellValue('p'.$x, "100.0%")
							;
					}else{
					$objPHPExcel->setActiveSheetIndex(0)
							
							->setCellValue('c'.$x, "Compromiso en Tramite")
							->setCellValue('e'.$x, $query->fields['t_ctes'])
							->setCellValue('f'.$x, $query->fields['t_ctas'])
							->setCellValue('g'.$x, $query->fields['t_imp'])
							->setCellValue('h'.$x, '0.00')
							->setCellValue('i'.$x, '0.00')
							->setCellValue('j'.$x, '0.00')
							->setCellValue('K'.$x, '0.00')
							->setCellValue('L'.$x, '0.00')
							->setCellValue('M'.$x, '0.00')
							->setCellValue('N'.$x, "100.0%")
							->setCellValue('o'.$x, "100.0%")
							->setCellValue('p'.$x, "100.0%")
							;
			
					}
					$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
					$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
					$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					
					$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(1);
					$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(1);
					$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
					$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
					//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(false);	
					$query->MoveNext();
		}
		$x=$x+2;
		////+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor'  AND gg.idgrupogestion='1' AND rs.idresultado='46' AND j.idjustificacion='179'
					";	// CONTACTO EFECTIVO - compromiso en tramite - Documento condicionado
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Documento condicionado")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E6')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F6')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G6')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E8')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F8')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G8')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Documento condicionado")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor'  AND gg.idgrupogestion='1' AND rs.idresultado='46' AND j.idjustificacion='178'
					";	// CONTACTO EFECTIVO - compromiso en tramite - Pendiente de autorizacion
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Pendiente de autorizacion")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E6')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F6')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G6')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E8')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F8')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G8')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Pendiente de autorizacion")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor'  AND gg.idgrupogestion='1' AND rs.idresultado='46' AND j.idjustificacion='177'
					";	// CONTACTO EFECTIVO - compromiso en tramite - Pendiente de confirmacion
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Pendiente de confirmacion")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E6')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F6')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G6')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E8')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F8')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G8')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Pendiente de confirmacion")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setVisible(false);
						$query->MoveNext();
			}
		//******************************************************************************************
		$x=$x+2;
		$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
				JOIN cuentas c ON cp.idcuenta=c.idcuenta
				JOIN clientes cl ON c.idcliente=cl.idcliente
				JOIN carteras ct ON c.idcartera=ct.idcartera
				JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
				JOIN resultados rs ON gt.idresultado=rs.idresultado
				JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
				JOIN proveedores p ON ct.idproveedor=p.idproveedor
				WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor'  AND gg.idgrupogestion='1' AND rs.idresultado='2'
			";	// CONTACTO EFECTIVO - promesa de pago
		$query=$db->Execute($sql);
			while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Promesa de Pago")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E6')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F6')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G6')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Promesa de Pago")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(false);	
						$query->MoveNext();
			}
			$x=$x+2;
			////+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='1' AND rs.idresultado='2' AND j.idjustificacion='79'
					";	// CONTACTO EFECTIVO - promesa de pago - Condonacion
				$query=$db->Execute($sql);
				
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Condonacion")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E6')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F6')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G6')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E14')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F14')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G14')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Condonacion")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='1' AND rs.idresultado='2' AND j.idjustificacion='78'
					";	// CONTACTO EFECTIVO - promesa de pago - Cuota convenio
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Cuota convenio")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E6')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F6')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G6')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E14')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F14')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G14')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Cuota convenio")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='1' AND rs.idresultado='2' AND j.idjustificacion='77'
					";	// CONTACTO EFECTIVO - promesa de pago - Parcial
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Parcial")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E6')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F6')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G6')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E14')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F14')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G14')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Parcial")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='1' AND rs.idresultado='2' AND j.idjustificacion='76'
					";	// CONTACTO EFECTIVO - promesa de pago - Total
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Total")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E6')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F6')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G6')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E14')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F14')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G14')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Total")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setVisible(false);
						$query->MoveNext();
			}
	//******************************************************************************************
		$x=$x+2;
		$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
				JOIN cuentas c ON cp.idcuenta=c.idcuenta
				JOIN clientes cl ON c.idcliente=cl.idcliente
				JOIN carteras ct ON c.idcartera=ct.idcartera
				JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
				JOIN resultados rs ON gt.idresultado=rs.idresultado
				JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
				JOIN proveedores p ON ct.idproveedor=p.idproveedor
				WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='1' AND rs.idresultado='6'
			";	// CONTACTO EFECTIVO - Seguimiento
		$query=$db->Execute($sql);
			while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Seguimiento")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E6')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F6')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G6')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Seguimiento")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(false);	
						$query->MoveNext();
			}
			$x=$x+2;
			////+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='1' AND rs.idresultado='6' AND j.idjustificacion='87'
					";	// CONTACTO EFECTIVO - Seguimiento - Convenio de pago
				$query=$db->Execute($sql);
				
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Convenio de pago")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E6')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F6')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G6')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E21')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F21')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G21')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Convenio de pago")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='1' AND rs.idresultado='6' AND j.idjustificacion='86'
					";	// CONTACTO EFECTIVO - Seguimiento - En espera de aplicacion
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "En espera de aplicacion")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E6')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F6')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G6')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E21')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F21')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G21')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "En espera de aplicacion")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='1' AND rs.idresultado='6' AND j.idjustificacion='85'
					";	// CONTACTO EFECTIVO - Seguimiento - En tramite de refinanciacion
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "En tramite de refinanciacion")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E6')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F6')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G6')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E21')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F21')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G21')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "En tramite de refinanciacion")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='1' AND rs.idresultado='6' AND j.idjustificacion='152'
					";	// CONTACTO EFECTIVO - Seguimiento - Espera respuesta a propuesta de pago 
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Espera respuesta a propuesta de pago ")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E6')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F6')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G6')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E21')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F21')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G21')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Espera respuesta a propuesta de pago ")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
				}
				$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
					WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='1' AND rs.idresultado='6' AND j.idjustificacion='81'
					";	// CONTACTO EFECTIVO - Seguimiento - Firma de documentos / convenio
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Firma de documentos / convenio")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E6')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F6')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G6')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E21')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F21')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G21')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Firma de documentos / convenio")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
				}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='1' AND rs.idresultado='6' AND j.idjustificacion='84'
					";	// CONTACTO EFECTIVO - Seguimiento - Interesado en condonacion
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Interesado en condonacion")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E6')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F6')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G6')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E21')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F21')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G21')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Interesado en condonacion")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
				}
				$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='1' AND rs.idresultado='6' AND j.idjustificacion='83'
					";	// CONTACTO EFECTIVO - Seguimiento - Paga fuera de plazo
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Paga fuera de plazo")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E6')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F6')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G6')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E21')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F21')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G21')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Paga fuera de plazo")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
				}
				$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='1' AND rs.idresultado='6' AND j.idjustificacion='151'
					";	// CONTACTO EFECTIVO - Seguimiento - Presentara propuesta de pago
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Presentara propuesta de pago")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E6')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F6')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G6')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E21')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F21')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G21')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Presentara propuesta de pago")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
				}
					$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='1' AND rs.idresultado='6' AND j.idjustificacion='82'
					";	// CONTACTO EFECTIVO - Seguimiento - Recordatorio de pago
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Recordatorio de pago")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E6')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F6')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G6')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E8')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F8')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G8')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Recordatorio de pago")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
				}
						$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WWHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='1' AND rs.idresultado='6' AND j.idjustificacion='80'
					";	// CONTACTO EFECTIVO - Seguimiento - Se apersonara a agencia
				$query=$db->Execute($sql);
				
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Se apersonara a agencia")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E6')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F6')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G6')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E21')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F21')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G21')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Se apersonara a agencia")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
				
				
		
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='1' AND rs.idresultado='6' AND j.idjustificacion='89'
					";	// CONTACTO EFECTIVO - Seguimiento - Volver a llamar
				$query=$db->Execute($sql);
				
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Volver a llamar")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E6')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F6')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G6')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E8')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F8')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G8')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Volver a llamar")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setVisible(false);
				
			//******************************************************************************************
		$x=$x+2;
		$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
				JOIN cuentas c ON cp.idcuenta=c.idcuenta
				JOIN clientes cl ON c.idcliente=cl.idcliente
				JOIN carteras ct ON c.idcartera=ct.idcartera
				JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
				JOIN resultados rs ON gt.idresultado=rs.idresultado
				JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
				JOIN proveedores p ON ct.idproveedor=p.idproveedor
				WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='1' AND rs.idresultado='1'
			";	// CONTACTO EFECTIVO - ya pago
		$query=$db->Execute($sql);
			
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Promesa de Pago")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E6')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F6')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G6')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Promesa de Pago")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(false);	
					
			$x=$x+2;
			////+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='1' AND rs.idresultado='1' AND j.idjustificacion='73'
					";	// CONTACTO EFECTIVO - ya pago - Cuenta al dia
				$query=$db->Execute($sql);
				
			
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Condonacion")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E6')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F6')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G6')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E35')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F35')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G35')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Condonacion")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
				
		
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='1' AND rs.idresultado='1' AND j.idjustificacion='75'
					";	// CONTACTO EFECTIVO - ya pago - Cuota de convenio
				$query=$db->Execute($sql);
				
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Cuota convenio")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E6')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F6')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G6')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E8')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F8')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G8')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Cuota convenio")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
				
			
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='1' AND rs.idresultado='1' AND j.idjustificacion='74'
					";	// CONTACTO EFECTIVO - ya pago - Pago parcial
				$query=$db->Execute($sql);
				
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Parcial")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E6')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F6')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G6')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E35')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F35')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G35')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Parcial")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
			
			
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WWHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='1' AND rs.idresultado='1' AND j.idjustificacion='72'
					";	// CONTACTO EFECTIVO - ya pago - Pago total
				$query=$db->Execute($sql);
				
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Total")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E6')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F6')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G6')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E35')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F35')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G35')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Total")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setVisible(false);
					
	//******************************************************************************************
			//$x=$x+1;
			$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setOutlineLevel(1);
			$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setVisible(false);
	$x=$x+3;		
		$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
				JOIN cuentas c ON cp.idcuenta=c.idcuenta
				JOIN clientes cl ON c.idcliente=cl.idcliente
				JOIN carteras ct ON c.idcartera=ct.idcartera
				JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
				JOIN resultados rs ON gt.idresultado=rs.idresultado
				JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
				JOIN proveedores p ON ct.idproveedor=p.idproveedor
				WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2'
			";	// Contacto No Efectivo	
		$query=$db->Execute($sql);	
		while(!$query->EOF){
					if($query->fields['t_ctes']!=0){
					$objPHPExcel->setActiveSheetIndex(0)
							
							->setCellValue('b'.$x, "Contacto No Efectivo")
							->setCellValue('e'.$x, $query->fields['t_ctes'])
							->setCellValue('f'.$x, $query->fields['t_ctas'])
							->setCellValue('g'.$x, $query->fields['t_imp'])
							->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
							->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
							->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
							->setCellValue('K'.$x, "100.0%")
							->setCellValue('L'.$x, "100.0%")
							->setCellValue('M'.$x, "100.0%")
							
							;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
							
							->setCellValue('b'.$x, "Contacto No Efectivo")
							->setCellValue('e'.$x, $query->fields['t_ctes'])
							->setCellValue('f'.$x, $query->fields['t_ctas'])
							->setCellValue('g'.$x, $query->fields['t_imp'])
							->setCellValue('h'.$x, '0.00')
							->setCellValue('i'.$x, '0.00')
							->setCellValue('j'.$x, '0.00')
							->setCellValue('K'.$x, "100.0%")
							->setCellValue('L'.$x, "100.0%")
							->setCellValue('M'.$x, "100.0%")
							
							;
					}
					$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);					
					$query->MoveNext();
		}
				
					
	$x=$x+2;
		//**********************************************************************************
		
		$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
				JOIN cuentas c ON cp.idcuenta=c.idcuenta
				JOIN clientes cl ON c.idcliente=cl.idcliente
				JOIN carteras ct ON c.idcartera=ct.idcartera
				JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
				JOIN resultados rs ON gt.idresultado=rs.idresultado
				JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
				JOIN proveedores p ON ct.idproveedor=p.idproveedor
				WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='47'
			";	// Contacto No Efectivo	 - Contacto sin compromiso
		$query=$db->Execute($sql);
		while(!$query->EOF){
					if($query->fields['t_ctes']!=0){
					$objPHPExcel->setActiveSheetIndex(0)
							
							->setCellValue('c'.$x, "Contacto sin compromiso")
							->setCellValue('e'.$x, $query->fields['t_ctes'])
							->setCellValue('f'.$x, $query->fields['t_ctas'])
							->setCellValue('g'.$x, $query->fields['t_imp'])
							->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
							->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
							->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
							->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E6')
							->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F6')
							->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G6')
							->setCellValue('N'.$x, "100.0%")
							->setCellValue('o'.$x, "100.0%")
							->setCellValue('p'.$x, "100.0%")
							;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('c'.$x, "Contacto sin compromiso")
							->setCellValue('e'.$x, $query->fields['t_ctes'])
							->setCellValue('f'.$x, $query->fields['t_ctas'])
							->setCellValue('g'.$x, $query->fields['t_imp'])
							->setCellValue('h'.$x, '0')
							->setCellValue('i'.$x, '0')
							->setCellValue('j'.$x, '0')
							->setCellValue('K'.$x, '0')
							->setCellValue('L'.$x, '0')
							->setCellValue('M'.$x, '0')
							->setCellValue('N'.$x, "100.0%")
							->setCellValue('o'.$x, "100.0%")
							->setCellValue('p'.$x, "100.0%")
							;
					}
					$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
					$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
					$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					
					$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(1);
					$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(1);
					$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
					$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
					//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(false);	
					$query->MoveNext();
		}
		$x=$x+2;
		////+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='47' AND j.idjustificacion='181'
					";	// Contacto No Efectivo - Contacto sin compromiso - No hay programacion de pagos
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "No hay programacion de pagos")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E45')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F45')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G45')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "No hay programacion de pagos")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			
			$x=$x+1;
		
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='47' AND j.idjustificacion='180'
					";	// Contacto No Efectivo - Contacto sin compromiso - No quiere dar fecha de pago
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "No quiere dar fecha de pago")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E45')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F45')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G45')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "No quiere dar fecha de pago")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setVisible(false);
						$query->MoveNext();
			}
		//******************************************************************************************
		$x=$x+2;
		$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
				JOIN cuentas c ON cp.idcuenta=c.idcuenta
				JOIN clientes cl ON c.idcliente=cl.idcliente
				JOIN carteras ct ON c.idcartera=ct.idcartera
				JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
				JOIN resultados rs ON gt.idresultado=rs.idresultado
				JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
				JOIN proveedores p ON ct.idproveedor=p.idproveedor
				WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='7'
			";	// Contacto No Efectivo - Dificultad de pago
		$query=$db->Execute($sql);
			while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Dificultad de pago")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Dificultad de pago")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(false);	
						$query->MoveNext();
			}
			$x=$x+2;
			////+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='7' AND j.idjustificacion='155'
					";	// Contacto No Efectivo - Dificultad de pago - Cambio de razon social
				$query=$db->Execute($sql);
				
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Cambio de razon social")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E50')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F50')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G50')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Cambio de razon social")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='7' AND j.idjustificacion='90'
					";	// Contacto No Efectivo - Dificultad de pago - Efectuo pago a otro producto
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Cuota convenio")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E50')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F50')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G50')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Cuota convenio")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='7' AND j.idjustificacion='97'
					";	// Contacto No Efectivo - Dificultad de pago - Enfermedad / Accidente
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Enfermedad / Accidente")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E50')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F50')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G50')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Enfermedad / Accidente")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='7' AND j.idjustificacion='95'
					";	// Contacto No Efectivo - Dificultad de pago - Negocio quebrado / en liquidacion
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Negocio quebrado / en liquidacion")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E50')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F50')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G50')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Negocio quebrado / en liquidacion")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='7' AND j.idjustificacion='93'
					";	// Contacto No Efectivo - Dificultad de pago - Reduccion de ingresos
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Reduccion de ingresos")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E50')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F50')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G50')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Reduccion de ingresos")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='7' AND j.idjustificacion='92'
					";	// Contacto No Efectivo - Dificultad de pago - Remuneracion variable
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Remuneracion variable")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E50')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F50')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G50')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Remuneracion variable")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='7' AND j.idjustificacion='94'
					";	// Contacto No Efectivo - Dificultad de pago - Sin trabajo
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Sin trabajo")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E50')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F50')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G50')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Sin trabajo")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='7' AND j.idjustificacion='96'
					";	// Contacto No Efectivo - Dificultad de pago - Sobreendeudado
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Sobreendeudado")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E50')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F50')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G50')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Sobreendeudado")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='7' AND j.idjustificacion='91'
					";	// Contacto No Efectivo - Dificultad de pago - Trabajos eventuales
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Trabajos eventuales")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E50')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F50')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G50')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Trabajos eventuales")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setVisible(false);
						$query->MoveNext();
			}
	//******************************************************************************************
		$x=$x+2;
		$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
				JOIN cuentas c ON cp.idcuenta=c.idcuenta
				JOIN clientes cl ON c.idcliente=cl.idcliente
				JOIN carteras ct ON c.idcartera=ct.idcartera
				JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
				JOIN resultados rs ON gt.idresultado=rs.idresultado
				JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
				JOIN proveedores p ON ct.idproveedor=p.idproveedor
				WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='10'
			";	// Contacto No Efectivo - Fallecido / Invalidez
		$query=$db->Execute($sql);
			while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Fallecido / Invalidez")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Fallecido / Invalidez")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(false);	
						$query->MoveNext();
			}
			$x=$x+2;
			////+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='10' AND j.idjustificacion='108'
					";	// Contacto No Efectivo - Fallecido / Invalidez - Presentaron papeles
				$query=$db->Execute($sql);
				
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Presentaron papeles")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E62')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F62')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G62')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Presentaron papeles")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}

			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='10' AND j.idjustificacion='107'
					";	// Contacto No Efectivo - Fallecido / Invalidez - Sin papeles presentados
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Sin papeles presentados")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E62')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F62')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G62')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Sin papeles presentados")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setVisible(false);
						$query->MoveNext();
			}
			//******************************************************************************************
		$x=$x+2;
		$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
				JOIN cuentas c ON cp.idcuenta=c.idcuenta
				JOIN clientes cl ON c.idcliente=cl.idcliente
				JOIN carteras ct ON c.idcartera=ct.idcartera
				JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
				JOIN resultados rs ON gt.idresultado=rs.idresultado
				JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
				JOIN proveedores p ON ct.idproveedor=p.idproveedor
				WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='11'
			";	// Contacto No Efectivo - Mensaje a Terceros
		$query=$db->Execute($sql);
			while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Mensaje a Terceros")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Mensaje a Terceros")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(false);	
						$query->MoveNext();
			}
			$x=$x+2;
			////+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='11' AND j.idjustificacion='183'
					";	// Contacto No Efectivo - Mensaje a Terceros - Asistente
				$query=$db->Execute($sql);
				
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Asistente")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E67')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F67')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G67')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Asistente")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='11' AND j.idjustificacion='115'
					";	// Contacto No Efectivo - Mensaje a Terceros - De viaje en el extranjero
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "De viaje en el extranjero")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E67')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F67')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G67')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "De viaje en el extranjero")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='11' AND j.idjustificacion='114'
					";	// Contacto No Efectivo - Mensaje a Terceros - De viaje en provincia
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "De viaje en provincia")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E67')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F67')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G67')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "De viaje en provincia")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='11' AND j.idjustificacion='185'
					";	// Contacto No Efectivo - Mensaje a Terceros - Familiares
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Familiares")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E67')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F67')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G67')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Familiares")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='11' AND j.idjustificacion='109'
					";	// Contacto No Efectivo - Mensaje a Terceros - Labora en la empresa
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Labora en la empresa")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E67')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F67')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G67')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Labora en la empresa")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='11' AND j.idjustificacion='153'
					";	// Contacto No Efectivo - Mensaje a Terceros - Mensaje con conyuge
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Mensaje con conyuge")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E67')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F67')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G67')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Mensaje con conyuge")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='11' AND j.idjustificacion='111'
					";	// Contacto No Efectivo - Mensaje a Terceros - No vive en el domicilio
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "No vive en el domicilio")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E67')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F67')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G67')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "No vive en el domicilio")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='11' AND j.idjustificacion='186'
					";	// Contacto No Efectivo - Mensaje a Terceros - Otros terceros
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Otros terceros")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E67')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F67')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G67')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Otros terceros")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='11' AND j.idjustificacion='184'
					";	// Contacto No Efectivo - Mensaje a Terceros - Recepcionista
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Recepcionista")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E67')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F67')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G67')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Recepcionista")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='11' AND j.idjustificacion='182'
					";	// Contacto No Efectivo - Mensaje a Terceros - Secretaria
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Secretaria")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E67')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F67')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G67')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Secretaria")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='11' AND j.idjustificacion='110'
					";	// Contacto No Efectivo - Mensaje a Terceros - Vive en el domicilio
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Vive en el domicilio")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E67')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F67')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G67')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Vive en el domicilio")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						//$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setVisible(false);
						$query->MoveNext();
			}
	$x=$x+2;
		$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
				JOIN cuentas c ON cp.idcuenta=c.idcuenta
				JOIN clientes cl ON c.idcliente=cl.idcliente
				JOIN carteras ct ON c.idcartera=ct.idcartera
				JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
				JOIN resultados rs ON gt.idresultado=rs.idresultado
				JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
				JOIN proveedores p ON ct.idproveedor=p.idproveedor
				WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='9'
			";	// Contacto No Efectivo - Reclamo
		$query=$db->Execute($sql);
			while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Reclamo")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Reclamo")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(false);	
						$query->MoveNext();
			}
			$x=$x+2;
			////+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='9' AND j.idjustificacion='156'
					";	// Contacto No Efectivo - Reclamo- Aseguradora
				$query=$db->Execute($sql);
				
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Aseguradora")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E81')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F81')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G81')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Aseguradora")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='9' AND j.idjustificacion='154'
					";	// Contacto No Efectivo - Reclamo- Esperando respuesta a reclamo
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Esperando respuesta a reclamo")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E81')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F81')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G81')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Esperando respuesta a reclamo")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='9' AND j.idjustificacion='104'
					";	// Contacto No Efectivo - Reclamo- Indecopi
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Indecopi")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E81')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F81')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G81')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Indecopi")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='9' AND j.idjustificacion='106'
					";	// Contacto No Efectivo - Reclamo- Misma institucion
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Misma institucion")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E81')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F81')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G81')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Misma institucion")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='9' AND j.idjustificacion='105'
					";	// Contacto No Efectivo - Reclamo - SBS
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "SBS")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E81')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F81')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G81')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "SBS")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						//$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setVisible(false);
						$query->MoveNext();
			}

	$x=$x+2;
		$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
				JOIN cuentas c ON cp.idcuenta=c.idcuenta
				JOIN clientes cl ON c.idcliente=cl.idcliente
				JOIN carteras ct ON c.idcartera=ct.idcartera
				JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
				JOIN resultados rs ON gt.idresultado=rs.idresultado
				JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
				JOIN proveedores p ON ct.idproveedor=p.idproveedor
				WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='17'
			";	// Contacto No Efectivo - Renuente
		$query=$db->Execute($sql);
			while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Renuente")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Renuente")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(false);	
						$query->MoveNext();
			}
			$x=$x+2;
			////+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='17' AND j.idjustificacion='100'
					";	// Contacto No Efectivo - Renuente- Desacuerdo con lo facturado
				$query=$db->Execute($sql);
				
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Desacuerdo con lo facturado")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E81')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F81')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G81')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Desacuerdo con lo facturado")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='17' AND j.idjustificacion='173'
					";	// Contacto No Efectivo - Renuente - Falta de liquidez
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Falta de liquidez")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E89')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F89')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G89')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Falta de liquidez")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='17' AND j.idjustificacion='98'
					";	// Contacto No Efectivo - Renuente - No llega estado de cuenta
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "No llega estado de cuenta")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E89')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F89')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G89')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "No llega estado de cuenta")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='17' AND j.idjustificacion='99'
					";	// Contacto No Efectivo - Renuente - No reconoce deuda
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "No reconoce deuda")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E89')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F89')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G89')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "No reconoce deuda")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='17' AND j.idjustificacion='103'
					";	// Contacto No Efectivo - Renuente - Pase a etapa judicial
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Pase a etapa judicial")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E89')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F89')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G89')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Pase a etapa judicial")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='17' AND j.idjustificacion='102'
					";	// Contacto No Efectivo - Renuente - Rehuye al pago
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Rehuye al pago")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E89')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F89')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G89')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Rehuye al pago")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
								
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='17' AND j.idjustificacion='101'
					";	// Contacto No Efectivo - Renuente - Solo coordinara empresa/cliente
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Solo coordinara empresa/cliente")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E43')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F43')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G43')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E89')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F89')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G89')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Solo coordinara empresa/cliente")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						//$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setVisible(false);
						$query->MoveNext();
			}
					
			
			$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setOutlineLevel(1);
			$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setVisible(false);
		$x=$x+3;		
		$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
				JOIN cuentas c ON cp.idcuenta=c.idcuenta
				JOIN clientes cl ON c.idcliente=cl.idcliente
				JOIN carteras ct ON c.idcartera=ct.idcartera
				JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
				JOIN resultados rs ON gt.idresultado=rs.idresultado
				JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
				JOIN proveedores p ON ct.idproveedor=p.idproveedor
				WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='4'
			";	// No Contacto	
		$query=$db->Execute($sql);	
		while(!$query->EOF){
					if($query->fields['t_ctes']!=0){
					$objPHPExcel->setActiveSheetIndex(0)
							
							->setCellValue('b'.$x, "No Contacto")
							->setCellValue('e'.$x, $query->fields['t_ctes'])
							->setCellValue('f'.$x, $query->fields['t_ctas'])
							->setCellValue('g'.$x, $query->fields['t_imp'])
							->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
							->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
							->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
							->setCellValue('K'.$x, "100.0%")
							->setCellValue('L'.$x, "100.0%")
							->setCellValue('M'.$x, "100.0%");
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
							
							->setCellValue('b'.$x, "No Contacto")
							->setCellValue('e'.$x, $query->fields['t_ctes'])
							->setCellValue('f'.$x, $query->fields['t_ctas'])
							->setCellValue('g'.$x, $query->fields['t_imp'])
							->setCellValue('h'.$x, '0')
							->setCellValue('i'.$x, '0')
							->setCellValue('j'.$x, '0')
							->setCellValue('K'.$x, "100.0%")
							->setCellValue('L'.$x, "100.0%")
							->setCellValue('M'.$x, "100.0%");
					
					
					}		
							
					$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);					
					$query->MoveNext();
		}
		
	//******************************************************************************************
		$x=$x+2;
		$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
				JOIN cuentas c ON cp.idcuenta=c.idcuenta
				JOIN clientes cl ON c.idcliente=cl.idcliente
				JOIN carteras ct ON c.idcartera=ct.idcartera
				JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
				JOIN resultados rs ON gt.idresultado=rs.idresultado
				JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
				JOIN proveedores p ON ct.idproveedor=p.idproveedor
				WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='4' AND rs.idresultado='12'
			";	// No Contacto	 - No Contacto	
		$query=$db->Execute($sql);
			while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "No Contacto")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E100')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F100')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G100')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "No Contacto")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(false);	
						$query->MoveNext();
			}
			$x=$x+2;
			////+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='4' AND rs.idresultado='12' AND j.idjustificacion='120'
					";	// No Contacto	 - No Contacto	- Buzon de voz
				$query=$db->Execute($sql);
				
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Buzon de voz")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E100')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F100')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G100')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E102')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F102')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G102')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Buzon de voz")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='4' AND rs.idresultado='12' AND j.idjustificacion='119'
					";	// No Contacto	 - No Contacto	- Contestan pero cuelgan
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Contestan pero cuelgan")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E100')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F100')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G100')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E102')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F102')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G102')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Contestan pero cuelgan")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='4' AND rs.idresultado='12' AND j.idjustificacion='116'
					";	//  No Contacto	 - No Contacto	- Destacado a provincia
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Destacado a provincia")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E100')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F100')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G100')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E102')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F102')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G102')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Destacado a provincia")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='4' AND rs.idresultado='12' AND j.idjustificacion='117'
					";	// No Contacto	 - No Contacto	- Destacado al extranjero
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Destacado al extranjero")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E100')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F100')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G100')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E102')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F102')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G102')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Destacado al extranjero")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='4' AND rs.idresultado='12' AND j.idjustificacion='175'
					";	// No Contacto	 - No Contacto	- Envio de carta
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Envio de carta")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E100')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F100')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G100')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E102')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F102')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G102')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Envio de carta")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='4' AND rs.idresultado='12' AND j.idjustificacion='187'
					";	// No Contacto	 - No Contacto	- Envio de email
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Envio de email")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E100')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F100')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G100')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E102')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F102')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G102')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Envio de email")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='4' AND rs.idresultado='12' AND j.idjustificacion='188'
					";	// No Contacto	 - No Contacto	- Envio de SMS
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Envio de SMS")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E100')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F100')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G100')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E102')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F102')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G102')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Envio de SMS")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='4' AND rs.idresultado='12' AND j.idjustificacion='112'
					";	// No Contacto	 - No Contacto	- No pueden pasar llamada
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "No pueden pasar llamada")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E100')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F100')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G100')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E102')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F102')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G102')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "No pueden pasar llamada")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='4' AND rs.idresultado='12' AND j.idjustificacion='113'
					";	// No Contacto	 - No Contacto	- No se puede dejar mensaje
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "No se puede dejar mensaje")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E100')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F100')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G100')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E102')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F102')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G102')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "No se puede dejar mensaje")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='4' AND rs.idresultado='12' AND j.idjustificacion='174'
					";	// No Contacto	 - No Contacto - Telefono apagado
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Telefono apagado")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E100')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F100')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G100')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E102')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F102')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G102')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Telefono apagado")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
	
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='4' AND rs.idresultado='12' AND j.idjustificacion='118'
					";	// No Contacto	 - No Contacto - Telefono errado pero lo conocen
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Telefono errado pero lo conocen")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E100')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F100')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G100')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E102')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F102')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G102')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Telefono errado pero lo conocen")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='4' AND rs.idresultado='12' AND j.idjustificacion='122'
					";	// No Contacto	 - No Contacto - Telefono no contesta
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Telefono no contesta")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E100')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F100')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G100')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E102')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F102')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G102')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Telefono no contesta")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='4' AND rs.idresultado='12' AND j.idjustificacion='121'
					";	// No Contacto	 - No Contacto - Telefono ocupado
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Telefono ocupado")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E100')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F100')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G100')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E102')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F102')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G102')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Telefono ocupado")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						//$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setVisible(false);
						$query->MoveNext();
			}
		
			$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setOutlineLevel(1);
			$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setVisible(false);
		$x=$x+3;		
		$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
				JOIN cuentas c ON cp.idcuenta=c.idcuenta
				JOIN clientes cl ON c.idcliente=cl.idcliente
				JOIN carteras ct ON c.idcartera=ct.idcartera
				JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
				JOIN resultados rs ON gt.idresultado=rs.idresultado
				JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
				JOIN proveedores p ON ct.idproveedor=p.idproveedor
				WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='3'
			";	// Ilocalizado	
		$query=$db->Execute($sql);	
		while(!$query->EOF){
				if($query->fields['t_ctes']!=0){
					$objPHPExcel->setActiveSheetIndex(0)
							
							->setCellValue('b'.$x, "Ilocalizado")
							->setCellValue('e'.$x, $query->fields['t_ctes'])
							->setCellValue('f'.$x, $query->fields['t_ctas'])
							->setCellValue('g'.$x, $query->fields['t_imp'])
							->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
							->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
							->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
							->setCellValue('K'.$x, "100.0%")
							->setCellValue('L'.$x, "100.0%")
							->setCellValue('M'.$x, "100.0%");
				}else{
					$objPHPExcel->setActiveSheetIndex(0)
							
							->setCellValue('b'.$x, "Ilocalizado")
							->setCellValue('e'.$x, $query->fields['t_ctes'])
							->setCellValue('f'.$x, $query->fields['t_ctas'])
							->setCellValue('g'.$x, $query->fields['t_imp'])
							->setCellValue('h'.$x, '0')
							->setCellValue('i'.$x, '0')
							->setCellValue('j'.$x, '0')
							->setCellValue('K'.$x, "100.0%")
							->setCellValue('L'.$x, "100.0%")
							->setCellValue('M'.$x, "100.0%");
				
				
				
				
				}			
							
					$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);					
					$query->MoveNext();
		}
		
		
	//******************************************************************************************
		$x=$x+2;
		$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
				JOIN cuentas c ON cp.idcuenta=c.idcuenta
				JOIN clientes cl ON c.idcliente=cl.idcliente
				JOIN carteras ct ON c.idcartera=ct.idcartera
				JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
				JOIN resultados rs ON gt.idresultado=rs.idresultado
				JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
				JOIN proveedores p ON ct.idproveedor=p.idproveedor
				WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='3' AND rs.idresultado='13'
			";	// Ilocalizado	- Ilocalizado
		$query=$db->Execute($sql);
			while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Ilocalizado")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E119')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F119')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G119')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Ilocalizado")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;
	
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(false);	
						$query->MoveNext();
			}
			$x=$x+2;
			////+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='3' AND rs.idresultado='13' AND j.idjustificacion='176'
					";	// Ilocalizado	- Ilocalizado - Empresa inactiva
				$query=$db->Execute($sql);
				
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Empresa inactiva")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E119')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F119')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G119')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E121')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F121')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G121')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Empresa inactiva")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
								
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='3' AND rs.idresultado='13' AND j.idjustificacion='123'
					";	// Ilocalizado	- Ilocalizado - Fax
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Fax")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E119')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F119')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G119')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E121')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F121')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G121')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Fax")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
	
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='3' AND rs.idresultado='13' AND j.idjustificacion='125'
					";	//  Ilocalizado	- Ilocalizado - Se mudo / ya no trabaja
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Se mudo / ya no trabaja")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E119')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F119')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G119')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E121')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F121')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G121')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Se mudo / ya no trabaja")
								->setCellValue('d'.$x, "Se mudo / ya no trabaja")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='3' AND rs.idresultado='13' AND j.idjustificacion='129'
					";	// Ilocalizado	- Ilocalizado - Sin telefono
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Sin telefono")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E119')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F119')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G119')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E121')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F121')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G121')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Sin telefono")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='3' AND rs.idresultado='13' AND j.idjustificacion='126'
					";	// Ilocalizado	- Ilocalizado - Telefono errado y no lo conocen
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Telefono errado y no lo conocen")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E119')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F119')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G119')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E121')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F121')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G121')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Telefono errado y no lo conocen")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='3' AND rs.idresultado='13' AND j.idjustificacion='127'
					";	// Ilocalizado	- Ilocalizado - Telefono fuera de servicio
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Telefono fuera de servicio")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E119')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F119')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G119')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E121')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F121')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G121')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Telefono fuera de servicio")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='3' AND rs.idresultado='13' AND j.idjustificacion='128'
					";	// Ilocalizado	- Ilocalizado - Telefono no existe
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Telefono no existe")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E119')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F119')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G119')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E121')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F121')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G121')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Telefono no existe")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='2' AND rs.idresultado='13' AND j.idjustificacion='124'
					";	// Ilocalizado	- Ilocalizado - Titular reside en el extranjero
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Titular reside en el extranjero")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E119')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F119')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G119')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E121')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F121')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G121')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Titular reside en el extranjero")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						//$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setVisible(false);
						$query->MoveNext();
			}
		
			$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setOutlineLevel(1);
			$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setVisible(false);
		$x=$x+3;		
		$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
				JOIN cuentas c ON cp.idcuenta=c.idcuenta
				JOIN clientes cl ON c.idcliente=cl.idcliente
				JOIN carteras ct ON c.idcartera=ct.idcartera
				JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
				JOIN resultados rs ON gt.idresultado=rs.idresultado
				JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
				JOIN proveedores p ON ct.idproveedor=p.idproveedor
				WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='5'
			";	// Busqueda
		$query=$db->Execute($sql);	
		while(!$query->EOF){
				if($query->fields['t_ctes']!=0){
					$objPHPExcel->setActiveSheetIndex(0)
							
							->setCellValue('b'.$x, "Busqueda")
							->setCellValue('e'.$x, $query->fields['t_ctes'])
							->setCellValue('f'.$x, $query->fields['t_ctas'])
							->setCellValue('g'.$x, $query->fields['t_imp'])
							->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
							->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
							->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
							->setCellValue('K'.$x, "100.0%")
							->setCellValue('L'.$x, "100.0%")
							->setCellValue('M'.$x, "100.0%");
				}else{
					$objPHPExcel->setActiveSheetIndex(0)
							
							->setCellValue('b'.$x, "Busqueda")
							->setCellValue('e'.$x, $query->fields['t_ctes'])
							->setCellValue('f'.$x, $query->fields['t_ctas'])
							->setCellValue('g'.$x, $query->fields['t_imp'])
							->setCellValue('h'.$x, '0')
							->setCellValue('i'.$x, '0')
							->setCellValue('j'.$x, '0')
							->setCellValue('K'.$x, "100.0%")
							->setCellValue('L'.$x, "100.0%")
							->setCellValue('M'.$x, "100.0%");

				}			
							
					$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);					
					$query->MoveNext();
		}
		
		
	//******************************************************************************************
		$x=$x+2;
		$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
				JOIN cuentas c ON cp.idcuenta=c.idcuenta
				JOIN clientes cl ON c.idcliente=cl.idcliente
				JOIN carteras ct ON c.idcartera=ct.idcartera
				JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
				JOIN resultados rs ON gt.idresultado=rs.idresultado
				JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
				JOIN proveedores p ON ct.idproveedor=p.idproveedor
				WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='5' AND rs.idresultado='15'
			";	// Busqueda	- Busqueda Externa
		$query=$db->Execute($sql);
			while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Busqueda Externa")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E133')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F133')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G133')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Busqueda")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;
	
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(false);	
						$query->MoveNext();
			}
			$x=$x+2;
			////+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
					WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='5' AND rs.idresultado='15' AND j.idjustificacion='134'
					";	// Busqueda	- Busqueda Externa - Correccion de datos
				$query=$db->Execute($sql);
				
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Correccion de datos")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E133')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F133')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G133')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E135')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F135')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G135')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Correccion de datos")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
								
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='5' AND rs.idresultado='15' AND j.idjustificacion='135'
					";	// Busqueda	- Busqueda Externa - Nueva direccion
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Nueva direccion")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E133')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F133')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G133')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E135')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F135')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G135')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Nueva direccion")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
	
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='5' AND rs.idresultado='15' AND j.idjustificacion='137'
					";	//  Busqueda	- Busqueda Externa - Nueva direccion y telefono
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Nueva direccion y telefono")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E133')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F133')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G133')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E135')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F135')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G135')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Nueva direccion y telefono")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='5' AND rs.idresultado='15' AND j.idjustificacion='136'
					";	// Busqueda	- Busqueda Externa - Nuevo telefono
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Nuevo telefono")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E133')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F133')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G133')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E135')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F135')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G135')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Nuevo telefono")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='5' AND rs.idresultado='15' AND j.idjustificacion='138'
					";	// Busqueda	- Busqueda Externa - Sin datos nuevos
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Sin datos nuevos")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E133')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F133')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G133')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E135')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F135')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G135')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Sin datos nuevos")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						//$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setVisible(false);
						$query->MoveNext();
			}
				$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setOutlineLevel(1);
			$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setVisible(false);
	$x=$x+3;		
		$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
				JOIN cuentas c ON cp.idcuenta=c.idcuenta
				JOIN clientes cl ON c.idcliente=cl.idcliente
				JOIN carteras ct ON c.idcartera=ct.idcartera
				JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
				JOIN resultados rs ON gt.idresultado=rs.idresultado
				JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
				JOIN proveedores p ON ct.idproveedor=p.idproveedor
				WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6'
			";	// Procedimientos	
		$query=$db->Execute($sql);	
		while(!$query->EOF){
					if($query->fields['t_ctes']!=0){
					
					$objPHPExcel->setActiveSheetIndex(0)
							
							->setCellValue('b'.$x, "Procedimientos")
							->setCellValue('e'.$x, $query->fields['t_ctes'])
							->setCellValue('f'.$x, $query->fields['t_ctas'])
							->setCellValue('g'.$x, $query->fields['t_imp'])
							->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
							->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
							->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
							->setCellValue('K'.$x, "100.0%")
							->setCellValue('L'.$x, "100.0%")
							->setCellValue('M'.$x, "100.0%")
							;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('b'.$x, "Procedimientos")
							->setCellValue('e'.$x, "0")
							->setCellValue('f'.$x, "0")
							->setCellValue('g'.$x, "0")
							->setCellValue('h'.$x, "0")
							->setCellValue('i'.$x, "0")
							->setCellValue('j'.$x, "0")
							->setCellValue('K'.$x, "100.0%")
							->setCellValue('L'.$x, "100.0%")
							->setCellValue('M'.$x, "100.0%")
							;
					
					}
					$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);					
					$query->MoveNext();
		}
				
					
	$x=$x+2;
		//**********************************************************************************
		
		$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
				JOIN cuentas c ON cp.idcuenta=c.idcuenta
				JOIN clientes cl ON c.idcliente=cl.idcliente
				JOIN carteras ct ON c.idcartera=ct.idcartera
				JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
				JOIN resultados rs ON gt.idresultado=rs.idresultado
				JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
				JOIN proveedores p ON ct.idproveedor=p.idproveedor
				WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='14'
			";	// Procedimientos	 - Actualizacion de datos
		$query=$db->Execute($sql);
		while(!$query->EOF){
					if($query->fields['t_ctes']!=0){
					$objPHPExcel->setActiveSheetIndex(0)
							
							->setCellValue('c'.$x, "Actualizacion de datos")
							->setCellValue('e'.$x, $query->fields['t_ctes'])
							->setCellValue('f'.$x, $query->fields['t_ctas'])
							->setCellValue('g'.$x, $query->fields['t_imp'])
							->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
							->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
							->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
							->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
							->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
							->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
							->setCellValue('N'.$x, "100.0%")
							->setCellValue('o'.$x, "100.0%")
							->setCellValue('p'.$x, "100.0%")
							;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('c'.$x, "Actualizacion de datos")
							->setCellValue('e'.$x, $query->fields['t_ctes'])
							->setCellValue('f'.$x, $query->fields['t_ctas'])
							->setCellValue('g'.$x, $query->fields['t_imp'])
							->setCellValue('h'.$x, '0')
							->setCellValue('i'.$x, '0')
							->setCellValue('j'.$x, '0')
							->setCellValue('K'.$x, '0')
							->setCellValue('L'.$x, '0')
							->setCellValue('M'.$x, '0')
							->setCellValue('N'.$x, "100.0%")
							->setCellValue('o'.$x, "100.0%")
							->setCellValue('p'.$x, "100.0%")
							;
					}
					$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
					$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
					$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					
					$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(1);
					$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(1);
					$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
					$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
					//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(false);	
					$query->MoveNext();
		}
		$x=$x+2;
		////+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='14' AND j.idjustificacion='130'
					";	// Procedimientos	 - Actualizacion de datos - Activar direccion
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Activar direccion")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E146')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F146')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G146')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Activar direccion")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			
			$x=$x+1;
		////+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='14' AND j.idjustificacion='131'
					";	// Procedimientos	 - Actualizacion de datos - Activar telefono
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Activar telefono")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E146')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F146')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G146')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Activar telefono")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			
			$x=$x+1;

////+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='14' AND j.idjustificacion='132'
					";	// Procedimientos	 - Actualizacion de datos - Desactivar direccion
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Desactivar direccion")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E146')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F146')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G146')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Desactivar direccion")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			
			$x=$x+1;			
		
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='14' AND j.idjustificacion='133'
					";	// Procedimientos	 - Actualizacion de datos - Desactivar telefono
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Desactivar telefono")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E146')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F146')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G146')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Desactivar telefono")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setVisible(false);
						$query->MoveNext();
			}
		//******************************************************************************************
		$x=$x+2;
		$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
				JOIN cuentas c ON cp.idcuenta=c.idcuenta
				JOIN clientes cl ON c.idcliente=cl.idcliente
				JOIN carteras ct ON c.idcartera=ct.idcartera
				JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
				JOIN resultados rs ON gt.idresultado=rs.idresultado
				JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
				JOIN proveedores p ON ct.idproveedor=p.idproveedor
				WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='19'
			";	// Procedimientos  - Cobranza preventiva
		$query=$db->Execute($sql);
			while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Cobranza preventiva")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Cobranza preventiva")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(false);	
						$query->MoveNext();
			}
			$x=$x+2;
			////+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='19' AND j.idjustificacion='160'
					";	// Procedimientos  - Cobranza preventiva - Con promesa de pago
				$query=$db->Execute($sql);
				
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Con promesa de pago")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E153')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F153')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G153')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Con promesa de pago")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='19' AND j.idjustificacion='161'
					";	// Procedimientos  - Cobranza preventiva - Sin promesa de pago
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Sin promesa de pago")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E153')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F153')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G153')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Sin promesa de pago")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setVisible(false);
						$query->MoveNext();
			}
	//******************************************************************************************
		$x=$x+2;
		$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
				JOIN cuentas c ON cp.idcuenta=c.idcuenta
				JOIN clientes cl ON c.idcliente=cl.idcliente
				JOIN carteras ct ON c.idcartera=ct.idcartera
				JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
				JOIN resultados rs ON gt.idresultado=rs.idresultado
				JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
				JOIN proveedores p ON ct.idproveedor=p.idproveedor
				WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='20'
			";	// Procedimientos - Confirmacion de entrega
		$query=$db->Execute($sql);
			while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Confirmacion de entrega")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Confirmacion de entrega")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(false);	
						$query->MoveNext();
			}
			$x=$x+2;
			////+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='20' AND j.idjustificacion='167'
					";	// Procedimientos - Confirmacion de entrega - Contabilidad
				$query=$db->Execute($sql);
				
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Contabilidad")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E158')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F158')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G158')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Contabilidad")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}

			$x=$x+1;
			////+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='20' AND j.idjustificacion='165'
					";	// Procedimientos - Confirmacion de entrega - en Tesoreria
				$query=$db->Execute($sql);
				
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "en Tesoreria")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E158')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F158')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G158')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "en Tesoreria")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}

			$x=$x+1;
			////+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='20' AND j.idjustificacion='166'
					";	// Procedimientos - Confirmacion de entrega - Finanzas
				$query=$db->Execute($sql);
				
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Finanzas")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E158')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F158')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G158')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Finanzas")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}

			$x=$x+1;
			////+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='20' AND j.idjustificacion='168'
					";	// Procedimientos - Confirmacion de entrega - Otra area
				$query=$db->Execute($sql);
				
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Otra area")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E158')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F158')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G158')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Otra area")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}

			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='20' AND j.idjustificacion='164'
					";	// Procedimientos - Confirmacion de entrega - Pago proveedores
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Pago proveedores")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E158')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F158')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G158')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Pago proveedores")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setVisible(false);
						$query->MoveNext();
			}
			//******************************************************************************************
		$x=$x+2;
		$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
				JOIN cuentas c ON cp.idcuenta=c.idcuenta
				JOIN clientes cl ON c.idcliente=cl.idcliente
				JOIN carteras ct ON c.idcartera=ct.idcartera
				JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
				JOIN resultados rs ON gt.idresultado=rs.idresultado
				JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
				JOIN proveedores p ON ct.idproveedor=p.idproveedor
				WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='18'
			";	// Procedimientos - Dejar de gestionar
		$query=$db->Execute($sql);
			while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Dejar de gestionar")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Dejar de gestionar")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(false);	
						$query->MoveNext();
			}
			$x=$x+2;
			////+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='18' AND j.idjustificacion='140'
					";	// Procedimientos - Dejar de gestionar - A orden de proveedor
				$query=$db->Execute($sql);
				
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "A orden de proveedor")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E166')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F166')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G166')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "A orden de proveedor")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='18' AND j.idjustificacion='143'
					";	// Procedimientos - Dejar de gestionar - Anulada
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Anulada")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E166')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F166')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G166')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Anulada")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='18' AND j.idjustificacion='139'
					";	// Procedimientos - Dejar de gestionar - Deuda ya cancelada
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Deuda ya cancelada")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E166')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F166')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G166')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Deuda ya cancelada")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
					
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='18' AND j.idjustificacion='144'
					";	// Procedimientos - Dejar de gestionar - Observada
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Vive en el domicilio")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E166')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F166')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G166')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Vive en el domicilio")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						//$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setVisible(false);
						$query->MoveNext();
			}
	$x=$x+2;
		$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
				JOIN cuentas c ON cp.idcuenta=c.idcuenta
				JOIN clientes cl ON c.idcliente=cl.idcliente
				JOIN carteras ct ON c.idcartera=ct.idcartera
				JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
				JOIN resultados rs ON gt.idresultado=rs.idresultado
				JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
				JOIN proveedores p ON ct.idproveedor=p.idproveedor
				WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='45'
			";	// Procedimientos - Documento en transito
		$query=$db->Execute($sql);
			while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Documento en transito")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Documento en transito")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(false);	
						$query->MoveNext();
			}
			$x=$x+2;
			////+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='45' AND j.idjustificacion='162'
					";	// Procedimientos - Documento en transito- No entregado al cliente
				$query=$db->Execute($sql);
				
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "No entregado al cliente")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E173')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F173')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G173')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "No entregado al cliente")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='45' AND j.idjustificacion='163'
					";	// Procedimientos - Documento en transito- No recibido por el encargado
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "No recibido por el encargado")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E173')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F173')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G173')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "No recibido por el encargado")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						//$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setVisible(false);
						$query->MoveNext();
			}
	$x=$x+2;
		$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
				JOIN cuentas c ON cp.idcuenta=c.idcuenta
				JOIN clientes cl ON c.idcliente=cl.idcliente
				JOIN carteras ct ON c.idcartera=ct.idcartera
				JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
				JOIN resultados rs ON gt.idresultado=rs.idresultado
				JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
				JOIN proveedores p ON ct.idproveedor=p.idproveedor
				WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='16'
			";	// Procedimientos - Entrega de facturas
		$query=$db->Execute($sql);
			while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Entrega de facturas")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Entrega de facturas")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(false);	
						$query->MoveNext();
			}
			$x=$x+2;
			////+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='16' AND j.idjustificacion='142'
					";	// Procedimientos - Entrega de facturas - Encargado
				$query=$db->Execute($sql);
				
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Encargado")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E178')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F178')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G178')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Encargado")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		$x=$x+1;
			////+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='16' AND j.idjustificacion='157'
					";	// Procedimientos - Entrega de facturas - Mesa de partes
				$query=$db->Execute($sql);
				
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Mesa de partes")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E178')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F178')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G178')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Mesa de partes")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		$x=$x+1;
			////+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='16' AND j.idjustificacion='159'
					";	// Procedimientos - Entrega de facturas - Otros
				$query=$db->Execute($sql);
				
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Otros")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E178')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F178')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G178')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Otros")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		$x=$x+1;
			////+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='16' AND j.idjustificacion='158'
					";	// Procedimientos - Entrega de facturas - Recepcion
				$query=$db->Execute($sql);
				
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Recepcion")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E178')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F178')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G178')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Recepcion")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='16' AND j.idjustificacion='141'
					";	// Procedimientos - Entrega de facturas - Tesoreria
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Tesoreria")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E178')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F178')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G178')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Tesoreria")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						//$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setVisible(false);
						$query->MoveNext();
			}
    
	$x=$x+2;
		$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
				JOIN cuentas c ON cp.idcuenta=c.idcuenta
				JOIN clientes cl ON c.idcliente=cl.idcliente
				JOIN carteras ct ON c.idcartera=ct.idcartera
				JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
				JOIN resultados rs ON gt.idresultado=rs.idresultado
				JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
				JOIN proveedores p ON ct.idproveedor=p.idproveedor
				WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='22'
			";	// Procedimientos - Envio de Carta
		$query=$db->Execute($sql);
			while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Envio de Carta")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Envio de Carta")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(false);	
						$query->MoveNext();
			}
			$x=$x+2;
			////+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='22' AND j.idjustificacion='169'
					";	// Procedimientos - Envio de Carta - Aviso de cobranza
				$query=$db->Execute($sql);
				
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Aviso de cobranza")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E186')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F186')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G186')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Aviso de cobranza")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='22' AND j.idjustificacion='170'
					";	// Procedimientos - Envio de Carta - Carta notarial
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Carta notarial")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E186')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F186')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G186')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Carta notarial")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						//$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setVisible(false);
						$query->MoveNext();
			}
    	$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='22' AND j.idjustificacion='171'
					";	// Procedimientos - Envio de Carta - Comunicados
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Comunicados")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E186')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F186')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G186')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Comunicados")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						//$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setVisible(false);
						$query->MoveNext();
			}
    
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='22' AND j.idjustificacion='172'
					";	// Procedimientos - Envio de Carta - Courier
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Courier")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E186')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F186')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G186')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Courier")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						//$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setVisible(false);
						$query->MoveNext();
			}

	$x=$x+2;
		$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
				JOIN cuentas c ON cp.idcuenta=c.idcuenta
				JOIN clientes cl ON c.idcliente=cl.idcliente
				JOIN carteras ct ON c.idcartera=ct.idcartera
				JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
				JOIN resultados rs ON gt.idresultado=rs.idresultado
				JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
				JOIN proveedores p ON ct.idproveedor=p.idproveedor
				WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='23'
			";	// Procedimientos - Observacion
		$query=$db->Execute($sql);
			while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Observacion")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Observacion")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(false);	
						$query->MoveNext();
			}
			$x=$x+2;
			////+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='23' AND j.idjustificacion='145'
					";	// Procedimientos - Observacion - Coordino con ejecutivo
				$query=$db->Execute($sql);
				
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Coordino con ejecutivo")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E193')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F193')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G193')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Coordino con ejecutivo")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='23' AND j.idjustificacion='149'
					";	// Procedimientos - Observacion - En desacuerdo con lo facturado
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "En desacuerdo con lo facturado")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E193')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F193')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G193')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "En desacuerdo con lo facturado")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='23' AND j.idjustificacion='150'
					";	// Procedimientos - Observacion - Factura anulada
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Factura anulada")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E193')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F193')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G193')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Factura anulada")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='23' AND j.idjustificacion='146'
					";	// Procedimientos - Observacion - Indica ya pago
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Indica ya pago")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E193')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F193')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G193')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Indica ya pago")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;

					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='23' AND j.idjustificacion='147'
					";	// Procedimientos - Observacion - No recibio factura
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "No recibio factura")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E193')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F193')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G193')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "No recibio factura")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
						$query->MoveNext();
			}
		
			$x=$x+1;
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		
				$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
						JOIN cuentas c ON cp.idcuenta=c.idcuenta
						JOIN clientes cl ON c.idcliente=cl.idcliente
						JOIN carteras ct ON c.idcartera=ct.idcartera
						JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
						JOIN resultados rs ON gt.idresultado=rs.idresultado
						JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion
						JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
						JOIN proveedores p ON ct.idproveedor=p.idproveedor
						WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND gg.idgrupogestion='6' AND rs.idresultado='23' AND j.idjustificacion='148'
					";	// Procedimientos - Observacion - No solicito el servicio
				$query=$db->Execute($sql);
				while(!$query->EOF){
					if($query->fields['t_ctes']!=0){

						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "No solicito el servicio")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '=('.$query->fields['t_ctes'].'*100)/E4')
								->setCellValue('i'.$x, '=('.$query->fields['t_ctas'].'*100)/F4')
								->setCellValue('j'.$x, '=('.$query->fields['t_imp'].'*100)/G4')
								->setCellValue('K'.$x, '=('.$query->fields['t_ctes'].'*100)/E144')
								->setCellValue('L'.$x, '=('.$query->fields['t_ctas'].'*100)/F144')
								->setCellValue('M'.$x, '=('.$query->fields['t_imp'].'*100)/G144')
								->setCellValue('N'.$x, '=('.$query->fields['t_ctes'].'*100)/E193')
								->setCellValue('o'.$x, '=('.$query->fields['t_ctas'].'*100)/F193')
								->setCellValue('p'.$x, '=('.$query->fields['t_imp'].'*100)/G193')
								;
					}else{
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "No solicito el servicio")
								->setCellValue('e'.$x, $query->fields['t_ctes'])
								->setCellValue('f'.$x, $query->fields['t_ctas'])
								->setCellValue('g'.$x, $query->fields['t_imp'])
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
						
					
					}
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						//$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setVisible(false);
						$query->MoveNext();
			}
				
				$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setOutlineLevel(1);
				$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setVisible(false);
		$x=$x+3;		
			// Sin gestion
		
		$sql="SELECT cl.idcliente,cp.idcuenta,cp.imptot,IF(g.idgestion,g.idgestion,'-') idgestion,p.proveedor FROM cuenta_periodos cp 
			JOIN cuentas c ON cp.idcuenta=c.idcuenta
			JOIN clientes cl ON c.idcliente=cl.idcliente
			JOIN carteras ct ON c.idcartera=ct.idcartera
			LEFT JOIN gestiones g ON cp.idcuenta=g.idcuenta
			JOIN proveedores p ON ct.idproveedor=p.idproveedor
			WHERE cp.idperiodo='$periodo' AND p.idproveedor='$proveedor' AND cp.idestado='1'
			ORDER BY g.idgestion";
		$z=0;
		
		$imptot="";	
		$ctas= array();
		$query=$db->Execute($sql);
				while(!$query->EOF){
						if($query->fields['idgestion']=="-"){
							$z++;
							$imptot=$imptot+$query->fields['imptot'];
							
							if(in_array($query->fields['idcliente'],$ctas)){
							
							}else{
								$ctas[$z]=$query->fields['idcliente'];
							}
						}			
						$query->MoveNext();
				}
		$total=count($ctas);		
					$objPHPExcel->setActiveSheetIndex(0)
							
							->setCellValue('b'.$x, "Sin gestion")
							->setCellValue('e'.$x, "$total")
							->setCellValue('f'.$x, "$z")
							->setCellValue('g'.$x, "$imptot")
							->setCellValue('h'.$x, '=(e'.$x.'*100)/E4')
							->setCellValue('i'.$x, '=(f'.$x.'*100)/F4')
							->setCellValue('j'.$x, '=(g'.$x.'*100)/G4')
							->setCellValue('K'.$x, "100.0%")
							->setCellValue('L'.$x, "100.0%")
							->setCellValue('M'.$x, "100.0%")
							
							;
					$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
	$x=$x+2;
		
			// Sin gestion - Sin gestion
		
							
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('c'.$x, "Sin gestion")
								->setCellValue('e'.$x, "")
								->setCellValue('f'.$x, "")
								->setCellValue('g'.$x, "")
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, "100.0%")
								->setCellValue('o'.$x, "100.0%")
								->setCellValue('p'.$x, "100.0%")
								;
					
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(1);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(false);	
		
			$x=$x+2;
			////+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
					// Sin gestion - Sin gestion - Sin gestion
				
			
						$objPHPExcel->setActiveSheetIndex(0)
								
								->setCellValue('d'.$x, "Sin gestion")
								->setCellValue('e'.$x, "")
								->setCellValue('f'.$x, "")
								->setCellValue('g'.$x, "")
								->setCellValue('h'.$x, '0')
								->setCellValue('i'.$x, '0')
								->setCellValue('j'.$x, '0')
								->setCellValue('K'.$x, '0')
								->setCellValue('L'.$x, '0')
								->setCellValue('M'.$x, '0')
								->setCellValue('N'.$x, '0')
								->setCellValue('o'.$x, '0')
								->setCellValue('p'.$x, '0')
								;
		
						$objPHPExcel->getActiveSheet()->getStyle('g'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('h'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('k'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);	
						$objPHPExcel->getActiveSheet()->getStyle('l'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);						
						$objPHPExcel->getActiveSheet()->getStyle('m'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('n'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('o'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						$objPHPExcel->getActiveSheet()->getStyle('p'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setOutlineLevel(2);
						$objPHPExcel->getActiveSheet()->getRowDimension($x-1)->setVisible(false);
						$objPHPExcel->getActiveSheet()->getRowDimension($x)->setVisible(false);
						//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setCollapsed(true);	
				
				$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setOutlineLevel(1);
				$objPHPExcel->getActiveSheet()->getRowDimension($x+1)->setVisible(false);
				$x=$x+3;	
					
				
	//--------------------------------------------------------------------------------------------		
			
	
			
		$sql="SELECT COUNT(cp.idcuentaperiodo) t_ctas,SUM(cp.imptot) t_imp,COUNT(DISTINCT cl.idcliente) t_ctes,p.proveedor FROM cuenta_periodos cp 
				JOIN cuentas c ON cp.idcuenta=c.idcuenta
				JOIN clientes cl ON c.idcliente=cl.idcliente
				JOIN carteras ct ON c.idcartera=ct.idcartera
				JOIN gestiones gt ON cp.idcuenta=gt.idcuenta
				JOIN resultados rs ON gt.idresultado=rs.idresultado
				JOIN grupo_gestiones gg ON rs.idgrupogestion=gg.idgrupogestion
				JOIN proveedores p ON ct.idproveedor=p.idproveedor
				WHERE cp.idperiodo='7' AND p.idproveedor='11' AND gg.idgrupogestion='6'
			";	// PROCEDIMIENTO	

		$objPHPExcel->getActiveSheet()->mergeCells('E1:G1');
		$objPHPExcel->getActiveSheet()->getStyle('E1')
		->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$objPHPExcel->getActiveSheet()->mergeCells('H1:J1');
		$objPHPExcel->getActiveSheet()->getStyle('H1')
		->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$objPHPExcel->getActiveSheet()->mergeCells('K1:M1');
		$objPHPExcel->getActiveSheet()->getStyle('K1')
		->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$objPHPExcel->getActiveSheet()->mergeCells('N1:P1');
		$objPHPExcel->getActiveSheet()->getStyle('N1')
		->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
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
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
	
		//$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(22);;

		/*$objPHPExcel->getActiveSheet()->getStyle('A1:P2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('A1:P2')->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setARGB('00000000');*/
		$objPHPExcel->getActiveSheet()->setTitle('Reporte_Segmentacion_Call');
	
/*Fin Instrucciones*/

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

header('Content-Disposition: attachment;filename="reporte_segmentacion.xls"');
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
//$objWriter->save('archivo.xls');  
$objWriter->save('php://output');

//header("Location: archivo.xls");
exit;

?>
