
<div class="TabbedPanelsContentGroup">
	
        <div class="TabbedPanelsContent" > 
				<!-- Clientes -->
				<!---->
				<!-- D I R E C C I O N E S -->
				<div id="areaList" >
					<div id="nuevo">
						<table width="100%" id="design4" align="left" style="visibility:hidden;position:absolute;" >
							<form name="frmDireccion" method="post">
								<caption>Direcciones</caption>
								<thead>
									<tr>
										<th>Origen</th>
										<th>Dirección</th>
										<th>Dpto.</th>
										<th>Provincia</th>
										<th>Distrito</th>
										<th>Plano</th>
										<th>Cuadrante</th>
										<th colspan="1">Priorización<!--<br />&nbsp;<sub>Dir.</sub> &nbsp;&nbsp; | &nbsp;&nbsp; <sub></sub>--></th>
										<th>Estado</th>
										<th>Observaciones</th>
									</tr>
								</thead>
								<tbody>
						
											<?php
											
											$dir=$db2->Execute("SELECT d.prioridad,d.direccion,d.observacion,od.origendireccion,u.nombre dpto,cu.cuadrante,es.estado,u2.nombre prov,u3.nombre dist,p.plano FROM direcciones d
																JOIN origen_direcciones od ON d.idorigendireccion=od.idorigendireccion
																JOIN ubigeos u ON d.coddpto=u.coddpto AND u.codprov=00 AND u.coddist=00
																JOIN ubigeos u2 ON d.coddpto=u2.coddpto AND d.codprov=u2.codprov AND u2.coddist=00
																JOIN ubigeos u3 ON d.coddpto=u3.coddpto AND d.codprov=u3.codprov AND d.coddist=u3.coddist
																JOIN cuadrantes cu ON d.idcuadrante=cu.idcuadrante
																JOIN estados es ON d.idestado=es.idestado
																WHERE d.idcliente='$id'
																
																");
												if(!$dir){
													echo "<tr><td> <td><tr>";	
												}else{
												
													while(!$dir->EOF){
														echo "<tr>
																<td>".$dir->fields['origendireccion']."</td>
																<td>".$dir->fields['direccion']."</td>
																<td>".$dir->fields['dpto']."</td>
																<td>".$dir->fields['prov']."</td>
																<td>".$dir->fields['dist']."</td>
																<td>".$dir->fields['plano']."</td>
																<td>".$dir->fields['cuadrante']."</td>
																<td align='center'><input type='radio' name='dprioridad' id='d687728' onclick=\"return false;\"";
															if($dir->fields['prioridad']=="1"){
																echo " checked ";
															}
														echo "  /></td>
																<td align='center'>".$dir->fields['estado']."</td>
																<td>".$dir->fields['observacion']."</td>
																<!--<td align='center'><input type='checkbox' id='ed687728' onclick=\"estado(this, 'd', '687728');\" checked disabled /></td>-->
																</tr>";
														$dir->MoveNext();
													}
												}
												//mysql_free_result($dir->_queryID);
											?>
								</tbody>
							</form>
									
						</table>
								
					</div>
					<!-- Aca iba Direcciones y Telefonos-->
				</div>
				<!--<br/><br/><br/><br/><br/><br/>-->
				<!--<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>-->
						
						<!-- C O N T A C T O S -->
						<!--<div id="areaList">-->
								
									<div style="overflow:auto" >
									
										<table width="100%" id="tabla_cuentas" class="tabla_listado" >
											<caption>Cuentas
												<a href="#"  ><span id="mas_menos">(+)</span></a>
											</caption>
											<?php 
													if(
													$_SESSION['prove']==30 or
													$_SESSION['prove']==29 or
													$_SESSION['prove']==28 or
													$_SESSION['prove']==18 or
													$_SESSION['prove']==27 or
													$_SESSION['prove']==25 or
													$_SESSION['prove']==24 or
													$_SESSION['prove']==16 or
													$_SESSION['prove']==20 or 
													$_SESSION['prove']==21 or 
													$_SESSION['prove']==22 or 
													$_SESSION['prove']==9 or 
													$_SESSION['prove']==14 or 
													$_SESSION['prove']==15 or 
													$_SESSION['prove']==7 or 
													$_SESSION['prove']==8 or 
													$_SESSION['prove']==12 or 
													$_SESSION['prove']==2 or 
													$_SESSION['prove']==11 or 
													$_SESSION['prove']==31 or
													$_SESSION['prove']==17) { include 'detalle_cta.php'; } else {?>
											<thead id="unos">
												
												<tr>
													<th>Cta</th>
													<th>Mon.</th>
													<th>Proveedor - Producto</th>
													<?php $prove_nro=array(8);?>
													<?php if($_SESSION['prove']!=12){?>	
													<th>Imp.Capital</th>
													<?php }?>	
													
													<?php if(in_array($_SESSION['prove'],$prove_nro)){ }else{?>		
														<th>Imp.Vencido</th>
													<?php } ?>	
														<th class="oculto">Intereses</th>
														<th class="oculto">Gastos</th>
														<th class="oculto">Honorarios</th>
														<th class="oculto">Penalidad</th>
													<th>Imp.Total</th>
													<?php $prove_nro=array(8);
													if($_SESSION['prove']==8){?>	
														<th>Imp.Fracc.Ini</th>
														<th>Imp.Fracc.Pr</th>
														<th>Imp.Fracc.Mnt</th>
														<th>Fracc.Cuo</th>
													<?php }?>
													<th>Mora</th>
														<th class="oculto">Cuotas crédito</th>
														<th class="oculto">Cuotas pagadas</th>
														<th class="oculto">Cuotas vencidas</th>
													<?php 
													$prove_nro=array(8,2);
													if(!in_array($_SESSION['prove'],$prove_nro)){?>	
													<th>Imp.Min</th>
													<?php }else if($_SESSION['prove']!=12){ ?>	
													<th>Imp.Descuento</th>
													<?php } ?>	
													<th>F. Vcto</th>
													<?php if($_SESSION['cartera']==52){?>	
														<th>F.Emision</th>
													<?php }?>	
													<?php $prove_nro=array(8,10);
													if($_SESSION['prove']==10){?>	
														<th>F. Con</th>
													<?php }?>
														<th class="oculto">F. Ingreso</th>
														<th class="oculto">Cartera</th>
													<th>Grupo</th>
													<?php 
													if(!in_array($_SESSION['prove'],$prove_nro)){?>	
													<th>Ciclo</th>
													
													<th>Obs.</th>
													
													<?php }?>
													<?php if($_SESSION['cartera']==52){?>	
														<th>Modalidad de Pago</th>
													<?php }?>
													
													<?php if($_SESSION['prove']==10){?>	
														<th>Ciclo</th>
													<?php }?>	
														<th class="oculto">Auxiliar1</th>
														<th class="oculto">Auxiliar2</th>
													<th></th>
												</tr>
											</thead>
											<tbody>
											
											<?php
												$prove_nro=array(8);
												$cartera = $_SESSION['cartera'];
												$periodo = $_SESSION['periodo'];
												$sql="SELECT c.*,cp.*,m.simbolo,m.monedas,pr.proveedor,pro.producto,cp.idperiodo,cp.idestado FROM cuentas c 
																		JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta 
																		JOIN monedas m ON c.idmoneda=m.idmoneda
																		JOIN carteras ct ON c.idcartera=ct.idcartera
																		JOIN proveedores pr ON ct.idproveedor=pr.idproveedor
																		JOIN productos pro ON c.idproducto=pro.idproducto	
																		WHERE c.idcliente='$id'	and cp.idperiodo='$periodo'";
												
																		if($_SESSION['nivel']!=1 and $_SESSION['nivel']!=5 ){
																				$sql.=" and ct.idcartera='$cartera' and cp.idestado=1 and cp.idusuario=".$_SESSION['iduser'];
																		}
											//	echo $sql						
												$cuenta=$db2->Execute($sql);						
												if(!$dir){
													echo "<tr><td> <td><tr>";	
												}else{
													while(!$cuenta->EOF){
														$ct=$cuenta->fields['idcuenta'];
															if($cuenta->fields['idestado']=='0'){
																$bgr="background-color:#87CEFA;";
															}else{$bgr="";}
														echo "<tr id='".$cuenta->fields['idcuenta']."'  onclick=\"show_cta_gest('$ct')\"; style='cursor:pointer;$bgr'>";
															$cta=explode('-',$cuenta->fields['idcuenta']);
														
															if(count($cta)>2 and $_SESSION['cartera']==52){
																
																$cta_i="";
																for($i=0;$i<=count($cta);$i++){
																  $cta_i.=$cta[$i]."-";
																}
																$total=strlen($cta_i);
																
																$cta=substr($cta_i,0,($total-2));
																//$cta[0]=$cta[0]."-".$cta[1];

															}else if(count($cta)>2){$cta=$cta[0]."-".$cta[1];} else{ $cta=$cta[0]."-".$cta[1];}

														echo "<td>".$cta."</td>
																<!--<td>&nbsp;</td>
																<td>00675346240K</td>-->
																<td>".$cuenta->fields['simbolo']."</td>
																<td>".$cuenta->fields['proveedor']."-".$cuenta->fields['producto']."</td>";
																
														if($_SESSION['prove']!=12){
															$sp= explode(".",$cuenta->fields['impcap']);
																		$tot_impt=strlen($sp[0]);
																		if($tot_impt>=4){
																			$impcap=substr_replace($sp[0],',',-3,0).".".$sp[1];
																		}else{
																			$impcap=$cuenta->fields['impcap'];
																		}	
															echo "	<td class='numeros'>".$impcap."</td>";
														}
																
																if(in_array($_SESSION['prove'],$prove_nro)){
																	if($_SESSION['prove']==10){
																		$sp= explode(".",$cuenta->fields['impven']);
																		$tot_impt=strlen($sp[0]);
																		if($tot_impt>=4){
																			$impven=substr_replace($sp[0],',',-3,0).".".$sp[1];
																		}else{
																			$impven=$cuenta->fields['impven'];
																		}	
																		echo "<td class='numeros'>".$impven."</td>";
																	}
																	
																}else{
																	$sp= explode(".",$cuenta->fields['impven']);
																	$tot_impt=strlen($sp[0]);
																	if($tot_impt>=4){
																		$impven=substr_replace($sp[0],',',-3,0).".".$sp[1];
																	}else{
																		$impven=$cuenta->fields['impven'];
																	}	
																	echo "<td class='numeros'>".$impven."</td>";
																}
																
																$sp= explode(".",$cuenta->fields['imptot']);
																$tot_impt=strlen($sp[0]);
																	if($tot_impt>=4){
																		$impt=	substr_replace($sp[0],',',-3,0).".".$sp[1];
																	}else{
																		$impt=$cuenta->fields['imptot'];
																	}
																	
																											
														echo "	<td class='numero_oculto'>0.00</td>
																	<td class='numero_oculto'>0.00</td>
																	<td class='numero_oculto'>0.00</td>
																	<td class='numero_oculto'>0.00</td>
																<td class='numeros'>".$impt."</td>";
														if($_SESSION['prove']==8){
															echo  "<td class='numeros'>".$cuenta->fields['impfraini']."</td>
																   <td class='numeros'>".$cuenta->fields['impfracpr']."</td>
																   <td class='numeros'>".$cuenta->fields['impframnt']."</td>
																   <td class='numeros'>".$cuenta->fields['fracuo']."</td>";
														}			
																
														echo "		<td class='numeros'>".$cuenta->fields['diasmora']."</td>
																	<td class='numero_oculto'>13</td>
																	<td class='numero_oculto'>1</td>
																	<td class='numero_oculto'>11</td>";
																
																if($_SESSION['prove']==10){
																	echo "<td class='numeros'>".$cuenta->fields['impmin']."</td>";
																}else if($_SESSION['prove']!=12){
																	$sp= explode(".",$cuenta->fields['impdestot']);
																	$tot_impt=strlen($sp[0]);
																	if($tot_impt>=4){
																		$impds=substr_replace($sp[0],',',-3,0).".".$sp[1];
																	}else{
																		$impds=$cuenta->fields['impdestot'];
																	}	
																	echo "<td class='numeros'>".$impds."</td>";
																}
														echo "	<td align='center'>".$cuenta->fields['fecven']."</td>";
														
														if($_SESSION['cartera']==52){ echo "<td class='numeros'>".$cuenta->fields['feccon']."</td>";} 
														if($_SESSION['prove']==10){ echo "<td class='numeros'>".$cuenta->fields['feccon']."</td>";} 
														
														echo "	<td align='center' class='oculto'>06/02/2010</td>
																	<td>".$cuenta->fields['grupo']."</td>";
														$prove_nro=array(8);			
														if(in_array($_SESSION['prove'],$prove_nro)){
																	
																	
														}else if($_SESSION['prove']==10){ echo "	<td class='numeros'>".$cuenta->fields['ciclo']."</td>"; }
														
														else{	
														
															echo "	<td class='numeros'>".$cuenta->fields['ciclo']."</td>";
															if($_SESSION['cartera']==6){
																echo "<td>".$cuenta->fields['observacion2']."</td>";
															}else{
																echo "<td>".$cuenta->fields['observacion']."</td>";
															}
														}
														if($_SESSION['cartera']==52){ echo "<td class='numeros'>".$cuenta->fields['obs3']."</td>";} 		
														
														
															echo "<td class='oculto'>5</td>
																<td class='oculto'>SI</td>
																	<td class='oculto'>BTR</td>
																<td></td>
															</tr>" ;
														$cuenta->MoveNext();
													}
												}
												//mysql_free_result($cuenta->_queryID);

											?>
												<!-- cuentas -->
											</tbody>
											<?php }?>
										</table>
									</div>
										<table width="100%" id="tabla_cuentas_detalle" class="no-print">
											<caption>Detalle de la cuenta</caption>
											<?php
											$cta_detalle=$db->Execute("SELECT cp.idcuenta,m.simbolo,cd.* FROM cuenta_detalles cd 
																	JOIN cuenta_periodos cp ON cd.idcuentaperiodo=cp.idcuentaperiodo
																	JOIN cuentas c ON cp.idcuenta=c.idcuenta
																	JOIN monedas m ON c.idmoneda=m.idmoneda
																	JOIN clientes cl ON c.idcliente=cl.idcliente
																	WHERE cp.idperiodo='$periodo' AND cl.idcliente='$id' and cd.idestado='1'");
											if($cta_detalle->fields['idcuenta']==""){
											?>
											<tr>
												<td><b>No hay detalles de cuenta</b></td>
											</tr>
											<?php }else{
											echo "<thead>
												<tr>
													<th>Nro cuota</th>
													<th>Cta</th>
													<th>Fecha vencimiento</th>
													<th>Capital</th>
													<th>Intereses</th>
													<th>Honorarios</th>
													<th>Gastos</th>
													<th>Total por cuota</th>
													<th>Observacion</th>
												</tr>
											</thead>";
											
											
												echo "<tbody>";
													while(!$cta_detalle->EOF){
														$simbol=$cta_detalle->fields['simbolo'];
														echo '
															<tr id="'.$cta_detalle->fields['idcuenta'].'">
																<td class="numeros">'.$cta_detalle->fields['nrocuota'].'</td>
																<td>'.$cta_detalle->fields['idcuenta'].'</td>
																<td align="center">'.$cta_detalle->fields['fecven'].'</td>
																<td class="numeros">'.$simbol." ".$cta_detalle->fields['impcap'].'</td>
																<td class="numeros">'.$simbol." ".$cta_detalle->fields['impint'].'</td>
																<td class="numeros">'.$simbol." ".$cta_detalle->fields['impmor'].'</td>
																<td class="numeros">'.$simbol." ".$cta_detalle->fields['impotr'].'</td>
																<td class="numeros">'.$simbol." ".$cta_detalle->fields['imptot'].'</td>
																<td>'.utf8_encode($cta_detalle->fields['observacion']).'</td>
															</tr>
														';
														$cta_detalle->MoveNext();
													}
													//mysql_free_result($cta_detalle->_queryID);
												echo "</tbody>";
											}
											?>
										</table>
									<!-- GESTIONES -->
										<table width="100%" id="design4">
											<tr>
												<td>
													<caption>Gestiones&nbsp;&nbsp;&nbsp;
														<input type="radio" name="gt" onclick="ver_gestiones('campo', 'call');" <?php if(!isset($_SESSION['campo'])) echo "checked";?> />Call Center&nbsp;&nbsp;&nbsp;
														<input type="radio" name="gt" onclick="ver_gestiones('call', 'campo');" <?php if(isset($_SESSION['campo'])) echo "checked";?> />Campo
													</caption>
												</td>
											</tr>
										</table>
									<div id="gest">
										<span id="span_gestiones_call" style="<?php  	
																						if(!isset($_SESSION['campo'])) 
																							echo "visibility:visible;position:relative";
																						else
																							echo "visibility:hidden;position:absolute";
																				?>">
											<table width="100%" id="tabla_gestiones_call">
												<thead>
													<tr>
														<?php
															//if($_SERVER['REMOTE_ADDR']=="192.168.50.44"){$db->debug=true;}
															$call="SELECT g.idcuenta FROM gestiones g
																	JOIN resultados r ON g.idresultado=r.idresultado
																	LEFT JOIN telefonos t ON g.idtelefono=t.idtelefono
																	JOIN contactabilidad c ON g.idcontactabilidad=c.idcontactabilidad
																	JOIN cuentas cu ON g.idcuenta=cu.idcuenta
																	left JOIN justificaciones j ON g.idjustificacion=j.idjustificacion
																	JOIN usuarios us ON g.usureg=us.idusuario
																	JOIN niveles n ON us.idnivel=n.idnivel
																	WHERE cu.idcliente='$id'  
																	";
															if($_SESSION['nivel']==2){
																	$call.=" AND (g.idresultado NOT IN (12,13) OR fecges>='".date("Y-m")."-01') ";
															}
															if($_SESSION['nivel']!=5 and $_SESSION['nivel']!=1){
																if($_SESSION['prove']!=2){$crt="and cu.idcartera='$cartera'";} else {$crt="";}
																$call.="and us.idnivel=2 $crt";
															}else{
																$call.="and us.idnivel!=3 and g.idactividad!=4  ORDER BY fecges DESC, horges DESC";
															}
														//if($_SERVER['REMOTE_ADDR']=="192.168.50.44"){echo $call;}
														$total=$db->Execute($call);
															
														if($_SESSION['nivel']!=5 and $_SESSION['nivel']!=1){
															$sq="and us.idnivel in (2,1) AND idactividad!=4  ";

														}else{
															$sq="AND us.idnivel!=3 and g.idactividad!=4 ";
															//$sq="";
														}
														//echo $sq;
														$cs="SELECT f.fuente,g.idresultado,g.idcuenta,us.usuario,n.nivel,g.fecges,g.horges,g.fecreg,g.idgestion,cu.idcuenta,c.contactabilidad,r.resultado,j.justificacion,g.feccomp,g.impcomp,t.telefono,g.observacion FROM gestiones g
																	JOIN resultados r ON g.idresultado=r.idresultado
																	LEFT JOIN telefonos t ON g.idtelefono=t.idtelefono
																	LEFT JOIN fuentes f on t.idfuente=f.idfuente
																	JOIN contactabilidad c ON g.idcontactabilidad=c.idcontactabilidad
																	JOIN cuentas cu ON g.idcuenta=cu.idcuenta
																	LEFT JOIN justificaciones j ON g.idjustificacion=j.idjustificacion
																	JOIN usuarios us ON g.usureg=us.idusuario
																	JOIN niveles n ON us.idnivel=n.idnivel
																	WHERE cu.idcliente='$id' 
																	
																	";
														if($_SESSION['nivel']==2){
																	$cs.=" AND (g.idresultado NOT IN (12,13)  OR fecges>='".date("Y-m")."-01') ";
														}

														if($_SESSION['nivel']!=5 and $_SESSION['nivel']!=1){
															if($cartera==24){$fg=" and g.fecges>='2012-03-01' ";}else{$fg="";}
															if($_SESSION['prove']!=2 and $_SESSION['prove']!=9 and $_SESSION['prove']!=14 and $_SESSION['prove']!=20 and $_SESSION['prove']!=21 and $_SESSION['prove']!=22){
																$crt="and cu.idcartera='$cartera'";
															} else {$crt="";}
															$cs.="$crt";
														}
														
														$cs.=" $sq $fg ORDER BY fecges DESC, horges DESC
																	Limit 0,10";
														//if($_SERVER['REMOTE_ADDR']=="192.168.50.44"){echo "<pre>";var_dump($_SESSION); echo $cs;}
														

														$query=$db->Execute($cs);
														$t_regist=$total->_numOfRows;
																		
														?>
														<td colspan="10">1 - <?php if($t_regist<=10){ echo $t_regist;}else{ echo "10";}?>
														de <?php echo $t_regist; ?> Gestiones 
															<?php if($t_regist >10) {
																 echo	"<a onclick=\"gest_datos('$id',2);\" href='#'>".utf8_encode("Siguiente »")."</a>";
															}else{
																 echo	utf8_encode("Siguiente »");
															}
															//mysql_free_result($total->_queryID);
															?>
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
														<th>Telefono </th>
														<th>Fuente</th>
														<th>Observaciones</th>
														</tr>
												</thead>
												<tbody>
													<?php
															
														if($query){
															while(!$query->EOF){
																echo 
																	"<tr id='".$query->fields['idcuenta']."' >
																		<td align='left'><font color='blue'>".$query->fields['fecges']."</font>&nbsp; ".$query->fields['horges']."</td>
																		<td>".$query->fields['contactabilidad']."</td>
																		<td>".utf8_encode($query->fields['resultado'])."</td>
																		<td>".$query->fields['justificacion']."</td>
																		<td>".$query->fields['nivel']."</td>
																		<td>".$query->fields['usuario']."</td>
																		<td align='center'>";
																		$valF= $query->fields['feccomp'];
																		
																		$valF=explode("-",$valF);
																		if($query->fields['idresultado']==2 and $query->fields['impcomp']!="" and $query->fields['impcomp']!="0" and $valF[0]!="0000"){
																																						$id_gs=$query->fields['idgestion'];
																			$fecha_comp=$db->Execute("SELECT fecha FROM tareas WHERE idgestion='$id_gs' ");
																			echo $query->fields['feccomp'];
																			//echo $fecha_comp->fields['fecha'];
																		}else if($valF[0]!="0000" and $query->fields['impcomp']!=""){
																			echo $query->fields['feccomp'];
																		}
																		
																		echo "</td>";
																			
																				$mon_cta=$query->fields['idcuenta'];
																				$mon=explode("-",$mon_cta);
																				$consulta = $db->Execute("Select simbolo from monedas where idmoneda='".$mon[1]."'");
																				
																				$simbolo_m= $consulta->fields['simbolo'];
																				$valI= $query->fields['impcomp'];
																				$valI=explode(".",$valI);
																				$importe=$query->fields['impcomp'];
																				
																				if($valI[0]=="0" or $importe==""){
																					$simbolo_m="";
																					$importe="";
																				}
																		echo "<td class='numero'>$simbolo_m</td><td class='numero'><nobr> ".$importe."</nobr></td>
																		
																		<td><b>".$query->fields['telefono']."</b></td>
																		<td><b>".$query->fields['fuente']."</b></td>";
																		$obs=str_replace('á','&aacute;',$query->fields['observacion']);
																		$obs=str_replace('é','&eacute;',$obs);
																		$obs=str_replace('í','&iacute;',$obs);
																		$obs=str_replace('ó','&oacute;',$obs);
																		$obs=str_replace('ú','&uacute;',$obs);
																		
																		echo "<td>".$obs."</td>";
																		if($_SESSION['iduser']==1263 or $_SESSION['iduser']==231 or $_SESSION['iduser']==916) {
																			
																			if($query->fields['idresultado']==2 or $query->fields['idresultado']==248 or $query->fields['idresultado']==1 or $query->fields['idresultado']==254){
																				$id_llamada=$db_asterisk->Execute("SELECT *,date(calldate) fecha, time (calldate) hora FROM cdr WHERE dst='".$query->fields['telefono']."' AND DATE(calldate)='".$query->fields['fecges']."' AND disposition='ANSWERED'");
																				$grabacion="http://192.168.50.8/grabaciones/kobsa/salida/hoy/".$id_llamada->fields['uniqueid'].".gsm";
																				$grabacion_2="http://192.168.50.19/cenco-tv/grabaciones/salida/".$query->fields['fecges']."/".$id_llamada->fields['src']."_".$id_llamada->fields['dst']."_".$id_llamada->fields['fecha']."_".$id_llamada->fields['hora']."_".$id_llamada->fields['billsec'].".gsm";
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
																$query->MoveNext();
																}
														}
														//mysql_free_result($query->_queryID);		
													?>
												</tbody>
											</table>
										</span>
										<span id="span_gestiones_campo" style="<?php  	
																						if(!isset($_SESSION['campo'])) 
																							echo "visibility:hidden;position:absolute";
																						else
																							echo "visibility:visible;position:relative";
																				?>">
											<table width="100%" id="tabla_gestiones_campo">
												<thead>
													<?php
														$nivel=$_SESSION['nivel'];
														$sql="SELECT g.idcuenta,us.usuario,n.nivel,g.fecges,g.horges,g.fecreg,g.idgestion,cu.idcuenta,c.contactabilidad,r.resultado,g.feccomp,g.impcomp,d.direccion,g.observacion FROM gestiones g
																	JOIN resultados r ON g.idresultado=r.idresultado
																	left JOIN direcciones d ON g.iddireccion=d.iddireccion
																	JOIN contactabilidad c ON g.idcontactabilidad=c.idcontactabilidad
																	JOIN cuentas cu ON g.idcuenta=cu.idcuenta
																	LEFT JOIN usuarios us ON g.idagente=us.idusuario
																	LEFT JOIN niveles n ON us.idnivel=n.idnivel
																	WHERE cu.idcliente='$id'  and g.idactividad=4 
																	";/* AND us.idnivel=3  */
														if($nivel!=1 and $nivel!=5)	{	
															$sql.="and cu.idcartera='$cartera'";
														}
														$sql.=" GROUP BY g.idcuenta,g.observacion,fecreg ";
														$sql.=" ORDER BY g.fecges DESC, g.horges DESC";
														//echo $sql;
														//if($_SERVER['REMOTE_ADDR']=="192.168.50.44"){echo $sql;}
														$query=$db->Execute($sql);
														$t_regist=$query->_numOfRows;
													?>
													<tr>
														<td colspan="10">1 - <?php if($t_regist<=10){ echo $t_regist;}else{ echo "10";}?>
														de <?php echo $t_regist; ?> Gestiones 
															<?php if($t_regist >10) {
																 echo	"<a onclick=\"gest_datos('$id',2);\" href='#'>Siguiente »</a>";
															}else{
																 echo	utf8_encode("Siguiente »");
															}
															?>
														</td>
													</tr>
													<tr>
														<th>Fecha</th>
														
														<th>T.Contacto</th>
														<th>Resultado</th>
														<th>Tipo</th>
														<th>Negociador</th>
														
														<th>Direccion</th>
														<th>Observaciones</th>
													</tr>
												</thead>
												<tbody>
													
													<?php
															
														if($query){
															while(!$query->EOF){
																echo 
																	"<tr >
																		<td align='left'><font color='blue'>".$query->fields['fecges']."</font>&nbsp; ".$query->fields['horges']."</td>
																		<td>".$query->fields['contactabilidad']."</td>
																		<td>".$query->fields['resultado']."</td>
																		<td>".$query->fields['nivel']."</td>
																		<td>".$query->fields['usuario']."</td>
																		<td><b>".$query->fields['direccion']."</b></td>
																		<td>".$query->fields['observacion']."</td>
																	</tr>";
																$query->MoveNext();
																}
														}
														//mysql_free_result($query->_queryID);		
													?>
													
													
												</tbody>
											</table>
										</span>
									</div>
									<!-- PAGOS -->
										<table width="100%" id="tabla_pagos">
											<caption>Pagos </caption>
											<thead><tr><th>Cuenta</th><th>Fecha Pago</th><?php if($_SESSION['prove']==14){ echo "<th>Fecha Vencimiento</th><th>Dias Mora</th>";}  ?><th>Importe</th><th>F.Sistema</th><th>Observaciones</th></tr></thead>

											<?php
														//$db->debug=true;	
														$query=$db->Execute("SELECT cp.idcuenta,cp.fecpag,m.simbolo,cp.imptot,DATE(cp.fecreg) fsis,cp.observacion FROM clientes c
																			JOIN cuentas ct ON c.idcliente=ct.idcliente
																			JOIN monedas m ON ct.idmoneda=m.idmoneda
																			JOIN cuenta_pagos cp ON ct.idcuenta=cp.idcuenta and cp.idperiodo=1".$_SESSION['periodo']." and month(cp.fecpag)>=".((date("m"))-1)."
																			WHERE c.idcliente='$id'  and cp.idestado='1' -- and cp.idusuario='".$_SESSION['iduser']."'
																			AND DATE(cp.fecreg)=(SELECT MAX(DATE(fecreg)) FROM cuenta_pagos WHERE idcuenta=cp.idcuenta)
																			
																			GROUP BY fecpag,imptot,idcuenta
																			");
															//$db->debug=false;		

											?>
											<?php
															
											if($query){
												while(!$query->EOF){
												
												
															/*$cta=explode('-',$query->fields['idcuenta']);
															if(count($cta)>2){
																$cta[0]=$cta[0]."-".$cta[1];
															}*/
															
															$cta=explode('-',$query->fields['idcuenta']);
														
															if(count($cta)>2 and $_SESSION['cartera']==52){
																
																$cta_i="";
																for($i=0;$i<=count($cta);$i++){
																  $cta_i.=$cta[$i]."-";
																}
																$total=strlen($cta_i);
																
																$cta=substr($cta_i,0,($total-2));
																//$cta[0]=$cta[0]."-".$cta[1];

															}else if(count($cta)>2){$cta=$cta[0]."-".$cta[1];} else{ $cta=$cta[0]."-".$cta[1];}
															
													$obs=$query->fields['observacion'];
													$fecven_mora;
													if($_SESSION['prove']==14){
														$obs=explode("*",$obs);
														$fi1=explode("-",$query->fields['fecpag']);
														$ff2=explode("-",$obs[0] );
														$fi=$fi1[2]."-".$fi1[1]."-".$fi1[0];
														$ff=$ff2[0]."-".$ff2[1]."-".$ff2[2];
		
														$dias_mora=restaFechas($ff,$fi);
														$fc=explode("-",str_replace("/","-",$obs[0]));
														$fecven_mora="<td>".$fc[2]."-".$fc[1]."-".$fc[0]."</td><td>$dias_mora</td>";
														$obs=$obs[1];
													}
															
													echo 
														"
														<tbody>
															<tr id='".$cta."'>
																<td>".$cta."</td>
																<td>".$query->fields['fecpag']."</td>
																$fecven_mora<td class='numeros'><nobr>".$query->fields['simbolo']." ".$query->fields['imptot']."</nobr></td>
																<td >".$query->fields['fsis']."</td>";
																
																
																
													echo "		<td>".$obs."</td>
															</tr>
														 </tbody>";
														
													$query->MoveNext();
												}
													
												
											}else{
												echo "<tr>
															<td><b>No hay pagos</b></td>
														</tr>";
											}
											//mysql_free_result($query->_queryID);					
											?>
											
											
											
							</tbody>
										</table>
						<!--</div>-->
						<br />
						<div class="no-print">
							<?php 
								//if(isset($_GET['mn'])){
									$nr=$_GET['mn'];
									$next=$_SESSION['next'][$nr];
								//}
								if(isset($_SESSION['dni_rs'])){
									
									$key = array_search($id, $_SESSION['dni_rs']);//posicion del cliente 
									$dni_cl=$_SESSION['dni_rs'][$key+1];
									
								}else{
									$key = array_search($id, $_SESSION['dni']);//posicion del cliente 
									$dni_cl=$_SESSION['dni'][$key+1];
									//echo count($_SESSION['dni'])."-".$key;

								}

							?>
							
							<?php if(!isset($_GET['editid2'])){ ?>
										<input class="btn" type="button" value="Siguiente &raquo;" onclick="self.location.href='functions/next.php?gestion=1&idCl=<?php echo $dni_cl;?>'" />&nbsp;&nbsp;&nbsp;
							<?php }else{ ?>
										<input type="text" style="visibility:hidden;position:absolute;" id="editid2" value="<?php echo $_GET['editid2']; ?>"/>
							<?php }?>
							<input class="btn" type="button" value="Regresar" onclick="location.href='<?php if(!isset($_SESSION['admin'])){ echo "index.php";}else{echo "index.php?gestion=1";}?>';" />&nbsp;&nbsp;&nbsp;<br /><br />
						</div>
		</div> 
		