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
				<input id="importar" onclick="i_gc()" type="button"  class="btn" value="Seleccionar Archivo" /></br>
					
</form>			  
			<iframe  style="display:none" name="iframe" src="importar.php" width="400" height="100"></iframe>
</div>
<div id="design1">
<table>
	<tr>
											
			<th>ID_GESTOR</th>	
			<th>DNI/RUC/CE</th>	
			<th>FECHA_GEST</th>	
			<th>HORA</th>	
			<th>ID_RESULTADO	</th>	
			<th>ID_CONTACTABILIDAD.</th>	
			<th>ID_JUSTIFICACION</th>	
			<th>ID_ACTIVIDAD</th>	
			<th>ID_TELEFONO</th>		
			<th>FEC_PROMESA</th>		
			<th>IMP_PROMESA	</th>	
			<th>ID_CARTERA</th>	

	</tr>
</table>
</div>


			<div id="resultado" align="center">
			</div>


