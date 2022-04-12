<?php
setlocale(LC_ALL,"","es_ES","esp");
$dia=strftime("%A");
$mes = strftime("%B");
switch($dia){
    case "Monday":
        $dia = "Lunes";
        break;
    case "Tuesday":
        $dia = "Martes";
        break;
    case "Wednesday":
        $dia = "Miercoles";
        break;
    case "Thursday":
        $dia = "Jueves";
        break;
    case "Friday":
        $dia = "Viernes";
        break;
    case "Saturday":
        $dia = "Sabado";
        break;
    case "Sunday":
        $dia = "Domingo";
        break;
    }

switch ($mes){
    case "January":
        $mes="Enero";
        break;
    case "February":
        $mes="Febrero";
        break;
    case "March":
        $mes="Marzo";
        break;
    case "April":
        $mes="Abril";
        break;
    case "May":
        $mes="Mayo";
        break;
    case "June":
        $mes="Junio";
        break;
    case "July":
        $mes="Julio";
        break;
    case "August":
        $mes="Agosto";
        break;
    case "September":
        $mes="Setiembre";
        break;
    case "October":
        $mes="Octubre";
        break;
    case "November":
        $mes="Noviembre";
        break;
    case "December";
        $mes="Diciembre";
        break;
       }
$fecha = strftime(" ".$dia." %d de ".$mes." del %Y");
$fecha = utf8_encode($fecha);
//$hora = date("H:m:s");
?>
