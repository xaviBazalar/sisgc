<?php
//return false;


ini_set('memory_limit', '-1');
set_time_limit(1800);
 
include '../scripts/conexion.php';

echo "<pre>";

//$db->debug=true;

$hora=date("H:i:s");
$archivo='det_ripley'.$hora.'.txt';
$fp = fopen($archivo, 'w');

$peri=$_GET['peri'];
$mes_p=$db->Execute("SELECT fecini,MONTH(fecini) mes,YEAR(fecini) ano FROM periodos WHERE idperiodo='$peri'");
$mes_a=$mes_p->fields['mes'];
$año_a=$mes_p->fields['ano'];
$cart=$_GET['cart'];
$sql="SELECT ct.idcuenta,cp.observacion2,c.idcliente,DATE(g.fecreg) fecha,DATE_FORMAT(g.fecreg,'%r') hora,
			CASE 
				WHEN g.idactividad=3 THEN 'DEMOGRAPHIC CHANGE' 
				WHEN g.idactividad=1 THEN 'MAKE CALL'
				WHEN g.idactividad=2 THEN 'RECEIVE CALL' 
				WHEN g.idactividad=6 THEN 'RECEIVE VISIT' 
				WHEN g.idactividad=4 THEN 'REPORT VISIT'
				WHEN g.idactividad=9 THEN 'SOLICIT VISIT'  
				WHEN g.idactividad=7 THEN 'SEND LETTER' 
			END AS accion,
			CASE
				WHEN g.idactividad=2 AND (g.idresultado=12 OR g.idresultado=13) THEN ''
				WHEN g.idactividad=3 THEN ''
				WHEN g.idresultado=15 THEN '' 
				WHEN g.idresultado=7 THEN 'PAYMENT DIFFICULTY'
				WHEN g.idresultado=10  OR g.idresultado=28 THEN 'DEAD' 
				WHEN g.idresultado=13 THEN 'NO UBICABLE' 
				WHEN g.idactividad=2 AND g.idresultado=11 THEN 'PAYMENT DIFFICULTY'
				WHEN g.idactividad=2 AND g.idresultado=6 THEN 'PAYMENT DIFFICULTY'
				WHEN g.idresultado=11 THEN 'MESSAGE'
				WHEN g.idactividad=6  AND g.idresultado=6 THEN 'PAYMENT DIFFICULTY' 
				WHEN g.idresultado=12 AND g.idjustificacion='112' THEN 'NO ANSWER'
				WHEN g.idresultado=12 AND g.idjustificacion='119' THEN 'HANGUP'  
				WHEN g.idresultado=12 AND g.idjustificacion='120' THEN 'MESSAGE'  
				WHEN g.idresultado=12 AND g.idjustificacion='121' THEN 'BUSY'
				WHEN g.idresultado=12 AND g.idjustificacion='122' THEN 'NO ANSWER'
				WHEN g.idresultado=12 AND g.idjustificacion='175' THEN ''     
				WHEN g.idresultado=2 OR g.idresultado=39 THEN 'PROMISE' 
				WHEN g.idresultado=9 THEN 'DISCORD' 
				WHEN g.idresultado=17 OR g.idresultado=27 THEN 'RELUCTANT' 
				WHEN g.idresultado=6 AND g.idjustificacion='82' THEN 'MESSAGE'
				WHEN g.idresultado=6 AND g.idjustificacion='83' THEN 'MESSAGE'  
				WHEN g.idresultado=6 AND g.idjustificacion='84' THEN 'MESSAGE'  
				WHEN g.idresultado=6 AND g.idjustificacion='86' THEN 'MESSAGE'  
				WHEN g.idresultado=6 AND g.idjustificacion='80' THEN 'MESSAGE'
				WHEN g.idresultado=6 AND g.idjustificacion='89' THEN 'MESSAGE'
				WHEN g.idresultado=6 AND g.idjustificacion='85' THEN 'MESSAGE'
				WHEN g.idresultado=1 OR g.idresultado=38 THEN 'PAID'
				WHEN g.idresultado=40 THEN 'OTHER'
				WHEN g.idresultado=29 OR g.idresultado=30 OR g.idresultado=73 THEN 'MESSAGE'
				WHEN g.idresultado=31 THEN 'DELIVER LETTER'
				WHEN g.idresultado=34 OR g.idresultado=35 OR g.idresultado=36 OR g.idresultado=37 THEN 'NO UBICABLE'
				WHEN g.idresultado=64 THEN 'PAYMENT DIFFICULTY'
				WHEN g.idresultado=32 OR g.idresultado=33 THEN 'NO UBICABLE'	
				WHEN g.idresultado=43 THEN 'NO UBICABLE'
			END AS respuesta,
			CASE 
				WHEN g.idcontactabilidad=1 THEN 'DEBTOR' 
				WHEN g.idcontactabilidad=2 THEN 'DEBTOR' 
				WHEN g.idcontactabilidad=3 THEN 'CONYUGE' 
				WHEN g.idresultado=2 AND g.idcontactabilidad=7 THEN 'CONYUGE' 
				WHEN g.idcontactabilidad=6 THEN 'OTHER' 
				WHEN g.idcontactabilidad=7 THEN 'OTHER' 
				WHEN g.idcontactabilidad=8 THEN 'MACHINE' 
				WHEN g.idcontactabilidad=12 THEN '' 
				WHEN g.idcontactabilidad=13 THEN '' 
				WHEN g.idcontactabilidad=14 THEN '' 
				WHEN g.idcontactabilidad=15 THEN '' 
				WHEN g.idcontactabilidad=16 THEN 'PADRES' 
				WHEN g.idcontactabilidad=17 THEN '' 
			END AS contacto,
			CASE 
				WHEN g.idresultado=15 THEN 'ACTUALIZACION DE DATOS'  /*Busqueda Externa*/
				WHEN g.idresultado=7 AND g.idjustificacion=90 THEN 'DIFICULTAD DE PAGO'  /*Dificultad de Pago*/
				WHEN g.idresultado=7 AND g.idjustificacion=91 THEN 'INSOLVENTE'  /*Dificultad de Pago*/
				WHEN g.idresultado=7 AND g.idjustificacion=93 THEN 'INSOLVENTE'  /*Dificultad de Pago*/
				WHEN g.idresultado=7 AND g.idjustificacion=94 THEN 'SIN TRABAJO'  /*Dificultad de Pago*/
				WHEN g.idresultado=7 AND g.idjustificacion=95 THEN 'INSOLVENTE'  /*Dificultad de Pago*/
				WHEN g.idresultado=7 AND g.idjustificacion=96 THEN 'SOBREENDEUDADO'  /*Dificultad de Pago*/
				WHEN g.idresultado=7 AND g.idjustificacion=97 THEN 'ENFERMEDAD TIT/FAMILIAR'  /*Dificultad de Pago*/
				WHEN g.idresultado=10 THEN 'FALLECIO'  /*Fallecido / Invalidez*/
				WHEN g.idresultado=13 AND g.idjustificacion=124 THEN 'RADICA EN EL EXTRANJERO'  /*Ilocalizado*/
				WHEN g.idresultado=13 AND g.idjustificacion=125 THEN 'SE MUDO/NO TRABAJA'  /*Ilocalizado*/
				WHEN g.idresultado=13 AND g.idjustificacion=211 THEN 'SE MUDO/NO TRABAJA'  /*Ilocalizado*/
				WHEN g.idresultado=13 AND g.idjustificacion=97 THEN 'NO LO CONOCEN'  /*Ilocalizado*/
				WHEN g.idresultado=13 AND g.idjustificacion=127 THEN 'FUERA DE SERVICIO'  /*Ilocalizado*/
				WHEN g.idresultado=13 AND g.idjustificacion=128 THEN 'TEL. NO EXISTE'  /*Ilocalizado*/
				WHEN g.idresultado=13 AND g.idjustificacion=126 THEN 'NO LO CONOCEN'  /*Ilocalizado*/
				WHEN g.idresultado=13 AND g.idjustificacion=129 THEN 'SIN TELEFONO'  /*Ilocalizado*/
				WHEN g.idresultado=13 AND g.idjustificacion=123 THEN 'FAX'  /*Ilocalizado*/
				WHEN g.idresultado=11 AND g.idjustificacion=109 THEN 'TERCERO OFICINA'  /*Mensaje a Terceros*/
				WHEN g.idresultado=11 AND g.idjustificacion=110 THEN 'TERCERO CASA'  /*Mensaje a Terceros*/
				WHEN g.idresultado=11 AND g.idjustificacion=111 THEN 'TERCERO CASA'  /*Mensaje a Terceros*/
				WHEN g.idresultado=11 AND g.idjustificacion=114 THEN 'DE VIAJE'  /*Mensaje a Terceros*/
				WHEN g.idresultado=11 AND g.idjustificacion=115 THEN 'DE VIAJE'  /*Mensaje a Terceros*/
				WHEN g.idresultado=12 AND g.idjustificacion=112 THEN 'NO PUEDEN PASAR LA LLAMADA'  /*No Contacto*/
				WHEN g.idresultado=12 AND g.idjustificacion=119 THEN 'COLGO'  /*No Contacto*/
				WHEN g.idresultado=12 AND g.idjustificacion=120 THEN 'GRABADORA'  /*No Contacto*/
				WHEN g.idresultado=12 AND g.idjustificacion=121 THEN 'OCUPADO'  /*No Contacto*/
				WHEN g.idresultado=12 AND g.idjustificacion=122 THEN 'NO CONTESTA'  /*No Contacto*/
				WHEN g.idresultado=2 AND g.idjustificacion=78 THEN 'PROMESA DE PAGO'  /*Promesa de Pago*/
				WHEN g.idresultado=2 AND g.idjustificacion=76 THEN 'PROMESA DE PAGO'  /*Promesa de Pago*/
				WHEN g.idresultado=2 AND g.idjustificacion=77 THEN 'PROMESA DE PAGO PARCIAL'  /*Promesa de Pago*/
				WHEN g.idresultado=2 AND g.idjustificacion=79 THEN 'PROMESA DE PAGO'  /*Promesa de Pago*/
				WHEN g.idresultado=9 THEN 'TIENE RECLAMO'  /*Reclamo*/
				WHEN g.idresultado=17 AND g.idjustificacion=99 THEN 'NO RECONOCE DEUDA'  /*Renuente*/
				WHEN g.idresultado=17 AND g.idjustificacion=100 THEN 'RENUENTE'  /*Renuente*/
				WHEN g.idresultado=17 AND g.idjustificacion=98 THEN 'RENUENTE'  /*Renuente*/
				WHEN g.idresultado=17 AND g.idjustificacion=102 THEN 'RENUENTE'  /*Renuente*/
				WHEN g.idresultado=6 AND g.idjustificacion!=85 THEN 'SEGUIMIENTO'  /*Seguimiento*/
				WHEN g.idresultado=6 AND g.idjustificacion=85 THEN 'REALIZARA FINANCIAMIENTO'  /*Seguimiento*/
				WHEN g.idresultado=1 THEN 'YA PAGO'  /*Ya Pago*/
			END AS detalle,
			CASE 
				WHEN g.idresultado=15 OR g.idresultado=13 OR g.idresultado=12 OR g.idresultado=15 THEN 'No contacto' 
				WHEN g.idresultado=7 OR g.idresultado=2 OR g.idresultado=9 OR g.idresultado=17  OR g.idresultado=6 OR g.idresultado=1 THEN 'Contacto Directo' 
				WHEN g.idresultado=10 OR g.idresultado=11  THEN 'Contacto Indirecto' 
			END AS tip,	

			
			g.observacion g_observacion,
			pr.proveedor,
			prd.producto,
			cp.observacion,
			CASE 
				WHEN ct.idtipocartera=2  THEN 'Recovery' 
				WHEN ct.idtipocartera=1 OR ct.idtipocartera=5 OR ct.idtipocartera=6 OR ct.idtipocartera=7 THEN 'Tardia' 
			END AS carteral

		FROM clientes c
		JOIN cuentas ct ON c.idcliente=ct.idcliente
		JOIN carteras cr ON ct.idcartera=cr.idcartera
		JOIN proveedores pr ON cr.idproveedor=pr.idproveedor
		JOIN productos prd ON ct.idproducto=prd.idproducto
		JOIN cuenta_periodos cp ON ct.idcuenta=cp.idcuenta AND cp.idperiodo='$peri'
		JOIN gestiones g ON cp.idcuenta=g.idcuenta
		LEFT JOIN telefonos tel ON g.idtelefono=tel.idtelefono
		WHERE cr.idcartera='$cart' AND YEAR(g.fecges)='$año_a' AND MONTH(g.fecges)='$mes_a' and  g.idactividad!=3 
		";
		
		if(isset($_GET['fecini']) and isset($_GET['fecfin'])){
			$ini=$_GET['fecini'];
			$fin=$_GET['fecfin'];
			$sql.=" AND g.fecges BETWEEN '$ini' AND '$fin' ";
			
		}
	/*echo $sql;
	return false;*/

	$rp=$db->Execute($sql);
	//$titulo="Ident.usuario|Ident.cliente|F.Accion|Accion|Respuesta|Contacto|Observacion|F.Prox.G|Telefono|Indicativo|Tip|T.Act/Desact|Ind.T.Act/Desac|Tip.T.Act/Desac|Direccion|Ciudad direccion|Zona direccion|Tipo direccion|Sec.direccion|Id.destinatario|Codigo carta|Entidad|Producto|Obligacion|Ent.de obligac|Monto promesa|Fecha|S|Num.cheque|Ent.cheque|Def.usuario|Visitador|";
	$titulo="Campaña\tCartera\tidentificacion usuario\tidentificacion cliente\tfecha_accion\taccion\trespuesta\tcontacto\tobservacion\tvisitador\tdet\ttip\tfcarga";	
	$año=date("Y");
	
	fwrite($fp, $titulo);
	fwrite($fp , chr(13).chr(10));	
	while(!$rp->EOF){
		$det=$rp->fields['detalle'];
		$tip=$rp->fields['tip'];
		$cart=$rp->fields['carteral'];
		$ent_obli=explode("/",$rp->fields['observacion2']);
		if($ent_obli[1]=="FC"){ $ent_obli="FINANCIEROS";}
		if($ent_obli[1]=="TC"){ $ent_obli="TARJCOMPARTIDAS";}
		if($ent_obli[1]=="TP"){ $ent_obli="TARJPROPIAS";}
		if($ent_obli[1]=="BR"){ $ent_obli="BANCORIPLEY";}
		$datos=trim($rp->fields['observacion']);
		$arr = explode("-",$datos);
		$id=$arr[0];
		$hour=explode(" ",$rp->fields['hora']);
		if($hour[1]=="AM"){ $e="a.m.";}
		if($hour[1]=="PM"){ $e="p.m.";}
		$fechs=explode("-",$rp->fields['fecha']);
		$fechs=$fechs[2]."/".$fechs[1]."/".$fechs[0];
		$fecr=$fechs." ".$hour[0]." ".$e;
		$acc=$rp->fields['accion'];
		$rpta=$rp->fields['respuesta'];
		$cont=$rp->fields['contacto'];
		
		$obs=$rp->fields['g_observacion'];
		if(strlen($obs)>=200){
			$maxlength = 198;
			$obs = substr($obs, 0, $maxlength);
		}
		
		$f_pg=$rp->fields['fec_comp'];
		if($pos = strpos($f_pg, "-")){
			$f_pg=explode("-",$rp->fields['fec_comp']);
			$f_pg=$f_pg[2]."/".$f_pg[1]."/".$f_pg[0];
		}
		//$f_pg=$rp->fields['fec_comp'];
		$tel_g=$rp->fields['telefono'];
		$tel_g=trim($rp->fields['telefono']);
		
		if($pos = strpos($tel_g, "-")){$tel_g=explode("(",$tel_g);  $tel_g=$tel_g[0].$tel_g[1].$tel_g[2];	}
		if($pos = strpos($tel_g, "-")){$tel_g=explode(")",$tel_g);  $tel_g=$tel_g[0].$tel_g[1];	}
		if($pos = strpos($tel_g, "-")){$tel_g=explode("-",$tel_g);  $tel_g=$tel_g[0].$tel_g[1];	}
		if($pos = strpos($tel_g, ".")){$tel_g=explode(".",$tel_g);  $tel_g=$tel_g[0].$tel_g[1]; }
		if($pos = strpos($tel_g, ",")){$tel_g=explode(",",$tel_g);  $tel_g=$tel_g[0].$tel_g[1]; }
		if($pos = strpos($tel_g, "*")){$tel_g=explode("*",$tel_g);  $tel_g=$tel_g[0].$tel_g[1]; }
			$patron = "/^[[:digit:]]+$/";/*solo numeros*/
           // if (preg_match($patron, $tel_g)){}else{ continue;}
		$tel_t=$rp->fields['tipo_tel'];
		$entidad=$rp->fields['proveedor'];
		$producto=$rp->fields['producto'];
		$producto=trim($rp->fields['producto']);
		if(strlen($producto)>=20){
			$maxlength = 19;
			$producto = substr($producto, 0, $maxlength);
		}
		$obli=trim($arr[1]);
		$fec_p=$rp->fields['fec_comp'];
		$imp_p=$rp->fields['imp_comp'];
		
		if($id==""){
			$rp->MoveNext();
			continue;
		}
		if($acc=="RECEIVE CALL" and $rpta==""){
			$rp->MoveNext();
			continue;
		}
		
		if($acc=="MAKE CALL" and $rpta==""){
			$rp->MoveNext();
			continue;
		}
		/*if($det==""){
			$det=$rp->fields['idcuenta'];
		}*/
		$linea="UNICA-$año\t$cart-$año\tKOBSA\t$id\t$fecr\t$acc\t$rpta\t$cont\t$obs\tKOBSA\t$det\t$tip\t";
		//$linea="Kobsa|$id|$fecr|$acc|$rpta|$cont|$obs|$f_pg|$tel_g|1|$tel_t|||||||||||BANCORIPLEY|$producto|$obli|$ent_obli|||N||||KOBSA";
		fwrite($fp, $linea);
		fwrite($fp , chr(13).chr(10));
		$rp->MoveNext();
		
	}	


mysql_free_result($rp->_queryID);	


$db->Close();
echo "</br></br></br>";
echo "<font color='red'>Inicio de reporte :</font>".$ti=date("H:i:s")." - <font color='blue'>Fin de Reporte:</font>".$tf=date("H:i:s")." - ";
echo "Guardar:<a href='functions/guardar_como.php?name=$archivo' target='blank'>$archivo</a>";	


?>
