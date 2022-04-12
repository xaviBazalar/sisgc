		<h1>Reportes Cencosud Televentas</h1>
		<br/>
		<table>
		<?php
				session_start();
				include '../define_con.php';
		?>
			<tr>
				<td>
				<nobr>
					<b>Per&iacute;odo:</b>
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
						?>
					</select>
					&nbsp;&nbsp;&nbsp;<b>Tipo Cartera:</b>
					<select id="u_tcart" name="tcartera">
						<option value="">..Seleccion</option>
						<?php
						$sql="SELECT tc.* FROM cuentas c
							JOIN carteras ct ON c.idcartera=ct.idcartera
							JOIN tipo_carteras tc ON c.idtipocartera=tc.idtipocartera
							WHERE c.idcartera='24'
							GROUP BY tc.idtipocartera ";
							$query= $db->Execute($sql);
							while(!$query->EOF){
								echo "<option value='".$query->fields['idtipocartera']."'>".$query->fields['tipocartera']."</option>";
								$query->MoveNext();
							}
							
						?>
					</select>
	
					&nbsp;&nbsp;&nbsp;<b>Tipo Reporte</b>
					<select id="u_tv_rep" name="tcartera_rep">
						<option value="">..Seleccion</option>
						<option value="mg">Mejor Gestion</option>
						<option value="ug">Ultima Gestion</option>

					</select>	
						
					<!--
					<b>Fecha:<input id="fec_ra" type='checkbox' onchange='sel_fecha();'/></b>
					Desde:<select  id="fec_ges_i" name="fec_ges_i" disabled >
						<option value="">..Seleccione</option>
					
					</select>
					Hasta:<select  id="fec_ges_f" name="fec_ges_f" disabled>
						<option value="">..Seleccione</option>
					
					</select>-->
				</nobr></td>
				<td width="100%">
					<span id="span_botones"><input type="hidden" value="excel" name="acc">&nbsp;&nbsp;&nbsp;<a href="#" ><input onclick ="rep_cenco_tv();" class="btn" type="button" value="Exportar a Excel" name="exportar"></a></span>
					<!---<span id="span_wait">&nbsp;&nbsp;&nbsp;<b style="color:#FF0000"><div id="div_mensaje">Espere...</div></b></span>-->
				</td>
			</tr>
			
		<table>