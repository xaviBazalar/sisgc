<?php
include '../define_con.php';
/*
	
  p0  : 0: agregar ,1: editar , 2: borrar
  p1  : Nombre de la campa�a
  p2  : Tipo de campa�a
     p3  : Aqu� va el URL de la gesti�n 
  p4  : devolver valor "0"
  p5  :  ID de la campa�a en tu tabla
  p6  :  (vaci�) 
  p7  :  campa�a activa  
     0    :  no mostrar en monitoreo
     1    :  mostrar en monitoreo
  p8  :  (vaci�) 

*/
if($_GET['acc']=="ins"){
	$name=$_GET['nm'];
	$db->Execute("INSERT INTO campana (`campana`) value('$name')");
	$cm="p0=0&p1=$name";
	//$p�g = file_get_contents('http://192.168.50.16/orionc7_core/kob/ws02.php?'.$cm);
}

?>