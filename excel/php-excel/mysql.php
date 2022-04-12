<?php
require_once("excel.php"); 
require_once("excel-ext.php"); 

$conexion = mysql_connect("localhost", "root", "");
mysql_select_db("gestions", $conexion);
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


while($datatmp = mysql_fetch_assoc($resEmp)) { 
	$data[] = $datatmp; 
}  
createExcel("excel-mysql.xlsx", $data);
exit;
?>