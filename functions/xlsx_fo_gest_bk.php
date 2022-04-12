<?php

session_start();
$iduser=$_SESSION['iduser'];
ini_set('memory_limit', '-1');
set_time_limit(1800);
/** Error reporting */
error_reporting(E_ALL);

date_default_timezone_set('Europe/London');

/** PHPExcel */
require_once '../class/PHPExcel.php';
require_once '../class/PHPExcel/IOFactory.php';
include '../scripts/conexion.php';

$flag=$db->Execute("select flag from flag_reportes where reporte='gestiones'");
if($flag->fields['flag']=="0"){
	$ip=$_SERVER['REMOTE_ADDR'];
	$db->_query("update flag_reportes set flag='1',idusuario='$iduser',host='$ip' where reporte='gestiones'");
	$fecha=date("Y-m-d");
	$hora=date("H:i:s");
	$db->_query("INSERT into detalle_reportes (`idreporte`,`idusuario`,`fecha`,`hora_ini`,`host`) VALUES('2','$iduser','$fecha','$hora','$ip')");
}else{
	echo "En estos momentos ya se encuentra una reporte de gestiones en curso. Espero uno minutos por favor y vuelva a intentarlo.";
	return false;
}

include 'rango_hora.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

		$peri=$_GET['peri'];
		//$mes=$db->Execute("Select month(fecini) mes from periodos where idperiodo='$peri'");
		$mes_p=$db->Execute("SELECT MONTH(fecini) mes FROM periodos WHERE idperiodo='$peri'");
		$mes_a=$mes_p->fields['mes'];
		mysql_free_result($mes_p->_queryID);
/*Inicio Instrucciones*/
		
	$objPHPExcel->getProperties()->setCreator("Kobsa - Gestion")
									 ->setLastModifiedBy("Kobsa - Gestion")
									 ->setTitle("Office 2007 XLSX Test Document")
									 ->setSubject("Office 2007 XLSX Test Document")
									 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
									 ->setKeywords("office 2007 openxml php")
									 ->setCategory("Test result file");
		
		$sql="SELECT  j.peso,ct.idcliente
	,cl.cliente
	,ct.idcuenta
	,m.monedas	
	,pr.proveedor
	,cra.cartera
	,pd.producto
	,a.idactividad
	,a.actividad
	,g.fecges
	,g.horges
	,v.validaciones	
	,t.telefono
	,e.email
	,d.direccion
	,CASE WHEN t.idorigentelefono!=0 THEN ot.origentelefono WHEN d.idorigendireccion!=0 THEN od.origendireccion END AS origen
	,ft.fuente
	,tcb.tipocontactabilidad
	,co.contactabilidad
	,gg.grupogestion
	,r.resultado
	,j.justificacion
	,g.fecreg
	,g.impcomp
	,g.feccomp
	,g.observacion
	,u.usuario
	,HOUR(g.horges) as hora
				FROM gestiones g
				JOIN cuenta_periodos cp ON g.idcuenta=cp.idcuenta
				JOIN cuentas ct ON g.idcuenta=ct.idcuenta 
				JOIN monedas m ON ct.idmoneda=m.idmoneda				
				JOIN carteras cra ON ct.idcartera=cra.idcartera 
				JOIN proveedores pr ON cra.idproveedor=pr.idproveedor
				JOIN productos pd ON ct.idproducto=pd.idproducto				
				JOIN clientes cl ON ct.idcliente=cl.idcliente
				JOIN contactabilidad co ON g.idcontactabilidad=co.idcontactabilidad
				JOIN tipo_contactabilidad tcb ON co.idtipocontactabilidad=tcb.idtipocontactabilidad
				JOIN resultados r ON g.idresultado=r.idresultado
				JOIN grupo_gestiones gg ON r.idgrupogestion=gg.idgrupogestion
				JOIN justificaciones j ON g.idjustificacion=j.idjustificacion
				JOIN actividades a ON g.idactividad=a.idactividad
				LEFT JOIN emails e ON g.idemail=e.idemail
				LEFT JOIN telefonos t ON g.idtelefono=t.idtelefono
				LEFT JOIN direcciones d ON g.iddireccion=d.iddireccion
				LEFT JOIN origen_telefonos ot ON t.idorigentelefono=ot.idorigentelefono	
				LEFT JOIN origen_direcciones od ON d.idorigendireccion=od.idorigendireccion
				LEFT JOIN fuentes ft ON t.idfuente=ft.idfuente													
				LEFT JOIN validaciones v ON t.idvalidacion=v.idvalidaciones				
				JOIN usuarios u ON g.usureg=u.idusuario	 where Month(g.fecges)='$mes_a'";
		
			if(isset($_GET['peri'])){
				$peri=$_GET['peri'];
				$sql.=" AND cp.idperiodo='$peri'   ";
			}	
		

		if(isset($_GET['prove'])){
			$prov=$_GET['prove'];
			$sql.=" AND pr.idproveedor='$prov' AND u.idproveedor='$prov' ";
		}

		if(isset($_GET['cart'])){
			$cart=$_GET['cart'];
			$sql.=" AND cra.idcartera='$cart' ";
		}
		
		if(isset($_GET['tp_cart'])){
			$tpcart=$_GET['tp_cart'];
			$sql.=" AND ct.idtipocartera='$tpcart' ";
		}
		
		$total=0;
		
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
			
			
		}
		
		if(isset($_GET['fecini']) and isset($_GET['fecfin'])){
			$ini=$_GET['fecini'];
			$fin=$_GET['fecfin'];
			$sql.=" AND g.fecges BETWEEN '$ini' AND '$fin' ";
		}

		$sql.=" ORDER BY fecreg ";

		$query=$db->Execute($sql);
				
		$n=1;
		
		$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('a1', "Documento")
							->setCellValue('b1', "Cliente")
							->setCellValue('c1', "Cta")
							->setCellValue('d1', "Moneda")
							->setCellValue('e1', "Proveedor")
							->setCellValue('f1', "Cartera")
							->setCellValue('g1', "Producto")
							->setCellValue('h1', "Indicador")
							->setCellValue('i1', "Fecha Gest.")
							->setCellValue('j1', "Hora Gest.")
							->setCellValue('k1', "Val.")
							->setCellValue('l1', "Tel Gest.")
							->setCellValue('m1', "Mail Gest.")
							->setCellValue('n1', "Dir Gest.")
							->setCellValue('o1', "Origen")
							->setCellValue('p1', "Fuente")
							->setCellValue('q1', "Tipo Contacto")
							->setCellValue('r1', "Contacto")
							->setCellValue('s1', "Tipo Resultado")
							->setCellValue('t1', "Resultado")
							->setCellValue('u1', "Justificacion")
							->setCellValue('v1', "ImpComp.")
							->setCellValue('w1', "FecComp.")
							->setCellValue('x1', "Observacion")
							->setCellValue('y1', "RangoHora")
							->setCellValue('z1', "Negociador")
							->setCellValue('AA1', "Peso")
							;
						
		
		$objPHPExcel->getDefaultStyle()->getFont()
			->setName('Calibri')
			->setSize(9);
				while(!$query->EOF){
					if($total!=0){
						if(in_array($query->fields['idactividad'],$act)){
						
						}else{
							$query->MoveNext();
							continue;
						}
					}
					$r_hora=rango_hora($query->fields['horges']);
					++$n;
					if($query->fields['impcomp']=="0.00"){	$impcomp="";}else{$impcomp=$query->fields['impcomp'];}
					if($query->fields['feccomp']=="0000-00-00"){$feccomp="";}else{$feccomp=$query->fields['feccomp'];}
					$pos = strpos($query->fields['idcuenta'], "-");
								if($pos){
									$ctas = explode("-",$query->fields['idcuenta']);
									if(count($ctas)>2){ $cta=$ctas[0]."-".$ctas[1]; } else { $cta=$ctas[0]; }
								}
					if($query->fields['impcomp']==0){$impcomp="";}else{$impcomp=$query->fields['impcomp'];}
					if($query->fields['feccomp']=="0000-00-00"){$feccomp="";}else{$feccomp=$query->fields['feccomp'];}
					
					$cliente=utf8_encode($query->fields['cliente']);
					$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('a'.$n, '=("'.$query->fields['idcliente'].'")')
							->setCellValue('b'.$n, $cliente)
							->setCellValue('c'.$n, '="'.$cta.'"')
							->setCellValue('d'.$n, $query->fields['monedas'])
							->setCellValue('e'.$n, $query->fields['proveedor'])
							->setCellValue('f'.$n, $query->fields['cartera'])
							->setCellValue('g'.$n, $query->fields['producto'])
							->setCellValue('h'.$n, $query->fields['actividad'])
							->setCellValue('i'.$n, $query->fields['fecges'])
							->setCellValue('j'.$n, $query->fields['horges'])
							->setCellValue('k'.$n, $query->fields['validaciones'])
							->setCellValue('l'.$n, '=("'.$query->fields['telefono'].'")')
							->setCellValue('m'.$n, $query->fields['email'])
							->setCellValue('n'.$n, $query->fields['direccion'])
							->setCellValue('o'.$n, $query->fields['origen'])
							->setCellValue('p'.$n, $query->fields['fuente'])
							->setCellValue('q'.$n, $query->fields['tipocontactabilidad'])
							->setCellValue('r'.$n, $query->fields['contactabilidad'])
							->setCellValue('s'.$n, $query->fields['grupogestion'])
							->setCellValue('t'.$n, $query->fields['resultado'])
							->setCellValue('u'.$n, $query->fields['justificacion'])
							->setCellValue('v'.$n, $impcomp)
							->setCellValue('w'.$n, $feccomp)
							//->setCellValue('c'.$n, $query->fields['doi'])
							->setCellValue('x'.$n, $query->fields['observacion'])
							->setCellValue('y'.$n, $r_hora)
							->setCellValue('z'.$n, $query->fields['usuario'])
							->setCellValue('aa'.$n, $query->fields['peso'])
							;
					$objPHPExcel->getActiveSheet()->getStyle('f'.$n)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
					$query->MoveNext();
						
					
				}
				mysql_free_result($query->_queryID);	
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
		$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(22);;
		$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:Z1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle('A1:Z1')->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setARGB('00000000');
		$objPHPExcel->getActiveSheet()->setTitle('Gestiones');
	
/*Fin Instrucciones*/

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

/*header('Content-Disposition: attachment;filename="gestiones.xls"');
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);*/

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="gestiones.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

$f=$db->_query("update flag_reportes set flag='0' where reporte='gestiones'");
mysql_free_result($f->_queryID);	
$db->Close();

$objWriter->save('php://output');

//header("Location: archivo.xls");
exit;

?>
