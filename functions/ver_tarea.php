<?php
	
	header ("Expires: Thu, 27 Mar 1980 23:59:00 GMT"); 
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
	header ("Cache-Control: no-cache, must-revalidate"); 
	header ("Pragma: no-cache"); 	
	include '../scripts/conexion.php';
	
	if(isset($_GET['t_estado']) && $_GET['t_estado']=="1"){
		$idT=$_GET['idtarea'];
		$up="Update tareas SET idestado='0' where idtarea='$idT' ";
		$db->Execute($up);
		
		return false;
	}
	session_start();
	$id_user = $_SESSION['iduser'];
	$id_prv=$_SESSION['idpro'];
	$id_nivel=$_SESSION['nivel'];
	$año=Date("Y");
	$mes=Date("m");
	$dia=Date("d");
	$hora=Date("H");
	$minuto=Date("i");

	/*$sql2="SELECT u.enlinea,t.* FROM tareas t
		JOIN usuarios u ON t.usureg=u.idusuario
		WHERE YEAR(t.fecha)=$año AND MONTH(t.fecha)=$mes AND DAY(t.fecha)=$dia AND  
		HOUR(t.hora)=$hora AND (MINUTE(t.hora)-6)=$minuto 
		AND u.idproveedor=$id_prv
		AND u.idnivel=$id_nivel";	
	
	$verificar=$db->Execute($sql2);	
	
	
	
	while(!$verificar->EOF){
		$id_tr=$verificar->fields['idtarea'];
			if($verificar->fields['enlinea']=="0"){
				$min=$minuto+06;
				$up="Update tareas SET usureg='$id_user', hora='$hora".":"."$min' where idtarea=$id_tr ";
				$db->Execute($up);
				$verificar->MoveNext();
				continue;
			}
		$verificar->MoveNext();
	}*/
		
	$sql="SELECT u.enlinea,t.* FROM tareas t
			JOIN usuarios u ON t.usureg=u.idusuario
			WHERE YEAR(t.fecha)=$año AND MONTH(t.fecha)=$mes AND DAY(t.fecha)=$dia AND  
			HOUR(t.hora)=$hora AND (MINUTE(t.hora)-10)=$minuto 
			AND t.usureg='$id_user'";
	//echo $sql;
	$consulta =$db->Execute($sql);
	$t_regist=$consulta->_numOfRows;
	if($t_regist=="1"){
		$cli=$consulta->fields['idcliente'];
		$sql="SELECT cliente FROM clientes WHERE idcliente='$cli'";
		$query =$db->Execute($sql);
		
		echo"<div  class=\"demo\">
			<div id=\"dialog\" title=\"Recordatorio de Tarea <br/> Hora : ".$consulta->fields['hora']."\">
				<p style='font-size:11px;'>Cliente:<font color='blue'>".utf8_encode($query->fields['cliente'])."</font>-<font color='red'>".$consulta->fields['idcliente']."</font><br/><br/>-".$consulta->fields['tarea']."</p>
			</div>
		 </div>";
	
	}else{
		echo"0";
	}
	mysql_free_result($consulta->_queryID);
	$db->Close();

?>