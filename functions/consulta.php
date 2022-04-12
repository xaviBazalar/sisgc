<?php
include '../scripts/conexion.php';

ini_set('memory_limit', '-1');
set_time_limit(1800);
if(!isset($_SESSION)){
		session_start();
	}
$id = $_SESSION['nivel'];
$id_u=$_SESSION['iduser'];
$id_cartera=$_SESSION['cartera'];
$periodo = $_SESSION['periodo'];

if($_SESSION['user']=='mtorresf'){
	$_SESSION['prove']=$_GET['prov'];
}
if(isset($_GET['prov']) and $_GET['prov']!=""){
	$_SESSION['prove']=$_GET['prov'];
}

if(isset($_GET['cart']) and $_GET['cart']!=""){
$_SESSION['cartera']=$_GET['cart'];

}



//$db->debug=true;
/*$fecha_x_per=$db->Execute("Select YEAR(fecini) anop,MONTH(fecini) mesp from periodos where idperiodo='$periodo'");

$año_p=$fecha_x_per->fields['anop'];
$mes_p=$fecha_x_per->fields['mesp'];
*/
$año_p=$_SESSION['ano_p'];
$mes_p=$_SESSION['mes_p'];
				

$pag=0;
$r_pag=20;
		if(isset($_GET['r_cart']) and $_GET['r_cart']!="" ){
			$ag="JOIN gestiones gt ON cp.idcuenta=gt.idcuenta AND YEAR(gt.fecges)='".date("Y")."' AND MONTH(gt.fecges)='".date("m")."'";
		}else{
			$ag="";
		}
		
		if ($_SESSION['tnivel']==2){$cp_es="and cp.idestado='1'";}else{ $cp_es="";}
		$sql="SELECT 	cu.obs2 ,cp.observacion2,cu.idmoneda,cu.u_fecges,cp.idperiodo,cu.idusuario,c.idcliente,pv.proveedor,c.cliente,cu.idcuenta,cp.diasmora,
						cp.grupo,cp.imptot,cp.ciclo,cp.idestado,cp.impmor FROM cuentas cu
						JOIN clientes c ON cu.idcliente=c.idcliente
						
						JOIN carteras cr ON cu.idcartera=cr.idcartera
						JOIN proveedores pv ON pv.idproveedor=cr.idproveedor
						JOIN cuenta_periodos cp ON cp.idcuenta=cu.idcuenta $cp_es
						$ag					
						";

		
		if ($_SESSION['tnivel']==1 or $_SESSION['tnivel']==4){
			$sql.=" WHERE cu.idusuario!=0 and cp.idperiodo='$periodo'  ";
		}else if($_SESSION['tnivel']!=5){
			//if($_SESSION['prove']!=2){$cdr="AND cu.idcartera='$id_cartera'";}else{$cdr="";}
			$sql.=" WHERE cp.idusuario=$id_u and cp.idperiodo='$periodo' $cdr";
			//$cs=$db->Execute($sql);
			//echo $cs->fields['t_ge'];
			//$sql.=" WHERE cp.idusuario=$id_u and cp.idperiodo='$periodo' ";
		}else{
			//$sql.="WHERE cr.idcartera='$id_cartera' ";
			$sql.=" WHERE cu.idusuario!=0 and cp.idperiodo='$periodo' ";
		}
		
		//echo $sql;
	
		$conta="SELECT  COUNT(cu.idusuario) FROM clientes c
						JOIN doi d ON c.iddoi=d.iddoi
						JOIN personerias p ON p.idpersoneria=c.idpersoneria
						JOIN cuentas cu ON cu.idcliente=c.idcliente
						JOIN monedas m ON m.idmoneda=cu.idmoneda
						JOIN carteras cr ON cr.idcartera=cu.idcartera
						JOIN proveedores pv ON cr.idproveedor=pv.idproveedor
						JOIN cuenta_periodos cp ON cp.idcuenta=cu.idcuenta and cp.idestado='1'
						WHERE cp.idusuario='$id'  " ;	
		$ord_ig="ASC";
		
		$flag_next=0;	
		
		if(isset($_GET['tipo_cartera'])){
			$tpcrt=$_GET['tipo_cartera'];
			$sql.="AND cu.idtipocartera='$tpcrt' ";
			$fil_tpc="AND c.idtipocartera='$tpcrt' ";
		}
		
		if(isset($_GET['ndoc'])){
			$ndoc=$_GET['ndoc'];
			$sql.="AND c.idcliente='$ndoc'";
			$ord_ig="DESC";
		}
		
		if(isset($_GET['r_cart'])){
			$r_cart=$_GET['r_cart'];
			$sql.="AND gt.idresultado='$r_cart'";
			
			$flag_next=1;
		}else{
			//if(!isset($_GET['grup']) and !isset($_GET['ciclo'])){
					/*if($cs->fields['t_ge']!=0){
						$ord_g="idgestion $ord_ig,";
					}*/	
			//}
			unset($_SESSION['dni_rs']);
		}
		
		if(isset($_GET['name'])){
			$name=$_GET['name'];
			$idcl=$db->Execute("SELECT cl.idcliente FROM clientes cl  join cuentas c on cl.idcliente=c.idcliente WHERE cl.cliente LIKE '%$name%' and c.idcartera=$id_cartera ");
			$cli_id="";	
			while(!$idcl->EOF){
				$cli_id.="'".$idcl->fields['idcliente']."',";
				$idcl->MoveNext();
			}
			$cli_id=substr($cli_id, 0, -1);
			//$sql.="AND c.cliente like '%$name%'";
			$sql.="AND c.idcliente in ($cli_id)";
			//$sql.="AND c.cliente like '%$name%'";
		}
		
		$flag_grp_next=0;	
		if(isset($_GET['grup'])){
			$grup=$_GET['grup'];
			$sql.="AND cp.grupo like '%$grup%'";
				$flag_grp_next=1;
			$_SESSION['grupo']=$grup;
		}else{
			$_SESSION['grupo']="";
		}
		
		$flag_ciclo_next=0;
		if(isset($_GET['ciclo'])){
			$ciclo=$_GET['ciclo'];
			$sql.="AND cp.ciclo LIKE '%$ciclo%'";
			$flag_ciclo_next=1;
			$_SESSION['ciclo']=$ciclo;
		}else{
			$_SESSION['ciclo']="";
		}

		if(isset($_GET['clasificacion'])){
			$clasificacion=$_GET['clasificacion'];
			$sql.="AND cu.obs2 LIKE '$clasificacion%'";
			//$flag_ciclo_next=1;
			//$_SESSION['ciclo']=$ciclo;
		}else{
			//$_SESSION['ciclo']="";
		}

		if(isset($_GET['riesgo'])){
			$riesgo=$_GET['riesgo'];
			$sql.="AND cp.observacion2 LIKE '%$riesgo%'";
			//$flag_ciclo_next=1;
			//$_SESSION['ciclo']=$ciclo;
		}else{
			//$_SESSION['ciclo']="";
		}
		

		

		if(isset($_GET['prov']) and !isset($_GET['cart'])){
			$prov=$_GET['prov'];
			$sql.="AND pv.idproveedor='$prov'";
			
			if($_SESSION['tnivel']==1 or $_SESSION['tnivel']==5){
				$_SESSION['prove']=$prov;
			}
			
		}
		
		if(isset($_GET['cart'])){
			$cart=$_GET['cart'];
			$sql.="AND cr.idcartera='$cart'";
			
			$_SESSION['cartera']=$cart;
		}else if(!isset($_GET['cart']) and !isset($_GET['prov'])){
			if($_SESSION['nivel']==2){
				$cart=$_SESSION['cartera'];
				$sql.="AND cr.idcartera='$cart'";
			}
			
		}
		
		

		if(isset($_GET['ord_cart'])){/*Ordenar por Importe Total*/
			$ord_cp=$_GET['ord_cart'];
			if($_GET['ord_cart']=="ASC"){
				$ord_cp=" cp.imptot ASC";
			}else{
				$ord_cp=" cp.imptot DESC";
			}
		}else{
				$ord_cp=" cp.imptot DESC";
		}
		
		if(isset($_GET['ord_diasmor']) and !isset($_GET['ord_cart'])){/*Ordenar por Dias Mora*/
			$ord_cp=$_GET['ord_diasmor'];
			//echo $ord_cp;
			if($_GET['ord_diasmor']=="ASC"){
				$ord_cp=" cp.diasmora ASC";
			}else{
				$ord_cp=" cp.diasmora DESC";
			}
		}/*else{
				$ord_cp=" cp.diasmora DESC";
		}*/

		if(isset($_GET['ord_grup']) and !isset($_GET['ord_cart']) and !isset($_GET['ord_diasmor'])){/*Ordenar por Grupo*/
			$ord_cp=$_GET['grup'];
			//echo $ord_cp;
			if($_GET['ord_grup']=="ASC"){
				$ord_cp=" cp.grupo ASC";
			}else{
				$ord_cp=" cp.grupo DESC";
			}
		}/*else{
				$ord_cp=" cp.grupo DESC";
		}*/

		if(isset($_GET['ord_ciclo']) and !isset($_GET['ord_cart']) and !isset($_GET['ord_diasmor']) and !isset($_GET['ord_grup'])){/*Ordenar por Ciclo*/
			$ord_cp=$_GET['ciclo'];
			//echo $ord_cp;
			if($_GET['ord_ciclo']=="ASC"){
				$ord_cp=" cp.ciclo ASC";
			}else{
				$ord_cp=" cp.ciclo DESC";
			}
		}/*else{
				$ord_cp=" cp.ciclo DESC";
		}*/

		if(isset($_GET['ord_clas'])){/*Ordenar por Clasificacion*/
			$ord_clas=$_GET['ord_clas'];
			if($_GET['ord_clas']=="ASC"){
				$ord_cp=" cu.obs2 ASC";
			}else{
				$ord_cp=" cu.obs2 DESC";
			}
		}

		if(isset($_GET['ord_ries'])){/*Ordenar por Riesgo*/
			$ord_ries=$_GET['ord_ries'];
			if($_GET['ord_ries']=="ASC"){
				$ord_cp=" cp.observacion2 ASC";
			}else{
				$ord_cp=" cp.observacion2 DESC";
			}
		}

		if(isset($_GET['ord_incre'])){/*Ordenar por Clasificacion*/
			$ord_incre=$_GET['ord_incre'];
			if($_GET['ord_incre']=="ASC"){
				$ord_cp=" CONVERT(SUBSTRING_INDEX(SUBSTRING_INDEX( cu.obs2 , '*', -2 ),'*',1),SIGNED) ASC";
			}else{
				$ord_cp=" CONVERT(SUBSTRING_INDEX(SUBSTRING_INDEX( cu.obs2 , '*', -2 ),'*',1),SIGNED) DESC";
			}
		}


	 
	$fecha=date("Y-m-d");
	$sql2=$sql;
	$sql2.=" AND gt.feccomp='$fecha' AND cp.grupo!='0' $cp_es";
	$sql3=$sql;
	//$sql3.=" and gt.idgestion is null";
	
	//$fla =$db->Execute($sql3);
	
	if($_SESSION['tnivel']!=4){ $order_b="/*GROUP BY cu.idcuenta*/ ORDER BY /*cu.u_fecges,idestado ASC,*/$ord_g  $ord_cp";}else{$ordet_b="";}
	//if($fla->fields['idperiodo']==""){
		//$sql.=" and cp.idestado='1' GROUP BY cu.idcuenta ORDER BY idestado DESC, cp.imptot $ord_cp  ";
		$sql.=" $cp_es $order_b ";
		//$sql.=" and cp.idestado='1' GROUP BY cu.idcuenta ORDER BY idestado DESC,$ord_g cp.imptot $ord_cp,idagente DESC, gt.fecreg DESC";
	/*}else{
		$sql.=" $cp_es $order_b ";
	
	}*/
	
	//echo $sql;

	//if($_SERVER['REMOTE_ADDR']=="172.23.218.243"){echo $sql;}
	
	if($_SESSION['tnivel']==2){
		
		$sqt_c_tot="
				SELECT COUNT(*) total FROM cuentas c
					JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta AND cp.idperiodo=$periodo AND cp.idestado=1
					WHERE cp.idusuario=$id_u
					AND c.idcartera=$id_cartera
					";
		if(isset($_GET['tipo_cartera'])){
			$tpcrt=$_GET['tipo_cartera'];
			$sqt_c_tot.="AND c.idtipocartera='$tpcrt' ";
		}
		$total =$db->Execute($sqt_c_tot);
		$t_regist=$total->fields['total'];		
					
	}else{
		$total =$db->Execute($sql);
		$t_regist=$total->_numOfRows;
	}
	/*if($t_regist=="0"){
		$total =$db->Execute($sql);
		$t_regist=$total->_numOfRows;
	}*/
	//echo $t_regist;
	
	if(isset($_GET['pag'])){
		$pag=$_GET['pag'];
		$pag=$pag-1;
		$pag=$pag*$r_pag;
		$sql.=" LIMIT $pag,$r_pag";
	}else{
		$sql.=" LIMIT $pag,$r_pag";
	}
	
	//if($_SERVER['REMOTE_ADDR']=="192.168.50.44"){echo $sql;}
	//echo $sql;
	
	//if($t_regist=="0"){
		$consulta =$db->Execute($sql);
	//}else{
		//$consulta =$db->Execute($sql2);
	//}
	

	$t_p=$t_regist/$r_pag;

	$z=0;
	//echo $_SESSION['web'];
	//echo "<pre>";
	//echo count($_SESSION['dni']);
	
	if(isset($_SESSION['dni']) and $flag_next==1 and $flag_grp_next!=1 and $flag_ciclo_next!=1){
		
			$r_cart=$_GET['r_cart'];
			$sql="AND gt.idresultado='$r_cart'";
		
		$but_next=$db->Execute("
				SELECT c.idcliente,c.cliente
										FROM cuentas cu 
										JOIN clientes c ON c.idcliente=cu.idcliente 
										JOIN carteras cr ON cr.idcartera=cu.idcartera 
										JOIN cuenta_periodos cp ON cp.idcuenta=cu.idcuenta  and cp.idestado='1'
										JOIN gestiones gt ON cp.idcuenta=gt.idcuenta AND YEAR(gt.fecges)='".date("Y")."' AND MONTH(gt.fecreg)='".date("m")."' AND MONTH(gt.fecges)='".date("m")."' 
										WHERE cp.idusuario='$id_u' AND cp.idperiodo='$periodo' AND cu.idcartera='$id_cartera' AND cp.idestado='1' $sql
										GROUP BY cu.idcuenta ORDER BY cp.idestado DESC,cu.u_fecges ASC,idagente DESC, cp.imptot DESC");
				
		while(!$but_next->EOF){
			++$z;
			$id_cli_n=$but_next->fields['idcliente'];
			if($z>1){
				$key = array_search($id_cli_n, $_SESSION['dni_rs']);
				if($key!=""){
					$but_next->MoveNext();
					--$z;
					continue;
				}
			}
			$_SESSION['dni_rs'][$z]="$id_cli_n";
			$but_next->MoveNext();
		}
	}
	
	if(isset($_SESSION['dni']) and $flag_grp_next==1 and $flag_ciclo_next!=1 ){
		
			$grup=$_GET['grup'];
			$sql="AND cp.grupo='$grup'";
		//$db->debug=true;
		$but_next=$db->Execute("
				SELECT c.idcliente,c.cliente
										FROM cuentas cu 
										JOIN clientes c ON c.idcliente=cu.idcliente 
										JOIN carteras cr ON cr.idcartera=cu.idcartera 
										JOIN cuenta_periodos cp ON cp.idcuenta=cu.idcuenta  and cp.idestado='1'
										$ag
										WHERE cp.idusuario='$id_u' AND cp.idperiodo='$periodo' AND cu.idcartera='$id_cartera' AND cp.idestado='1' $sql
										GROUP BY cu.idcuenta ORDER BY cp.idestado DESC,cu.u_fecges  ASC, cp.imptot DESC ");
			//$db->debug=false;
		while(!$but_next->EOF){
			++$z;
			$id_cli_n=$but_next->fields['idcliente'];
			if($z>1){
				$key = array_search($id_cli_n, $_SESSION['dni_rs']);
				if($key!=""){
					$but_next->MoveNext();
					--$z;
					continue;
				}
			}
			$_SESSION['dni_rs'][$z]="$id_cli_n";
			$but_next->MoveNext();
		}
	}
	
	
	if(isset($_SESSION['dni']) and $flag_ciclo_next==1 and $flag_grp_next!=1){
		
			$ciclo=$_GET['ciclo'];
			$sql="AND cp.ciclo LIKE '%$ciclo%'";
		//$db->debug=true;
		$but_next=$db->Execute("
				SELECT c.idcliente,c.cliente
										FROM cuentas cu 
										JOIN clientes c ON c.idcliente=cu.idcliente 
										JOIN carteras cr ON cr.idcartera=cu.idcartera 
										JOIN cuenta_periodos cp ON cp.idcuenta=cu.idcuenta  and cp.idestado='1'
										$ag
										WHERE cp.idusuario='$id_u' AND cp.idperiodo='$periodo' AND cu.idcartera='$id_cartera' AND cp.idestado='1' $sql
										GROUP BY cu.idcuenta ORDER BY cp.idestado DESC,cu.u_fecges ASC,cp.imptot DESC ");
			//$db->debug=false;
		while(!$but_next->EOF){
			++$z;
			$id_cli_n=$but_next->fields['idcliente'];
			if($z>1){
				$key = array_search($id_cli_n, $_SESSION['dni_rs']);
				if($key!=""){
					$but_next->MoveNext();
					--$z;
					continue;
				}
			}
			$_SESSION['dni_rs'][$z]="$id_cli_n";
			$but_next->MoveNext();
		}
	}
	
	if(isset($_SESSION['dni']) and $flag_ciclo_next==1 and $flag_grp_next==1){
		
			$ciclo=$_GET['ciclo'];
			$sql="AND cp.ciclo LIKE '%$ciclo%' ";
			
			$grup=$_GET['grup'];
			$sql.="AND cp.grupo='$grup'";
			
		//$db->debug=true;
		$but_next=$db->Execute("
				SELECT c.idcliente,c.cliente
										FROM cuentas cu 
										JOIN clientes c ON c.idcliente=cu.idcliente 
										JOIN carteras cr ON cr.idcartera=cu.idcartera 
										JOIN cuenta_periodos cp ON cp.idcuenta=cu.idcuenta  and cp.idestado='1'
										$ag
										WHERE cp.idusuario='$id_u' AND cp.idperiodo='$periodo' AND cu.idcartera='$id_cartera' AND cp.idestado='1' $sql
										GROUP BY cu.idcuenta ORDER BY cp.idestado DESC,cu.u_fecges ASC,cp.imptot DESC ");
			//$db->debug=false;
		while(!$but_next->EOF){
			++$z;
			$id_cli_n=$but_next->fields['idcliente'];
			if($z>1){
				$key = array_search($id_cli_n, $_SESSION['dni_rs']);
				if($key!=""){
					$but_next->MoveNext();
					--$z;
					continue;
				}
			}
			$_SESSION['dni_rs'][$z]="$id_cli_n";
			$but_next->MoveNext();
		}
	}
	



	
?>
<table border="0" id="design1" width="100%">
				<thead>
					<tr>
                        <td colspan="13">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b> Pagina <?php echo $_GET['pag']; ?> </b>
                        </td>
                    </tr>
					<tr>
                        <th>Nº</th>
                        <th><a href=''>N° Documento</a> </th>
						<th><a href=''>Cliente</a> </th>
                        <th><a href=''>Proveedor.</a> </th>
                        <th><a href=''>Moneda</a> </th>
						<?php
							if($_GET['ord_cart']=="DESC"){$ord_cp="ASC";}else{$ord_cp="DESC";}
							if($_GET['ord_diasmor']=="DESC"){$ord_dm="ASC";}else{$ord_dm="DESC";}
							if($_GET['ord_grup']=="DESC"){$ord_gr="ASC";}else{$ord_gr="DESC";}
							if($_GET['ord_ciclo']=="DESC"){$ord_cl="ASC";}else{$ord_cl="DESC";}
							if($_GET['ord_clas']=="DESC"){$ord_clas="ASC";}else{$ord_clas="DESC";}
							if($_GET['ord_ries']=="DESC"){$ord_ries="ASC";}else{$ord_ries="DESC";}
							if($_GET['ord_incre']=="DESC"){$ord_incre="ASC";}else{$ord_incre="DESC";}


						?>
                        <th><a href="javascript:buscar('functions/consulta.php?id=&pag=1&ord_cart=<?php echo $ord_cp;?>');">Capital Total</a> </th>
                        <th><a href="javascript:buscar('functions/consulta.php?id=&pag=1&ord_diasmor=<?php echo $ord_dm;?>');">D&iacute;as Mora</a> </th>
                        <th><a href="javascript:buscar('functions/consulta.php?id=&pag=1&ord_grup=<?php echo $ord_gr;?>');">Grupo</a> </th>
						<th><a href="javascript:buscar('functions/consulta.php?id=&pag=1&ord_ciclo=<?php echo $ord_cl;?>');">Ciclo</a> </th>
						<th><a href="javascript:buscar('functions/consulta.php?id=&pag=1&ord_incre=<?php echo $ord_incre;?>');">Incremental</a></th>
						<th><a href="javascript:buscar('functions/consulta.php?id=&pag=1&ord_clas=<?php echo $ord_clas;?>');">Clasificacion</a></th>
						<th><a href="javascript:buscar('functions/consulta.php?id=&pag=1&ord_ries=<?php echo $ord_ries;?>');">Riesgo</a></th>
						<th><a href=''>Fec.Ultima Gestion</a> </th>
                    </tr>
				</thead>
				<tbody>
                        <?php
						
                        $n=0;//Nro de Registros
						if($_GET['pag']!=1){
							$n=$_GET['pag']-1;
							$n=$n*$r_pag;
						}
							
							
                        while(!$consulta->EOF){
                            $s = ++$n;
							
							if($consulta->fields['observacion2']=="1"){$riesgo="Riesgo Alto";}
							if($consulta->fields['observacion2']=="2"){$riesgo="Riesgo Medio";}
							if($consulta->fields['observacion2']=="3"){$riesgo="Riesgo Bajo";}
							
							$impmor=number_format($consulta->fields['impmor'],2,'.',',');
							$tr_mora=explode("*",$consulta->fields['obs2']);
							$fecha_pago=substr($tr_mora[2],0,4)."-".substr($tr_mora[2],4,2)."-".substr($tr_mora[2],6);
							$d_mora=$db->Execute("SELECT DATEDIFF(DATE(NOW()),'$fecha_pago') diasmora_fc");
							$cta_id=$consulta->fields['idcuenta'];
							//$db->debug=true;
							$fecs=explode("-",$consulta->fields['u_fecges']);
							$fech=$fecs[0]."-".$fecs[1];
							//$valor=$db->Execute("select idgestion from gestiones where idcuenta='$cta_id' and year(fecges)='$año_p' and month(fecges)='$mes_p'");
							if($consulta->fields['idestado']=='0'){	
								echo "<tr onMouseOver=\"this.className=''\" onMouseOut=\"this.className=''\" style='background-color:#87CEFA;'>";
							}else if($fech==Date("Y-m")){
								echo "<tr onmouseover=\"this.className='rowshover'\" onmouseout=\"this.className='gris'\" class=\"gris\">";
							}else{
								echo "<tr onMouseOver=\"this.className='rowshover'\" onMouseOut=\"this.className='rows2'\" class=\"rows1\">";
							}
							echo"<td>$s</td>";
								if($_SESSION['tnivel']=="2" or $_SESSION['tnivel']=="3"){
									echo "<td align='center'><a href='index.php?gestion=1&idCl=".$consulta->fields['idcliente']."&mn=$s";
									echo "'>".$consulta->fields['idcliente']."</a></td>";
									$cliente=$consulta->fields['idcliente'];
								}else{
									echo "<td align='center'><a href='index.php?gestion_c=1&idCl=".$consulta->fields['idcliente']."'>".$consulta->fields['idcliente']."</a></td>";
								}
                            echo "<td>".utf8_encode($consulta->fields['cliente'])."</td>";
							echo "<td>".$consulta->fields['proveedor']."</td>";
							if($consulta->fields['idmoneda']==1){$moneda="Soles";}else if($consulta->fields['idmoneda']==2){$moneda="Dolares";}
                            echo "<td>".$moneda."</td>";
							$sp= explode(".",$consulta->fields['imptot']);
																		$tot_impt=strlen($sp[0]);
																		if($tot_impt>=4){
																			$imptot=substr_replace($sp[0],',',-3,0).".".$sp[1];
																		}else{
																			$imptot=$consulta->fields['imptot'];
																		}	
                            echo "<td align='center'>".$imptot."</td>";
							
                            echo "<td>".$d_mora->fields['diasmora_fc']."</td>";
                            echo "<td>".$consulta->fields['grupo']."</td>";
							echo "<td>".$consulta->fields['ciclo']."</td>";
							
							echo "<td>".$tr_mora[1]."</td>";
							echo "<td>".$tr_mora[0]."</td>";
							echo "<td>".$riesgo."</td>";
							echo "<td align='center'>".$consulta->fields['u_fecges']."</td></tr>";
                            $consulta->MoveNext();
								$_SESSION['next'][$s]=$consulta->fields['idcliente'];
                        }
                        ?>
				</tbody>
				<tfoot>
						<tr>
								<?php
									$t_p=$t_regist/$r_pag;
									$Res=$t_regist%$r_pag;
									if($Res>0) $t_p=floor($t_p)+1;
										$Ant=$_GET['pag']-1;
									    $Sig=$_GET['pag']+1;
								?>
			
							<td colspan="10">
									<?php
								if($_SESSION['tnivel']){
										if($_GET['pag']!=1 )
											echo "<a onclick=\"buscar('functions/consulta.php?id=&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp; <a onclick=\"buscar('functions/consulta.php?id=&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
										else
											echo "Inicio &nbsp;Anterior&nbsp;----";
									
										for($i=$_GET['pag'];$i<=$t_p;$i++){
											
											if($i==$_GET['pag']){
											
												echo "$i&nbsp;&nbsp;";
												
												
											}else{
												echo "<a onclick=\"buscar('functions/consulta.php?id=&pag=$i');\" href='#'>$i</a>&nbsp;&nbsp;";
											}
											$resto=$i/20;
											
											/*if(is_int($resto))
												break;*/
												
											if($i==($_GET['pag']+20))
												break;	
													//echo "<br/>";
										}
									
										if($_GET['pag']!=$t_p && $t_p!=0)
												echo "<a onclick=\"buscar('functions/consulta.php?id=&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"buscar('functions/consulta.php?id=&pag=$t_p');\" href='#'>Fin</a> " ;
											else
												echo "&nbsp;Siguiente&nbsp; Fin";
										
									
									
									}else{
									if($_GET['pag']!=1 )
											echo "<a onclick=\"buscar('functions/consulta.php?id=&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp; <a onclick=\"buscar('functions/consulta.php?id=&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
										else
											echo "Inicio &nbsp;Anterior&nbsp;----";
									
									for($i=1;$i<=$t_p;$i++){
										
										if($i==$_GET['pag']){
										
											echo "$i&nbsp;&nbsp;";
											
											
										}else{
											echo "<a onclick=\"buscar('functions/consulta.php?id=&pag=$i');\" href='#'>$i</a>&nbsp;&nbsp;";
										}
										$resto=$i/50;
										if(is_int($resto))
												echo "<br/>";
									}
									
									if($_GET['pag']!=$t_p && $t_p!=0)
											echo "<a onclick=\"buscar('functions/consulta.php?id=&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"buscar('functions/consulta.php?id=&pag=$t_p');\" href='#'>Fin</a> " ;
										else
											echo "&nbsp;Siguiente&nbsp; Fin";
									
									}		
									?>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
								
							</td>
						</tr>
				</tfoot>
			</table>
<?php $db->Close(); ?>