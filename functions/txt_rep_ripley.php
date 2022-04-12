<?
session_start();
$iduser=$_SESSION['iduser'];
ini_set('memory_limit', '-1');
set_time_limit(1800);
/** Error reporting */
error_reporting(E_ALL);

date_default_timezone_set('Europe/London');

require_once '../class/PHPExcel.php';
require_once '../class/PHPExcel/IOFactory.php';
include '../scripts/conexion.php';

$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator("Kobsa - Gestion")
	->setLastModifiedBy("Kobsa - Gestion")
	->setTitle("Office 2007 XLSX Test Document")
	->setSubject("Office 2007 XLSX Test Document")
	->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
	->setKeywords("office 2007 openxml php")
	->setCategory("Test result file");

$peri=$_GET['peri'];
$mes_p=$db->Execute("SELECT fecini,MONTH(fecini) mes,YEAR(fecini) ano FROM periodos WHERE idperiodo='$peri'");
$mes_a=$mes_p->fields['mes'];
$año_a=$mes_p->fields['ano'];
$cart=$_GET['cart'];
$sql="SELECT (SELECT COUNT(tc.tipocontactabilidad) FROM gestiones g
JOIN cuentas ct ON g.idcuenta=ct.idcuenta
JOIN carteras cr ON ct.idcartera=cr.idcartera
JOIN proveedores pr ON cr.idproveedor=pr.idproveedor
JOIN resultados r ON r.idresultado=g.idresultado
JOIN contactabilidad co ON g.idcontactabilidad=co.idcontactabilidad
JOIN tipo_contactabilidad tc ON co.idtipocontactabilidad=tc.idtipocontactabilidad
WHERE cr.idcartera='$cart' AND YEAR(g.fecges)='$año_a' AND MONTH(g.fecges)='$mes_a' ";
if(isset($_GET['fecini']) and isset($_GET['fecfin']))
{
	$ini=$_GET['fecini'];
	$fin=$_GET['fecfin'];
	$sql.="AND g.fecges BETWEEN '$ini' AND '$fin' ";
}
$sql.="AND tc.tipocontactabilidad='CONTACTO DIRECTO') AS cd,
(SELECT COUNT(tc.tipocontactabilidad) FROM gestiones g
JOIN cuentas ct ON g.idcuenta=ct.idcuenta
JOIN carteras cr ON ct.idcartera=cr.idcartera
JOIN proveedores pr ON cr.idproveedor=pr.idproveedor
JOIN resultados r ON r.idresultado=g.idresultado
JOIN contactabilidad co ON g.idcontactabilidad=co.idcontactabilidad
JOIN tipo_contactabilidad tc ON co.idtipocontactabilidad=tc.idtipocontactabilidad
WHERE cr.idcartera='$cart' AND YEAR(g.fecges)='$año_a' AND MONTH(g.fecges)='$mes_a' ";
if(isset($_GET['fecini']) and isset($_GET['fecfin']))
{
	$ini=$_GET['fecini'];
	$fin=$_GET['fecfin'];
	$sql.="AND g.fecges BETWEEN '$ini' AND '$fin' ";
}
$sql.="AND tc.tipocontactabilidad='CONTACTO INDIRECTO') AS ci,
(SELECT COUNT(tc.tipocontactabilidad) FROM gestiones g
JOIN cuentas ct ON g.idcuenta=ct.idcuenta
JOIN carteras cr ON ct.idcartera=cr.idcartera
JOIN proveedores pr ON cr.idproveedor=pr.idproveedor
JOIN resultados r ON r.idresultado=g.idresultado
JOIN contactabilidad co ON g.idcontactabilidad=co.idcontactabilidad
JOIN tipo_contactabilidad tc ON co.idtipocontactabilidad=tc.idtipocontactabilidad
WHERE cr.idcartera='$cart' AND YEAR(g.fecges)='$año_a' AND MONTH(g.fecges)='$mes_a' ";
if(isset($_GET['fecini']) and isset($_GET['fecfin']))
{
	$ini=$_GET['fecini'];
	$fin=$_GET['fecfin'];
	$sql.="AND g.fecges BETWEEN '$ini' AND '$fin' ";
}
$sql.="AND tc.tipocontactabilidad='NO CONTACTO') AS nc";
//---------------------------------------------------------------------------------------------
$sql1="SELECT idresultado, idjustificacion FROM gestiones 
WHERE idresultado IN ('17','2','6','7','9','1','12','11','13') 
AND g.fecges BETWEEN '$ini' AND '$fin' ";

echo "$sql1";return false;

$query=$db->Execute($sql);
$a=$query->fields['cd'];
$b=$query->fields['ci'];
$c=$query->fields['nc'];
$sum=$a+$b+$c;

$query1=$db->Execute($sql1);
while (!$query->EOF)
{
 if ($query1->fields['idresultado']==17)
 {
	if ($query1->fields['idjustificacion']==99){$a1+=1;}
	if ($query1->fields['idjustificacion']==100 || $query1->fields['idjustificacion']==98 || $query1->fields['idjustificacion']==102){$a3+=1;}
 }

 if ($query1->fields['idresultado']==2)
 {
	if ($query1->fields['idjustificacion']>77 && $query1->fields['idjustificacion']<80){$a2+=1;}
 }

 if ($query1->fields['idresultado']==6)
 {
	if ($query1->fields['idjustificacion']==80 || $query1->fields['idjustificacion']==82 || $query1->fields['idjustificacion']==83 || $query1->fields['idjustificacion']==86 || $query1->fields['idjustificacion']==89){$a4+=1;}
 }
 
 if ($query1->fields['idresultado']==7)
 {
	if ($query1->fields['idjustificacion']==94){$a5+=1;}
 }
 
 if ($query1->fields['idresultado']==9)
 {
	{$a6+=1;}
 }
 
 //if ($query1->fields['resultado']=="TITULAR SOLICITA NUEVA LLAMADA"){$a7+=1;}
 
 if ($query1->fields['idresultado']==1)
 {
	if ($query1->fields['idjustificacion']>71 && $query1->fields['idjustificacion']<76){$a8+=1;}
 }
 
 if ($query1->fields['idresultado']==12)
 {
	if ($query1->fields['idjustificacion']==119){$b1+=1;} 
	if ($query1->fields['idjustificacion']==120){$c3+=1;} 
	if ($query1->fields['idjustificacion']==112 || $query1->fields['idjustificacion']==122){$c4+=1;}
 }

 if ($query1->fields['idresultado']==11)
 {
	if ($query1->fields['idjustificacion']==114 || $query1->fields['idjustificacion']==115){$b2+=1;} 
	if ($query1->fields['idjustificacion']==110 || $query1->fields['idjustificacion']==111){$b4+=1;} 
	if ($query1->fields['idjustificacion']==119){$b5+=1;}
 }

 if ($query1->fields['idresultado']==13)
 {
	if ($query1->fields['idjustificacion']==125 || $query1->fields['idjustificacion']==211){$b3+=1;} 
	if ($query1->fields['idjustificacion']==123){$c1+=1;} 
	if ($query1->fields['idjustificacion']==127){$c2+=1;}
	if ($query1->fields['idjustificacion']==128){$c5+=1;}
	if ($query1->fields['idjustificacion']==129){$c6+=1;}
 } 
}

$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('a1', "Detalle")
	->setCellValue('b1', "Ctas")
	->setCellValue('c1', "%Tip")
	->setCellValue('d1', "%Tot")
	;

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('a2', "CONTACTO DIRECTO")->setCellValue('b2', $a)->setCellValue('c2', "")->setCellValue('d2', $a/$sum);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('a3', "NO RECONOCE DEUDA")->setCellValue('b3', $a1)->setCellValue('c3', $a1/$a)->setCellValue('d3', $a1/$sum);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('a4', "PROMESA DE PAGO")->setCellValue('b4', $a2)->setCellValue('c4', $a2/$a)->setCellValue('d4', $a2/$sum);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('a5', "RENUENTE")->setCellValue('b5', $a3)->setCellValue('c5', $a3/$a)->setCellValue('d5', $a3/$sum);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('a6', "SEGUIMIENTO")->setCellValue('b6', $a4)->setCellValue('c6', $a4/$a)->setCellValue('d6', $a4/$sum);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('a7', "SIN TRABAJO")->setCellValue('b7', $a5)->setCellValue('c7', $a5/$a)->setCellValue('d7', $a5/$sum);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('a8', "TIENE RECLAMO")->setCellValue('b8', $a6)->setCellValue('c8', $a6/$a)->setCellValue('d8', $a6/$sum);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('a9', "TITULAR SOLICITA NUEVA LLAMADA")->setCellValue('b9', $a7)->setCellValue('c9', $a7/$a)->setCellValue('d9', $a/$sum);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('a10', "YA PAGO")->setCellValue('b10', $a8)->setCellValue('c10', $a8/$a)->setCellValue('d10', $a/$sum);

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('a11', "CONTACTO INDIRECTO")->setCellValue('b11', $b)->setCellValue('c11', "")->setCellValue('d11', $b/$sum);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('a12', "COLGO")->setCellValue('b12', $b1)->setCellValue('c12', $b1/$b)->setCellValue('d12', $b1/$sum);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('a13', "DE VIAJE")->setCellValue('b13', $b2)->setCellValue('c13', $b2/$b)->setCellValue('d13', $b2/$sum);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('a14', "SE MUDO / NO TRABAJA")->setCellValue('b14', $b3)->setCellValue('c14', $b3/$b)->setCellValue('d14', $b3/$sum);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('a15', "TERCERO CASA")->setCellValue('b15', $b4)->setCellValue('c15', $b4)->setCellValue('d15', $b4/$sum);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('a16', "TERCERO OFICINA")->setCellValue('b16', $b5)->setCellValue('c16', $b5/$b)->setCellValue('d16', $b5/$sum);

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('a17', "NO CONTACTO")->setCellValue('b17', $c)->setCellValue('c17', "")->setCellValue('d17', $c/$sum);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('a18', "FAX")->setCellValue('b18', $c1)->setCellValue('c18', $c1/$c)->setCellValue('d18', $c1/$sum);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('a19', "FUERA DE SERVICIO")->setCellValue('b19', $c2)->setCellValue('c19', $c2/$c)->setCellValue('d19', $c2/$sum);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('a20', "GRABADORA")->setCellValue('b20', $c3)->setCellValue('c20', $c3/$c)->setCellValue('d20', $c3/$sum);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('a21', "NO CONTESTA")->setCellValue('b21', $c4)->setCellValue('c21', $c4/$c)->setCellValue('d21', $c4/$sum);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('a22', "NO LO CONOCEN")->setCellValue('b22', $c5)->setCellValue('c22', $c5/$c)->setCellValue('d22', $c5/$sum);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('a23', "SIN TELEFONO")->setCellValue('b23', $c6)->setCellValue('c23', $c6/$c)->setCellValue('d23', $c6/$sum);

$objPHPExcel->getActiveSheet()->getStyle('f'.$n)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);

mysql_free_result($query->_queryID);	
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFill()
->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
->getStartColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);

$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getFill()
->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
->getStartColor()->setARGB(PHPExcel_Style_Color::COLOR_YELLOW);

$objPHPExcel->getActiveSheet()->getStyle('A11:D11')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
$objPHPExcel->getActiveSheet()->getStyle('A11:D11')->getFill()
->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
->getStartColor()->setARGB(PHPExcel_Style_Color::COLOR_YELLOW);

$objPHPExcel->getActiveSheet()->getStyle('A17:D17')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
$objPHPExcel->getActiveSheet()->getStyle('A17:D17')->getFill()
->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
->getStartColor()->setARGB(PHPExcel_Style_Color::COLOR_YELLOW);


$objPHPExcel->getActiveSheet()->setTitle('Gestiones');

$objPHPExcel->setActiveSheetIndex(0);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="gestiones.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

mysql_free_result($f->_queryID);	
$db->Close();

$objWriter->save('php://output');

exit;

	//echo "Reporte Ceconsud:<a href='functions/guardar_como.php?name=gest_ripley.txt' target='blank'>Click para descargar</a><br/>";	
	$db->Execute("update flag_reportes set flag='0' where reporte='gestiones'");
	$db->Close();
?>