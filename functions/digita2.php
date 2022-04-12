<?php
session_start();
include '../scripts/conexion.php';

if(isset($_GET['value'])){
	$idc=$_GET['value'];
	if($_GET['tipo']=="tel"){
		if(isset($_GET['func'])){
			$nro_t=$_GET['fono'];
			$or_t=$_GET['o_fono'];
			$anx=$_GET['anx'];
			if(isset($_GET['pre_fono']) && $_GET['pre_fono']!="*" ){	$nro_t=$_GET['pre_fono']." ".$_GET['fono']; }
				
				if(isset($_GET['id_up']) && $_GET['id_up']!="-"){
					$id_tf=$_GET['id_up'];
					$sql="UPDATE telefonos set idorigentelefono='$or_t',telefono='$nro_t',anexo='$anx'
								 where idtelefono='$id_tf'";
					$query= $db->Execute($sql);			 
										
				}else{
					//$db->debug=true;
					$sql="INSERT into telefonos(idorigentelefono,idcliente,telefono,anexo)";
					$sql.=" VALUES('$or_t','$idc','$nro_t','$anx') ";
					$query= $db->Execute($sql);
				}
			if($query){
				if($query->EOF){
				//echo "ok";
				}else{
				//echo "false";
				}
			}
			mysql_free_result($query->_queryID);
			
		}
		echo "<table width=\"100%\" id=\"design4\" align=\"left\" >
				<!--<form name=\"frmDireccion\" method=\"post\">-->
					<caption>".utf8_encode("Teléfonos")."</caption>
					<thead>
						<tr>
							<th>N&uacute;mero</th>
							<th>Estado</th>
							<th>Origen</th>
										
						</tr>
					</thead>
					<tbody>";
					
						$fono=$db->Execute("SELECT t.idtelefono,t.telefono,t.observacion,t.prioridad,ot.idorigentelefono,ot.origentelefono,e.estado 
											FROM telefonos t
											JOIN origen_telefonos ot ON t.idorigentelefono=ot.idorigentelefono
											JOIN estados e ON t.idestado=e.idestado
											where idcliente='$idc'");
							
							while(!$fono->EOF){
								echo "<tr style='padding:0 0 0 0 ;margin:0 0 0 0;'>
								<td colspan='2' style='padding:0 0 0 0 ;margin:0 0 0 0;border-collapse: collapse;'>
								<input size='25' id='f".$fono->fields['idtelefono']."'   style='color:#000;background-color: #F9FCFF;border:none;font-size:11px;' type='text' value='".$fono->fields['telefono']."' disabled/>
								<input disabled size='20' style='color:#000;background-color: #F9FCFF;border:none;font-size:11px;' type='text' value='".$fono->fields['estado']."'/>
								<input disabled size='25' style='color:#000;background-color: #F9FCFF; border:none;font-size:11px;' type='text' value='".$fono->fields['origentelefono']."'/>
								</td>
								<td><input name='editarF' onclick=\"disa(".$fono->fields['idtelefono'].",".$fono->fields['idorigentelefono'].",'0','fono');\"type='checkbox' id='ef".$fono->fields['idtelefono']."' value='1' /></td>
								</tr>";
								$fono->MoveNext();
							}
							mysql_free_result($fono->_queryID);
							$db->Close();

		echo "			</tbody>
				</form>
			</table>";
		return false;	
	
	}else if($_GET['tipo']=="dir"){
		if(isset($_GET['func'])){
			$dir=$_GET['dir'];
			$or_dir=$_GET['o_dir'];
			$ubi=$_GET['ubi'];
				$query= $db->Execute("SELECT coddpto,codprov,coddist FROM ubigeos WHERE idubigeo=$ubi");
				$dpto=$query->fields['coddpto'];
				$prov=$query->fields['codprov'];
				$dist=$query->fields['coddist'];
				if(isset($_GET['id_up']) && $_GET['id_up']!="-"){
					$id_dr=$_GET['id_up'];
					$sql="UPDATE direcciones set idorigendireccion='$or_dir',idcliente='$idc',idcuadrante='1',
								direccion='$dir',coddpto='$dpto',codprov='$prov',coddist='$dist' where iddireccion=$id_dr";
										
				}else{
				$sql="INSERT into direcciones(idorigendireccion,idcliente,idcuadrante,direccion,coddpto,codprov,coddist)";
				$sql.=" VALUES('$or_dir','$idc','1','$dir','$dpto','$prov','$dist') ";
				}
				
				$query= $db->Execute($sql);
				
				if($query){
					if($query->EOF){
					//echo "ok";
					}else{
					//echo "false";
					}
				}
				mysql_free_result($query->_queryID);
				
				
		}
		echo" <table width='100%' cellspacing='0' cellpadding='0' id=\"design4\" align=\"left\" >
					<caption>Direcciones</caption>
					<thead>
						<tr>
							<th>".utf8_encode("Dirección")."</th>
							<th>Ubigeo.</th>
							<th>Origen</th>
							<th>Editar</th>
						</tr>
					</thead>
				<tbody>";
							
								
				$dir=$db->Execute(" SELECT od.idorigendireccion,u3.idubigeo,d.iddireccion,d.prioridad,d.direccion,d.observacion,od.origendireccion,u.nombre dpto,cu.cuadrante,es.estado,u2.nombre prov,u3.nombre dist,p.plano FROM direcciones d
									JOIN origen_direcciones od ON d.idorigendireccion=od.idorigendireccion
									JOIN ubigeos u ON d.coddpto=u.coddpto AND u.codprov=00 AND u.coddist=00
									JOIN ubigeos u2 ON d.coddpto=u2.coddpto AND d.codprov=u2.codprov AND u2.coddist=00
									JOIN ubigeos u3 ON d.coddpto=u3.coddpto AND d.codprov=u3.codprov AND d.coddist=u3.coddist
									JOIN cuadrantes cu ON d.idcuadrante=cu.idcuadrante
									JOIN estados es ON d.idestado=es.idestado
									JOIN planos p ON cu.idplano=p.idplano
									WHERE d.idcliente='$idc'
									
									");
								
					if(!$dir){
						echo "<tr><td> <td><tr>";	
					}else{
					
						while(!$dir->EOF){
						
							echo "<tr style='padding:0 0 0 0 ;margin:0 0 0 0;'>
									
								<td colspan='3' style='padding:0 0 0 0 ;margin:0 0 0 0;border-collapse: collapse;'>
								<input id='d".$dir->fields['iddireccion']."'   style='color:#000;background-color: #F9FCFF;border:none;font-size:11px;' type='text' value='".$dir->fields['direccion']."' disabled/>
								<input disabled size='64' style='color:#000;background-color: #F9FCFF;border:none;font-size:10px;' type='text' value='".$dir->fields['dpto']."-".$dir->fields['prov']."-".$dir->fields['dist']."'/>
								<input disabled size='8' style='color:#000;background-color: #F9FCFF; border:none;font-size:11px;' type='text' value='".$dir->fields['origendireccion']."'/>
								</td>
								<td><input name='editar' onclick=\"disa(".$dir->fields['iddireccion'].",".$dir->fields['idubigeo'].",".$dir->fields['idorigendireccion'].",'dir');\"type='checkbox' id='ed".$dir->fields['iddireccion']."' value='1' /></td>
								</tr>";
							$dir->MoveNext();
						}
						mysql_free_result($dir->_queryID);
						$db->Close();
					}
								
								
							
	//<input disabled size='52' style='color:#000;background-color: #F9FCFF;border:none;font-size:11px;' type='text' value='".$dir->fields['dpto']."-".$dir->fields['prov']."-".$dir->fields['dist']."'/>
														
		echo"	</tbody>
			</table>";
		return false;	
	}if($_GET['tipo']=="result"){
		echo "<span id=\"span_gestiones_campo\" style=\"visibility:visible;position:relative\">
											<table width=\"100%\" id=\"tabla_gestiones_campo\">";
											$total=$db->Execute("	SELECT g.observacion,g.idcuenta,us.usuario,n.nivel,g.fecges,g.horges,g.fecreg,g.idgestion,cu.idcuenta,c.contactabilidad,r.resultado,g.feccomp,g.impcomp,d.direccion,g.observacion FROM gestiones g
																	JOIN resultados r ON g.idresultado=r.idresultado
																	JOIN direcciones d ON g.iddireccion=d.iddireccion
																	JOIN contactabilidad c ON g.idcontactabilidad=c.idcontactabilidad
																	JOIN cuentas cu ON g.idcuenta=cu.idcuenta
																	JOIN usuarios us ON g.usureg=us.idusuario
																	JOIN niveles n ON us.idnivel=n.idnivel
																	WHERE cu.idcliente=$idc AND us.idnivel=3  GROUP BY g.fecreg ORDER BY fecges DESC, horges DESC 
																	");
																	
																	
											$query=$db->Execute("	SELECT g.observacion,g.idcuenta,us.usuario,n.nivel,g.fecges,g.horges,g.fecreg,g.idgestion,cu.idcuenta,c.contactabilidad,r.resultado,g.feccomp,g.impcomp,d.direccion,g.observacion FROM gestiones g
																	JOIN resultados r ON g.idresultado=r.idresultado
																	LEFT JOIN direcciones d ON g.iddireccion=d.iddireccion
																	JOIN contactabilidad c ON g.idcontactabilidad=c.idcontactabilidad
																	JOIN cuentas cu ON g.idcuenta=cu.idcuenta
																	JOIN usuarios us ON g.usureg=us.idusuario
																	JOIN niveles n ON us.idnivel=n.idnivel
																	WHERE cu.idcliente='$idc'  AND us.idnivel='3' GROUP BY g.fecreg ORDER BY fecges DESC, horges DESC 
																	Limit 0,10
																	");						
																	//AND us.idnivel=3
																	$t_regist=$total->_numOfRows;
		echo"									<caption style='color: #27688F;background-color: #CDE9F5;'>Detalle Historico de Visitas  - ";
												if($t_regist<=10){ echo $t_regist;}else{ echo "10";}
																echo	" de ".$t_regist." ";
															 if($t_regist >10) {
																 echo	"<a onclick=\"gest_datos('02778833',2);\" href='#'>Siguiente ".utf8_encode("»")."</a>";
															}else{
																 echo	"Siguiente ".utf8_encode("»");
															}		
		echo "</caption>
												<thead>";
												mysql_free_result($total->_queryID);
												echo"	
													</tr>
													<tr>
														<th>Fecha</th>
														<th>Direccion</th>
														<th>Resultado</th>
														<th>Obs.</th>
													</tr>
												</thead>
												<tbody>";
												
										//if($query){
											
											while(!$query->EOF){
												echo 
													"<tr >
														<td align='left'><font color='blue'>".$query->fields['fecges']."</font>&nbsp; ".$query->fields['horges']."</td>
														<td><b>".$query->fields['direccion']."</b></td>
														<td>".$query->fields['resultado']."</td>
														<td >".$query->fields['observacion']."</td>
													</tr>";
												$query->MoveNext();
											}
											mysql_free_result($query->_queryID);
										//}
																
													
													
													
										echo"		</tbody>
											</table>
										</span>";
				$db->Close();						
				return false;						
	}
}

$periodo = $_SESSION['periodo'];

if(isset($_GET['proveedor'])){
	$id=$_GET['id_gca'];
	$sql="SELECT cl.cliente,c.idcuenta,c.idcartera,cr.cartera,p.idproveedor,p.proveedor FROM cuentas c 
		JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta
		JOIN clientes cl ON c.idcliente=cl.idcliente
		JOIN carteras cr ON c.idcartera=cr.idcartera
		JOIN proveedores p ON cr.idproveedor=p.idproveedor
		WHERE c.idcliente='$id'  AND cp.idperiodo='$periodo'
		GROUP BY cr.idcartera ORDER BY p.idproveedor ";
	//echo $sql;
	
	//$db->debug=true;
	$result = $db->Execute($sql);
	$n=1;
	while(!$result->EOF){
		
		if($n!=1){
			
			if($_SESSION['pro_campo']==$result->fields['idproveedor']){
				echo "-".$result->fields['idcartera'].".".$result->fields['cartera'];
				$result->MoveNext();
				$n++;
				continue;
			}else{
				echo ",";
			}
		}
		
		echo $result->fields['idproveedor'].".".$result->fields['proveedor']."*".$result->fields['idcartera'].".".$result->fields['cartera'];
				
		$_SESSION['pro_campo']=$result->fields['idproveedor'];
		$result->MoveNext();
		$n++;
	}
	mysql_free_result($result->_queryID);
	$db->Close();
	return false;
}

$id=$_GET['id_gca'];
$result = $db->Execute("SELECT iddireccion,direccion  FROM direcciones WHERE idcliente='$id'");
	while(!$result->EOF){
		echo $result->fields['iddireccion']."/".$result->fields['direccion']."*";
		$result->MoveNext();
	}
	mysql_free_result($result->_queryID);
echo	"<br/>";
$result = $db->Execute("SELECT cliente  FROM clientes WHERE idcliente='$id'");
	while(!$result->EOF){
		echo utf8_encode($result->fields['cliente'])."*";
		$result->MoveNext();
	}	
	
	mysql_free_result($result->_queryID);
	$db->Close();


?>