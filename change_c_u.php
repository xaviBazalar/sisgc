<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="style/login.css" rel="stylesheet" type="text/css" />
<title>Sistema Gestion</title>
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

	if (top.location != self.location)top.location = self.location;
</script>
<!--{/literal}-->
</head>
<body>
<div id="ykWrapper">
<noscript><h3><center>¡ATENCIÓN! Se debe permitir Javascript para la operación apropiada del administrador</center></h3></noscript>

	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr><td width="30%">
	<div id="ykLeft">
		<!--<img src="style/blue/images/logo_prejudicial_ori.jpg" width="178" height="50" />-->	</div>
	</td>
	<td>
	<div id="ykRight">
		<div class="login">
			<h2><img src="style/blue/images/ico_prejudicial.jpg" width="16" height="18" />Cambio de Clave</h2>
                        <form method="post" name="frmDatos" id="frmDatos"  action="change_c_u.php">
				<div class="form-block">
					<!--<span><img src="imag/tmp/titulo.jpg" /></span>-->
                                        <input name="estado" type="hidden" value="0" /><br />
					<label class="inputlabel">Nueva Clave :</label><input id="clave1" type="password"  class="inputbox" size="15" /><br />
					<label class="inputlabel">Repetir Nueva Clave :</label><input id="clave2" type="password" class="inputbox" size="15" /><br />
					<!--<span><input name="pantalla_completa" type="checkbox" value="1" />
					<label>Abrir en pantalla completa</label></span>-->
                                        <?php
                                        if(isset($_GET['error'])){
											$error=$_GET['error'];
                                            echo "<span><strong style=\" color: #FF0000\">$error</strong></span>";}
                                        ?>
					<!--<span><strong style=" color: #FF0000">{$mensaje}</strong></span>-->
					<span><input style="cursor:pointer;"  type="button" onclick="validar();" class="button" value="Guardar" />
						<input style="cursor:pointer;" type="button" onclick="location.href='logout.php'" class="button" value="Regresar" />
					</span>
				</div>
			</form>
			<div id="vf" align="center" style=" color: #FF0000"></div>
			<div align="right"><img src="imag/tmp/esquina_inferior.jpg" /></div>
		</div>
		<div class="footer">
			<div>Copyright 2010 Kobsa. Todos los derechos reservados.</div>
			<!--<div>Power by <a href="#" target="_blank">Kobsa</a></div>-->
  <!--
			<div>&nbsp;</div>
			<div>&nbsp;</div>
   			<div><font color="#FF0000">Usted ha excedido el limite permitido de intentos.</font></div>
   			<div><font color="#FF0000">Sirvase comunicarse con el Encargado de Sistemas.</font></div>         -->

		</div>
	</div>
	</td>
	</tr>
	</table>
</div>
</body>
</html>
<!--{literal}-->
<script type="text/javascript">
	document.frmDatos.usuario.focus();
</script>
<!--{/literal}-->