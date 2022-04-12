<?php

/** Error reporting */
ini_set('memory_limit', '-1');
set_time_limit(1800);
error_reporting(E_ALL);
session_start();
date_default_timezone_set('Europe/London');

/** PHPExcel */
//require_once '../class/PHPExcel.php';
//require_once '../class/PHPExcel/IOFactory.php';
include '../scripts/conexion.php';

if(isset($_GET['peri']) and  $_GET['peri']!=""){
	$peri=$_GET['peri'];
	$fecini=$_GET['fecini'];
	$fc=$db->Execute("SELECT year(fecini) ano, month(fecini) mes from periodos where idperiodo=$peri");	
	$ano=$fc->fields['ano'];
	$mes=$fc->fields['mes'];
	if($mes<10){$mes=(string) "0".$mes;}
}else{
	return false;
}

function getMonthDays($Month, $Year)
{
  
   if( is_callable("cal_days_in_month"))
   {
      return cal_days_in_month(CAL_GREGORIAN, $Month, $Year);
   }
   else
   {
      // Obtenemos el ultima dia del mes
      return date("d",mktime(0,0,0,$Month+1,0,$Year));
   }
}
		
		//unlink("trama_cencosud_$fecini.txt");
		$fp=fopen("trama_cencosud_$fecini.txt",'w');

$bn="CODIGO CLIENTE	DOCUMENTO	NUMERO CUENTA	PAN	GRUPO FACTURACION	FECHA FACTURACION	NOMBRE COMPLETO	NOM_SIS	PMIN1	PMIN2	SAL_FACT1	SAL_FACT2	SEGMENTO	DIAS_MORA_COB	TEL1	TEL2	TEL3	TEL_EMPRESA	ANX_EMPRESA	DIR_DOMICILIO	DISTRITO_DOMICILIO	PROVINCIA_DOMICILIO	DEPARTAMENTO_DOMICILIO	EMPRESA	DIR_EMPRESA	DISTRITO_EMPRESA	DIR_SIS	DISTRITO_SIS	REF1	REF1_TEL	REF2	REF2_TEL	TEL4	TEL5	TEL6	TEL7	TEL8	DIR_ADI1	DISTRITO_ADI1	MARCA_REF	RIESGO	CLASIF_ACTUAL	INCREMENTAL	FECHA DE PAGO	EFECTIVIDAD	PARA_GESTION	TIPO_PAGO	TOT_PAGOS	FLG_EMP_COBRANZA	DIAS_MORA_ASIGNACION	TRAMO_ASIGNACION	PERIODO	PAGO	DESCRIPCION_PAGO	CAMPO_LIBRE_1	F_GESTION	HORA_GESTION	TIPO DE CONTACTO (ACCION)	RESULTADO	NOMBRE GESTOR	FECHA PROM.PAGO	COD_DDN (Telf Contacto)	TLFS_CONTACTO	ANEXO	TIPO TELF NUEVO (CELULAR/FIJO)	COD_DDN (Telf Nvo)	TLF_NUEVO	ANEXO	DIRECCIÓN NUEVA	DISTRITO NUEVO	PROVINCIA NUEVA	DEPARTAMENTO NUEVO	COD_UBIGEO	RESULTADO_CAMPO	CANTIDAD_DE_GESTIONES	CANTIDAD_DE_CONTACTOS	ESTADO";
		fwrite($fp,$bn);
		fwrite($fp , chr(13).chr(10));

//$desde=$_GET['cod'];
// Set properties
$idcartera=$_GET['idcartera'];
		
			if($peri>=23){
			$sql_us=" and cp.usureg=$peri";
			}else{	
			 $sql_us="";
			}
			$sql="SELECT 
						cp.idestado,
						cl.idcliente,
						cp.idcuenta,
						cp.observacion,
						cp.grupo,
						cp.fecven,
						cl.cliente,
						cp.impmin,
						cp.imptot,
						cp.ciclo segmento,
						cp.diasmora,
						(SELECT telefono FROM telefonos WHERE idcliente=cl.idcliente AND idorigentelefono IN (1,2) LIMIT 0,1) tel1,
						(SELECT telefono FROM telefonos WHERE idcliente=cl.idcliente AND idorigentelefono IN (1,2) LIMIT 1,1) tel2,
						(SELECT telefono FROM telefonos WHERE idcliente=cl.idcliente AND idorigentelefono IN (1,2)LIMIT 2,1) tel3,
						(SELECT telefono FROM telefonos WHERE idcliente=cl.idcliente AND idorigentelefono IN (3,4) LIMIT 0,1) tel_emp,
						(SELECT observacion FROM telefonos WHERE idcliente=cl.idcliente AND idorigentelefono IN (3,4) LIMIT 0,1) anex_emp,
						(SELECT observacion FROM telefonos WHERE idcliente=cl.idcliente AND idorigentelefono IN (9,10)LIMIT 0,1) ref1,	
						(SELECT telefono FROM telefonos WHERE idcliente=cl.idcliente AND idorigentelefono IN (9,10)LIMIT 0,1) ref1_tel,
						(SELECT observacion FROM telefonos WHERE idcliente=cl.idcliente AND idorigentelefono IN (9,10)LIMIT 1,1) ref2,	
						(SELECT telefono FROM telefonos WHERE idcliente=cl.idcliente AND idorigentelefono IN (9,10)LIMIT 1,1) ref2_tel,
						(SELECT telefono FROM telefonos WHERE idcliente=cl.idcliente AND idorigentelefono IN (1,2)LIMIT 3,1) tel4,
						(SELECT telefono FROM telefonos WHERE idcliente=cl.idcliente AND idorigentelefono IN (1,2)LIMIT 4,1) tel5,
						(SELECT telefono FROM telefonos WHERE idcliente=cl.idcliente AND idorigentelefono IN (1,2)LIMIT 5,1) tel6,
						(SELECT telefono FROM telefonos WHERE idcliente=cl.idcliente AND idorigentelefono IN (1,2)LIMIT 6,1) tel7,
						(SELECT telefono FROM telefonos WHERE idcliente=cl.idcliente AND idorigentelefono IN (1,2)LIMIT 7,1) tel8,
						d.direccion,u.*,d.observacion dir_emp,
						CASE
							WHEN cp.observacion2='1' THEN 'Riesgo Alto'
							WHEN cp.observacion2='2' THEN 'Riesgo Medio'
							WHEN cp.observacion2='3' THEN 'Riesgo Bajo'
							ELSE ''
						END riesgo,
						ctp.observacion obs_pago,
						c.observacion detalle_pago,
						c.obs2 clasificacion,
						cp.impmor
					FROM cuentas c
					JOIN clientes cl ON c.idcliente=cl.idcliente 
					JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta AND cp.idperiodo=$peri
					LEFT JOIN cuenta_pagos ctp ON cp.idcuenta=ctp.idcuenta AND ctp.idperiodo=$peri AND ctp.fecreg=(SELECT MAX(fecreg) FROM cuenta_pagos WHERE idcuenta=c.idcuenta and idperiodo=$peri) 
					LEFT JOIN  direcciones d ON c.idcliente=d.idcliente
					LEFT JOIN ubigeos u ON d.coddpto=u.coddpto AND d.codprov=u.codprov AND d.coddist=u.coddist
					WHERE c.idcartera=$idcartera $sql_us group by c.idcuenta";	
			//echo $sql;return false;
			$qr=$db->Execute($sql);

		while(!$qr->EOF){
			$mr=explode("*",$qr->fields['clasificacion']);
			$f_mr=substr($mr[2],0,4)."-".substr($mr[2],4,2)."-".substr($mr[2],6,2);
			$d_mora=$db->Execute("SELECT DATEDIFF(DATE(NOW()),'$mr[2]') diasmora_fc");
			

			$cod_cta_pan=explode("-",$qr->fields['observacion']);
							$cta=explode("-",$qr->fields['idcuenta']);
							$linea=$cod_cta_pan[0]."\t";
							$linea.=$qr->fields['idcliente']."\t";
							$linea.=$cta[0]."\t";
							$linea.=$cod_cta_pan[2]."\t";
							$linea.=$qr->fields['grupo']."\t";
							$linea.=str_replace("-","",$qr->fields['fecven'])."\t";
							$linea.=$qr->fields['cliente']."\t";
							$linea.=$qr->fields['cliente']."\t";
							$linea.=$qr->fields['impmin']."\t";
							$linea.="-"."\t";
							$linea.=$qr->fields['imptot']."\t";
							$linea.="-"."\t";
							$linea.=$qr->fields['segmento']."\t";
							$linea.=$d_mora->fields['diasmora_fc']."\t";
							$linea.=$qr->fields['tel1']."\t";
							$linea.=$qr->fields['tel2']."\t";
							$linea.=$qr->fields['tel3']."\t";
							$linea.=$qr->fields['tel_emp']."\t";
							$linea.=$qr->fields['anex_emp']."\t";

							$linea.=$qr->fields['direccion']."\t";
							$linea.=$qr->fields['nombre']."\t";
							$linea.=$qr->fields['nombre2']."\t";
							$linea.=$qr->fields['nombre3']."\t";
			if(strpos($qr->fields['dir_emp'],'*')){
				$dir_emp=explode("*",$qr->fields['dir_emp']);	
							$linea.=$dir_emp[0]."\t";
							$linea.=$dir_emp[1]."\t";
							$linea.=$dir_emp[2]."\t";
							
			}else{
				$linea.="\t\t\t";
			
			}				
			
							$linea.=$qr->fields['direccion']."\t";
							$linea.=$qr->fields['coddpto'].$qr->fields['codprov'].$qr->fields['coddist']."\t";
							$linea.=$qr->fields['ref1']."\t";
							$linea.=$qr->fields['ref1_tel']."\t";
							$linea.=$qr->fields['ref2']."\t";
							$linea.=$qr->fields['ref2_tel']."\t";
							$linea.=$qr->fields['tel4']."\t";
							$linea.=$qr->fields['tel5']."\t";
							$linea.=$qr->fields['tel6']."\t";
							$linea.=$qr->fields['tel7']."\t";
							$linea.=$qr->fields['tel8']."\t";
							$linea.="-"."\t";
							$linea.="-"."\t";
							$linea.="-"."\t";
							$linea.=$qr->fields['riesgo']."\t";
			$ri="";
			if(strpos($qr->fields['clasificacion'],"*")){
				$pago=explode("*",$qr->fields['clasificacion']);
					
								
								$linea.=$pago[0]."\t";
								$linea.=$pago[1]."\t";
								$linea.=$pago[2]."\t";
				
								if($pago[1]!=""){
									$ri="RI";
								}
							
			}else{
								$linea.="\t";
								$linea.="\t";
								$linea.="\t";
			
			}
				
			if(strpos($qr->fields['obs_pago'],"*")){
				$pago=explode("*",$qr->fields['obs_pago']);
					
								
								$linea.=$pago[0]."\t";
								$linea.=$pago[1]."\t";
								$linea.=$pago[2]."\t";
								$linea.=$pago[3]."\t";
							
			}else{
								$linea.="\t";
								$linea.="\t";
								$linea.="\t";
								$linea.="\t";
			
			}
			
			if(strpos($qr->fields['detalle_pago'],"*")){

				$tr_mora=explode("*",$qr->fields['detalle_pago']);			
							$linea.=$tr_mora[0]."\t";
							$linea.=$d_mora->fields['diasmora_fc']."\t";
							$linea.=$tr_mora[2]."\t";
							$linea.=$tr_mora[3]."\t";
							$linea.=$tr_mora[4]."\t";
							$linea.=$tr_mora[5]."\t";
							
			}else{
							$linea.="\t";
							$linea.=$d_mora->fields['diasmora_fc']."\t";
							$linea.="\t";
							$linea.="\t";
							$linea.="\t";
							$linea.="\t";

			}
				
				$fec_l=explode("-",$fecini);
				$fec_i_l=$ano."-".$mes;	
				
				if($fecini==99){
					$fil_fecha=" AND g2.fecges like '$fec_i_l%' ";
					$fic=" and g.fecges like '$fec_i_l%' ";
					$fil_gs_c=" ";
				}else{
					$fil_fecha=" AND g2.fecges='$fecini' ";
					$fic="";
					$fil_gs_c=" and g.fecges='$fecini' ";

				}
				
				$sql_tw="
								SELECT g.fecges,
										   g.horges,
										   CASE
											WHEN gg.idgrupogestion IN (11,12,13) THEN 'CD'
											WHEN gg.idgrupogestion=14 THEN 'CI'
											WHEN gg.idgrupogestion=15 THEN 'NC'
										END tipo_contacto,
										r.resultado,
										u.usuario,
										g.feccomp,
										t.telefono,
										
										CASE 
											WHEN t.telefono LIKE '9%' AND LENGTH(t.telefono)=9 THEN 'CELULAR'
											ELSE 'FIJO'
										END tipo_fono,
										(SELECT telefono FROM telefonos WHERE idcliente=c.idcliente AND DATE(fecreg)='$fecini' LIMIT 0,1) tel_nuevo,
										u.documento
									FROM cuentas c
									JOIN gestiones g ON c.idcuenta=g.idcuenta $fic $fil_gs_c
									JOIN resultados r ON g.idresultado=r.idresultado
									JOIN grupo_gestiones gg ON r.idgrupogestion=gg.idgrupogestion -- 11 al 15
									LEFT JOIN telefonos t ON g.idtelefono=t.idtelefono
									JOIN usuarios u ON g.usureg=u.idusuario
									WHERE c.idcartera=$idcartera and c.idcliente='".$qr->fields['idcliente']."'
									AND r.peso_r=(
											SELECT MIN(r2.peso_r) FROM gestiones g2 
												JOIN cuentas c2 ON g2.idcuenta=c2.idcuenta AND c2.idcartera=$idcartera
												JOIN cuenta_periodos cp2 ON c2.idcuenta=cp2.idcuenta AND cp2.idperiodo=$peri 
												JOIN resultados r2 ON g2.idresultado=r2.idresultado
												WHERE c2.idcliente=c.idcliente
												$fil_fecha AND g2.idactividad!=4

											)
									ORDER BY g.fecges DESC,g.horges DESC
							";
					
					$linea.="$ri\t";
					$cs=$db->Execute($sql_tw);
					if($cs->fields['feccomp']=="" or $cs->fields['feccomp']=="0000-00-00"){$feccomp="";}else{$feccomp=$cs->fields['feccomp'];}
							
							$linea.=$cs->fields['fecges']."\t";
							$linea.=$cs->fields['horges']."\t";
							$linea.=$cs->fields['tipo_contacto']."\t";
							$linea.=substr($cs->fields['resultado'],3)."\t";
							$linea.=$cs->fields['usuario']."\t";
							$linea.=$feccomp."\t";
							$linea.="\t";
							$linea.=$cs->fields['telefono']."\t";
							$linea.="\t";
							$linea.=$cs->fields['tipo_fono']."\t";
							$linea.="\t";

							$tnew=$db->Execute("SELECT telefono as tele_nuevo FROM telefonos WHERE idcliente='".$qr->fields['idcliente']."' AND fecreg like '$ano-$mes%'/*DATE(fecreg)='$fecini'*/ and idfuente=2 LIMIT 0,1");
							$linea.=$tnew->fields['tele_nuevo']."\t";

							$linea.="\t";
							$tnewd=$db->Execute("SELECT d.*,u.nombre,u.nombre2,u.nombre3 FROM direcciones d 
										join ubigeos u on d.coddpto=u.coddpto and d.codprov=u.codprov and d.coddist=u.coddist
										WHERE d.idcliente='".$qr->fields['idcliente']."' 
										 AND d.fecreg like '$ano-$mes%' /*AND DATE(d.fecreg)='$fecini'*/ and d.idfuente=2 LIMIT 0,1");
							$linea.=$tnewd->fields['direccion']."\t";
							$linea.=$tnewd->fields['nombre']."\t";
							$linea.=$tnewd->fields['nombre2']."\t";
							$linea.=$tnewd->fields['nombre3']."\t";
							$linea.="=\"".$tnewd->fields['coddpto'].$tnewd->fields['codprov'].$tnewd->fields['coddist']."\"\t";

							//$linea.="\t";
							//$linea.="\t";
							//$linea.="\t";
							//$linea.="\t";
							//$linea.="\t";
							$linea.="\t";
							
							//$linea.=$cs->fields['documento']."\t";
					$tt_ct_q="SELECT COUNT(*) total_ges,
									SUM(gg.idgrupogestion BETWEEN 11 AND 14) contactos
									FROM gestiones g2 
									JOIN cuentas c2 ON g2.idcuenta=c2.idcuenta AND c2.idcartera=$idcartera
									JOIN cuenta_periodos cp2 ON c2.idcuenta=cp2.idcuenta AND cp2.idperiodo=$peri
									JOIN resultados r2 ON g2.idresultado=r2.idresultado
									JOIN grupo_gestiones gg ON r2.idgrupogestion=gg.idgrupogestion
									WHERE c2.idcliente='".$qr->fields['idcliente']."'
									$fil_fecha AND g2.idactividad!=4";
									
					$tt_q=$db->Execute($tt_ct_q);
							$linea.=$tt_q->fields['total_ges']."\t";
							$linea.=$tt_q->fields['contactos']."\t";

					$linea.=$qr->fields['idestado']."\t";

							
					fwrite($fp,$linea);
					fwrite($fp , chr(13).chr(10));		
				
					
					$qr->MoveNext();
		}	
			fclose($fp);
			echo "Trama Cencosud:<a href='guardar_como.php?name=trama_cencosud_$fecini.txt' target='blank'>Click para descargar</a><br/>";	
	
			




?>