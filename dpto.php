<?php
require 'define_con.php';
 if(isset($_GET['id_dpto']) && $_GET['id_dpto']!==""){
                    $id_dpto=$_GET['id_dpto'];
                    $r2 = $db->Execute("SELECT  * FROM ubigeos WHERE coddpto=$id_dpto  AND coddist=00");
                    $r2->MoveNext();
                    $provincias = $r2->GetArray();
						foreach ($provincias as $k => $v) {
							$_provincias[] = "new Array('".$provincias[$k]["codprov"]."', '".utf8_encode($provincias[$k]["nombre"])."')";
						}
					
						if (count($provincias) > 0) {
							$provincias = "new Array(".implode(", ", $_provincias).");";
							echo $provincias;
						}
						else {
							echo "new Array(0);";
						}
						mysql_free_result($r2->_queryID);
						$db->Close();
	}
?>
