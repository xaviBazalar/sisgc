<?php
			/*if($query->fields['flag_p']==0){
					
					$iu=$query->fields['idusuario'];
					$par="p0=0&"; ///:agregar, 1,:editar, 2:borrar
					$par.="p1=".$query->fields['idusuario']."&";        
					$par.="p2=".$query->fields['login']."&";   
					$par.="p3=".$query->fields['usuario']."&"; 
					$par.="p4=".$_SESSION['pass_p']."&"; 
					$par.="p5=1&";				//  1:agente		  2:supervisor			  4:administrador
					$par.="p6=".$query->fields['idcampana']."&";//id campaña  editid2
					$par.="p7=";

					$web="http://192.168.50.16/orionc7_core/kob/ws03.php?$par";
					file_get_contents($web);
					//http://192.168.50.16/orionc7_core/kob/ws03.php?p0=2&p2=886
						//return false;
					$_SESSION['web']=$web;
					$db->Execute("UPDATE usuarios set flag_p='1' where idusuario='$iu'");
					
			}*/
				
?>