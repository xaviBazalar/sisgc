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
		
$db=  &NewADOConnection('mysqli');
$db->Connect("localhost", "root", "g3st1onkb", "sis_gestion"); 

		$sql="CALL show_direccion_x_cart($cart,$año_a,$mes_a)";
		$titulo="iddireccion\tfuente\torigen_direccion\tidcliente\tdireccion\tcoddpto\tcodprov\tcoddist\testado\tprioridad\tobservacion\tvalidacion\tfecreg";
		/*echo $sql;
		return false;*/	
		$n=1;
		$fp=fopen("datos_d.txt",'w');

		$body=$titulo;
		fwrite($fp,$body);
		fwrite($fp , chr(13).chr(10));
		$query=$db->Execute($sql);

		$db->Close();

		while(!$query->EOF){
				++$n;

					$cont=$query->fields['iddireccion']."\t";
					$cont.=$query->fields['idfuente']."\t";
					$cont.=$query->fields['idorigendireccion']."\t";
					$cont.="=\"".$query->fields['idcliente']."\"\t";
					$cont.=$query->fields['direccion']."\t";
					$cont.=$query->fields['coddpto']."\t";
					$cont.=$query->fields['codprov']."\t";
					$cont.=$query->fields['coddist']."\t";

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
			echo "<a href='guardar_como.php?name=datos_d.txt' target='blank'>Click para descargar</a><br/>";	
				

	$db->Close();
	
?>
