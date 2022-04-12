		<h1>Resumen Productividad</h1>
		<br/>
		<table>
		<?php
				session_start();
				include '../define_con.php';
		?>
			<tr>
				<td><nobr><b>
						Desde:<input type='text' id='u_fn' size='20'/>
						<button onclick="cale3();" id="f_b1">...</button>
						
						Hasta:<input type='text' id='u_fi' size='20'/>
						<button onclick="cale2();" id="f_btn1">...</button>
				<!--<input id="fec_ra" type='checkbox' onchange='sel_fecha();'/></b>
					Desde:<select  id="fec_ges_i" name="fec_ges_i" disabled >
						<option value="">..Seleccione</option>
					
					</select>
					Hasta:<select  id="fec_ges_f" name="fec_ges_f" disabled>
						<option value="">..Seleccione</option>
					
					</select>-->
				
					
					&nbsp;&nbsp;&nbsp;
					<b>Proveedor: </b>
					<select onchange="cart();" id="u_prove" name="proveedor">
						<option value="">Seleccione...</option>
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
					&nbsp;&nbsp;&nbsp;<b>Tipo de Cartera:</b>
					<select id="u_cart" name="cartera">
						<option value="">Todos</option>
					</select>
					<!--&nbsp;&nbsp;&nbsp;<b>Departamento:</b>
					<select onchange="" id="departamento" name="departamento">
						<option value="">Todos</option>
						<?php
							/*$sql="SELECT idubigeo,nombre FROM ubigeos WHERE codprov=00 AND coddist=00";
							$query= $db->Execute($sql);
							while(!$query->EOF){
								echo "<option value='".$query->fields['idubigeo']."'>".$query->fields['nombre']."</option>";
								$query->MoveNext();
							}*/
						?>
					</select>-->
				</nobr></td>
				<td width="100%">
					<span id="span_botones"><input type="hidden" value="excel" name="acc">&nbsp;&nbsp;&nbsp;<a href="#" ><input onclick ="rep_pro('E');" class="btn" type="button" value="Exportar a Excel" name="exportar"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input onclick ="rep_pro('M');" class="btn" type="button" value="Mostrar" name="mostrar"></span>
					<!---<span id="span_wait">&nbsp;&nbsp;&nbsp;<b style="color:#FF0000"><div id="div_mensaje">Espere...</div></b></span>-->
				</td>
				
			</tr>
		<table>
		<div id="rpta_repor_pro">
			
		</div>