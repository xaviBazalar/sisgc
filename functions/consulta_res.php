<?php
include '../scripts/conexion.php';

session_start();
$periodo = $_SESSION['periodo'];
//$db->debug=true;
$fecha_x_per=$db->EXECUTE("Select YEAR(fecini) anop,MONTH(fecini) mesp from periodos where idperiodo='$periodo'");

$año_p=$fecha_x_per->fields['anop'];
$mes_p=$fecha_x_per->fields['mesp'];
$pag=0;
$r_pag=20;
		$sql="SELECT us.usuario,c.idcliente,pv.proveedor,cr.cartera,pr.producto,m.monedas,c.cliente,c.observacion,cu.idcuenta,cp.diasmora,cp.grupo,cp.imptot,cp.ciclo 
						FROM cuentas cu
						JOIN clientes c ON c.idcliente=cu.idcliente
						JOIN monedas m ON m.idmoneda=cu.idmoneda
						JOIN carteras cr ON cr.idcartera=cu.idcartera
						JOIN proveedores pv ON cr.idproveedor=pv.idproveedor
						JOIN productos pr ON pr.idproducto=cu.idproducto			
						JOIN cuenta_periodos cp ON cp.idcuenta=cu.idcuenta
						JOIN usuarios us ON cp.idusuario=us.idusuario
						WHERE cp.idperiodo='$periodo'	
						 ";
		
		if(isset($_GET['ndoc'])){
			$ndoc=$_GET['ndoc'];
			$sql.="AND c.idcliente='$ndoc'";
		}
		if(isset($_GET['name'])){
			$name=$_GET['name'];
			//$idcl=$db->Execute("SELECT idcliente FROM clientes WHERE cliente LIKE '%$name%'");
			//$sql.="AND c.idcliente='".$idcl->fields['idcliente']."'";
			
			$idcl=$db->Execute("SELECT cl.idcliente FROM clientes cl  join cuentas c on cl.idcliente=c.idcliente WHERE cl.cliente LIKE '%$name%'  ");
			$cli_id="";	
			while(!$idcl->EOF){
				$cli_id.="'".$idcl->fields['idcliente']."',";
				$idcl->MoveNext();
			}
			$cli_id=substr($cli_id, 0, -1);
			//$sql.="AND c.cliente like '%$name%'";
			$sql.="AND c.idcliente in ($cli_id)";
		}
		if(isset($_GET['grup'])){
			$grup=$_GET['grup'];
			$sql.="AND cp.grupo='$grup'";
		}
		if(isset($_GET['ciclo'])){
			$ciclo=$_GET['ciclo'];
			$sql.="AND cp.ciclo='$ciclo'";
		}
		
		/*if(isset($_GET['prov'])){
			$prov=$_GET['prov'];
			$sql.="AND pv.idproveedor='$prov'";
		}*/
		
		if(isset($_GET['prov']) and $_GET['cart']==""){
			$prov=$_GET['prov'];
			$CRT="";
			$t_cartera=$db->Execute("select idcartera from carteras where idproveedor=$prov");
				while(!$t_cartera->EOF){
					$CRT.="'".$t_cartera->fields['idcartera']."',";
					$t_cartera->MoveNext();
				}
			$sql.=" AND cu.idcartera in (".substr($CRT,0,-1).")";
			//$sql.=" AND pr.idproveedor='$prov' "; AND u.idproveedor='$prov' 
		}
		
		if(isset($_GET['cart']) and $_GET['cart']!=""){
			$cart=$_GET['cart'];
			$sql.="AND cu.idcartera='$cart'";
		}
		/*if(isset($_GET['cart'])){
			$cart=$_GET['cart'];
			$sql.="AND cr.idcartera='$cart'";
		}*/
		
		$sql.=" order by c.idcliente ";
		
	$total =$db->Execute($sql);
	$t_regist=$total->_numOfRows;
	if(isset($_GET['pag'])){
		$pag=$_GET['pag'];
		$pag=$pag-1;
		$pag=$pag*$r_pag;
		$sql.=" LIMIT $pag,$r_pag";
	}else{
		$sql.=" LIMIT $pag,$r_pag";
	}
	$consulta =$db->Execute($sql);
	
	//echo $t_regist;
	$t_p=$t_regist/$r_pag;
	


?>
<table border="0" id="design1" width="100%">
				<thead>
					<tr>
                                            <td colspan="13" style="font-size:11px;">
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b> Pagina <?php echo $_GET['pag']; ?> </b>
                                            </td>
                                        </tr>
								<tr>
                                                                    <th><?php echo utf8_encode("Nº"); ?></th>
                                                                    <th><a href=''><?php echo utf8_encode("Nº"); ?> Documento</a> </th>
																	<th><a href=''>Cliente</a> </th>
                                                                    <th><a href=''>Proveedor.</a> </th>
                                                                    
                                                                    <th><a href=''>Moneda</a> </th>
                                                                    <th><a href=''>Capital Total</a> </th>
                                                                   <!-- <th><a href=''>Pago</a> </th>
                                                                    <th><a href=''>PP</a> </th>
                                                                    <th><a href=''>Venc.</a> </th>-->
                                                                    <th><a href=''>D&iacute;as Mora</a> </th>
                                                                    <th><a href=''>Grupo</a> </th>
																	<th><a href=''>Ciclo</a> </th>
																	<th><a href=''>Usuario</a> </th>
                                                                </tr>
				</thead>
				<tbody>

                                        
                                            <?php
                                            $n=0;
											if($_GET['pag']!=1){
												$n=$_GET['pag']-1;
												$n=$n*$r_pag;
											}
                                            while(!$consulta->EOF){
                                                $s = ++$n;
												echo "<tr onMouseOver=\"this.className='rowshover'\" onMouseOut=\"this.className='rows2'\" class=\"rows1\"><td>$s</td>";
													if($_SESSION['tnivel']=="2" or $_SESSION['tnivel']=="3"){
														echo "<td align='center'>".$consulta->fields['idcliente']."</td>";
													}else{
														echo "<td align='center'>".$consulta->fields['idcliente']."</td>";
													}
                                                if($_SESSION['prove']==$_GET['prov'] or $_SESSION['prove']==$consulta->fields['idproveedor']){
													echo "<td><a href='../index.php?temp=ok&gestion=1&idCl=".$consulta->fields['idcliente']."'>".utf8_encode($consulta->fields['cliente'])."</a></td>";
                                                }else{
													echo "<td>".utf8_encode($consulta->fields['cliente'])."</td>";
												}
												echo "<td>".$consulta->fields['proveedor']."</td>";
                                                
                                                echo "<td>".$consulta->fields['monedas']."</td>";
                                                echo "<td align='center'>".$consulta->fields['imptot']."</td>";
                                                echo "<td>".$consulta->fields['diasmora']."</td>";
                                                echo "<td>".$consulta->fields['grupo']."</td>";
												echo "<td>".$consulta->fields['ciclo']."</td>";
												echo "<td>".$consulta->fields['usuario']."</td></tr>";
												
                                                $consulta->MoveNext();
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
			
					<td colspan="10" style="font-size:11px;">
						<?php //if(!isset($_GET['ndoc']) and !isset($_GET['name'])){?>
							<?php
							
							if($_GET['pag']!=1 )
									echo "<a onclick=\"buscar('consulta_res.php?id=&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp; <a onclick=\"buscar('consulta_res.php?id=&pag=$Ant');\" href='#'> ".utf8_encode("«")." Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"buscar('consulta_res.php?id=&pag=$Sig');\" href='#'>&nbsp;Siguiente ".utf8_encode("»")."</a>&nbsp;&nbsp;<a onclick=\"buscar('consulta_res.php?id=&pag=$t_p');\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
								</tfoot>
			</table>
			
			<?php $db->Close(); ?>