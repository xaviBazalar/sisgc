<?php

session_start();
require 'define_con.php';
	if(isset($_GET['idC']) && $_GET['idC']==""){
		header("Location: index.php");
	}
	
	if($_GET['USER_AUTH_TOKEN_APPLICATION']!=""){
		$_SESSION["user"]=$_GET['USER_AUTH_TOKEN_APPLICATION'];
		$_SESSION["user"]="IC";
		//$db->debug=true;
		require_once 'functions/login.class.php';

		$user= new Login('IC','123456',0,'');
		$ch = $user->test($user,$db);
		//if($user->bol=="0")
		$user->validar($user,$db);
		
	}
	
	if (!isset($_SESSION["user"]) || $_SESSION["user"] == "") {
		header("Location: logout.php");
		//exit();
	}else{
		$usr=$_SESSION['user'];
		
		$n = 0;
		$query = $db->Execute("SELECT * FROM usuarios WHERE login='$usr'");
		$nivel = $db->Execute("SELECT n.nivel,u.usuario,u.idusuario,u.idnivel,u.idproveedor FROM usuarios u
							  JOIN niveles n ON n.idnivel=u.idnivel
							  WHERE u.login='$usr'");
		$_SESSION['usuario']=$nivel->fields['usuario'];								  
		$_SESSION['idpro']=$nivel->fields['idproveedor'];
		$_SESSION['tnivel']=$_SESSION['nivel'];	
		$_SESSION['security']="xsx";
		if($_SERVER['REMOTE_ADDR']=="192.168.50.44"){
			
			//$query = $db->Execute("UPDATE usuarios SET enlinea='1' where login='$usr' ");
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SISGC</title>
<noscript><meta http-equiv="refresh" content="0; URL=login.php"/></noscript>
<?php 	if($_SESSION['nivel']!="2"){?>
<script language="javascript" src="includes/jquery-1.3.1.min.js"></script>
<?php   }?>
<script type="text/javascript" src="inconcert/lib/jquery/jquery-1.3.2.js"></script>
<script type="text/javascript" src="inconcert/lib/jquery/jquery.jsonp-2.2.0.js"></script>
<script type="text/javascript" src="inconcert/lib/webtoolkit/webtoolkit.md5.js"></script>
<script type="text/javascript" src="inconcert/shared/cookies/cookies.js"></script>
<script type="text/javascript" src="inconcert/lib/sessvars/sessvars.js"></script>
<script type="text/javascript" src="inconcert/shared/scripts/inconcert.session_storage.js"></script>
<script type="text/javascript" src="inconcert/inConcertSDK.js"></script>

<script type="text/javascript">
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
    
	function abreventana(){
        var user= document.getElementById("usrP").value;
        var ajax = Ajax();
        ajax.open("GET", "logout.php?user="+user,true);
        ajax.send(null);
        alert("Sesion finalizada por Seguridad");
    }

    <?php if($_SERVER['REMOTE_ADDR']=="192.168.50.4"){?>
	window.onbeforeunload = function(){
                   abreventana();
	}
	<?php }?>

</script> 
<!-- Hmenu para el menu del administrador -->
<?php 	if($_SESSION['nivel']=="1" or $_SESSION['nivel']=="5" or $_SESSION['nivel']=="7" or $_SESSION['nivel']=="4"){
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"hmenu/skin-xp.css\" />
				  <script type=\"text/javascript\">_dynarch_menu_url = \"hmenu/\";</script>
				  <script type=\"text/javascript\" src=\"hmenu/hmenu.js\"></script>
				  <script type=\"text/javascript\">MENU_ITEM=\"index\"</script>";
		}
?>
<!-- file css vacio, solo para evitar bugs-->
	<link rel="stylesheet" type="text/css" href="style/blue/menu.css" />

	<!-- zpForm para validar los formularios -->
	<script src="style/zpform/utils/utils.js" type="text/javascript"></script>
	<script src="style/zpform/utils/transport.js" type="text/javascript"></script>
	<script src="style/zpform/src/form.js" type="text/javascript"></script>
	<link href="style/zpform/themes/alternate.css" rel="stylesheet" />

<!-- Spry para mostrar en pestañas los datos de cliente -->
	<script src="spry/SpryTabbedPanels.js" type="text/javascript"></script>
	<link href="spry/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
	
	<link href="style/blue/blue.css" rel="stylesheet" type="text/css" />
	<link href="style/blue/print.css" rel="stylesheet" type="text/css" media="print" />
	<link href="style/tables.css" type="text/css" rel="stylesheet"/>
	<link href="style/filas.css" type="text/css" rel="stylesheet" />

    <?php if($_SESSION['nivel']=="1" or $_SESSION['nivel']=="5" or $_SESSION['nivel']=="7" or $_SESSION['nivel']=="4"){ ?>
		<script type="text/javascript" src="includes/calendar/src/js/jscal2.js"></script>
    	<script type="text/javascript" src="includes/calendar/src/js/lang/es.js"></script>
    	<link rel="stylesheet" type="text/css" href="includes/calendar/src/css/jscal2.css" />
    	<link rel="stylesheet" type="text/css" href="includes/calendar/src/css/border-radius.css" />
    	<link rel="stylesheet" type="text/css" href="includes/calendar/src/css/steel/steel.css" />
		
		<script type="text/javascript">
                            //<![CDATA[
                        function cale(){  var cal = Calendar.setup({
                              onSelect: function(cal) { cal.hide() },
                              showTime: true
                          });
                          cal.manageFields("bcalendario1", "desde", "%Y-%m-%d");
                          
                        }
                        function cale1(){  var cal = Calendar.setup({
                              onSelect: function(cal) { cal.hide() },
                              showTime: true
                          });
                          
                          cal.manageFields("bcalendario2", "hasta", "%Y-%m-%d");
                        }
                        function cale2(){  var cal = Calendar.setup({
                              onSelect: function(cal) { cal.hide() },
                              showTime: true
                          });

                          cal.manageFields("f_btn1", "u_fi", "%Y-%m-%d");
                        }
                        function cale3(){  var cal = Calendar.setup({
                              onSelect: function(cal) { cal.hide() },
                              showTime: true
                          });

                          cal.manageFields("f_b1", "u_fn", "%Y-%m-%d");
                        }

                        //]]>
        </script>
<?php } ?>
<!-- Prototype para extender funciones de JS -->
<!--<script type="text/javascript" src="includes/prototype.js"></script>--> 
<script src="includes/filas.js"></script>

<!--<script src="scripts/jquery-1.4.4.min.js" type="text/javascript" charset="utf-8"></script>-->
<?php if($_SESSION['nivel']=="1" or $_SESSION['nivel']=="5" or $_SESSION['nivel']=="7" or $_SESSION['nivel']=="4" ){ ?>
<script type="text/javascript" src="scripts/gs_admin.js?id=<?php echo Date("s");?>"></script>
<link href="style/campana.css" rel="stylesheet" type="text/css" />
<link href="includes/int.css" rel="stylesheet" type="text/css" />

<?php } ?>

<?php 	$nro=array(5,7,1,6);
		if(!in_array($_SESSION['nivel'],$nro)){
?>
<style type="text/css">@import url("includes/calendar/calendar-blue2.css");</style>
<script type="text/javascript" src="includes/calendar/calendar.js"></script>
<script type="text/javascript" src="includes/calendar/lang/calendar-es.js"></script>
<script type="text/javascript" src="includes/calendar/calendar-setup.js"></script>
<?php } ?>
	
<?php if($_SESSION['nivel']=="2" or  $_SESSION['nivel']=="5" ){ ?>
<script type="text/javascript" src="scripts/gs_val.js?id=<?php echo date("s");?>"></script>
<?php } ?>
<?php if($_SESSION['nivel']=="3" ){ ?>
<script type="text/javascript" src="scripts/gs_campo.js?id=<?php echo date("s");?>"></script>
<?php } ?>

<?php if($_SESSION['nivel']=="1111" ){ ?>
<script type="text/javascript" src="scripts/shadowbox.js"></script>
	<link rel="stylesheet" type="text/css" href="scripts/shadowbox.css">
	<script type="text/javascript">
		Shadowbox.init();
	</script>

<?php } ?>	

<script type="text/javascript">

var NewSession = null;
var oe = null;
var ba = null;
function LoginFromToken(){				
				
		
		var identityToken = document.getElementById("USER_AUTH_TOKEN").value;
		
		if (NewSession === null){
			NewSession = new Session(ResponseSuccess, ResponseError);				
		}
				
		NewSession.LoginFromToken("OutboundEngine", identityToken);
		
		}

function ResponseError(response) {
	alert("ResponseError: " + response.code + "\r\n" + response.message + "\r\n Internal Code: " + response.internalCode);
}

function ResponseSuccess(response) {
	/*if (response.func === "MAKECALL"){
			var objid = response.data;
			document.forms.formLogin.elements.IdCall.value = objid.interactionId;
	}
	if (response.func === "GET_INTERACTIONID"){
			var objid = response.data;
			document.forms.formLogin.elements.IdCall.value = objid.interactionId;
	}
	else {*/
		alert("ResponseSuccess: " + response.code + "\r\n" + response.message + "\r\n Internal Code: " + response.internalCode);
	//}
		
}
</script>

</head>
<?php 
		if($_SESSION['nivel']=="1" or $_SESSION['nivel']=="5" or $_SESSION['nivel']=="7" or $_SESSION['nivel']=="4"){
            echo "<body onLoad=\"DynarchMenu.setup('menu');\" topmargin=\"0\" rightmargin=\"0\" bottommargin=\"0\" leftmargin=\"0\" onunloads=\"abreventana()\">";
		//echo "<body onLoad=\"\"topmargin=\"0\" rightmargin=\"0\" bottommargin=\"0\" leftmargin=\"0\" onunloads=\"abreventana()\">";

        }else if($_SESSION['nivel']=="2"){
            echo "<body onLoad=\"\"topmargin=\"0\" rightmargin=\"0\" bottommargin=\"0\" leftmargin=\"0\" onunloads=\"abreventana()\">";
        }
?>
<input type="hidden" id="usrP" value="<?php echo $usr ;?>"/>
<?php
	if(isset($_GET['idllam']) and $_GET['idllam']!=""){
		$_SESSION['idllam']=$_GET['idllam'];
		$idll=$_GET['idllam'];
	}else{
		$idll=$_SESSION['idllam'];
	}

	$id_user_pre_o=$_SESSION['iduser'];
	
	/*if(isset($_SESSION['user_pre'])){
		
		$id_user_pre_o=$_SESSION['user_pre'];
	}*/
	

?>
<!--<input type="button" value="LoginIC" onclick="LoginFromToken()"/>-->
<input id="id_user_p" type="text" style="visibility:hidden;position:absolute;" value="<?php echo $id_user_pre_o;?>"/>
<input id="idllam" type="text" style="visibility:hidden;position:absolute;" value="<?php echo $idll; ?>"/> 
<input id="ws" type="text" style="visibility:hidden;position:absolute;" value="'<?php echo $_SESSION['web'];?>'"/>
<input id="idcliente" type="text" style="visibility:hidden;position:absolute;" value='<?php echo $_GET['idCl'];?>'/>
<input id="id_pre_ta" type="text" style="visibility:hidden;position:absolute;" value='<?php if(isset($_GET['editid2'])){echo $_GET['editid2'];}?>'/>

<input id="USER_AUTH_TOKEN_APPLICATION" type="text" style="visibility:hidden;position:absolute;" value='<?php echo $_GET['USER_AUTH_TOKEN_APPLICATION'];?>'/>
<input id="ID_PROCESO" type="text" style="visibility:hidden;position:absolute;" value='<?php echo $_GET['IDPROCESO'];?>'/>
<input id="CAMPAIGNID" type="text" style="visibility:hidden;position:absolute;" value='<?php echo $_GET['CAMPAIGNID'];?>'/>
<input id="ADDRESS" type="text" style="visibility:hidden;position:absolute;" value='<?php echo $_GET['ADDRESS'];?>'/>
<input id="ACTORID" type="text" style="visibility:hidden;position:absolute;" value='<?php echo $_GET['ACTORID'];?>'/>
<input id="INTERACTIONID" type="text" style="visibility:hidden;position:absolute;" value='<?php echo $_GET['INTERACTIONID'];?>'/>
<input id="USER_AUTH_TOKEN" type="text" style="visibility:hidden;position:absolute;" value='<?php echo $_GET['USER_AUTH_TOKEN'];?>'/>
<input id="BATCHID" type="text" style="visibility:hidden;position:absolute;" value='<?php echo $_GET['BATCH_ID'];?>'/>
<input id="CONTACT_ID" type="text" style="visibility:hidden;position:absolute;" value='<?php echo $_GET['CONTACT_ID'];?>'/>


<div id="ykMain" >
	<div id="ykHead">
		<div id="bgHead">
			<!--<div id="logo_sys"></div>-->
			<!-- Administrador-->
			<?php 	if($_SESSION['nivel']=="1" or $_SESSION['nivel']=="6" or $_SESSION['nivel']=="7"){
                            include 'includes/menu_admin.php';
					}
            ?>
			<!-- Fin Administrador-->
			
			<!-- Analista-->
			<?php 	if($_SESSION['nivel']=="5" ){
                            include 'includes/menu_analista.php';
					}
            ?>
			<!-- Fin Analista-->
			
			<!-- Analista-->
			<?php 	if($_SESSION['nivel']=="4" ){
                            include 'includes/menu_externo.php';
					}
            ?>
			<!-- Fin Analista-->
			
			<!-- Negociador -->
			<?php 	if($_SESSION['nivel']=="2" or $_SESSION['nivel']=="3" ){
						if($_SESSION['nivel']=="3"){
							$_SESSION['campo']=1;
						}
						include 'includes/menu_neg.php';
                    }
            ?>
			<!-- Fin Negociador -->
		</div>
	</div>

<div id="ykBody" >

				<?php
					if(isset($_GET['tipo'])){
						include 'zonificacion.php';
					}
				?>
                         <?php 
						if($_SESSION['nivel']=="2"){
                           if(isset($_GET['gestion'])){
							$_SESSION['Call']=1;
                            include 'includes/datos_new.php';
                           }else{
							$_SESSION['Call']=1;
                            include 'includes/datos_n.php';
                           }
                        }
						
						if($_SESSION['nivel']=="3"){
                           if(isset($_GET['gestion'])){
							$_SESSION['campo']=1;
                            include 'includes/datos_new.php';
                           }else{
							$_SESSION['campo']=1;
                            include 'includes/datos_n.php';
                           }
                        }
						
						if($_SESSION['nivel']=="1" or $_SESSION['nivel']=="5" or $_SESSION['nivel']=="7" or $_SESSION['nivel']=="4"){
							$_SESSION['admin']=1;
                           if(isset($_GET['gestion'])){
							include 'includes/datos_n.php';
                            }else  if(isset($_GET['gestion_c'])){
                            include 'includes/datos_new.php';
                           }
                        }
						
						$db->Close();
						if($db2){
							$db2->Close();
						}
						$_SESSION['web']="";
                        ?>
			<!-- No Borrar(Alerta de Tareas) -->
			<div id="d_tarea">
			
			</div>
			<button style="visibility:hidden;" id="opener">Visualizar</button>
			<button style="visibility:hidden;" id="active">Abrir</button>
			<button style="visibility:hidden;" id="close" >Cerrar</button>
			<!-- No Borrar -->
	</div>
        <center>
            <div id="ykBody2" style=" ">
            
            </div>
        </center>

        <!-- /ykBody -->

		<script type="text/javascript">
			Zapatec.Form.setupAll ({
				showErrors: "afterField",
				showErrorsOnSubmit: true
			});
		</script>

</div>

</body>
</html>