<?php
	session_start();
	if(isset($_SESSION['user'])){
		$usr=$_SESSION['user'];
	}else{
		$usr=$_GET['user'];
	}
	require 'define_con.php';
	$query = $db->Execute("UPDATE usuarios SET enlinea='0' where login='$usr' ");
	session_destroy();
//	mysql_free_result($query->_queryID);	
	$db->Close();
	header("Location: login.php");
?>