<?php
require '../define_con.php';
		session_start();
		if($_GET['valor']!=""){

			$id=$_GET['valor'];
			$id_pro=$_SESSION['idpro'];
			$id_cart=$_SESSION['cartera'];
			/*$query= $db->Execute("SELECT idjustificacion,justificacion FROM justificaciones WHERE idresultado='$id' ");*/
			//$db->debug=true;
			//if($_SERVER['REMOTE_ADDR']=="192.168.50.44"){$db->debug=true;}							
			$query= $db->Execute("SELECT j.idjustificacion,j.justificacion FROM justificacion_carteras jc
										JOIN justificaciones j ON jc.idjustificacion=j.idjustificacion
										JOIN resultados r ON j.idresultado=r.idresultado
										JOIN carteras c ON jc.idcartera=c.idcartera
										JOIN proveedores p ON c.idproveedor=p.idproveedor
										WHERE r.idresultado='$id'
										/*AND p.idproveedor='$id_pro'*/
										AND c.idcartera='$id_cart'
										and jc.idestado='1'
										");
		
			$total=$query->_numOfRows;
			$n=0;
			while(!$query->EOF){
			++$n;
				if($n==$total){
				echo $query->fields['justificacion']."-".$query->fields['idjustificacion'];
				break;
				}
			echo $query->fields['justificacion']."-".$query->fields['idjustificacion'].",";
			$query->MoveNext();
			}
		}

?>