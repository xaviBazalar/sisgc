		<h1>Seguimiento Usuarios</h1>
		<br/>
		<table>
		<?php
				session_start();
				include '../define_con.php';
		?>
			<tr>
				<td>
					<nobr>
					<?php
							echo"<b>Seleccionar Campa&ntilde;a: </b>
							<select onchange=\"users_c('s_u')\" id='c_id' name='id_camp' >
								<option value=''>Seleccione...</option>";
						
							$sql="SELECT idcampana,campana FROM campana ";
							
							$query= $db->Execute($sql);
							while(!$query->EOF){
								echo "<option value='".$query->fields['idcampana']."'>".$query->fields['campana']."</option>";
								$query->MoveNext();
							}
						
							echo"				</select>
														
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<input type='button' class='btn' value='Actualizar' onclick=\"users_c('s_u')\">
							  ";
					?>
					&nbsp;&nbsp;&nbsp;
					
				</nobr></td>
				<td width="100%">
					<span id="span_botones">
					</span>
				</td>
			</tr>
		<table>
		<div id="rslt_uc">
		</div>