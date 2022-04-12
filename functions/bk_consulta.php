<?php
include '../scripts/conexion.php';
if(!isset($_SESSION)){
		session_start();
	}
$id = $_SESSION['nivel'];
$id_u=$_SESSION['iduser'];
$id_cartera=$_SESSION['cartera'];
$periodo = $_SESSION['periodo'];
//$db->debug=true;
$fecha_x_per=$db->Execute("Select YEAR(fecini) anop,MONTH(fecini) mesp from periodos where idperiodo='$periodo'");

$año_p=$fecha_x_per->fields['anop'];
$mes_p=$fecha_x_per->fields['mesp'];

mysql_free_result($fecha_x_per->_queryID);	
$pag=0;
$r_pag=20;
		$sql="SELECT MAX(gt.fecges) mx_g,SUM(gt.idgestion IS NULL) t_ge,cp.idperiodo,YEAR(gt.fecges) ano,MONTH(gt.fecges) mes,gt.idgestion,cu.idusuario,c.idcliente,pv.proveedor,cr.cartera,pr.producto,m.monedas,c.cliente,c.observacion,cu.idcuenta,cp.diasmora,cp.grupo,cp.imptot,cp.ciclo,cp.idestado FROM cuentas cu
						JOIN clientes c ON cu.idcliente=c.idcliente
						JOIN monedas m ON cu.idmoneda=m.idmoneda
						JOIN carteras cr ON cu.idcartera=cr.idcartera
						JOIN proveedores pv ON pv.idproveedor=cr.idproveedor
						JOIN productos pr ON pr.idproducto=cu.idproducto			
						JOIN cuenta_periodos cp ON cp.idcuenta=cu.idcuenta and cp.idestado='1'
						LEFT JOIN gestiones gt ON cp.idcuenta=gt.idcuenta AND MONTH(gt.fecreg)='$mes_p' AND MONTH(gt.fecges)='$mes_p'
						
						";
						
		if ($_SESSION['tnivel']==1){
			$sql.=" WHERE cu.idusuario!=0 and cp.idperiodo='$periodo'  ";
		}else if($_SESSION['tnivel']!=5){
			if($_SESSION['prove']!=2){$cdr="AND cu.idcartera='$id_cartera'";}else{$cdr="";}
			$sql.=" WHERE cp.idusuario=$id_u and cp.idperiodo='$periodo' $cdr";
			$cs=$db->Execute($sql);
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
						JOIN productos pr ON pr.idproveedor=pv.idproveedor			
						JOIN cuenta_periodos cp ON cp.idcuenta=cu.idcuenta and cp.idestado='1'
						WHERE cp.idusuario='$id'  " ;	
		$ord_ig="ASC";
		
		$flag_next=0;		
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
					if($cs->fields['t_ge']!=0){
						$ord_g="idgestion $ord_ig,";
						
					}	
			//}
			
			unset($_SESSION['dni_rs']);
		}
		
		if(isset($_GET['name'])){
			$name=$_GET['name'];
			$sql.="AND c.cliente like '%$name%'";
		}
		$flag_grp_next=0;	
		if(isset($_GET['grup'])){
			$grup=$_GET['grup'];
			$sql.="AND cp.grupo='$grup'";
				$flag_grp_next=1;
			$_SESSION['grupo']=$grup;
		}else{
			$_SESSION['grupo']="";
		}
		
		if(isset($_GET['ciclo'])){
			$ciclo=$_GET['ciclo'];
			$sql.="AND cp.ciclo LIKE '%$ciclo%'";
		
			$_SESSION['ciclo']=$ciclo;
		}else{
			$_SESSION['ciclo']="";
		}
	
		if(isset($_GET['prov'])){
			$prov=$_GET['prov'];
			$sql.="AND pv.idproveedor='$prov'";

			if($_SESSION['tnivel']==1 or $_SESSION['tnivel']==5){
				$_SESSION['prove']=$prov;
			}
		}
		
		if(isset($_GET['cart'])){
			$cart=$_GET['cart'];
			$sql.="AND cr.idcartera='$cart'";
		}
		
		if(isset($_GET['ord_cart'])){
			$ord_cp=$_GET['ord_cart'];
			if($_GET['ord_cart']=="ASC"){
				$ord_cp="ASC";
			}else{
				$ord_cp="DESC";
			}
		}else{
				$ord_cp="DESC";
		}
	$fecha=date("Y-m-d");
	$sql2=$sql;
	$sql2.=" AND gt.feccomp='$fecha' AND cp.grupo!='0' and cp.idestado='1'";
	$sql3=$sql;
	$sql3.=" and gt.idgestion is null";
	$fla =$db->Execute($sql3);
	if($fla->fields['idperiodo']==""){
		//$sql.=" and cp.idestado='1' GROUP BY cu.idcuenta ORDER BY idestado DESC, cp.imptot $ord_cp ";
		$sql.=" and cp.idestado='1' GROUP BY cu.idcuenta ORDER BY idestado DESC,$ord_g cp.imptot $ord_cp,idagente DESC, gt.fecreg DESC";
	}else{
		$sql.=" and cp.idestado='1' GROUP BY cu.idcuenta ORDER BY idestado DESC,$ord_g cp.imptot $ord_cp,idagente DESC,  gt.fecreg DESC";
	}
	
	//	echo $sql;

	
	$total =$db->Execute($sql);
	
	$t_regist=$total->_numOfRows;
	
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
	
	if(isset($_SESSION['dni']) and $flag_next==1 and $flag_grp_next!=1){
		
			$r_cart=$_GET['r_cart'];
			$sql="AND gt.idresultado='$r_cart'";
		
		$but_next=$db->Execute("
				SELECT c.idcliente,c.cliente
										FROM cuentas cu 
										JOIN clientes c ON c.idcliente=cu.idcliente 
										JOIN carteras cr ON cr.idcartera=cu.idcartera 
										JOIN cuenta_periodos cp ON cp.idcuenta=cu.idcuenta  and cp.idestado='1'
										LEFT JOIN gestiones gt ON cp.idcuenta=gt.idcuenta AND YEAR(gt.fecges)='".date("Y")."' AND MONTH(gt.fecreg)='".date("m")."' AND MONTH(gt.fecges)='".date("m")."' 
										WHERE cp.idusuario='$id_u' AND cp.idperiodo='$periodo' AND cu.idcartera='$id_cartera' AND cp.idestado='1' $sql
										GROUP BY cu.idcuenta ORDER BY cp.idestado DESC,idgestion ASC,idagente DESC, cp.imptot DESC ,gt.fecreg DESC");
				
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
		mysql_free_result($but_next->_queryID);	
	}
	
	if(isset($_SESSION['dni']) and $flag_grp_next==1){
		
			$grup=$_GET['grup'];
			$sql="AND cp.grupo='$grup'";
		//$db->debug=true;
		$but_next=$db->Execute("
				SELECT c.idcliente,c.cliente
										FROM cuentas cu 
										JOIN clientes c ON c.idcliente=cu.idcliente 
										JOIN carteras cr ON cr.idcartera=cu.idcartera 
										JOIN cuenta_periodos cp ON cp.idcuenta=cu.idcuenta  and cp.idestado='1'
										LEFT JOIN gestiones gt ON cp.idcuenta=gt.idcuenta AND YEAR(gt.fecges)='".date("Y")."' AND MONTH(gt.fecreg)='".date("m")."' AND MONTH(gt.fecges)='".date("m")."' 
										WHERE cp.idusuario='$id_u' AND cp.idperiodo='$periodo' AND cu.idcartera='$id_cartera' AND cp.idestado='1' $sql
										GROUP BY cu.idcuenta ORDER BY cp.idestado DESC,idgestion ASC,idagente DESC, cp.imptot DESC ,gt.fecreg DESC");
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
		mysql_free_result($but_next->_queryID);	
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
							if($_GET['ord_cart']=="DESC"){
								$ord_cp="ASC";
							}else{
								$ord_cp="DESC";
							}
						?>
                        <th><a href="javascript:buscar('functions/consulta.php?id=&pag=1&ord_cart=<?php echo $ord_cp;?>');">Capital Total</a> </th>
                        <th><a href=''>D&iacute;as Mora</a> </th>
                        <th><a href=''>Grupo</a> </th>
						<th><a href=''>Ciclo</a> </th>
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
							$cta_id=$consulta->fields['idcuenta'];
							//$db->debug=true;
							$valor=$db->Execute("select idgestion from gestiones where idcuenta='$cta_id' and year(fecges)='$año_p' and month(fecges)='$mes_p'");
							if($consulta->fields['idestado']=='0'){	
								echo "<tr onMouseOver=\"this.className=''\" onMouseOut=\"this.className=''\" style='background-color:#87CEFA;'>";
							}else if($valor->fields['idgestion']!=""){
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
                            echo "<td>".$consulta->fields['monedas']."</td>";
							$sp= explode(".",$consulta->fields['imptot']);
																		$tot_impt=strlen($sp[0]);
																		if($tot_impt>=4){
																			$imptot=substr_replace($sp[0],',',-3,0).".".$sp[1];
																		}else{
																			$imptot=$consulta->fields['imptot'];
																		}	
                            echo "<td align='center'>".$imptot."</td>";
							
                            echo "<td>".$consulta->fields['diasmora']."</td>";
                            echo "<td>".$consulta->fields['grupo']."</td>";
							echo "<td>".$consulta->fields['ciclo']."</td>";
							echo "<td align='center'>".$consulta->fields['mx_g']."</td></tr>";
							
                            $consulta->MoveNext();
								$_SESSION['next'][$s]=$consulta->fields['idcliente'];
                        }
						mysql_free_result($consulta->_queryID);	
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
									
									if($_GET['pag']!=1 )
											echo "<a onclick=\"buscar('functions/consulta.php?id=&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp; <a onclick=\"buscar('functions/consulta.php?id=&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
										else
											echo "Inicio &nbsp;Anterior&nbsp;----";
										
									if($_GET['pag']!=$t_p && $t_p!=0)
											echo "<a onclick=\"buscar('functions/consulta.php?id=&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"buscar('functions/consulta.php?id=&pag=$t_p');\" href='#'>Fin</a> " ;
										else
											echo "&nbsp;Siguiente&nbsp; Fin";
									
									mysql_free_result($total->_queryID);										
									?>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
								
							</td>
						</tr>
				</tfoot>
			</table>
<?php $db->Close(); ?>