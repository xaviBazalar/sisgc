<?php
//return false;


ini_set('memory_limit', '-1');
set_time_limit(1800);
 
include '../scripts/conexion.php';

echo "<pre>";

//$db->debug=true;

$hora=date("H:i:s");
$archivo='predictivo'.$hora.'.txt';
$fp = fopen($archivo, 'w');

if($_GET['id_cam']!=""){
	$idc=$_GET['id_cam'];
	$cont=$_GET['cont'];
	$sql="SELECT * FROM ori_base WHERE contact='$cont' and id_ori_campana='$idc'";

}
		

	/*echo $sql;
	return false;*/

	$rp=$db->Execute($sql);
	$titulo="Ident.usuario|Ident.cliente|F.Accion|Accion|Respuesta|Contacto|Observacion|F.Prox.G|Telefono|Indicativo|Tip|T.Act/Desact|Ind.T.Act/Desac|Tip.T.Act/Desac|Direccion|Ciudad direccion|Zona direccion|Tipo direccion|Sec.direccion|Id.destinatario|Codigo carta|Entidad|Producto|Obligacion|Ent.de obligac|Monto promesa|Fecha|S|Num.cheque|Ent.cheque|Def.usuario|Visitador|";
	fwrite($fp, $titulo);
	fwrite($fp , chr(13).chr(10));	
	while(!$rp->EOF){
		$ent_obli=explode("/",$rp->fields['observacion2']);
		if($ent_obli[1]=="FC"){ $ent_obli="FINANCIEROS";}
		if($ent_obli[1]=="TC"){ $ent_obli="TARJCOMPARTIDAS";}
		if($ent_obli[1]=="TP"){ $ent_obli="TARJPROPIAS";}
		if($ent_obli[1]=="BR"){ $ent_obli="BANCORIPLEY";}
		$datos=trim($rp->fields['observacion']);
		$arr = explode("-",$datos);
		$id=$arr[0];
		$hour=explode(" ",$rp->fields['hora']);
		if($hour[1]=="AM"){ $e="a.m.";}
		if($hour[1]=="PM"){ $e="p.m.";}
		$fechs=explode("-",$rp->fields['fecha']);
		$fechs=$fechs[2]."/".$fechs[1]."/".$fechs[0];
		$fecr=$fechs." ".$hour[0]." ".$e;
		$acc=$rp->fields['accion'];
		$rpta=$rp->fields['respuesta'];
		$cont=$rp->fields['contacto'];
		
		$obs=$rp->fields['g_observacion'];
		if(strlen($obs)>=200){
			$maxlength = 198;
			$obs = substr($obs, 0, $maxlength);
		}
		
		$f_pg=$rp->fields['fec_comp'];
		if($pos = strpos($f_pg, "-")){
			$f_pg=explode("-",$rp->fields['fec_comp']);
			$f_pg=$f_pg[2]."/".$f_pg[1]."/".$f_pg[0];
		}
		//$f_pg=$rp->fields['fec_comp'];
		$tel_g=$rp->fields['telefono'];
		$tel_g=trim($rp->fields['telefono']);
		
		if($pos = strpos($tel_g, "-")){$tel_g=explode("(",$tel_g);  $tel_g=$tel_g[0].$tel_g[1].$tel_g[2];	}
		if($pos = strpos($tel_g, "-")){$tel_g=explode(")",$tel_g);  $tel_g=$tel_g[0].$tel_g[1];	}
		if($pos = strpos($tel_g, "-")){$tel_g=explode("-",$tel_g);  $tel_g=$tel_g[0].$tel_g[1];	}
		if($pos = strpos($tel_g, ".")){$tel_g=explode(".",$tel_g);  $tel_g=$tel_g[0].$tel_g[1]; }
		if($pos = strpos($tel_g, ",")){$tel_g=explode(",",$tel_g);  $tel_g=$tel_g[0].$tel_g[1]; }
		if($pos = strpos($tel_g, "*")){$tel_g=explode("*",$tel_g);  $tel_g=$tel_g[0].$tel_g[1]; }
			$patron = "/^[[:digit:]]+$/";/*solo numeros*/
           // if (preg_match($patron, $tel_g)){}else{ continue;}
		$tel_t=$rp->fields['tipo_tel'];
		$entidad=$rp->fields['proveedor'];
		$producto=$rp->fields['producto'];
		$producto=trim($rp->fields['producto']);
		if(strlen($producto)>=20){
			$maxlength = 19;
			$producto = substr($producto, 0, $maxlength);
		}
		$obli=trim($arr[1]);
		$fec_p=$rp->fields['fec_comp'];
		$imp_p=$rp->fields['imp_comp'];
		
		if($id==""){
			$rp->MoveNext();
			continue;
		}
		if($acc=="RECEIVE CALL" and $rpta==""){
			$rp->MoveNext();
			continue;
		}
		
		if($acc=="MAKE CALL" and $rpta==""){
			$rp->MoveNext();
			continue;
		}
		
		$linea="Kobsa|$id|$fecr|$acc|$rpta|$cont|$obs|$f_pg|$tel_g|1|$tel_t|||||||||||BANCORIPLEY|$producto|$obli|$ent_obli|||N||||KOBSA";
		fwrite($fp, $linea);
		fwrite($fp , chr(13).chr(10));
		$rp->MoveNext();
		
	}	


mysql_free_result($rp->_queryID);	


$db->Close();
echo "</br></br></br>";
echo "<font color='red'>Inicio de reporte :</font>".$ti=date("H:i:s")." - <font color='blue'>Fin de Reporte:</font>".$tf=date("H:i:s")." - ";
echo "Guardar:<a href='functions/guardar_como.php?name=$archivo' target='blank'>$archivo</a>";	


?>
