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
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
$data->read($name);

foreach($data->sheets as $x => $y){//recorrido de las pestañas del archivo excel
            
	$name= strtolower($data->boundsheets[$x]['name']);
     //echo $data->boundsheets[$x]['name']."<br/><br/>";//nombre de las pestañas
	$db3->Execute("UPDATE cuentas c, cuenta_periodos cp SET cp.idestado=0,cp.usureg='22' WHERE c.idcuenta=cp.idcuenta AND cp.idestado=1 AND cp.idperiodo=$id_periodo AND c.idcartera=51");
 	sleep(380);
	$s=1;
	$l=1;
	$direcciones= array();
	$clientes=array();
	$telefonos=array();
	$cuentas=array();
	$cuenta_p=array();
	$flag_cta=array(); 
	$update_cta=array();
    for ($i = 1,$h=1; $i <= $data->sheets[$x]['numRows']; $i++,$h++) {// defino $i=2 para q no tome la primera fila q es la cabecera
			    
                for ($j = 1; $j <= $data->sheets[$x]['numCols']; $j++) {
						//if($j>=44){break;}
					if($j==$data->sheets[$x]['numCols']){
						if(strlen(trim($data->sheets[$x]['cells'][$i][2]))==8){
						$flag_cta[0][$s]="select idcliente from clientes where idcliente='".$data->sheets[$x]['cells'][$i][2]."'";
						$update_cta[0][$s]="update clientes set cliente='".addslashes($data->sheets[$x]['cells'][$i][5])."' where idcliente='".$data->sheets[$x]['cells'][$i][2]."'";

						$clientes[$s]="INSERT INTO clientes (iddoi,idpersoneria,idcliente,cliente,observacion)
						values(1,1,'".$data->sheets[$x]['cells'][$i][2]."','".addslashes($data->sheets[$x]['cells'][$i][5])."','".$data->sheets[$x]['cells'][$i][1]."-".$data->sheets[$x]['cells'][$i][3]."-".$data->sheets[$x]['cells'][$i][4]."')";
						
						if(strlen(trim($data->sheets[$x]['cells'][$i][15]))==5){
							$dir=(string) '0'.substr($data->sheets[$x]['cells'][$i][15],0,1);
						}else{
							$dir=(string) substr($data->sheets[$x]['cells'][$i][15],0,2);
						}
						
						if(strlen(trim($data->sheets[$x]['cells'][$i][15]))==5){
							$flag_cta[1][$s]="select idcliente from direcciones where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and direccion='".$data->sheets[$x]['cells'][$i][7]."'";
							$update_cta[1][$s]="update direcciones set coddpto='$dir',codprov='".substr($data->sheets[$x]['cells'][$i][15],1,2)."',coddist='".substr($data->sheets[$x]['cells'][$i][15],3,2)."',observacion='".addslashes($data->sheets[$x]['cells'][$i][11])."*".addslashes($data->sheets[$x]['cells'][$i][12])."*".$data->sheets[$x]['cells'][$i][13]."' where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and direccion='".$data->sheets[$x]['cells'][$i][7]."'";
							$direcciones[$s]="INSERT INTO direcciones (idfuente,idcuadrante,idorigendireccion,idcliente,direccion,coddpto,codprov,coddist,observacion)
							values (1,1,1,'".$data->sheets[$x]['cells'][$i][2]."','".addslashes($data->sheets[$x]['cells'][$i][7])."',
							'0".substr($data->sheets[$x]['cells'][$i][15],0,1)."','".substr($data->sheets[$x]['cells'][$i][15],1,2)."','".substr($data->sheets[$x]['cells'][$i][15],3,2)."','".addslashes($data->sheets[$x]['cells'][$i][11])."*".addslashes($data->sheets[$x]['cells'][$i][12])."*".addslashes($data->sheets[$x]['cells'][$i][13])."')
							";
						}else{
							$flag_cta[1][$s]="select idcliente from direcciones where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and direccion='".$data->sheets[$x]['cells'][$i][7]."'";
							$update_cta[1][$s]="update direcciones set coddpto='$dir',codprov='".substr($data->sheets[$x]['cells'][$i][15],2,2)."',coddist='".substr($data->sheets[$x]['cells'][$i][15],4,2)."',observacion='".addslashes($data->sheets[$x]['cells'][$i][11])."*".addslashes($data->sheets[$x]['cells'][$i][12])."*".$data->sheets[$x]['cells'][$i][13]."' where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and direccion='".addslashes($data->sheets[$x]['cells'][$i][7])."'";
							$direcciones[$s]="INSERT INTO direcciones (idfuente,idcuadrante,idorigendireccion,idcliente,direccion,coddpto,codprov,coddist,observacion)
							values (1,1,1,'".$data->sheets[$x]['cells'][$i][2]."','".addslashes($data->sheets[$x]['cells'][$i][7])."',
							'".substr($data->sheets[$x]['cells'][$i][15],0,2)."','".substr($data->sheets[$x]['cells'][$i][15],2,2)."','".substr($data->sheets[$x]['cells'][$i][15],4,2)."','".addslashes($data->sheets[$x]['cells'][$i][11])."*".addslashes($data->sheets[$x]['cells'][$i][12])."*".addslashes($data->sheets[$x]['cells'][$i][13])."')
							";
						
						}
						if($i>1){
						if($data->sheets[$x]['cells'][$i][16]!="" and $data->sheets[$x]['cells'][$i][16]!="NULL"){
						
							if(substr($data->sheets[$x]['cells'][$i][16],0,1)==9){$oriT=2;}else{$oriT=1;}
							$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$data->sheets[$x]['cells'][$i][16]."'";
							$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$data->sheets[$x]['cells'][$i][16]."'";
							$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
							values (1,$oriT,'".$data->sheets[$x]['cells'][$i][2]."','".$data->sheets[$x]['cells'][$i][16]."','')";
							++$l;
						}
						
						if($data->sheets[$x]['cells'][$i][17]!="" and $data->sheets[$x]['cells'][$i][17]!="NULL"){
							
							if(substr($data->sheets[$x]['cells'][$i][17],0,1)==9){$oriT=2;}else{$oriT=1;}
							$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$data->sheets[$x]['cells'][$i][17]."'";
							$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$data->sheets[$x]['cells'][$i][17]."'";
							
							$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
							values (1,$oriT,'".$data->sheets[$x]['cells'][$i][2]."','".$data->sheets[$x]['cells'][$i][17]."','')";
							++$l;
						
						}
						
						if($data->sheets[$x]['cells'][$i][18]!="" and $data->sheets[$x]['cells'][$i][18]!="NULL"){
						
							if(substr($data->sheets[$x]['cells'][$i][18],0,1)==9){$oriT=2;}else{$oriT=1;}
							$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$data->sheets[$x]['cells'][$i][18]."'";
							$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$data->sheets[$x]['cells'][$i][18]."'";
							
							$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
							values (1,$oriT,'".$data->sheets[$x]['cells'][$i][2]."','".$data->sheets[$x]['cells'][$i][18]."','')";
							++$l;
						}
						
						if($data->sheets[$x]['cells'][$i][19]!="" and $data->sheets[$x]['cells'][$i][19]!="NULL"){
						
							if(substr($data->sheets[$x]['cells'][$i][19],0,1)==9){$oriT=4;}else{$oriT=3;}
							$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$data->sheets[$x]['cells'][$i][19]."'";
							$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT,observacion='".$data->sheets[$x]['cells'][$i][20]."' where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$data->sheets[$x]['cells'][$i][19]."'";
							
							$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
							values (1,$oriT,'".$data->sheets[$x]['cells'][$i][2]."','".$data->sheets[$x]['cells'][$i][19]."','".$data->sheets[$x]['cells'][$i][20]."')";
							++$l;
						}
						

						
						if($data->sheets[$x]['cells'][$i][21]!="" and $data->sheets[$x]['cells'][$i][21]!="NULL"){	
						
							if(substr($data->sheets[$x]['cells'][$i][21],0,1)==9){$oriT=2;}else{$oriT=1;}
							$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$data->sheets[$x]['cells'][$i][21]."'";
							$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$data->sheets[$x]['cells'][$i][21]."'";
				
							$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
							values (1,$oriT,'".$data->sheets[$x]['cells'][$i][2]."','".$data->sheets[$x]['cells'][$i][21]."','')";
							++$l;
						}
						
						if($data->sheets[$x]['cells'][$i][22]!="" and $data->sheets[$x]['cells'][$i][22]!="NULL"){
						
							if(substr($data->sheets[$x]['cells'][$i][22],0,1)==9){$oriT=2;}else{$oriT=1;}
							$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$data->sheets[$x]['cells'][$i][22]."'";
							$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$data->sheets[$x]['cells'][$i][22]."'";
							
							$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
							values (1,$oriT,'".$data->sheets[$x]['cells'][$i][2]."','".$data->sheets[$x]['cells'][$i][22]."','')";
							++$l;
						}
						
						if($data->sheets[$x]['cells'][$i][23]!="" and $data->sheets[$x]['cells'][$i][23]!="NULL"){
						
							if(substr($data->sheets[$x]['cells'][$i][23],0,1)==9){$oriT=2;}else{$oriT=1;}
							$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$data->sheets[$x]['cells'][$i][23]."'";
							$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$data->sheets[$x]['cells'][$i][23]."'";
							
							$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
							values (1,$oriT,'".$data->sheets[$x]['cells'][$i][2]."','".$data->sheets[$x]['cells'][$i][23]."','')";
							++$l;
						}
						
						if($data->sheets[$x]['cells'][$i][24]!="" and $data->sheets[$x]['cells'][$i][24]!="NULL"){
						
							if(substr($data->sheets[$x]['cells'][$i][24],0,1)==9){$oriT=2;}else{$oriT=1;}
							$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$data->sheets[$x]['cells'][$i][24]."'";
							$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$data->sheets[$x]['cells'][$i][24]."'";
							
							$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
							values (1,$oriT,'".$data->sheets[$x]['cells'][$i][2]."','".$data->sheets[$x]['cells'][$i][24]."','')";
							++$l;
						}
						
						if($data->sheets[$x]['cells'][$i][25]!="" and $data->sheets[$x]['cells'][$i][25]!="NULL"){
						
							if(substr($data->sheets[$x]['cells'][$i][25],0,1)==9){$oriT=2;}else{$oriT=1;}
							$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$data->sheets[$x]['cells'][$i][25]."'";
							$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$data->sheets[$x]['cells'][$i][25]."'";
							
							$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
							values (1,$oriT,'".$data->sheets[$x]['cells'][$i][2]."','".$data->sheets[$x]['cells'][$i][25]."','')";
							++$l;
						}
						
						if($data->sheets[$x]['cells'][$i][27]!="" and $data->sheets[$x]['cells'][$i][27]!="NULL"){
						
							if(substr($data->sheets[$x]['cells'][$i][18],0,1)==9){$oriT=2;}else{$oriT=1;}
							$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$data->sheets[$x]['cells'][$i][27]."'";
							$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT,observacion='".$data->sheets[$x]['cells'][$i][26]."' where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$data->sheets[$x]['cells'][$i][27]."'";
							
							$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
							values (1,$oriT,'".$data->sheets[$x]['cells'][$i][2]."','".$data->sheets[$x]['cells'][$i][27]."','".$data->sheets[$x]['cells'][$i][26]."')";
							++$l;
						}
						
						if($data->sheets[$x]['cells'][$i][29]!="" and $data->sheets[$x]['cells'][$i][29]!="NULL"){
						
							if(substr($data->sheets[$x]['cells'][$i][18],0,1)==9){$oriT=10;}else{$oriT=9;}
							$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$data->sheets[$x]['cells'][$i][29]."'";
							$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT,observacion='".$data->sheets[$x]['cells'][$i][28]."' where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$data->sheets[$x]['cells'][$i][29]."'";
							
							$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
							values (1,$oriT,'".$data->sheets[$x]['cells'][$i][2]."','".$data->sheets[$x]['cells'][$i][29]."','".$data->sheets[$x]['cells'][$i][28]."')";
							++$l;
						}
						}

						if(strlen(trim($data->sheets[$x]['cells'][$i][3]))==7){

						$pos = strpos($data->sheets[$x]['cells'][$i][44], "/");

										if($pos){
											$fecs = explode("/",$data->sheets[$x]['cells'][$i][44]);
											$fecs = $fecs[2]."-".$fecs[1]."-".$fecs[0];
	
										}else{
											$fecs=$data->sheets[$x]['cells'][$i][44];

										}

						$flag_cta[3][$s]="select idcuenta from cuentas where idcuenta='".$data->sheets[$x]['cells'][$i][3]."-1' /* and idcliente='".$data->sheets[$x]['cells'][$i][2]."' */ ";
						$update_cta[3][$s]="update cuentas set idcliente='".$data->sheets[$x]['cells'][$i][2]."',obs2='".$data->sheets[$x]['cells'][$i][42]."*".$data->sheets[$x]['cells'][$i][43]."*".$fecs."', idproducto=522,idtipocartera=1 where idcuenta='".$data->sheets[$x]['cells'][$i][3]."-1'";
						$cuentas[$s]="INSERT INTO cuentas (idmoneda,idproducto,idcartera,idusuario,idtipocartera,idcliente,idcuenta,obs2)
							values (1,522,51,2,1,'".$data->sheets[$x]['cells'][$i][2]."','".$data->sheets[$x]['cells'][$i][3]."-1','".$data->sheets[$x]['cells'][$i][42]."*".$data->sheets[$x]['cells'][$i][43]."*".$fecs."');
							";
						
						if($data->sheets[$x]['cells'][$i][41]=="Riesgo Alto"){$riesgo=1;}
						if($data->sheets[$x]['cells'][$i][41]=="Riesgo Medio"){$riesgo=2;}
						if($data->sheets[$x]['cells'][$i][41]=="Riesgo Bajo"){$riesgo=3;}

						$sl="select idcuenta,idusuario from cuenta_periodos where idcuenta='".$data->sheets[$x]['cells'][$i][3]."-1' and idperiodo=".($id_periodo-1);
						$id_usureg=$db->Execute($sl);
						
						if($id_usureg->fields['idusuario']!=""){
							$idusu=$id_usureg->fields['idusuario'];
						}else{
							$idusu=2;
						}

						
						$fecven=substr($data->sheets[$x]['cells'][$i][34],0,4)."-".substr($data->sheets[$x]['cells'][$i][34],4,2)."-".substr($data->sheets[$x]['cells'][$i][34],6);
						$flag_cta[4][$s]="select idcuenta from cuenta_periodos where idcuenta='".$data->sheets[$x]['cells'][$i][3]."-1' and idperiodo=$id_periodo";
						$update_cta[4][$s]="update cuenta_periodos set usureg=$id_periodo,idestado=1,grupo=".$data->sheets[$x]['cells'][$i][33].",fecven='".$fecven."',impmin='".$data->sheets[$x]['cells'][$i][35]."',imptot='".$data->sheets[$x]['cells'][$i][37]."',diasmora='".$data->sheets[$x]['cells'][$i][40]."',ciclo=".$data->sheets[$x]['cells'][$i][39].",observacion2='$riesgo',observacion='".$data->sheets[$x]['cells'][$i][1]."-".$data->sheets[$x]['cells'][$i][3]."-".$data->sheets[$x]['cells'][$i][4]."' where idcuenta='".$data->sheets[$x]['cells'][$i][3]."-1' and idperiodo=$id_periodo";
						$cuentas_p[$s]="INSERT INTO cuenta_periodos (usureg,idperiodo,idusuario,idcuenta,grupo,fecven,impmin,imptot,diasmora,ciclo,observacion2,observacion)
							values ($id_periodo,$id_periodo,$idusu,'".$data->sheets[$x]['cells'][$i][3]."-1','".$data->sheets[$x]['cells'][$i][33]."','".$fecven."','".$data->sheets[$x]['cells'][$i][35]."','".$data->sheets[$x]['cells'][$i][37]."','".$data->sheets[$x]['cells'][$i][40]."','".$data->sheets[$x]['cells'][$i][39]."','".$riesgo."','".$data->sheets[$x]['cells'][$i][1]."-".$data->sheets[$x]['cells'][$i][3]."-".$data->sheets[$x]['cells'][$i][4]."');
							";
						
						$s++;
						}
						}
					}
				}
				
				//if($i==1) var_dump($dato);
								
	}
				 
				$ok=true;
				$t=1;
				//$db->debug=true;
				$ist=0;$ups=0;$err=0;
				$db->StartTrans();
						for($i=2;$i<=count($clientes);$i++){
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
				echo "Clientes | &nbsp;&nbsp;Ingresados ( $ist )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) <br/>";
				//$db->debug=true;
				$ist=0;$ups=0;$err=0;
				$db->StartTrans();
						for($i=2;$i<=count($direcciones);$i++){
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
				echo "Direcciones | &nbsp;&nbsp;Ingresados ( $ist )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) <br/>";
				//$db->debug=false;
				$ist=0;$ups=0;$err=0;
				$db->StartTrans();
						for($i=2;$i<=count($telefonos);$i++){
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
				echo "Telefonos | &nbsp;&nbsp;Ingresados ( $ist )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) <br/>";
				
				//$db->debug=true;
				$ist=0;$ups=0;$err=0;
				$db->StartTrans();
						for($i=2;$i<=count($cuentas);$i++){
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
				echo "Cuentas | &nbsp;&nbsp;Ingresados ( $ist )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) <br/>";
				//$db->debug=true;
				$ist=0;$ups=0;$err=0;
				//$db->Execute("UPDATE cuentas c, cuenta_periodos cp SET cp.idestado=0 WHERE c.idcuenta=cp.idcuenta AND cp.idestado=1 AND cp.idperiodo=$id_periodo AND c.idcartera=51");
				$db->StartTrans();
						for($i=2;$i<=count($cuentas_p);$i++){
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
				echo "Cuentas Periodo | &nbsp;&nbsp;Ingresados ( $ist )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) <br/>";
				
				//var_dump($clientes);
				
				/*var_dump($direcciones);
				var_dump($telefonos);
				var_dump($cuentas);
				var_dump($cuentas_p);*/
	
}






?>
