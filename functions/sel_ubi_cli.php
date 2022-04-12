<?php
require '../define_con.php';


		if($_GET['id']!="" and $_GET['ubi']!=""){
			$id=$_GET['id'];
			$ubi=$_GET['ubi'];
			$v=$db->Execute("select * from ubicabilidad_clientes where idcliente='$id'  ");
			
			if($v->fields['idcliente']==""){
				$db->Execute("insert into ubicabilidad_clientes (idcliente,idubicabilidad) values ('$id',$ubi) ");
			}else{
				$db->Execute("update ubicabilidad_clientes set idubicabilidad=$ubi where idcliente='$id' ");
			}
		}else if($_GET['id']!=""){
			$id=$_GET['id'];
			$ubi=$_GET['ubi'];
			$v=$db->Execute("select * from ubicabilidad_clientes where idcliente='$id'  ");
			
			echo $v->fields['idubicabilidad'];
		
		}

?>