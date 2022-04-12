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
		$peri=$_GET['peri'];
		$mes=$db->Execute("Select month(fecini) mes from periodos where idperiodo='$peri'");
		$mes=$mes->fields['mes'];
		
		
		$sql="SELECT ctr.cartera,prv.proveedor,c.idcliente,c.idubicabilidad,dc.doi,p.personerias
				,c.cliente,d.direccion,cta.idcuenta,mn.simbolo,pr.producto
				,ctap.idcuenta,ctap.fecven,ctap.diasmora,ctap.observacion,ctap.impcap
				,ctap.impven,ctap.imptot,ctap.impdestot,ctap.grupo,ctap.ciclo,us.usuario
				,IF(gs.fecges,gs.fecges,'Sin gestion') fecges,IF(gs.horges,gs.horges,'Sin Gestion') horges,rst.resultado,gs.impcomp importe_compromiso	
				FROM clientes c
				JOIN doi dc ON c.iddoi=dc.iddoi
				JOIN personerias p ON c.idpersoneria=p.idpersoneria 
				LEFT JOIN direcciones d ON c.idcliente=d.idcliente
				JOIN cuentas cta ON c.idcliente=cta.idcliente	
				JOIN productos pr ON cta.idproducto=pr.idproducto	
				JOIN cuenta_periodos ctap ON cta.idcuenta=ctap.idcuenta
				JOIN monedas mn ON cta.idmoneda=mn.idmoneda
				JOIN carteras ctr ON cta.idcartera=ctr.idcartera	
				JOIN proveedores prv ON ctr.idproveedor=prv.idproveedor	
				JOIN usuarios us ON ctap.idusuario=us.idusuario	
				LEFT JOIN gestiones gs ON ctap.idcuenta=gs.idcuenta
				LEFT JOIN resultados rst ON gs.idresultado=rst.idresultado
				WHERE gs.fecreg=(
						SELECT MAX(fecreg) FROM gestiones WHERE idcuenta=ctap.idcuenta
				)
				
				and  month(gs.fecges)='$mes'
			";
					
		if(isset($_GET['prove'])){
			$prov=$_GET['prove'];
			$sql.=" AND prv.idproveedor='$prov' ";
		}

		if(isset($_GET['cart'])){
			$cart=$_GET['cart'];
			$sql.=" AND ctr.idcartera='$cart' ";
		}

		if(isset($_GET['peri'])){
			$peri=$_GET['peri'];
			$sql.=" AND ctap.idperiodo='$peri' ";
		}	
			$sql.="  ORDER BY cliente,direccion ";
		$query=$db->Execute($sql);
		//echo $sql;
		//return false;
		$t_regist=$query->_numOfRows;
		//$db->debug=true;
		if($t_regist==0){
			$sql="SELECT d.prioridad,ctr.cartera,prv.proveedor,c.idcliente,c.idubicabilidad,dc.doi,p.personerias
					,c.cliente,d.direccion,cta.idcuenta,mn.simbolo,pr.producto
					,ctap.idcuenta,ctap.fecven,ctap.diasmora,ctap.observacion,ctap.impcap
					,ctap.impven,ctap.imptot,ctap.impdestot,ctap.grupo,ctap.ciclo,us.usuario
					FROM clientes c
					JOIN doi dc ON c.iddoi=dc.iddoi
					JOIN personerias p ON c.idpersoneria=p.idpersoneria 
					LEFT JOIN direcciones d ON c.idcliente=d.idcliente
					JOIN cuentas cta ON c.idcliente=cta.idcliente	
					JOIN productos pr ON cta.idproducto=pr.idproducto	
					JOIN cuenta_periodos ctap ON cta.idcuenta=ctap.idcuenta
					JOIN monedas mn ON cta.idmoneda=mn.idmoneda
					JOIN carteras ctr ON cta.idcartera=ctr.idcartera	
					JOIN proveedores prv ON ctr.idproveedor=prv.idproveedor	
					JOIN usuarios us ON ctap.idusuario=us.idusuario	
					WHERE ctap.idperiodo='$peri'
					ORDER BY prioridad DESC
					 ";
					$query=$db->Execute($sql);
		
		}
		
		$sql2="	SELECT ctr.cartera,prv.proveedor,c.idcliente,c.idubicabilidad,dc.doi,p.personerias
				,c.cliente,d.direccion,cta.idcuenta,mn.simbolo,pr.producto
				,ctap.idcuenta,ctap.fecven,ctap.diasmora,ctap.observacion,ctap.impcap
				,ctap.impven,ctap.imptot,ctap.impdestot,ctap.grupo,ctap.ciclo,us.usuario
				,IF(gs.fecges,gs.fecges,'Sin gestion') fecges,IF(gs.horges,gs.horges,'Sin Gestion') horges,rst.resultado,gs.impcomp importe_compromiso	
				FROM clientes c
				JOIN doi dc ON c.iddoi=dc.iddoi
				JOIN personerias p ON c.idpersoneria=p.idpersoneria 
				LEFT JOIN direcciones d ON c.idcliente=d.idcliente
				JOIN cuentas cta ON c.idcliente=cta.idcliente	
				JOIN productos pr ON cta.idproducto=pr.idproducto	
				JOIN cuenta_periodos ctap ON cta.idcuenta=ctap.idcuenta
				JOIN monedas mn ON cta.idmoneda=mn.idmoneda
				JOIN carteras ctr ON cta.idcartera=ctr.idcartera	
				JOIN proveedores prv ON ctr.idproveedor=prv.idproveedor	
				JOIN usuarios us ON ctap.idusuario=us.idusuario	
				LEFT JOIN gestiones gs ON ctap.idcuenta=gs.idcuenta
				LEFT JOIN resultados rst ON gs.idresultado=rst.idresultado
				WHERE d.prioridad=1
				AND ctap.idperiodo='$peri'
				and  month(gs.fecges)='$mes'
				ORDER BY rst.resultado";
				
		$query2=$db->Execute($sql2);					
							/*
							SELECT c.idcliente,c.idubicabilidad,dc.doi,p.personerias,c.cliente,d.direccion,u.nombre dpto,u2.nombre prov,u3.nombre dist,t.telefono,cta.idcuenta,cta.idmoneda,pr.producto,ctap.* FROM clientes c
							JOIN doi dc ON c.iddoi=dc.iddoi
							JOIN personerias p ON c.idpersoneria=p.idpersoneria 
							JOIN direcciones d ON c.idcliente=d.idcliente
							JOIN ubigeos u ON d.coddpto=u.coddpto AND u.codprov=00 AND u.coddist=00
							JOIN ubigeos u2 ON d.coddpto=u2.coddpto AND d.codprov=u2.codprov AND u2.coddist=00
							JOIN ubigeos u3 ON d.coddpto=u3.coddpto AND d.codprov=u3.codprov AND d.coddist=u3.coddist
							LEFT JOIN telefonos t ON c.idcliente=t.idcliente
							JOIN cuentas cta ON c.idcliente=cta.idcliente	
							JOIN productos pr ON cta.idproducto=pr.idproducto	
							JOIN cuenta_periodos ctap ON cta.idcuenta=ctap.idcuenta
							JOIN carteras ctr ON cta.idcartera=ctr.idcartera							
							WHERE ctr.idproveedor=6
							*/
		$n=1;

		$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('a1', "Nro.Doc")
							->setCellValue('b1', "Ubicabilidad")
							->setCellValue('c1', "Tipo Doc.")
							->setCellValue('d1', "Tip.Pers")
							->setCellValue('e1', "Cliente")
							->setCellValue('f1', "Direccion")
							->setCellValue('g1', "--")
							->setCellValue('h1', "Cuenta")
							->setCellValue('i1', "Moneda")
							->setCellValue('j1', "Producto")
							->setCellValue('k1', "Proveedor")
							->setCellValue('l1', "Cartera")
							->setCellValue('m1', "FecVen")
							->setCellValue('n1', "Dias Mora")
							->setCellValue('o1', "Observacion")
							->setCellValue('p1', "ImpCap")
							->setCellValue('q1', "ImpVen")
							->setCellValue('r1', "ImpTot")
							->setCellValue('s1', "ImpDesTot")
							->setCellValue('t1', "Grupo")
							->setCellValue('u1', "Ciclo")
							->setCellValue('v1', "Usuario")
							->setCellValue('w1', "Fecha Gestion")
							->setCellValue('x1', "Hora Gestion")
							//->setCellValue('y1', "Contactabilidad")
							->setCellValue('z1', "Resultado")
							->setCellValue('aa1', "Imp.Compromiso")
							//	->setCellValue('ab1', "Actividad")
							;
						
		
		$objPHPExcel->getDefaultStyle()->getFont()
			->setName('Arila')
			->setSize(10);
			
				while(!$query->EOF){
					
					if($query->fields['prioridad']==0){
							if(in_array($query->fields['idcliente'],$idcls)){
								$query->MoveNext();
								continue;
							}
							
					}
					++$n;
					$idcls[$n]=$query->fields['idcliente'];
					$pos = strpos($query->fields['idcuenta'], "-");
								if($pos){
									$ctas = explode("-",$query->fields['idcuenta']);
									if(count($ctas)>2){ $cta=$ctas[0]."-".$ctas[1]; } else { $cta=$ctas[0]; }
								}
								if(!isset($query->fields['importe_compromiso']) or $query->fields['importe_compromiso']=="0"){	$impcomp="";}else{$impcomp=$query->fields['importe_compromiso'];}
					//$aa =" ".$query->fields['documento'];
					
					if($t_regist=='0'){$fecges='Sin Gestion';}else{$fecges=$query->fields['fecges'];}
					if($t_regist=='0'){$horges='';}else{$horges=$query->fields['horges'];}
					if($t_regist=='0'){$rslt='';}else{$rslt= $query->fields['resultado'];}
					$objPHPExcel->setActiveSheetIndex(0)
							
							->setCellValue('a'.$n, '="'.$query->fields['idcliente'].'"')
							->setCellValue('b'.$n, $query->fields['idubicabilidad'])
							->setCellValue('c'.$n, $query->fields['doi'])
							->setCellValue('d'.$n, $query->fields['personerias'])
							->setCellValue('e'.$n, $query->fields['cliente'])
							->setCellValue('f'.$n, $query->fields['direccion'])
							//->setCellValue('g'.$n, $query->fields['telefono'])
							->setCellValue('h'.$n, '="'.$cta.'"')
							->setCellValue('i'.$n, $query->fields['simbolo'])
							->setCellValue('j'.$n, $query->fields['producto'])
							->setCellValue('k'.$n, $query->fields['proveedor'])
							->setCellValue('l'.$n, $query->fields['cartera'])
							->setCellValue('m'.$n, $query->fields['fecven'])
							->setCellValue('n'.$n, $query->fields['diasmora'])
							->setCellValue('o'.$n, $query->fields['observacion'])
							->setCellValue('p'.$n, $query->fields['impcap'])
							->setCellValue('q'.$n, $query->fields['impven'])
							->setCellValue('r'.$n, $query->fields['imptot'])
							->setCellValue('s'.$n, $query->fields['impdestot'])
							->setCellValue('t'.$n, $query->fields['grupo'])
							->setCellValue('u'.$n, $query->fields['ciclo'])
							->setCellValue('v'.$n, $query->fields['usuario'])
							->setCellValue('w'.$n, $fecges)
							->setCellValue('x'.$n, $horges)
							//->setCellValue('y'.$n, $query->fields['contactabilidad'])
							->setCellValue('z'.$n, $rslt)
							->setCellValue('aa'.$n, $impcomp)
							//->setCellValue('ab'.$n, $query->fields['actividad'])
							;
					$objPHPExcel->getActiveSheet()->getStyle('p'.$n)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('q'.$n)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('r'.$n)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('s'.$n)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('aa'.$n)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
		
					$query->MoveNext();
				}
				while(!$query2->EOF){
					
					if($query2->fields['resultado']!=""){
						$query2->MoveNext();
						continue;
					}
					++$n;
					
					$pos = strpos($query->fields['idcuenta'], "-");
								if($pos){
									$ctas = explode("-",$query->fields['idcuenta']);
									if(count($ctas)>2){ $cta=$ctas[0]."-".$ctas[1]; } else { $cta=$ctas[0]; }
								}
					//$aa =" ".$query2->fields['documento'];
					$objPHPExcel->setActiveSheetIndex(0)
							
							->setCellValue('a'.$n, " ".$query2->fields['idcliente'])
							->setCellValue('b'.$n, $query2->fields['idubicabilidad'])
							->setCellValue('c'.$n, $query2->fields['doi'])
							->setCellValue('d'.$n, $query2->fields['personerias'])
							->setCellValue('e'.$n, $query2->fields['cliente'])
							->setCellValue('f'.$n, $query2->fields['direccion'])
							//->setCellValue('g'.$n, $query->fields['telefono'])
							->setCellValue('h'.$n, '="'.$cta.'"')
							->setCellValue('i'.$n, $query2->fields['simbolo'])
							->setCellValue('j'.$n, $query2->fields['producto'])
							->setCellValue('k'.$n, $query2->fields['proveedor'])
							->setCellValue('l'.$n, $query2->fields['cartera'])
							->setCellValue('m'.$n, $query2->fields['fecven'])
							->setCellValue('n'.$n, $query2->fields['diasmora'])
							->setCellValue('o'.$n, $query2->fields['observacion'])
							->setCellValue('p'.$n, $query2->fields['impcap'])
							->setCellValue('q'.$n, $query2->fields['impven'])
							->setCellValue('r'.$n, $query2->fields['imptot'])
							->setCellValue('s'.$n, $query2->fields['impdestot'])
							->setCellValue('t'.$n, $query2->fields['grupo'])
							->setCellValue('u'.$n, $query2->fields['ciclo'])
							->setCellValue('v'.$n, $query2->fields['usuario'])
							->setCellValue('w'.$n, $query2->fields['fecges'])
							->setCellValue('x'.$n, $query2->fields['horges'])
							//->setCellValue('y'.$n, $query->fields['contactabilidad'])
							->setCellValue('z'.$n, $query2->fields['resultado'])
							->setCellValue('aa'.$n, $query2->fields['importe_compromiso'])
							//->setCellValue('ab'.$n, $query->fields['actividad'])
							;
					$objPHPExcel->getActiveSheet()->getStyle('p'.$n)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('q'.$n)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('r'.$n)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('s'.$n)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('aa'.$n)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
		
					$query2->MoveNext();
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
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
		//var_dump($objPHPExcel->getActiveSheet()->getStyle('A1')->getNumberFormat()->getFormatCode());
		$objPHPExcel->getActiveSheet()->setTitle('Foto_Cartera');
	
/*Fin Instrucciones*/

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

header('Content-Disposition: attachment;filename="archivo.xls"');
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
//$objWriter->save('archivo.xls');  
$objWriter->save('php://output');

//header("Location: archivo.xls");
exit;

?>
