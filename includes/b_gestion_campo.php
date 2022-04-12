<?php $id_pro=$_SESSION['idpro'];?>

<div class="TabbedPanelsContent"> 
				<!-- Gestion -->
				<div id="areaForm">
					<div class="zpFormContent">
						
						<table width="100%">
							<tr>
								<td>
									<!-- Seccion de gestión -->
									<fieldset style="min-height: 506px;"><legend>Proceso de Gesti&oacute;n</legend>
																				
											<?php if(isset($_SESSION['campo'])){ ?>	
												<label for="agente" class="zpFormLabel">Agente:</label>
												<select id="agente_gs" name="agente" class="zpFormRequired" onchange="">
													<option value="">Seleccione...</option>
													<?php
															$sql="SELECT idusuario,usuario from usuarios where idnivel=3 and idestado=1";
															$cuenta2=$db->Execute($sql);
															while(!$cuenta2->EOF){
																echo "<option value='".$cuenta2->fields['idusuario']."' >".$cuenta2->fields['idusuario']." - ".utf8_encode($cuenta2->fields['usuario'])."</option>";
																$cuenta2->MoveNext();
															}
															//mysql_free_result($cuenta2->_queryID);
													?>
												</select>
												<script>
															document.getElementById("agente_gs").focus();
												</script><br/>
												<label for="id_gca" class="zpFormLabel">Id Cliente:</label>
												<input id="gca_id" name="id_gca" type="text" size="25" class="zpFormRequired" onblur="gest_digi();" /><br />
												
											<?php } ?>	

												<label for="cliente" class="zpFormLabel">Cliente:</label>
													<input name="clientes" type="text" class="zpFormNotRequired" id="clientes" value="<?php echo $cli;?>" size="40" disabled />
													<br/>
													<?php if(isset($_SESSION['campo'])) {?>
													<label for="proveedor" class="zpFormLabel">Proveedor:</label><select id='prov' onchange='s_fono(13);' >
													<option value=''>-Seleccionar-</option>
													</select>
													<br/>
													 <label for="cartera" class="zpFormLabel">Cartera :</label><select id='id_c' onchange='tp_cont();' >
																 <option value=''>-Todos-</option>
																	</select>
													<br/>
													<?php }?>
													<label for="fechagestion" class="zpFormLabel">Fecha Gesti&oacute;n:</label>
												<input id="g_fg" type="text" class="zpFormRequired zpFormDates" name="fechagestion" id="fechagestion" onkeyup="return false;" onkeydown="return false;" onfocus="return false;" onclick="return false;"value="<?php //if(!isset($_SESSION['campo'])) { echo date("Y-m-d");}else{echo date("Y-m-");}?>" size="11" maxlength="10"<?php if(!isset($_SESSION['campo'])) { echo "disabled";}?>/>
													<button id="bcalendario2" onclick="fec_ges();">...</button>
													<?php if(isset($_SESSION['campo'])) {?>
													<script type="text/javascript">
														function fech(){
															var fech=document.getElementById("g_fg").value
															var total=fech.length
															fecha = new Date()
															ano = fecha.getFullYear()
															
																if(total=="4"){
																	if (fech > ano ){
																		document.getElementById("g_fg").value=''
																		return 0		
																	}
																	document.getElementById("g_fg").value=fech+"-"
																}
																if(total=="7"){
																	mes_f=fech.split("-")
																	if(mes_f[1] > 12){
																		document.getElementById("g_fg").value=''
																		return 0
																	}
																	document.getElementById("g_fg").value=fech+"-"
																}
																
																if(total=="10"){
																	mes_d=fech.split("-")
																	if(mes_d[2] > 31){
																		document.getElementById("g_fg").value=''
																		return 0
																	}
																}
														}
													</script><br />
													
													<script type="text/javascript">
														Calendar.setup(	{
															inputField : "g_fg", // ID of the input field
															ifFormat : "%Y-%m-%d", // the date format
															button : "bcalendario2", // ID of the button
															weekNumbers : false,
															range : [1900, 2050] } );
														</script><br />
													<?php }else {?>
													<script type="text/javascript">
														Calendar.setup(	{
															inputField : "g_fg", // ID of the input field
															ifFormat : "%Y-%m-%d", // the date format
															button : "bcalendario2", // ID of the button
															weekNumbers : false,
															range : [1900, 2050] } );
														</script><br />
													<?php } ?>
												<?php if(isset($_SESSION['campo'])) {?>		
													<label for="horagestion" class="zpFormLabel">Hora Gesti&oacute;n:</label>
												<input id="g_hg" type="text" class="zpFormRequired zpFormDates" name="horagestion" onkeyup="hora();" onfocus="if(this.value=='00:00'){this.value=''}" onblur="if(this.value==''){this.value='00:00'};" value="00:00" size="5" maxlength="5"/>(HH:mm)<br/>
												<script type="text/javascript">
														function hora(){
															var hora=document.getElementById("g_hg").value
															var total=hora.length
																if(total=="2"){
																	if(hora>21 || hora<7){
																	document.getElementById("g_hg").value=''
																	return 0
																	}
																	document.getElementById("g_hg").value=hora+":00"
																}
														}
												</script>
												<?php }?>	
												<div id="show_rg" >
													<label for="resultado" class="zpFormLabel">Resultado Gesti&oacute;n:</label>
													
												<select id="resultado_gs" name="resultado" class="zpFormRequired" <?php if(!isset($_SESSION['campo'])) {echo "onchange=\"res_ges();\""; }else{ echo "onchange=\"filtro_rsg();\"";}?> >
													<option value="">Seleccione...</option>
													<?php
															
															 if(!isset($_SESSION['campo'])) {
																$sql="SELECT r.idcompromisos,r.idresultado,rc.idresultadocartera,gp.grupogestion,r.resultado,c.cartera  FROM resultado_carteras rc
																JOIN resultados r ON rc.idresultado=r.idresultado
																JOIN carteras c ON rc.idcartera=c.idcartera
																JOIN proveedores p ON c.idproveedor=p.idproveedor																																
																JOIN grupo_gestiones gp ON r.idgrupogestion=gp.idgrupogestion WHERE rc.idestado=1 AND r.idestado=1 AND r.flag!=1 AND p.idproveedor=$id_pro AND c.idcartera='$cartera' ";
															 }else{
																	$sql=" SELECT * FROM resultados r
																	JOIN grupo_gestiones gg ON r.idgrupogestion=gg.idgrupogestion																
																	WHERE r.idestado=1 AND flag=1";
															 }
															
							
															$cuenta2=$db->Execute($sql);
															while(!$cuenta2->EOF){
																if(!isset($_SESSION['campo'])) {
																	echo "<option value='".$cuenta2->fields['idcompromisos']."-".$cuenta2->fields['idresultado']."-".$cuenta2->fields['idresultadocartera']."' >".utf8_encode($cuenta2->fields['resultado'])."</option>";
																}else{
																	echo "<option value='".$cuenta2->fields['idcompromisos']."-".$cuenta2->fields['idresultado']."' >".utf8_encode($cuenta2->fields['resultado'])."</option>";
																}
																$cuenta2->MoveNext();
															
															}
															//mysql_free_result($cuenta2->_queryID);
													?>

													 
														
												</select><br />
												</div>
												
												<div id="g_tcg_c">
												<label for="tipo_contacto" class="zpFormLabel">Tipo de Contacto:</label>
														<select id="g_tcg" name="tipo_contacto" class="zpFormRequired" >
																<option value="">Seleccione...</option>
																<?php
																 if(!isset($_SESSION['campo'])) { 
																 $sql="SELECT co.idcontactabilidad,cc.idcontactabilidadcartera,co.contactabilidad,c.cartera  
																				FROM contactabilidad_carteras cc
																				JOIN contactabilidad co ON cc.idcontactabilidad=co.idcontactabilidad
																				JOIN carteras c ON cc.idcartera=c.idcartera
																				JOIN proveedores p ON c.idproveedor=p.idproveedor																																
																				WHERE cc.idestado=1 AND co.idestado=1  AND p.idproveedor='$id_pro' AND c.idcartera='$cartera'";
																 $tpc=$db->Execute($sql);
																	while(!$tpc->EOF){
																		echo "<option value='".$tpc->fields['idcontactabilidad']."'>".$tpc->fields['contactabilidad']."</option>";
																		$tpc->MoveNext();
																	}
																	//mysql_free_result($tpc->_queryID);
																	
																}/*else{
																 $sql="SELECT idcontactabilidad,contactabilidad from contactabilidad where idestado='1'	";			
																}*/
																
																?>
															
														</select><br />
												</div>
												<div id='detalle_s_v'>		
												<label for="detalles" class="zpFormLabel">Persona que recepciona:</label>
												<input id="detalle_s" type="text" class="zpFormRequired zpFormDates" name="horagestion"  size="25"/>
												<br/><br/>
												</div>
												<div id="rs_camp" style="visibility: hidden;position:absolute;">
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
												
												<label for="detalles" class="zpFormLabel">Monto Soles:</label>
												<input id="sol_s" type="text" class="zpFormRequired zpFormDates" name="msoles" onblur="if(isNaN(this.value)){this.value=''};"  size="12"/>
												<br/>
												<label for="detalles" class="zpFormLabel">Monto Dolares:</label>
												<input id="dol_s" type="text" class="zpFormRequired zpFormDates" name="mdolares" onblur="if(isNaN(this.value)){this.value=''};" size="12"/>
												<br/>
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
													<input id="g_icg" type="text" class="zpFormRequired zpFormFloat" name="importecompromiso" id="importecompromiso" value="" onchange="validar_importe_compromiso();" size="10" maxlength="15" style="text-align:right" /><br /><!--<br /> -->
												</div>
												
											
											
												<div id="div_contacto">
													<div id="tipc_gestion">	
													
						<!-- Inicio Campo-->		<?php if(isset($_SESSION['campo'])){?>
														<div id="gc_dg_ver">
															<label for="gc_dg" class="zpFormLabel">Ubicabilidad:</label>				
															<select id="gc_dg" name="gc_dg" class="zpFormRequired" >
																<option value="">Seleccione...</option>
																<?php
																	 $sql="Select * from ubicabilidad where flag=1";
																	 $tpc=$db->Execute($sql);
																		while(!$tpc->EOF){
																			echo "<option value='".$tpc->fields['idubicabilidad']."'>".utf8_encode($tpc->fields['ubicabilidad'])."</option>";
																			$tpc->MoveNext();
																		}
																		//mysql_free_result($tpc->_queryID);
																	?>
															</select><br />
														</div>		
						<!-- Fin Campo -->			<?php }?>
							
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
													<?php //if($_SERVER['REMOTE_ADDR']=="192.168.50.44"){?>
														<label for="cuadrante_gestion" class="zpFormLabel">Cuadrante:</label>

															<input id="g_d_cd" type="text" size="11" class="zpFormNotRequired" name="g_d_cd" value=""  />
														<br />
													<?php //} ?>
												
												
															<label for="direccion_gestion" class="zpFormLabel">Direccion Correcta:</label>		
																					Si<input name="gca_dt"  type='radio' value='1' />
																					No<input name="gca_dt"  type='radio' value='2' checked />
																					No Validado<input name="gca_dt"  type='radio' value='3'  />
																					<br />
												</div>
												<?php if(isset($_SESSION['campo'])){ ?>
													<div id="dt_dr">									
												<label for="tipo_predio" class="zpFormLabel">Tipo Predio:</label>	
													<select  id="gca_tp"  name="tipo_predio" class="zpFormRequired">
														<option value="">Seleccione...</option>
														<?php
																	$sql=$db->Execute("select * from tipo_predio where idestado=1");
																	while(!$sql->EOF){
																		echo "<option value='".$sql->fields['idpredio']."'>".$sql->fields['tipo_predio']."</option>";
																		$sql->MoveNext();	
																	}
																	//mysql_free_result($sql->_queryID);
														?>
													</select><br />
												<label for="material" class="zpFormLabel">Material:</label>	
													<select  id="gca_m"  name="material" class="zpFormRequired">
														<option value="">Seleccione...</option>
														<?php
																	$sql=$db->Execute("select * from material_predio where idestado=1");
																	while(!$sql->EOF){
																		echo "<option value='".$sql->fields['idmaterial_predio']."'>".$sql->fields['material']."</option>";
																		$sql->MoveNext();	
																	}
																	//mysql_free_result($sql->_queryID);
														?>
													</select><br />
												<label for="n_pisos" class="zpFormLabel">Nro Pisos:</label>	
													<select  id="gca_nrp"  name="n_pisos" class="zpFormRequired">
														<option value="">Seleccione...</option>
														<?php
																	$sql=$db->Execute("select * from nro_pisos where idestado=1");
																	while(!$sql->EOF){
																		echo "<option value='".$sql->fields['idnro_pisos']."'>".$sql->fields['piso']."</option>";
																		$sql->MoveNext();	
																	}
																	//mysql_free_result($sql->_queryID);
														?>
													</select><br />
												<label for="color" class="zpFormLabel">Color:</label>	
													<select  id="gca_c"  name="color" class="zpFormRequired">
														<option value="">Seleccione...</option>
														<?php
																	$sql=$db->Execute("select * from colores_pared where idestado=1");
																	while(!$sql->EOF){
																		echo "<option value='".$sql->fields['idcolor_pared']."'>".$sql->fields['color']."</option>";
																		$sql->MoveNext();	
																	}
																	//mysql_free_result($sql>_queryID);
														?>
													</select><br />
													</div>	
												<?php }?>
												
												<!-- Fin Opcion Campo -->
												</div>
												<label for="observaciones" class="zpFormLabel">Observaci&oacute;n:</label>
													<textarea id="g_obsg" name="observaciones" class="zpFormNotRequired" cols="50" rows="4"></textarea>
													<br /><br />
														
									</fieldset>
												
												<div class="zpFormButtons">
													<input type="button" id='btn_gest' onclick="gs_<?php if(isset($_SESSION['campo'])) {echo"campo();";}else{echo"gs();";}?>" value="Aceptar" />
													<div id="e_gg" style="color:red" ></div>
												</div>
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
<?php if(isset($_SESSION['campo'])) {?>	
			<input id="tel_ca" style="display:none;" type="button" onclick="c_ver_dt();" value="Grabar" />
			<input id="prove_ca" style="display:none;" type="button" onclick="provee();" value="Grabar" />
			<input id="dir_ca" style="display:none;" type="button" onclick="c_ver_dt2();" value="Grabar" />
			<input id="resu_ca" style="display:none;" type="button" onclick="c_ver_r();" value="Grabar" />			
			<div id="pos" style="overflow: scroll;float:left;position:relative;width:46%;height:240px;left:52%;bottom:560px;border-left: solid 1px #09C;border-top: solid 1px #09C;">
				<div id="historial" style="width:550px;">
					<table width="100%" id="tabla_gestiones_campo">
					<caption style='color: #27688F;background-color: #CDE9F5;'>Detalle Historico de Visitas <caption/>
					</table>					
				</div>
			</div>
			<div id="TabbedPanels2" class="TabbedPanels" style="position:relative;float:left;left:52%;bottom:544px;">
				<ul class="TabbedPanelsTabGroup">
					<li class="TabbedPanelsTab" tabindex="0">Tel&eacute;fonos</li>
					<li class="TabbedPanelsTab" tabindex="1">Direcciones</li>
				</ul>
					<div class="TabbedPanelsContentGroup" style="width:46%;">

						<div class="TabbedPanelsContent" > 

							<div style="width:560px;height:216px;" id="tab1" >
												<table width="100%" id="design4" align="left"  >
													<tr><td>Prefijo</td><td>N&uacute;mero Telef&oacute;nico</td><td>Anexo</td><td>Origen</td><td>Corregir</td><td></td></tr>
													<tr>
														<td><input id="pre_fono"type="text" size="5"/></td>
														<td><input id="camp_fono" type="text" size="20"/></td>
														<td><input id="a_fono" type="text" size="5"/></td>
														<td><select id="camp_ot" name="origentelefono" class="zpFormRequired" id="origentelefono">
																<option value="">Seleccione...</option>
																<?php
																	$sql=$db->Execute("select * from origen_telefonos where idestado=1 order by origentelefono");
																	while(!$sql->EOF){
																		echo "<option value='".$sql->fields['idorigentelefono']."'>".$sql->fields['origentelefono']."</option>";
																		$sql->MoveNext();	
																	}
																	//mysql_free_result($sql->_queryID);
																?>
															</select>
														</td>
														<td><input type="hidden" id="telup"  value="" /></td>
														<td><div class="zpFormButtons">
																<input  style="float:right" id ="bt_telgsc" type="button" onclick="camp_insert('tel');" value="Grabar" />
																<div id="e_gg_ct" ></div>
															</div>
														</td>
													</tr>
												</table>
											<div id="rpta_t_c" style="float:left;overflow: auto;width:550px;height:134px;">
												
											</div>
												
							</div>
							
						</div>
						<div class="TabbedPanelsContent" >
						
							<div style="width:560px;height:216px;" id="tab2" >
											<table id="design4" align="left"  >
													<tr><td>Direcci&oacute;n</td><td>Ubigeo</td></tr>
													<tr>
														<td><input id="camp_dir" type="text" size="25"/></td>
														<td colspan="2"><select id="camp_ubi" name="origen" class="zpFormRequired" id="origen"/>
															<option value="">Seleccione...</option>
																<?php
																	$sql=$db->Execute("SELECT idubigeo,coddpto,codprov,coddist,nombre FROM ubigeos  WHERE coddist!=00");
																	$t_regist=$sql->_numOfRows;
																
																	$xj=1;
																	while(!$sql->EOF){
																		
																		$idubigeo=$sql->fields['idubigeo'];
																		$coddpto=$sql->fields['coddpto'];
																		$codprov=$sql->fields['codprov'];
																		
																		if($xj!=1){
																			if($coddpto!=$_SESSION['c_dpto']){
																				$dpto=$db->Execute("SELECT nombre,coddpto FROM ubigeos WHERE codprov=00 AND coddist=00 AND coddpto='$coddpto'");
																				$_SESSION['dpto']=$dpto->fields['nombre'];
																				$dpt=$_SESSION['dpto'];	
																				$_SESSION['c_dpto']=$coddpto;
																			}
																			
																			if($codprov!=$_SESSION['c_prov']){		
																				$prov=$db->Execute("SELECT nombre,codprov FROM ubigeos WHERE codprov='$codprov' AND coddist=00 AND coddpto='$coddpto'");
																				$_SESSION['prov']=$prov->fields['nombre'];
																				$provi=$_SESSION['prov'];
																				$_SESSION['c_prov']=$codprov;
																			}	
																		}else{
																			$dpto=$db->Execute("SELECT nombre,coddpto FROM ubigeos WHERE codprov=00 AND coddist=00 AND coddpto='$coddpto'");
																			$_SESSION['c_dpto']=$coddpto;
																			$_SESSION['dpto']=$dpto->fields['nombre'];
																			$dpt=$_SESSION['dpto'];	
																			
																			$prov=$db->Execute("SELECT nombre,codprov FROM ubigeos WHERE codprov='$codprov' AND coddist=00 AND coddpto='$coddpto'");
																			$_SESSION['c_prov']=$codprov;
																			$_SESSION['prov']=$prov->fields['nombre'];
																			$provi=$_SESSION['prov'];
																		}
																	
																		$dist=$sql->fields['nombre'];
																																												
																		echo "<option value='".$idubigeo."'>".utf8_encode($dpt."-".$provi."-".$dist)."</option>";
																		$sql->MoveNext();
																		$xj++;
																	}	
																	//mysql_free_result($sql->_queryID);
																?>	
															</select>
														</td>
														
													</tr>
													<tr><td>Origen
															<select id="camp_od" name="origen" class="zpFormRequired" id="origen" onchange="if(this.value!='') {document.getElementById('orig').className='zpIsValid'}else{document.getElementById('orig').className='zpNotValid'}">
																<option value="">Seleccione...</option>
																<?php
																	$sql=$db->Execute("select * from origen_direcciones");
																	while(!$sql->EOF){
																		echo "<option value='".$sql->fields['idorigendireccion']."'>".$sql->fields['origendireccion']."</option>";
																		$sql->MoveNext();	
																	}
																	//mysql_free_result($sql->_queryID);
																?>	
															</select></td>
															
															<td><div class="zpFormButtons" style="float:right;">
																<input id="dir_gca" type="button" onclick="camp_insert('dir');"  value="Grabar" />
																<input id="dir_up" type="hidden"  />
																<div id="e_gg_cd" ></div>
															</div></td>
													</tr>
													
											</table>
										
										<div id="rpta_d_c" style="float:left;overflow: auto;width:550px;height:134px;">
											
										</div>	
											
							</div>
						</div>
					</div>
			</div>
<?php }?>
	</div>
</div> 