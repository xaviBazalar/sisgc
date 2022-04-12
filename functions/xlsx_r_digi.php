<?php
ini_set('memory_limit', '-1');
set_time_limit(1800);
/** Error reporting */
error_reporting(E_ALL);

date_default_timezone_set('Europe/London');

/** PHPExcel */
require_once '../class/PHPExcel.php';
require_once '../class/PHPExcel/IOFactory.php';
include '../scripts/conexion.php';
include 'rango_hora.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

		$peri=$_GET['peri'];
		
		$mes_p=$db->Execute("SELECT fecini,MONTH(fecini) mes FROM periodos WHERE idperiodo='$peri'");
		$mes_a=$mes_p->fields['mes'];
/*Inicio Instrucciones*/
	$objPHPExcel->getProperties()->setCreator("Kobsa - Gestion")
									 ->setLastModifiedBy("Kobsa - Gestion")
									 ->setTitle("Office 2007 XLSX Test Document")
									 ->setSubject("Office 2007 XLSX Test Document")
									 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
									 ->setKeywords("office 2007 openxml php")
									 ->setCategory("Test result file");
		
		$sql="SELECT  /*Reporte Digitacion*/j.peso,d.coddist,d.coddpto,d.codprov,cl.idcliente,cl.cliente,cp.idcuenta,m.monedas,p.proveedor,cr.cartera,pr.producto,a.actividad,g.fecges,g.horges,
				v.validaciones,d.direccion,od.origendireccion,f.fuente,tp.tipo_predio,mp.material,np.piso,cpr.color,
				tpc.tipocontactabilidad,cct.contactabilidad,gg.grupogestion,r.resultado,g.impcomp,g.feccomp,g.observacion,g.idagente,
				usr.usuario negociador,
				DATE(g.fecreg) fechad,TIME(g.fecreg) horad,HOUR(g.fecreg) hora
				FROM gestiones g
			LEFT JOIN resultados r ON g.idresultado=r.idresultado
			LEFT JOIN justificaciones j ON r.idresultado=j.idresultado
			LEFT JOIN grupo_gestiones gg ON r.idgrupogestion=gg.idgrupogestion
			LEFT JOIN contactabilidad cct ON g.idcontactabilidad=cct.idcontactabilidad
			LEFT JOIN tipo_contactabilidad tpc ON cct.idtipocontactabilidad=tpc.idtipocontactabilidad
			LEFT JOIN tipo_predio tp ON g.idpredio=tp.idpredio
			LEFT JOIN material_predio mp ON g.idmaterial_predio=mp.idmaterial_predio
			LEFT JOIN nro_pisos np ON g.idnro_pisos=np.idnro_pisos
			LEFT JOIN colores_pared cpr ON g.idcolor_pared=cpr.idcolor_pared
			JOIN actividades a ON g.idactividad=a.idactividad
			JOIN direcciones d ON g.iddireccion=d.iddireccion
			LEFT JOIN validaciones v ON d.idvalidacion=v.idvalidaciones
			JOIN origen_direcciones od ON d.idorigendireccion=od.idorigendireccion
			JOIN fuentes f ON d.idfuente=f.idfuente
			JOIN cuentas c ON g.idcuenta=c.idcuenta
			JOIN monedas m ON c.idmoneda=m.idmoneda
			JOIN productos pr ON c.idproducto=pr.idproducto
			JOIN carteras cr ON c.idcartera=cr.idcartera
			JOIN proveedores p ON cr.idproveedor=p.idproveedor
			JOIN cuenta_periodos  cp ON g.idcuenta=cp.idcuenta
			JOIN clientes cl ON c.idcliente=cl.idcliente
			LEFT JOIN usuarios usr ON g.usureg=usr.idusuario
			WHERE g.idagente and Month(g.fecges)=$mes_a ";
		
		if(isset($_GET['peri'])){
			$peri=$_GET['peri'];
			$sql.=" AND cp.idperiodo='$peri'   ";
		}	
//JOIN ubigeos ub ON d.coddpto=ub.coddpto AND d.codprov=ub.codprov AND d.coddist=ub.coddist
		if(isset($_GET['prove'])){
			$prov=$_GET['prove'];
			$sql.=" AND pr.idproveedor='$prov' ";
		}

		if(isset($_GET['cart'])){
			$cart=$_GET['cart'];
			$sql.=" AND cr.idcartera='$cart' ";
		}
		
		$total=0;
		/*if(isset($_GET['actv'])){
			$pos = strpos($_GET['actv'], "-");
			if($pos){
				$acts = explode("-",$_GET['actv']);
				
				$total=count($acts);
				$act= array();
				for($i=0;$i<$total;$i++){
					$act[$i+1]=$acts[$i];
				}
			}else{
				$total=0;
				$actv=$_GET['actv'];	
				$sql.=" AND a.idactividad='$actv' ";
			}
		}*/
		
		if(isset($_GET['fecini']) and isset($_GET['fecfin'])){
			$ini=$_GET['fecini'];
			$fin=$_GET['fecfin'];
			$sql.=" AND g.fecges BETWEEN '$ini' AND '$fin' ";
			//$sql.=" AND g.fecges BETWEEN '$ini' AND '$fin' ";
		}

		$sql.=" ORDER BY g.fecreg ";
		
		$query=$db->Execute($sql);
		/*echo $sql;
		return false;*/
		$n=1;
		$fp=fopen('f_digit.txt','w');
		$titulo="Documento\tCliente\tCta\tMoneda\tProveedor\tCartera\tProducto\tIndicador\tFechaVisita\tHoraVisita\tValidacionDK\tDireccionVisitada\tCuadrante\tUbigeo\tOrigen\t";
		$titulo.="Fuente\tTipoPredio\tMaterialPredio\tNroPisos\tColorPared\tTipoContacto\tContacto\tTipoResultado\tResultado\tImpPDP_S/.\tImpPDP_US$\t";
		$titulo.="FechaPDP\tObservaciones\tRangoHoraVisita\tPrioridadInf\tNegociador\tFechaDigito\tHoraDigito\tRangoHoraDigito\tDigitador\tPeso";
		$body=$titulo;
		fwrite($fp, $body);
		fwrite($fp , chr(13).chr(10));
		$query=$db->Execute($sql);
		
			$objPHPExcel->getDefaultStyle()->getFont()
				->setName('Calibri')
				->setSize(9);
				while(!$query->EOF){
										$r_hora=rango_hora($query->fields['horges']);
					$r_hora_d=rango_hora($query->fields['hora']);
					++$n;
					if($query->fields['impcomp']=="0.00"){	$impcomp="";}else{$impcomp=$query->fields['impcomp'];}
					if($query->fields['feccomp']=="0000-00-00"){$feccomp="";}else{$feccomp=$query->fields['feccomp'];}
					$pos = strpos($query->fields['idcuenta'], "-");
								if($pos){
									$ctas = explode("-",$query->fields['idcuenta']);
									if(count($ctas)>2){ $cta=$ctas[0]."-".$ctas[1]; } else { $cta=$ctas[0]; }
								}
					if($query->fields['impcomp']==0){$impcomp="";}else{$impcomp=$query->fields['impcomp'];}
					if($query->fields['feccomp']=="0000-00-00"){$feccomp="";}else{$feccomp=$query->fields['feccomp'];}
						
					$coddpto=$query->fields['coddpto'];
					$dpto=$db->Execute("SELECT nombre,coddpto FROM ubigeos WHERE codprov=00 AND coddist=00 AND coddpto='$coddpto'");
					$dpt=$dpto->fields['nombre'];
					
					$codprov=$query->fields['codprov'];
					$prov=$db->Execute("SELECT nombre,codprov FROM ubigeos WHERE codprov='$codprov' AND coddist=00 AND coddpto='$coddpto'");
					$provi=$prov->fields['nombre'];
					
					$coddist=$query->fields['coddist'];
					$dist=$db->Execute("SELECT nombre,coddist FROM ubigeos WHERE codprov='$codprov' AND coddist='$coddist' AND coddpto='$coddpto'");
					$distr=$dist->fields['nombre'];
					
					$idagente=$query->fields['idagente'];
					$agnte=$db->Execute("SELECT usuario FROM usuarios WHERE idusuario='$idagente'");
					$agente=$agnte->fields['usuario'];
					
					$validacion=$query->fields['validaciones'];
					if($validacion==""){ $validacion="No Validado";}
					
						$cont="=\"".$query->fields['idcliente']."\"\t";
						$cont.=$query->fields['cliente']."\t";
						$cont.="=\"".$cta."\"\t";
						$cont.=$query->fields['monedas']."\t";
						$cont.=$query->fields['actividad']."\t";
						$cont.=$query->fields['proveedor']."\t";
						$cont.=$query->fields['cartera']."\t";
						$cont.=$query->fields['producto']."\t";
						$cont.=$query->fields['fecges']."\t";
						$cont.=$query->fields['horges']."\t";
						$cont.=$validacion."\t";
						$cont.=$query->fields['direccion']."\t";
								$cdr=explode("*",$query->fields['observacion']);
						$cont.=$cdr[4]."\t";
						$cont.=$dpt."-".$provi."-".$distr."\t";
						$cont.=$query->fields['origendireccion']."\t";
						$cont.=$query->fields['fuente']."\t";
						$cont.=$query->fields['tipo_predio']."\t";
						$cont.=$query->fields['material']."\t";
						$cont.=$query->fields['piso']."\t";
						$cont.=$query->fields['color']."\t";
						$cont.=$query->fields['tipocontactabilidad']."\t";
						$cont.=$query->fields['contactabilidad']."\t";
						$cont.=$query->fields['grupogestion']."\t";
						$cont.=$query->fields['resultado']."\t";
						$cont.="\t";
						$cont.="\t";
						$cont.="\t";
									$obs=str_replace("."," ",$query->fields['observacion']);
									$obs=str_replace("\n"," ",$query->fields['observacion']);
						//$obs=substr($obs, 0, 60);
						$cont.=$obs." \t";
						//$cont.=$query->fields['observacion']."\t";
						$cont.=$r_hora."\t";
						$cont.="\t";
						$cont.=$agente."\t";
						$cont.=$query->fields['fechad']."\t";
						$cont.=$query->fields['horad']."\t";
						$cont.=$r_hora_d."\t";
						$cont.=$query->fields['negociador']."\t";
						$cont.=$query->fields['peso']."\t";
						;
						fwrite($fp , $cont);
						fwrite($fp , chr(13).chr(10));
						$query->MoveNext();
										}
					fclose($fp);
					echo "Foto Cartera:<a href='guardar_como.php?name=f_digit.txt' target='blank'>Click para descargar</a><br/>";		

		
		//mysql_free_result($query->_queryID);
		$db->Execute("update flag_reportes set flag='0' where reporte='f_digit'");
		$db->Close();

?>
