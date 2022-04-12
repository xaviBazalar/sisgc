<?php
//return false;


ini_set('memory_limit', '-1');
set_time_limit(1800);
 
include '../scripts/conexion.php';

echo "<pre>";

//$db->debug=true;


//$peri=$_GET['peri'];
$peri=9;
$mes_p=$db->Execute("SELECT fecini,fecfin FROM periodos WHERE idperiodo='9'");
$mes_ini=$mes_p->fields['fecini'];
$mes_fin=$mes_p->fields['fecfin'];
$cart=$_GET['cart'];
$sql="SELECT id, phone, cliente, dni, disposition, agent, lastupdated, cb_datetime,
			CASE 
				WHEN disposition  BETWEEN -5 AND -2 THEN '12' 
				WHEN disposition BETWEEN -7 AND -6 THEN '13' 
			
			END AS idresultado,
			CASE 
				WHEN disposition='-2' THEN '122' 
				WHEN disposition='-3' THEN '122'
				WHEN disposition='-5' THEN '122'
				WHEN disposition='-4' THEN '121'
				WHEN disposition='-6' THEN '123'
				WHEN disposition='-7' THEN '127'
			END AS idjustificacion,
			CASE 
				WHEN disposition='-4'  THEN '13' 
				WHEN disposition='-7'  THEN '14' ELSE '12' 
			END AS idcontactabilidad,
			CASE 
				WHEN disposition  BETWEEN -7 AND -2 THEN ''
			END AS obs_gs
				
		FROM `RPY_FE_TAR` 
		WHERE  (disposition BETWEEN -7 AND -2) AND 
			  (SUBSTRING(lastupdated,1,10) BETWEEN '$mes_ini' AND '$mes_fin') AND 
			  (lastupdated != cb_datetime)	
		";

		
	/*echo $sql;
	return false;*/
	//$db->debug=true;
	$rp=$db3->Execute($sql);
	/*PRIMARY KEY  (`idcliente`),*/
	$db->Execute("CREATE TEMPORARY TABLE tmpr_sis
					(
							idcliente VARCHAR(11) NOT NULL ,
							telefono VARCHAR(11), 
							lastupdated TIMESTAMP, 
							idresultado INT(11),
							idjustificacion INT(11),
							idcontactabilidad INT(11),
							INDEX `idcliente` (`idcliente`),
							INDEX `telefono` (`telefono`)
							

						  )ENGINE = MEMORY ;");
							  
						  
	while(!$rp->EOF){
		$dni=$rp->fields['dni'];
		$idres=$rp->fields['idresultado'];
		$idjust=$rp->fields['idjustificacion'];
		$idcont=$rp->fields['idcontactabilidad'];
		$fecreg=$rp->fields['lastupdated'];
		$fono=$rp->fields['phone'];
		$db->Execute("INSERT into tmpr_sis (`idcliente`,`telefono`,`lastupdated`,`idresultado`,`idjustificacion`,`idcontactabilidad`)
							VALUES ('$dni','$fono','$fecreg','$idres','$idjust','$idcont')");
		
		$rp->MoveNext();
	}			
	
	
	$qo="SELECT ts.*,cp.idcuenta,t.idtelefono FROM tmpr_sis ts
			left JOIN cuentas ct ON ts.idcliente=ct.idcliente
			left JOIN cuenta_periodos cp ON ct.idcuenta=cp.idcuenta AND cp.idperiodo='9'
			left JOIN telefonos t ON ts.telefono=t.telefono
			GROUP BY cp.idcuenta";
	$q=$db->Execute($qo);
	
	$x=0;
	
	while(!$q->EOF){
		$fon=$q->fields['idtelefono'];
		$idcta=$q->fields['idcuenta'];	
		$fecreg=$q->fields['lastupdated'];
		$t_h=explode(" ",$fecreg);
			$fec=$t_h[0];
			$hor=$t_h[1];
		$idres=$q->fields['idresultado'];
		$idjust=$q->fields['idjustificacion'];
		$idcont=$q->fields['idcontactabilidad'];
		//$idg=$db->Execute("Select idgestion from gestiones where idtelefono='$fon' and fecreg='$fecreg' and usureg is null");
		/*if($idg->fields['idgestion']==""){
			$inst="INSERT INTO gestiones (idcuenta,idcontactabilidad,idresultado,idjustificacion,observacion,fecges,horges,idactividad,idtelefono,fecreg)
							VALUES('$idcta','$idcont','$idres','$idjust','','$fec','$hor','1','$fon','$fecreg')";
			$db->Execute($inst);		
		}else{
			++$x;
		}*/
		if($q->fields['idcuenta']=="" or $q->fields['idcliente']=="" or $q->fields['idtelefono']==""){
			echo $q->fields['idcuenta']."*".$q->fields['idtelefono']."+".$q->fields['idcliente']." / ".$q->fields['telefono']." / ".$q->fields['lastupdated']."</br>";
		}
		$q->MoveNext();
	}
	
	echo "</br>".$x;


mysql_free_result($rp->_queryID);	


$db3->Close();



?>
