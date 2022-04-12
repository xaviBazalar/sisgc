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
p9: idcampaña sistema gestion
*/
if($_GET['acc']=="ins"){
	$name=$_GET['nm'];
	
	$db->Execute("INSERT INTO campana (`campana`) value('$name')");
	$result=$db->Execute("SELECT idcampana from campana order by idcampana desc limit 0,1");
	while(!$result->EOF){
			$id=$result->fields['idcampana'];
			$result->MoveNext();
	}
	$cm="p0=0&p1=$name&p5=$id&p9=$id";
	
	$pág = file_get_contents('http://192.168.50.16/orionc7_core/kob/ws02.php?'.$cm);
}

if($_GET['acc']=="up"){
	$con=mysql_connect("192.168.50.16","kobbases","kob_210911") or die(mysql_error());
	$db=mysql_select_db('orionc7_bases',$con) or die(mysql_error());
		$id=$_GET['idC'];
		$tipo_tel=$_GET['tt'];
		
		$p1=$_GET['p1'];
		$p2=$_GET['p2'];
		if($tipo_tel=="F"){
			$sql="UPDATE ori_base set activo=1 where id_ori_campana=$id ";
			$result=mysql_query($sql) or die(mysql_error());
			$sql="UPDATE ori_base set activo=0 where id_ori_campana=$id  and TipoTelefono!='$tipo_tel'";
			$result=mysql_query($sql) or die(mysql_error());
		}else if($tipo_tel=="M"){
			$sql="UPDATE ori_base set activo=1 where id_ori_campana=$id ";
			$result=mysql_query($sql) or die(mysql_error());
			$sql="UPDATE ori_base set activo=0 where id_ori_campana=$id  and TipoTelefono!='$tipo_tel'";
			$result=mysql_query($sql) or die(mysql_error());
		}else if($tipo_tel=="T"){
			$sql="UPDATE ori_base set activo=1 where id_ori_campana=$id ";
			$result=mysql_query($sql) or die(mysql_error());
		}
		
		if($p1!=""){
			$sql="UPDATE ori_base set activo=0 where prior1!=$p1 and id_ori_campana=$id  ";
			//echo $sql;
			$result=mysql_query($sql) or die(mysql_error());
			$sql="UPDATE ori_base set activo=1 where  prior1=$p1 and id_ori_campana=$id  ";
			$result=mysql_query($sql) or die(mysql_error());
		}
		
		if($p2!=""){
			$sql="UPDATE ori_base set activo=0 where prior2!=$p2 and id_ori_campana=$id  ";
			//echo $sql;
			$result=mysql_query($sql) or die(mysql_error());
			$sql="UPDATE ori_base set activo=1 where  prior2=$p2 and id_ori_campana=$id  ";
			$result=mysql_query($sql) or die(mysql_error());
		}
		echo $sql;
		//$result=mysql_query($sql) or die(mysql_error());

}


if($_GET['rp']=="CD"){
	$con=mysql_connect("192.168.50.16","kobbases","kob_210911") or die(mysql_error());
	$db=mysql_select_db('orionc7_bases',$con) or die(mysql_error());

		$sql="UPDATE";
		$result=mysql_query($sql) or die(mysql_errno());

}

if($_GET['rp']=="CI"){
	$con=mysql_connect("192.168.50.16","kobbases","kob_210911") or die(mysql_error());
	$db=mysql_select_db('orionc7_bases',$con) or die(mysql_error());

		$sql="UPDATE";
		$result=mysql_query($sql) or die(mysql_errno());

}

if($_GET['rp']=="NC"){
	$con=mysql_connect("192.168.50.16","kobbases","kob_210911") or die(mysql_error());
	$db=mysql_select_db('orionc7_bases',$con) or die(mysql_error());

		$sql="UPDATE";
		$result=mysql_query($sql) or die(mysql_errno());

}


?>