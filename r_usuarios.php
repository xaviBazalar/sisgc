<?php
session_start();
if (!isset($_SESSION["user"]) || $_SESSION["user"] == "") {
	header("Location: login.php");
}


require 'define_con.php';

			
			
			$sql="SELECT usuario,idusuario FROM usuarios order by usuario";

            $result = $db->Execute($sql);
	
             echo "<div id=\"ykBody\">
                <center>
                <fieldset ><legend>Usuario</legend>
                    
                        <table style='text-align:left;width:400px;'> ";
                        echo "<tr><td>Usuario:</td><td style='background-color:white;'><select id='r_usuario' >";
							while (!$result->EOF) {
								echo "<option value='".$result->fields['idusuario']."'>".$result->fields['usuario']."</option>";
								 $result->MoveNext();	
							}
						echo "</select></td><td> <input type=\"button\" class='btn' onclick=\"resetear();\" value=\"Resetear \"/></td>
                         </tr>";
                       
                                         
            
		echo       "</table>
                 
                 </fieldset>
               
                    </center>
                </div>
                ";
		mysql_free_result($result->_queryID);
		$db->Close();




?>
