		<h1>Anticuamiento</h1>
		<br/>
		<table>
		<?php
				session_start();
				include '../define_con.php';
		?>
			<tr>
				<td>
					<nobr>
					<b>Proveedor: </b>
					<select onchange="cart();" id="u_prove" name="proveedor">
						<option value="">Seleccione...</option>
						<?php
							$sql="SELECT idproveedor, proveedor FROM proveedores WHERE idestado=1 and idproveedor in (14,9,20,21,22,27)";
							/*if($_SESSION['tnivel']==5){
								$sql.= " and idproveedor='".$_SESSION['idpro']."'";
							} */
							
							$query= $db->Execute($sql);
							while(!$query->EOF){
								
								echo "<option value='".$query->fields['idproveedor']."'>".$query->fields['proveedor']."</option>";
								$query->MoveNext();
							}
						?>
					</select>
									
					&nbsp;&nbsp;&nbsp;	
					<b>Cartera:</b>
					<select  id="u_cart" name="cartera" onchange="">
						<option value="">Seleccione...</option>
						<?php
						/*	$sql="Select idcartera,cartera from carteras where idproveedor='2' and idestado='1' ";
							$query= $db->Execute($sql);
							while(!$query->EOF){
								
								echo "<option value='".$query->fields['idcartera']."'>".$query->fields['cartera']."</option>";
								$query->MoveNext();
							}*/
						?>
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
					<!--<b>Fecha:<input id="fec_ra" type='checkbox' onchange='sel_fecha();'/></b>
					Desde:<select  id="fec_ges_i" name="fec_ges_i" disabled >
						<option value="">..Seleccione</option>
					
					</select>
					Hasta:<select  id="fec_ges_f" name="fec_ges_f" disabled>
						<option value="">..Seleccione</option>
					
					</select>-->
				</nobr></td>
				<td width="100%">
					<span id="span_botones"><input type="hidden" value="excel" name="acc">&nbsp;&nbsp;&nbsp;<a href="#" ><input onclick ="rep_rpp_a();" class="btn" type="button" value="Exportar a Excel" name="exportar"></a></span>
					<!---<span id="span_wait">&nbsp;&nbsp;&nbsp;<b style="color:#FF0000"><div id="div_mensaje">Espere...</div></b></span>-->
				</td>
			</tr>
			<tr>
				<td colspan='2'>
					<nobr>
					
					<b>Venc.:</b>
					<select  id="venc" name="venc" >
						<option value="">...</option>
						<option value="1">Si</option>
						<option value="0">No</option>
					</select>
					&nbsp;&nbsp;&nbsp;|
					<b>Pendientes:</b>
					<select  id="pend" name="pend" >
						<option value="">...</option>
						<option value="1">Si</option>
						<option value="0">No</option>
					</select>
					&nbsp;&nbsp;&nbsp;|
					<b>Solo Retencion:</b>
					<select  id="sr" name="sr" >
						<option value="">...</option>
						<option value="1">Si</option>
						<option value="0">No</option>
					</select>
					&nbsp;&nbsp;&nbsp;|
					<b>Solo Detraccion:</b>
					<select  id="sd" name="sd" >
						<option value="">...</option>
						<option value="1">Si</option>
						<option value="0">No</option>
					</select>
					&nbsp;&nbsp;&nbsp;|
					<b>Solo Central de R.:</b>
					<select  id="cr" name="cr" >
						<option value="">...</option>
						<option value="1">Si</option>
						<option value="0">No</option>
					</select>
					&nbsp;&nbsp;&nbsp;|
					<b>Solo OBS:</b>
					<select  id="sr" name="sr" >
						<option value="">...</option>
						<option value="1">Si</option>
						<option value="0">No</option>
					</select>
					&nbsp;&nbsp;&nbsp;
					|
					<b>Provincia:</b>
					<select  id="sr" name="sr" >
						<option value="">...</option>
						<option value="T">Todos</option>
						<option value="0">No</option>
					</select>
					</nobr>
				</td>
			</tr>
			
		<table>
		
		<div id="show_report_r">
		
		</div>