<?php

include '../define_con.php';+
//$db->debug=true;
session_start();

$id = $_SESSION['iduser'];

if($_GET['acc']=='edit'){
	$det=$_GET['det'];
	$idTk=$_GET['idTk'];
	$fecf=date("Y-m-d H:i:s");
	
	$result = $db->Execute("update tickets set idusuario_i=$id,rpta='$det',estado='1',fecfin='$fecf' where idticket='$idTk'");
	
	return false;
}
if(isset($_GET['idT'])){
$idT=$_GET['idT'];
$dt=$_GET['dt'];

$result = $db->Execute("INSERT INTO tickets(idusuario,tipo_a,detalle) VALUES('$id','$idT','$dt')");
}


?>
<table cellpadding='1' cellspacing='0' style='margin:0 auto;width:90%;border:1px solid #C0C0C0;'>
			<tr class="ct"><th>Usuario</th><th>Tipo</th><th>Detalle</th><th>Fecha Inicio</th><th>Fecha Fin</th><th>Estado</th><th>U.Sistema</th></tr>
			<?php
								$sql="SELECT t.*,u.usuario,u2.usuario as user  FROM tickets t
									  join usuarios u on t.idusuario=u.idusuario 
									   left join usuarios u2 on t.idusuario_i=u2.idusuario	";
								$query= $db->Execute($sql);
								while(!$query->EOF){
								
									if($query->fields['estado']==1){
										$class="cl";
										$fc="";
									}else{
										$class="cl2";
										$fc="onclick='setTicket(".$query->fields['idticket'].");'";
									}
									
									echo "	<tr class='$class' $fc >
												<td>".$query->fields['usuario']."</td>";
												
												if($query->fields['tipo_a']==1){ $tipo_a="Soporte Tecnico";}
												if($query->fields['tipo_a']==2){ $tipo_a="Desarrollo / Sistema";}
												if($query->fields['tipo_a']==3){ $tipo_a="Telefonia & Redes";}
						
												
									echo "		<td>".$tipo_a."</td>
												<td>".$query->fields['detalle']."</td>
												<td>".$query->fields['fecini']."</td>
												<td>".$query->fields['fecfin']."</td>
												<td>";
												if($query->fields['estado']==1){echo "<img src='imag/icons/estado_1.png'>";}
												if($query->fields['estado']==0){echo "<img src='imag/icons/estado_0.png'>";}
												echo "</td>
												<td>".$query->fields['user']."</td>
											</tr>";
									$query->MoveNext();
								}
			?>
			
</table>