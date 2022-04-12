		
		<div class="TabbedPanelsContent"> 
<?php if(!isset($_SESSION['campo'])){ ?>		
		<!-- Direcciones -->
			<div id="areaForm">
				
		
				<fieldset ><legend>Nueva Direcci&oacute;n</legend>
					
						
						<label for="origen" class="zpFormLabel">Origen:</label>
						<span style="-moz-user-select: none;" class="zpIsRequired">
							<span style="-moz-user-select: none;" class=" zpNotEditing">
								<span style="-moz-user-select: none;" class=" zpIsEmpty">
									<span id="orig" style="-moz-user-select: none;" class=" zpNotValid">
										<span style="-moz-user-select: none;" class="zpStatusImg">
										</span>
									</span>
								</span>
							</span>
						</span>
						<select id="g_od" name="origen" class="zpFormRequired" id="origen" onchange="if(this.value!='') {document.getElementById('orig').className='zpIsValid'}else{document.getElementById('orig').className='zpNotValid'}">
							<option value="">Seleccione...</option>
							<?php
									$filas= $dom->getElementsByTagName( "origen_dir" );
																	
									foreach ($filas as $fila){
										echo "<option value='".$fila->getElementsByTagName( "idoridir" )->item(0)->nodeValue."'>".$fila->getElementsByTagName( "origendir" )->item(0)->nodeValue."</option>";
									}
									
									/*$total_a=count($_SESSION['ori_direcc']);
									for($i=1;$i<=$total_a;$i++){
										$or_direcc=explode("*",$_SESSION['ori_direcc'][$i]);
										echo "<option value='".$or_direcc[0]."'>".$or_direcc[1]."</option>";
									}*/
								
							?>	
						</select><br />
						<label for="priorizacion" class="zpFormLabel">Priorizaci&oacute;n:
						</label>
						<span style="-moz-user-select: none;" class="zpIsRequired">
							<span style="-moz-user-select: none;" class=" zpNotEditing">
								<span style="-moz-user-select: none;" class=" zpIsEmpty">
									<span id="prio" style="-moz-user-select: none;" class=" zpIsValid">
										<span style="-moz-user-select: none;" class="zpStatusImg">
										</span>
									</span>
								</span>
							</span>
						</span>
						
						<!---onchange="ver_priorizacion('direccion');--->
						Si<input name="g_pds" id='g_pd' type='radio' value='1' />
						No<input name="g_pds" id='g_pd' type='radio' value='0' checked />
						
						<!--<select id="g_pd" name="priorizacion" class="zpFormRequired" onchange="if(this.value!=''){document.getElementById('prio').className='zpIsValid'}else{document.getElementById('prio').className='zpNotValid'}">
							<option value="">Seleccione...</option>
							<option label="Primario" value="0">Primario</option>
							<option label="Secundario" value="1">Secundario</option>
						</select>--><br />
						<label for="tipo" class="zpFormLabel">Tipo:</label>
							<span style="-moz-user-select: none;" class="zpIsRequired">
								<span style="-moz-user-select: none;" class=" zpNotEditing">
									<span style="-moz-user-select: none;" class=" zpIsEmpty">
										<span id="tip" style="-moz-user-select: none;" class=" zpNotValid">
											<span style="-moz-user-select: none;" class="zpStatusImg">
											</span>
										</span>
									</span>
								</span>
							</span>
						<select id="g_td" name="tipo" class="zpFormRequired" onchange="if(this.value!=''){document.getElementById('tip').className='zpIsValid'}else{document.getElementById('tip').className='zpNotValid'}" >
							<option value="">Seleccione...</option>
							<option label="Activo" value="1">Activo</option>
							<option label="Inactivo" value="2">Inactivo</option>
						</select><br />
						<label for="departamento" class="zpFormLabel">Departamento:</label>
							<span style="-moz-user-select: none;" class="zpIsRequired">
								<span style="-moz-user-select: none;" class=" zpNotEditing">
									<span style="-moz-user-select: none;" class=" zpIsEmpty">
										<span id="gs_dpto" style="-moz-user-select: none;" class=" zpNotValid">
											<span style="-moz-user-select: none;" class="zpStatusImg">
											</span>
										</span>
									</span>
								</span>
							</span>
						
						<select id="dpto" name="departamento" class="zpFormRequired" onchange="dpto();" >
							<option value="">Seleccione...</option>
							<?php
									
									$filas= $dom->getElementsByTagName( "dpto" );
																	
									foreach ($filas as $fila){
										echo "<option value='".$fila->getElementsByTagName( "iddpto" )->item(0)->nodeValue."'>".$fila->getElementsByTagName( "nombre" )->item(0)->nodeValue."</option>";
									}
									
									/*$total_a=count($_SESSION['dpto']);
									for($i=1;$i<=$total_a;$i++){
										$dpto=explode("*",$_SESSION['dpto'][$i]);
										echo "<option value='".$dpto[0]."'>".$dpto[1]."</option>";
									}*/
								
							?>
						</select><br />
						
						<label for="provincia" class="zpFormLabel">Provincia:</label>
							<span style="-moz-user-select: none;" class="zpIsRequired">
								<span style="-moz-user-select: none;" class=" zpNotEditing">
									<span style="-moz-user-select: none;" class=" zpIsEmpty">
										<span id="gs_prov" style="-moz-user-select: none;" class=" zpNotValid">
											<span style="-moz-user-select: none;" class="zpStatusImg">
											</span>
										</span>
									</span>
								</span>
							</span>	
						<select id="prov" name="provincia" class="zpFormRequired" onchange="dist();">
							<option value="">Seleccione...</option>
						</select><br />
						
												
						<label for="distrito" class="zpFormLabel">Distrito:</label>
							<span style="-moz-user-select: none;" class="zpIsRequired">
								<span style="-moz-user-select: none;" class=" zpNotEditing">
									<span style="-moz-user-select: none;" class=" zpIsEmpty">
										<span id="gs_dist" style="-moz-user-select: none;" class=" zpNotValid">
											<span style="-moz-user-select: none;" class="zpStatusImg">
											</span>
										</span>
									</span>
								</span>
							</span>
							<select id="dist" name="distrito" class="zpFormRequired" onchange="if(this.value!=''){document.getElementById('gs_dist').className='zpIsValid'}else{document.getElementById('gs_dist').className='zpNotValid'}">

								<option value="">Seleccione...</option>
							</select>

						</select><br />
						<label for="direccion" class="zpFormLabel">Direcci&oacute;n:</label>
							<span style="-moz-user-select: none;" class="zpIsRequired">
								<span style="-moz-user-select: none;" class=" zpNotEditing">
									<span style="-moz-user-select: none;" class=" zpIsEmpty">
										<span id="gs_dir" style="-moz-user-select: none;" class=" zpNotValid">
											<span style="-moz-user-select: none;" class="zpStatusImg">
											</span>
										</span>
									</span>
								</span>
							</span>
						<input id="g_dd" type="text" name="direccion" class="zpFormRequired" onblur="if(this.value!=''){document.getElementById('gs_dir').className='zpIsValid'}else{document.getElementById('gs_dir').className='zpNotValid'}" size="75" maxlength="255" />
						<!--<input type="button" value="Buscar cuadrante..." name="busqueda_cuadrante" onclick="cuadrantes();" disabled />--><br />
						<!--<label for="cuadrante" class="zpFormLabel">Cuadrante:</label>
							<span style="-moz-user-select: none;" class="zpIsRequired">
								<span style="-moz-user-select: none;" class=" zpNotEditing">
									<span style="-moz-user-select: none;" class=" zpIsEmpty">
										<span id="gs_cdr" style="-moz-user-select: none;" class=" zpNotValid">
											<span style="-moz-user-select: none;" class="zpStatusImg">
											</span>
										</span>
									</span>
								</span>
							</span>-->
						<select style="visibility:hidden;position:absolute;" id="g_cd" name="cuadrante" class="zpFormRequired" onchange="if(this.value!=''){document.getElementById('gs_cdr').className='zpIsValid'}else{document.getElementById('gs_cdr').className='zpNotValid'}"/>
							<option value="1">1</option>
						</select><br />
						<label for="observacion" class="zpFormLabel">Observaci&oacute;n:</label>
							<span style="-moz-user-select: none;" class="zpIsRequired">
								<span style="-moz-user-select: none;" class=" zpNotEditing">
									<span style="-moz-user-select: none;" class=" zpIsEmpty">
										<span id="gs_obs" style="-moz-user-select: none;" class=" zpNotValid">
											<span style="-moz-user-select: none;" class="zpStatusImg">
											</span>
										</span>
									</span>
								</span>
							</span>
						<input  id="g_odobs" type="text" name="observacion" class="zpFormNotRequired" value="" size="75" maxlength="100" onblur="if(this.value!=''){document.getElementById('gs_obs').className='zpIsValid'}else{document.getElementById('gs_obs').className='zpNotValid'}"/><br />
						
						
							<input type="hidden" name="codigo_form" value="20110105093011" />
							<input type="hidden" name="cliid" value="627546" />
							<input type="hidden" name="acc1" value="dir" />
							<input type="button" class="btn" onclick="gs_dir();" value="Aceptar" />
							<div id="e_d"></div>
						
								<!--<div id="nuevo" style="width:50%;height:auto;position:static;float:right;vertical-align: top;overflow: hidden;display: table; ">-->
						
			
					
				</fieldset>
			</div>
			
		</div> <!-- Fin Direcciones -->
		<div class="TabbedPanelsContent"> 
		<!-- Telefonos -->
			<div id="areaForm">
							
				<fieldset ><legend>Nuevo Tel&eacute;fono:</legend>
					<form name="frmDatos3" id="userForm3" class="zpForm" method="post" style="text-align:left">
						
						<label for="numerotelefono" class="zpFormLabel" >N&deg; tel&eacute;fono:</label>
						<input id="g_nt" type="text" class="zpFormRequired" name="numerotelefono" value="" size="15" maxlength="15" autocomplete="off" /> 
							<span id="span_mensaje_telefono" style="color:#FF0000;visibility:hidden;"></span><br />
						<label for="origen" class="zpFormLabel" >Origen:</label>
								
						<select id="g_ot" name="origentelefono" class="zpFormRequired" id="origentelefono">
							<option value="">Seleccione...</option>
							<?php
									
									$filas= $dom->getElementsByTagName( "origen_tel" );
																	
									foreach ($filas as $fila){
										echo "<option value='".$fila->getElementsByTagName( "idoritel" )->item(0)->nodeValue."'>".$fila->getElementsByTagName( "origentel" )->item(0)->nodeValue."</option>";
									}
									/*$total_a=count($_SESSION['ori_tel']);
									for($i=1;$i<=$total_a;$i++){
										$ori_tel=explode("*",$_SESSION['ori_tel'][$i]);
										echo "<option value='".$ori_tel[0]."'>".$ori_tel[1]."</option>";
									}*/
								
							?>
						</select><br />
						<label for="priorizacion" class="zpFormLabel">Priorizaci&oacute;n:</label>
						<span style="-moz-user-select: none;" class="zpIsRequired">
							<span style="-moz-user-select: none;" class=" zpNotEditing">
								<span style="-moz-user-select: none;" class=" zpIsEmpty">
									<span id="prio" style="-moz-user-select: none;" class=" zpIsValid">
										<span style="-moz-user-select: none;" class="zpStatusImg">
										</span>
									</span>
								</span>
							</span>
						</span>
						Si<input name="g_pts" id='g_pd' type='radio' value='1'  />
											
						No<input name="g_pts" id='g_pd' type='radio' value='0'  checked />
						<!--<select id="g_pt" name="priorizacion" class="zpFormRequired" onchange="ver_priorizacion('telefono');">
							<option value="">Seleccione...</option>
							<option value="0">Primario</option>
							<option value="1" selected>Secundario</option>
						</select>--><br />
						
						<label for="tipo" class="zpFormLabel" >Tipo:</label>
						<select  id="g_tt" name="tipotelefono" class="zpFormRequired" id="tipotelefono">
							<option value="">Seleccione...</option>
							<option value="1">Activo</option>
							<option value="0">Inactivo</option>
						</select><br />
						<label for="direcciontelefono" class="zpFormLabel" >Direcci&oacute;n:</label>
						<select  id="g_dt" name="direcciontelefono" class="zpFormNotRequired">
							<option value="NULL">Sin Direcci&oacute;n</option>
							<?php
								$sql=$db->Execute("select iddireccion,direccion from direcciones where idcliente='$id'");
								while(!$sql->EOF){
									echo "<option value='".$sql->fields['iddireccion']."'>".$sql->fields['direccion']."</option>";
									$sql->MoveNext();	
								}
								//mysql_free_result($sql->_queryID);	
							?>
						</select><br />
						
						<label for="observaciontelefono" class="zpFormLabel" >Observaci&oacute;n:</label>
						<input id="g_obt" type="text" name="observaciontelefono" class="zpFormNotRequired" size="75" maxlength="100" value="" /><br />
						
							<br/>
							<!--<input type="hidden" name="codigo_form" value="20110105093011" />
							<input type="hidden" name="cliid" value="627546" />
							<input type="hidden" name="acc1" value="tel" />-->
							<input class="btn" type="button" value="Aceptar" onclick="gs_tel();" />
							<div id="e_t"></div>
						
					</form>
					<br/>
							
			
				</fieldset>
			</div>
					<!-- <div id="nuevo" style="width:50%;position:absolute;left:49%;top:21%; "> -->
					
		</div> 
		<!-- Fin Telefonos -->
		
		<!-- Fin Contactos -->
<?php } 
$db->Close();

//mysql_free_result($db->_queryID);


?>
</div></div>		
		
		
		