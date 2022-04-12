<?php

/** Error reporting */
ini_set('memory_limit', '-1');
set_time_limit(1800);
error_reporting(E_ALL);
session_start();
date_default_timezone_set('Europe/London');
//$db->debug=true;
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
		$fp=fopen("trama_tv_$fecini.txt",'w');

//$bn="CODIGO CLIENTE	DOCUMENTO	NUMERO CUENTA	PAN	GRUPO FACTURACION	FECHA FACTURACION	NOMBRE COMPLETO	NOM_SIS	PMIN1	PMIN2	SAL_FACT1	SAL_FACT2	SEGMENTO	DIAS_MORA_COB	TEL1	TEL2	TEL3	TEL_EMPRESA	ANX_EMPRESA	DIR_DOMICILIO	DISTRITO_DOMICILIO	PROVINCIA_DOMICILIO	DEPARTAMENTO_DOMICILIO	EMPRESA	DIR_EMPRESA	DISTRITO_EMPRESA	DIR_SIS	DISTRITO_SIS	REF1	REF1_TEL	REF2	REF2_TEL	TEL4	TEL5	TEL6	TEL7	TEL8	DIR_ADI1	DISTRITO_ADI1	MARCA_REF	RIESGO	CLASIF_ACTUAL	INCREMENTAL	FECHA DE PAGO	EFECTIVIDAD	PARA_GESTION	TIPO_PAGO	TOT_PAGOS	FLG_EMP_COBRANZA	DIAS_MORA_ASIGNACION	TRAMO_ASIGNACION	PERIODO	PAGO	DESCRIPCION_PAGO	CAMPO_LIBRE_1	F_GESTION	HORA_GESTION	TIPO DE CONTACTO (ACCION)	RESULTADO	NOMBRE GESTOR	FECHA PROM.PAGO	COD_DDN (Telf Contacto)	TLFS_CONTACTO	ANEXO	TIPO TELF NUEVO (CELULAR/FIJO)	COD_DDN (Telf Nvo)	TLF_NUEVO	ANEXO	DIRECCIÓN NUEVA	DISTRITO NUEVO	PROVINCIA NUEVA	DEPARTAMENTO NUEVO	COD_UBIGEO	RESULTADO_CAMPO	CANTIDAD_DE_GESTIONES	CANTIDAD_DE_CONTACTOS	ESTADO";
$bn="CODIGO_BASE	NUMERO CUENTA	NOMBRE_BASE	NOMBRE_CALL	DOCUMENTO	NOMBRE_COMPLETO	TIPO_TC	NRO_TC	LINEA_TC	LINEA_ADIC	LINEA_DISP_EFECT	DIRECCION_ORIGINAL	DISTRITO	PROVINCIA	DEPARTAMENTO	CODIGO_TDA	NOM_TDA	DERIVAR A :	Nom AG_Derivada	EMPRESA	DIR_EMPRESA	DISTRITO_EMPRESA	TEL1	TEL2	TEL3	TEL4	TEL5	TEL6	TEL7	TEL8	TIPO_DOC_IDENT	NUM_DOC	FECH_NAC	APELLIDO PATERNO	APELLIDO MATERNO	NOMBRES	SEXO	NUEV0_TELF_FIJO	NUEV0_TELF_CEL	NUEVA_DIRECCIÓN	NUEVO_DISTRITO	NUEVA_PROVINCIA	NUEVO_DEPARTAMENTO	REFERENCIA DE NUEVA_DIRECCIÓN	EMAIL	TLF_ADIC_1	TLF_ADIC_2	F_CONTACTO	HRA_CONTACTO	TIPO_CONTACTO	RESULTADO	DETALLE (No desea)	OBS_GESTION	TLF DE CONTACTO EFECTIVO	TV	SMS	IVR	CARTA	CANTIDAD_DE_GESTIONES	CANTIDAD_DE_CONTACTOS	F_ENTREGA	HORA_ENTREGA	DIRECCION_ENTREGA	DISTRITO_ENTREGA	PROVINCIA_ENTREGA	DEPARTAMENTO_ENTREGA	REFERENCIA LUGAR DE ENTREGA	NOMB_EJEC_CAMPO	RESULTADO ENTREGA	MOTIVO DE NO ENTREGA	QUIEN  ATENDIO	DESCRIPCION CASA	OBS_ENTREGA	F_ENTREGA EN  TDA	F_HABILITACION	F_1ER_CONSUMO	F_DESEMBOLSO	CAMPO LIBRE 1	OBS FINALES";
		
		fwrite($fp,$bn);
		fwrite($fp , chr(13).chr(10));

//$desde=$_GET['cod'];
// Set properties
$idcartera=$_GET['idcartera'];
$idtipocartera=$_GET['idtipocartera'];
			
			$sql="SELECT 
						cp.idestado,
						cl.idcliente,
						cp.idcuenta,
						cp.observacion,
						cp.grupo,
						cp.fecven,
						cl.cliente,
						cp.impmin,
						cp.impven,
						cp.imptot,
						cp.ciclo segmento,
						cp.diasmora,
						(SELECT telefono FROM telefonos WHERE idcliente=cl.idcliente AND idorigentelefono IN (1,2) LIMIT 0,1) tel1,
						(SELECT telefono FROM telefonos WHERE idcliente=cl.idcliente AND idorigentelefono IN (1,2) LIMIT 1,1) tel2,
						(SELECT telefono FROM telefonos WHERE idcliente=cl.idcliente AND idorigentelefono IN (1,2)LIMIT 2,1) tel3,
						(SELECT telefono FROM telefonos WHERE idcliente=cl.idcliente AND idorigentelefono IN (1,2)LIMIT 3,1) tel4,
						(SELECT telefono FROM telefonos WHERE idcliente=cl.idcliente AND idorigentelefono IN (1,2)LIMIT 4,1) tel5,
						(SELECT telefono FROM telefonos WHERE idcliente=cl.idcliente AND idorigentelefono IN (1,2)LIMIT 5,1) tel6,
						(SELECT telefono FROM telefonos WHERE idcliente=cl.idcliente AND idorigentelefono IN (1,2)LIMIT 6,1) tel7,
						(SELECT telefono FROM telefonos WHERE idcliente=cl.idcliente AND idorigentelefono IN (1,2)LIMIT 7,1) tel8,
						d.direccion,u.*,d.observacion dir_emp,
						c.observacion detalle_pago,
						c.obs2 ,
						cp.impmor,
						tc.tipocartera
						
					FROM cuentas c
					join tipo_carteras tc on c.idtipocartera=tc.idtipocartera
					JOIN clientes cl ON c.idcliente=cl.idcliente 
					JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta AND cp.idperiodo=$peri
					LEFT JOIN  direcciones d ON c.idcliente=d.idcliente
					LEFT JOIN ubigeos u ON d.coddpto=u.coddpto AND d.codprov=u.codprov AND d.coddist=u.coddist
					WHERE c.idcartera=$idcartera and c.idtipocartera=$idtipocartera group by c.idcuenta";	
		//	echo $sql;return false;
			$qr=$db->Execute($sql);

		while(!$qr->EOF){
			$mr=explode("*",$qr->fields['clasificacion']);
			$f_mr=substr($mr[2],0,4)."-".substr($mr[2],4,2)."-".substr($mr[2],6,2);
			$d_mora=$db->Execute("SELECT DATEDIFF(DATE(NOW()),'$mr[2]') diasmora_fc");
			
			$cod_cta=explode("*",$qr->fields['obs2']);

			$cod_cta_pan=explode("*",$qr->fields['observacion']);
							$cta=explode("-",$qr->fields['idcuenta']);
							$linea=$cta[0]."\t";
							$linea.=$cod_cta[0]."\t";
							$linea.=$qr->fields['tipocartera']."\t";
							$linea.="ALTO CONTACTO"."\t";
							$linea.=$qr->fields['idcliente']."\t";
							$linea.=$qr->fields['cliente']."\t";
							$linea.=$cod_cta[1]."\t";
							$linea.="=\"".$cod_cta[2]."\"\t";
							$linea.=$qr->fields['impmin']."\t";
							$linea.=$qr->fields['impven']."\t";
							$linea.=$qr->fields['imptot']."\t";
							
							$linea.=$qr->fields['direccion']."\t";
							$linea.=$qr->fields['nombre']."\t";
							$linea.=$qr->fields['nombre2']."\t";
							$linea.=$qr->fields['nombre3']."\t";
							
							$linea.=$cod_cta_pan[0]."\t";
							$linea.=$cod_cta_pan[1]."\t";
							$linea.=$cod_cta_pan[2]."\t";
							$linea.=$cod_cta_pan[3]."\t";
							$dir_emp=explode("*",$qr->fields['dir_emp']);	
											$linea.=$dir_emp[0]."\t";
											$linea.=$dir_emp[1]."\t";
											$linea.=$dir_emp[2]."\t";

							
							$linea.=$qr->fields['tel1']."\t";
							$linea.=$qr->fields['tel2']."\t";
							$linea.=$qr->fields['tel3']."\t";
							$linea.=$qr->fields['tel4']."\t";
							$linea.=$qr->fields['tel5']."\t";
							$linea.=$qr->fields['tel6']."\t";
							$linea.=$qr->fields['tel7']."\t";
							$linea.=$qr->fields['tel8']."\t";
							$val_tc=$db->Execute("SELECT vc.*,d.doi,
												(SELECT concat(nombre,'-',nombre2,'-',nombre3) ubi FROM ubigeos WHERE coddist=vc.coddist AND codprov=vc.codprov AND coddpto=vc.coddpto limit 0,1)  ubigeo
												FROM validacion_tc_clientes vc left join doi d on vc.iddoi=d.iddoi WHERE idcuenta='".$qr->fields['idcuenta']."' AND idperiodo='$peri'");
							
							$linea.=$val_tc->fields['doi']."\t";
							$linea.=$val_tc->fields['nro_doc']."\t";
							$linea.=$val_tc->fields['fec_nac']."\t";
							$linea.=$val_tc->fields['ape_p']."\t";
							$linea.=$val_tc->fields['ape_m']."\t";
							$linea.=$val_tc->fields['nombre']."\t";
							$linea.=$val_tc->fields['sexo']."\t";
							$linea.=$val_tc->fields['fono']."\t";
							$linea.=$val_tc->fields['celular']."\t";
							$linea.=$val_tc->fields['direccion']."\t";
							$ubi=explode("-",$val_tc->fields['ubigeo']);
							$linea.=$ubi[0]."\t";
							$linea.=$ubi[1]."\t";
							$linea.=$ubi[2]."\t";
							$linea.=$val_tc->fields['referencia']."\t";
							$linea.=$val_tc->fields['email']."\t";
			
				
				$fec_l=explode("-",$fecini);
				$fec_i_l=$ano."-".$mes;	
				//echo $fecini;
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
											WHEN gg.idgrupogestion=7 THEN 'CD'
											WHEN gg.idgrupogestion=8 THEN 'CI'
											WHEN gg.idgrupogestion in (3,9) THEN 'NC'
										END tipo_contacto,
										g.idresultado,
										r.resultado,
										j.justificacion,
										u.usuario,
										g.feccomp,
										t.telefono,
										g.observacion,
										CASE 
											WHEN t.telefono LIKE '9%' AND LENGTH(t.telefono)=9 THEN 'CELULAR'
											ELSE 'FIJO'
										END tipo_fono,
										(SELECT telefono FROM telefonos WHERE idcliente=c.idcliente AND DATE(fecreg)='$fecini' LIMIT 0,1) tel_nuevo,
										u.documento,
										t.telefono
									FROM cuentas c
									JOIN gestiones g ON c.idcuenta=g.idcuenta $fic $fil_gs_c
									JOIN resultados r ON g.idresultado=r.idresultado
									left join justificaciones j on g.idjustificacion=j.idjustificacion
									JOIN grupo_gestiones gg ON r.idgrupogestion=gg.idgrupogestion 
									LEFT JOIN telefonos t ON g.idtelefono=t.idtelefono
									JOIN usuarios u ON g.usureg=u.idusuario
									WHERE c.idcartera=$idcartera and c.idtipocartera=$idtipocartera and c.idcliente='".$qr->fields['idcliente']."'
									AND r.peso_r=(
											SELECT MIN(r2.peso_r) FROM gestiones g2 
												JOIN cuentas c2 ON g2.idcuenta=c2.idcuenta AND c2.idcartera=$idcartera and c2.idtipocartera=$idtipocartera
												JOIN cuenta_periodos cp2 ON c2.idcuenta=cp2.idcuenta AND cp2.idperiodo=$peri 
												JOIN resultados r2 ON g2.idresultado=r2.idresultado
												WHERE c2.idcliente=c.idcliente
												$fil_fecha AND g2.idactividad!=4

											)
									ORDER BY g.fecges DESC,g.horges DESC
							";
					//echo $sql_tw;return false;
					$cs=$db->Execute($sql_tw);
							$linea.=$cs->fields['telnuevo']."\t";
							$tnew=$db->Execute("SELECT telefono as tele_nuevo FROM telefonos WHERE idcliente='".$qr->fields['idcliente']."' AND fecreg like '$ano-$mes%'/*DATE(fecreg)='$fecini'*/ and idfuente=2 LIMIT 0,1");
							$linea.=$tnew->fields['tele_nuevo']."\t";
							$linea.=$cs->fields['fecges']."\t";
							$linea.=$cs->fields['horges']."\t";
							$linea.=$cs->fields['tipo_contacto']."\t";
							$linea.=$cs->fields['resultado']."\t";
							if($cs->fields['idresultado']==52){
								$linea.=$cs->fields['justificacion']."\t";
							}else{
								$linea.="\t";
							}
							$linea.=$cs->fields['observacion']."\t";
							$linea.=$cs->fields['telefono']."\t";
							$linea.=$cs->fields['usuario']."\t";

							$linea.="-\t";
							$linea.="-\t";
							$linea.="-\t";
							
					$tt_ct_q="SELECT COUNT(*) total_ges,
									SUM(gg.idgrupogestion BETWEEN 7 AND 8) contactos
									FROM gestiones g2 
									JOIN cuentas c2 ON g2.idcuenta=c2.idcuenta AND c2.idcartera=$idcartera and c2.idtipocartera=$idtipocartera
									JOIN cuenta_periodos cp2 ON c2.idcuenta=cp2.idcuenta AND cp2.idperiodo=$peri
									JOIN resultados r2 ON g2.idresultado=r2.idresultado
									JOIN grupo_gestiones gg ON r2.idgrupogestion=gg.idgrupogestion
									WHERE c2.idcliente='".$qr->fields['idcliente']."'
									$fil_fecha AND g2.idactividad!=4";
									
					$tt_q=$db->Execute($tt_ct_q);
							$linea.=$tt_q->fields['total_ges']."\t";
							$linea.=$tt_q->fields['contactos']."\t";

					$val_tc=$db->Execute("SELECT vc.*,
												(SELECT concat(nombre,'-',nombre2,'-',nombre3) ubi FROM ubigeos WHERE coddist=vc.coddist AND codprov=vc.codprov AND coddpto=vc.coddpto limit 0,1)  ubigeo
												FROM entrega_tc vc WHERE idcuenta='".$qr->fields['idcuenta']."' AND idperiodo='$peri'");
				
					$linea.=$val_tc->fields['f_entrega']."\t";
					$linea.=$val_tc->fields['h_entrega']."\t";
					$linea.=$val_tc->fields['dir_entrega']."\t";
					$ubi=explode("-",$val_tc->fields['ubigeo']);
					$linea.=$ubi[0]."\t";
					$linea.=$ubi[1]."\t";
					$linea.=$ubi[2]."\t";
					$linea.=$val_tc->fields['ref']."\t";
					$linea.=$val_tc->fields['n_ejecutivo']."\t";	
					
					$val_r_etc=$db->Execute("SELECT rc.* FROM rs_entrega_tc rc WHERE idcuenta='".$qr->fields['idcuenta']."' AND idperiodo='$peri'");
					
					$linea.=$val_r_etc->fields['resultado']."\t";
					$linea.=$val_r_etc->fields['motivo']."\t";
					$linea.=$val_r_etc->fields['q_atiende']."\t";
					$linea.=$val_r_etc->fields['descripcion']."\t";
					$linea.=$val_r_etc->fields['obs']."\t";
					
					$val_r_fc=$db->Execute("SELECT fc.* FROM feedback_tc fc WHERE idcuenta='".$qr->fields['idcuenta']."' AND idperiodo='$peri'");
					           
					$linea.=$val_r_fc->fields['fec_entrega']."\t";
					$linea.=$val_r_fc->fields['fec_hab']."\t";
					$linea.=$val_r_fc->fields['fec_primerc']."\t";
					$linea.=$val_r_fc->fields['fec_des']."\t";
					$linea.=$val_r_fc->fields['libre']."\t";
					$linea.=$val_r_fc->fields['obs']."\t";
					fwrite($fp,$linea);
					fwrite($fp , chr(13).chr(10));		
				
					
					$qr->MoveNext();
		}	
			fclose($fp);
			echo "Trama Cencosud:<a href='guardar_como.php?name=trama_tv_$fecini.txt' target='blank'>Click para descargar</a><br/>";	
	
			




?>