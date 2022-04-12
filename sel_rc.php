<?php
require 'define_con.php';
 if(isset($_GET['id_cart']) && $_GET['id_cart']!==""){
                    $id_cart=$_GET['id_cart'];
                    $r2 = $db->Execute("SELECT rs.resultado,rs.idresultado FROM resultado_carteras rc
                                        JOIN carteras c ON c.idcartera=rc.idcartera
                                        JOIN proveedores p ON p.idproveedor=c.idproveedor
                                        JOIN resultados rs ON rs.idresultado=rc.idresultado
                                        WHERE rc.idcartera=$id_cart ");
                    //$r2->MoveNext();
                    $r_carteras = $r2->GetArray();
                    foreach ($r_carteras as $k => $v) {
                    $_carteras[] = "new Array('".$r_carteras[$k]["idresultado"]."', '".utf8_encode($r_carteras[$k]["resultado"])."')";
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

