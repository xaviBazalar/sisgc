<?php
session_start();
require 'define_con.php';
 if(isset($_GET['id_prove']) && $_GET['id_prove']!==""){
					
                    $id_prove=$_GET['id_prove'];
					if($_SESSION['nivel']==2){
						$cart=$_SESSION['cartera'];
						if(isset($_SESSION['varios'])){
							$cart=$_SESSION['varios'][$id_prove];
							if(explode(",",$cart)){
								$ext=" and idcartera in ($cart) ";
							}else{
								$ext=" and idcartera='$cart' ";
							}
						}else{
							$ext=" and idcartera='$cart' ";
						}
						
						
						
					}else{
						$ext="";
					}
					//$db->debug=true;
                    $r2 = $db->Execute("SELECT * FROM carteras WHERE idproveedor='$id_prove' $ext and idestado='1'");
                    //$r2->MoveNext();
                    $carteras = $r2->GetArray();
                    foreach ($carteras as $k => $v) {
                    $_carteras[] = "new Array('".$carteras[$k]["idcartera"]."', '".utf8_encode($carteras[$k]["cartera"])."')";
                    }
                    if (count($carteras) > 0) {
                    $carteras = "new Array(".implode(", ", $_carteras).");";
                    echo $carteras;
                    }
                    else {
                    echo "new Array(0);";
                    }
					mysql_free_result($r2->_queryID);
					$db->Close();
                    }
?>
