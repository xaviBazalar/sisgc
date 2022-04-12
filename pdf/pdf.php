<?php
require_once('class.ezpdf.php');
$pdf =& new Cezpdf('a4');
$pdf->selectFont('../fonts/courier.afm');
$pdf->ezSetCmMargins(1,1,1.4,1.4);


$conexion = mysql_connect("localhost", "root", "g3st1onkb");
mysql_select_db("sis_gestion", $conexion);
$sql = "SELECT nombre,coddpto,codprov,coddist FROM ubigeos ";
if(isset($_GET['iddpto']) && $_GET['iddpto']!==""){
    $iddpto=$_GET['iddpto'];
    $sql.="WHERE coddpto=$iddpto";

    if(isset($_GET['idprov'])&& $_GET['idprov']!==""){
    $idprov=$_GET['idprov'];
    $sql.=" AND codprov=$idprov";
    }
    if(isset($_GET['iddist'])&& $_GET['iddist']!==""){
    $iddist=$_GET['iddist'];
    $sql.=" AND coddist=$iddist";
    }
}
//echo $sql;

$resEmp = mysql_query($sql, $conexion) or die(mysql_error());
$totEmp = mysql_num_rows($resEmp);

$ixx = 0;

while($datatmp = mysql_fetch_assoc($resEmp)) { 
	$ixx = $ixx+1;
	$data[] = array_merge($datatmp, array('num'=>$ixx));
}
$titles = array(
				'num'=>'<b>Num</b>',
				'nombre'=>'<b>Nombre</b>',
				'coddpto'=>'<b>CodDpto</b>',
                                'codprov'=>'<b>CodProv</b>',
    				'coddist'=>'<b>CodDist</b>'
				
			);
$options = array(
				'shadeCol'=>array(0.8,0.8,0.8),
				'xOrientation'=>'center',
				'width'=>520,
				'fontSize' => 7,
                                'titleFontSize' => 12,
                                'rowGap' => 2,
                                'colGap' => 1

			);
$txttit = "<b>Sistema Gestion</b> - ";
$txttit.= "Exportado desde Ubigeos \n";
/*while($row=mysql_fetch_array($resEmp)){
	 $txttit.= $row['nombre']." \n";
	
}*/

$pdf->ezText($txttit, 9);
$pdf->addJpegFromFile("logo_sys3.jpg", 36, 800);
//$pdf->ezImage("logo_sys3.jpg", 0, 80, 'none', 'left');
$pdf->ezTable($data, $titles, '', $options);

$pdf->ezText("\n\n\n", 10);
$pdf->ezText("<b>Fecha:</b> ".date("d/m/Y"), 10);
$pdf->ezText("<b>Hora:</b> ".date("H:i:s")."\n\n", 10);
ob_end_clean();//linea muy importante
$pdf->ezStream();
?>