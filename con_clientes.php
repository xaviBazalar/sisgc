<?php 
if(!isset($_SESSION)){
		session_start();
	}
?>
<html>
<body>
<div id="main_sys" style="float: left; width:auto;">
	<form name="frmDatos" method="post" action="">
		<center><table></center>
		<td>Num Documento: </td>
		<td><input type="text" id="numdoc" name="numdoc" value="<? echo $_POST["numdoc"] ?>" size="12" maxlength="12" /></td>
		<td><input id="srch" class='btn' type="submit" value="Buscar"/></td></table>
	</form>
</div>
</body>
</html>
<?php 
if(isset($_POST["numdoc"]))
{
$cn=mysql_connect("localhost","root","g3st1onkb") or die (mysql_error());
mysql_select_db("sis_gestion")or die (mysql_error()); 
$sql="SELECT cl.idcliente, cl.cliente ,ca.cartera,pr.proveedor FROM cuentas cu JOIN clientes cl ON cl.idcliente=cu.idcliente ";
$sql.="JOIN carteras ca ON ca.idcartera=cu.idcartera ";
$sql.="JOIN proveedores pr ON pr.idproveedor=ca.idproveedor ";
$sql.="WHERE cl.idcliente='".$_POST['numdoc']."' GROUP BY cartera";
//echo "$sql";
$result=mysql_query($sql,$cn);
echo "<br/><br/><br/><br/><table id='design1' border=1><p>";
echo "<tr><p>";
echo "<thead>";
echo "<th><b>Id Cliente</b></th>";
echo "<th><b>Cliente</b></th>";
echo "<th><b>Cartera</b></th>";
echo "<th><b>Proveedor</b></th>";
echo "</thead>";
echo "</tr><p>";
echo "<tbody>";
while ($row=mysql_fetch_array($result)){
echo "<tr><p>";
echo "<td>$row[0]</td>";
echo "<td>$row[1]</td>";
echo "<td>$row[2]</td>";
echo "<td>$row[3]</td>";
echo "</tr><p>";
}
echo "</tbody>";
echo "</table><p>";
mysql_close();
}
?>