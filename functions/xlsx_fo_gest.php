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
/*
$flag=$db->Execute("select flag from flag_reportes where reporte='gestiones'");
if($flag->fields['flag']=="0"){
	$ip=$_SERVER['REMOTE_ADDR'];
	$db->Execute("update flag_reportes set flag='1',idusuario='$iduser',host='$ip' where reporte='gestiones'");
	$fecha=date("Y-m-d");
	$hora=date("H:i:s");
	$db->Execute("INSERT into detalle_reportes (`idreporte`,`idusuario`,`fecha`,`hora_ini`,`host`) VALUES('2','$iduser','$fecha','$hora','$ip')");
}else{
	echo "En estos momentos ya se encuentra una reporte de gestiones en curso. Espero uno minutos por favor y vuelva a intentarlo.";
	return false;
}
*/
include 'rango_hora.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

		$peri=$_GET['peri'];
		//$mes=$db->Execute("Select month(fecini) mes from periodos where idperiodo='$peri'");
		$mes_p=$db->Execute("SELECT MONTH(fecini) mes,YEAR(fecini) ano FROM periodos WHERE idperiodo='$peri'");
		$mes_a=$mes_p->fields['mes'];
		$ano_a=$mes_p->fields['ano'];
		//mysql_free_result($mes_p->_queryID);
/*Inicio Instrucciones*/
if($mes_a<10){
	$mes_a="0".$mes_a;
}		
	$objPHPExcel->getProperties()->setCreator("Kobsa - Gestion")
									 ->setLastModifiedBy("Kobsa - Gestion")
									 ->setTitle("Office 2007 XLSX Test Document")
									 ->setSubject("Office 2007 XLSX Test Document")
									 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
									 ->setKeywords("office 2007 openxml php")
									 ->setCategory("Test result file");
		
		/*$sql=" SELECT  ct.idcliente
	,cl.cliente
	,ct.idcuenta
	,m.monedas	
	,pr.proveedor
	,cra.cartera
	,pd.producto
	,a.idactividad
	,a.actividad
	,v.validaciones	
	,t.telefono
	-- ,e.email
	,d.direccion
	,CASE WHEN t.idorigentelefono!=0 THEN ot.origentelefono WHEN d.idorigendireccion!=0 THEN od.origendireccion END AS origen
	,ft.fuente
	,tcb.tipocontactabilidad
	,co.contactabilidad
	,gg.grupogestion
	,r.resultado
	,j.peso
	,j.justificacion
	,g.fecreg
	,g.impcomp
	,g.feccomp
	,g.observacion
	,g.fecges
	,g.horges
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
				LEFT JOIN justificaciones j ON g.idjustificacion=j.idjustificacion 
				JOIN actividades a ON g.idactividad=a.idactividad
				-- LEFT JOIN emails e ON g.idemail=e.idemail
				LEFT JOIN telefonos t ON g.idtelefono=t.idtelefono
				LEFT JOIN direcciones d ON g.iddireccion=d.iddireccion
				LEFT JOIN origen_telefonos ot ON t.idorigentelefono=ot.idorigentelefono	
				LEFT JOIN origen_direcciones od ON d.idorigendireccion=od.idorigendireccion
				LEFT JOIN fuentes ft ON t.idfuente=ft.idfuente													
				LEFT JOIN validaciones v ON t.idvalidacion=v.idvalidaciones				
				left JOIN usuarios u ON g.usureg=u.idusuario	 
				where -- Month(g.fecges)='$mes_a'
				fecges LIKE '$ano_a-$mes_a-%' AND ";*/
		$sql="
			/*Foto Gestion */ SELECT ct.idcliente ,cl.cliente ,ct.idcuenta ,m.monedas	 ,pr.proveedor ,cra.cartera ,pd.producto ,a.idactividad ,a.actividad ,
	v.validaciones	 ,t.telefono /*,e.email*/ ,d.direccion ,
	CASE 
		WHEN t.idorigentelefono!=0 THEN ot.origentelefono 
		WHEN d.idorigendireccion!=0 THEN od.origendireccion
	END AS origen ,ft.fuente ,tcb.tipocontactabilidad ,co.contactabilidad ,gg.grupogestion ,r.resultado ,j.peso ,
	j.justificacion ,g.fecreg ,g.impcomp ,g.feccomp ,g.observacion ,g.fecges ,g.horges ,u.usuario ,HOUR(g.horges) AS hora 
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
	LEFT JOIN justificaciones j ON g.idjustificacion=j.idjustificacion 
	JOIN actividades a ON g.idactividad=a.idactividad 
	/*LEFT JOIN emails e ON g.idemail=e.idemail*/ 
	LEFT JOIN telefonos t ON g.idtelefono=t.idtelefono 
	LEFT JOIN direcciones d ON g.iddireccion=d.iddireccion 
	LEFT JOIN origen_telefonos ot ON t.idorigentelefono=ot.idorigentelefono	 
	LEFT JOIN origen_direcciones od ON d.idorigendireccion=od.idorigendireccion 
	LEFT JOIN fuentes ft ON t.idfuente=ft.idfuente	 
	LEFT JOIN validaciones v ON t.idvalidacion=v.idvalidaciones	 
	LEFT JOIN usuarios u ON g.usureg=u.idusuario	 
WHERE 
fecges LIKE '$ano_a-$mes_a%'  AND g.idactividad!=4 AND 
			
			";
		
			if(isset($_GET['peri'])){
				$peri=$_GET['peri'];
				$sql.=" cp.idperiodo='$peri'   ";
			}	

		if(isset($_GET['prove']) and $_GET['cart']==""){
			$prov=$_GET['prove'];
			$CRT="";
			$t_cartera=$db->Execute("select idcartera from carteras where idproveedor=$prov");
				while(!$t_cartera->EOF){
					$CRT.="'".$t_cartera->fields['idcartera']."',";
					$t_cartera->MoveNext();
				}
			$sql.=" AND cra.idcartera in (".substr($CRT,0,-1).")";
			//$sql.=" AND pr.idproveedor='$prov' "; AND u.idproveedor='$prov' 
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

		/*echo $sql;
		return false;*/

		$query=$db->Execute($sql);
				
		$n=1;
		unlink('f_gestion_'.$cart.'.txt');
		
		$fp=fopen("f_gestion_$cart.txt",'w');
		chmod("f_gestion_$cart.txt", 0777);
		$titulo="Documento\tCliente\tCta\tMoneda\tProveedor\tCartera\tProducto\tIndicador\tFechaGest\tHoraGest\tVal\tTelGest\tMailGest\tDirGest\t";
		$titulo.="Origen\tFuente\tTipoContacto\tContacto\tTipoResultado\tResultado\tJustificacion\tImpComp\tFecComp\tObservacion\tRangoHora\t";
		$titulo.="Negociador\tPeso";
		$body=$titulo;
		fwrite($fp,$body);
		fwrite($fp , chr(13).chr(10));
		$query=$db->Execute($sql);
	
			while(!$query->EOF){
				++$n;
				if($total!=0){
						if(in_array($query->fields['idactividad'],$act)){
						
						}else{
							$query->MoveNext();
							continue;
						}
				}
				$pos = strpos($query->fields['idcuenta'], "-");
				$r_hora=rango_hora($query->fields['horges']);
				if($query->fields['impcomp']=="0.00"){	$impcomp="";}else{$impcomp=$query->fields['impcomp'];}
				if($query->fields['feccomp']=="0000-00-00"){$feccomp="";}else{$feccomp=$query->fields['feccomp'];}
					$pos = strpos($query->fields['idcuenta'], "-");
				if($pos){
					$ctas = explode("-",$query->fields['idcuenta']);
				if(count($ctas)>2){ $cta=$ctas[0]."-".$ctas[1]; } else { $cta=$ctas[0]; }
				}
				if($query->fields['impcomp']==0){$impcomp="";}else{$impcomp=$query->fields['impcomp'];}
				if($query->fields['feccomp']=="0000-00-00"){$feccomp="";}else{$feccomp=$query->fields['feccomp'];}
					if($pos){
						$ctas = explode("-",$query->fields['idcuenta']);
						if(count($ctas)>2){ $cta=$ctas[0]."-".$ctas[1]; } else { $cta=$ctas[0]; }
							}
				$cont="=\"".$query->fields['idcliente']."\"\t";
				$cont.=$query->fields['cliente']."\t";				
				$cont.="=\"".$cta."\"\t";
				$cont.=$query->fields['monedas']."\t";
				$cont.=$query->fields['proveedor']."\t";
				$cont.=$query->fields['cartera']."\t";
				$cont.=$query->fields['producto']."\t";
				$cont.=$query->fields['actividad']."\t";
				$cont.=$query->fields['fecges']."\t";
				$cont.=$query->fields['horges']." \t";
				$cont.=$query->fields['validaciones']."\t";
				$cont.="=\"".$query->fields['telefono']."\"\t";
				$cont.=$query->fields['email']."\t";
				$cont.=$query->fields['direccion']."\t";
				$cont.=$query->fields['origen']."\t";
				$cont.=$query->fields['fuente']."\t";
				$cont.=$query->fields['tipocontactabilidad']."\t";
				$cont.=$query->fields['contactabilidad']."\t";
				$cont.=$query->fields['grupogestion']."\t";
				$cont.=$query->fields['resultado']."\t";
				$cont.=$query->fields['justificacion']."\t";
				$cont.=$impcomp."\t";
				$cont.=$feccomp."\t";
				$cont.=$query->fields['observacion']."\t";
				$cont.=$r_hora."\t";
				$cont.=$query->fields['usuario']."\t";
				$cont.=$query->fields['peso']."\t";
				;
				fwrite($fp , $cont);
				fwrite($fp , chr(13).chr(10));
				$query->MoveNext();
								}
			fclose($fp);
			echo "Foto Cartera:<a href='guardar_como.php?name=f_gestion_$cart.txt' target='blank'>Click para descargar</a><br/>";	
				
	//mysql_free_result($query->_queryID);
	$db->Execute("update flag_reportes set flag='0' where reporte='gestiones'");
	$db->Close();
	
	
?>
