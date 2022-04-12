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



/*$flag=$db->Execute("select flag from flag_reportes where reporte='f_cartera'");
if($flag->fields['flag']=="0"){
	
	$ip=$_SERVER['REMOTE_ADDR'];
	$db->Execute("update flag_reportes set flag='1',idusuario='$iduser',host='$ip' where reporte='f_cartera'");
	$fecha=date("Y-m-d");
	$hora=date("H:i:s");
	$db->Execute("INSERT into detalle_reportes (`idreporte`,`idusuario`,`fecha`,`hora_ini`,`host`) VALUES('3','$iduser','$fecha','$hora','$ip')");
}else{
	echo "En estos momentos se estan ejecutando muchos reportes en curso. Espero uno minutos por favor y vuelva a intentarlo.";
		return false;
}*/


		$peri=$_GET['peri'];
		
		$mes_p=$db->Execute("SELECT MONTH(fecini) mes,YEAR(fecfin) ano FROM periodos WHERE idperiodo='$peri'");
		$mes_a=$mes_p->fields['mes'];
		if($mes_a<10){$mes_a=(string) "0".$mes_a;}
		$ano_a=$mes_p->fields['ano'];
			
		$sql="/* Foto Cartera*/ SELECT MIN(j.peso) peso,MAX(gt.fecreg),cl.idcliente,d.doi,cl.cliente,pr.personerias,cp.idcuenta,mn.simbolo ,p.proveedor
				,c.cartera,pd.producto,e.estado,pe.periodo,us.usuario,cp.fecven ,gt.feccomp,cp.nrocuotas,cp.diasmora,cp.grupo,cp.ciclo
				,cp.observacion,cp.impcap ,cp.impint,cp.impmor,cp.impotr,cp.imptot,cp.impven,cp.impmin,cp.impdestot,cp.impedesmor 
				,cp.impdesmmo,cp.impfraini,cp.fracuo,cp.impfracpr,cp.impframnt,cp.impcapori,cp.impprxpag ,tc.tipocartera,gt.fecges,gg.grupogestion ,r.resultado,j.justificacion

				FROM clientes cl JOIN doi d ON cl.iddoi=d.iddoi 
				JOIN personerias pr ON cl.idpersoneria=pr.idpersoneria 
				JOIN cuentas ct ON cl.idcliente=ct.idcliente 
				LEFT JOIN tipo_carteras tc ON ct.idtipocartera=tc.idtipocartera 
				JOIN monedas mn ON ct.idmoneda=mn.idmoneda 
				JOIN carteras c ON ct.idcartera=c.idcartera 
				LEFT JOIN gestiones gt ON ct.idcuenta=gt.idcuenta AND gt.fecges like '".$ano_a."-".$mes_a."%'
				LEFT JOIN resultados r ON gt.idresultado=r.idresultado 
				LEFT JOIN grupo_gestiones gg ON r.idgrupogestion=gg.idgrupogestion 
				LEFT JOIN justificaciones j ON gt.idjustificacion=j.idjustificacion 
				JOIN proveedores p ON c.idproveedor=p.idproveedor 
				JOIN productos pd ON ct.idproducto=pd.idproducto 
				JOIN cuenta_periodos cp ON ct.idcuenta=cp.idcuenta 
				JOIN estados e ON cp.idestado=e.idestado 
				JOIN periodos pe ON cp.idperiodo=pe.idperiodo 
				JOIN usuarios us ON cp.idusuario=us.idusuario 

			";	
		if(isset($_GET['peri'])){
			$peri=$_GET['peri'];
			$sql.=" WHERE cp.idperiodo='$peri' ";
		}	
		
		if(isset($_GET['prove']) and $_GET['cart']==""){
			$prov=$_GET['prove'];
			$t_pro_c=$db->Execute("Select idcartera from carteras where idproveedor=$prov");
			$id_carteras_pro="";
			while(!$t_pro_c->EOF){
				$id_carteras_pro.=$t_pro_c->fields['idcartera'].",";
				$t_pro_c->MoveNext();
			}
			
			$tt_c_p=strlen($id_carteras_pro);
			
			$sql.=" AND c.idcartera in (".substr($id_carteras_pro,0,($tt_c_p-1)).")";	
		}

		if(isset($_GET['cart'])){
			$cart=$_GET['cart'];
			$sql.=" AND c.idcartera='$cart' ";
		}
		
		if(isset($_GET['tp_cart'])){
			$tpcart=$_GET['tp_cart'];
			$sql.=" AND ct.idtipocartera='$tpcart' ";
		}
			
		$sql.=" GROUP BY cp.idcuenta ORDER BY gt.fecreg DESC,cl.idcliente ";
	
		$n=1;
		if(!file('f_cartera.txt')){
			chmod("f_cartera.txt", 0777);
		}
		$fp = fopen('f_cartera.txt', 'w');
		$titulo="Nro.Doc\tTipo Doc\tCliente\tTip.Pers\tCuenta\tMoneda\tProveedor\tCartera\tProducto\tEstado\tPeriodo\tUsuario\tFecVen\tFecCon\tNro.Cuotas\tDias Mora\tGrupo";
		$titulo.="\tCiclo\tObs.\tImpcap\tImpint\tImpmor\tImpotr\tImptot\tImpven\tImpmin\tImpdestot\tImpdesmor\tImpdesmmo\tImpfraini\tFracuo\tImpfracpr\tImpframnt\tImpcapori";
		$titulo.="\tImpprxpag\tTipo Cartera\tImppagoperacum";
		$titulo.="\tFecRes\tTipo Resultado\tResultado\tDetalle";
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
							
							$cont="=\"".$query->fields['idcliente']."\"\t";
							$cont.=$query->fields['doi']."\t";
							$cont.=$query->fields['cliente']."\t";
							$cont.=$query->fields['personerias']."\t";
							$cont.="=\"".$cta."\"\t";
							$cont.=$query->fields['simbolo']."\t";
							$cont.=$query->fields['proveedor']."\t";
							$cont.=$query->fields['cartera']."\t";
							$cont.=$query->fields['producto']."\t";
							$cont.=$query->fields['estado']."\t";
							$cont.=$query->fields['periodo']."\t";
							$cont.=$query->fields['usuario']." \t";
							$cont.=$query->fields['fecven']."\t";
							$cont.=$query->fields['feccomp']."\t";
							$cont.=$query->fields['nrocuotas']."\t";
							$cont.=$query->fields['diasmora']."\t";
							$cont.=$query->fields['grupo']."\t";
							$cont.=$query->fields['ciclo']."\t";
							$cont.=$query->fields['observacion']."\t";
							$cont.=$query->fields['impcap']."\t";
							$cont.=$query->fields['impint']."\t";
							$cont.=$query->fields['impmor']."\t";
							$cont.=$query->fields['impotr']."\t";
							$cont.=$query->fields['imptot']."\t";
							$cont.=$query->fields['impven']."\t";
							$cont.=$query->fields['impmin']."\t";
							$cont.=$query->fields['impdestot']."\t";
							$cont.=$query->fields['impedesmor']."\t";
							$cont.=$query->fields['impdesmmo']."\t";
							$cont.=$query->fields['impfraini']."\t";
							$cont.=$query->fields['fracuo']."\t";
							$cont.=$query->fields['impfracpr']."\t";
							$cont.=$query->fields['impframnt']."\t";
							$cont.=$query->fields['impcapori']."\t";
							$cont.=$query->fields['impprxpag']."\t";
							$cont.=$query->fields['tipocartera']."\t";
							$cont.="-\t";
	
							$cont.=$query->fields['fecges']."\t";
							$cont.=$query->fields['grupogestion']."\t";
							$cont.=$query->fields['resultado']."\t";
							$cont.=$query->fields['justificacion']."\t";

							;
							//echo $cont."<br>";
							fwrite($fp , $cont);
							fwrite($fp , chr(13).chr(10));
						$query->MoveNext();
					
				}
			fclose($fp);
			echo "Foto Cartera:<a href='guardar_como.php?name=f_cartera.txt' target='blank'>Click para descargar</a><br/>";	



$db->Execute("update flag_reportes set flag='0' where reporte='f_cartera'");
$db->Close();


?>
