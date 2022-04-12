<?php

session_start();
$iduser=$_SESSION['iduser'];
ini_set('memory_limit', '-1');
set_time_limit(1800);
/** Error reporting */
error_reporting(E_ALL);

date_default_timezone_set('Europe/London');


include '../define_con.php';
		$peri=$_GET['peri'];

		$mes_p=$db->Execute("SELECT year(fecini) ano,MONTH(fecini) mes FROM periodos WHERE idperiodo='$peri'");
		$mes_a=$mes_p->fields['mes'];
		$año_a=$mes_p->fields['ano'];

		$cart=$_GET['cart'];	

			/*if($peri=="T"){
				$range="";
			}	else{
				$range=" AND YEAR(t.fecreg)=$año_a AND MONTH(t.fecreg)='$mes_a' ";
			}*/
			if($_GET['peri']==""){
				$db=  &NewADOConnection('mysqli');
				$db->Connect("localhost", "root", "g3st1onkb", "sis_gestion"); 
				
				$sql="call show_telefonos_x_cart($cart,$año_a,$mes_a)";
			}else{
				$sql="SELECT t.*
					FROM cuentas c
					JOIN telefonos t ON c.idcliente=t.idcliente 
					WHERE c.idcartera=$cart
					GROUP BY t.idtelefono;";
				$db->Execute($sql);
			}
				
			$titulo="idtelefono\tfuente\torigen_telefono\tdocumento\ttelefono\testado\tprioridad\tobservacion\tvalidacion\tfecreg";	
	
		$n=1;
		$fp=fopen("datos_t.txt",'w');

		$body=$titulo;
		fwrite($fp,$body);
		fwrite($fp , chr(13).chr(10));
		$query=$db->Execute($sql);
		$db->Close();

		while(!$query->EOF){
				++$n;
				
					$fono= str_replace("-", " ", $query->fields['telefono']);
					$fono= str_replace("*", " ", $query->fields['telefono']);
					$fono= str_replace("(", " ", $query->fields['telefono']);
					$fono= str_replace(")", " ", $query->fields['telefono']);
					$fono= str_replace("#", " ", $query->fields['telefono']);
					
					$cont=$query->fields['idtelefono']."\t";	
					$cont.=$query->fields['idfuente']."\t";	
					$cont.=$query->fields['idorigentelefono']."\t";
					$cont.="=\"".$query->fields['idcliente']."\"\t";
					$cont.="=\"".$fono."\"\t";
				
				$cont.=$query->fields['idestado']."\t";
				$cont.=$query->fields['prioridad']."\t";
				$cont.=$query->fields['observacion']."\t";
				$cont.=$query->fields['idvalidacion']." \t";
				$cont.=$query->fields['fecreg']."\t";

				fwrite($fp , $cont);
				fwrite($fp , chr(13).chr(10));
				$query->MoveNext();
			}
			fclose($fp);
			echo "<a href='guardar_como.php?name=datos_t.txt' target='blank'>Click para descargar</a><br/>";	
				

	$db->Close();
	
?>
