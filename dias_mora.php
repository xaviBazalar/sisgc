<html>
<body>
<form method="post">
<? require 'define_con.php'; ?>
<nobr><b>Per&iacute;odo:</b>
	  <select id="periodo" name="periodo">
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
									
					&nbsp;&nbsp;&nbsp;<b>Cartera:</b>
					<select id="u_cart" name="cartera" onchange="">
						<option value="">Todos</option>
					</select></nobr>
<input type="button" id="exp" name="exp" value="exportar" onclick="abrirventana();" />
<!--span id="span_botones"><input type="hidden" value="excel" name="acc">&nbsp;&nbsp;&nbsp;<a href="#" >
<input onclick ="abrirventana();" class="btn" type="button" value="Exportar a Excel" name="exportar"></a></span-->
</form>
</body>
</html>