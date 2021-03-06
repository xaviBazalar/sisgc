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


		$peri=$_GET['peri'];
		//$mes=$db->Execute("Select month(fecini) mes from periodos where idperiodo='$peri'");
		$mes_p=$db->Execute("SELECT MONTH(fecini) mes FROM periodos WHERE idperiodo='$peri'");
		$mes_a=$mes_p->fields['mes'];
		mysql_free_result($mes_p->_queryID);	
		/*$sql="SELECT ct.feccon,MIN(j.peso) peso,MAX(gt.fecreg),cl.idcliente,cl.cliente,cp.idcuenta
				,c.cartera,tcr.tipocartera,e.estado,us.usuario,gt.feccomp
				,cp.observacion,cp.observacion2,cp.impcap
				,tc.tipocartera,gt.fecges,gt.horges,gg.grupogestion ,r.resultado,j.justificacion,
				gt.observacion obs_ges,tl.telefono,r.idresultado,
				CASE 
				    WHEN tcb.idtipocontactabilidad='1' THEN 'CD'
				    WHEN tcb.idtipocontactabilidad='2' THEN 'CI'
				    WHEN tcb.idtipocontactabilidad='3' THEN 'NC'
				END AS tipo_contacto
				 
				FROM clientes cl  
				JOIN cuentas ct ON cl.idcliente=ct.idcliente 
				LEFT JOIN tipo_carteras tc ON ct.idtipocartera=tc.idtipocartera 
				JOIN carteras c ON ct.idcartera=c.idcartera 
				JOIN tipo_carteras tcr ON ct.idtipocartera=tcr.idtipocartera				
				LEFT JOIN gestiones gt ON ct.idcuenta=gt.idcuenta AND MONTH(gt.fecges)='$mes_a'
				LEFT JOIN resultados r ON gt.idresultado=r.idresultado 
				LEFT JOIN grupo_gestiones gg ON r.idgrupogestion=gg.idgrupogestion 
				LEFT JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion 
				LEFT JOIN telefonos tl ON gt.idtelefono=tl.idtelefono
				LEFT JOIN contactabilidad cb ON gt.idcontactabilidad=cb.idcontactabilidad
				LEFT JOIN tipo_contactabilidad tcb ON cb.idtipocontactabilidad=tcb.idtipocontactabilidad								
				JOIN proveedores p ON c.idproveedor=p.idproveedor 
				JOIN cuenta_periodos cp ON ct.idcuenta=cp.idcuenta 
				JOIN estados e ON cp.idestado=e.idestado 
				JOIN usuarios us ON cp.idusuario=us.idusuario 
				WHERE cp.idperiodo='$peri'
				AND p.idproveedor='15' 
				AND c.idcartera='24'
			";	*/
		$tot_cont="SELECT MIN(j.peso) peso,MAX(gt.fecreg) fecr,
		SUM(gg.idgrupogestion='7') C1,
		SUM(gg.idgrupogestion='8') C2,
		SUM(gg.idgrupogestion='4') N1,
		SUM(gg.idgrupogestion='9') N2,
		cl.idcliente,cp.idcuenta,
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
				LEFT JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion 
				JOIN proveedores p ON c.idproveedor=p.idproveedor 
				JOIN cuenta_periodos cp ON ct.idcuenta=cp.idcuenta 
				WHERE 
				p.idproveedor='15' 
				AND c.idcartera='24'
				";
				
		/*SELECT MIN(j.peso) peso,MAX(gt.fecreg) fecr,
		SUM(gg.idgrupogestion='7') C1,
		SUM(gg.idgrupogestion='8') C2,
		SUM(gg.idgrupogestion='4') N1,
		SUM(gg.idgrupogestion='9') N2,
		cl.idcliente,cp.idcuenta,
				CASE 
				    WHEN gg.idgrupogestion='7' THEN 'CD'
				    WHEN gg.idgrupogestion='8' THEN 'CI'
				    WHEN gg.idgrupogestion='4' OR  gg.idgrupogestion='9' THEN 'NC'
				END AS tipo_contacto
				 
				FROM clientes cl  
				JOIN cuentas ct ON cl.idcliente=ct.idcliente 
				LEFT JOIN tipo_carteras tc ON ct.idtipocartera=tc.idtipocartera 
				JOIN carteras c ON ct.idcartera=c.idcartera 
				LEFT JOIN gestiones gt ON ct.idcuenta=gt.idcuenta AND MONTH(gt.fecges)='$mes_a'
				LEFT JOIN resultados r ON gt.idresultado=r.idresultado 
				LEFT JOIN grupo_gestiones gg ON r.idgrupogestion=gg.idgrupogestion 
				LEFT JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion 
				JOIN proveedores p ON c.idproveedor=p.idproveedor 
				JOIN cuenta_periodos cp ON ct.idcuenta=cp.idcuenta 
				WHERE cp.idperiodo='$peri'
				AND p.idproveedor='15' 
				AND c.idcartera='24'*/
			
		$sql="SELECT gt.idgestion,ct.feccon,MIN(j.peso) peso,MAX(gt.fecreg),cl.idcliente,cl.cliente,cp.idcuenta
				,c.cartera,tcr.tipocartera,e.estado,us.usuario,gt.feccomp
				,cp.observacion,cp.observacion2,cp.obs3,cp.impcap,prd.producto
				,tc.tipocartera,tc.idtipocartera,gt.fecges,gt.horges,gg.grupogestion ,r.resultado,j.justificacion,
				gt.observacion obs_ges,tl.telefono,r.idresultado,
				CASE 
				    WHEN gg.idgrupogestion='7' THEN 'CD'
				    WHEN gg.idgrupogestion='8' THEN 'CI'
				    WHEN gg.idgrupogestion='4' or  gg.idgrupogestion='9' THEN 'NC'
				END AS tipo_contacto
				 
				FROM clientes cl  
				JOIN cuentas ct ON cl.idcliente=ct.idcliente 
				JOIN productos prd ON ct.idproducto=prd.idproducto
				LEFT JOIN tipo_carteras tc ON ct.idtipocartera=tc.idtipocartera 
				JOIN carteras c ON ct.idcartera=c.idcartera 
				JOIN tipo_carteras tcr ON ct.idtipocartera=tcr.idtipocartera				
				LEFT JOIN gestiones gt ON ct.idcuenta=gt.idcuenta 
				LEFT JOIN resultados r ON gt.idresultado=r.idresultado 
				LEFT JOIN grupo_gestiones gg ON r.idgrupogestion=gg.idgrupogestion 
				LEFT JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion 
				LEFT JOIN telefonos tl ON gt.idtelefono=tl.idtelefono
				JOIN proveedores p ON c.idproveedor=p.idproveedor 
				JOIN cuenta_periodos cp ON ct.idcuenta=cp.idcuenta 
				JOIN estados e ON cp.idestado=e.idestado 
				LEFT JOIN usuarios us ON gt.usureg=us.idusuario 
				WHERE 
				p.idproveedor='15' 
				AND c.idcartera='24'
			";	
			/*
				SELECT gt.idgestion,ct.feccon,MIN(j.peso) peso,MAX(gt.fecreg),cl.idcliente,cl.cliente,cp.idcuenta
				,c.cartera,tcr.tipocartera,e.estado,us.usuario,gt.feccomp
				,cp.observacion,cp.observacion2,cp.obs3,cp.impcap,prd.producto
				,tc.tipocartera,tc.idtipocartera,gt.fecges,gt.horges,gg.grupogestion ,r.resultado,j.justificacion,
				gt.observacion obs_ges,tl.telefono,r.idresultado,
				CASE 
				    WHEN gg.idgrupogestion='7' THEN 'CD'
				    WHEN gg.idgrupogestion='8' THEN 'CI'
				    WHEN gg.idgrupogestion='4' or  gg.idgrupogestion='9' THEN 'NC'
				END AS tipo_contacto
				 
				FROM clientes cl  
				JOIN cuentas ct ON cl.idcliente=ct.idcliente 
				JOIN productos prd ON ct.idproducto=prd.idproducto
				LEFT JOIN tipo_carteras tc ON ct.idtipocartera=tc.idtipocartera 
				JOIN carteras c ON ct.idcartera=c.idcartera 
				JOIN tipo_carteras tcr ON ct.idtipocartera=tcr.idtipocartera				
				LEFT JOIN gestiones gt ON ct.idcuenta=gt.idcuenta AND MONTH(gt.fecges)='$mes_a'
				LEFT JOIN resultados r ON gt.idresultado=r.idresultado 
				LEFT JOIN grupo_gestiones gg ON r.idgrupogestion=gg.idgrupogestion 
				LEFT JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion 
				LEFT JOIN telefonos tl ON gt.idtelefono=tl.idtelefono
				JOIN proveedores p ON c.idproveedor=p.idproveedor 
				JOIN cuenta_periodos cp ON ct.idcuenta=cp.idcuenta 
				JOIN estados e ON cp.idestado=e.idestado 
				LEFT JOIN usuarios us ON gt.usureg=us.idusuario 
				WHERE cp.idperiodo='$peri'
				AND p.idproveedor='15' 
				AND c.idcartera='24'
			*/
		$n=1;
		if(isset($_GET['fecini']) and isset($_GET['fecfin'])){
			$ini=$_GET['fecini'];
			$fin=$_GET['fecfin'];
			$sql.=" AND gt.fecges BETWEEN '$ini' AND '$fin' ";
			$tot_cont.=" AND gt.fecges BETWEEN '$ini' AND '$fin' ";
		}
		
		if(isset($_GET['tipo_c']) and $_GET['tipo_c']!=""){
			$tip_c=$_GET['tipo_c'];
			
			$sql.=" AND ct.idtipocartera='$tip_c' ";
			$tot_cont.=" AND ct.idtipocartera='$tip_c' ";
		}
		
		
		
		$sql.="GROUP BY cp.idcuenta,j.peso ORDER BY gt.idgestion DESC,cl.idcliente	";
		$tot_cont.=" GROUP BY cp.idcuenta ORDER BY gt.idgestion DESC,cl.idcliente ";
			$query2=$db->Execute($tot_cont);
			$var_tot= array ();
			while(!$query2->EOF){
				$pos = strpos($query2->fields['idcuenta'], "-");
				if($pos){
						$ctas = explode("-",$query2->fields['idcuenta']);
						if(count($ctas)>2){ $cta=$ctas[0]."-".$ctas[1]; } else { $cta=$ctas[0]; }
				}
				$var_tot[$cta]['CONTACTOS']=$query2->fields['C1']+$query2->fields['C2'];
				$var_tot[$cta]['LLAMADAS']=$query2->fields['C1']+$query2->fields['C2']+$query2->fields['N1']+$query2->fields['N2'];
				$query2->MoveNext();
			}

		$titulo="CODIGO_BASE\tNUMERO_CUENTA\tNOMBRE_CALL\tNOMBRE_BASE\tDOCUMENTO\tNOMBRE_COMPLETO\tLINEA_TC\tTIPO_TC\tDIRECCION_ORIGINAL\tDISTRITO\tPROVINCIA\tDEPARTAMENTO\tCODIGO_TDA\tNOM_TDA\tDERIVAR A :\tJefe _Agencia\tRegional\tTELEFONO_1\tTELEFONO_2\tTELEFONO_3\tTELEFONO_4\tTELEFONO_5\tCAMPO_LIBRE_1\tCAMPO_LIBRE_2\tAPELLIDO_PATERNO\tAPELLIDO_MATERNO\tNOMBRES\tFECHA_DE_NACIMIENTO\tSEXO\tDIRECCI?N\tREFERENCIA_DE_DIRECCI?N\tDISTRITO\tPROVINCIA\tDEPARTAMENTO\tTLF_DE_CONTACTO_EFECTIVO\tTLF_ADIC_1\tTLF_ADIC_2\tEMAIL\tOBS_BD\tF_CONTACTO\tHRA_CONTACTO\tTIPO_CONTACTO\tRESULTADO\tDETALLE\tTV\tQ_CONTACTOS\tQ_MARCACIONES\tF_HABILITACION\tF_1ER_CONSUMO\tOBS_FINALES\t";

		$fp = fopen('f_tv.txt', 'w');

		$body=$titulo;
		fwrite($fp, $body);
		fwrite($fp , chr(13).chr(10));
		/*echo $sql;
		return false;*/
		$query=$db->Execute($sql);
	
				while(!$query->EOF){
					++$n;
					$pos = strpos($query->fields['idcuenta'], "-");
					
								if($pos){
									$ctas = explode("-",$query->fields['idcuenta']);
									if(count($ctas)>2){ $cta=$ctas[0]."-".$ctas[1]; } else { $cta=$ctas[0]; }
								}
					
					
							$cli=$query->fields['idcliente'];
							if($n==1){
								$ctas2[$cta]=$cta;
		
							}else if(in_array($cta,$ctas2) and $query->fields['idgestion']!=""){
									$query->MoveNext();
									continue;	
							}else{
								$ctas2[$cta]=$cta;
							}
							$cont=$cta."\t";
							$nro_cta=explode("/",$query->fields['observacion2']);
							$cont.="=\"".$nro_cta[0]."\"\t";
							$cont.="KOBSA"."\t";
							$cont.=$query->fields['tipocartera']."\t";
							$cont.="=\"".$query->fields['idcliente']."\"\t";
							$cont.=utf8_encode($query->fields['cliente'])."\t";
							$cont.=$query->fields['impcap']."\t";
							$cont.=$query->fields['producto']."\t";
							
							$sel_dir=$db->Execute("Select direccion from direcciones where idcliente='$cli' and prioridad='1' limit 0,1");
							$cont.=$sel_dir->fields['direccion']."\t";
							
								$ubi=explode("-",$nro_cta[1]);
							
							$cont.=$ubi[0]." \t";
							$cont.=$ubi[1]." \t";
							$cont.=$ubi[2]." \t";
							
							if($query->fields['idtipocartera']==15){
								$tp_push=explode("-",$query->fields['observacion']);
								$cont.=$tp_push[0]."\t";
								$cont.=$tp_push[1]."\t";
								
								$obs3_push=explode("/",$query->fields['obs3']);
								$cont.=$obs3_push[0]."\t";
								$cont.=$obs3_push[1]."\t";
								$cont.=$obs3_push[2]."\t";
								
							}else{
								$cont.="-\t";
								$cont.="-\t";
								$cont.="-\t";
								$cont.="-\t";
								$cont.="-\t";
								
							}
							
							$sel_tel=$db->Execute("Select telefono from telefonos where idcliente='$cli'");
							$xs=0;	
							while(!$sel_tel->EOF){
								$cont.="=\"".$sel_tel->fields['telefono']."\"\t";
								++$xs;
								if($xs==5){
									break;
								}
								$sel_tel->MoveNext();							
							}
							$total=5-$xs;
							for($i=0;$i<$total;$i++){
								$cont.="-"."\t";
							}
							
							$cont.=" "."\t";
							$cont.=" "."\t";
							if($query->fields['idresultado']==51){
								$venta=explode("(",$query->fields['obs_ges']);
								$vta=explode("*",$venta[1]);
								$cont.=$vta[0]."\t";
								$cont.=$vta[1]."\t";
								$cont.=$vta[2]."\t";
								$cont.=$vta[3]."\t";
								$cont.=$vta[4]."\t";
								$cont.=$vta[5]."\t";
								$cont.=$vta[6]."\t";
								$cont.=$vta[7]."\t";
								$cont.=$vta[8]."\t";
								$cont.=$vta[9]."\t";
							}else{
								$cont.=" \t";
								$cont.=" \t";
								$cont.=" \t";
								$cont.=" \t";
								$cont.=" \t";
								$cont.=" \t";
								$cont.=" \t";
								$cont.=" \t";
								$cont.=" \t";
								$cont.=" \t";
							}
							$cont.="=\"".$query->fields['telefono']."\"\t";
							$cont.=" \t";
							$cont.=" \t";
							$cont.=" \t";
							$cont.=" \t";
							
							$cont.=$query->fields['fecges']."\t";
							$cont.=$query->fields['horges']."\t";
							$cont.=$query->fields['tipo_contacto']." \t";
							$cont.=$query->fields['resultado']."\t";
							if($query->fields['idresultado']==52){
								$cont.=$query->fields['justificacion']."\t";
							}else{
								$cont.=" \t";
							}
							$usuario=str_replace("??","?",utf8_decode($query->fields['usuario']));
							$cont.=strtoupper($usuario)." \t";
							$cont.=$var_tot[$cta]['CONTACTOS']."\t";
							$cont.=$var_tot[$cta]['LLAMADAS']."\t";
							$cont.="-\t";
							$cont.="-\t";
							$cont.="-\t";

							
						fwrite($fp , $cont);
						fwrite($fp , chr(13).chr(10));
					$query->MoveNext();
					
				}
			fclose($fp);
			echo "Foto TV:<a href='guardar_como.php?name=f_tv.txt' target='blank'>Click para descargar</a><br/>";	


mysql_free_result($query->_queryID);
$db->Execute("update flag_reportes set flag='0' where reporte='f_cartera'");
$db->Close();


?>
