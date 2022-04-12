<?php
	
	if(isset($_POST) and $_POST['login']!=""){
		require_once 'define_con.php';
		ini_set('memory_limit', '-1');
		set_time_limit(1800);
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
		exit;
	}
			
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sis-GC</title>

<link href="login-box.css" rel="stylesheet" type="text/css" />
</head>

<body>


<div style="padding: 100px 0 0 450px;">


<div id="login-box">

<H2>Login</H2>

<br />
<br />
<form method="post" name="frmDatos" id="frmDatos"  action="login.php" >
	<input name="estado" type="hidden" value="0" />
	<div id="login-box-name" style="margin-top:20px;">Usuario:</div>
	<div id="login-box-field" style="margin-top:20px;">
		<input id="logn" name="login" type="text" class="form-login" title="Username" value="" size="30" maxlength="2048" />
	</div>
	<div id="login-box-name">Contrase&ntilde;a:</div>
	<div id="login-box-field">
		<input id="clave" name="clave" type="password" class="form-login" title="Password" value="" size="30" maxlength="2048" />
	</div>
		
	<br />
	<span class="login-box-options">
		<input id="c_pass" name="c_pass" type="checkbox" value="1"> Cambiar Contrase&ntilde;a 
	</span>
	<br />
	<br />
	<!--<a href="#" style="margin-left:30px;">Forgot password?</a>-->
	<a href="javascript:document.frmDatos.submit();">
		<img src="images/aceptar-btn.png" width="103" height="42" style="margin-left:130px;" />
	</a>
	 <?php
        if(isset($_GET['error'])){
			$error=$_GET['error'];
            echo "<br/><center><strong style=\" color: #FF\">$error</strong></center>";
			}
     ?>
</form>

</div>

</div>




</body>
</html>
