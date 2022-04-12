2<?
	//funcion para restar fechas
	function restaFechas($dFecIni, $dFecFin)
{
     $dFecIni = str_replace("-","",$dFecIni);
    $dFecIni = str_replace("/","",$dFecIni);
    $dFecFin = str_replace("-","",$dFecFin);
    $dFecFin = str_replace("/","",$dFecFin);
 
    ereg( "([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})", $dFecIni, $aFecIni);
    ereg( "([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})", $dFecFin, $aFecFin);
 
    $date1 = mktime(0,0,0,$aFecIni[2], $aFecIni[1], $aFecIni[3]);
    $date2 = mktime(0,0,0,$aFecFin[2], $aFecFin[1], $aFecFin[3]);
 

    return round(($date2 - $date1) / (60 * 60 * 24));
}
	//fin de la funcion

	include '../define_con.php';
	
	$pe=$_GET['pe'];
	$ca=$_GET['ca'];
	
	$cm="SELECT c.idcuenta,cl.idcliente,cl.cliente,cpr.fecven,cp.fecpag,cp.imptot t_pago,cpr.impven, cpr.impcap,cp.observacion,date(cp.fecreg) fecreg,
	case 
		when cp.idestado=1 then 'ACTIVO'
		when cp.idestado=0 then 'INACTIVO'
	end  estado
	FROM cuenta_pagos cp
	JOIN cuentas c ON c.idcuenta=cp.idcuenta 
	JOIN cuenta_periodos cpr ON cpr.idcuenta=c.idcuenta 
	JOIN clientes cl ON cl.idcliente=c.idcliente 
	WHERE cp.idperiodo = $pe ";
	if($ca!=""){
	$cm.="AND c.idcartera = $ca GROUP BY cp.idcuenta,cp.fecpag ";
	}

 
	if($ca==21 or ($ca>=38 and $ca<=41)){
		$titulo="RUC\tCuenta\tFecha de pago\tImporte\tF. Sistema\tF. Vencimiento\tBanco\tNro. Op\tDias de Pago\tEstado";
	}else{
		$titulo="idcuenta\tidcliente\tcliente\tfecven\tfecpag\tdiasmora\tt_pago\timpven\timpcap\tEstado";
	}
	
	$fp=fopen("cuentas_pago.txt",'w');
	chmod("cuentas_pago.txt", 0777);
	fwrite($fp , $titulo);
	fwrite($fp , chr(13).chr(10));
	
	//echo $cm;
	$query=$db->Execute($cm) or die (mysql_error());
	$i=0;

	while(!$query->EOF)
	{
	$obs=explode('*',$query->fields['observacion']);
	$fi1=explode("-",$obs[0]);
	$ff2=explode("-",$query->fields['fecpag']);
	$fi=$fi1[2]."-".$fi1[1]."-".$fi1[0];
	$ff=$ff2[2]."-".$ff2[1]."-".$ff2[0];
	$dmora=restaFechas($fi,$ff);
	
	if($ca==21 or ($ca>=38 and $ca<=41)){
		$cont="\"".$query->fields['idcliente']."\"\t";
		$cont.="\"".$query->fields['idcuenta']."\"\t";
		$cont.=$query->fields['fecpag']."\t";
		$cont.=$query->fields['t_pago']."\t";
		$cont.=$query->fields['fecreg']."\t";
		
		$cont.=$obs[0]."\t";
		$cont.=$obs[1]."\t";
		$cont.=$obs[2]."\t";
		$cont.=$dmora;
		//$cont.=$query->fields['fecven']."\t";
		
		
		/*
		$cont.=$dmora."\t";
		$cont.=$query->fields['t_pago']."\t";
		$cont.=$query->fields['impven']."\t";
		$cont.=$query->fields['impcap'];*/
	}else{
		$cont=$query->fields['idcuenta']."\t";
		$cont.=$query->fields['idcliente']."\t";
		$cont.=$query->fields['cliente']."\t";
		$cont.=$query->fields['fecven']."\t";
		$cont.=$query->fields['fecpag']."\t";
		$cont.=$dmora."\t";
		$cont.=$query->fields['t_pago']."\t";
		$cont.=$query->fields['impven']."\t";
		$cont.=$query->fields['impcap']."\t";
		$cont.=$query->fields['estado']."\t";
	}
	fwrite($fp , $cont);
	fwrite($fp , chr(13).chr(10));
	$query->MoveNext();
	}
	fclose($fp);
	echo "cuentas_pago:<a href='guardar_como.php?name=cuentas_pago.txt' target='blank'>Click para descargar</a><br/>";	
				
	//mysql_free_result($query->_queryID);
	$db->Close();
?>