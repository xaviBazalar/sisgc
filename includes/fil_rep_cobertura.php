		<h1>Reporte Cobertura</h1>
		<br/>
		<table>
		<?php
				session_start();
				include '../define_con.php';
		?>
			<tr>
				<td>
					<nobr><b>Per&iacute;odo:</b>
					<select onchange="" id="periodo" name="periodo">
						<option value="">Seleccione...</option>
						<?php
							$sql="SELECT idperiodo, periodo FROM periodos ORDER BY fecini DESC ";
							$query= $db->Execute($sql);
							while(!$query->EOF){
								if($_SESSION['periodo']==$query->fields['idperiodo']){
								echo "<option value='".$query->fields['idperiodo']."' SELECTED>".$query->fields['periodo']."</option>";
								$query->MoveNext();
								}
								echo "<option value='".$query->fields['idperiodo']."'>".$query->fields['periodo']."</option>";
								$query->MoveNext();
							}
						?>
					</select>
					&nbsp;&nbsp;&nbsp;
					<b>Proveedor: </b>
					<select onchange="cart();" id="u_prove" name="proveedor">
						<option value="">Seleccione</option>
						<?php
							$sql="SELECT idproveedor, proveedor FROM proveedores WHERE idestado=1";
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
					&nbsp;&nbsp;&nbsp;<b>Cartera:</b>
					<select id="u_cart" name="cartera" >
						<option value="">Seleccione..</option>
					</select>

						
					</select>
				</nobr></td>
				<td width="100%">
					<span id="span_botones"><input type="hidden" value="excel" name="acc">&nbsp;&nbsp;&nbsp;<a href="#" ><input onclick ="rep_cob();" class="btn" type="button" value="Exportar a Excel" name="exportar"></a></span>
					<!---<span id="span_wait">&nbsp;&nbsp;&nbsp;<b style="color:#FF0000"><div id="div_mensaje">Espere...</div></b></span>-->
				</td>
			</tr>
		<table>