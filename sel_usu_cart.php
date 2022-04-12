<?php
session_start();
require 'define_con.php';
 if(isset($_GET['idcart']) && $_GET['idcart']!==""){
					
                    $idcartera=$_GET['idcart'];

					//$db->debug=true;
                    $r2 = $db->Execute("SELECT idusuario,usuario FROM usuarios WHERE idcartera=$idcartera AND idestado=1 AND idnivel=2");
                    //$r2->MoveNext();
                    $usuarios= $r2->GetArray();
                    foreach ($usuarios as $k => $v) {
                    $_usuarios[] = "new Array('".$usuarios[$k]["idusuario"]."', '".$usuarios[$k]["usuario"]."')";
                    }
                    if (count($usuarios) > 0) {
                    $usuarios = "new Array(".implode(", ", $_usuarios).");";
                    echo $usuarios;
                    }
                    else {
                    echo "new Array(0);";
                    }
					mysql_free_result($r2->_queryID);
					$db->Close();
                    }
?>
