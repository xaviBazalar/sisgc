<?php

	include '../scripts/conexion.php';
	$db_ori= NewADOConnection('mysql');
	$db_ori->Connect("192.168.50.16", "kobbases", "kob_210911", "orionc7_bases");
	$sql="SELECT rs2.id_ori_usuario,SUM(barrido=0) x_llamar,SUM(barrido!=0) llamado FROM
		 (SELECT rs.id_ori_usuario,rs.contacto,
			SUM(rs.PreFlag=1 AND rs.PreEstado=4 AND FlagContacto=1) barrido
			 FROM (SELECT id_ori_usuario,contacto,TelefonoMarcado,PreFlag,PreEstado,FlagContacto
			FROM ori_base WHERE id_ori_campana=3 and activo=1 and TipoTelefono='F' ORDER  BY contacto) AS rs
			GROUP BY rs.contacto ORDER BY barrido DESC) AS rs2
			GROUP BY rs2.id_ori_usuario
		";
	$consulta=$db_ori->Execute($sql);
	echo "<table style='\"lucida grande\",tahoma,verdana,arial,sans-serif;font-weight: normal; font-size:11px;'>
			<th>Usuario</th><th>Total x Llamar</th><th>Total Contactado</th>";
	while(!$consulta->EOF){
		$id=$consulta->fields['id_ori_usuario'];
		$db_g= NewADOConnection('mysql');
		$db_g->Connect("localhost", "root", "g3st1onkb", "sis_gestion"); 
		$user=$db_g->Execute("Select usuario from usuarios where idusuario='$id'");
		echo "<tr><td>".$user->fields['usuario']."</td><td>".$consulta->fields['x_llamar']."</td><td>".$consulta->fields['llamado']."</td></tr>"; 
		$consulta->MoveNext();
		
	}
	echo "</table>";
	
	$db_ori->Close();

?>
