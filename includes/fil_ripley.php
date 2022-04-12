		<h1>Reportes Ripley</h1>
		<br/>
		<table>
		<?php
				session_start();
				include '../define_con.php';
		?>
			<tr>
				<td>
					<nobr>
					<b>Cartera:</b>
					<select  id="reporte_r_c" name="reporte_r_c" onchange="">
						<option value="">Seleccione...</option>
						<?php
							$sql="Select idcartera,cartera from carteras where idproveedor='2' and idestado='1' ";
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
					&nbsp;&nbsp;&nbsp;<b>Reporte:</b>
					<select  id="reporte_r" name="reporte_r" onchange="">
						<option value="">Seleccione...</option>
						<option value="gs" selected>Gestiones</option>
						<option value="dt" >Detalle</option>
						<option value="ps">Productividad</option>
					</select>
					&nbsp;&nbsp;&nbsp;<b>Tipo Cartera:</b>
					<select  id="tipoc_r" name="tipoc_r" onchange="">
						<option value="">Seleccione...</option>
						<option value="1" >Tardia</option>
						<option value="2" >Recovery</option>
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
					<b>Fecha:<input id="fec_ra" type='checkbox' onchange='sel_fecha();'/></b>
					Desde:<select  id="fec_ges_i" name="fec_ges_i" disabled >
						<option value="">..Seleccione</option>
					
					</select>
					Hasta:<select  id="fec_ges_f" name="fec_ges_f" disabled>
						<option value="">..Seleccione</option>
					
					</select>
				</nobr></td>
				<td width="100%">
					<span id="span_botones"><input type="hidden" value="excel" name="acc">&nbsp;&nbsp;&nbsp;<a href="#" ><input onclick ="rep_ripley();" class="btn" type="button" value="Exportar a Excel" name="exportar"></a></span>
					<!---<span id="span_wait">&nbsp;&nbsp;&nbsp;<b style="color:#FF0000"><div id="div_mensaje">Espere...</div></b></span>-->
				</td>
			</tr>
			
		<table>
		
		<div id="show_report_r">
		
		</div>