<?php
//include '../scripts/conexion.php';
include '../define_con.php';

$id = $_SESSION['iduser'];
if(isset($_POST['parametros'])){
	$valor=$_POST['parametros'];
	if($valor=="val_dni"){

		$dni=$_POST['value'];
	
	    	 $result = $db->Execute("SELECT * FROM usuarios where documento='$dni'");
             	          
           	 if(!$result->EOF){ echo "Este Documento ya se encuentra registrado"; 
           	 }else{
                echo "";
             }
			 mysql_free_result($result->_queryID);
	}

	if($valor=="val_login"){
		$login=$_POST['value'];
		$login=trim($login);
	   //$db->debug=true;
		$result = $db->Execute("SELECT * FROM usuarios where login='$login'");
        
	  // echo $result->fields['idusuario'];     	          
           	 if(!$result->EOF){ echo "Este nombre de usuario ya se encuentra registrado"; 
           	 }else{
                echo "";
            	}
			mysql_free_result($result->_queryID);

	}
	
	if($valor=="val_dni_pr"){
	   $dni=$_POST['value'];
		$result = $db->Execute("SELECT * FROM proveedores where documento='$dni'");
             	          
           	 if(!$result->EOF){ echo "Este Documento ya se encuentra registrado"; 
           	 }else{
                echo "";
            	}

		mysql_free_result($result->_queryID);
	}

}
$db->Close();
?>