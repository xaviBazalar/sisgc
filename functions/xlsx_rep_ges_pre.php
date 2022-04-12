<?
include '../class/adodb5/adodb.inc.php';
session_start();
$iduser=$_SESSION['iduser'];
ini_set('memory_limit', '-1');
set_time_limit(1800);
/** Error reporting */
error_reporting(E_ALL);
date_default_timezone_set('Europe/London');

$idcampana=$_GET['campana'];
/*$cn=mysql_connect('192.168.50.16', 'kobbases', 'kob_210911') or die (mysql_error());
$db=mysql_select_db('orionc7_bases',$cn) or die (mysql_error());*/

$db=  &ADONewConnection('mysql');
$db ->Connect("192.168.50.16", "kobbases", "kob_210911", "orionc7_bases");

//echo "$idcampana";
$sql="Select telefonomarcado,contacto,proestado,fechaproceso,hour(fechaproceso) as hora,audio,contact from ori_base 
where id_ori_campana=".$idcampana." and proflag!=0" ;
$sql.=" Order BY fechaproceso";

$titulo="Telefono Marcado\tContacto\tResultado\tFecha / Hora\tHora";
//$query=$db->Execute($sql);
$fp=fopen('Rep_Ges_Ori.txt','w');
$body=$titulo;
fwrite($fp,$body);
fwrite($fp , chr(13).chr(10));
$query=$db->Execute($sql);
//$db->debug=true;
//4=contestado
//2=congestion / error de linea
//3=no conesto
while(!$query->EOF){
//if($query->fields['proestado']!=0){
$audio=explode("-",$query->fields['audio']);
$fec_g=$audio[4]."-".$audio[3]."-".$audio[2];
$hor_g=$audio[5].":".$audio[6].":".$audio[7];
   
if($query->fields['proestado']==4){
	if($query->fields['contact']==0){$res="NC";}
	if($query->fields['contact']==1){$res="CD";}
	if($query->fields['contact']==2){$res="CI";}
	if($query->fields['contact']==3){$res="NC";}
}else{
	$res="NC";
}


$cont="=\"".$query->fields['telefonomarcado']."\"\t";
$cont.="=\"".$query->fields['contacto']."\"\t";				
$cont.=$res."\t";
$cont.=$query->fields['fechaproceso']."\t";
$cont.=$query->fields['hora'];

fwrite($fp , $cont);
fwrite($fp , chr(13).chr(10));
$query->MoveNext();
//}
}
fclose($fp);
echo "Reporte Gestion Predictivo: <a href='functions/guardar_como.php?name=Rep_Ges_Ori.txt' target='blank'>Click para descargar</a><br/>";	
	
?>