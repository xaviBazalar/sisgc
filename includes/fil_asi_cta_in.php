		<h1>Asignacion Cuentas x Indicadores</h1>
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
					&nbsp;&nbsp;&nbsp;<b>Incremental:</b>
					<select id="inc" name="incremental" > <!--usuarios_cart();-->
						<option value="">Seleccione..</option>
						<option value="1">Si</option>	
						<option value="2">No</option>		
					</select>
					
					&nbsp;&nbsp;&nbsp;<b>Tramo Mora:</b>
					<select id="cla" name="clasificacion" > <!--usuarios_cart();-->
						<option value="">Seleccione..</option>
						<option value="1"><0></option>
						<option value="2"><1-8></option>
						<option value="3"><9-30></option>
						<option value="4"><31-60></option>
						<option value="5"><61-90></option>
						<option value="6"><91-120></option>
						<option value="7"><121-150></option>
						<option value="8"><151-180></option>
						<option value="9"><181-210></option>
						<option value="10"><211-240></option>
						<option value="11"><241-270></option>
						<option value="12"><271-300></option>
						<option value="13"><301-330></option>
						<option value="14"><331-360></option>
						<option value="15"><361-Mas></option>

						<!--<option value="0">Normal</option>	
						<option value="1">Cpp</option>		
						<option value="2">Deficiente</option>	
						<option value="3">Dudoso</option>	
						<option value="4">Perdida</option>	-->
					</select>
					
					
					&nbsp;&nbsp;&nbsp;<b>Saldo Facturado:</b>
					<input type="text" size='5' maxlength='5' id="desde_s"/>
					Y
					<input type="text" size='5' maxlength='5' id="hasta_s"/>
					<!---&nbsp;&nbsp;&nbsp;<b>Agente:</b>
					<select id="usu_cart" name="usuarios_cartera" onchange="res_c();">
						<option value="">Seleccione..</option>
					</select>-->
					
						
					
				</nobr></td>
				<td width="100%">
					 <span id="span_botones"><input type="hidden" value="excel" name="acc">&nbsp;&nbsp;&nbsp;<a href="#" ><input onclick="res_c(3);" class="btn" type="button" value="Filtrar Ctas" name="exportar"></a></span>
					<!---<span id="span_wait">&nbsp;&nbsp;&nbsp;<b style="color:#FF0000"><div id="div_mensaje">Espere...</div></b></span>-->
				</td>
			</tr>
			
			<tr>
				<td width="100%">
					<nobr>
					&nbsp;&nbsp;&nbsp;<b>Pagos:</b>
					<select id="pgs" name="pagos" > <!--usuarios_cart();-->
						<option value="">Seleccione..</option>
						<option value="Al dia">Al dia</option>	
						<option value="Gestionar">Gestionar</option>
						<option value="Por Vencer">Por Vencer</option>		
						<option value="Preventivo">Preventivo</option>
					</select>
					&nbsp;&nbsp;&nbsp;<b>Grupo:</b>
					<select id="grp" name="grupo" > <!--usuarios_cart();-->
						<option value="">Seleccione..</option>
						<option value="6">6</option>	
						<option value="10">10</option>
						<option value="20">20</option>		
						<option value="25">25</option>
					</select>
					&nbsp;&nbsp;&nbsp;<b>Riesgo:</b>
					<select id="rie" name="riesgo" > <!--usuarios_cart();-->
						<option value="">Seleccione..</option>
						<option value="1">Alto</option>	
						<option value="2">Medio</option>
						<option value="3">Bajo</option>		
						
					</select>
					&nbsp;&nbsp;&nbsp;<b>Gestion:</b>
					<select id="gst" name="gst" > <!--usuarios_cart();-->
						<option value="">Seleccione..</option>
						<option value="0">Sin Gestion</option>	
						<option value="1">Con Gestion</option>
						
					</select>
					</nobr>
				</td>
			</tr>
		<table>
		<div style="width:600px;margin:0 auto;" id="rslt_uc">
		</div>