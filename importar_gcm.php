<?php
				session_start();
				include 'define_con.php';
		?>
<div id="name_id" style="margin:10px;" align="center">
<form name="tipo_impor" method="get">
				&nbsp;&nbsp;&nbsp;
					<b>Per&iacute;odo:</b>
					<select onchange="" id="periodo" name="periodo">
						<option value="">Seleccione...</option>
						<option value="T">Todos</option>
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
				<input name="seleccion" id="seleccion" type="text" name="file" size="34"/> 
				<input id="importar" onclick="i_gcm()" type="button" class="btn" value="Seleccionar Archivo" /></br>
					
</form>			  
			<iframe  style="display:none" name="iframe" src="importar.php" width="400" height="100"></iframe>
</div>
<div id="design1">
<table>
	<tr>
		<th>GESTOR</th>	
			<th>DNI/RUC/CE	</th>	
			<th>FECHA_GEST	</th>	
			<th>HORA	</th>	
			<th>RESULTADO	</th>	
			<th>IMP_PDP_S/.	</th>	
			<th>IMP_PDP_US$	</th>	
			<th>DIRECCION	</th>	
			<th>TIPO CONTACTO</th>		
			<th>UBICABILIDAD</th>		
			<th>TIPO PREDIO	</th>	
			<th>MATERIAL	</th>	
			<th>Nro_PISOS	</th>	
			<th>COLOR	</th>	
			<th>OBSERVACION	</th>	
			<th>DIGITADOR	</th>	
			<th>CARTERA</th>	
	</tr>
</table>
</div>
			<div id="resultado" align="center">
			</div>


