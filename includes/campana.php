<?php
				session_start();
				include '../define_con.php';
?>
	<?php if(!isset($_GET['rl'])){ ?>
	<link href="style/campana.css" rel="stylesheet" type="text/css" />

		<legend>Campa&ntilde;as</legend>


	<input type="button" style="float:right;" value="Nuevo" onclick="showNewC('s_campana',1)" class="btn" />
	<div id="s_campana" style="margin:0 auto;width:100%;visibility:hidden;position:absolute;">	
		<table>
			
			<tr>
				<td>Nombre:</td>
				<td>
					<input type="text" id="n_campana" size="35"/><input type="button"  value="Agregar" onclick="insC()" class="btn" />
					<input type="button"  value="Cancelar" onclick="showNewC('s_campana',0)" class="btn" />
				</td>
			</tr>
		
		</table>
	</div>
	<div id="content_tk">	
	<?php }?>
	
		
			<div class='tit_cam'>Id</div><div class='tit_cam'>Campa&ntilde;a</div><div class='tit_cam'>Estado</div><div class='tit_cam'>CD</div><div class='tit_cam'>CI</div><div class='tit_cam'>NC</div>
			<?php
								$sql="SELECT * FROM campana ";
								$query= $db->Execute($sql);
								while(!$query->EOF){
									if($query->fields['estado']==1){
										$class="cl";
									}else{
										$class="cl2";
									}
									echo "	<div style='clear:both;cursor:pointer;' onclick='showOp(".$query->fields['idcampana'].",1);'>
												
												<div class='fila_cam'>".$query->fields['idcampana']."</div>
												<div class='fila_cam'>".$query->fields['campana']."</div>
												<div class='fila_cam'>";
												if($query->fields['estado']==1){echo "<img src='imag/icons/estado_1.png'>";}
												if($query->fields['estado']==0){echo "<img src='imag/icons/estado_0.png'>";}
												echo "</div>
												
											</div>
												<div class='fila_cam'><img src='img/download.png'></div>
												<div class='fila_cam'><img src='img/download.png'></div>
												<div class='fila_cam'>
													<a href='#'><img src='img/download.png'></a>
												</div>
											<div id='".$query->fields['idcampana']."' class='edit_cam'>
											Estado:
												<select id='est_c'/>
													<option value=''>..seleccione</option>
													<option value='1'>Activo</option>
													<option value='0'>Inactivo</option>
												</select>&nbsp;&nbsp;&nbsp;
											Tipo Telefono:
												<select id='tipotel_c".$query->fields['idcampana']."'/>
													<option value=''>..seleccione</option>
													<option value='T'>Todos</option>
													<option value='F'>Fijo</option>
													<option value='M'>Movil</option>
												</select>&nbsp;&nbsp;&nbsp;
											Pertenencia :
												<select id='pertel'/>
													<option value=''>..seleccione</option>
													<option value='A'>Titular</option>
													<option value='F'>Familiar</option>
													<option value='O'>Otro</option>
												</select>&nbsp;&nbsp;&nbsp;
											Correspondencia :
												<select id='pertel'/>
													<option value=''>..seleccione</option>
													<option value='P'>P (personal-casa)</option>
													<option value='T'>T (trabajo-oficina)</option>
												</select>&nbsp;&nbsp;&nbsp;	
											Cobertura:
												<input type='checkbox' name='cobertura' />
												<input type='checkbox' name='cobertura' />
												<input type='checkbox' name='cobertura' />
												<input type='checkbox' name='cobertura' />
												
												&nbsp;&nbsp;&nbsp;
												<br/>
											Prio1:
												<select id='p1".$query->fields['idcampana']."'/>
													<option value=''>..seleccione</option>
													<option value='1'>1</option>
													<option value='2'>2</option>
													<option value='3'>3</option>
													<option value='4'>4</option>
													<option value='5'>5</option>
													<option value='6'>6</option>
													<option value='7'>7</option>
													<option value='8'>8</option>
													<option value='9'>9</option>
													<option value='10'>10</option>
												</select>
												&nbsp;&nbsp;&nbsp;
											Prio2:
												<select id='p2".$query->fields['idcampana']."'/>
													<option value=''>..seleccione</option>
													<option value='1'>1</option>
													<option value='2'>2</option>
													<option value='3'>3</option>
													<option value='4'>4</option>
													<option value='5'>5</option>
													<option value='6'>6</option>
													<option value='7'>7</option>
													<option value='8'>8</option>
													<option value='9'>9</option>
													<option value='10'>10</option>
												</select>
												&nbsp;&nbsp;&nbsp;
											Prio3:
												<select id='p3'/>
													<option value=''>..seleccione</option>
													<option value='1'>1</option>
													<option value='2'>2</option>
													<option value='3'>3</option>
													<option value='4'>4</option>
													<option value='5'>5</option>
													<option value='6'>6</option>
													<option value='7'>7</option>
													<option value='8'>8</option>
													<option value='9'>9</option>
													<option value='10'>10</option>
												</select>
												&nbsp;&nbsp;&nbsp;
											Prio4:
												<select id='p4'/>
													<option value=''>..seleccione</option>
													<option value='1'>1</option>
													<option value='2'>2</option>
													<option value='3'>3</option>
													<option value='4'>4</option>
													<option value='5'>5</option>
													<option value='6'>6</option>
													<option value='7'>7</option>
													<option value='8'>8</option>
													<option value='9'>9</option>
													<option value='10'>10</option>
												</select>
												&nbsp;&nbsp;&nbsp;
											Prio5:
												<select id='p5'/>
													<option value=''>..seleccione</option>
													<option value='1'>1</option>
													<option value='2'>2</option>
													<option value='3'>3</option>
													<option value='4'>4</option>
													<option value='5'>5</option>
													<option value='6'>6</option>
													<option value='7'>7</option>
													<option value='8'>8</option>
													<option value='9'>9</option>
													<option value='10'>10</option>
												</select>
												&nbsp;&nbsp;&nbsp;
												
												<input type='button' value='Actualizar' onclick='updC(".$query->fields['idcampana'].")' class='btn' />
												<input type='button' value='Cancelar' onclick='showOp(".$query->fields['idcampana'].",0)' class='btn' />
												
											</div>";
									$query->MoveNext();
								}
			?>
	<?php if(!isset($_GET['rl'])){ ?>	
	</div>
	<?php } ?>
	
	
	

	




