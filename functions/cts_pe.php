<?php
/*session_start();
if (!isset($_SESSION["user"]) || $_SESSION["user"] == "") {
	header("Location: login.php");
}*/
include '../class/adodb_lite/adodb.inc.php';
$db->debug=true;
$db=  &ADONewConnection('mysql');
//$db->debug= true;
$db->Connect("localhost", "root", "g3st1onkb", "intranet");
	$sql= $db->Execute("SELECT ui.idusuario,ui.usuario,ui.dni,us.idusuario,us.login FROM intranet.usuarios  ui
JOIN sis_gestion.usuarios us ON ui.dni=us.documento");
	$no= array();
	$n=1;
	while(!$sql->EOF){
		//$cta=$sql->fields['idcuenta'];
		$no[$n]=$sql->fields['dni'];
		//$db->Execute("DELETE FROM cuenta_periodos  WHERE idcuenta='$cta'");
		$sql->MoveNext();
		$n++;
	}


	$sql= $db->Execute("SELECT usuario,pass,paterno,materno,nombres,dni,fecnac,direccion,telefono
						,email,fecing,idnivel,idestado,idturno,idproveedor,idempresa,idmodalidad 
						FROM intranet.usuarios");
	echo "<pre>";
	while(!$sql->EOF){
		//$cta=$sql->fields['idcuenta'];
		if(in_array($sql->fields['dni'],$no)){
		$sql->MoveNext();
		continue;
		}
		$sql2="Insert  into sis_gestion.usuarios (`idproveedor`,`iddoi`,`idcartera`,`idnivel`,`usuario`,`documento`,`fechanacimiento`,
												`email`,`direccion`,`coddpto`,`codprov`,`coddist`,`telefonos`,`fechaingreso`,
												`login`,`clave`,`idmodalidad`,`idturno`,`idempresa`,`idestado`)
												VALUES (";
		
		if($sql->fields['idnivel']==2 or $sql->fields['idnivel']==7){
			$idnivel=5;
		}
		if($sql->fields['idnivel']==3 ){
			$idnivel=2;
		}
		if($sql->fields['idnivel']==6 ){
			$idnivel=8;
		}
		if($sql->fields['idnivel']==4 ){
			$idnivel=7;
		}
		if($sql->fields['idnivel']==5 ){
			$idnivel=6;
		}
		if($sql->fields['idestado']==3)
		{
			$estado=1;
		}else{
			$estado=0;
		}
		$sql2.="'3','1','4','".$idnivel."','".$sql->fields['paterno']." ".$sql->fields['materno']." ".$sql->fields['nombres']."','".$sql->fields['dni']."','".$sql->fields['fecnac']."','".$sql->fields['email']."',";
		$sql2.="'".$sql->fields['direccion']."','15','01','01','".$sql->fields['telefono']."','".$sql->fields['fecing']."','".$sql->fields['usuario']."','".$sql->fields['pass']."','";
		$sql2.=$sql->fields['idmodalidad']."','".$sql->fields['idturno']."','".$sql->fields['idempresa']."','".$estado."')";
		$db->Execute($sql2);
		
		//echo $sql2."<br/>";
		$sql->MoveNext();
	}
?>
