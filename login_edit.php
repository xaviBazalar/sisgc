<?php
	
	if(isset($_POST) and $_POST['login']!=""){
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
		exit;
	}
			
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sis-GC</title>
<!--{literal}-->
<script>
	function Ajax(){var xmlhttp=false;
            try {xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e)
            {try {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (E) {
                    xmlhttp = false;
                }
            }
            if (!xmlhttp && typeof XMLHttpRequest!='undefined'){
                xmlhttp = new XMLHttpRequest();
            }return xmlhttp;
   	}

	function up_date(){
			var c1=document.frmDatos.clave1.value
			div_vf=document.getElementById("vf");
			ajax=Ajax();
			ajax.open("POST", "functions/update_l_c.php",true);

			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");//Cabecera obligatoria para method POST

			var para="clave="+c1;
			ajax.send(para);//enviamos parametros

			ajax.onreadystatechange=

				function() {
					if (ajax.readyState==4) {
						if(ajax.responseText=="true"){
							window.open('index.php','_self');
						}
					//div_vf.innerHTML = ajax.responseText
					
					}
				}
	}

	function launchYK(pagina) {
		window.open(pagina, "YKSys", "chrome,centerscreen,resizable");
	}

	function validar() {
		var c1=document.frmDatos.clave1.value
		var c2=document.frmDatos.clave2.value
		div_vf=document.getElementById("vf");

		if(c1!==c2 ){
		div_vf.innerHTML="Las claves no coinciden!";
		return false;
		}
		if(c1=="" || c2==""){
		div_vf.innerHTML="Introduzca la clave porfavor";
		return false;
		}
		up_date();
	}
	
	function salir(){
		location.href='logout.php';
	}
	if (top.location != self.location)top.location = self.location;
</script>
<!--{/literal}-->
<link href="login-box.css" rel="stylesheet" type="text/css" />
</head>

<body>


<div style="padding: 100px 0 0 450px;">


<div id="login-box">

<H2>Cambio de clave</H2>

<br />
<br />
<form method="post" name="frmDatos" id="frmDatos"  action="login.php" >
	<input name="estado" type="hidden" value="0" />
	<div id="login-box-name" style="margin-top:20px;">Contraseña:</div>
	<div id="login-box-field" style="margin-top:20px;">
		<input id="clave1" type="password" class="form-login" title="Username" value="" size="30" maxlength="2048" />
	</div>
	<div id="login-box-name">Repetir:</div>
	<div id="login-box-field">
		<input id="clave2" type="password"  class="form-login" title="Password" value="" size="30" maxlength="2048" />
	</div>
		
	<br />
	<br />
	<br />
	<!--<a href="#" style="margin-left:30px;">Forgot password?</a>-->
	<nobr>
		<a href="javascript:validar();">
			<img src="images/guardar-btn.png" width="103" height="42" style="margin-left:60px;" />
		</a>
		<a href="javascript:salir();">
			<img src="images/regresar-btn.png" width="103" height="42" style="margin-left:10px;" />
		</a>
	</nobr>
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
