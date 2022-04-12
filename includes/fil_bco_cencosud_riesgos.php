		<h1>Reporte Riesgos</h1>
		<br/>
		<table>
		<?php
				session_start();
				include '../define_con.php';
		?>
			<tr>
				<td>
					<nobr>
					&nbsp;&nbsp;&nbsp;<b>Riesgo:</b>
					<select id="u_riesgo" name="riesgo" >
						<option value="">..Seleccionar</option>
						<option value="1">Alto</option>
						<option value="2">Medio</option>
						<option value="3">Bajo</option>
					</select>
					&nbsp;&nbsp;&nbsp;<b>Cartera:</b>
					<select id="u_cart" name="cartera" >
						<option value="">..Seleccionar</option>
						<?php
							$sql="SELECT idcartera, cartera FROM carteras where idproveedor in (30) and idestado=1 ";
							$query= $db->Execute($sql);
							while(!$query->EOF){
								echo "<option value='".$query->fields['idcartera']."'>".$query->fields['cartera']."</option>";
								$query->MoveNext();
							}
						?>
					</select>
	
					
					&nbsp;&nbsp;&nbsp;
					 <b>Per&iacute;odo:</b>
					<select  id="periodo" name="periodo" >
						<option value="">Seleccione...</option>
						<?php
							$sql="SELECT idperiodo, periodo FROM periodos ORDER BY fecini DESC ";
							$query= $db->Execute($sql);
							while(!$query->EOF){
								echo "<option value='".$query->fields['idperiodo']."'>".$query->fields['periodo']."</option>";
								$query->MoveNext();
							}
						?>
					</select>
					
				</nobr></td>
				<td width="100%">
					<span id="span_botones">
						<input type="hidden" value="excel" name="acc">&nbsp;&nbsp;&nbsp;
						<a href="#" >
							<input onclick ="rep_cenco_riesgos();" class="btn" type="button" value="Consultar" name="consul_bco_c">
						</a>
					</span>
					<!---<span id="span_wait">&nbsp;&nbsp;&nbsp;<b style="color:#FF0000"><div id="div_mensaje">Espere...</div></b></span>-->
				</td>
			</tr>
			
		<table>
		<div id="resultado_bco_c">
		</div>