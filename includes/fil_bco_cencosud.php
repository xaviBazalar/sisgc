		<h1>Reportes Bco. Cencosud</h1>
		<br/>
		<table>
		<?php
				session_start();
				include '../define_con.php';
		?>
			<tr>
				<td>
					<nobr><b>Reporte:</b>
					<select  id="reporte_bco_c" name="reporte_bco_c" >
						<option value="">Seleccione...</option>
						<option value="200">Gestiones</option>
						<option value="600">Pagos</option>
						<option value="700">Telefonos</option>
						<option value="800">Direcciones</option>
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
					<!-- <b>Fecha:<input id="fec_ra" type='checkbox' onchanges='sel_fecha();'/></b> -->
					Desde:<select  id="fec_ges_i" name="fec_ges_i"  >
						<option value="">..Seleccione</option>
					
					</select>
					<select style="visibility:hidden;" id="fec_ges_f" name="fec_ges_f" disabled>
						<option value="">..Seleccione</option>
					
					</select>
				</nobr></td>
				<td width="100%">
					<span id="span_botones"><input type="hidden" value="excel" name="acc">&nbsp;&nbsp;&nbsp;<a href="#" ><input onclick ="rep_bco_c();" class="btn" type="button" value="Consultar" name="consul_bco_c"></a></span>
					<!---<span id="span_wait">&nbsp;&nbsp;&nbsp;<b style="color:#FF0000"><div id="div_mensaje">Espere...</div></b></span>-->
				</td>
			</tr>
			
		<table>
		<div id="resultado_bco_c">
		</div>