<?php

session_start();
$iduser=$_SESSION['iduser'];
ini_set('memory_limit', '-1');
set_time_limit(1800);
/** Error reporting */
error_reporting(E_ALL);

date_default_timezone_set('Europe/London');

/** PHPExcel */

include '../define_con.php';
$db_t= $db;

	$id_cartera=$_GET['idcartera'];
	//$id_camp=$_GET['idcampana'];
	//$db_t->debug=true;
	
	if(isset($_GET['idusuario']) and isset($_GET['idcampana'])){
			$usuarios=explode(",",$_GET['idusuario']);
			$idcmp=$_GET['idcampana'];
			

	}
	
	$up=$db_t->Execute("SELECT  u.idusuario,u.usuario,u.login,c.campana 
						FROM usuarios u 
						LEFT join campana c ON u.idcampana=c.idcampana
							WHERE u.idcartera='$id_cartera'  and u.idestado=1 and u.idnivel!=1 and u.idnivel!=5 and u.idnivel!=7");
	
	echo "<div style='float:left;width:600px;height:500px;overflow-y:scroll;'>
			<div id='design1'>
			<table>
				<tr>
					<th>Idusuario</th><th>Nombre y Apellido</th><th>Login</th><th></th>
				</tr>";
	$n=0;
	while(!$up->EOF){
		++$n;
			echo "<tr>
					<td>".$up->fields['idusuario']."</td>
					<td>".$up->fields['usuario']."</td>
					<td>".$up->fields['login']."</td>
					<td><input type='radio' name='user_c' value='".$up->fields['idusuario']."' onchange='users_c_c2(".$up->fields['idusuario'].");' /></td>
				 </tr>";
		    $up->MoveNext();
	}
	
	echo "	</table>
	        </div>
		 </div>
		 <div id='usuarios_carteras' style='width:300px;height:500px;float:left;margin-left:20px;'>
		 </div>
		 ";
		/* echo"<b>Seleccionar Campa&ntilde;a: </b>
					<select onchange='' id='c_id' name='id_camp'>
						<option value=''>Seleccione...</option>";
					
							$sql="SELECT idcampana,campana FROM campana ";
							
							$query= $db->Execute($sql);
							while(!$query->EOF){
								echo "<option value='".$query->fields['idcampana']."'>".$query->fields['campana']."</option>";
								$query->MoveNext();
							}
						
		echo"				</select>
									
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type='button' class='btn' value='Actualizar' onclick='up_users_c(0);'>
		  ";
		  */
		  
		  
		
	
?>
