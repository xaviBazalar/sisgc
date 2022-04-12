<?php
require '../define_con.php';
		session_start();
		if($_GET['idac']!="" and $_GET['idcampo']==""){

			$id=$_GET['idac'];
					
			$r2= $db->Execute("SELECT r.idresultado,r.resultado,r.idgrupogestion,ac.idactividad
								 FROM actividad_resultados ac
								 JOIN resultados r ON ac.idresultado=r.idresultado
								 WHERE ac.idactividad=$id
										");
			$r_carteras = $r2->GetArray();
                    foreach ($r_carteras as $k => $v) {
						$_carteras[] = "new Array('".$r_carteras[$k]['idgrupogestion']."-".$r_carteras[$k]['idresultado']."-".$r_carteras[$k]['idactividad']."', '".substr(utf8_encode($r_carteras[$k]["resultado"]),3)."')";
                    }
                    if (count($r_carteras) > 0) {
						$r_carteras = "new Array(".implode(", ", $_carteras).");";
						echo $r_carteras;
                    }
                    else {
						echo "new Array(0);";
                    }
					mysql_free_result($r2->_queryID);
					$db->Close();
		}else{
			if($_GET['idcampo']!=30){
				$r2= $db->Execute(" SELECT * FROM resultados r
																		JOIN grupo_gestiones gg ON r.idgrupogestion=gg.idgrupogestion																
																		WHERE r.idestado=1 AND flag=1");
			}else{												
				$r2= $db->Execute("SELECT r.idresultado,r.resultado FROM actividad_resultados ar
										JOIN resultados r ON ar.idresultado=r.idresultado
										WHERE idactividad=13 AND r.fecreg LIKE '2012-09-03%' OR r.idresultado IN (248,351)
										GROUP BY r.idresultado
											");
			}
			$r_carteras = $r2->GetArray();
                    foreach ($r_carteras as $k => $v) {
						$_carteras[] = "new Array('0-".$r_carteras[$k]['idresultado']."', '".utf8_encode($r_carteras[$k]["resultado"])."')";
                    }
                    if (count($r_carteras) > 0) {
						$r_carteras = "new Array(".implode(", ", $_carteras).");";
						echo $r_carteras;
                    }
                    else {
						echo "new Array(0);";
                    }
					mysql_free_result($r2->_queryID);
					$db->Close();
		
		}

?>