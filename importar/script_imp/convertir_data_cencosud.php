<?php
	ini_set('memory_limit', '-1');
	set_time_limit(1800);
	include 'ConnecADO.php';
	echo "<pre style='font-size=8px;'>";
	$n=0;
	$flag=0;//session_start();
	$slp=$db->Execute("Select idperiodo from periodos where fecini like '".date("Y-m")."%'");
	$id_periodo=$slp->fields['idperiodo'];
	function limpiar_espacios($x){
			$nombre=explode(" ",$x);
			$nombre_r="";
			for($i=0;$i<=count($nombre);$i++){
				
				if($nombre[$i]!=""){
					$nombre_r.=$nombre[$i]." ";
				}
				
			}
			
			return $nombre_r;
	}
	
	function fecha($x){
	/*Formato mmddyyyy a yyyy-mm-dd*/
			$ano=substr($x,4,4);
			$mes=substr($x,0,2);
			$dia=substr($x,2,2);
			
			$fecha=(string)$ano."-".$mes."-".$dia;
			
			return $fecha;
	}
	$dia=(date("d"))-1;
	if($dia<10){
	$dia=(string)"0".$dia;
	}
	$df=$db->Execute("SELECT DATE(DATE_ADD(NOW(),INTERVAL -1 DAY)) fecha");
	$fecha_sql=str_replace("-","",$df->fields['fecha']);
	$fp = fopen("/var/www/html/sisgc/rem_ciber_original/rem_AE7".$fecha_sql.".txt", 'r') or die("Error");

	$db->debug=true;

	//$db->Execute("UPDATE cuentas c, cuenta_periodos cp SET cp.idestado=0 WHERE c.idcuenta=cp.idcuenta AND c.idcartera=10 AND cp.idusuario=4");// Ae8
	//$db->Execute("UPDATE cuentas c, cuenta_periodos cp SET cp.idestado=0 WHERE c.idcuenta=cp.idcuenta AND c.idcartera=11 AND cp.idusuario=3");// Ae3

	$cli= array();
	$ctas= array();
	$ctas_p= array();
	$ls="";
	while (!feof($fp))
	{
		$n++;
		
			$linea=fgets($fp);
			//$linea=str_replace(" ","_",$linea);
			//echo strlen($linea)."<br>";
			$ctas[$n]['EMPRESA']=substr($linea,0,8);
			$ctas[$n]['NUM_CTA']=substr($linea,8,25);
			$ctas[$n]['GRUPO_CIERRE']=trim(substr($linea,33,2));
			$ctas[$n]['COD_DOC']=trim(substr($linea,35,25));
			if(trim(substr($linea,8,25))==""){continue;}
			$sql="SELECT idcliente cl FROM clientes WHERE idcliente='".trim(substr($linea,35,25))."'";
			$cli['sql'][$n]=$sql;
				
			$sql_ic="INSERT into clientes (idcliente,iddoi,idpersoneria,cliente) values ('".trim(substr($linea,35,25))."',1,1,'".trim(substr($linea,149,80))."')";
			$cli['ins'][$n]=$sql_ic;
			
			$sql_uc="UPDATE clientes  set cliente='".trim(substr($linea,149,80))."' where idcliente='".trim(substr($linea,35,25))."'";
			$cli['up'][$n]=$sql_uc;
			
			$ctas[$n]['FEC_NAC']=substr($linea,60,8);
			$ctas[$n]['SEXO']=substr($linea,68,1);
			$ctas[$n]['RIESGO']=substr($linea,69,3);
			$ctas[$n]['FECHA_ACTV']=fecha(substr($linea,72,8));
			$ctas[$n]['DIAS_MORA']=trim(substr($linea,80,5));
			$ctas[$n]['SALDO_FACTURADO_ACTUAL']=(trim(substr($linea,85,9)))/100;
			$ctas[$n]['SALDO_TOTAL']=trim(substr($linea,94,15));
			$ctas[$n]['LINEA_TC']=trim(substr($linea,109,15));
			$ctas[$n]['COD_SUC']=trim(substr($linea,124,5));
			$ctas[$n]['FLG_RF']=substr($linea,129,1);
			$ctas[$n]['FLG_CASTIGO']=substr($linea,130,1);
			$ctas[$n]['PMIN1 ']=(trim(substr($linea,131,9)))/100;
			$ctas[$n]['SAL_FACT1']=(trim(substr($linea,140,9)))/100;
			
			$sql_ct="SELECT  idcuenta FROM cuentas WHERE idcuenta='".trim(substr($linea,8,25))."-1'";
			$ctas['sql'][$n]=$sql_ct;
			
			$sql_ict="INSERT into cuentas (idmoneda,idproducto,idusuario,idcuenta,idcliente,idcartera,idtipocartera) values (1,1,4,'".trim(substr($linea,8,25))."-1','".trim(substr($linea,35,25))."',10,1)";// Ae8	
			//$sql_ict="INSERT into cuentas (idmoneda,idproducto,idusuario,idcuenta,idcliente,idcartera,idtipocartera) values (1,1,3,'".trim(substr($linea,8,25))."-1','".trim(substr($linea,35,25))."',11,1)"; //ae3

			$ctas['ins'][$n]=$sql_ict;
			
			$sql_uct="UPDATE cuentas  set idcliente='".trim(substr($linea,35,25))."' where idcuenta='".trim(substr($linea,8,25))."-1'";
			$ctas['up'][$n]=$sql_uct;
			
			$sql_ctp="SELECT  idcuenta FROM cuenta_periodos WHERE idcuenta='".trim(substr($linea,8,25))."-1' and idperiodo=$id_periodo";
			$ctas_p['sql'][$n]=$sql_ctp;

			$sql_ictp="INSERT into cuenta_periodos (idusuario,idperiodo,idcuenta,diasmora,impmin,imptot,impcap) values (4,$id_periodo,'".trim(substr($linea,8,25))."-1',".trim(substr($linea,80,5)).",".((trim(substr($linea,131,9)))/100).",".((trim(substr($linea,140,9)))/100).",".trim(substr($linea,94,15)).")"; //Ae8
			//$sql_ictp="INSERT into cuenta_periodos (idusuario,idperiodo,idcuenta,diasmora,impmin,imptot,impcap) values (3,$id_periodo,'".trim(substr($linea,8,25))."-1',".trim(substr($linea,80,5)).",".((trim(substr($linea,131,9)))/100).",".((trim(substr($linea,140,9)))/100).",".trim(substr($linea,94,15)).")"; //Ae3

			$ctas_p['ins'][$n]=$sql_ictp;

			$sql_uctp="UPDATE cuenta_periodos  set idusuario=4,idestado=1,diasmora=".trim(substr($linea,80,5)).",impmin=".((trim(substr($linea,131,9)))/100).",imptot=".((trim(substr($linea,140,9)))/100).",impcap=".trim(substr($linea,94,15))." where idcuenta='".trim(substr($linea,8,25))."-1'"; // Ae8	
			//$sql_uctp="UPDATE cuenta_periodos  set idusuario=3,idestado=1,diasmora=".trim(substr($linea,80,5)).",impmin=".((trim(substr($linea,131,9)))/100).",imptot=".((trim(substr($linea,140,9)))/100).",impcap=".trim(substr($linea,94,15))." where idcuenta='".trim(substr($linea,8,25))."-1'"; // Ae3	

			$ctas_p['up'][$n]=$sql_uctp;

			
			

			
			$ctas[$n]['NOM_COMPLETO']=limpiar_espacios(substr($linea,149,80));
			
			$ctas[$n]['DIR_DOMICILIO']=limpiar_espacios(substr($linea,229,80));
			$ctas[$n]['DIR_DOMICILIO2']=limpiar_espacios(substr($linea,309,80));
			$ctas[$n]['DISTRITO_DOMICILIO']=limpiar_espacios(substr($linea,389,80));
			$ctas[$n]['PROVINCIA_DOMICILIO']=limpiar_espacios(substr($linea,469,80));
			$ctas[$n]['DEPARTAMENTO_DOMICILIO']=limpiar_espacios(substr($linea,549,40));
			$ctas[$n]['EMPRESA2']=limpiar_espacios(substr($linea,589,80));
			$ctas[$n]['DIR_LABORAL']=limpiar_espacios(substr($linea,659,80));
			$ctas[$n]['DIR_LABORAL2']=limpiar_espacios(substr($linea,749,80));
			$ctas[$n]['DISTRITO_LABORAL']=limpiar_espacios(substr($linea,829,80));
			$ctas[$n]['PROVINCIA_LABORAL']=limpiar_espacios(substr($linea,909,80));
			$ctas[$n]['DEPARTAMENTO_LABORAL']=limpiar_espacios(substr($linea,989,40));
			
			$ctas[$n]['NRO_CALLE_PARTICULAR']=limpiar_espacios(substr($linea,1029,5));
			$ctas[$n]['NRO_CALLE_LABORAL']=limpiar_espacios(substr($linea,1034,5));
			
			$ctas[$n]['PROVINCIA_DOMICILIO']=limpiar_espacios(substr($linea,1039,40));
			$ubigeo=limpiar_espacios(str_replace(" ","",substr($linea,1079,8)));
			if(strlen($ubigeo)<7){$ubigeo="0".$ubigeo;}
			$ctas[$n]['CODIGO_POSTAL_DOMICILIO']=$ubigeo;
			$ctas[$n]['PROVINCIA_LABORA']=limpiar_espacios(substr($linea,1087,40));
			$ctas[$n]['CODIGO_POSTAL_LABORAL']=limpiar_espacios(substr($linea,1127,8));
			$ctas[$n]['REF_DIR_DOMICILIO_2']=limpiar_espacios(substr($linea,1135,20));
			$ctas[$n]['REF_DIR_LABORAL_2']=limpiar_espacios(substr($linea,1155,20));
			$ctas[$n]['CDDD']=substr($linea,1175,2);
			$ctas[$n]['TELEFONO_DOMICILIO']=limpiar_espacios(substr($linea,1179,13)); // parametro real substr($linea,1177,13)
			$ctas[$n]['CDDL']=substr($linea,1190,2);
			$ctas[$n]['TELEFONO_LABORAL']=limpiar_espacios(substr($linea,1194,13));   // parametro real substr($linea,1192,13)
			$ctas[$n]['CDDC']=substr($linea,1205,2);
			$ctas[$n]['TELEFONO_CELULAR']=limpiar_espacios(substr($linea,1209,13));   // parametro real substr($linea,1207,13)
			
			
			$ctas[$n]['TIPO_TELEFONO1']=substr($linea,1220,3);
			$ctas[$n]['CODIGO_DISCADO_DIRECTO1']=substr($linea,1223,5);
			$ctas[$n]['TEL1']=substr($linea,1228,13);
			$ctas[$n]['TIPO_TELEFONO2']=substr($linea,1241,3);
			$ctas[$n]['CODIGO_DISCADO_DIRECTO2']=substr($linea,1244,5);
			$ctas[$n]['TEL2']=substr($linea,1249,13);
			$ctas[$n]['TIPO_TELEFONO3']=substr($linea,1262,3);
			$ctas[$n]['CODIGO_DISCADO_DIRECTO3']=substr($linea,1265,5);
			$ctas[$n]['TEL3']=substr($linea,1270,13);
		
	
	}
	//var_dump($cli['up']);return false;
	/*$db->StartTrans();

	for($i=1;$i<=count($cli['sql']);++$i){
		$flag=$db->Execute($cli['sql'][$i]);
		//echo $flag->fields['cl']."<br>";
		if($flag->fields['cl']!=""){
			$ok=$db->Execute($cli['up'][$i]);
		}else{
			$ok=$db->Execute($cli['ins'][$i]);
			
		}
		
	}
	$db->CompleteTrans(true);
	
	$db->StartTrans();
	for($i=1;$i<=count($ctas['sql']);++$i){
		$flag=$db->Execute($ctas['sql'][$i]);
		//echo $flag->fields['cl']."<br>";
		if($flag->fields['idcuenta']!=""){
			$ok=$db->Execute($ctas['up'][$i]);
		}else{
			$ok=$db->Execute($ctas['ins'][$i]);
			
		}
		
	}
	$db->CompleteTrans(true);
	
	$db->StartTrans();
	for($i=1;$i<=count($ctas_p['sql']);++$i){
		$flag=$db->Execute($ctas_p['sql'][$i]);
		
		if($flag->fields['idcuenta']!=""){
			$ok=$db->Execute($ctas_p['up'][$i]);
		}else{
			$ok=$db->Execute($ctas_p['ins'][$i]);
			
		}
		
	}
	$db->CompleteTrans(true);
	*/
	fclose($fp);
	

	$fp2=fopen("/var/www/html/sisgc/rem_ciber_cencosud/Ae7_CENCO_".$fecha_sql.".txt", 'w') or die("Error");
	
	$titulo="EMPRESA	NUM_CTA	GRUPO_CIERRE	COD_DOC	FEC_NAC	SEXO	RIESGO	FECHA_ACTV	DIAS_MORA	SALDO_FACTURADO_ACTUAL	SALDO_TOTAL	LINEA_TC	COD_SUC	FLG_RF";
	$titulo.="	FLG_CASTIGO	PMIN1	SAL_FACT1	NOM_COMPLETO	DIR_DOMICILIO	DIR_DOMICILIO2	DISTRITO_DOMICILIO	PROVINCIA_DOMICILIO	DEPARTAMENTO_DOMICILIO	EMPRESA";
	$titulo.="	DIR_EMPRESA	DIR_EMPRESA	DISTRITO_EMPRESA	PROVINCIA_EMPRESA	DEPARTAMENTO_EMPRESA	NRO_CALLE_PARTICULAR	NRO_CALLE_LABORAL	PROVINCIA_DOMICILIO\tCODIGO_POSTAL_DOMICILIO\tPROVINCIA_LABORA\tCODIGO_POSTAL_LABORAL\tREF_DIR_DOMICILIO_2\tREF_DIR_LABORAL_2\tCDDD\tTELEFONO_DOMICILIO\tCDDL\tTELEFONO_LABORAL\tCDDC\tTELEFONO_CELULAR\tTIPO_TELEFONO1	CODIGO_DISCADO_DIRECTO1	TEL1	TIPO_TELEFONO2	CODIGO_DISCADO_DIRECTO2";
	$titulo.="	TEL2	TIPO_TELEFONO3	CODIGO_DISCADO_DIRECTO3	TEL3";
	
	fwrite($fp2, $titulo);
	fwrite($fp2 , chr(13).chr(10));
	
	for ($i=1;$i<count($ctas);$i++){
			if($ctas[$i]['EMPRESA']==""){
				continue;
			}
		
			$linea=$ctas[$i]['EMPRESA']."\t";
			$linea.=$ctas[$i]['NUM_CTA']."\t";
			$linea.=$ctas[$i]['GRUPO_CIERRE']."\t";
			$linea.=$ctas[$i]['COD_DOC']."\t";
			$linea.=$ctas[$i]['FEC_NAC']."\t";
			$linea.=$ctas[$i]['SEXO']."\t";
			$linea.=$ctas[$i]['RIESGO']."\t";
			$linea.=$ctas[$i]['FECHA_ACTV']."\t";
			$linea.=$ctas[$i]['DIAS_MORA']."\t";
			$linea.=$ctas[$i]['SALDO_FACTURADO_ACTUAL']."\t";
			$linea.=$ctas[$i]['SALDO_TOTAL']."\t";
			$linea.=$ctas[$i]['LINEA_TC']."\t";
			$linea.=$ctas[$i]['COD_SUC']."\t";
			$linea.=$ctas[$i]['FLG_RF']."\t";
			$linea.=$ctas[$i]['FLG_CASTIGO']."\t";
			$linea.=$ctas[$i]['PMIN1 ']."\t";
			$linea.=$ctas[$i]['SAL_FACT1']."\t";
			$linea.=$ctas[$i]['NOM_COMPLETO']."\t";
			
			$linea.=$ctas[$i]['DIR_DOMICILIO']."\t";
			$linea.=$ctas[$i]['DIR_DOMICILIO2']."\t";
			$linea.=$ctas[$i]['DISTRITO_DOMICILIO']."\t";
			$linea.=$ctas[$i]['PROVINCIA_DOMICILIO']."\t";
			$linea.=$ctas[$i]['DEPARTAMENTO_DOMICILIO']."\t";
			$linea.=$ctas[$i]['EMPRESA2']."\t";
			$linea.=$ctas[$i]['DIR_EMPRESA']."\t";
			$linea.=$ctas[$i]['DIR_EMPRESA2']."\t";
			$linea.=$ctas[$i]['DISTRITO_EMPRESA']."\t";
			$linea.=$ctas[$i]['PROVINCIA_EMPRESA']."\t";
			$linea.=$ctas[$i]['DEPARTAMENTO_EMPRESA']."\t";
			
			$linea.=$ctas[$i]['NRO_CALLE_PARTICULAR']."\t";
			$linea.=$ctas[$i]['NRO_CALLE_LABORAL']."\t";
			
			
			$linea.=$ctas[$i]['PROVINCIA_DOMICILIO']."\t";
			$linea.=str_replace(" ","",$ctas[$i]['CODIGO_POSTAL_DOMICILIO'])."\t";
			$linea.=$ctas[$i]['PROVINCIA_LABORA']."\t";
			$linea.=substr($ctas[$i]['CODIGO_POSTAL_LABORAL'],0,5)."\t";
			$linea.=$ctas[$i]['REF_DIR_DOMICILIO_2']."\t";
			$linea.=$ctas[$i]['REF_DIR_LABORAL_2']."\t";
			$linea.=$ctas[$i]['CDDD']."\t";
			$linea.=$ctas[$i]['TELEFONO_DOMICILIO']."\t";
			$linea.=$ctas[$i]['CDDL']."\t";
			$linea.=$ctas[$i]['TELEFONO_LABORAL']."\t";
			$linea.=$ctas[$i]['CDDC']."\t";
			$linea.=$ctas[$i]['TELEFONO_CELULAR']."\t";
			
			$linea.=$ctas[$i]['TIPO_TELEFONO1']."\t";
			$linea.=$ctas[$i]['CODIGO_DISCADO_DIRECTO1']."\t";
			$linea.=$ctas[$i]['TEL1']."\t";
			$linea.=$ctas[$i]['TIPO_TELEFONO2']."\t";
			$linea.=$ctas[$i]['CODIGO_DISCADO_DIRECTO2']."\t";
			$linea.=$ctas[$i]['TEL2']."\t";
			$linea.=$ctas[$i]['TIPO_TELEFONO3']."\t";
			$linea.=$ctas[$i]['CODIGO_DISCADO_DIRECTO3']."\t";
			$linea.=$ctas[$i]['TEL3']."\t";

			//echo $linea;
			fwrite($fp2, $linea);
			fwrite($fp2 , chr(13).chr(10));

	}
	fclose($fp2);
	return false;
	


?>
