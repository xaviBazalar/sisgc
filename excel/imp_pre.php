<?php
	$con=mysql_connect("192.168.50.16","kobbases","kob_210911") or die(mysql_error());
	$db=mysql_select_db('orionc7_bases',$con) or die(mysql_error());
	
	
	$ar=fopen("Libro1.csv","r") or
	  die("No se pudo abrir el archivo");
	  $x=0;
	  while (!feof($ar))
	  {	
		++$x;
		$linea=fgets($ar);

		$sep=explode(",",str_replace("\"","",$linea));
		echo $linea."<br>";
		$sql="INSERT INTO ori_base(`id_ori_campana`,`id_ori_usuario`,`Contacto`,`TelefonoMarcado`,`TipoTelefono`,`Orden`,`pertel`,`cortel`,`cobert`,`bestch`,`prior1`,`prior2`,`prior3`,`prior4`,`prior5`,`activo`)
		VALUES('".$sep[15]."','".$sep[7]."','".$sep[0]."','".$sep[1]."','".$sep[3]."','".$sep[2]."','".$sep[4]."','".$sep[5]."','".$sep[6]."','".$sep[8]."','".$sep[9]."','".$sep[10]."','".$sep[11]."','".$sep[12]."','".$sep[13]."','".$sep[14]."')";
		$result=mysql_query($sql) or die(mysql_errno());
	 }
	 
	  fclose($ar);
?>
