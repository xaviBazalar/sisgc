<?php
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=archivo.xls");
header("Pragma: no-cache");
header("Expires: 0");
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

$resEmp = mysql_query($sql, $conexion) or die(mysql_error());
echo "<table border=1> ";
echo "<tr> ";
echo "<th>Nombre</th><th>Cod_Dpto</th><th>Cod_Prov</th><th>Cod_Dist</th> ";
echo "</tr> ";
    while($row= mysql_fetch_array($resEmp)){
        echo "<tr> ";
        echo "<td><font color=green></font>".$row['nombre']."</td> ";
        echo "<td>".$row['coddpto']."</td> ";
        echo "<td>".$row['codprov']."</td> ";
        echo "<td>".$row['coddist']."</td> ";
        echo "</tr> ";
    }
echo "</table> ";
?>