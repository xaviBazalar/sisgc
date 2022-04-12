		<h1>Gestiones</h1>
		<br/>
		<table>
		<?php
				session_start();
				include '../define_con.php';
		?>
			<tr>
				<td>
					<nobr><b>Per&iacute;odo:</b>
					<select  id="periodo" name="periodo" onchange="fechas();">
						<option value="">Seleccione...</option>
						<?php
							$sql="SELECT idperiodo, periodo FROM periodos ORDER BY fecini DESC ";
							$query= $db->Execute($sql);
							while(!$query->EOF){
								/*if($_SESSION['periodo']==$query->fields['idperiodo']){
									echo "<option value='".$query->fields['idperiodo']."' SELECTED>".$query->fields['periodo']."</option>";
									$query->MoveNext();
								}*/
								echo "<option value='".$query->fields['idperiodo']."'>".$query->fields['periodo']."</option>";
								$query->MoveNext();
							}
							
							if($_SESSION['tnivel']==4){
								$c="and idproveedor='".$_SESSION['prove']."'";
								$opc_p=" DISABLED";
								$sele="SELECTED";
																	
							}
						?>
					</select>
					&nbsp;&nbsp;&nbsp;
					<b>Proveedor: </b>
					<select onchange="cart();" id="u_prove" name="proveedor" <?php echo $opc_p;?>>
						<option value="">Seleccione...</option>
						<?php
							$sql="SELECT idproveedor, proveedor FROM proveedores WHERE idestado=1 $c";

							$query= $db->Execute($sql);
							while(!$query->EOF){
								echo "<option value='".$query->fields['idproveedor']."' $sele>".$query->fields['proveedor']."</option>";
								$query->MoveNext();
							}
						?>
					</select>
					&nbsp;&nbsp;&nbsp;<b>Cartera:</b>
					<select id="u_cart" name="cartera"  onchange="tpcart();" <?php echo $opc_p;?>>
						<option value="">..Seleccione</option>
						<?php
							if($_SESSION['tnivel']==4 ){
								$c="Select idcartera, cartera from carteras where idcartera='".$_SESSION['cartera']."' ";
															
								$result = $db->Execute($c);	
								while (!$result->EOF) {
									echo "<option value='".$result->fields['idcartera']."' $sele>".$result->fields['cartera']."</option>";
									$result->MoveNext();                                    
								}
							}
						?>
					</select>
					&nbsp;&nbsp;&nbsp;<b>Tipo Cartera:</b>
					<select id="u_tcart" name="tcartera">
						<option value="">..Selecciones</option>
						
					</select>
					<!--&nbsp;&nbsp;&nbsp;<b>Departamento:</b>
					<select onchange="" id="departamento" name="departamento">
						<option value="">..Seleccione</option>
						<?php
							/*$sql="SELECT idubigeo,nombre FROM ubigeos WHERE codprov=00 AND coddist=00";
							$query= $db->Execute($sql);
							while(!$query->EOF){
								echo "<option value='".$query->fields['idubigeo']."'>".$query->fields['nombre']."</option>";
								$query->MoveNext();
							}*/
						?>
					</select>-->
					
				</nobr></td>
				<td width="100%">
					<span id="span_botones"><input type="hidden" value="excel" name="acc">&nbsp;&nbsp;&nbsp;<a href="#" ><input onclick ="foto_gest();" class="btn" type="button" value="Exportar a Excel" name="exportar"></a></span>
					<!---<span id="span_wait">&nbsp;&nbsp;&nbsp;<b style="color:#FF0000"><div id="div_mensaje">Espere...</div></b></span>-->
				</td>
			</tr>
			<tr>
				<td>
					<b>Indicador:</b>
					
						
						<?php
							$sql="SELECT idactividad,actividad FROM actividades WHERE idestado=1";
							$query= $db->Execute($sql);
							while(!$query->EOF){
									$ind = explode("-",$query->fields['actividad']);
									$ind = $ind[0];
								echo "&nbsp;&nbsp;".$ind."<input name='indicador' type='checkbox' value='".$query->fields['idactividad']."'/>";
								$query->MoveNext();
							}
						?>
					
				</td>
			</tr>
			<tr><td><b>Fecha:<input id="fec_ra" type='checkbox' onchange='sel_fecha();'/></b>
					Desde:<select  id="fec_ges_i" name="fec_ges_i" disabled >
						<option value="">..Seleccione</option>
					
					</select>
					Hasta:<select  id="fec_ges_f" name="fec_ges_f" disabled>
						<option value="">..Seleccione</option>
					
					</select></td></tr>
		<table>