<?php 


$fecha="2011-02-12";
$fecha=explode("-",$fecha);
$año= $fecha[0];
$mes= $fecha[1];
$dia = $fecha[2];

echo date("l", mktime(0, 0, 0, $mes, $dia, $año));

?>
