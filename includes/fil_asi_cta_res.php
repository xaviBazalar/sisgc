		<h1>Asignacion Cuentas x Cartera</h1>
		<br/>
		<table>
		<?php
				session_start();
				include '../define_con.php';
		?>
			<tr>
				<td>
					<nobr>
					<!--<b>Per&iacute;odo:</b>
					<select  id="periodo" name="periodo" onchange="fechas();">
						<option value="">Seleccione...</option>
						<?php
							/*$sql="SELECT idperiodo, periodo FROM periodos ORDER BY fecini DESC ";
							$query= $db->Execute($sql);
							while(!$query->EOF){

								echo "<option value='".$query->fields['idperiodo']."'>".$query->fields['periodo']."</option>";
								$query->MoveNext();
							}*/
						?>
					</select>
					&nbsp;&nbsp;&nbsp;
					<b>Fecha:<input id="fec_ra" type='checkbox' onchange='sel_fecha();'/></b>
					Desde:
						<select  id="fec_ges_i" name="fec_ges_i" disabled >
							<option value="">..Seleccione</option>
						</select>	
					&nbsp;&nbsp;&nbsp;
					<select  style="visibility:hidden;position:absolute;" id="fec_ges_f" name="fec_ges_f" disabled>
						<option value="">..Seleccione</option>
					
					</select> -->
					<b>Proveedor: </b>
					<select onchange="cart();" id="u_prove" name="proveedor">
						<option value="">Seleccione</option>
						<?php
							$sql="SELECT idproveedor, proveedor FROM proveedores WHERE idestado=1 and idproveedor not in (1)";
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
					<select id="u_cart" name="cartera" onchange="res_c(1);"> <!--usuarios_cart();-->
						<option value="">Seleccione..</option>
					</select>
					<!---&nbsp;&nbsp;&nbsp;<b>Agente:</b>
					<select id="usu_cart" name="usuarios_cartera" onchange="res_c();">
						<option value="">Seleccione..</option>
					</select>-->
					
						
					
				</nobr></td>
				<td width="100%">
					 <!--<span id="span_botones"><input type="hidden" value="excel" name="acc">&nbsp;&nbsp;&nbsp;<a href="#" ><input onclick ="up_res_c(2);" class="btn" type="button" value="Habilitar Todo" name="exportar"></a></span>--> 
					<!---<span id="span_wait">&nbsp;&nbsp;&nbsp;<b style="color:#FF0000"><div id="div_mensaje">Espere...</div></b></span>-->
				</td>
			</tr>
		<table>
		<div style="width:600px;margin:0 auto;" id="rslt_uc">
		</div>