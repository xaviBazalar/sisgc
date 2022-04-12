		<h1>Reporte TN Encuesta </h1>
		<br/>
		<table>
		<?php
				session_start();
				include '../define_con.php';
		?>
			<tr>
				<td>
					<nobr>
					
					<!--&nbsp;&nbsp;&nbsp;<b>Reporte:</b>
					<select  id="reporte_r" name="reporte_r" onchange="">
						<option value="">Seleccione...</option>
						<option value="gs">Resumen</option>
					</select>-->
					&nbsp;&nbsp;&nbsp;
					<b>Cartera:</b>
					<select  id="cartera" name="cartera"  onchange="tpcart();">
						<option value="">Seleccione...</option>
						<?php
							$sql="SELECT idcartera, cartera FROM carteras where idcartera in(46) ORDER BY idcartera DESC ";
							$query= $db->Execute($sql);
							while(!$query->EOF){
								/*if($_SESSION['periodo']==$query->fields['idperiodo']){
								echo "<option value='".$query->fields['idperiodo']."' SELECTED>".$query->fields['periodo']."</option>";
								$query->MoveNext();
								}*/
								echo "<option value='".$query->fields['idcartera']."'>".$query->fields['cartera']."</option>";
								$query->MoveNext();
							}
						?>
						
					</select>
					&nbsp;&nbsp;&nbsp;<b>Tipo Cartera:</b>
					<select id="u_tcart" name="tcartera">
						<option value="">..Seleccion</option>
						
					</select>
					&nbsp;&nbsp;&nbsp;
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
					<b>Fecha:<input id="fec_ra" type='checkbox' onchange='sel_fecha();'/></b>
					Desde:<select  id="fec_ges_i" name="fec_ges_i" disabled >
						<option value="">..Seleccione</option>
					
					</select>
					Hasta:<select  id="fec_ges_f" name="fec_ges_f" disabled>
						<option value="">..Seleccione</option>
					
					</select>
				</nobr></td>
				<td width="100%">
					<!--<span id="span_botones"><input type="hidden" value="excel" name="acc">&nbsp;&nbsp;&nbsp;<a href="#" ><input onclick ="rep_claro('resume');" class="btn" type="button" value="Exportar a Excel" name="exportar"></a></span>
					<span id="span_botones"><input type="hidden" value="excel" name="acc">&nbsp;&nbsp;&nbsp;<a href="#" ><input onclick="rpta_ec1_2()" class="btn" type="button" value="Ver respuestas" name="exportar"></a></span>-->
					<span id="span_botones"><input type="hidden" value="excel" name="acc">&nbsp;&nbsp;&nbsp;<a href="#" ><input onclick ="rep_tn('trama');" class="btn" type="button" value="Trama" name="exportar"></a></span>
				
					<!---<span id="span_wait">&nbsp;&nbsp;&nbsp;<b style="color:#FF0000"><div id="div_mensaje">Espere...</div></b></span>-->
				</td>
			</tr>
			
		<table>
		
		<div id="show_report_r">
		
		</div>