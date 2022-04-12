<?php

 
echo "<pre>";
include 'ConnecADO.php';
$name=$_GET['archivo'];
//return false;
  ini_set('memory_limit', '-1');
  set_time_limit(3600);
  $fp= fopen ($name, "r");
  $n=0;
  $s=1;
	$cuentas_up=array();
	$cuentas_p_up=array();
	$flag_cta=array();
	
	while (!feof($fp)){
		++$n;
		$linea=fgets($fp);
		$datos=explode("\t",$linea);
		
		if($n>1){
			if($datos[1]==""){break;}
			if($datos[0]=="T"){
				$flag="select idcuenta from cuentas where idcuenta='".$datos[1]."-1'";
				$sql_c="UPDATE cuenta_periodos set diasmora='".$datos[2]."',impmor='".$datos[5]."' where idcuenta='".$datos[1]."-1' and idperiodo=22";
				
				$flag_cta[$s]=$flag;
				$cuentas_up[$s]=$sql;
				$cuentas_p_up[$s]=$sql_c;
				$s++;
			}
		}
	}

	$ok=true;
	//$db->debug=true;
	$ist=0;$ups=0;$err=0;
	$db->StartTrans();
						for($i=1;$i<=count($cuentas_up);$i++){
							$flag=$db->Execute($flag_cta[$i]);
							if($flag->fields['idcuenta']!=""){
								++$ups;
								$ok=$db->Execute($cuentas_up[$i]);
								$ok=$db->Execute($cuentas_p_up[$i]);

								if($ok == false){
									$db->CompleteTrans(false);
									return false;
								}
							}else{
								++$err;
							}
						}
					
	$db->CompleteTrans($ok);
	echo "Cuentas Moras| &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; No existentes ( $err ) <br/>";

	fclose($fp);
?>