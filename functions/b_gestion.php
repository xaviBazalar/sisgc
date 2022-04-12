<?php $id_pro=$_SESSION['idpro'];?>

<div class="TabbedPanelsContent"> 
				<!-- Gestion -->
				<div id="areaForm">
					<div class="zpFormContent">
						
							<div id="areaList">
								<a href="javascript:imprimir()"><span class="impresion"></span></a>
								<!-- D A T O S  D E L  C L I E N T E -->
								<table width="100%" id="design4">
									<caption>Datos del cliente</caption>
									<tr class="noborde">
										<td valign="top">
											<table width="100%" id="design3">
												<tr>
													<td class="cellhead">Cliente:</td><td><?php echo utf8_encode($cliente->fields['cliente']);?></td>
													<?php
															echo 	"<td  class='cellhead'>Direccion:</td><td>".utf8_encode($d_t->fields['direccion'])."</td>
																	<td  class='cellhead'>Tel:</td><td>".$d_t->fields['telefono']."</td>";

													?>
													<td class="cellhead">Personeria:</td><td><?php echo utf8_encode($cliente->fields['personerias']);?></td>
													<td class="cellhead">Documento:</td><td><?php echo utf8_encode($cliente->fields['doi'])."-";?><?php echo utf8_encode($cliente->fields['idcliente']);?></td>
												</tr>
											</table>
											<?php if($_SESSION['prove']==14){ ?>
													<table width="62%" id="design3">
														<tr>
															<td  class="cellhead">Contacto:</td><td></td>
															<td class="cellhead">&Aacute;rea:</td><td></td>
															<td class="cellhead">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
																	
															<td class="cellhead">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
															<td class="cellhead">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
														</tr>
													</table>
											<?php } ?>
										</td>
									</tr>
								</table>
							</div>
						
						<table width="100%">
							<tr>
								<td>
									<!-- Seccion de gestión -->
									<fieldset style="min-height:510px;"><legend>Proceso de Gesti&oacute;n</legend>
										
											<form name="frmDatos" id="userForm" class="zpForm" method="post" style="text-align:left">

												<label for="cuenta2" class="zpFormLabel">Cuenta:</label>
												<?php
													$periodo = $_SESSION['periodo'];
													$cartera=$_SESSION['cartera'];
												//	if($_SESSION['prove']!=2){
														$sel="AND ct.idcartera='$cartera'";
													//}else{$sel="";}
													$cuenta2=$db->Execute("SELECT cu.idcuenta,c.cliente,
																			m.simbolo
																			FROM clientes c
																			JOIN cuentas cu ON c.idcliente=cu.idcliente
																			join monedas m on cu.idmoneda=m.idmoneda
																			JOIN cuenta_periodos cp ON cu.idcuenta=cp.idcuenta
																			JOIN carteras ct ON cu.idcartera=ct.idcartera
																			WHERE c.idcliente='$id' and cp.idperiodo='$periodo' $sel and cp.idestado!='0'
																			
																			");
													$total = $cuenta2->_numOfRows;
												?>
												<select id="cuenta2" name="cuenta2" class="zpFormRequireds" multiple size="<?php echo ++$total;?>">
												
												<?php 		
														if(!$dir){
														
														}else{		
															$g=1;	
															while(!$cuenta2->EOF){
																$moneda[$g]=$cuenta2->fields['simbolo'];
																$cta_click=$cuenta2->fields['idcuenta'];
																if($g==1){$sele="SELECTED";}else{$sele="";}
																echo "<option onclick=\"tipo_m_g('".$cuenta2->fields['idcuenta']."');\" value='".$cuenta2->fields['idcuenta']."' ".$sele.">".$cuenta2->fields['idcuenta']."</option>";
																$cli=utf8_encode($cuenta2->fields['cliente']);
																$cuenta2->MoveNext();
																++$g;
															}
															//mysql_free_result($cuenta2->_queryID);
														}
											    ?>
												</select>
												<select multiple size="<?php echo ++$total;?>" style="overflow:hidden;">
												
												<?php 		
														for($i=1;$i<=count($moneda);$i++){
															echo "<option  value='".$moneda[$i]."'>".$moneda[$i]."</option>";
														}
											    ?>
												</select><br />
												<script type="text/javascript">
													tipo_m_g('<?php echo $cta_click;?>');
												</script>

												<label for="cliente" class="zpFormLabel">Cliente:</label>
													<input name="clientes" type="text" class="zpFormNotRequired" id="clientes" value="<?php echo $cli;?>" size="40" disabled />
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
													
													
													<label for="indicador" class="zpFormLabel">Indicador:</label>
														<select id="g_ig" name="indicador" class="zpFormRequired" onchange="s_fono();">
														<option value="">Seleccione...</option>
															<?php
																	
																	$filas= $dom->getElementsByTagName( "actividades_".$_SESSION['cartera'] );
																	
																	foreach ($filas as $fila){
																		echo "<option value='".$fila->getElementsByTagName( "idactividad" )->item(0)->nodeValue."'>".$fila->getElementsByTagName( "actividad" )->item(0)->nodeValue."</option>";
																	}
																	
																	/*$total_a=count($_SESSION['actividad']);
																	for($i=1;$i<=$total_a;$i++){
																		$actividad=explode("*",$_SESSION['actividad'][$i]);
																		echo "<option value='".$actividad[1]."'>".$actividad[0]."</option>";
																	}*/
																
															?>
														</select><br />
												
												<div id="show_rg" >
													<label for="resultado" class="zpFormLabel">Resultado Gesti&oacute;n:</label>
													
												<select id="resultado_gs" name="resultado" class="zpFormRequired" <?php if(!isset($_SESSION['campo'])) {echo "onchange=\"res_ges();\""; }else{ echo "onchange=\"filtro_rsg();\"";}?> >
													<option value="">Seleccione...</option>
													<?php
																	
																	$filas= $dom->getElementsByTagName( "resultados_".$_SESSION['cartera'] );
																	
																	foreach ($filas as $fila){
																		echo "<option value='".$fila->getElementsByTagName( "idcompromisos" )->item(0)->nodeValue."-".$fila->getElementsByTagName( "idresultado" )->item(0)->nodeValue."-".$fila->getElementsByTagName( "idresultadocartera" )->item(0)->nodeValue."'>".$fila->getElementsByTagName( "resultado" )->item(0)->nodeValue."</option>";
																	}
																	
																	/*$total_a=count($_SESSION['result']);
																	for($i=1;$i<=$total_a;$i++){
																		$result=explode("*",$_SESSION['result'][$i]);
																		echo "<option value='".$result[0]."'>".$result[1]."</option>";
																	}*/
															
													?>

													 
														
												</select><br />
												</div>
												
												<div id="show_j">
													<label for="detalle" class="zpFormLabel">Detalle:</label>
													<select id="det_ges" name="resultado" class="zpFormRequired" onchange="tv_box();" >
													<option value="">-----</option>
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
																	$filas= $dom->getElementsByTagName( "contactabilidad_".$_SESSION['cartera'] );
																	
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
															$sql=$db->Execute("select idtelefono,telefono from telefonos where idcliente='$id'");
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
														
												<div id="dat_val" >		
													<?php if($_SESSION['prove']!=15){?>
													<label for="validacion_t" class="zpFormLabel" >Validacion:</label>
								
													<select id="t_v" name="validacion" class="zpFormRequired" id="validacion_t">
														<option value="">Seleccione...</option>
														<?php
															
															$filas= $dom->getElementsByTagName( "validaciones" );
																	
															foreach ($filas as $fila){
																echo "<option value='".$fila->getElementsByTagName( "idvalidacion" )->item(0)->nodeValue."'>".$fila->getElementsByTagName( "validacion" )->item(0)->nodeValue."</option>";
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
													<label for="encuesta" class="zpFormLabel">Encuesta?</label>
													Si<input name="e_rpta" id="s_rc" type="radio" value="1" onclick="encuesta(this.value);">  
													No<input  name="e_rpta" value="0" id="n_rc" type="radio"  onclick="encuesta(this.value);"><br/>
													
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
			
			<?php if($_SESSION['iduser']==1){?>
			<div id="encuesta_c" style="visibility:hidden;width:620px;position:absolute;left:620px;top:156px;font-family: 'lucida grande',tahoma,verdana,arial,sans-serif;color: #333;line-height: 1.28;font-size: 11px;">
				<?php
					//echo "<pre>";
					if(isset($_POST['p1'])){
						$n=0;
						$sql="insert into encuesta(idusuario";
						$val=") value (".$_SESSION['iduser'];
						foreach($_POST as $key=>$value){
							if($key=="p0"){continue;}
							++$n;
							$sql.=",".$key;
							$val.=",".$value;
							//echo $key."->".$value."<br/>";
						}
						$sql=$sql.$val.")";
						$rpta=$db->Execute($sql);
						
						$act="update usuarios set flag_e=1 where idusuario=".$_SESSION['iduser'];
						$db->Execute($act);
						echo "
							<script>
								location.href='../index.php';
							</script>
						";
						//echo $sql."<br>";
					}
				//	echo " $n &nbsp;".$_SESSION['iduser']."</pre>";
				?>
				<p align="center"><strong><span style="text-decoration: underline;font-size:14px;">Encuesta de Calidad Call Center</span></strong><strong><span style="text-decoration: underline;">( Postpago y Prepago )</span></strong></p>
			
			<p>Saludo: Buenos (d&iacute;as/tardes) (Sr. / Sra.), le&nbsp; saluda la se&ntilde;orita&hellip;&hellip;. De Claro. Usted se comunic&oacute; el d&iacute;a (de hoy, de ayer) al 123 a las________ (am/pm).<br/>
			El motivo de nuestra llamada es para realizarle una breve encuesta que nos permitir&aacute; mejorar nuestro servicio. Me permite unos minutos de su tiempo &iquest;?.</p>

			<ul>
			<li>Si ( Continuar con speech )  |  No ( Pasar a&nbsp; Despedida 2 )</li>
			</ul>
			<p>1.- En general, &nbsp;&iquest;C&oacute;mo califica la atenci&oacute;n recibida por el asesor?
			<select name="p1" id="p1" onchange="valp1();">
				<option value="">..Seleccione</option>
				<option value="1">Muy Buena</option>
				<option value="2">Buena</option>
				<option value="3">Regular</option>
				<option value="4">Mala</option>
			</select>
			</p> 
				<div id="box" style="visibility:hidden;position:absolute;">
					<p>2.- Porque considera que la atenci&oacute;n recibida por el asesor telef&oacute;nico fue&hellip;. (<strong>Mencionar resultado de P1</strong>)
					(<strong>NO LEER ALTERNATIVAS</strong>)
					<select name="p2" id="p2">
						<option value="">..Seleccione</option>
						<option value="1">Tiempo de espera( antes de ser atendido por el AS )</option>
						<option value="2">Tiempo de atenci&oacute;n ( durante la llamada )</option>
						<option value="3">Actitud del asesor</option>
						<option value="4">Conocimiento del asesor</option>
						<option value="4">Informaci&oacute;n</option>
						<option value="4">Soluci&oacute;n</option>
						<option value="4">Gesti&oacute;n del caso</option>
						<option value="4">Ninguna</option>
						<option value="4">Otros ( Especificar )</option>
					</select>
					</p> 
				</div>
			<p>3.- &iquest;C&oacute;mo califica la actitud del asesor para atenderlo?
			<select name="p3" id="p3">
				<option value="">..Seleccione</option>
				<option value="1">Muy Buena</option>
				<option value="2">Buena</option>
				<option value="3">Regular</option>
				<option value="4">Mala</option>
			</select>
			</p> 
			<p>4.- &iquest;C&oacute;mo califica la explicaci&oacute;n brindad por el asesor?
			<select name="p4" id="p4">
				<option value="">..Seleccione</option>
				<option value="1">Muy Buena</option>
				<option value="2">Buena</option>
				<option value="3">Regular</option>
				<option value="4">Mala</option>
			</select></p> 
			<p>5.- &iquest;C&oacute;mo califica la soluci&oacute;n brindad por el asesor para resolver su inconveniente o necesidad?
			<select name="p5" id="p5">
				<option value="">..Seleccione</option>
				<option value="1">Muy Buena</option>
				<option value="2">Buena</option>
				<option value="3">Regular</option>
				<option value="4">Mala</option>
			</select></p> 
			<p>6.- &iquest;C&oacute;mo califica el tiempo de atenci&oacute;n, es decir, desde que fue atendido por el asesor hasta que concluy&oacute;&nbsp; la llamada?
			<select name="p6" id="p6">
				<option value="">..Seleccione</option>
				<option value="1">Muy Buena</option>
				<option value="2">Buena</option>
				<option value="3">Regular</option>
				<option value="4">Mala</option>
			</select></p> 
			<p>7.- &iquest;C&oacute;mo veces tuvo que llamar para que su duda o inconveniente fuera resuelto? (<strong>No leer las opciones</strong>)
			<select name="p7" id="p7">
				<option value="">..Seleccione</option>
					<option value="">..Seleccione</option>
					<option value="1">Muy Buena</option>
					<option value="2">Buena</option>
					<option value="3">Regular</option>
					<option value="4">Mala</option>
			</select></p> 
			<p>8.- &iquest;Qu&eacute; sugerencia nos puede hacer para mejorar la atenci&oacute;n que brindamos mediante el 123?
			<select name="p8" id="p8">
				<option value="">..Seleccione</option>
				<option value="1">Muy Buena</option>
				<option value="2">Buena</option>
				<option value="3">Regular</option>
				<option value="4">Mala</option>
			</select></p> 
			<p><strong>Despedida 1</strong>: (Sr. / Sra.) Ha sido muy amable, sus respuesta nos permitir&aacute;n mejorar cada vez m&aacute;s el servicio a nuestro clientes.</p>
			<p><strong>Despedida 2</strong>: De acuerdo Sr.(a)-------- gracias por su tiempo y disculpe la molestia.</p>

				
				</form>
			</div>	
			<?php } ?>
			<br/>
	</div>
</div> 