<?php 
//este upload no usa ajax solo iframe + JS 
ini_set('memory_limit', '-1');
set_time_limit(1800);
error_reporting(E_ALL ^ E_NOTICE);
if (isset($_POST['name']))
{	//$directorio = 'uploads/';
	$directorio = 'script_imp/';
    $file_name = $_POST['name']; //con este nombre identificams a nuestra archivo file que viene en $_FILES
    $file_temp = $_FILES[$file_name]['tmp_name']; //archivo temporal
	$uploadfile = $directorio . basename($_FILES[$file_name]['name']);
	$archivo = $_FILES[$file_name]['name']; //moveoms el archivo
    if (move_uploaded_file($file_temp,$uploadfile))
    {	
        //$file_msg = "archivo  <strong><a onclick=select_name('$archivo');>$archivo.$file_name.</a></strong> subido con exito al servidor";
		$file_msg = "<input id='nombre' type='button' onclick=select_name('$archivo'); value='$archivo'/>";
    } else {
        $file_msg = "<font size='3'>Error, archivo  <strong style='color:red;'>$archivo</strong> no fue cargado<br/>(<font color='blue' size='2'>Posiblemente el archivo se este ejecutando &oacute; este fallado</font>)</font>";
    }
?>
<script type="text/javascript">
    //nombre del campo donde vamos a bloquear los contenidos, en este caso es name_id
    parent.document.getElementById('resultado').innerHTML = "<?php echo $file_msg;  ?>";
	parent.document.getElementById('nombre').click();
    //alert("<?php echo $file_msg;  ?> ");
</script>

<?php
} else {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Importar</title>
<script type="text/javascript">
function upload(objeto)
{
document.getElementById('resultado').innerHTML="<img src='imagen/cargando.gif' alt=''/>"
objeto.target = "iframe";     //le agregamos el nombre del iframe, por donde enviara el form
objeto.submit();    //enviamos el form con totod los contenidos
}
</script>
<script >
	function select_name(dato) {
		opener.document.getElementById('seleccion').value = dato;
		opener.document.getElementById('importar').value = "Importar Archivo";
		window.close();
	}
</script>


</head>
<body>
<div id="name_id" style="margin:10px;" align="center">
  <form id="form1" name="form1" enctype="multipart/form-data" method="post" action="" onsubmit="upload(this); return false;" >
    <input type="file" name="file" /> 
    <input name="name" type="hidden" value="file" />
    <input name="" type="submit" value="Subir Archivo" />
  </form>
<iframe  style="display:none" name="iframe" src="importar.php" width="400" height="100"></iframe>
</div>
<div id="resultado" align="center">

</div>

</body>
</html>
<?php 
} 
?> 