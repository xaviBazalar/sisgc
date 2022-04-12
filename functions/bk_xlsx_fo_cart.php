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
$flag2=$db->Execute("select flag from flag_reportes where reporte='gestiones'");
	if($flag2->fields['flag']!="0"){
		echo "En estos momentos se estan ejecutando muchos reportes en curso. Espero uno minutos por favor y vuelva a intentarlo.";
		return false;
	}
mysql_free_result($flag2->_queryID);	
$flag3=$db->Execute("select flag from flag_reportes where reporte='importacion'");
	if($flag3->fields['flag']!="0"){
		echo "En estos momentos se estan ejecutando muchos reportes en curso. Espero uno minutos por favor y vuelva a intentarlo.";
		return false;
	}	
mysql_free_result($flag3->_queryID);	
$flag=$db->Execute("select flag from flag_reportes where reporte='f_cartera'");
if($flag->fields['flag']=="0"){
	
	$ip=$_SERVER['REMOTE_ADDR'];
	$db->_query("update flag_reportes set flag='1',idusuario='$iduser',host='$ip' where reporte='f_cartera'");
	$fecha=date("Y-m-d");
	$hora=date("H:i:s");
	$db->_query("INSERT into detalle_reportes (`idreporte`,`idusuario`,`fecha`,`hora_ini`,`host`) VALUES('3','$iduser','$fecha','$hora','$ip')");
}else{
	echo "En estos momentos se estan ejecutando muchos reportes en curso. Espero uno minutos por favor y vuelva a intentarlo.";
		return false;


}
mysql_free_result($flag->_queryID);
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
		//$mes=$db->Execute("Select month(fecini) mes from periodos where idperiodo='$peri'");
		$mes_p=$db->Execute("SELECT MONTH(fecini) mes FROM periodos WHERE idperiodo='$peri'");
		$mes_a=$mes_p->fields['mes'];
		
		
		
		
		
		$tmp_t="CREATE TEMPORARY TABLE tmpr_ftc
					(
							idcuenta VARCHAR(11) NOT NULL ,
							idcliente VARCHAR(13) NOT NULL ,
							idmoneda INT(11),
							idproveedor INT(11),		
							idcartera INT(11), 
							idproducto INT(11),
							idtipocartera INT(11),
							fecven DATE,
							nrocuota INT(11),
							diasmora INT(11),
							idestado INT(11),
							observacion VARCHAR(200),
							observacion2 VARCHAR(200),
							impcap FLOAT(10,2),
							impven FLOAT(10,2),
							impint FLOAT(10,2),
							impotr FLOAT(10,2),
							impmor FLOAT(10,2),
							imptot FLOAT(10,2),
							impmin FLOAT(10,2),
							impdestot FLOAT(10,2),
							impedesmor FLOAT(10,2),
							impdesmmo FLOAT(10,2),
							impfraini FLOAT(10,2),
							impfracuo FLOAT(10,2),
							impfracpr FLOAT(10,2),
							impframnt FLOAT(10,2),
							impcapori FLOAT(10,2),
							impprxpag FLOAT(10,2),
							fracuo INT(11),
							grupo VARCHAR(50),
							ciclo VARCHAR(50),
							idcontactabilidad INT(11),
							idresultado INT(11),
							idjustificacion INT(11),
							observacion_g VARCHAR(200),
							fecges DATE,
							horges TIME,
							impcomp FLOAT(10,2),
							feccomp DATE,
							fecconf DATE,
							idactividad INT(11),
							idtelefono INT(11),
							iddireccion INT(11),
							idemail INT(11),
							idubicabilidad INT(11),
							idpredio INT(11),
							idmaterial_predio INT(11),
							idnro_pisos INT(11),
							idcolor_pared INT(11),
							idagente INT(11),
							idusuario INT(11),
							INDEX `idcliente` (`idcliente`),
							INDEX `idcuenta` (`idcuenta`),
							INDEX `idmonedad` (`idmoneda`),
							INDEX `idproveedor` (`idproveedor`),
							INDEX `idcartera` (`idcartera`),
							INDEX `idproducto` (`idproducto`),
							INDEX `idtipocartera` (`idtipocartera`),
							INDEX `idcontactabilidad` (`idcontactabilidad`),
							INDEX `idresultado` (`idresultado`),
							INDEX `idjustificacion` (`idjustificacion`),
							INDEX `idactividad` (`idactividad`),
							INDEX `idtelefono` (`idtelefono`),
							INDEX `iddireccion` (`iddireccion`),
							INDEX `idemail` (`idemail`),
							INDEX `idubicabilidad` (`idubicabilidad`),
							INDEX `idpredio` (`idpredio`),
							INDEX `idmaterial_predio` (`idmaterial_predio`),
							INDEX `idnro_pisos` (`idnro_pisos`),
							INDEX `idcolor_pared` (`idcolor_pared`),
							INDEX `idagente` (`idagente`),
							INDEX `idusuario` (`idusuario`)


						  )ENGINE = MEMORY ;";
						  
		$db->Execute($tmp_t);			  
						  
		$fas_one="SELECT ct.idcuenta,ct.idmoneda,ct.idcartera,ct.idproducto,ct.idcliente,ct.idtipocartera,
				cp.idusuario,cp.idperiodo,cp.fecven,cp.nrocuotas,cp.diasmora,cp.idestado,cp.observacion,cp.observacion2,cp.impcap,
				cp.impven,cp.impint,cp.impotr,cp.impmor,cp.imptot,cp.impmin,cp.impdestot,cp.impedesmor,cp.impdesmmo,cp.impfraini,cp.impfracuo,
				cp.impfracpr,cp.impframnt,cp.impcapori,cp.impprxpag,cp.fracuo,cp.grupo,cp.ciclo,
				g.idcontactabilidad,g.idresultado,g.idjustificacion,g.observacion,g.fecges,g.horges,g.impcomp,g.feccomp,g.fecconf,g.idactividad,g.idtelefono,
				g.iddireccion,g.idemail,g.idubicabilidad,g.idpredio,g.idmaterial_predio,g.idnro_pisos,g.idcolor_pared,g.idagente
				FROM cuentas ct
				JOIN cuenta_periodos cp ON ct.idcuenta=cp.idcuenta 
				LEFT JOIN gestiones g ON ct.idcuenta=g.idcuenta AND MONTH(g.fecges)='8'
				WHERE cp.idperiodo='9' AND ct.idcartera='6' 
				GROUP BY g.idgestion
									";
									
		$datos=$db->Execute($fas_one);
			$db->debug=true;
		while(!$datos->EOF){
			$cta=$datos->fields['idcuenta'];
			$idmon=$datos->fields['idmoneda'];
			$idcart=$datos->fields['idcartera'];
			$idpro=$datos->fields['idproducto'];
			$idcli=$datos->fields['idcliente'];
			$idtipoc=$datos->fields['idtipocartera'];
			$id_usu=$datos->fields['idusuario'];
			$fecven=$datos->fields['fecven'];
			$nro_cuota=$datos->fields['nrocuotas'];
			$diasmora=$datos->fields['diasmora'];
			$idestado=$datos->fields['idestado'];
			$obs=$datos->fields['observacion'];
			$obs2=$datos->fields['observacion2'];
			$impcap=$datos->fields['impcap'];
			$cta=$datos->fields['idcuenta'];
			$cta=$datos->fields['idcuenta'];
			$cta=$datos->fields['idcuenta'];
			$cta=$datos->fields['idcuenta'];
			$cta=$datos->fields['idcuenta'];
			$cta=$datos->fields['idcuenta'];
			$cta=$datos->fields['idcuenta'];
			$cta=$datos->fields['idcuenta'];
			$cta=$datos->fields['idcuenta'];
			$cta=$datos->fields['idcuenta'];
			
		}
		
		return false;
		mysql_free_result($mes_p->_queryID);	
		/*$sql="SELECT MIN(j.peso) peso,MAX(gt.fecreg),cl.idcliente,d.doi,cl.cliente,pr.personerias,cp.idcuenta,mn.simbolo ,p.proveedor
				,c.cartera,pd.producto,e.estado,pe.periodo,us.usuario,cp.fecven ,gt.feccomp,cp.nrocuotas,cp.diasmora,cp.grupo,cp.ciclo
				,cp.observacion,cp.impcap ,cp.impint,cp.impmor,cp.impotr,cp.imptot,cp.impven,cp.impmin,cp.impdestot,cp.impedesmor 
				,cp.impdesmmo,cp.impfraini,cp.fracuo,cp.impfracpr,cp.impframnt,cp.impcapori,cp.impprxpag ,tc.tipocartera,dc.direccion,
				od.origendireccion,cdr.cuadrante,dc.coddpto,dc.codprov,dc.coddist ,f.fuente,dc.idestado,dc.prioridad,dc.idvalidacion v_dk,
				dc.idvalidacion	v_cc,gt.fecges,gg.grupogestion ,r.resultado,j.justificacion,
				CASE WHEN gt.idagente!=0 THEN gt.fecges END fecges_dk, CASE WHEN gt.idagente!=0 THEN gg.grupogestion END t_resultado_dk,
				CASE WHEN gt.idagente!=0 THEN r.resultado END resultado_dk	 
				FROM clientes cl JOIN doi d ON cl.iddoi=d.iddoi 
				JOIN personerias pr ON cl.idpersoneria=pr.idpersoneria 
				JOIN cuentas ct ON cl.idcliente=ct.idcliente 
				LEFT JOIN tipo_carteras tc ON ct.idtipocartera=tc.idtipocartera 
				JOIN monedas mn ON ct.idmoneda=mn.idmoneda 
				JOIN carteras c ON ct.idcartera=c.idcartera 
				LEFT JOIN gestiones gt ON ct.idcuenta=gt.idcuenta AND MONTH(gt.fecges)='$mes_a'
				LEFT JOIN direcciones dc ON cl.idcliente=dc.idcliente AND dc.prioridad='1' 
				LEFT JOIN origen_direcciones od ON dc.idorigendireccion=od.idorigendireccion 
				LEFT JOIN cuadrantes cdr ON dc.idcuadrante=cdr.idcuadrante 
				LEFT JOIN fuentes f ON dc.idfuente=f.idfuente 
				LEFT JOIN resultados r ON gt.idresultado=r.idresultado 
				LEFT JOIN grupo_gestiones gg ON r.idgrupogestion=gg.idgrupogestion 
				LEFT JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion 
				JOIN proveedores p ON c.idproveedor=p.idproveedor 
				JOIN productos pd ON ct.idproducto=pd.idproducto 
				JOIN cuenta_periodos cp ON ct.idcuenta=cp.idcuenta 
				JOIN estados e ON cp.idestado=e.idestado 
				JOIN periodos pe ON cp.idperiodo=pe.idperiodo 
				JOIN usuarios us ON cp.idusuario=us.idusuario 

			";	
		if(isset($_GET['peri'])){
			$peri=$_GET['peri'];
			$sql.=" WHERE cp.idperiodo='$peri' ";
		}	
		
		if(isset($_GET['prove'])){
			$prov=$_GET['prove'];
			$sql.=" AND p.idproveedor='$prov' ";
		}

		if(isset($_GET['cart'])){
			$cart=$_GET['cart'];
			$sql.=" AND c.idcartera='$cart' ";
		}
			
			$sql.=" GROUP BY cp.idcuenta ORDER BY gt.fecreg DESC,cl.idcliente ";*/
		echo $sql;
		return false;			
		$n=1;
		
		$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('a1', "Nro.Doc")
							->setCellValue('b1', "Tipo Doc.")
							->setCellValue('c1', "Cliente")
							->setCellValue('d1', "Tip.Pers")
							->setCellValue('e1', "Cuenta")
							->setCellValue('f1', "Moneda")
							->setCellValue('g1', "Proveedor")
							->setCellValue('h1', "Cartera")
							->setCellValue('i1', "Producto")
							->setCellValue('j1', "Estado")
							->setCellValue('k1', "Periodo")
							->setCellValue('l1', "Usuario")
							->setCellValue('m1', "FecVen")
							->setCellValue('n1', "FecCon")
							->setCellValue('o1', "Nro.Cuotas")
							->setCellValue('p1', "Dias Mora")
							->setCellValue('q1', "Grupo")
							->setCellValue('r1', "Ciclo")
							->setCellValue('s1', "Obs.")
							->setCellValue('t1', "Impcap")
							->setCellValue('u1', "Impint")
							->setCellValue('v1', "Impmor")
							->setCellValue('w1', "Impotr")
							->setCellValue('x1', "Imptot")
							->setCellValue('y1', "Impven")
							->setCellValue('z1', "Impmin")
							->setCellValue('aa1', "Impdestot")
							->setCellValue('ab1', "Impdesmor")
							->setCellValue('ac1', "Impdesmmo")
							->setCellValue('ad1', "Impfraini")
							->setCellValue('ae1', "Fracuo")
							->setCellValue('af1', "Impfracpr")
							->setCellValue('ag1', "Impframnt")
							->setCellValue('ah1', "Impcapori")
							->setCellValue('ai1', "Impprxpag")
							->setCellValue('aj1', "Tipo Cartera")
							->setCellValue('ak1', "Imppagoperacum")
							->setCellValue('al1', "Direccion")
							->setCellValue('am1', "Origen Direccion")
							->setCellValue('an1', "Cuadrante")
							->setCellValue('ao1', "CodDpto")
							->setCellValue('ap1', "CodProv")
							->setCellValue('aq1', "CodDist")
							->setCellValue('ar1', "Fuente")
							->setCellValue('as1', "Estado")
							->setCellValue('at1', "Prioridad")
							->setCellValue('au1', "Validacion_DK")
							->setCellValue('av1', "Validacion_CC")
							->setCellValue('aw1', "FecRes")
							->setCellValue('ax1', "Tipo Resultado")
							->setCellValue('ay1', "Resultado")
							->setCellValue('az1', "Detalle")
							->setCellValue('ba1', "FecRes_DK")
							->setCellValue('bb1', "T.Resultado_DK")
							->setCellValue('bc1', "Resultado_DK")
							;
		
		$objPHPExcel->getDefaultStyle()->getFont()
			->setName('Arila')
			->setSize(10);
		
		$query=$db->Execute($sql);
	
				while(!$query->EOF){
					++$n;
					$pos = strpos($query->fields['idcuenta'], "-");
					
								if($pos){
									$ctas = explode("-",$query->fields['idcuenta']);
									if(count($ctas)>2){ $cta=$ctas[0]."-".$ctas[1]; } else { $cta=$ctas[0]; }
								}
								
								/*if($n==2){
									$_SESSION['cta']=$query->fields['idcuenta'];
									$_SESSION['cli']=$query->fields['idcliente'];
									$_SESSION['peso']=$query->fields['peso'];
								}	
								
								if($n!=2){
									if($query->fields['idcliente']==$_SESSION['cli'] && $query->fields['idcuenta']==$_SESSION['cta'] ){
										if($query->fields['peso'] <= $_SESSION['peso']){
											$n=$n-1;
										}else{
											$n=$n-1;
											$query->MoveNext();
											continue;
										}
									}else{
										$_SESSION['cta']=$query->fields['idcuenta'];
										$_SESSION['cli']=$query->fields['idcliente'];
										$_SESSION['peso']=$query->fields['peso'];
									}
								}*/
								
					
					$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('a'.$n, '="'.$query->fields['idcliente'].'"')
							->setCellValue('b'.$n, $query->fields['doi'])
							->setCellValue('c'.$n, utf8_encode($query->fields['cliente']))
							->setCellValue('d'.$n, $query->fields['personerias'])
							->setCellValue('e'.$n, '="'.$cta.'"')
							->setCellValue('f'.$n, $query->fields['simbolo'])
							->setCellValue('g'.$n, $query->fields['proveedor'])
							->setCellValue('h'.$n, $query->fields['cartera'])
							->setCellValue('i'.$n, $query->fields['producto'])
							->setCellValue('j'.$n, $query->fields['estado'])
							->setCellValue('k'.$n, $query->fields['periodo'])
							->setCellValue('l'.$n, $query->fields['usuario'])
							->setCellValue('m'.$n, $query->fields['fecven'])
							->setCellValue('n'.$n, $query->fields['feccomp'])
							->setCellValue('o'.$n, $query->fields['nrocuotas'])
							->setCellValue('p'.$n, $query->fields['diasmora'])
							->setCellValue('q'.$n, $query->fields['grupo'])
							->setCellValue('r'.$n, $query->fields['ciclo'])
							->setCellValue('s'.$n, $query->fields['observacion'])
							->setCellValue('t'.$n, $query->fields['impcap'])
							->setCellValue('u'.$n, $query->fields['impint'])
							->setCellValue('v'.$n, $query->fields['impmor'])
							->setCellValue('w'.$n, $query->fields['impotr'])
							->setCellValue('x'.$n, $query->fields['imptot'])
							->setCellValue('y'.$n, $query->fields['impven'])
							->setCellValue('z'.$n, $query->fields['impmin'])
							->setCellValue('aa'.$n, $query->fields['impdestot'])
							->setCellValue('ab'.$n, $query->fields['impedesmor'])
							->setCellValue('ac'.$n, $query->fields['impdesmmo'])
							->setCellValue('ad'.$n, $query->fields['impfraini'])
							->setCellValue('ae'.$n, $query->fields['fracuo'])
							->setCellValue('af'.$n, $query->fields['impfracpr'])
							->setCellValue('ag'.$n, $query->fields['impframnt'])
							->setCellValue('ah'.$n, $query->fields['impcapori'])
							->setCellValue('ai'.$n, $query->fields['impprxpag'])
							->setCellValue('aj'.$n, $query->fields['tipocartera'])
							->setCellValue('ak'.$n, "-")
							->setCellValue('al'.$n, $query->fields['direccion'])
							->setCellValue('am'.$n, $query->fields['origendireccion'])
							->setCellValue('an'.$n, $query->fields['cuadrante'])
							->setCellValue('ao'.$n, $query->fields['coddpto'])
							->setCellValue('ap'.$n, $query->fields['codprov'])
							->setCellValue('aq'.$n, $query->fields['coddist'])
							->setCellValue('ar'.$n, $query->fields['fuente'])
							->setCellValue('as'.$n, $query->fields['idestado'])
							->setCellValue('at'.$n, $query->fields['prioridad'])
							->setCellValue('au'.$n, $query->fields['v_dk'])
							->setCellValue('av'.$n, $query->fields['v_cc'])
							->setCellValue('aw'.$n, $query->fields['fecges'])
							->setCellValue('ax'.$n, $query->fields['grupogestion'])
							->setCellValue('ay'.$n, $query->fields['resultado'])
							->setCellValue('az'.$n, $query->fields['justificacion'])
							->setCellValue('ba'.$n, $query->fields['fecges_dk'])
							->setCellValue('bb'.$n, $query->fields['t_resultado_dk'])
							->setCellValue('bc'.$n, $query->fields['resultado_dk'])
							
							;
							
					$objPHPExcel->getActiveSheet()->getStyle('p'.$n)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('q'.$n)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('r'.$n)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('s'.$n)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('aa'.$n)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
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
		$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AJ')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AK')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AL')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AM')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AN')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AO')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AP')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AQ')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AR')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AS')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AT')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AU')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AV')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AW')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AX')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AY')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AZ')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('BA')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('BB')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('BC')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setTitle('Foto_Cartera');
	
/*Fin Instrucciones*/

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

/*header('Content-Disposition: attachment;filename="foto_cartera.xls"');
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);*/

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="foto_cartera.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

mysql_free_result($query->_queryID);
$db->Execute("update flag_reportes set flag='0' where reporte='f_cartera'");
$db->Close();
//$objWriter->save('archivo.xls');  
$objWriter->save('php://output');

//header("Location: archivo.xls");
exit;

?>
