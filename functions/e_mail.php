<?php
session_start();
if(isset($_GET['ctas']) and $_GET['ctas']!=""){
	$ctas;
	$cta=explode(",",substr($_GET['ctas'],0, -1));
	for($i=0;$i<count($cta);$i++){
		$ctas.="'".$cta[$i]."',";
	}
	$cta_up="  in (".substr($ctas,0, -1).") ";
	$ctas=" and c.idcuenta in (".substr($ctas,0, -1).") ";
	
}else{ return false;}

if(isset($_GET['t']) and $_GET['t']!=""){
	$tv=$_GET['t'];
	if($tv==1){
		$qr1=" AND fecven<'".date("Y-m-d")."' "; // vencidos
	}else if($tv==2){
		$qr1=" and c.fecrev!='".date("Y-m-d")."' AND fecven=ADDDATE('".date("Y-m-d")."', +10) "; // 10 dias antes de la fecha de vencimiento	
	}else if($tv==3){
		$qr1=" and c.fecrev!='".date("Y-m-d")."' AND fecven='".date("Y-m-d")."' "; // vencen Hoy
	}
}else{ return false;}

$cart=$_GET['cartera'];
require '../define_con.php';	
ini_set('memory_limit', '-1');
set_time_limit(1800);

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


include_once 'Email/phpmailer/class.phpmailer.php';	
/*
$mail = new PHPMailer();
$mail->SetLanguage("es", "language/");
$mail->From = "kobranzas_sac@kobsa.com.pe";
$mail->FromName = "kobranzas_sac@kobsa.com.pe";
//$mail->AddAddress("noropeza@kobsa.com.pe","Noemi Oropeza");
//$mail->AddAddress("jbazalar@kobsa.com.pe","Noemi Oropeza");

$mail->AddCC("jlnapuri@kobsa.com.pe");
$mail->AddCC("jlorellana@kobsa.com.pe");
$mail->AddCC("ksotelo@kobsa.com.pe");
$mail->AddCC("kgebol@kobsa.com.pe");
$mail->AddCC("apacheco@kobsa.com.pe");
$mail->AddCC("rpena@kobsa.com.pe");
$mail->AddCC("cobranzas.rpp@gmail.com");
$mail->AddCC("cobranzas.rpp1@gmail.com");
$mail->AddCC("rpena@kobsa.com.pe");*/



// Establecer formato HTML

//$mail->Subject = "RPP";

/*for($i=1;$i<=30;$i++){
	if(!$mail->Send()) {
		exit('No se pudo enviar el correo. Error: ' .$mail->ErrorInfo);
	} else {
		echo "Se envió correctamente.<br/>";
	}	
}*/



/********************************************************************************************************/
$periodo=$_SESSION['periodo'];


$sql="SELECT cnt.contacto,
		CASE 
			WHEN cnt.area='C' THEN 'Contabilidad'
			WHEN cnt.area='G' THEN 'Gerencia'
			WHEN cnt.area='T' THEN 'Tesorer&iacute;a'
			WHEN cnt.area='R' THEN 'Recepci&oacute;n'
			WHEN cnt.area='PP' THEN 'Pago a Proveedores'
		END area_c
			,
		m.monedas,pr.proveedor,pr.documento,cl.idcliente,cliente,/*u.nombre dist,u2.nombre prov,*/d.direccion,p.producto,cnt.email,
		SUBSTRING(c.idcuenta,1,(LENGTH(c.idcuenta)-2)) 	idcuenta	
		,c.feccon,cp.fecven,cp.imptot,cp.diasmora,cp.idestado,cp.idcuenta cta
				FROM cuentas c
				JOIN clientes cl ON c.idcliente=cl.idcliente
				JOIN contactos cnt ON cl.idcliente=cnt.idcliente
				JOIN direcciones d ON cl.idcliente=d.idcliente
				/*JOIN ubigeos u ON d.coddpto=u.coddpto AND d.codprov=u.codprov AND d.coddist=u.coddist*/
				/*JOIN ubigeos u2 ON d.coddpto=u2.coddpto AND d.codprov=u2.codprov AND d.coddist=00*/
				JOIN carteras cr ON c.idcartera=cr.idcartera 
				JOIN proveedores pr ON cr.idproveedor=pr.idproveedor
				JOIN productos p ON c.idproducto=p.idproducto
				JOIN monedas m ON c.idmoneda=m.idmoneda
				JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta AND cp.idestado=1
		WHERE 
				c.idcartera=$cart AND cp.idperiodo=$periodo and cp.grupo!='OBS'  and cp.grupo!='RET'   and cp.grupo!='0'  and cp.impmin!=6
				
				$ctas $qr1
				GROUP BY c.idcliente";
				
		
//echo $sql;
//return false;
$x=0;
$xm=0;
$qry=$db->Execute($sql);
while(!$qry->EOF){
	$mail = new PHPMailer();
	$mail->SetLanguage("es", "language/");
	$mail->From = "kobranzas_sac@kobsa.com.pe";
	
	//-----------------------------------------------------------
/*
  $mail->Mailer = "smtp"; 	//Con la propiedad Mailer le indicamos que vamos a usar un servidor smtp
  $mail->Host = "mail.kobsa.com.pe";  //Asignamos a Host el nombre de nuestro servidor smtp
  $mail->SMTPAuth = true;  //Le indicamos que el servidor smtp requiere autenticación

  //Le decimos cual es nuestro nombre de usuario y password
  $mail->Username = "jbazalar@kobsa.com.pe"; 
  $mail->Password = "12345678";*/
	
	//-----------------------------------------------------------
	
	
	
	$mail->FromName = "kobranzas_sac@kobsa.com.pe";
	
	$mail->FromName="irojas@kobsa.com.pe";
	
	//$mail->FromName = "jbazalar@kobsa.com.pe";
	//$mail->AddAddress("fvasquez@kobsa.com.pe","Frank Vasquez");
	//$mail->AddCC("jbazalar@kobsa.com.pe","Xavi Bazalar");
	//$mail->AddAddress("javi46@hotmail.com","Javier Arturo Bazalar");
	//$mail->AddAddress("xavi18@gmail.com","Xavi Bazalar");

	//$mail->AddCC("jlnapuri@kobsa.com.pe");
	//$mail->AddCC("irojas@kobsa.com.pe");
	$mail->AddCC("lorellana@kobsa.com.pe");
	$mail->AddCC("lcastellano@kobsa.com.pe");
	$mail->AddCC("ksotelo@kobsa.com.pe");
	$mail->AddCC("kgebol@kobsa.com.pe");
	$mail->AddCC("apacheco@kobsa.com.pe");
	$mail->AddCC("ytello@kobsa.com.pe");
       $mail->AddCC("ctamanaha@kobsa.com.pe");
	$mail->AddCC("cobranzas.rpp@gmail.com");
	$mail->AddCC("cobranzas.rpp1@gmail.com");


	$mail->WordWrap = 200; // Máximo 40 caracteres de ancho
	$mail->IsHTML(true); // Establecer formato HTML


	$email=$qry->fields['email'];
	$cliente=$qry->fields['cliente'];
	$idcliente=$qry->fields['idcliente'];
	$direccion=$qry->fields['direccion'];
	$contacto=$qry->fields['contacto'];
	$area=$qry->fields['area_c'];
	$proveedor=$qry->fields['proveedor'];
	$doi_pro=$qry->fields['documento'];
	$ubigeo=strtoupper($qry->fields['prov'])." - ".strtoupper($qry->fields['dist']);
	
	if($cart==21){$cta_c="194-1782752-0-94";$cci="002-194-001782752094-95";$proveedor="GRUPORPP S.A";$doi_pro="20100016843";}
	if($cart==40){$cta_c="194-1782752-0-94";$cci="002-194-001782752094-95";$proveedor="GRUPORPP S.A.C";$doi_pro="20492353214";}
	if($cart==38){$cta_c="193-1901785-0-48";$cci="002-193-001901785048-18";$proveedor="RADIO EL SOL PROMOTORA SIGLO XX S.A.";$doi_pro="20101599893";}
	if($cart==39){$cta_c="194-1718566-0-51";$cci="002-194-001718566051-95";$proveedor="PRODUCCIONES ASTURIAS S.A.C";$doi_pro="20505448180";}	
	if($tv==1){	
		$doc_tit="el(los) siguiente(s) documento(s) vencido(s):";	
	}
	if($tv==2){
		$doc_tit="el(los) siguiente(s) documento(s) por vencer:";
	}
	if($tv==3){
		$doc_tit="el(los) siguiente(s) documento(s) vencido(s) al d&iacute;a de hoy:";
	}
$mail->FromName = "Cobranza de documentos GRUPORPP S.A. - $cliente";	
$mail->AddAddress($email);
$mail->Subject = "Cobranza de documentos GRUPORPP S.A. - $cliente";
$contenido="
	Se&ntilde;ores<br/>
	<strong>$cliente - $idcliente</strong><br/>
	
	$direccion<br/>
	$ubigeo
	<br/><br/>
	Atenci&oacute;n: $contacto - $area
	<br/><br/>
	Por encargo de $proveedor (RUC $doi_pro) le recordamos que mantiene(n) <br/>
	<strong>$doc_tit</strong><br/><br/>";
	
	
	$contenido.="<table  border='1'  width='640'>
	<tr bgcolor='#CCCCCC'>
		<th>Tipo Documento</th><th>Documento</th><th>Fecha Emisi&oacute;n</th><th>Fecha Vencimiento</th><th>Moneda</th><th>Importe</th><th>D&iacute;as Mora</th><th>Estado</th>
	</tr>";
	
	$total="";	
			$sql2="SELECT cnt.contacto,
			CASE 
				WHEN cnt.area='C' THEN 'Contabilidad'
				WHEN cnt.area='G' THEN 'Gerencia'
				WHEN cnt.area='T' THEN 'Tesorer&iacute;a'
				WHEN cnt.area='R' THEN 'Recepci&oacute;n'
				WHEN cnt.area='PP' THEN 'Pago a Proveedores'
			END area_c
				,
			m.monedas,pr.proveedor,pr.documento,cl.idcliente,cliente,d.direccion,p.producto,cp.impmin,cp.impcap,
			SUBSTRING(c.idcuenta,1,(LENGTH(c.idcuenta)-2)) 	idcuenta	
			,c.feccon,cp.fecven,cp.imptot,cp.diasmora,cp.idestado
					FROM cuentas c
					JOIN clientes cl ON c.idcliente=cl.idcliente
					JOIN contactos cnt ON cl.idcliente=cnt.idcliente
					JOIN direcciones d ON cl.idcliente=d.idcliente
					JOIN carteras cr ON c.idcartera=cr.idcartera 
					JOIN proveedores pr ON cr.idproveedor=pr.idproveedor
					JOIN productos p ON c.idproducto=p.idproducto
					JOIN monedas m ON c.idmoneda=m.idmoneda
					JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta AND cp.idestado=1
			WHERE 
					c.idcartera=$cart AND cp.idperiodo=$periodo and c.idcliente='$idcliente' $ctas
					and cp.grupo!='OBS' and cp.grupo!='RET'  and cp.grupo!='0'  and cp.impmin!=6 $qr1
					group by cp.idcuenta
					";
			/*echo $sql2;
			return false;*/
			$qry_cta=$db->Execute($sql2);
			$cta_flag="";		
			while(!$qry_cta->EOF){
				$fi1=explode("-",date("Y-m-d"));
				$ff2=explode("-",$qry_cta->fields['fecven']);
				$fi=$fi1[2]."-".$fi1[1]."-".$fi1[0];
				$ff=$ff2[2]."-".$ff2[1]."-".$ff2[0];
				$dias_mora=restaFechas($ff,$fi);

				if($qry_cta->fields['impmin']==6){$es="RET"; $qry_cta->MoveNext(); continue;}else{$es="";}
				$contenido.= "<tr>
							<td align='center'>".$qry_cta->fields['producto']."</td>
							<td align='center'>".substr($qry_cta->fields['idcuenta'],0,-5)."</td>
							<td align='center'>".$qry_cta->fields['feccon']."</td>
							<td align='center'>".$qry_cta->fields['fecven']."</td>
							<td align='center'>".$qry_cta->fields['monedas']."</td>
							<td align='center'>".number_format($qry_cta->fields['impcap'], 2, '.', ',')."</td>
							<td align='center'>".$dias_mora."</td>
							<td align='center'>".$es."</td>
				
					</tr>";
					$total=$total+$qry_cta->fields['imptot'];
					$cta_flag.=$qry_cta->fields['idcuenta'].",";
					$qry_cta->MoveNext();
					
			}
	$contenido.= "
		<tr>
			<td align='center'>Total</td>
			<td align='center'></td>
			<td align='center'></td>
			<td align='center'></td>
			<td align='center'></td>
			<td align='center'>".number_format($total, 2, '.', ',')."</td>
			<td align='center'></td>
			<td align='center'></td>
		</tr>
	
		</table><br/>";
	
	if($tv!=1){
	
	$contenido.= "Asimismo, le recordamos que mantiene(n) <strong>el(los) siguiente(s) documento(s) <br/>
		vencido(s): </strong><br/>";
	
	$contenido.= "<table  border='1'  width='640'>
			<tr bgcolor='#CCCCCC'>
				<th>Tipo Documento</th><th>Documento</th><th>Fecha Emisi&oacute;n</th><th>Fecha Vencimiento</th><th>Moneda</th><th>Importe</th><th>D&iacute;as Mora</th><th>Estado</th>
			</tr>";
			$total="";	
			$sql2="SELECT cnt.contacto,
			CASE 
				WHEN cnt.area='C' THEN 'Contabilidad'
				WHEN cnt.area='G' THEN 'Gerencia'
				WHEN cnt.area='T' THEN 'Tesorer&iacute;a'
				WHEN cnt.area='R' THEN 'Recepci&oacute;n'
				WHEN cnt.area='PP' THEN 'Pago a Proveedores'
			END area_c
				,
			m.monedas,pr.proveedor,pr.documento,cl.idcliente,cliente,d.direccion,p.producto,
			SUBSTRING(c.idcuenta,1,(LENGTH(c.idcuenta)-2)) 	idcuenta	
			,c.feccon,cp.fecven,cp.imptot,cp.diasmora,cp.idestado,cp.impmin,cp.impcap
					FROM cuentas c
					JOIN clientes cl ON c.idcliente=cl.idcliente
					JOIN contactos cnt ON cl.idcliente=cnt.idcliente
					JOIN direcciones d ON cl.idcliente=d.idcliente
					JOIN carteras cr ON c.idcartera=cr.idcartera 
					JOIN proveedores pr ON cr.idproveedor=pr.idproveedor
					JOIN productos p ON c.idproducto=p.idproducto
					JOIN monedas m ON c.idmoneda=m.idmoneda
					JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta AND cp.idestado=1
			WHERE 
					c.idcartera=$cart AND cp.idperiodo=$periodo and c.idcliente='$idcliente'
					and cp.grupo!='OBS' and cp.grupo!='RET'   and cp.grupo!='0'  and cp.impmin!=6  AND fecven<'".date("Y-m-d")."' 
					group by cp.idcuenta
					";
			$qry_cta=$db->Execute($sql2);	
			while(!$qry_cta->EOF){
				if($qry_cta->fields['impmin']==6){$es="RET";}else{$es="";}
				$fi1=explode("-",date("Y-m-d"));
				$ff2=explode("-",$qry_cta->fields['fecven']);
				$fi=$fi1[2]."-".$fi1[1]."-".$fi1[0];
				$ff=$ff2[2]."-".$ff2[1]."-".$ff2[0];
				$dias_mora=restaFechas($ff,$fi);
				$contenido.= "<tr>
							<td align='center'>".$qry_cta->fields['producto']."</td>
							<td align='center'>".substr($qry_cta->fields['idcuenta'],0,-5)."</td>
							<td align='center'>".$qry_cta->fields['feccon']."</td>
							<td align='center'>".$qry_cta->fields['fecven']."</td>
							<td align='center'>".$qry_cta->fields['monedas']."</td>
							<td align='center'>".number_format($qry_cta->fields['impcap'], 2, '.', ',')."</td>
							<td align='center'>".$dias_mora."</td>
							<td align='center'>".$es."</td>
				
					</tr>";
					$total=$total+$qry_cta->fields['imptot'];
					$qry_cta->MoveNext();
					
			}
			$contenido.= "
		<tr>
			<td align='center'>Total</td>
			<td align='center'></td>
			<td align='center'></td>
			<td align='center'></td>
			<td align='center'></td>
			<td align='center'>".number_format($total, 2, '.', ',')."</td>
			<td align='center'></td>
			<td align='center'></td>
		</tr>
	
		</table><br/>";
	}	
		$contenido.="<br/><br/>";
	
	

	$contenido.= "Agradeceremos se sirvan realizar los dep&oacute;sitos de dichos adeudos en el m&aacute;s breve plazo <br/>
		 en nuestra <strong>cuenta recaudadora</strong> de $proveedor  en el <strong> Banco de Cr&eacute;dito del Per&uacute;</strong>,<br/>
		 identificando su n&uacute;mero de factura (10 &uacute;ltimos d&iacute;gitos).
		 <br/><br/>
		 ";
	if($cart==21 or $cart==40){$cta_c="194-1782752-0-94";$cci="002-194-001782752094-95";}
    	if($cart==38){$cta_c="193-1901785-0-48";$cci="002-193-001901785048-18";}
    	if($cart==39){$cta_c="194-1718566-0-51";$cci="002-194-001718566051-95";}


	$contenido.= "<table  border='1' width='640'>
			<tr bgcolor='#CCCCCC'>
				<th>BANCO DE CREDITO</th><th>NRO CUENTA </th>
			</tr>
			<tr>
				<td align='center'>CUENTA CORRIENTE M.N</td><td align='center'> $cta_c</td>
			</tr> 
			<tr bgcolor='#CCCCCC'>
				<th colspan='2'>CODIGO INTERBANCARIO BANCO DE CREDITO </th>
			</tr>
			<tr>
				<td align='center'>$cci</td><td align='center'>CCI SOLES</td>
			</tr> 
		</table>
		
		 <br/><br/>";		
		 
		  	 

	$contenido.= "Es importante la atenci&oacute;n a la presente a efectos de regularizar su situaci&oacute;n crediticia.<br/>
	S&iacute;rvase informarnos en su respuesta la fecha de pago (si la cancelaci&oacute;n ya fue<br/>
	realizada) o la fecha de pago programada (si aun la cancelaci&oacute;n no ha sido realizada).<br/>
	Por favor, enviar su respuesta comunic&aacute;ndose al tel&eacute;fono 700-5200 o a los siguientes <br/>
	correos: lcastellano@kobsa.com.pe, ksotelo@kobsa.com.pe, cobranzas@gruporpp.com.pe<br/>
	<br/>
	Atentamente,<br/>
	---------------------<br/>
	KOBRANZAS S.A.C.<br/>
	Tel&eacute;fono (01) 700-5200 <br/>
	<br/><br/>

	* En caso de haber realizado la cancelaci&oacute;n de los documentos pendientes, s&iacute;rvase dejar sin efecto el presente documento.<br/>
	* En caso su documento tenga m&aacute;s de 75 dias nos veremos obligados de acuerdo a nuestra pol&iacute;tica de informar a la central de riesgo INFOCORP dicha morosidad.<br/>
	* Le agradeceremos no entregar dinero en efectivo al portador de la presente. <br/><br/><br/>
	";
	//echo $contenido;
	$mail->Body ="$contenido";
	$mail->AltBody = "Contenido en <b>html</b> del correo.";
	//$mail->Send();
	$cta=$qry->fields['cta'];
	//$db->debug=true;
		
	//$db->debug=false;
	$qry->MoveNext();
	
	if($mail->Send()){
		++$x;
		$db->Execute("INSERT INTO envios_rpp (`idcliente`,`obs`) values ('$idcliente','$cta_flag')");
		
	}else{
		echo $mail->ErrorInfo;
		++$xm;
	}
	
}

$fecha=Date("Y-m-d");
if($tv!=1){
	$db->Execute("Update cuentas set fecrev='$fecha' where idcuenta $cta_up");
}else{
	$db->Execute("Update cuentas set idsecuencia=1,fecrev='$fecha' where idcuenta $cta_up");
}

echo "Total Enviados: $x| No Enviados:$xm";	
?>