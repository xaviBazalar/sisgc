		<h1>Reportes Predictivo</h1>
		<br/>
		<table>
		<?php
				session_start();
				include '../define_con.php';
		?>
			<tr>
				<td>
					<nobr><b>Tipo Reporte:</b>
					<select  id="tipo_dato" name="tipo_dato">
						<option value="">Seleccione...</option>
						<option value="L">Limpiar Base</option>
						<option value="SC">Sincronizacion</option>
						<option value="TG">Transferencia de Gestiones</option>
						<option value="RG">Reporte de gesti&oacute;n</option>
						<option value="AM">Avance de la marcaci&oacute;n</option>
					</select>
					&nbsp;&nbsp;&nbsp;
					
					<b>Campa&ntilde;a: </b>
					<select onchange="" id="c_id" name="id_camp">
						<option value="">Seleccione...</option>
						<option value="26">Prueba 2</option>
						<?php
							$sql="SELECT idcampana,campana FROM campana ";
							
							$query= $db->Execute($sql);
							while(!$query->EOF){
								
								echo "<option value='".$query->fields['idcampana']."'>".$query->fields['campana']."</option>";
								$query->MoveNext();
							}
						?>
					</select>
									
					&nbsp;&nbsp;&nbsp;
				</nobr></td>
				<td width="100%">
					<span id="span_botones"><input type="hidden" value="excel" name="acc">&nbsp;&nbsp;&nbsp;<a href="#" ><input onclick ="r_camp();" class="btn" type="button" value="Ejecutar" name="exportar"></a></span>
					<!---<span id="span_wait">&nbsp;&nbsp;&nbsp;<b style="color:#FF0000"><div id="div_mensaje">Espere...</div></b></span>-->
				</td>
			</tr>
		<table>
		
		<div id="resultado_r_c">
		</div>