<?php
//return false;
session_start();
ini_set('memory_limit', '-1');
set_time_limit(1800);
echo "<pre style='font-size:11px;'>";
require_once 'Excel/reader.php';
include 'ConnecADO.php';
$name=$_GET['archivo'];
$id_periodo=$_SESSION['periodo'];
$idcartera=$_GET['idcartera'];
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
$data->read($name);

foreach($data->sheets as $x => $y){//recorrido de las pestañas del archivo excel
            
	$name= strtolower($data->boundsheets[$x]['name']);
     //echo $data->boundsheets[$x]['name']."<br/><br/>";//nombre de las pestañas
	// $db->Execute("UPDATE cuentas c, cuenta_periodos cp SET cp.idestado=0,cp.usureg='22' WHERE c.idcuenta=cp.idcuenta AND cp.idestado=1 AND cp.idperiodo=$id_periodo AND c.idcartera=51");
 	//sleep(60);
	$s=1;
	$l=1;
	$gestiones=array();
	$flag_gestiones=array(); 
	$update_gestiones=array();
	$fecha_g=date("Y-m-d");
	$hora_g=date("H:i:s");
    for ($i = 2,$h=1; $i <= $data->sheets[$x]['numRows']; $i++,$h++) {// defino $i=2 para q no tome la primera fila q es la cabecera
			    
                for ($j = 1; $j <= $data->sheets[$x]['numCols']; $j++) {
					if($j==$data->sheets[$x]['numCols']){
						if($data->sheets[$x]['cells'][$i][1]==""){continue;}
								/*
								Promesa de Pago
								Ya Pago
								Renuente a pagar
								Reclamo Cliente
								Contacto tercero / Deja notificacion
								Falleci
								Se mudo**
								Inubicable / Notificacion bajo puerta
								Titular desconocido***
								Direccion errada / incompleta
								Zona fuera de cobertura
								Zona peligrosa*/

								//$flag_gestiones[0][$s]="select idcuenta from cuentas where idcuenta='".$data->sheets[$x]['cells'][$i][1]."-1'  and idcliente='".$data->sheets[$x]['cells'][$i][5]."'  ";
								//$update_gestiones[0][$s]="update cuentas sezt idcliente='".$data->sheets[$x]['cells'][$i][5]."',obs2='".$data->sheets[$x]['cells'][$i][7]."*".$data->sheets[$x]['cells'][$i][8]."*".$data->sheets[$x]['cells'][$i][9]."', idproducto=522,idtipocartera=$tipo_car where idcuenta='".$data->sheets[$x]['cells'][$i][1]."-1'";
								$rs="";
								$resg=trim(strtolower($data->sheets[$x]['cells'][$i][3]));

								$resg=str_replace("á","a",$resg);
								$resg=str_replace("é","e",$resg);
								$resg=str_replace("í","i",$resg);
								$resg=str_replace("ó","o",$resg);
								$resg=str_replace("ú","u",$resg);
								
								if($resg=="promesa de pago"){ $rs="248";}
								if($resg=="ya pago"){$rs="351";}
								if($resg=="renuente a pagar"){$rs="341";}
								if($resg=="reclamo cliente"){$rs="340";}
								if($resg=="contacto tercero / deja notificacion"){$rs="314";}
								if($resg=="fallecio titular"){$rs="320";}
								if($resg=="se mudo" or $resg=="se mudo**"){$rs="328";}
								if($resg=="inubicable / notificacion bajo puerta"){$rs="323";}
								if($resg=="titular desconocido***" or $resg=="titular desconocido"){$rs="347";}
								if($resg=="direccion errada / incompleta"){$rs="318";}
								if($resg=="zona fuera de cobertura"){$rs="352";}
								if($resg=="zona peligrosa"){$rs="353";}
								
								$flag_gestiones[0][$s]="select idgestion from gestiones where idcuenta='".trim($data->sheets[$x]['cells'][$i][1])."-1' and idactividad=13 and fecges='$fecha_g'";
								$update_gestiones[0][$s]="update gestiones set idresultado='$rs',fecges='$fecha_g',horges='$hora_g' where idcuenta='".trim($data->sheets[$x]['cells'][$i][1])."-1' and idactividad=13 and fecges=$fecha_g";
								$gestiones[$s]="INSERT INTO gestiones (idactividad,idcontactabilidad,fecges,horges,idcuenta,idresultado)
									values (13,32,'$fecha_g','$hora_g','".trim($data->sheets[$x]['cells'][$i][1])."-1','$rs');
									";
								++$s;	
					}
				}

								
	}

				$ok=true;
				$t=1;
				$ist=0;$ups=0;$err=0;
				$db->StartTrans();
						for($i=1;$i<=count($gestiones);$i++){
							$flag=$db->Execute($flag_gestiones[0][$i]);
							if($flag->fields['idgestion']==""){
								++$ist;
								$ok=$db->Execute($gestiones[$i]);
								if($ok == false){
									$db->CompleteTrans(false);
									return false;
								}
							}else{
								++$ups;
								$ok=$db->Execute($update_gestiones[0][$i]);
								if($ok == false){
									$db->CompleteTrans(false);
									return false;
								}
							}
						}
					
				$db->CompleteTrans($ok);
				echo "<b style='font-size:14px;'><center>Gestiones </center></br> &nbsp;&nbsp;Ingresados ( $ist )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) </b><br/><hr/>";
				
	
}






?>
