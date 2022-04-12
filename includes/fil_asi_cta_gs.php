		<h1>Asignacion Cuentas x Resultado Gestion</h1>
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
					<select id="u_cart" name="cartera" > <!--usuarios_cart();-->
						<option value="">Seleccione..</option>
					</select>
					&nbsp;&nbsp;&nbsp;<b>Resultado:</b>
					<select id="u_rs" name="rs" onchange="res_c(2);"> <!--usuarios_cart();-->
						<option value="">Seleccione..</option>
						<?php
							$sql="SELECT rs.idresultado,rs.resultado FROM
									(SELECT cp.idcuenta,g.idresultado,r.resultado
									FROM cuentas c
									JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta AND cp.idperiodo=".$_SESSION['periodo']." AND cp.idestado=1
									JOIN gestiones g ON g.idcuenta=cp.idcuenta AND g.fecges LIKE '".date("Y-m")."%'
									JOIN resultados r ON g.idresultado=r.idresultado
									AND r.peso_r=(
											SELECT MIN(r2.peso_r) FROM gestiones g2 
												JOIN cuentas c2 ON g2.idcuenta=c2.idcuenta AND c2.idcartera=51
												JOIN cuenta_periodos cp2 ON c2.idcuenta=cp2.idcuenta AND cp2.idperiodo=".$_SESSION['periodo']."  
												JOIN resultados r2 ON g2.idresultado=r2.idresultado
												WHERE c2.idcliente=c.idcliente
												AND g2.fecges LIKE '".date("Y-m")."%' AND g2.idactividad!=4)
									GROUP BY c.idcuenta) AS rs GROUP BY rs.idresultado";
							/*if($_SESSION['tnivel']==5){
								$sql.= " and idproveedor='".$_SESSION['idpro']."'";
							} */
							
							$query= $db->Execute($sql);
							while(!$query->EOF){
								
								echo "<option value='".$query->fields['idresultado']."'>".utf8_encode(substr($query->fields['resultado'],3))."</option>";
								$query->MoveNext();
							}
						?>
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