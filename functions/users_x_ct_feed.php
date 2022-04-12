<?php

session_start();
//$iduser=$_SESSION['iduser'];
ini_set('memory_limit', '-1');
set_time_limit(1800);
/** Error reporting */
error_reporting(E_ALL);

date_default_timezone_set('Europe/London');

/** PHPExcel */

include '../define_con.php';
$db_ori= NewADOConnection('mysql');
	$db_ori->Connect("192.168.50.16", "kobbases", "kob_210911", "orionc7_bases"); 	

	//$id_cartera=$_GET['idcartera'];
	$id_camp=$_GET['idcampana'];
	//$db_t->debug=true;
	
	if(isset($_GET['idusuario']) and isset($_GET['idcampana'])){
			$usuarios=explode(",",$_GET['idusuario']);
			$idcmp=$_GET['idcampana'];
			
			for($i=0;$i<(count($usuarios)-1);$i++){
				$sql_up="UPDATE ori_base set FlagMarcado=0,FlagContacto=0,ProFlag=0,ProEstado=0,PreFlag=0,PreEstado=0,activo=1 where  id_ori_campana=$idcmp and id_ori_usuario='$usuarios[$i]'"; /*Probando FlagContacto=1*/
				//$db_ori->debug=true;
				$db_ori->Execute($sql_up);
				//$db_ori->Execute("UPDATE ori_base set FlagMarcado=0,FlagContacto=0,ProFlag=0,ProEstado=0,PreFlag=0,PreEstado=0,activo=1 where  idcampana=$idcmp where idusuario='$usuarios[$i]'");
			}
	}
	/*$sql="SELECT rs2.id_ori_usuario,SUM(barrido=0) x_llamar,SUM(barrido!=0) llamado FROM
		 (SELECT rs.id_ori_usuario,rs.contacto,
			SUM(rs.PreFlag=1 AND rs.PreEstado=4 AND FlagContacto=1) barrido
			 FROM (SELECT id_ori_usuario,contacto,TelefonoMarcado,PreFlag,PreEstado,FlagContacto
			FROM ori_base WHERE id_ori_campana=$id_camp and activo=1 ORDER  BY contacto) AS rs
			GROUP BY rs.contacto,rs.id_ori_usuario ORDER BY barrido DESC) AS rs2
			GROUP BY rs2.id_ori_usuario
		";Pre*/
		
	$sql="SELECT rs2.id_ori_usuario,SUM(barrido=0) x_llamar,SUM(barrido!=0) llamado FROM
		 (SELECT rs.id_ori_usuario,rs.contacto,
			SUM(rs.ProFlag=1 AND rs.ProEstado=4 AND FlagContacto=1) barrido
			 FROM (SELECT id_ori_usuario,contacto,TelefonoMarcado,ProFlag,ProEstado,FlagContacto
			FROM ori_base WHERE id_ori_campana=$id_camp and activo=1 ORDER  BY contacto) AS rs
			GROUP BY rs.contacto,rs.id_ori_usuario ORDER BY barrido DESC) AS rs2
			GROUP BY rs2.id_ori_usuario
		";	/*Pro*/
		
	$up=$db_ori->Execute($sql);
	
	echo "<div style='width:600px;height:500px;overflow-y:scroll;'>
			<div id='design1'>
			<table>
				<tr>
					<th>Idusuario</th><th>Usuario</th><th>Total X Llamar</th><th>Contactados</th><th><input type='checkbox' name='user_c' value='all'  onclick='up_users_c(1);'/></th>
				</tr>";
	
	while(!$up->EOF){
		++$n;
		$id=$up->fields['id_ori_usuario'];
		$db_g= NewADOConnection('mysql');
		$db_g->Connect("localhost", "root", "g3st1onkb", "sis_gestion"); 
		$user=$db_g->Execute("Select usuario from usuarios where idusuario='$id'");
			echo "<tr>
					<td>".$up->fields['id_ori_usuario']."</td>
					<td>".$user->fields['usuario']."</td>
					<td>".$up->fields['x_llamar']."</td>
					<td>".$up->fields['llamado']."</td>
					<td><input type='checkbox' name='user_c' value='".$up->fields['id_ori_usuario']."' /></td>
				 </tr>";
		    $up->MoveNext();
	}
	
	echo "	</table>
	        </div>
		 </div>";
				//up_users_c(0);			
		echo"				
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type='button' class='btn' value='Dar Vuelta' onclick='up_users_c(2);'>
					
		  ";
		  
		  
		  
		
	
?>
