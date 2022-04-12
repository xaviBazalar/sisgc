<?php

	require_once 'define_con.php';
	require_once 'functions/login.class.php';
	if(!isset($_POST['c_pass']))
		$ch=0;
	else
		$ch=$_POST['c_pass'];
	
	$user= new Login($_POST['login'],$_POST['clave'],$ch,'');
	
		$ch = $user->test($user,$db);
			
		if($user->bol=="0")
		$user->validar($user,$db);
	$db->Close();	
			
?>
