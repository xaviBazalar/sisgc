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
$db_t=  &ADONewConnection('mysql');
$db_t->Connect("localhost", "root", "g3st1onkb", "sis_gestion"); 	

	$id_cartera=$_GET['idcartera'];
	$id_camp=$_GET['idcampana'];
	//$db_t->debug=true;
	
	if(isset($_GET['idusuario']) and isset($_GET['idcampana'])){
			$usuarios=explode(",",$_GET['idusuario']);
			$idcmp=$_GET['idcampana'];
			
			for($i=0;$i<(count($usuarios)-1);$i++){
				$db_t->Execute("UPDATE usuarios set flag_p=1, idcampana=$idcmp where idusuario='$usuarios[$i]'");
					
					$web="http://192.168.50.16/orionc7_core/kob/ws03.php?p0=2&p1=$usuarios[$i]";
					file_get_contents($web);
					
					
					$usr=$db_t->Execute("select idusuario,usuario,login,clave from usuarios where idusuario='$usuarios[$i]'");
					while(!$usr->EOF){
						
						$par="p0=0&"; ///0:agregar, 1,:editar, 2:borrar
						$par.="p1=".$usr->fields['idusuario']."&";        
						$par.="p2=".$usr->fields['login']."&";   
						$par.="p3=".str_replace(" ","_",$usr->fields['usuario'])."&"; 
						$par.="p4=".$usr->fields['clave']."&"; 
						$par.="p5=1&";				//  1:agente		  2:supervisor			  4:administrador
						$par.="p6=".$idcmp."&";//id campaña  editid2
						$par.="p7=";
					
						$web2="http://192.168.50.16/orionc7_core/kob/ws03.php?p0=0&$par";
						file_get_contents($web2);
					
						
						$usr->MoveNext();
					}

			}
	}
	
	$up=$db_t->Execute("SELECT  u.idusuario,u.usuario,u.login,c.campana 
						FROM usuarios u 
						LEFT join campana c ON u.idcampana=c.idcampana
							WHERE u.idcartera='$id_cartera'  and u.idestado=1 ");
	
	echo "<div style='width:600px;height:500px;overflow-y:scroll;'>
			<div id='design1'>
			<table>
				<tr>
					<th>Idusuario</th><th>Nombre y Apellido</th><th>Login</th><th>Campa&ntilde;a</th><th><input type='checkbox' name='user_c' value='all'  onclick='up_users_c(1);'/></th>
				</tr>";
	
	while(!$up->EOF){
		++$n;
			echo "<tr>
					<td>".$up->fields['idusuario']."</td>
					<td>".$up->fields['usuario']."</td>
					<td>".$up->fields['login']."</td>
					<td>".$up->fields['campana']."</td>
					<td><input type='checkbox' name='user_c' value='".$up->fields['idusuario']."' /></td>
				 </tr>";
		    $up->MoveNext();
	}
	
	echo "	</table>
	        </div>
		 </div>";
		 echo"<b>Seleccionar Campa&ntilde;a: </b>
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
		  
		  
		  
		
	
?>
