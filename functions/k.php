<?php
	return false;
	include '../scripts/conexion.php';

	$consulta=$db->Execute("SHOW PROCESSLIST");
	
	$consulta=$db->Execute("SHOW PROCESSLIST");
	while(!$consulta->EOF){
		$id=$consulta->fields['Id'];
		
		$z=$db->Execute("KILL $id");
		$consulta->MoveNext();
		mysql_free_result($z->_queryID);
	}
	mysql_free_result($consulta->_queryID);
	$db->Close();
	//function restaFechas($dFecIni, $dFecFin)
/*{
    $dFecIni = str_replace("-","",$dFecIni);
    $dFecIni = str_replace("/","",$dFecIni);
    $dFecFin = str_replace("-","",$dFecFin);
    $dFecFin = str_replace("/","",$dFecFin);
 
    ereg( "([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})", $dFecIni, $aFecIni);
    ereg( "([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})", $dFecFin, $aFecFin);
 
    $date1 = mktime(0,0,0,$aFecIni[2], $aFecIni[1], $aFecIni[3]);
    $date2 = mktime(0,0,0,$aFecFin[2], $aFecFin[1], $aFecFin[3]);
 
    return round(($date2 - $date1) / (60 * 60 * 24));
}**/
 
// Ej.: con fechas fijas
//$resultado_resta = restaFechas('01-05-2007','04-05-2007');
//echo "Artículo publicado hace ".$resultado_resta." días.";
?>
