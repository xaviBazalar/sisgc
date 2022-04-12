<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Documento sin título</title>
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
	
	function relojW()
	{
		ajax=Ajax();
			ajax.open("GET", "hora.php",true);
			ajax.onreadystatechange=
				function() {
					if (ajax.readyState==4) {
							document.getElementById('hora_a').innerHTML  = ajax.responseText
					}
				}
		ajax.send(null)

		setTimeout('relojW()',1000)
	}
</script>
</head>

<body onload="relojW();" style="padding;0px;border:0px;">
	<div id="hora_a" style="position:absolute;top:1px;left:1px;color:white;"><?php echo date("H:i:s");?></div>
</body>
</html>
