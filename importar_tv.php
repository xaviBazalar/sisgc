<div id="name_id" style="margin:10px;" align="center">
<?php
				session_start();
				include 'define_con.php';
		?>
<form name="tipo_impor" method="get">
				<b>Proveedor: </b>
					<select onchange="cart();" id="u_prove" name="proveedor">
						<option value="">Seleccione</option>
						<?php
							$sql="SELECT idproveedor, proveedor FROM proveedores WHERE idestado=1 and idproveedor in (31)";

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

				<input name="seleccion" id="seleccion" type="text" name="file" size="34"/> 
				<select id="tipo_imp">
					<option value="">..Seleccionar</option>
					<option value="7">Archivo Asignacion TV</option>
					<option value="9">Resultado Entrega TC</option>
					<option value="10">Feedback Cliente</option>

					<!--<option value="2">Archivo Pagos</option>
					<option value="3">Archivo Detalle de Pagos</option>
					<option value="4">Conversion Archivo Asignacion (REM)</option>
					<option value="5">Actualizacion Datos(Clientes,Tlf,Dir,Ctas)</option>
					<option value="6">Archivo Mora</option>-->


				</select>
				<input id="importar" onclick="i_archivo();" type="button" class="btn" value="Seleccionar Archivo" /></br>
				<!--Cliente,Dir,Tel<input type='radio' name="tip_imp"  value='0'/>
				Ctas<input type='radio' name="tip_imp"  value='1'/>
				Ctas.Pagos<input type='radio' name="tip_imp"  value='2'/>
				Ctas.Det<input type='radio' name="tip_imp"  value='3'/>-->
</form>			  
			<iframe  style="display:none" name="iframe" src="importar.php" width="400" height="100"></iframe>
</div>
			<div id="resultado" align="center">
			</div>


