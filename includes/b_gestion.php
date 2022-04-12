<?php $id_pro=$_SESSION['idpro'];?>

<div class="TabbedPanelsContent"> 
				<!-- Gestion -->
				<div id="areaForm">
					<div class="zpFormContent">
						
							
						
						<table width="100%">
							<tr>
								<td>
									<!-- Seccion de gestión -->
									<?php
										 $tam_fl="";
										 if($_SESSION['cartera']==34 or $_SESSION['cartera']==37){
											$tam_fl="min-height:660px;";
										 }
									?>
									<fieldset style="<?php echo $tam_fl;?>"><legend>Proceso de Gesti&oacute;n</legend>
										
											<form name="frmDatos" id="userForm" class="zpForm" method="post" style="text-align:left">

												<label for="cuenta2" class="zpFormLabel">Cuenta:</label>
												<?php
													$periodo = $_SESSION['periodo'];
													$cartera=$_SESSION['cartera'];
													if($_SESSION['prove']!=14 and $_SESSION['prove']!=22){
														$sel="AND ct.idcartera='$cartera'";
													}else{$sel="";}
													if($_SESSION['prove']==14 or $_SESSION['prove']==22){
														$sel="AND pr.idproveedor=".$_SESSION['prove']." ";
													}
													//echo $_SESSION['prove
													//$db->debug=true;
													$cuenta2=$db->Execute("SELECT cu.idcuenta,c.cliente,cp.impcap,
																			m.simbolo
																			FROM clientes c
																			JOIN cuentas cu ON c.idcliente=cu.idcliente
																			join monedas m on cu.idmoneda=m.idmoneda
																			JOIN cuenta_periodos cp ON cu.idcuenta=cp.idcuenta
																			JOIN carteras ct ON cu.idcartera=ct.idcartera
																			JOIN proveedores pr ON ct.idproveedor=pr.idproveedor
																			WHERE c.idcliente='$id' and cp.idperiodo='$periodo' $sel and cp.idestado!='0'
																			
																			");
													$total = $cuenta2->_numOfRows;
												?>
												<select id="cuenta2" name="cuenta2" class="zpFormRequireds" multiple size="<?php echo ++$total;?>">
												
												<?php 		
														$prv=$_SESSION['prove'];	
														//if(!$dir){
														
														//}else{		
															$g=1;	
															while(!$cuenta2->EOF){
																$moneda[$g]=$cuenta2->fields['simbolo'];
																$cta_click=$cuenta2->fields['idcuenta'];
																if($g==1){$sele="SELECTED";}else{$sele="";}
																
																$impcap="";
																if($prv==14 or $prv==9 or $prv==20 or $prv==21 or $prv==22) {
																		$sp= explode(".",$cuenta2->fields['impcap']);
																		$tot_impt=strlen($sp[0]);
																		if($tot_impt>=4){
																			$impcap=substr_replace($sp[0],',',-3,0).".".$sp[1];
																		}else{
																			$impcap=$cuenta2->fields['impcap'];
																		}	
																}
																
																echo "<option onclick=\"tipo_m_g('".$cuenta2->fields['idcuenta']."');\" value='".$cuenta2->fields['idcuenta']."' ".$sele.">".$cuenta2->fields['idcuenta']." ".$cuenta2->fields['simbolo']."$impcap</option>";
																$cli=utf8_encode($cuenta2->fields['cliente']);
																$cuenta2->MoveNext();
																++$g;
															}
															//mysql_free_result($cuenta2->_queryID);
														//}
											    ?>
												</select>
												
												<br />
												<script type="text/javascript">
													tipo_m_g('<?php echo $cta_click;?>');
												</script>

												<label for="cliente" class="zpFormLabel">Cliente:</label>
													<input name="clientes" type="text" class="zpFormNotRequired" id="clientes" value="<?php echo $cli;?>" size="40" disabled />
													<?php if($_SESSION['cartera']==52){?>
													<input type="button" class="btn" value="Entrega TC" onclick="pop_up('entrega_tc')"/>
													<input type="button" class="btn" value="Validar TC" onclick="pop_up('validacion_tc')"/>
													<?php }?>
													<br/>
													
													<label for="fechagestion" class="zpFormLabel">Fecha Gesti&oacute;n:</label>
												<input id="g_fg" type="text" class="zpFormRequired zpFormDates" name="fechagestion" id="fechagestion" onkeyup="fech();" value="<?php if(!isset($_SESSION['campo'])) { echo date("Y-m-d");}else{echo date("Y-m-");}?>" size="11" maxlength="10"<?php if(!isset($_SESSION['campo'])) { echo "disabled";}?>/>(aaaa-mm-dd)
													
													<script type="text/javascript">
														Calendar.setup(	{
															inputField : "g_fg", // ID of the input field
															ifFormat : "%Y-%m-%d", // the date format
															button : "bcalendario2", // ID of the button
															weekNumbers : false,
															range : [1900, 2050] } );
														</script><br />
													<?php 
														$styl_tv_i="";
														if($_SESSION['cartera']==52){
															$styl_tv_i="visibility: hidden;position:absolute;";
														}
													
													?>
													<div id="ind_tv" style="<?php echo $styl_tv_i;?>">
														<label for="indicador" class="zpFormLabel">Indicador:</label>
														<select id="g_ig" name="indicador" class="zpFormRequired" onchange="s_fono();">
														<option value="">Seleccione...</option>
															<?php
																	$cart_in=$_SESSION['cartera'];
																	if($_SESSION['prove']==30 and $_SESSION['cartera']!=52){
																		$cart_in=51;
																	}
																	$filas= $dom->getElementsByTagName( "actividades_".$cart_in );
																	
																	foreach ($filas as $fila){
																		if($_SESSION['cartera']==52){
																		  $sel_tv_i="";
																		  if($fila->getElementsByTagName( "idactividad" )->item(0)->nodeValue==1){
																			$sel_tv_i="SELECTED";
																		  }
																		}
																		//if($_SERVER['REMOTE_ADDR']!="192.168.50.44"){if($fila->getElementsByTagName( "idactividad" )->item(0)->nodeValue>=26){break;}}
																		if($_SESSION['prove']==30 and $_SESSION['cartera']!=52){ $acti=substr($fila->getElementsByTagName( "actividad" )->item(0)->nodeValue,3);}else{$acti=$fila->getElementsByTagName( "actividad" )->item(0)->nodeValue;}
																		echo "<option value='".$fila->getElementsByTagName( "idactividad" )->item(0)->nodeValue."' $sel_tv_i>".$acti."</option>";
																	
																	
																	}
																	
																	/*$total_a=count($_SESSION['actividad']);
																	for($i=1;$i<=$total_a;$i++){
																		$actividad=explode("*",$_SESSION['actividad'][$i]);
																		echo "<option value='".$actividad[1]."'>".$actividad[0]."</option>";
																	}*/
																
															?>
														</select><br />
													</div>
												<div id="show_rg" >
													<label for="resultado" class="zpFormLabel">Resultado Gesti&oacute;n:</label>
													
												<select id="resultado_gs" name="resultado" class="zpFormRequired" <?php if(!isset($_SESSION['campo'])) {echo "onchange=\"res_ges();\""; }else{ echo "onchange=\"filtro_rsg();\"";}?> >
													<option value="">Seleccione...</option>
													<?php
																	if($_SESSION['cartera']!=51){
																		$filas= $dom->getElementsByTagName( "resultados_".$_SESSION['cartera'] );
																		
																		foreach ($filas as $fila){
																			//echo "<option value='".$fila->getElementsByTagName( "idcompromisos" )->item(0)->nodeValue."-".$fila->getElementsByTagName( "idresultado" )->item(0)->nodeValue."-".$fila->getElementsByTagName( "idresultadocartera" )->item(0)->nodeValue."'>".$fila->getElementsByTagName( "resultado" )->item(0)->nodeValue."</option>";
																		}
																	}
																	if($_SESSION['cartera']==52){
																		$total_a=count($_SESSION['result']);
																		for($i=1;$i<=$total_a;$i++){
																			$result=explode("*",$_SESSION['result'][$i]);
																			echo "<option value='".$result[0]."'>".$result[1]."</option>";
																		}
																	}
													?>

													 
														
												</select><br />
												</div>
												
												<div id="show_j" <?php if($_SESSION['prove']==30 and $_SESSION['cartera']!=52){ echo "style='visibility:hidden;position:absolute;'";} ?> >
													<label for="detalle" class="zpFormLabel">Detalle:</label>
													<select id="det_ges" name="resultado" class="zpFormRequired" onchanges="tv_box();" >
													<option value="">-----</option>
													<?php if($_SESSION['prove']==30 and $_SESSION['cartera']!=52){ echo '<option value="1" SELECTED >Por defecto</option>';} ?>
													</select><br />
												</div>
												
												<!--<div id="div_justificacion" style="visibility:hidden;">
													<label for="justificacion_a" class="zpFormLabel">Justificaci&oacute;n:</label>	
													<select name="justificacion_a" class="zpFormRequired"></select><br />
												</div>-->
												
												<div id="resultados" style="visibility: hidden;position:absolute;">
													<label for="fechacompromiso" class="zpFormLabel">F. Compromiso:</label>
													<input id="desde" type="text" onchange="fec_ges();" class="zpFormRequired zpFormDates"   size="11" maxlength="10"  />
													<button id="bcalendario1" onclick="fec_ges();">...</button>
														<script type="text/javascript">
															Calendar.setup(	{
																inputField : "desde", // ID of the input field
																ifFormat : "%Y-%m-%d", // the date format
																button : "bcalendario1", // ID of the button
																weekNumbers : false,
																range : [1900, 2050] } );
														</script><br />
													<br />
													<label for="moneda" class="zpFormLabel">Moneda:</label>
													<input id="g_mg" type="text" size="11" class="zpFormNotRequired" name="moneda" value="" disabled /><br />
													<label for="importecompromiso" class="zpFormLabel">Imp. Compromiso:</label>
													<input id="g_icg" type="text" class="zpFormRequired zpFormFloat" name="importecompromiso" id="importecompromiso" value=""  size="10" maxlength="15" style="text-align:right" /><br /><!--<br /> -->
												</div>

												<div id="div_contacto">
													<div id="tipc_gestion">	
												
													<label for="tipo_contacto" class="zpFormLabel">Tipo de Contacto:</label>
														<select id="g_tcg" name="tipo_contacto" class="zpFormRequired"  >
																<option value="">Seleccione...</option>
																<?php
																	$cart_tpc=$_SESSION['cartera'];
																	if($_SESSION['prove']==30 and $_SESSION['cartera']!=52){
																		$cart_tpc=51;
																	}
																	$filas= $dom->getElementsByTagName( "contactabilidad_".$cart_tpc );
																	
																	foreach ($filas as $fila){
																		echo "<option value='".$fila->getElementsByTagName( "idcontactabilidad" )->item(0)->nodeValue."'>".$fila->getElementsByTagName( "contactabilidad" )->item(0)->nodeValue."</option>";
																	}
																	
																	/*$total_a=count($_SESSION['contacto']);
																	for($i=1;$i<=$total_a;$i++){
																		$contacto=explode("*",$_SESSION['contacto'][$i]);
																		echo "<option value='".$contacto[0]."'>".$contacto[1]."</option>";
																	}*/
																
																?>
															
													</select><br />
													</div>
													<div id="telf_gestion">
													<label for="telefono_gestion" class="zpFormLabel">Telef. Gestionado:</label>
														<?php
															$sql=$db->Execute("select idtelefono,telefono from telefonos where idcliente='$id' and idestado=1");
															$tot=$sql->_numOfRows;
															++$tot;	
														?>
														<select id="g_tg" name="telefono_gestion" class="zpFormRequired">
															
																<?php
																	if($sql->_numOfRows==0){
																		echo "<option value='0'>Sin Telefono</option>";
																	}else{
																		echo "<option value=''>Seleccione...</option>";
																	}
																	
																	while(!$sql->EOF){
																		echo "<option value='".$sql->fields['idtelefono']."'>".$sql->fields['telefono']."</option>";
																		$sql->MoveNext();	
																	}
																	//mysql_free_result($sql->_queryID);
																	
																?>
															
														</select><br />
														</div>
												<div id="dat_email" style='visibility:hidden;position:absolute;'>		
													<label for="validacion_t" class="zpFormLabel" >Email:</label>
								
													<select id="gst_email" name="emails" class="zpFormRequired" >
														<option value="">Seleccione...</option>
														<?php
															$filas= $dom->getElementsByTagName( "origen_mail" );
																	
															foreach ($filas as $fila){
																echo "<option value='".$fila->getElementsByTagName( "idorimail" )->item(0)->nodeValue."'>".$fila->getElementsByTagName( "orimail" )->item(0)->nodeValue."</option>";
															}
															/*$sql=$db->Execute("select idemail,email from emails where idestado=1 and idcliente='$id'order by idemail");
															while(!$sql->EOF){
																echo "<option value='".$sql->fields['idemail']."'>".$sql->fields['email']."</option>";
																$sql->MoveNext();	
															}*/
															//mysql_free_result($sql->_queryID);
														?>
													</select><br />	
												</div>	
														
												<div id="dat_val" style="visibility:hidden;position:absolute;">		
													<?php if($_SESSION['cartera']!=52){?>
													<label for="validacion_t" class="zpFormLabel" >Validacion:</label>
								
													<select id="t_v" name="validacion" class="zpFormRequired" id="validacion_t">
														<option value="">Seleccione...</option>
														<?php
															
															$filas= $dom->getElementsByTagName( "validaciones" );
																	
															foreach ($filas as $fila){
																if($fila->getElementsByTagName( "idvalidacion" )->item(0)->nodeValue==3){$selec="SELECTED";}
																echo "<option value='".$fila->getElementsByTagName( "idvalidacion" )->item(0)->nodeValue."' $selec>".$fila->getElementsByTagName( "validacion" )->item(0)->nodeValue."</option>";
															}
																	/*$total_a=count($_SESSION['validacion']);
																	for($i=1;$i<=$total_a;$i++){
																		$validar=explode("*",$_SESSION['validacion'][$i]);
																		echo "<option value='".$validar[0]."'>".$validar[1]."</option>";
																	}*/
															
														?>
													</select><br />	
													<?php }else{?>
														
														<input type='text' id="t_v" name="validacion"  value='3' style="visibility:hidden;position:absolute;">
														<br />	
													<?php }?>
												</div>									
												<div id="ventas_tarjeta" style="visibility:hidden;position:absolute;">
													<label for="ape_pa" class="zpFormLabel">Apellido Paterno:</label>
													<input type="text" class="zpFormRequired" id="ape_pa" size="30"/><br />	
													
													<label for="ape_ma" class="zpFormLabel">Apellido Materno:</label>
													<input type="text" class="zpFormRequired" id="ape_ma" size="30"/><br />	
													
													<label for="tv_nom" class="zpFormLabel">Nombres:</label>
													<input type="text" class="zpFormRequired" id="tv_nom" size="30"/><br />	
													
													<label for="tv_fc" class="zpFormLabel">Fecha Nacimiento:</label>
													<input type="text" class="zpFormRequired" id="tv_fc" size="30"/><br />	
													
													<label for="tv_sx" class="zpFormLabel">Sexo:</label>
													<input type="text" class="zpFormRequired" id="tv_sx" size="30"/><br />	
													
													<label for="tv_dir" class="zpFormLabel">Direccion:</label>
													<input type="text" class="zpFormRequired" id="tv_dir" size="30"/><br />	
													
													<label for="tv_ref_dir" class="zpFormLabel">Ref. Direccion:</label>
													<input type="text" class="zpFormRequired" id="tv_ref_dir" size="30"/><br />	
													
													<label for="tv_ds" class="zpFormLabel">Distrito:</label>
													<input type="text" class="zpFormRequired" id="tv_ds" size="30"/><br />	
													
													<label for="tv_pr" class="zpFormLabel">Provincia:</label>
													<input type="text" class="zpFormRequired" id="tv_pr" size="30"/><br />	
													
													<label for="tv_dp" class="zpFormLabel">Departamento:</label>
													<input type="text" class="zpFormRequired" id="tv_dp" size="30"/><br />	
												</div>
												
												<div id="ventas_cita" style="visibility:hidden;position:absolute;">
													<label for="f_ent" class="zpFormLabel">Fecha Entrega:</label>
													<input type="text" class="zpFormRequired" id="f_ent" size="30"/><br />	
													
													<label for="h_ent" class="zpFormLabel">Hora Entrega:</label>
													<input type="text" class="zpFormRequired" id="h_ent" size="30"/><br />	
													
													<label for="d_ent" class="zpFormLabel">Direccion Entrega:</label>
													<input type="text" class="zpFormRequired" id="d_ent" size="30"/><br />	
													
													<label for="ds_ent" class="zpFormLabel">Distrito Entrega:</label>
													<input type="text" class="zpFormRequired" id="ds_ent" size="30"/><br />	
													
													<label for="p_ent" class="zpFormLabel">Provincia Entrega:</label>
													<input type="text" class="zpFormRequired" id="p_ent" size="30"/><br />	
													
													<label for="dp_ent" class="zpFormLabel">Dpto. Entrega:</label>
													<input type="text" class="zpFormRequired" id="dp_ent" size="30"/><br />	
													
													<label for="ref_dir_e" class="zpFormLabel">Ref.Lugar Entrega:</label>
													<input type="text" class="zpFormRequired" id="ref_dir_e" size="30"/><br />	

												</div>
												
												
												
													<!--Opcion Campo -->
												
												<?php
													if(!isset($_SESSION['campo'])){
														$sty="visibility:hidden;position:absolute;";
													}else{$sty="";}								

																	?>
												<div id="show_dir" style="<?php echo $sty;?>">
													<label for="direccion_gestion" class="zpFormLabel">Direcci&oacute;n:</label>
															<?php
																$sql=$db->Execute("SELECT iddireccion,direccion FROM direcciones WHERE idcliente='$id'");
											
															?>
														<select  id="g_drg"  name="direccion_gestion" class="zpFormRequired">
															<option value="">Seleccione...</option>
															<?php
																		while(!$sql->EOF){
																			echo "<option value='".$sql->fields['iddireccion']."'>".$sql->fields['direccion']."</option>";
																			$sql->MoveNext();	
																		}
																		//mysql_free_result($sql->_queryID);
															?>
														</select>
														<br />
												
												
															<label for="direccion_gestion" class="zpFormLabel">Direccion Correcta:</label>		
																					Si<input name="gca_dt"  type='radio' value='1' />
																					No<input name="gca_dt"  type='radio' value='0' checked />
																					No Validado<input name="gca_dt"  type='radio' value=''  />
																					<br />
												</div>
												
												
												<!-- Fin Opcion Campo -->
												</div>
												<label for="observaciones" class="zpFormLabel">Observaci&oacute;n:</label>
													<textarea id="g_obsg" name="observaciones" class="zpFormNotRequired" cols="50" rows="4"></textarea>
													<br /><br />
													<?php if($_SESSION['prove']==10 and $_SESSION['cartera']==9) {?>
														<label for="val_cta" class="zpFormLabel">Validacion Cta</label>
														<select id="val_cta" name="val_cta" class="zpFormRequired" onchange="val_d();" >
															<option value="">...Seleccione</option>
															<option value="0">Validado</option>
															<option value="1">NO, MI DIRECCION ES OTRA</option>
															<option value="SI, ES MI DIRECCION">SI, ES MI DIRECCION</option>
															<option value="ANTES DE LA FECHA DE VENCIMIENTO">ANTES DE LA FECHA DE VENCIMIENTO</option>
															<option value="DESPUES DE LA FECHA DE VENCIMIENTO">DESPUES DE LA FECHA DE VENCIMIENTO</option>
														</select><br/>
														<div id="val_det" style="visibility:hidden;position:absolute;">
															<label for="val_det_dir" class="zpFormLabel">Ingresar Direccion:</label>
															<input type="text" class="zpFormRequired" id="val_det_dir" size="40"/><br />	
														</div>
													<?php }?>	
													<label for="nuevatarea" class="zpFormLabel">Nueva Tarea<?php //echo $_SESSION['prove'];?></label>
													<input id="new_t" type="checkbox" name="nuevatarea" value="si" onchange="nueva_tarea();" /><br /><br/>
													
									</fieldset>
												<div id="div_tarea" style="visibility:hidden;position:absolute;">
													<fieldset><legend>Tarea</legend>
														<label for="fechatarea" class="zpFormLabel">Fecha:</label>
														<input type="text" class="zpFormRequired zpFormDates" name="fechatarea" id="fechatarea" value="<?php echo date("Y-m-d")?>" size="11" maxlength="10" onchange="fec_tar();" />
														<input type="button" id="bcalendario3" value=" ... " /> (aaaa-mm-dd)
														<script type="text/javascript">
														Calendar.setup(	{
															inputField : "fechatarea", // ID of the input field
															ifFormat : "%Y-%m-%d", // the date format
															button : "bcalendario3", // ID of the button
															weekNumbers : false,
															range : [1900, 2050] } );
														</script><br />
														<label for="horatarea" class="zpFormLabel">Hora:</label>
														<?php
															$hora=Date("H");
															$minutos=Date("i");
															if($minutos<="30"){
																$minutos="30";
															}else{
																++$hora;
																$minutos="00";
															}
														?>
														<input type="text" id="horatarea" name="horatarea" value="<?php echo $hora.":".$minutos; ?>" size="6" maxlength="5" class='zpFormRequired zpFormHour zpFormMask="00:00"' /> (HH:mm)<br />
														<label for="comentariotarea" class="zpFormLabel">Comentarios:</label>
														<input id="coment_tarea" type="text" name="comentariotarea" class="zpFormNotRequired" size="75" maxlength="100" value=""/><br />
													</fieldset>
												</div>
												
													<input type="button" class='btn' onclick="gs_<?php if(isset($_SESSION['campo'])) {echo"campo();";}else{echo"gs();";}?>" value="Aceptar" />
													<?php
														$prv=$_SESSION['prove'];
														$p_cart=$_SESSION['cartera'];	
														if($prv==2 and $p_cart==6){
															echo   "<input type='button' id='bz' onclick='gs_gs_r(1);' value='Buzon' />
																	<input type='button' id='cl' onclick='gs_gs_r(2);' value='Colgo' />
																	<input type='button' id='fx' onclick='gs_gs_r(3);' value='Fax' />";
														}
													?>
													<div id="e_gg" style="color:red" ></div>
												
										</form>						
								</td>
							</tr>
						</table>
						<table>
							<!--<tr>
								<td><b style="color:#FF0000">Tarea Pendiente:</b> <b>Fecha:</b> 06/01/2011 08:00 <b>Comentarios:</b>  </td>
								<td>&nbsp;&nbsp;&nbsp;<img src="images/ico_eliminar.gif" /></td>
								<td><a href="javascript:void(0);" onclick="eliminar_tarea();"><b>Eliminar</b></a></td>
							</tr>-->
						</table>
			</div>


			<br/>
	</div>
</div> 