		<h1>Reportes Trama</h1>
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
						<option value="">Seleccione</option>
						<?php
							$sql="SELECT idproveedor, proveedor FROM proveedores WHERE idestado=1 and idproveedor in (30)";

							$db->debug=true;
							$query= $db->Execute($sql);
							while(!$query->EOF){
								
								echo "<option value='".$query->fields['idproveedor']."'>".$query->fields['proveedor']."</option>";
								$query->MoveNext();
							}
						?>
					</select>
					&nbsp;&nbsp;&nbsp;<b>Cartera:</b>
					<select id="u_cart" name="cartera" > <!--usuarios_cart();-->
						<option value="">Seleccione..</option>
					</select>

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
					<span id="span_botones"><input type="hidden" value="excel" name="acc">&nbsp;&nbsp;&nbsp;<a href="#" ><input onclick ="rep_bco_c_trama(0);" class="btn" type="button" value="Consultar x Dia" name="consul_bco_c"></a></span>
					<span id="span_botones"><input type="hidden" value="excel" name="acc">&nbsp;&nbsp;&nbsp;<a href="#" ><input onclick ="rep_bco_c_trama(1);" class="btn" type="button" value="Consultar x Mes" name="consul_bco_c"></a></span>

<!---<span id="span_wait">&nbsp;&nbsp;&nbsp;<b style="color:#FF0000"><div id="div_mensaje">Espere...</div></b></span>-->
				</td>
			</tr>
			
		<table>
		<div id="resultado_bco_c">
		</div>