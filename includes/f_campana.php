<?php
include '../define_con.php';
/*
	
  p0  : 0: agregar ,1: editar , 2: borrar
  p1  : Nombre de la campaña
  p2  : Tipo de campaña
     p3  : Aquí va el URL de la gestión 
  p4  : devolver valor "0"
  p5  :  ID de la campaña en tu tabla
  p6  :  (vació) 
  p7  :  campaña activa  
     0    :  no mostrar en monitoreo
     1    :  mostrar en monitoreo
  p8  :  (vació) 

*/
if($_GET['acc']=="ins"){
	$name=$_GET['nm'];
	$db->Execute("INSERT INTO campana (`campana`) value('$name')");
	$cm="p0=0&p1=$name";
	//$pág = file_get_contents('http://192.168.50.16/orionc7_core/kob/ws02.php?'.$cm);
}

?>