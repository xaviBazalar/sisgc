<?
	//require "define_con.php";
	include '../scripts/conexion.php';
	//include "/scripts/conexion.php";
	$ano=$_GET['ano'];
	$cm="Select * from planillas where afp_ano=$ano ";
	if($_GET['mes']!=""){
		$cm.=" and afp_mes=".$_GET['mes'];
	}
	
	$fp=fopen("planilla.txt",'w');
	chmod("planilla.txt", 0777);
		
	$query=$db->Execute($cm) or die (mysql_error());
	$i=0;

	while(!$query->EOF)
	{
	$i++;
	$cont=$i."\t";
	$cont.=$query->fields['cuspp']."\t";
	$cont.="=\"".$query->fields['dni']."\"\t";
	$cont.=$query->fields['ape_paterno']."\t";
	$cont.=$query->fields['ape_materno']."\t";
	$cont.=$query->fields['nombres']."\t";
	$cont.=$query->fields['tipo_movimiento']."\t";
	$cont.=$query->fields['fecha_movimiento']."\t";
	$cont.=$query->fields['remuneracion_asegurable']."\t";
	$cont.=$query->fields['aporte_vol_c_f_lucro']."\t";
	$cont.=$query->fields['aporte_vol_s_f_lucro']."\t";
	$cont.=$query->fields['aporte_vol_empleador']."\t";
	$cont.=$query->fields['rubro_trabajador']."\t";
	$cont.=$query->fields['afp']."\t";

	fwrite($fp , $cont);
	fwrite($fp , chr(13).chr(10));
	$query->MoveNext();
	}
	fclose($fp);
	echo "Planilla:<a href='guardar_como.php?name=planilla.txt' target='blank'>Click para descargar</a><br/>";	
				
	//mysql_free_result($query->_queryID);
	$db->Close();
?>