<?php
session_start();
require 'define_con.php';
 if(isset($_GET['id_cart']) && $_GET['id_cart']!==""){
					
                    $id_cart=$_GET['id_cart'];
					
                    $sql="SELECT tc.* FROM cuentas c
							JOIN carteras ct ON c.idcartera=ct.idcartera
							JOIN tipo_carteras tc ON c.idtipocartera=tc.idtipocartera
							WHERE c.idcartera='$id_cart' and tc.idestado=1
							GROUP BY tc.idtipocartera ";
							
					$r2= $db->Execute($sql);
							
                    $carteras = $r2->GetArray();
                    foreach ($carteras as $k => $v) {
                    $_carteras[] = "new Array('".$carteras[$k]["idtipocartera"]."', '".utf8_encode($carteras[$k]["tipocartera"])."')";
                    }
                    if (count($carteras) > 0) {
                    $carteras = "new Array(".implode(", ", $_carteras).");";
                    echo $carteras;
                    }
                    else {
                    echo "new Array(0);";
                    }
					
					$db->Close();
                    }
?>
