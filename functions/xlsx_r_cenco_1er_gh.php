<?php
//return false;


ini_set('memory_limit', '-1');
set_time_limit(1800);
 
include '../scripts/conexion.php';

echo "<pre>";

//$db->debug=true;

$hora=date("H:i:s");
$archivo='1er_cenco'.$hora.'.txt';
$fp = fopen($archivo, 'w');

$peri=$_GET['peri'];
$mes_p=$db->Execute("SELECT fecini,MONTH(fecini) mes,YEAR(fecini) ano FROM periodos WHERE idperiodo='$peri'");
$mes_a=$mes_p->fields['mes'];
$año_a=$mes_p->fields['ano'];
$cart=$_GET['cart'];
if(isset($_GET['fecini']) and isset($_GET['fecfin'])){
			$ini=$_GET['fecini'];
			$fin=$_GET['fecfin'];
			//$sql.=" AND g.fecges BETWEEN '$ini' AND '$fin' ";
}
$sql="SELECT g.usureg,u.usuario,MIN(TIME(g.fecreg)) hora_ges  FROM cuentas c 
		JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta AND cp.idperiodo=16
		JOIN gestiones g ON cp.idcuenta=g.idcuenta
		JOIN usuarios u ON g.usureg=u.idusuario		WHERE g.fecges='$ini' AND c.idcartera=9
		GROUP BY g.usureg";
		
		
	/*echo $sql;
	return false;*/

	$rp=$db->Execute($sql);
	//$titulo="Ident.usuario|Ident.cliente|F.Accion|Accion|Respuesta|Contacto|Observacion|F.Prox.G|Telefono|Indicativo|Tip|T.Act/Desact|Ind.T.Act/Desac|Tip.T.Act/Desac|Direccion|Ciudad direccion|Zona direccion|Tipo direccion|Sec.direccion|Id.destinatario|Codigo carta|Entidad|Producto|Obligacion|Ent.de obligac|Monto promesa|Fecha|S|Num.cheque|Ent.cheque|Def.usuario|Visitador|";
	$titulo="ID_USUARIO\tUSUARIO\tHORA_GESTION";	
	$año=date("Y");
	
	fwrite($fp, $titulo);
	fwrite($fp , chr(13).chr(10));	
	while(!$rp->EOF){
		$id_us=$rp->fields['idusuario'];
		$user=utf8_encode($rp->fields['usuario']);
		$hora=$rp->fields['hora_ges'];
		
		$linea="$id_us\t$user\t$hora";
	
		fwrite($fp, $linea);
		fwrite($fp , chr(13).chr(10));
		$rp->MoveNext();
		
	}	


mysql_free_result($rp->_queryID);	


$db->Close();
echo "</br></br></br>";
echo "<font color='red'>Inicio de reporte :</font>".$ti=date("H:i:s")." - <font color='blue'>Fin de Reporte:</font>".$tf=date("H:i:s")." - ";
echo "Guardar:<a href='guardar_como.php?name=$archivo' target='blank'>$archivo</a>";	


?>
