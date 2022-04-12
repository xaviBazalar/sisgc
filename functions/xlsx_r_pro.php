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
							->setCellValue('a1', "Nro")
							->setCellValue('b1', "DNI")
							->setCellValue('c1', "NEGOCIADOR")
							->setCellValue('d1', "TURNO")
							->setCellValue('e1', "07:00-07:59")
							->setCellValue('f1', "08:00-08:59")
							->setCellValue('g1', "09:00-09:59")
							->setCellValue('h1', "10:00-10:59")
							->setCellValue('i1', "11:00-11:59")
							->setCellValue('j1', "12:00-12:59")
							->setCellValue('k1', "13:00-13:59")
							->setCellValue('l1', "14:00-14:59")
							->setCellValue('m1', "15:00-15:59")
							->setCellValue('n1', "16:00-16:59")
							->setCellValue('o1', "17:00-17:59")
							->setCellValue('p1', "18:00-18:59")
							->setCellValue('q1', "19:00-19:59")
							->setCellValue('r1', "20:00-20:59")
							->setCellValue('s1', "TOTAL")
							->setCellValue('t1', "HORAS")
							->setCellValue('u1', "PROMEDIO")
							;
						
		//***		
				
				$prov=$_GET['prove'];

				$sql_tmp="SELECT g.idcuenta,g.idcontactabilidad,g.idresultado,g.idjustificacion,g.observacion,g.fecges,g.horges,g.impcomp,g.feccomp,
							g.idactividad,g.idtelefono,g.idemail,g.usureg,g.fecreg
						FROM usuarios u
						JOIN proveedores p ON u.idproveedor=p.idproveedor
						JOIN carteras c ON u.idcartera=c.idcartera
						JOIN gestiones g ON u.idusuario=g.usureg
						WHERE u.idnivel='2' AND p.idproveedor='$prov' 
						";
						
				if(isset($_GET['cart'])){
					$cart=$_GET['cart'];
					$sql_tmp.=" AND c.idcartera='$cart' ";
				}	
				
				$fi=$_GET['fi'];
				$fn=$_GET['fn'];
				
				if($cart==51){
								$sql_actividad=" AND g.idactividad BETWEEN '27' AND '33' AND fecges BETWEEN '$fi' AND '$fn' ";


				}else{
								$sql_actividad=" AND g.idactividad BETWEEN '1' AND '2' AND fecges BETWEEN '$fi' AND '$fn' ";

				}
				
				
				$sql_tmp.="$sql_actividad 
						AND fecges BETWEEN '$fi' AND '$fn' 
						GROUP BY u.idusuario,g.fecreg	";	
				//echo $sql_tmp;
				
			//	return false;			
				$tmp_a=$db->Execute($sql_tmp);
				
					
				$db->Execute("CREATE TEMPORARY TABLE tmpr_produc
						(
							idcuenta VARCHAR(50) ,
							idcontactabilidad INT(11),
							idresultado INT(11), 
							idjustificacion INT(11),  
							fecges DATE,
							horges TIME,
							impcomp FLOAT(10,2),
							feccomp DATE,
							idactividad INT(11),
							idtelefono INT(11),
							idemail INT(11),
							idusuario INT(11),
							fecreg TIMESTAMP,
							INDEX `idcliente` (`idcuenta`),
							INDEX `idcontactabilidad` (`idcontactabilidad`),
							INDEX `idresultado` (`idresultado`),
							INDEX `idjustificacion` (`idjustificacion`),
							INDEX `idactividad` (`idactividad`),
							INDEX `idusuario` (`idusuario`)
							
						)ENGINE = MEMORY ;");
				//$db->debug=true;				
				while(!$tmp_a->EOF){
					$ius=$tmp_a->fields['usureg'];
					$fgs=$tmp_a->fields['fecges'];
					$fr=$tmp_a->fields['fecreg'];
					$idr=$tmp_a->fields['idresultado'];
					$idc=$tmp_a->fields['idcontactabilidad'];
					$idj=$tmp_a->fields['idjustificacion'];
					$ida=$tmp_a->fields['idactividad'];
					$ide=$tmp_a->fields['idemail'];
					$hr=$tmp_a->fields['horges'];
					$idcta=$tmp_a->fields['idcuenta'];
					$id_a=$tmp_a->fields['idactividad'];
					$db->Execute("Insert into tmpr_produc (`idusuario`,`fecreg`,`idactividad`,`fecges`,`idcontactabilidad`,`idcuenta`,`idresultado`,`idjustificacion`,`horges`,`idemail`)
							values ('$ius','$fr','$id_a','$fgs','$idc','$idcta','$idr','$idj','$hr','$ide')");
					$tmp_a->MoveNext();
				}
		
						
		//****				
		$objPHPExcel->getDefaultStyle()->getFont()
			->setName('Calibri')
			->setSize(8);
		//Inicio------------------------------------------------------------------------------------------	
				
				$sql="SELECT u.idusuario,u.documento,u.usuario,COUNT(DISTINCT g.fecreg) total,
				SUM(HOUR(g.fecreg)='6') h6,
				SUM(HOUR(g.fecreg)='7') h7,
				SUM(HOUR(g.fecreg)='8') h8,
				SUM(HOUR(g.fecreg)='9') h9,
				SUM(HOUR(g.fecreg)='10') h10,
				SUM(HOUR(g.fecreg)='11') h11,
				SUM(HOUR(g.fecreg)='12') h12,
				SUM(HOUR(g.fecreg)='13') h13,
				SUM(HOUR(g.fecreg)='14') h14,
				SUM(HOUR(g.fecreg)='15') h15,
				SUM(HOUR(g.fecreg)='16') h16,
				SUM(HOUR(g.fecreg)='17') h17,
				SUM(HOUR(g.fecreg)='18') h18,
				SUM(HOUR(g.fecreg)='19') h19,
				SUM(HOUR(g.fecreg)='20') h20,
				SUM(HOUR(g.fecreg)='21') h21 
				FROM usuarios u
				JOIN proveedores p ON u.idproveedor=p.idproveedor
				JOIN carteras c ON u.idcartera=c.idcartera
				JOIN tmpr_produc g ON u.idusuario=g.idusuario
				WHERE u.idnivel='2' 
				";
				
				if(isset($_GET['prove'])){
					$prov=$_GET['prove'];
					$sql.=" AND p.idproveedor='$prov' ";
				}

				if(isset($_GET['cart'])){
					$cart=$_GET['cart'];
					$sql.=" AND c.idcartera='$cart' ";
				}

				$fi=$_GET['fi'];
				$fn=$_GET['fn'];
				$sql.="$sql_actividad and fecges BETWEEN '$fi' AND '$fn' GROUP BY u.idusuario ";
				$sql2=$sql;
				//$sql2.=" GROUP BY u.idusuario ";
				
				
				/*echo $sql;
				return false*/;
				$z=1;
				$v=2;
				
				$query=$db->Execute($sql2);
				while(!$query->EOF){
					
					$id=$query->fields['idusuario'];
					$horas[$id][$n]=$query->fields['documento'];++$n;
					$horas[$id][$n]=$query->fields['usuario'];++$n;
					
					++$n;
					$max=0;
					$hor=0;
					for($p=6;$p<=21;$p++){
						$nr="h".$p;
						if($query->fields[$nr]!="0"){++$hor;}
					}
					/*echo $hor;
					return false;*/
					$suma_c=$query->fields['h6']+
							$query->fields['h7']+
							$query->fields['h8']+
							$query->fields['h9']+
							$query->fields['h10']+
							$query->fields['h11']+
							$query->fields['h12']+
							$query->fields['h13']+
							$query->fields['h14']+
							$query->fields['h15']+
							$query->fields['h16']+
							$query->fields['h17']+
							$query->fields['h18']+
							$query->fields['h19']+
							$query->fields['h20']+
							$query->fields['h21'];
					$prom_c=$suma_c/$hor;
						$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('a'.$v, $n)
							->setCellValue('b'.$v, '="'.$query->fields['documento'].'"')
							->setCellValue('c'.$v, $query->fields['usuario'])
							->setCellValue('d'.$v, '')
							->setCellValue('e'.$v, $query->fields['h6']+$query->fields['h7'])
							->setCellValue('f'.$v, $query->fields['h8'])
							->setCellValue('g'.$v, $query->fields['h9'])
							->setCellValue('h'.$v, $query->fields['h10'])
							->setCellValue('i'.$v, $query->fields['h11'])
							->setCellValue('j'.$v, $query->fields['h12'])
							->setCellValue('k'.$v, $query->fields['h13'])
							->setCellValue('l'.$v, $query->fields['h14'])
							->setCellValue('m'.$v, $query->fields['h15'])
							->setCellValue('n'.$v, $query->fields['h16'])
							->setCellValue('o'.$v, $query->fields['h17'])
							->setCellValue('p'.$v, $query->fields['h18'])
							->setCellValue('q'.$v, $query->fields['h19'])
							->setCellValue('r'.$v, $query->fields['h20']+$query->fields['h21'])
							->setCellValue('s'.$v, $suma_c)
							->setCellValue('t'.$v, $hor)
							->setCellValue('u'.$v, $prom_c)
														;

						++$v;
						++$z;

					$n=1;
					$total[1][$id]=$query->fields['total'];
					$prom[1][$id]=$hor;
					$horas[$id][19]=$hor;
					$query->MoveNext();
					
				}
				
				
				$ant=$v-1;
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('c'.$v,'TOTAL  LLAMADAS X HORA');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('e'.$v,'=SUM(E2:E'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('f'.$v,'=SUM(F2:F'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('g'.$v,'=SUM(G2:G'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('h'.$v,'=SUM(H2:H'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('i'.$v,'=SUM(I2:I'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('j'.$v,'=SUM(J2:J'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('k'.$v,'=SUM(K2:K'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('l'.$v,'=SUM(L2:L'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('m'.$v,'=SUM(M2:M'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('n'.$v,'=SUM(N2:N'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('o'.$v,'=SUM(O2:O'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('p'.$v,'=SUM(P2:P'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('q'.$v,'=SUM(Q2:Q'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('r'.$v,'=SUM(R2:R'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('s'.$v,'=SUM(S2:S'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('t'.$v,'=SUM(T2:T'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('u'.$v,'=(s'.$v.'/t'.$v.')');
				

					$objPHPExcel->getActiveSheet()->getStyle('u'.$ant)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('u'.$v)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					
					$objPHPExcel->getActiveSheet()->getStyle('A1:U'.$v.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					$objPHPExcel->getActiveSheet()->getStyle('A1:U'.$v.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('A1:U'.$ant.'')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('A1:U'.$v.'')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('A1:U'.$v.'')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('A1:U'.$v.'')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

					$objPHPExcel->getActiveSheet()->getStyle('u'.$ant)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('u'.$v)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('A1:U1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
					$objPHPExcel->getActiveSheet()->getStyle('A1:U1')->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setARGB('00000000');
	///-------------------------------------------------	
	
	$n=1;
		$sql="SELECT u.idusuario,u.documento,u.usuario,COUNT(DISTINCT g.fecreg) total,
				SUM(HOUR(g.fecreg)='6') h6,
				SUM(HOUR(g.fecreg)='7') h7,
				SUM(HOUR(g.fecreg)='8') h8,
				SUM(HOUR(g.fecreg)='9') h9,
				SUM(HOUR(g.fecreg)='10') h10,
				SUM(HOUR(g.fecreg)='11') h11,
				SUM(HOUR(g.fecreg)='12') h12,
				SUM(HOUR(g.fecreg)='13') h13,
				SUM(HOUR(g.fecreg)='14') h14,
				SUM(HOUR(g.fecreg)='15') h15,
				SUM(HOUR(g.fecreg)='16') h16,
				SUM(HOUR(g.fecreg)='17') h17,
				SUM(HOUR(g.fecreg)='18') h18,
				SUM(HOUR(g.fecreg)='19') h19,
				SUM(HOUR(g.fecreg)='20') h20,
				SUM(HOUR(g.fecreg)='21') h21 		FROM usuarios u
												JOIN proveedores p ON u.idproveedor=p.idproveedor
												JOIN carteras c ON u.idcartera=c.idcartera
												JOIN tmpr_produc g ON u.idusuario=g.idusuario
												JOIN contactabilidad ct ON g.idcontactabilidad=ct.idcontactabilidad	
												JOIN tipo_contactabilidad tc ON ct.idtipocontactabilidad=tc.idtipocontactabilidad
												WHERE u.idnivel='2' 
				";
				
				if(isset($_GET['prove'])){
					$prov=$_GET['prove'];
					$sql.=" AND p.idproveedor='$prov' ";
				}

				if(isset($_GET['cart'])){
					$cart=$_GET['cart'];
					$sql.=" AND c.idcartera='$cart' ";
				}
				
				
				$sql.="$sql_actividad AND tc.idtipocontactabilidad BETWEEN '1' AND '2' and fecges BETWEEN '$fi' AND '$fn' GROUP BY u.idusuario ";
				$sql2=$sql;
				//$sql2.=" GROUP BY u.idusuario ";
				
				//$db->debug=true;
				
				$query=$db->Execute($sql2);
				
				++$v;
				++$v;
				$dos=$v;
				
				while(!$query->EOF){
					
					$id=$query->fields['idusuario'];
					$horas[$id][$n]=$query->fields['documento'];++$n;
					$horas[$id][$n]=$query->fields['usuario'];++$n;
					
					++$n;
					$max=0;
					$hor=0;
					for($p=6;$p<=21;$p++){
						$nr="h".$p;
						if($query->fields[$nr]!="0"){++$hor;}
					}
					
					$suma_c=$query->fields['h6']+
							$query->fields['h7']+
							$query->fields['h8']+
							$query->fields['h9']+
							$query->fields['h10']+
							$query->fields['h11']+
							$query->fields['h12']+
							$query->fields['h13']+
							$query->fields['h14']+
							$query->fields['h15']+
							$query->fields['h16']+
							$query->fields['h17']+
							$query->fields['h18']+
							$query->fields['h19']+
							$query->fields['h20']+
							$query->fields['h21'];
					$prom_c=$suma_c/$hor;
					
						$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('a'.$v, $n)
							->setCellValue('b'.$v, '="'.$query->fields['documento'].'"')
							->setCellValue('c'.$v, $query->fields['usuario'])
							->setCellValue('d'.$v, '')
							->setCellValue('e'.$v, $query->fields['h6']+$query->fields['h7'])
							->setCellValue('f'.$v, $query->fields['h8'])
							->setCellValue('g'.$v, $query->fields['h9'])
							->setCellValue('h'.$v, $query->fields['h10'])
							->setCellValue('i'.$v, $query->fields['h11'])
							->setCellValue('j'.$v, $query->fields['h12'])
							->setCellValue('k'.$v, $query->fields['h13'])
							->setCellValue('l'.$v, $query->fields['h14'])
							->setCellValue('m'.$v, $query->fields['h15'])
							->setCellValue('n'.$v, $query->fields['h16'])
							->setCellValue('o'.$v, $query->fields['h17'])
							->setCellValue('p'.$v, $query->fields['h18'])
							->setCellValue('q'.$v, $query->fields['h19'])
							->setCellValue('r'.$v, $query->fields['h20']+$query->fields['h21'])
							->setCellValue('s'.$v, $suma_c)
							->setCellValue('t'.$v, $hor)
							->setCellValue('u'.$v, $prom_c)
														;

						++$v;
						++$z;

					$n=1;
					$total[2][$id]=$query->fields['total'];
					$prom[2][$id]=$hor;
					$horas[$id][19]=$hor;
					$query->MoveNext();
					
				}
				$ant=$v-1;
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('c'.$v,'TOTAL  CONTACTOS X HORA');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('e'.$v,'=SUM(E'.$dos.':E'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('f'.$v,'=SUM(F'.$dos.':F'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('g'.$v,'=SUM(G'.$dos.':G'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('h'.$v,'=SUM(H'.$dos.':H'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('i'.$v,'=SUM(I'.$dos.':I'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('j'.$v,'=SUM(J'.$dos.':J'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('k'.$v,'=SUM(K'.$dos.':K'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('l'.$v,'=SUM(L'.$dos.':L'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('m'.$v,'=SUM(M'.$dos.':M'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('n'.$v,'=SUM(N'.$dos.':N'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('o'.$v,'=SUM(O'.$dos.':O'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('p'.$v,'=SUM(P'.$dos.':P'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('q'.$v,'=SUM(Q'.$dos.':Q'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('r'.$v,'=SUM(R'.$dos.':R'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('s'.$v,'=SUM(S'.$dos.':S'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('t'.$v,'=SUM(T'.$dos.':T'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('u'.$v,'=(s'.$v.'/t'.$v.')');
				
					$objPHPExcel->getActiveSheet()->getStyle('A'.$dos.':U'.$v.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$dos.':U'.$v.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$dos.':U'.$ant.'')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$dos.':U'.$v.'')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$dos.':U'.$v.'')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$dos.':U'.$v.'')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

					$objPHPExcel->getActiveSheet()->getStyle('u'.$ant)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('u'.$v)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			
	
	//------------------------------------------------------------------------------------------------
	
	$n=1;
		$sql="SELECT u.idusuario,u.documento,u.usuario,COUNT(DISTINCT g.fecreg) total,
				SUM(HOUR(g.fecreg)='6') h6,
				SUM(HOUR(g.fecreg)='7') h7,
				SUM(HOUR(g.fecreg)='8') h8,
				SUM(HOUR(g.fecreg)='9') h9,
				SUM(HOUR(g.fecreg)='10') h10,
				SUM(HOUR(g.fecreg)='11') h11,
				SUM(HOUR(g.fecreg)='12') h12,
				SUM(HOUR(g.fecreg)='13') h13,
				SUM(HOUR(g.fecreg)='14') h14,
				SUM(HOUR(g.fecreg)='15') h15,
				SUM(HOUR(g.fecreg)='16') h16,
				SUM(HOUR(g.fecreg)='17') h17,
				SUM(HOUR(g.fecreg)='18') h18,
				SUM(HOUR(g.fecreg)='19') h19,
				SUM(HOUR(g.fecreg)='20') h20,
				SUM(HOUR(g.fecreg)='21') h21 FROM usuarios u
												JOIN proveedores p ON u.idproveedor=p.idproveedor
												JOIN carteras c ON u.idcartera=c.idcartera
												JOIN tmpr_produc g ON u.idusuario=g.idusuario
												JOIN contactabilidad ct ON g.idcontactabilidad=ct.idcontactabilidad	
												JOIN tipo_contactabilidad tc ON ct.idtipocontactabilidad=tc.idtipocontactabilidad
												WHERE u.idnivel='2' 
				";
				
				if(isset($_GET['prove'])){
					$prov=$_GET['prove'];
					$sql.=" AND p.idproveedor='$prov' ";
				}

				if(isset($_GET['cart'])){
					$cart=$_GET['cart'];
					$sql.=" AND c.idcartera='$cart' ";
				}
				
				$sql.="$sql_actividad AND tc.idtipocontactabilidad='1' and fecges BETWEEN '$fi' AND '$fn' GROUP BY u.idusuario ";
				$sql2=$sql;
				//$sql2.=" GROUP BY u.idusuario ";
				$query=$db->Execute($sql2);
				++$v;
				++$v;
				$dos=$v;
				while(!$query->EOF){
					
					$id=$query->fields['idusuario'];
					$horas[$id][$n]=$query->fields['documento'];++$n;
					$horas[$id][$n]=$query->fields['usuario'];++$n;
					++$n;
					$max=0;
					$hor=0;
					for($p=6;$p<=21;$p++){
						$nr="h".$p;
						if($query->fields[$nr]!="0"){++$hor;}
					}
					$suma_c=$query->fields['h6']+
							$query->fields['h7']+
							$query->fields['h8']+
							$query->fields['h9']+
							$query->fields['h10']+
							$query->fields['h11']+
							$query->fields['h12']+
							$query->fields['h13']+
							$query->fields['h14']+
							$query->fields['h15']+
							$query->fields['h16']+
							$query->fields['h17']+
							$query->fields['h18']+
							$query->fields['h19']+
							$query->fields['h20']+
							$query->fields['h21'];
					$prom_c=$suma_c/$hor;
						$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('a'.$v, $n)
							->setCellValue('b'.$v, '="'.$query->fields['documento'].'"')
							->setCellValue('c'.$v, $query->fields['usuario'])
							->setCellValue('d'.$v, '')
							->setCellValue('e'.$v, $query->fields['h6']+$query->fields['h7'])
							->setCellValue('f'.$v, $query->fields['h8'])
							->setCellValue('g'.$v, $query->fields['h9'])
							->setCellValue('h'.$v, $query->fields['h10'])
							->setCellValue('i'.$v, $query->fields['h11'])
							->setCellValue('j'.$v, $query->fields['h12'])
							->setCellValue('k'.$v, $query->fields['h13'])
							->setCellValue('l'.$v, $query->fields['h14'])
							->setCellValue('m'.$v, $query->fields['h15'])
							->setCellValue('n'.$v, $query->fields['h16'])
							->setCellValue('o'.$v, $query->fields['h17'])
							->setCellValue('p'.$v, $query->fields['h18'])
							->setCellValue('q'.$v, $query->fields['h19'])
							->setCellValue('r'.$v, $query->fields['h20']+$query->fields['h21'])
							->setCellValue('s'.$v, $suma_c)
							->setCellValue('t'.$v, $hor)
							->setCellValue('u'.$v, $prom_c)
														;

						++$v;
						++$z;

					$n=1;
					$total[3][$id]=$query->fields['total'];
					$prom[3][$id]=$hor;
					$horas[$id][19]=$hor;
					$query->MoveNext();
					
				}
				
				$ant=$v-1;
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('c'.$v,'TOTAL  CONTACTOS DIRECTOS X HORA');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('e'.$v,'=SUM(E'.$dos.':E'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('f'.$v,'=SUM(F'.$dos.':F'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('g'.$v,'=SUM(G'.$dos.':G'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('h'.$v,'=SUM(H'.$dos.':H'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('i'.$v,'=SUM(I'.$dos.':I'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('j'.$v,'=SUM(J'.$dos.':J'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('k'.$v,'=SUM(K'.$dos.':K'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('l'.$v,'=SUM(L'.$dos.':L'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('m'.$v,'=SUM(M'.$dos.':M'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('n'.$v,'=SUM(N'.$dos.':N'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('o'.$v,'=SUM(O'.$dos.':O'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('p'.$v,'=SUM(P'.$dos.':P'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('q'.$v,'=SUM(Q'.$dos.':Q'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('r'.$v,'=SUM(R'.$dos.':R'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('s'.$v,'=SUM(S'.$dos.':S'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('t'.$v,'=SUM(T'.$dos.':T'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('u'.$v,'=(s'.$v.'/t'.$v.')');
					
					$objPHPExcel->getActiveSheet()->getStyle('A'.$dos.':U'.$v.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$dos.':U'.$v.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$dos.':U'.$ant.'')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$dos.':U'.$v.'')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$dos.':U'.$v.'')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$dos.':U'.$v.'')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

					$objPHPExcel->getActiveSheet()->getStyle('u'.$ant)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('u'.$v)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
	//------------------------------------------------------------------------------------------------
		$n=1;
		$sql="SELECT u.idusuario,u.documento,u.usuario,COUNT(DISTINCT g.fecreg) total,
				SUM(HOUR(g.fecreg)='6') h6,
				SUM(HOUR(g.fecreg)='7') h7,
				SUM(HOUR(g.fecreg)='8') h8,
				SUM(HOUR(g.fecreg)='9') h9,
				SUM(HOUR(g.fecreg)='10') h10,
				SUM(HOUR(g.fecreg)='11') h11,
				SUM(HOUR(g.fecreg)='12') h12,
				SUM(HOUR(g.fecreg)='13') h13,
				SUM(HOUR(g.fecreg)='14') h14,
				SUM(HOUR(g.fecreg)='15') h15,
				SUM(HOUR(g.fecreg)='16') h16,
				SUM(HOUR(g.fecreg)='17') h17,
				SUM(HOUR(g.fecreg)='18') h18,
				SUM(HOUR(g.fecreg)='19') h19,
				SUM(HOUR(g.fecreg)='20') h20,
				SUM(HOUR(g.fecreg)='21') h21 	FROM usuarios u
												JOIN proveedores p ON u.idproveedor=p.idproveedor
												JOIN carteras c ON u.idcartera=c.idcartera
												JOIN tmpr_produc g ON u.idusuario=g.idusuario
												WHERE u.idnivel='2' 
				";
				
				if(isset($_GET['prove'])){
					$prov=$_GET['prove'];
					$sql.=" AND p.idproveedor='$prov' ";
				}

				if(isset($_GET['cart'])){
					$cart=$_GET['cart'];
					$sql.=" AND c.idcartera='$cart' ";
				}
				if($cart==51){
								$sql_pro=" and g.idresultado='248' ";


				}else{
								$sql_pro=" and g.idresultado='2' ";

				}
				$sql.="$sql_actividad $sql_pro and fecges BETWEEN '$fi' AND '$fn' GROUP BY u.idusuario ";
				$sql2=$sql;
				//$sql2.=" GROUP BY u.idusuario ";
				$query=$db->Execute($sql2);
				++$v;
				++$v;
				$dos=$v;
				$t_pro="";
				while(!$query->EOF){
					
					$id=$query->fields['idusuario'];
					$horas[$id][$n]=$query->fields['documento'];++$n;
					$horas[$id][$n]=$query->fields['usuario'];++$n;
					
					++$n;
					$max=0;
					$hor=0;
					for($p=6;$p<=21;$p++){
						$nr="h".$p;
						if($query->fields[$nr]!="0"){++$hor;}
					}
					
					$suma_c=$query->fields['h6']+
							$query->fields['h7']+
							$query->fields['h8']+
							$query->fields['h9']+
							$query->fields['h10']+
							$query->fields['h11']+
							$query->fields['h12']+
							$query->fields['h13']+
							$query->fields['h14']+
							$query->fields['h15']+
							$query->fields['h16']+
							$query->fields['h17']+
							$query->fields['h18']+
							$query->fields['h19']+
							$query->fields['h20']+
							$query->fields['h21'];
					$prom_c=$suma_c/$hor;
						$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('a'.$v, $n)
							->setCellValue('b'.$v, '="'.$query->fields['documento'].'"')
							->setCellValue('c'.$v, $query->fields['usuario'])
							->setCellValue('d'.$v, '')
							->setCellValue('e'.$v, $query->fields['h6']+$query->fields['h7'])
							->setCellValue('f'.$v, $query->fields['h8'])
							->setCellValue('g'.$v, $query->fields['h9'])
							->setCellValue('h'.$v, $query->fields['h10'])
							->setCellValue('i'.$v, $query->fields['h11'])
							->setCellValue('j'.$v, $query->fields['h12'])
							->setCellValue('k'.$v, $query->fields['h13'])
							->setCellValue('l'.$v, $query->fields['h14'])
							->setCellValue('m'.$v, $query->fields['h15'])
							->setCellValue('n'.$v, $query->fields['h16'])
							->setCellValue('o'.$v, $query->fields['h17'])
							->setCellValue('p'.$v, $query->fields['h18'])
							->setCellValue('q'.$v, $query->fields['h19'])
							->setCellValue('r'.$v, $query->fields['h20']+$query->fields['h21'])
							->setCellValue('s'.$v, $suma_c)
							->setCellValue('t'.$v, $hor)
							->setCellValue('u'.$v, $prom_c)
														;

						++$v;
						++$z;

					$n=1;
					$total[4][$id]=$query->fields['total'];
					$t_pro=$t_pro+$query->fields['total'];
					$prom[4][$id]=$hor;
					$horas[$id][19]=$hor;
					$tot_p_7=0;
					$tot_p_8=0;
					$tot_p_9=0;
					$tot_p_10=0;
					$tot_p_11=0;
					$tot_p_12=0;
					$tot_p_13=0;
					$tot_p_14=0;
					$tot_p_15=0;
					$tot_p_16=0;
					$tot_p_17=0;
					$tot_p_18=0;
					$tot_p_19=0;
					$tot_p_20=0;
					$tot_p_21=0;
					$tot_p_22=0;
					
					$tot_p_7=$query->fields['h6']+$query->fields['h7']+$tot_p_7;
					$tot_p_8=$query->fields['h8']+$tot_p_8;
					$tot_p_9=$query->fields['h9']+$tot_p_9;
					$tot_p_10=$query->fields['h10']+$tot_p_10;
					$tot_p_11=$query->fields['h11']+$tot_p_11;
					$tot_p_12=$query->fields['h12']+$tot_p_12;
					$tot_p_13=$query->fields['h13']+$tot_p_13;
					$tot_p_14=$query->fields['h14']+$tot_p_14;
					$tot_p_15=$query->fields['h15']+$tot_p_15;
					$tot_p_16=$query->fields['h16']+$tot_p_16;
					$tot_p_17=$query->fields['h17']+$tot_p_17;
					$tot_p_18=$query->fields['h18']+$tot_p_18;
					$tot_p_19=$query->fields['h19']+$tot_p_19;
					$tot_p_20=$query->fields['h20']+$tot_p_20;
					$tot_p_21=$query->fields['h21']+$tot_p_21;

					$query->MoveNext();
					
					
				}
				$ant=$v-1;
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('c'.$v,'TOTAL  PROMESAS X HORA');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('e'.$v,$tot_p_7);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('f'.$v,$tot_p_8);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('g'.$v,$tot_p_9);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('h'.$v,$tot_p_10);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('i'.$v,$tot_p_11);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('j'.$v,$tot_p_12);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('k'.$v,$tot_p_13);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('l'.$v,$tot_p_14);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('m'.$v,$tot_p_15);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('n'.$v,$tot_p_16);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('o'.$v,$tot_p_17);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('p'.$v,$tot_p_18);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('q'.$v,$tot_p_19);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('r'.$v,$tot_p_20);
				//$objPHPExcel->setActiveSheetIndex(0)->setCellValue('s'.$v,$tot_p_21);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('s'.$v,$t_pro);
				if($_GET['prove']!=15){
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('t'.$v,'=SUM(T'.$dos.':T'.$ant.')');
				}

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('u'.$v,'=(s'.$v.'/t'.$v.')');

				/*$objPHPExcel->setActiveSheetIndex(0)->setCellValue('c'.$v,'TOTAL  PROMESAS X HORA');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('e'.$v,'=SUM(E'.$dos.':E'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('f'.$v,'=SUM(F'.$dos.':F'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('g'.$v,'=SUM(G'.$dos.':G'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('h'.$v,'=SUM(H'.$dos.':H'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('i'.$v,'=SUM(I'.$dos.':I'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('j'.$v,'=SUM(J'.$dos.':J'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('k'.$v,'=SUM(K'.$dos.':K'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('l'.$v,'=SUM(L'.$dos.':L'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('m'.$v,'=SUM(M'.$dos.':M'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('n'.$v,'=SUM(N'.$dos.':N'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('o'.$v,'=SUM(O'.$dos.':O'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('p'.$v,'=SUM(P'.$dos.':P'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('q'.$v,'=SUM(Q'.$dos.':Q'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('r'.$v,'=SUM(R'.$dos.':R'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('s'.$v,'=SUM(S'.$dos.':S'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('t'.$v,'=SUM(T'.$dos.':T'.$ant.')');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('u'.$v,'=(s'.$v.'/t'.$v.')');*/
				
					$objPHPExcel->getActiveSheet()->getStyle('A'.$dos.':U'.$v.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$dos.':U'.$v.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$dos.':U'.$ant.'')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$dos.':U'.$v.'')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$dos.':U'.$v.'')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$dos.':U'.$v.'')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

					$objPHPExcel->getActiveSheet()->getStyle('u'.$ant)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('u'.$v)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
				
	
	//-------------------------------------------------------------------------------
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
	
		$objPHPExcel->getActiveSheet(0)->setTitle('PRODUCTIVIDAD_X_HORA');
		
		 
////////////////////////////////////////////Pestaña 2

	$objWorksheet2 = $objPHPExcel->createSheet();
	$objWorksheet2->setTitle('RESUMEN_PRODUCTIVIDAD');
	$objWorksheet2->setCellValue('a1', "Nro")
							->setCellValue('b1', "DNI")
							->setCellValue('c1', "NEGOCIADOR")
							->setCellValue('d1', "TURNO")
							->setCellValue('e1', "LLAMADAS")
							->setCellValue('f1', "CONTACTOS")
							->setCellValue('g1', "DIRECTO")
							->setCellValue('h1', "PROMESAS")
							->setCellValue('i1', "%CONTACT")
							->setCellValue('j1', "%CONTACT-CD")
							->setCellValue('k1', "%PDP/CD")
							->setCellValue('l1', "LLAMADASPH")
							->setCellValue('m1', "CONTACTPH")
							->setCellValue('n1', "CONTACT-CDPH")
							->setCellValue('o1', "PROMESASPH")
							;
		$v=2;
		$z=1;
		$dos=$v;	
		foreach($horas as $x => $y){
					
					if($prom[4][$x]==0){
						$prom4="0";
					}else{
						$prom4='=(h'.$v.'/'.$prom[4][$x].')';
					}			
					
					$p1=$total[1][$x]/$prom[1][$x];if(!$p1){$p1=0;}
					$p2=$total[2][$x]/$prom[2][$x];if(!$p2){$p2=0;}
					$p3=$total[3][$x]/$prom[3][$x];if(!$p3){$p3=0;}
					$p4=$total[4][$x]/$prom[4][$x];if(!$p4){$p4=0;}
					$objWorksheet2
							->setCellValue('a'.$v, $z)
							->setCellValue('b'.$v, '="'.$horas[$x][1].'"')
							->setCellValue('c'.$v, $horas[$x][2])
							->setCellValue('d'.$v, '')
							->setCellValue('e'.$v, $total[1][$x])
							->setCellValue('f'.$v, $total[2][$x])
							->setCellValue('g'.$v, $total[3][$x])
							->setCellValue('h'.$v, $total[4][$x])
							->setCellValue('i'.$v, '=(f'.$v.'/e'.$v.')*100')
							->setCellValue('j'.$v, '=(g'.$v.'/e'.$v.')*100')
							->setCellValue('k'.$v, '=(h'.$v.'/g'.$v.')*100')
							->setCellValue('l'.$v, $p1)
							->setCellValue('m'.$v, $p2)
							->setCellValue('n'.$v, $p3)
							->setCellValue('o'.$v, $p4)
							;
							$objWorksheet2->getStyle('i'.$v)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
							$objWorksheet2->getStyle('j'.$v)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
							$objWorksheet2->getStyle('k'.$v)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
							$objWorksheet2->getStyle('l'.$v)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
							$objWorksheet2->getStyle('m'.$v)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
							$objWorksheet2->getStyle('n'.$v)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
							$objWorksheet2->getStyle('o'.$v)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					++$v;
					++$z;
				}		

				$ant=$v-1;
				$objWorksheet2->setCellValue('c'.$v,'TOTAL  CARTERA');
				$objWorksheet2->setCellValue('e'.$v,'=SUM(E'.$dos.':E'.$ant.')');
				$objWorksheet2->setCellValue('f'.$v,'=SUM(F'.$dos.':F'.$ant.')');
				$objWorksheet2->setCellValue('g'.$v,'=SUM(G'.$dos.':G'.$ant.')');
				$objWorksheet2->setCellValue('h'.$v,'=SUM(H'.$dos.':H'.$ant.')');
				$objWorksheet2->setCellValue('i'.$v,'=SUM(I'.$dos.':I'.$ant.')');
				$objWorksheet2->setCellValue('j'.$v,'=SUM(J'.$dos.':J'.$ant.')');
				$objWorksheet2->setCellValue('k'.$v,'=SUM(K'.$dos.':K'.$ant.')');
				$objWorksheet2->setCellValue('l'.$v,'=SUM(L'.$dos.':L'.$ant.')');
				$objWorksheet2->setCellValue('m'.$v,'=SUM(M'.$dos.':M'.$ant.')');
				//$objWorksheet2->setCellValue('n'.$v,'=SUM(N'.$dos.':N'.$ant.')');
				$objWorksheet2->setCellValue('o'.$v,'=SUM(O'.$dos.':O'.$ant.')');
				$styleArray = array(
					'borders' => array(
						'outline' => array(
							'style' => PHPExcel_Style_Border::BORDER_THICK,
							'color' => array('argb' => 'FFFF0000'),
						),
					),
				);
							

				$objWorksheet2->getStyle('i'.$v)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
				$objWorksheet2->getStyle('j'.$v)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
				$objWorksheet2->getStyle('k'.$v)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
				$objWorksheet2->getStyle('l'.$v)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
				$objWorksheet2->getStyle('m'.$v)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
				$objWorksheet2->getStyle('n'.$v)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
				$objWorksheet2->getStyle('o'.$v)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
				
				$objPHPExcel->getActiveSheet()->getStyle('A1:O'.$v.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$objPHPExcel->getActiveSheet()->getStyle('A1:O'.$v.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$objPHPExcel->getActiveSheet()->getStyle('A1:O'.$ant.'')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$objPHPExcel->getActiveSheet()->getStyle('A1:O'.$v.'')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$objPHPExcel->getActiveSheet()->getStyle('A1:O'.$v.'')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$objPHPExcel->getActiveSheet()->getStyle('A1:O'.$v.'')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				
				$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
				$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->getFill()
							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
							->getStartColor()->setARGB('00000000');
				
				
		$objWorksheet2->getColumnDimension('A')->setAutoSize(true);
		$objWorksheet2->getColumnDimension('B')->setAutoSize(true);
		$objWorksheet2->getColumnDimension('C')->setAutoSize(true);
		$objWorksheet2->getColumnDimension('D')->setAutoSize(true);
		$objWorksheet2->getColumnDimension('E')->setAutoSize(true);
		$objWorksheet2->getColumnDimension('F')->setAutoSize(true);
		$objWorksheet2->getColumnDimension('G')->setAutoSize(true);
		$objWorksheet2->getColumnDimension('H')->setAutoSize(true);
		$objWorksheet2->getColumnDimension('I')->setAutoSize(true);
		$objWorksheet2->getColumnDimension('J')->setAutoSize(true);
		$objWorksheet2->getColumnDimension('K')->setAutoSize(true);
		$objWorksheet2->getColumnDimension('L')->setAutoSize(true);
		$objWorksheet2->getColumnDimension('M')->setAutoSize(true);
		$objWorksheet2->getColumnDimension('N')->setAutoSize(true);
		$objWorksheet2->getColumnDimension('O')->setAutoSize(true);
		
////////////////////////////////////////////// Pestaña 3
	if($_SERVER['REMOTE_ADDR']=="192.168.50.44"){
		$objWorksheet3 = $objPHPExcel->createSheet();
	
	
		$objWorksheet3->setTitle('RESUMEN_PRODUCTIVIDAD_CLIENTES');
		
		
		$objWorksheet3->setCellValue('a1', "Nro")
							->setCellValue('b1', "DNI")
							->setCellValue('c1', "NEGOCIADOR")
							->setCellValue('d1', "TURNO")
							->setCellValue('e1', "07:00-07:59")
							->setCellValue('f1', "08:00-08:59")
							->setCellValue('g1', "09:00-09:59")
							->setCellValue('h1', "10:00-10:59")
							->setCellValue('i1', "11:00-11:59")
							->setCellValue('j1', "12:00-12:59")
							->setCellValue('k1', "13:00-13:59")
							->setCellValue('l1', "14:00-14:59")
							->setCellValue('m1', "15:00-15:59")
							->setCellValue('n1', "16:00-16:59")
							->setCellValue('o1', "17:00-17:59")
							->setCellValue('p1', "18:00-18:59")
							->setCellValue('q1', "19:00-19:59")
							->setCellValue('r1', "20:00-20:59")
							->setCellValue('s1', "TOTAL")
							->setCellValue('t1', "HORAS")
							->setCellValue('u1', "PROMEDIO")
							;
							
		$sql="SELECT
				  -- c.idcliente,
				  
				  g.usureg,
				  u.usuario,
				  u.documento,
				  CASE
					WHEN  HOUR(horges)=6 THEN 7 ELSE  HOUR(horges)
				  END hora
				  ,COUNT(*) total
				FROM cuentas c
				  JOIN gestiones g
					ON c.idcuenta = g.idcuenta
					  AND g.fecges = DATE(NOW())
					  AND g.idfuente = 2
				  JOIN usuarios u
					ON g.usureg=u.idusuario
				WHERE c.idcartera = $cart
				and g.usureg!=999
				GROUP BY usureg,hora 
				ORDER BY hora,usureg";
				
		$qs=$db->Execute($sql);
		
		$clientes = array();
		
		while(!$qs->EOF){
			$clientes[$qs->fields['usureg']][$qs->fields['hora']]=$qs->fields['total'];
			$clientes[$qs->fields['usureg']]['nombre']=$qs->fields['usuario'];
			$clientes[$qs->fields['usureg']]['dni']=$qs->fields['documento'];
			$qs->MoveNext();
		}

		
		$v=2;
		$z=1;
		$dos=$v;	
		
		
		foreach($clientes as $x => $y){
					

					$n++;
					
					$hor=0;
					for($p=6;$p<=21;$p++){
						
						if($y[$p]!="0" and  $y[$p]!="" ){++$hor;}
					}

					$suma_c=$y['6']+
							$y['7']+
							$y['8']+
							$y['9']+
							$y['10']+
							$y['11']+
							$y['12']+
							$y['13']+
							$y['14']+
							$y['15']+
							$y['16']+
							$y['17']+
							$y['18']+
							$y['19']+
							$y['20']+
							$y['21'];
					$prom_c=$suma_c/$hor;
					
					$objWorksheet3
							->setCellValue('a'.$v, $n)
							->setCellValue('b'.$v, '="'.$y['dni'].'"')
							->setCellValue('c'.$v, $y['nombre'])
							->setCellValue('d'.$v, '')
							->setCellValue('e'.$v, $y[7])
							->setCellValue('f'.$v, $y[8])
							->setCellValue('g'.$v, $y[9])
							->setCellValue('h'.$v, $y[10])
							->setCellValue('i'.$v, $y[11])
							->setCellValue('j'.$v, $y[12])
							->setCellValue('k'.$v, $y[13])
							->setCellValue('l'.$v, $y[14])
							->setCellValue('m'.$v, $y[15])
							->setCellValue('n'.$v, $y[16])
							->setCellValue('o'.$v, $y[17])
							->setCellValue('p'.$v, $y[18])
							->setCellValue('q'.$v, $y[19])
							->setCellValue('r'.$v, $y[20]+$y[21])
							->setCellValue('s'.$v, $suma_c)
							->setCellValue('t'.$v, $hor)
							->setCellValue('u'.$v, $prom_c)
														;
							

					
					++$v;
					++$z;
					
					
				}		
	
				$ant=$v-1;

				$styleArray = array(
					'borders' => array(
						'outline' => array(
							'style' => PHPExcel_Style_Border::BORDER_THICK,
							'color' => array('argb' => 'FFFF0000'),
						),
					),
				);
							

				$objWorksheet3->setCellValue('c'.$v,'TOTAL  LLAMADAS X HORA');
				$objWorksheet3->setCellValue('e'.$v,'=SUM(E2:E'.$ant.')');
				$objWorksheet3->setCellValue('f'.$v,'=SUM(F2:F'.$ant.')');
				$objWorksheet3->setCellValue('g'.$v,'=SUM(G2:G'.$ant.')');
				$objWorksheet3->setCellValue('h'.$v,'=SUM(H2:H'.$ant.')');
				$objWorksheet3->setCellValue('i'.$v,'=SUM(I2:I'.$ant.')');
				$objWorksheet3->setCellValue('j'.$v,'=SUM(J2:J'.$ant.')');
				$objWorksheet3->setCellValue('k'.$v,'=SUM(K2:K'.$ant.')');
				$objWorksheet3->setCellValue('l'.$v,'=SUM(L2:L'.$ant.')');
				$objWorksheet3->setCellValue('m'.$v,'=SUM(M2:M'.$ant.')');
				$objWorksheet3->setCellValue('n'.$v,'=SUM(N2:N'.$ant.')');
				$objWorksheet3->setCellValue('o'.$v,'=SUM(O2:O'.$ant.')');
				$objWorksheet3->setCellValue('p'.$v,'=SUM(P2:P'.$ant.')');
				$objWorksheet3->setCellValue('q'.$v,'=SUM(Q2:Q'.$ant.')');
				$objWorksheet3->setCellValue('r'.$v,'=SUM(R2:R'.$ant.')');
				$objWorksheet3->setCellValue('s'.$v,'=SUM(S2:S'.$ant.')');
				$objWorksheet3->setCellValue('t'.$v,'=SUM(T2:T'.$ant.')');
				$objWorksheet3->setCellValue('u'.$v,'=(s'.$v.'/t'.$v.')');
				

					$objWorksheet3->getStyle('u'.$ant)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objWorksheet3->getStyle('u'.$v)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					
					$objWorksheet3->getStyle('A1:U'.$v.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					$objWorksheet3->getStyle('A1:U'.$v.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objWorksheet3->getStyle('A1:U'.$ant.'')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objWorksheet3->getStyle('A1:U'.$v.'')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objWorksheet3->getStyle('A1:U'.$v.'')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objWorksheet3->getStyle('A1:U'.$v.'')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

					$objWorksheet3->getStyle('u'.$ant)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objWorksheet3->getStyle('u'.$v)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objWorksheet3->getStyle('A1:U1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
					$objWorksheet3->getStyle('A1:U1')->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setARGB('00000000');
				
				
				$objWorksheet3->getColumnDimension('A')->setAutoSize(true);
				$objWorksheet3->getColumnDimension('B')->setAutoSize(true);
				$objWorksheet3->getColumnDimension('C')->setAutoSize(true);
				$objWorksheet3->getColumnDimension('D')->setAutoSize(true);
				$objWorksheet3->getColumnDimension('E')->setAutoSize(true);
				$objWorksheet3->getColumnDimension('F')->setAutoSize(true);
				$objWorksheet3->getColumnDimension('G')->setAutoSize(true);
				$objWorksheet3->getColumnDimension('H')->setAutoSize(true);
				$objWorksheet3->getColumnDimension('I')->setAutoSize(true);
				$objWorksheet3->getColumnDimension('J')->setAutoSize(true);
				$objWorksheet3->getColumnDimension('K')->setAutoSize(true);
				$objWorksheet3->getColumnDimension('L')->setAutoSize(true);
				$objWorksheet3->getColumnDimension('M')->setAutoSize(true);
				$objWorksheet3->getColumnDimension('N')->setAutoSize(true);
				$objWorksheet3->getColumnDimension('O')->setAutoSize(true);
	
	}
/*Fin Instrucciones*/

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$db->Close();
header('Content-Disposition: attachment;filename="reporte.xls"');
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
//$objWriter->save('archivo.xls');  
$objWriter->save('php://output');

//header("Location: archivo.xls");
exit;

?>
