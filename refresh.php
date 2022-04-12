<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de cobranza - Gestion</title>
<?php 
	if($_GET['temp']=="si"){
		$temp="&temp=false";
	}else{
		$temp="";
	}

?>
<meta http-equiv="refresh" content="1; URL=index.php?gestion=1&idCl=<?php echo $_GET['idCl'].$temp;?>"/>
</head>
<body>
</body>
</html>
