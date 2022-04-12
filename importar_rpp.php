<div id="name_id" style="margin:10px;" align="center">
<form name="tipo_impor" method="get">
				<select id="tr_rpp">
				<option value="">..Seleccione</option>
				<option value="CT">Cuentas</option>
				<option value="AP">Archivo Planilla</option>
				<option value="AE">Archivo Entrega</option>

				</select>
				<input name="seleccion" id="seleccion" type="text" name="file" size="34"/> 
				<input id="importar" onclick="c_rpp();" type="button" value="Seleccionar Archivo" /></br>
					
</form>			  
			
			<iframe  style="display:none" name="iframe" src="importar.php" width="400" height="100"></iframe>
</div>
			<div id="resultado" align="center">
			</div>


