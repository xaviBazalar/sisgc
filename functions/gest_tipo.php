
											<table width="100%" id="tabla_gestiones_call">
												<thead>
													<tr>
														<?php
														session_start();
														$id=$_GET['id'];
														$q_ni="";
														if($_SESSION['nivel']==2){
															
															$q_ni=" and us.idnivel=2 and cu.idcartera=".$_SESSION['cartera'];	
														}
														
														if($_SESSION['nivel']==2){
																	$q_ni.=" AND (g.idresultado NOT IN (12,13) OR fecges>='".date("Y-m")."-01') ";
														}
														//include '../scripts/conexion.php';
														include '../define_con.php';
														$pag=0;
														$r_pag=10;
														$sql="SELECT g.idresultado,g.idcuenta,us.usuario,n.nivel,g.fecges,g.horges,g.fecreg,g.idgestion,cu.idcuenta,c.contactabilidad,r.resultado,j.justificacion,g.feccomp,g.impcomp,t.telefono,g.observacion FROM gestiones g
																	JOIN resultados r ON g.idresultado=r.idresultado
																	left JOIN telefonos t ON g.idtelefono=t.idtelefono
																	JOIN contactabilidad c ON g.idcontactabilidad=c.idcontactabilidad
																	JOIN cuentas cu ON g.idcuenta=cu.idcuenta
																	left JOIN justificaciones j ON g.idjustificacion=j.idjustificacion
																	JOIN usuarios us ON g.usureg=us.idusuario
																	JOIN niveles n ON us.idnivel=n.idnivel
																	WHERE cu.idcliente='$id' $q_ni ORDER BY fecges DESC, horges DESC";
																	
																	//$t_regist=$query->_numOfRows;

														
													//	if($_SERVER['REMOTE_ADDR']=="192.168.50.44"){}			
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
														
														
															$Res=$t_regist%$r_pag;
															if($Res>0) $t_p=floor($t_p)+1;
																$Ant=$_GET['pag']-1;
																$Sig=$_GET['pag']+1;
															
															
								
							
														?>
														
														<td colspan="10"> 
														<?php 
														if($pag!=0){
															echo ($pag+1);
															echo "-";
																if($_GET['pag']!=$t_p){
																	echo ($r_pag*$_GET['pag']);
																}else{
																	echo $t_regist;
																}
														}else{
															echo "1 - 10";
														}
														?>
														de <?php echo $t_regist; ?> Gestiones &nbsp;&nbsp;
															<?php if($_GET['pag']!=1 )
																	echo "<a onclick=\"gest_datos('$id',1);\" href='#'>Inicio</a> <a onclick=\"gest_datos('$id',$Ant);\" href='#'>&nbsp;".utf8_encode("« ")." &nbsp; Anterior--</a>" ;
																else
																	echo "Inicio &nbsp; Anterior--";
																
															if($_GET['pag']!=$t_p && $t_p!=0)
																	echo "<a onclick=\"gest_datos('$id',$Sig);\" href='#'>Siguiente &nbsp; ".utf8_encode("»")."&nbsp; </a><a onclick=\"gest_datos('$id',$t_p);\" href='#'>Fin</a> " ;
																else
																	echo "Siguiente &nbsp; Fin";
														    ?>
															<!--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>   Pagina <?php //echo $_GET['pag'];?> de <?php //echo $t_p?> / <?php //echo $t_regist; ?>  Gestiones</b>-->
														</td>
													</tr>
													<tr>
														<th>Fecha</th>
														<th>T.Contacto</th>
														<th>Resultado</th>
														<th>Detalles</th>
														<th>Tipo</th>
														<th>Negociador</th>
														<th>F.Comprom.</th>
														<th>Moneda</th>
														<th>Importe</th>
														<th>Telefono</th>
														<th>Observaciones</th>
														</tr>
												</thead>
												<tbody>
													<?php
															
														if($consulta){
															while(!$consulta->EOF){
																echo 
																	"<tr id='".$consulta->fields['idcuenta']."' >
																		<td align='left'><font color='blue'>".$consulta->fields['fecges']."</font>&nbsp; ".$consulta->fields['horges']."</td>
																		<td>".$consulta->fields['contactabilidad']."</td>
																		<td>".$consulta->fields['resultado']."</td>
																		<td>".$consulta->fields['justificacion']."</td>
																		<td>".$consulta->fields['nivel']."</td>
																		<td>".$consulta->fields['usuario']."</td>
																		<td align='center'>";
																		
																		$valF= $consulta->fields['feccomp'];
																		$valF=explode("-",$valF);
																		
																		if($valF[0]!="0000"){
																			echo $consulta->fields['feccomp'];
																		}
																		
																		echo "</td>";
																			
																				$mon_cta=$consulta->fields['idcuenta'];
																				$mon=explode("-",$mon_cta);
																				$consulta2 = $db->Execute("Select simbolo from monedas where idmoneda='".$mon[1]."'");
																				
																				$simbolo_m= $consulta2->fields['simbolo'];
																				
																				$valI= $consulta->fields['impcomp'];
																				$valI=explode(".",$valF);
																				$importe=$consulta->fields['impcomp'];
																				
																				if($valF[0]=="0"){
																					$simbolo_m="";
																					$importe="";
																				}
																		echo "<td class='numero'>$simbolo_m</td><td class='numero'><nobr> ".$importe."</nobr></td>
																		
																		<td><b>".$consulta->fields['telefono']."</b></td>";
																		$obs=str_replace('á','&aacute;',$consulta->fields['observacion']);
																		$obs=str_replace('Á','&Aacute;',$obs);
																		$obs=str_replace('é','&eacute;',$obs);
																		$obs=str_replace('í','&iacute;',$obs);
																		$obs=str_replace('ó','&oacute;',$obs);
																		$obs=str_replace('ú','&uacute;',$obs);
																		$obs=str_replace('ñ','&ntilde;',$obs);
																		
																		echo "<td>".$obs."</td>	";
																			
																		if($_SESSION['iduser']==1263 or $_SESSION['iduser']==231 or $_SESSION['iduser']==916) {
																			
																			if($consulta->fields['idresultado']==2 or $consulta->fields['idresultado']==248 or $consulta->fields['idresultado']==1 or $consulta->fields['idresultado']==254){
																				$id_llamada=$db_asterisk->Execute("SELECT *,date(calldate) fecha, time (calldate) hora FROM cdr WHERE dst='".$consulta->fields['telefono']."' AND DATE(calldate)='".$consulta->fields['fecges']."' AND disposition='ANSWERED'");
																				$grabacion="http://192.168.50.8/grabaciones/kobsa/salida/hoy/".$id_llamada->fields['uniqueid'].".gsm";
																				$grabacion_2="http://192.168.50.19/cenco-tv/grabaciones/salida/".$consulta->fields['fecges']."/".$id_llamada->fields['src']."_".$id_llamada->fields['dst']."_".$id_llamada->fields['fecha']."_".$id_llamada->fields['hora']."_".$id_llamada->fields['billsec'].".gsm";
																					//$grabacion_2="http://192.168.50.19/cenco-tv/grabaciones/salida/2012-04-04/".$id_llamada->fields['src']."_".$id_llamada->fields['dst']."_".$id_llamada->fields['fecha']."_".$id_llamada->fields['hora']."_".$id_llamada->fields['billsec'].".gsm";
																				
																				if(file($grabacion)){
																					echo "<td><a target='_blank' href='http://192.168.50.8/grabaciones/kobsa/salida/hoy/".$id_llamada->fields['uniqueid'].".gsm' >Grabacion</a></td>";
																				}else if(file($grabacion_2)){
																					//echo '<td><a href="" target="_blank">Grabacion</a></td>';
																					echo "<td><a href='$grabacion_2' target='_blank' >Grabacion</a></td>";
																				}
																			}
																		}	
																			
																		echo "</tr>";
																$consulta->MoveNext();
																}
														}
														mysql_free_result($consulta->_queryID);
														$db->Close();
																
													?>
												</tbody>
											</table>
										
										
										