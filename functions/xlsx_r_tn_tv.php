<?php
session_start();
$iduser=$_SESSION['iduser'];
ini_set('memory_limit', '-1');
set_time_limit(1800);
/** Error reporting */
error_reporting(E_ALL);

date_default_timezone_set('Europe/London');

/** PHPExcel */

include '../scripts/conexion.php';


		if(isset($_GET['fecini']) and isset($_GET['fecfin'])){
			$ini=$_GET['fecini'];
			$fin=$_GET['fecfin'];
		}
			/*$ini='2012-04-12';
			$fin='2012-04-12';*/
		$peri=$db->Execute("SELECT idperiodo FROM periodos WHERE fecini<='$ini' AND fecfin>='$ini'");
		$id_peri=$peri->fields['idperiodo'];
		$cartera=$_GET['cartera'];
		if($_GET['tcart']!=""){
			$tcart="and c.idtipocartera=".$_GET['tcart'];
			$tcart2="and ct.idtipocartera=".$_GET['tcart'];
		}else{$tcart="";}

		$nro_enc="";
		$columnas="";
		$case_9;

		$tot_cont="SELECT 
		SUM(gg.idgrupogestion='7') C1,
		SUM(gg.idgrupogestion='8') C2,
		SUM(gg.idgrupogestion='4') N1,
		SUM(gg.idgrupogestion='9') N2,
		cl.idcliente,ct.idcuenta,
				CASE 
				    WHEN gg.idgrupogestion='7' THEN 'CD'
				    WHEN gg.idgrupogestion='8' THEN 'CI'
				    WHEN gg.idgrupogestion='4' OR  gg.idgrupogestion='9' THEN 'NC'
				END AS tipo_contacto
				 
				FROM clientes cl  
				JOIN cuentas ct ON cl.idcliente=ct.idcliente 
				LEFT JOIN tipo_carteras tc ON ct.idtipocartera=tc.idtipocartera 
				JOIN carteras c ON ct.idcartera=c.idcartera 
				LEFT JOIN gestiones gt ON ct.idcuenta=gt.idcuenta 
				LEFT JOIN resultados r ON gt.idresultado=r.idresultado 
				LEFT JOIN grupo_gestiones gg ON r.idgrupogestion=gg.idgrupogestion 
				WHERE ct.idcartera=$cartera $tcart2 AND gt.fecges BETWEEN  '$ini' AND '$fin' 
				group by ct.idcuenta
				";
		
		
			
		/*$sql="SELECT rs.ob1,rs.ob2,rs.idcartera,rs.idcliente,rs.idcuenta,rs.fecven,rs.observacion,rs.telefono,rs.resultado,rs.justificacion,rs.Tipo_Contacto,rs.tp_c,
				rs.fecges,rs.horges,rs.usuario,ec.p1,ec.p2,ec.p3,ec.p4,ec.nr3,ec.fecreg
								
							FROM (
									SELECT cp.observacion ob1,cp.observacion2 ob2,c.idcartera,c.idcliente,g.idcuenta,g.fecges,g.horges,t.telefono,
									(SELECT resultado FROM resultados WHERE idresultado=g.idresultado) resultado,
									(SELECT justificacion FROM justificaciones WHERE idjustificacion=g.idjustificacion) justificacion,
									(SELECT grupogestion FROM grupo_gestiones WHERE idgrupogestion=
										(SELECT idgrupogestion FROM resultados WHERE idresultado=g.idresultado)
									 ) Tipo_Contacto,u.usuario,cp.fecven,cp.observacion,
									 CASE 
									    WHEN gg.idgrupogestion='7' THEN 'CD'
									    WHEN gg.idgrupogestion='8' THEN 'CI'
									    WHEN gg.idgrupogestion='4' OR  gg.idgrupogestion='9' THEN 'NC'
									END AS tp_c
									 
									
									FROM cuentas  c
									JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta
									JOIN gestiones g ON c.idcuenta=g.idcuenta 
									JOIN resultados r ON g.idresultado=r.idresultado 
									JOIN grupo_gestiones gg ON r.idgrupogestion=gg.idgrupogestion 
									JOIN telefonos t ON g.idtelefono=t.idtelefono
									JOIN resultado_carteras rc ON g.idresultado=rc.idresultado
									JOIN usuarios u ON g.usureg=u.idusuario
									WHERE c.idcartera=$cartera and cp.idestado=1 AND g.fecges BETWEEN  '$ini' AND '$fin' 
									AND g.fecreg=(
											SELECT MAX(fecreg) FROM gestiones WHERE idcuenta=g.idcuenta
											)
									GROUP BY c.idcuenta
									ORDER BY 3
							)  rs
							LEFT JOIN encuesta_claro$nro_enc ec ON rs.idcuenta=ec.idcuenta
							GROUP BY rs.idcuenta
							ORDER BY 2

			";	*/
		$sql="
			SELECT rs.cliente,rs.ob1,rs.ob2,rs.idcartera,rs.idcliente,rs.idcuenta,rs.fecven,rs.observacion,rs.telefono,rs.resultado,rs.justificacion,rs.Tipo_Contacto,rs.tp_c,
				rs.fecges,rs.horges,rs.usuario,ec.p1,ec.p2,ec.p3,ec.p4,ec.nr3,ec.fecreg
								
							FROM (
									SELECT cl.cliente,cp.observacion ob1,cp.observacion2 ob2,c.idcartera,c.idcliente,g.idcuenta,g.fecges,g.horges,t.telefono,
									(SELECT resultado FROM resultados WHERE idresultado=g.idresultado) resultado,
									(SELECT justificacion FROM justificaciones WHERE idjustificacion=g.idjustificacion) justificacion,
									(SELECT grupogestion FROM grupo_gestiones WHERE idgrupogestion=
										(SELECT idgrupogestion FROM resultados WHERE idresultado=g.idresultado)
									 ) Tipo_Contacto,u.usuario,cp.fecven,cp.observacion,
									 CASE 
									    WHEN gg.idgrupogestion='7' THEN 'CD'
									    WHEN gg.idgrupogestion='8' THEN 'CI'
									    WHEN gg.idgrupogestion='4' OR  gg.idgrupogestion='9' THEN 'NC'
									END AS tp_c
									 
									
									FROM cuentas  c
									JOIN clientes cl ON c.idcliente=cl.idcliente
									JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta AND cp.idestado=1
									JOIN gestiones g ON c.idcuenta=g.idcuenta 
									JOIN resultados r ON g.idresultado=r.idresultado 
									JOIN grupo_gestiones gg ON r.idgrupogestion=gg.idgrupogestion 
									JOIN telefonos t ON g.idtelefono=t.idtelefono
									JOIN resultado_carteras rc ON g.idresultado=rc.idresultado
									JOIN usuarios u ON g.usureg=u.idusuario
									WHERE c.idcartera=$cartera $tcart /*and cp.idcuenta like 'ENC%'*/ AND g.fecges BETWEEN  '$ini' AND '$fin' AND cp.idperiodo=$id_peri
									AND g.fecreg=(
											SELECT MAX(fecreg) FROM gestiones WHERE idcuenta=g.idcuenta
											)
											
									GROUP BY c.idcuenta
									ORDER BY 3
							)  rs
							LEFT JOIN encuesta_tn ec ON rs.idcuenta=ec.idcuenta
							GROUP BY rs.idcuenta
							ORDER BY 2
							
			";
		$n=1;

			$query2=$db->Execute($tot_cont);
			$var_tot= array ();
			while(!$query2->EOF){
				$pos = strpos($query2->fields['idcuenta'], "-");
				if($pos){
						$ctas = explode("-",$query2->fields['idcuenta']);
						if(count($ctas)>2){ $cta=$ctas[0]."-".$ctas[1]; } else { $cta=$ctas[0]; }
				}
				$var_tot[$cta]['CONTACTOS']=$query2->fields['C1']+$query2->fields['C2'];
				$var_tot[$cta]['LLAMADAS'] =$query2->fields['C1']+$query2->fields['C2']+$query2->fields['N1']+$query2->fields['N2'];
				$query2->MoveNext();
			}

		//$titulo="CODIGO_BASE\tNUMERO_CUENTA\tNOMBRE_CALL\tNOMBRE_BASE\tDOCUMENTO\tNOMBRE_COMPLETO\tLINEA_TC\tTIPO_TC\tDIRECCION_ORIGINAL\tDISTRITO\tPROVINCIA\tDEPARTAMENTO\tCODIGO_TDA\tNOM_TDA\tDERIVAR A :\tJefe _Agencia\tRegional\tTELEFONO_1\tTELEFONO_2\tTELEFONO_3\tTELEFONO_4\tTELEFONO_5\tCAMPO_LIBRE_1\tCAMPO_LIBRE_2\tAPELLIDO_PATERNO\tAPELLIDO_MATERNO\tNOMBRES\tFECHA_DE_NACIMIENTO\tSEXO\tDIRECCIÓN\tREFERENCIA_DE_DIRECCIÓN\tDISTRITO\tPROVINCIA\tDEPARTAMENTO\tTLF_DE_CONTACTO_EFECTIVO\tTLF_ADIC_1\tTLF_ADIC_2\tEMAIL\tOBS_BD\tF_CONTACTO\tHRA_CONTACTO\tTIPO_CONTACTO\tRESULTADO\tDETALLE\tTV\tQ_CONTACTOS\tQ_MARCACIONES\tF_HABILITACION\tF_1ER_CONSUMO\tOBS_FINALES\t";
		$titulo="DNI\tNUMUSU\tNombre\tCalle\tNúmero\tPiso/Manzana/Lote\tUrbanización\tDistrito\tTLF_ADIC_1\tTLF_ADIC_2\tTipo de envio\tF_CONTACTO\tHRA_CONTACTO\tTIPO_CONTACTO\tRESULTADO\tDETALLE\tTV\tQ_CONTACTOS\tQ_MARCACIONES\tP1\tP2\tP3\tObs_P3\tP4\tFEC_ENCUESTA";

		$fp = fopen('f_tv_tn_trama_e.txt', 'w');

		$body=$titulo;
		fwrite($fp, $body);
		fwrite($fp , chr(13).chr(10));
		
		$query=$db->Execute($sql);
		
				while(!$query->EOF){
					++$n;
					$pos = strpos($query->fields['idcuenta'], "-");
					
							if($pos){
								$ctas = explode("-",$query->fields['idcuenta']);
								if(count($ctas)>2){ $cta=$ctas[0]."-".$ctas[1]; } else { $cta=$ctas[0]; }
							}
							$cont="\"".$query->fields['idcliente']."\"\t";	
							$cont.=$cta."\t";	
							$cont.=$query->fields['cliente']."\t";
							
							$tt=explode("/",$query->fields['ob2']);
								
							$cont.=$tt[0]."\t";
							$cont.=$tt[1]."\t";
							$cont.=$tt[2]."\t";
							$cont.=$tt[3]."\t";
							$cont.=$tt[4]."\t";
							
							$cont.=$query->fields['telefono']."\t"; $fono=$query->fields['telefono'];
							
							$cli=$query->fields['idcliente']."\t";
							$sel_tel=$db->Execute("Select telefono from telefonos where idcliente='$cli' and telefono!='$fono' ");
							$xs=0;	
							
							while(!$sel_tel->EOF){
								if($query->fields['telefono']==$sel_tel->fields['telefono']){
									$sel_tel->MoveNext();	
									continue;
								}
								$cont.="=\"".$sel_tel->fields['telefono']."\"\t";
								++$xs;
								if($xs==1){
									break;
								}
								$sel_tel->MoveNext();							
							}
							
							$total=1-$xs;
							for($i=0;$i<$total;$i++){
								$cont.="-"."\t";
							}
							$cont.=$query->fields['ob1']."\t";
							$cont.=$query->fields['fecges']."\t";
							$cont.=$query->fields['horges']."\t";
							$cont.=$query->fields['Tipo_Contacto']."\t";
							$cont.=$query->fields['resultado']."\t";
							$cont.=$query->fields['justificacion']."\t";
							
							$usuario=str_replace("Ã±","ñ",utf8_decode($query->fields['usuario']));
							$cont.=strtoupper($usuario)." \t";

							$cont.=$var_tot[$cta]['CONTACTOS']."\t";
							$cont.=$var_tot[$cta]['LLAMADAS']."\t";
							
							
								$cont.=$query->fields['p1']."\t";
								$cont.=$query->fields['p2']."\t";/*letra*/
								$cont.=$query->fields['p3']."\t";
								$cont.=$query->fields['nr3']."\t";
								$cont.=$query->fields['p4']."\t";

							
							
							$cont.=$query->fields['fecreg']."\t";
							
						fwrite($fp , $cont);
						fwrite($fp , chr(13).chr(10));
					$query->MoveNext();
					
				}
				
			$sql_null="SELECT cl.cliente,c.idcuenta,c.idcliente,cp.fecven,cp.observacion ob1,cp.observacion2 ob2 FROM cuentas c
					join clientes cl on c.idcliente=cl.idcliente
					JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta AND cp.idperiodo=$id_peri AND cp.idestado=1
					LEFT JOIN gestiones g ON c.idcuenta=g.idcuenta
					WHERE c.idcartera=$cartera AND g.idgestion IS NULL";
					
			$query=$db->Execute($sql_null);
		
				while(!$query->EOF){
					++$n;
					$pos = strpos($query->fields['idcuenta'], "-");
					
								if($pos){
								$ctas = explode("-",$query->fields['idcuenta']);
								if(count($ctas)>2){ $cta=$ctas[0]."-".$ctas[1]; } else { $cta=$ctas[0]; }
							}
							$cont="\"".$query->fields['idcliente']."\"\t";	
							$cont.=$cta."\t";	
							$cont.=$query->fields['cliente']."\t";
							
							$tt=explode("/",$query->fields['ob2']);
								
							$cont.=$tt[0]."\t";
							$cont.=$tt[1]."\t";
							$cont.=$tt[2]."\t";
							$cont.=$tt[3]."\t";
							$cont.=$tt[4]."\t";
							
							$cont.=$query->fields['telefono']."\t"; $fono=$query->fields['telefono'];
							
							$cli=$query->fields['idcliente']."\t";
							$sel_tel=$db->Execute("Select telefono from telefonos where idcliente='$cli' and telefono!='$fono' ");
							$xs=0;	
							
							while(!$sel_tel->EOF){
								if($query->fields['telefono']==$sel_tel->fields['telefono']){
									$sel_tel->MoveNext();	
									continue;
								}
								$cont.="=\"".$sel_tel->fields['telefono']."\"\t";
								++$xs;
								if($xs==1){
									break;
								}
								$sel_tel->MoveNext();							
							}
							
							$total=1-$xs;
							for($i=0;$i<$total;$i++){
								$cont.="-"."\t";
							}
							$cont.=$query->fields['ob1']."\t";
						fwrite($fp , $cont);
						fwrite($fp , chr(13).chr(10));
					$query->MoveNext();
					
				}		
			fclose($fp);

			
			echo "Reporte TV Claro:<a href='guardar_como.php?name=f_tv_tn_trama_e.txt' target='blank'>Click para descargar</a><br/>";	
$db->Close();


?>
