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
	$direcciones= array();
	$clientes=array();
	$telefonos=array();
	$cuentas=array();
	$cuenta_p=array();
	$flag_cta=array(); 
	$update_cta=array();
    for ($i = 2,$h=1; $i <= $data->sheets[$x]['numRows']; $i++,$h++) {// defino $i=2 para q no tome la primera fila q es la cabecera
			    
                for ($j = 1; $j <= $data->sheets[$x]['numCols']; $j++) {
						//if($j>=44){break;}
					if($j==$data->sheets[$x]['numCols']){
						if($data->sheets[$x]['cells'][$i][5]==""){continue;}

					//	if(strlen(trim($data->sheets[$x]['cells'][$i][2]))==9){
						$flag_cta[0][$s]="select idcliente from clientes where idcliente='".$data->sheets[$x]['cells'][$i][5]."'";
						$update_cta[0][$s]="update clientes set cliente='".addslashes($data->sheets[$x]['cells'][$i][6])."' where idcliente='".$data->sheets[$x]['cells'][$i][5]."'";

						$clientes[$s]="INSERT INTO clientes (iddoi,idpersoneria,idcliente,cliente,observacion)
						values(1,1,'".$data->sheets[$x]['cells'][$i][5]."','".addslashes(trim($data->sheets[$x]['cells'][$i][6]))."','".$data->sheets[$x]['cells'][$i][1]."-".$data->sheets[$x]['cells'][$i][3]."-".$data->sheets[$x]['cells'][$i][4]."')";
						
						if(strlen(trim($data->sheets[$x]['cells'][$i][28]))==5){
							$dir=(string) '0'.substr($data->sheets[$x]['cells'][$i][28],0,1);
						}else{
							$dir=(string) substr($data->sheets[$x]['cells'][$i][28],0,2);
						}
						//$db->debug=true;

						if($data->sheets[$x]['cells'][$i][13]!="" and $data->sheets[$x]['cells'][$i][12]!=""){
						
						$id_dir=$db->Execute("Select coddpto,codprov,coddist from ubigeos where nombre='".$data->sheets[$x]['cells'][$i][13]."' and nombre2='".$data->sheets[$x]['cells'][$i][14]."' and nombre3='".$data->sheets[$x]['cells'][$i][15]."' ");
						$coddpto=$id_dir->fields['coddpto'];
						$codprov=$id_dir->fields['codprov'];
						$coddist=$id_dir->fields['coddist'];

						
						if(strlen(trim($data->sheets[$x]['cells'][$i][12]))==5){
							$flag_cta[1][$s]="select idcliente from direcciones where idcliente='".$data->sheets[$x]['cells'][$i][5]."' and direccion='".$data->sheets[$x]['cells'][$i][12]."'";
							$update_cta[1][$s]="update direcciones set coddpto='$dir',codprov='".substr($data->sheets[$x]['cells'][$i][28],1,2)."',coddist='".substr($data->sheets[$x]['cells'][$i][28],3,2)."',observacion='".addslashes($data->sheets[$x]['cells'][$i][24])."*".addslashes($data->sheets[$x]['cells'][$i][25])."*".$data->sheets[$x]['cells'][$i][26]."' where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and direccion='".$data->sheets[$x]['cells'][$i][20]."'";
							$direcciones[$s]="INSERT INTO direcciones (idfuente,idcuadrante,idorigendireccion,idcliente,direccion,coddpto,codprov,coddist,observacion)
							values (1,1,1,'".$data->sheets[$x]['cells'][$i][2]."','".addslashes($data->sheets[$x]['cells'][$i][20])."',
							'0".substr($data->sheets[$x]['cells'][$i][28],0,1)."','".substr($data->sheets[$x]['cells'][$i][28],1,2)."','".substr($data->sheets[$x]['cells'][$i][28],3,2)."','".addslashes($data->sheets[$x]['cells'][$i][24])."*".addslashes($data->sheets[$x]['cells'][$i][25])."*".addslashes($data->sheets[$x]['cells'][$i][26])."')
							";
						}else{
							$flag_cta[1][$s]="select idcliente from direcciones where idcliente='".$data->sheets[$x]['cells'][$i][5]."' and direccion='".addslashes(utf8_encode($data->sheets[$x]['cells'][$i][12]))."'";
							$update_cta[1][$s]="update direcciones set coddpto='$dir',codprov='".substr($data->sheets[$x]['cells'][$i][28],2,2)."',coddist='".substr($data->sheets[$x]['cells'][$i][28],4,2)."',observacion='".addslashes($data->sheets[$x]['cells'][$i][24])."*".addslashes($data->sheets[$x]['cells'][$i][25])."*".$data->sheets[$x]['cells'][$i][26]."' where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and direccion='".addslashes(utf8_encode($data->sheets[$x]['cells'][$i][20]))."'";
							$direcciones[$s]="INSERT INTO direcciones (idfuente,idcuadrante,idorigendireccion,idcliente,direccion,coddpto,codprov,coddist,observacion)
							values (1,1,1,'".$data->sheets[$x]['cells'][$i][5]."','".addslashes(utf8_encode($data->sheets[$x]['cells'][$i][12]))."','$coddpto','$codprov','$coddist',
							'".addslashes($data->sheets[$x]['cells'][$i][20])."*".addslashes($data->sheets[$x]['cells'][$i][21])."*".addslashes($data->sheets[$x]['cells'][$i][22])."')
							";
						
						}
						
						}
						
						if($i>1){
							if($data->sheets[$x]['cells'][$i][23]!="" and $data->sheets[$x]['cells'][$i][23]!="NULL"){
							
								if(substr($data->sheets[$x]['cells'][$i][23],0,1)==9){$oriT=5;}else{$oriT=2;}
								$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$data->sheets[$x]['cells'][$i][5]."' and telefono='".$data->sheets[$x]['cells'][$i][23]."'";
								$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT where idcliente='".$data->sheets[$x]['cells'][$i][5]."' and telefono='".$data->sheets[$x]['cells'][$i][23]."'";
								$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
								values (1,$oriT,'".$data->sheets[$x]['cells'][$i][5]."','".$data->sheets[$x]['cells'][$i][23]."','')";
								++$l;
							}
							
							if($data->sheets[$x]['cells'][$i][24]!="" and $data->sheets[$x]['cells'][$i][24]!="NULL"){
							
								if(substr($data->sheets[$x]['cells'][$i][24],0,1)==9){$oriT=5;}else{$oriT=2;}
								$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$data->sheets[$x]['cells'][$i][5]."' and telefono='".$data->sheets[$x]['cells'][$i][24]."'";
								$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT where idcliente='".$data->sheets[$x]['cells'][$i][5]."' and telefono='".$data->sheets[$x]['cells'][$i][24]."'";
								$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
								values (1,$oriT,'".$data->sheets[$x]['cells'][$i][5]."','".$data->sheets[$x]['cells'][$i][24]."','')";
								++$l;
							}
							
							if($data->sheets[$x]['cells'][$i][25]!="" and $data->sheets[$x]['cells'][$i][25]!="NULL"){
							
								if(substr($data->sheets[$x]['cells'][$i][25],0,1)==9){$oriT=5;}else{$oriT=2;}
								$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$data->sheets[$x]['cells'][$i][5]."' and telefono='".$data->sheets[$x]['cells'][$i][25]."'";
								$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT where idcliente='".$data->sheets[$x]['cells'][$i][5]."' and telefono='".$data->sheets[$x]['cells'][$i][25]."'";
								$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
								values (1,$oriT,'".$data->sheets[$x]['cells'][$i][5]."','".$data->sheets[$x]['cells'][$i][25]."','')";
								++$l;
							}
							
							if($data->sheets[$x]['cells'][$i][26]!="" and $data->sheets[$x]['cells'][$i][26]!="NULL"){
							
								if(substr($data->sheets[$x]['cells'][$i][26],0,1)==9){$oriT=5;}else{$oriT=2;}
								$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$data->sheets[$x]['cells'][$i][5]."' and telefono='".$data->sheets[$x]['cells'][$i][26]."'";
								$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT where idcliente='".$data->sheets[$x]['cells'][$i][5]."' and telefono='".$data->sheets[$x]['cells'][$i][26]."'";
								$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
								values (1,$oriT,'".$data->sheets[$x]['cells'][$i][5]."','".$data->sheets[$x]['cells'][$i][26]."','')";
								++$l;
							}
							
							if($data->sheets[$x]['cells'][$i][27]!="" and $data->sheets[$x]['cells'][$i][27]!="NULL"){
							
								if(substr($data->sheets[$x]['cells'][$i][27],0,1)==9){$oriT=5;}else{$oriT=2;}
								$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$data->sheets[$x]['cells'][$i][5]."' and telefono='".$data->sheets[$x]['cells'][$i][27]."'";
								$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT where idcliente='".$data->sheets[$x]['cells'][$i][5]."' and telefono='".$data->sheets[$x]['cells'][$i][27]."'";
								$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
								values (1,$oriT,'".$data->sheets[$x]['cells'][$i][5]."','".$data->sheets[$x]['cells'][$i][27]."','')";
								++$l;
							}
							
							if($data->sheets[$x]['cells'][$i][28]!="" and $data->sheets[$x]['cells'][$i][28]!="NULL"){
							
								if(substr($data->sheets[$x]['cells'][$i][28],0,1)==9){$oriT=5;}else{$oriT=2;}
								$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$data->sheets[$x]['cells'][$i][5]."' and telefono='".$data->sheets[$x]['cells'][$i][28]."'";
								$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT where idcliente='".$data->sheets[$x]['cells'][$i][5]."' and telefono='".$data->sheets[$x]['cells'][$i][28]."'";
								$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
								values (1,$oriT,'".$data->sheets[$x]['cells'][$i][5]."','".$data->sheets[$x]['cells'][$i][28]."','')";
								++$l;
							}
							
							if($data->sheets[$x]['cells'][$i][29]!="" and $data->sheets[$x]['cells'][$i][29]!="NULL"){
							
								if(substr($data->sheets[$x]['cells'][$i][29],0,1)==9){$oriT=5;}else{$oriT=2;}
								$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$data->sheets[$x]['cells'][$i][5]."' and telefono='".$data->sheets[$x]['cells'][$i][29]."'";
								$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT where idcliente='".$data->sheets[$x]['cells'][$i][5]."' and telefono='".$data->sheets[$x]['cells'][$i][29]."'";
								$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
								values (1,$oriT,'".$data->sheets[$x]['cells'][$i][5]."','".$data->sheets[$x]['cells'][$i][29]."','')";
								++$l;
							}
							
							if($data->sheets[$x]['cells'][$i][30]!="" and $data->sheets[$x]['cells'][$i][30]!="NULL"){
							
								if(substr($data->sheets[$x]['cells'][$i][30],0,1)==9){$oriT=5;}else{$oriT=2;}
								$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$data->sheets[$x]['cells'][$i][5]."' and telefono='".$data->sheets[$x]['cells'][$i][30]."'";
								$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT where idcliente='".$data->sheets[$x]['cells'][$i][5]."' and telefono='".$data->sheets[$x]['cells'][$i][30]."'";
								$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
								values (1,$oriT,'".$data->sheets[$x]['cells'][$i][5]."','".$data->sheets[$x]['cells'][$i][30]."','')";
								++$l;
							}
							
						
						}
							//if(strlen(trim($data->sheets[$x]['cells'][$i][3]))==7){

								$pos = strpos($data->sheets[$x]['cells'][$i][44], "/");

												if($pos){
													$fecs = explode("/",$data->sheets[$x]['cells'][$i][44]);
													$fecs = $fecs[2]."-".$fecs[1]."-".$fecs[0];
			
												}else{
													$fecs=$data->sheets[$x]['cells'][$i][44];

												}
								
								$sl_tipo_c="select idtipocartera from tipo_carteras where tipocartera='".$data->sheets[$x]['cells'][$i][3]."'";
								$d_sl=$db->Execute($sl_tipo_c);
								$tipo_car=$d_sl->fields['idtipocartera'];
								if($d_sl->fields['idtipocartera']==""){
									$tipo_c="insert tipo_carteras (tipocartera) values ('".$data->sheets[$x]['cells'][$i][3]."')";
									$db->Execute($tipo_c);
									$sl_tipo_c2="select idtipocartera from tipo_carteras where tipocartera='".$data->sheets[$x]['cells'][$i][3]."'";
									$d_sl2=$db->Execute($sl_tipo_c);
									$tipo_car=$d_sl2->fields['idtipocartera'];
								}
								$flag_cta[3][$s]="select idcuenta from cuentas where idcuenta='".$data->sheets[$x]['cells'][$i][1]."-1'  -- and idcliente='".$data->sheets[$x]['cells'][$i][5]."'  ";
								$update_cta[3][$s]="update cuentas set idcliente='".$data->sheets[$x]['cells'][$i][5]."',obs2='".$data->sheets[$x]['cells'][$i][2]."*".$data->sheets[$x]['cells'][$i][7]."*".$data->sheets[$x]['cells'][$i][8]."', idproducto=522,idtipocartera=$tipo_car where idcuenta='".$data->sheets[$x]['cells'][$i][1]."-1'";
								$cuentas[$s]="INSERT INTO cuentas (idtipocartera,idmoneda,idproducto,idcartera,idusuario,idcliente,idcuenta,obs2)
									values ('$tipo_car',1,522,$idcartera,2,'".$data->sheets[$x]['cells'][$i][5]."','".$data->sheets[$x]['cells'][$i][1]."-1','".$data->sheets[$x]['cells'][$i][2]."*".$data->sheets[$x]['cells'][$i][7]."*".$data->sheets[$x]['cells'][$i][8]."');
									";
								
								
								

								$sl="select idcuenta,idusuario from cuenta_periodos where idcuenta='".$data->sheets[$x]['cells'][$i][1]."-1' and idperiodo=".($id_periodo-1);
								$id_usureg=$db->Execute($sl);
								
								if($id_usureg->fields['idusuario']!=""){
									$idusu=$id_usureg->fields['idusuario'];
								}else{
									$idusu=2;
								}

								
								$fecven=substr($data->sheets[$x]['cells'][$i][6],0,4)."-".substr($data->sheets[$x]['cells'][$i][6],4,2)."-".substr($data->sheets[$x]['cells'][$i][6],6);
								$flag_cta[4][$s]="select idcuenta from cuenta_periodos where idcuenta='".$data->sheets[$x]['cells'][$i][1]."-1' and idperiodo=$id_periodo";
								$update_cta[4][$s]="update cuenta_periodos set usureg=$id_periodo,idestado=1,grupo='".$data->sheets[$x]['cells'][$i][16]."',impmin='".$data->sheets[$x]['cells'][$i][09]."',impven='".$data->sheets[$x]['cells'][$i][10]."',imptot='".$data->sheets[$x]['cells'][$i][11]."',observacion='".$data->sheets[$x]['cells'][$i][16]."*".$data->sheets[$x]['cells'][$i][17]."*".$data->sheets[$x]['cells'][$i][18]."*".$data->sheets[$x]['cells'][$i][19]."' where idcuenta='".$data->sheets[$x]['cells'][$i][1]."-1' and idperiodo=$id_periodo";
								$cuentas_p[$s]="INSERT INTO cuenta_periodos (usureg,idperiodo,idusuario,idcuenta,grupo,fecven,impmin,impven,imptot,observacion)
									values ($id_periodo,$id_periodo,$idusu,'".$data->sheets[$x]['cells'][$i][1]."-1','".$data->sheets[$x]['cells'][$i][16]."','".date("Y-m-d")."','".$data->sheets[$x]['cells'][$i][9]."','".$data->sheets[$x]['cells'][$i][10]."','".$data->sheets[$x]['cells'][$i][11]."','".$data->sheets[$x]['cells'][$i][16]."*".$data->sheets[$x]['cells'][$i][17]."*".$data->sheets[$x]['cells'][$i][18]."*".$data->sheets[$x]['cells'][$i][19]."');
									";
								
								$s++;
							//}
						//}
					}
				}
				
				//if($i==1) var_dump($dato);
								
	}
				// var_dump($direcciones);
			//	 return false;
				$ok=true;
				$t=1;
				//$db->debug=true;
				$ist=0;$ups=0;$err=0;
				$db->StartTrans();
						for($i=1;$i<=count($clientes);$i++){
							$flag=$db->Execute($flag_cta[0][$i]);
							if($flag->fields['idcliente']==""){
								++$ist;
								$ok=$db->Execute($clientes[$i]);
								if($ok == false){
									$db->CompleteTrans(false);
									return false;
								}
							}else{
								++$ups;
								$ok=$db->Execute($update_cta[0][$i]);
								if($ok == false){
									$db->CompleteTrans(false);
									return false;
								}
							}
						}
					
				$db->CompleteTrans($ok);
				echo "<b style='font-size:14px;'><center>Clientes </center></br> &nbsp;&nbsp;Ingresados ( $ist )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) </b><br/><hr/>";
				//$db->debug=true;
				$ist=0;$ups=0;$err=0;
				$db->StartTrans();
						for($i=1;$i<=count($direcciones);$i++){
							$flag=$db->Execute($flag_cta[1][$i]);
							if($flag->fields['idcliente']==""){
								++$ist;
								$ok=$db->Execute($direcciones[$i]);
								if($ok == false){
									$db->CompleteTrans(false);
									return false;
								}
							}else{
								++$ups;
								$ok=$db->Execute($update_cta[1][$i]);
								if($ok == false){
									$db->CompleteTrans(false);
									return false;
								}
							}
						}
					
				$db->CompleteTrans($ok);
				echo "<b style='font-size:14px;'><center>Direcciones </center></br> &nbsp;&nbsp;Ingresados ( $ist )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) </b><br/><hr/>";
				//$db->debug=false;
				//$db->debug=true;
				$ist=0;$ups=0;$err=0;
				$db->StartTrans();
						for($i=1;$i<=count($telefonos);$i++){
							$flag=$db->Execute($flag_cta[2][$i]);
							if($flag->fields['idcliente']==""){
								++$ist;
								$ok=$db->Execute($telefonos[$i]);
								if($ok == false){
									$db->CompleteTrans(false);
									return false;
								}
							}else{
								++$ups;
								$ok=$db->Execute($update_cta[2][$i]);
								if($ok == false){
									$db->CompleteTrans(false);
									return false;
								}
							}
						}
					
				$db->CompleteTrans($ok);
				echo "<b style='font-size:14px;'><center>Telefonos </center></br>  &nbsp;&nbsp;Ingresados ( $ist )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) </b><br/><hr/>";
				//$db->debug=false;
				//$db->debug=true;
				$ist=0;$ups=0;$err=0;
				$db->StartTrans();
						for($i=1;$i<=count($cuentas);$i++){
							$flag=$db->Execute($flag_cta[3][$i]);
							if($flag->fields['idcuenta']==""){
								++$ist;
								$ok=$db->Execute($cuentas[$i]);
								if($ok == false){
									$db->CompleteTrans(false);
									return false;
								}
							}else{
								++$ups;
								$ok=$db->Execute($update_cta[3][$i]);
								if($ok == false){
									$db->CompleteTrans(false);
									return false;
								}
							}
						}
					
				$db->CompleteTrans($ok);
				echo "<b style='font-size:14px;'><center>Cuentas </center></br>  &nbsp;&nbsp;Ingresados ( $ist )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) </b><br/><hr/>";
				//$db->debug=true;
				$ist=0;$ups=0;$err=0;
				//$db->Execute("UPDATE cuentas c, cuenta_periodos cp SET cp.idestado=0 WHERE c.idcuenta=cp.idcuenta AND cp.idestado=1 AND cp.idperiodo=$id_periodo AND c.idcartera=51");
				$db->StartTrans();
						for($i=1;$i<=count($cuentas_p);$i++){
							if($flag_cta[4][$i]==""){ continue;}
							$flag=$db->Execute($flag_cta[4][$i]);
							if($flag->fields['idcuenta']==""){
								++$ist;
								$ok=$db->Execute($cuentas_p[$i]);
								if($ok == false){
									$db->CompleteTrans(false);
									return false;
								}
							}else{
								++$ups;
								$ok=$db->Execute($update_cta[4][$i]);
								if($ok == false){
									$db->CompleteTrans(false);
									return false;
								}
							}
						}
					
				$db->CompleteTrans($ok);
				echo "<b style='font-size:14px;'><center>Cuentas Periodo</center></br> &nbsp;&nbsp;Ingresados ( $ist )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) </b><br/><hr/>";
				return false;
				//var_dump($clientes);
				
				/*var_dump($direcciones);
				var_dump($telefonos);
				var_dump($cuentas);
				var_dump($cuentas_p);*/
	
}






?>
