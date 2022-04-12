		<h1>Reportes TN</h1>
		<br/>
		<table>
		<?php
				session_start();
				include '../define_con.php';
		?>
			<tr>
				<td>
					<nobr><b>Reporte:</b>
					<select  id="reporte_tn" name="reporte_tn" >
						<option value="">Seleccione...</option>
						<option value="g_tn">Gestiones</option>
						<option value="ics_tn">ICS</option>

					</select>
					&nbsp;&nbsp;&nbsp;
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
					&nbsp;&nbsp;&nbsp;
					<b>Tipo Cartera:</b>
					<select  id="tipo_cartera" name="tipo_cartera" >
						<option value="">Seleccione...</option>
						<?php
							$sql="SELECT c.idtipocartera,tc.tipocartera FROM cuentas c
									join tipo_carteras tc on c.idtipocartera=tc.idtipocartera
									WHERE c.idcartera=27
									GROUP BY c.idtipocartera";
							$query= $db->Execute($sql);
							while(!$query->EOF){
								/*if($_SESSION['periodo']==$query->fields['idperiodo']){
								echo "<option value='".$query->fields['idperiodo']."' SELECTED>".$query->fields['periodo']."</option>";
								$query->MoveNext();
								}*/
								echo "<option value='".$query->fields['idtipocartera']."'>".$query->fields['tipocartera']."</option>";
								$query->MoveNext();
							}
						?>
					</select>
					&nbsp;&nbsp;&nbsp;
					<b>Fecha:<input id="fec_ra" type='checkbox' onchange='sel_fecha();'/></b>
					Desde:<select  id="fec_ges_i" name="fec_ges_i" disabled >
						<option value="">..Seleccione</option>
					<!--
					</select>
					Hasta:<select  id="fec_ges_f" name="fec_ges_f" disabled>
						<option value="">..Seleccione</option>
					
					</select>-->
				</nobr></td>
				<td width="100%">
					<span id="span_botones"><input type="hidden" value="excel" name="acc">&nbsp;&nbsp;&nbsp;<a href="#" ><input onclick ="rep_TN();" class="btn" type="button" value="Consultar" name="exportar"></a></span>
					<!---<span id="span_wait">&nbsp;&nbsp;&nbsp;<b style="color:#FF0000"><div id="div_mensaje">Espere...</div></b></span>-->
				</td>
			</tr>
			
		<table>
		<div id="resultado_r_c">
		</div>