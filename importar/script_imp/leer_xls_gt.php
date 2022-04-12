<?php
//return false;
session_start();
ini_set('memory_limit', '-1');
set_time_limit(0);
echo "<pre style='font-size:11px;'>";
require_once 'Excel/reader.php';
include 'ConnecADO.php';
$name=$_GET['archivo'];
$id_periodo=$_SESSION['periodo'];

$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
$data->read($name);
$idcartera=$_GET['idcartera'];
foreach($data->sheets as $x => $y){//recorrido de las pesta�as del archivo excel
            
	$name= strtolower($data->boundsheets[$x]['name']);
     //echo $data->boundsheets[$x]['name']."<br/><br/>";//nombre de las pesta�as
	$db->Execute("UPDATE cuentas c, cuenta_periodos cp SET cp.idestado=0,cp.usureg='".(($id_periodo)-1)."' WHERE c.idcuenta=cp.idcuenta AND cp.idperiodo=$id_periodo AND c.idcartera=$idcartera");
 	sleep(40);
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
						if(strlen(trim($data->sheets[$x]['cells'][$i][2]))==9 and $i>1){
							echo "Hay dni o nro de documento con 9 digitos. Verificar el archivo por favor.";
							return false;
						}
						if(strlen(trim($data->sheets[$x]['cells'][$i][2]))==8){
						$flag_cta[0][$s]="select idcliente from clientes where idcliente='".$data->sheets[$x]['cells'][$i][2]."'";
						$update_cta[0][$s]="update clientes set cliente='".addslashes($data->sheets[$x]['cells'][$i][7])."' where idcliente='".$data->sheets[$x]['cells'][$i][2]."'";

						$clientes[$s]="INSERT INTO clientes (iddoi,idpersoneria,idcliente,cliente,observacion,u_fecges)
						values(1,1,'".$data->sheets[$x]['cells'][$i][2]."','".addslashes(utf8_encode($data->sheets[$x]['cells'][$i][7]))."','".$data->sheets[$x]['cells'][$i][1]."-".$data->sheets[$x]['cells'][$i][3]."-".$data->sheets[$x]['cells'][$i][4]."','".date("Y-m-d")."')";
						
						if(strlen(trim($data->sheets[$x]['cells'][$i][28]))==5){
							$dir=(string) '0'.substr($data->sheets[$x]['cells'][$i][28],0,1);
						}else{
							$dir=(string) substr($data->sheets[$x]['cells'][$i][28],0,2);
						}
						
						if(strlen(trim($data->sheets[$x]['cells'][$i][28]))==5){

							if(trim($data->sheets[$x]['cells'][$i][25])!=""){
								++$d_trabajo;
							}

							$flag_cta[1][$s]="select idcliente from direcciones where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and direccion='".$data->sheets[$x]['cells'][$i][20]."'";
							$update_cta[1][$s]="update direcciones set coddpto='$dir',codprov='".substr($data->sheets[$x]['cells'][$i][28],1,2)."',coddist='".substr($data->sheets[$x]['cells'][$i][28],3,2)."',observacion='".addslashes($data->sheets[$x]['cells'][$i][24])."*".addslashes($data->sheets[$x]['cells'][$i][25])."*".$data->sheets[$x]['cells'][$i][26]."' where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and direccion='".$data->sheets[$x]['cells'][$i][20]."'";
							$direcciones[$s]="INSERT INTO direcciones (idfuente,idcuadrante,idorigendireccion,idcliente,direccion,coddpto,codprov,coddist,observacion)
							values (1,1,1,'".$data->sheets[$x]['cells'][$i][2]."','".addslashes($data->sheets[$x]['cells'][$i][20])."',
							'0".substr($data->sheets[$x]['cells'][$i][28],0,1)."','".substr($data->sheets[$x]['cells'][$i][28],1,2)."','".substr($data->sheets[$x]['cells'][$i][28],3,2)."','".addslashes($data->sheets[$x]['cells'][$i][24])."*".addslashes($data->sheets[$x]['cells'][$i][25])."*".addslashes($data->sheets[$x]['cells'][$i][26])."')
							";
						}else{
	
							if(trim($data->sheets[$x]['cells'][$i][25])!=""){
								++$d_trabajo;

							}

							$flag_cta[1][$s]="select idcliente from direcciones where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and direccion='".$data->sheets[$x]['cells'][$i][20]."'";
							$update_cta[1][$s]="update direcciones set coddpto='$dir',codprov='".substr($data->sheets[$x]['cells'][$i][28],2,2)."',coddist='".substr($data->sheets[$x]['cells'][$i][28],4,2)."',observacion='".addslashes($data->sheets[$x]['cells'][$i][24])."*".addslashes($data->sheets[$x]['cells'][$i][25])."*".$data->sheets[$x]['cells'][$i][26]."' where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and direccion='".addslashes($data->sheets[$x]['cells'][$i][20])."'";
							$direcciones[$s]="INSERT INTO direcciones (idfuente,idcuadrante,idorigendireccion,idcliente,direccion,coddpto,codprov,coddist,observacion)
							values (1,1,1,'".$data->sheets[$x]['cells'][$i][2]."','".addslashes($data->sheets[$x]['cells'][$i][20])."',
							'".substr($data->sheets[$x]['cells'][$i][28],0,2)."','".substr($data->sheets[$x]['cells'][$i][28],2,2)."','".substr($data->sheets[$x]['cells'][$i][28],4,2)."','".addslashes($data->sheets[$x]['cells'][$i][24])."*".addslashes($data->sheets[$x]['cells'][$i][25])."*".addslashes($data->sheets[$x]['cells'][$i][26])."')
							";
						
						}
						if($i>1){


						if(strlen(trim($data->sheets[$x]['cells'][$i][28]))==5){
							$dir=(string) '0'.substr($data->sheets[$x]['cells'][$i][28],0,1);
						}else{
							$dir=(string) substr($data->sheets[$x]['cells'][$i][28],0,2);
						}
						
						$cdd=$db->Execute("select cdd from fonos_cdd where coddpto='$dir'");
						if($data->sheets[$x]['cells'][$i][15]!="" and $data->sheets[$x]['cells'][$i][15]!="NULL"){
							if(substr($data->sheets[$x]['cells'][$i][15],0,1)==9 and strlen(trim($data->sheets[$x]['cells'][$i][15]))<9)
							{
								$nit="9";
							}
							
							if(substr($data->sheets[$x]['cells'][$i][15],0,1)==9){$cdd_f="";$oriT=5;}else{$cdd_f=$cdd->fields['cdd'];$oriT=2;}
							$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$cdd_f.$nit.$data->sheets[$x]['cells'][$i][15]."'";
							$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$cdd_f.$nit.$data->sheets[$x]['cells'][$i][15]."'";
							$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
							values (1,$oriT,'".$data->sheets[$x]['cells'][$i][2]."','".$cdd_f.$nit.$data->sheets[$x]['cells'][$i][15]."','')";
							++$l;
						}
						
						if($data->sheets[$x]['cells'][$i][16]!="" and $data->sheets[$x]['cells'][$i][16]!="NULL"){
							if(substr($data->sheets[$x]['cells'][$i][16],0,1)==9 and strlen(trim($data->sheets[$x]['cells'][$i][16]))<9)
							{
								$nit="9";
							}
							
							if(substr($data->sheets[$x]['cells'][$i][16],0,1)==9){$cdd_f="";$oriT=5;}else{$cdd_f=$cdd->fields['cdd'];$oriT=2;}
							$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$cdd_f.$nit.$data->sheets[$x]['cells'][$i][16]."'";
							$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$cdd_f.$nit.$data->sheets[$x]['cells'][$i][16]."'";
							
							$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
							values (1,$oriT,'".$data->sheets[$x]['cells'][$i][2]."','".$cdd_f.$nit.$data->sheets[$x]['cells'][$i][16]."','')";
							++$l;
						
						}
						
						if($data->sheets[$x]['cells'][$i][17]!="" and $data->sheets[$x]['cells'][$i][17]!="NULL"){
							if(substr($data->sheets[$x]['cells'][$i][17],0,1)==9 and strlen(trim($data->sheets[$x]['cells'][$i][17]))<9)
							{
								$nit="9";
							}
							
							if(substr($data->sheets[$x]['cells'][$i][17],0,1)==9){$cdd_f="";$oriT=5;}else{$cdd_f=$cdd->fields['cdd'];$oriT=2;}
							$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$cdd_f.$nit.$data->sheets[$x]['cells'][$i][17]."'";
							$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$cdd_f.$nit.$data->sheets[$x]['cells'][$i][17]."'";
							
							$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
							values (1,$oriT,'".$data->sheets[$x]['cells'][$i][2]."','".$cdd_f.$nit.$data->sheets[$x]['cells'][$i][17]."','')";
							++$l;
						}
						
						if($data->sheets[$x]['cells'][$i][18]!="" and $data->sheets[$x]['cells'][$i][18]!="NULL"){
							if(substr($data->sheets[$x]['cells'][$i][18],0,1)==9 and strlen(trim($data->sheets[$x]['cells'][$i][18]))<9)
							{
								$nit="9";
							}
							
							if(substr($data->sheets[$x]['cells'][$i][18],0,1)==9){$cdd_f="";$oriT=6;}else{$cdd_f=$cdd->fields['cdd'];$oriT=3;}
							$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$cdd_f.$nit.$data->sheets[$x]['cells'][$i][18]."'";
							$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT,observacion='".$data->sheets[$x]['cells'][$i][19]."' where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$cdd_f.$nit.$data->sheets[$x]['cells'][$i][18]."'";
							
							$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
							values (1,$oriT,'".$data->sheets[$x]['cells'][$i][2]."','".$cdd_f.$nit.$data->sheets[$x]['cells'][$i][18]."','".$data->sheets[$x]['cells'][$i][19]."')";
							++$l;
						}
						

						
						if($data->sheets[$x]['cells'][$i][33]!="" and $data->sheets[$x]['cells'][$i][33]!="NULL"){	
							if(substr($data->sheets[$x]['cells'][$i][33],0,1)==9 and strlen(trim($data->sheets[$x]['cells'][$i][33]))<9)
							{
								$nit="9";
							}
							
							if(substr($data->sheets[$x]['cells'][$i][33],0,1)==9){$cdd_f="";$oriT=5;}else{$cdd_f=$cdd->fields['cdd'];$oriT=2;}
							$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$cdd_f.$nit.$data->sheets[$x]['cells'][$i][33]."'";
							$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$cdd_f.$nit.$data->sheets[$x]['cells'][$i][33]."'";
				
							$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
							values (1,$oriT,'".$data->sheets[$x]['cells'][$i][2]."','".$cdd_f.$nit.$data->sheets[$x]['cells'][$i][33]."','')";
							++$l;
						}
						
						if($data->sheets[$x]['cells'][$i][34]!="" and $data->sheets[$x]['cells'][$i][34]!="NULL"){
							if(substr($data->sheets[$x]['cells'][$i][34],0,1)==9 and strlen(trim($data->sheets[$x]['cells'][$i][34]))<9)
							{
								$nit="9";
							}
							
							if(substr($data->sheets[$x]['cells'][$i][34],0,1)==9){$cdd_f="";$oriT=5;}else{$cdd_f=$cdd->fields['cdd'];$oriT=2;}
							$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$cdd_f.$nit.$data->sheets[$x]['cells'][$i][34]."'";
							$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$cdd_f.$nit.$data->sheets[$x]['cells'][$i][34]."'";
							
							$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
							values (1,$oriT,'".$data->sheets[$x]['cells'][$i][2]."','".$cdd_f.$nit.$data->sheets[$x]['cells'][$i][34]."','')";
							++$l;
						}
						
						if($data->sheets[$x]['cells'][$i][35]!="" and $data->sheets[$x]['cells'][$i][35]!="NULL"){
							if(substr($data->sheets[$x]['cells'][$i][35],0,1)==9 and strlen(trim($data->sheets[$x]['cells'][$i][35]))<9)
							{
								$nit="9";
							}
							
							if(substr($data->sheets[$x]['cells'][$i][35],0,1)==9){$cdd_f="";$oriT=5;}else{$cdd_f=$cdd->fields['cdd'];$oriT=2;}
							$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$cdd_f.$nit.$data->sheets[$x]['cells'][$i][35]."'";
							$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$cdd_f.$nit.$data->sheets[$x]['cells'][$i][35]."'";
							
							$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
							values (1,$oriT,'".$data->sheets[$x]['cells'][$i][2]."','".$cdd_f.$nit.$data->sheets[$x]['cells'][$i][35]."','')";
							++$l;
						}
						
						if($data->sheets[$x]['cells'][$i][36]!="" and $data->sheets[$x]['cells'][$i][36]!="NULL"){
							if(substr($data->sheets[$x]['cells'][$i][36],0,1)==9 and strlen(trim($data->sheets[$x]['cells'][$i][36]))<9)
							{
								$nit="9";
							}
							
							if(substr($data->sheets[$x]['cells'][$i][36],0,1)==9){$cdd_f="";$oriT=5;}else{$cdd_f=$cdd->fields['cdd'];$oriT=2;}
							$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$cdd_f.$nit.$data->sheets[$x]['cells'][$i][36]."'";
							$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$cdd_f.$nit.$data->sheets[$x]['cells'][$i][36]."'";
							
							$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
							values (1,$oriT,'".$data->sheets[$x]['cells'][$i][2]."','".$cdd_f.$nit.$data->sheets[$x]['cells'][$i][36]."','')";
							++$l;
						}
						
						if($data->sheets[$x]['cells'][$i][37]!="" and $data->sheets[$x]['cells'][$i][37]!="NULL"){
							if(substr($data->sheets[$x]['cells'][$i][37],0,1)==9 and strlen(trim($data->sheets[$x]['cells'][$i][37]))<9)
							{
								$nit="9";
							}
							
							if(substr($data->sheets[$x]['cells'][$i][37],0,1)==9){$cdd_f="";$oriT=5;}else{$cdd_f=$cdd->fields['cdd'];$oriT=2;}
							$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$cdd_f.$nit.$data->sheets[$x]['cells'][$i][37]."'";
							$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$cdd_f.$nit.$data->sheets[$x]['cells'][$i][37]."'";
							
							$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
							values (1,$oriT,'".$data->sheets[$x]['cells'][$i][2]."','".$cdd_f.$nit.$data->sheets[$x]['cells'][$i][37]."','')";
							++$l;
						}
						
						if($data->sheets[$x]['cells'][$i][30]!="" and $data->sheets[$x]['cells'][$i][30]!="NULL"){
							if(substr($data->sheets[$x]['cells'][$i][30],0,1)==9 and strlen(trim($data->sheets[$x]['cells'][$i][30]))<9)
							{
								$nit="9";
							}
							
							if(substr($data->sheets[$x]['cells'][$i][30],0,1)==9){$cdd_f="";$oriT=5;}else{$cdd_f=$cdd->fields['cdd'];$oriT=2;}
							$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$cdd_f.$nit.$data->sheets[$x]['cells'][$i][30]."'";
							$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT,observacion='".$data->sheets[$x]['cells'][$i][29]."' where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$cdd_f.$nit.$data->sheets[$x]['cells'][$i][30]."'";
							
							$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
							values (1,$oriT,'".$data->sheets[$x]['cells'][$i][2]."','".$cdd_f.$nit.$data->sheets[$x]['cells'][$i][30]."','".$data->sheets[$x]['cells'][$i][29]."')";
							++$l;
						}
						
						if($data->sheets[$x]['cells'][$i][32]!="" and $data->sheets[$x]['cells'][$i][32]!="NULL"){
							if(substr($data->sheets[$x]['cells'][$i][32],0,1)==9 and strlen(trim($data->sheets[$x]['cells'][$i][32]))<9)
							{
								$nit="9";
							}
							
							if(substr($data->sheets[$x]['cells'][$i][32],0,1)==9){$cdd_f="";$oriT=7;}else{$cdd_f=$cdd->fields['cdd'];$oriT=4;}
							$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$cdd_f.$nit.$data->sheets[$x]['cells'][$i][32]."'";
							$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT,observacion='".$data->sheets[$x]['cells'][$i][31]."' where idcliente='".$data->sheets[$x]['cells'][$i][2]."' and telefono='".$cdd_f.$nit.$nit.$data->sheets[$x]['cells'][$i][32]."'";
							
							$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
							values (1,$oriT,'".$data->sheets[$x]['cells'][$i][2]."','".$cdd_f.$nit.$data->sheets[$x]['cells'][$i][32]."','".$data->sheets[$x]['cells'][$i][31]."')";
							++$l;
						}
						}
							if(strlen(trim($data->sheets[$x]['cells'][$i][3]))<7 and strlen(trim($data->sheets[$x]['cells'][$i][3]))!=""){
								echo "La longitud del campo cuentas no corresponde al actual (7 digitos). Verificar el archivo por favor.";
								return false;
							}
							if(strlen(trim($data->sheets[$x]['cells'][$i][3]))>=7){

								$pos = strpos($data->sheets[$x]['cells'][$i][44], "/");

												if($pos){
													$fecs = explode("/",$data->sheets[$x]['cells'][$i][44]);
													$fecs = $fecs[2]."-".$fecs[1]."-".$fecs[0];
			
												}else{
													$fecs=$data->sheets[$x]['cells'][$i][44];

												}

								$flag_cta[3][$s]="select idcuenta from cuentas where idcuenta='".$data->sheets[$x]['cells'][$i][3]."-1'  -- and idcliente='".$data->sheets[$x]['cells'][$i][2]."'  ";
								$update_cta[3][$s]="update cuentas set idcartera=$idcartera,idcliente='".$data->sheets[$x]['cells'][$i][2]."',obs2='".$data->sheets[$x]['cells'][$i][42]."*".$data->sheets[$x]['cells'][$i][43]."*".$fecs."', idproducto=522,idtipocartera=1 where idcuenta='".$data->sheets[$x]['cells'][$i][3]."-1'";
								$cuentas[$s]="INSERT INTO cuentas (idmoneda,idproducto,idcartera,idusuario,idtipocartera,idcliente,idcuenta,obs2)
									values (1,522,$idcartera,2,1,'".$data->sheets[$x]['cells'][$i][2]."','".$data->sheets[$x]['cells'][$i][3]."-1','".$data->sheets[$x]['cells'][$i][42]."*".$data->sheets[$x]['cells'][$i][43]."*".$fecs."');
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

								
								$fecven=substr($data->sheets[$x]['cells'][$i][6],0,4)."-".substr($data->sheets[$x]['cells'][$i][6],4,2)."-".substr($data->sheets[$x]['cells'][$i][6],6);
								$flag_cta[4][$s]="select idcuenta from cuenta_periodos where idcuenta='".$data->sheets[$x]['cells'][$i][3]."-1' and idperiodo=$id_periodo";
								$update_cta[4][$s]="update cuenta_periodos set usureg=$id_periodo,idestado=1,grupo=".$data->sheets[$x]['cells'][$i][5].",fecven='".$fecven."',impmin='".$data->sheets[$x]['cells'][$i][9]."',imptot='".$data->sheets[$x]['cells'][$i][11]."',diasmora='".$data->sheets[$x]['cells'][$i][14]."',ciclo='".$data->sheets[$x]['cells'][$i][13]."',observacion2='$riesgo',observacion='".$data->sheets[$x]['cells'][$i][1]."-".$data->sheets[$x]['cells'][$i][3]."-".$data->sheets[$x]['cells'][$i][4]."' where idcuenta='".$data->sheets[$x]['cells'][$i][3]."-1' and idperiodo=$id_periodo";
								$cuentas_p[$s]="INSERT INTO cuenta_periodos (usureg,idperiodo,idusuario,idcuenta,grupo,fecven,impmin,imptot,diasmora,ciclo,observacion2,observacion)
									values ($id_periodo,$id_periodo,$idusu,'".$data->sheets[$x]['cells'][$i][3]."-1','".$data->sheets[$x]['cells'][$i][5]."','".$fecven."','".$data->sheets[$x]['cells'][$i][9]."','".$data->sheets[$x]['cells'][$i][11]."','".$data->sheets[$x]['cells'][$i][14]."','".$data->sheets[$x]['cells'][$i][13]."','".$riesgo."','".$data->sheets[$x]['cells'][$i][1]."-".$data->sheets[$x]['cells'][$i][3]."-".$data->sheets[$x]['cells'][$i][4]."');
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
				
				//$db->StartTrans();
					$db->Execute("START TRANSACTION;");
						for($i=1;$i<=count($clientes);$i++){
							
							$flag=$db->Execute($flag_cta[0][$i]);
							if($flag->fields['idcliente']==""){
								++$ist;
								$ok=$db->Execute($clientes[$i]);
								if($ok == false){
									//$db->CompleteTrans(false);
									$db->Execute("ROLLBACK;");
									return false;
								}
							}/*else{
								++$ups;
								$ok=$db->Execute($update_cta[0][$i]);
								if($ok == false){
									//$db->CompleteTrans(false);
									return false;
								}
							}*/
						}
					$db->Execute("COMMIT;");
				//$db->CompleteTrans($ok);
				echo "Clientes | &nbsp;&nbsp;Ingresados ( $ist )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) <br/>";
				//$db->debug=true;
				$ist=0;$ups=0;$err=0;
				$db->Execute("START TRANSACTION;");
						for($i=2;$i<=count($direcciones);$i++){
							$flag=$db->Execute($flag_cta[1][$i]);
							if($flag->fields['idcliente']==""){
								++$ist;
								

								$ok=$db->Execute($direcciones[$i]);
								//$db->debug=false;
								if($ok == false){
									

									//$db->Execute("ROLLBACK;");
									//return false;
								}
							}else{
								++$ups;
								$ok=$db->Execute($update_cta[1][$i]);
								if($ok == false){
									//$db->Execute("ROLLBACK;");
									//return false;
								}
							}
						}
					
				$db->Execute("COMMIT;");
				echo "Direcciones | &nbsp;&nbsp;Ingresados ( $ist )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) <br/>";
				
				//$db->debug=false;
				//$db->debug=true;
				$ist=0;$ups=0;$err=0;
				$db->Execute("START TRANSACTION;");
						for($i=1;$i<=count($telefonos);$i++){
							$flag=$db->Execute($flag_cta[2][$i]);
							if($flag->fields['idcliente']==""){
								++$ist;
								$ok=$db->Execute($telefonos[$i]);
								if($ok == false){
									$db->debug=true;

									$db->Execute("ROLLBACK;");
									//$db->debug=false;

									//return false;
								}
							}else{
								++$ups;
								$ok=$db->Execute($update_cta[2][$i]);
								if($ok == false){
									$db->debug=true;

									$db->Execute("ROLLBACK;");
									//$db->debug=false;

									//return false;
								}
							}
						}
					
				$db->Execute("COMMIT;");
				echo "Telefonos | &nbsp;&nbsp;Ingresados ( $ist )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) <br/>";
				//$db->debug=false;
				//$db->debug=true;
				$ist=0;$ups=0;$err=0;
				$db->Execute("START TRANSACTION;");
						for($i=1;$i<=count($cuentas);$i++){
							$flag=$db->Execute($flag_cta[3][$i]);
							if($flag->fields['idcuenta']==""){
								++$ist;
								$ok=$db->Execute($cuentas[$i]);
								if($ok == false){
									$db->Execute("ROLLBACK;");
									//return false;
								}
							}else{
								++$ups;
								$ok=$db->Execute($update_cta[3][$i]);
								if($ok == false){
									$db->Execute("ROLLBACK;");
									//return false;
								}
							}
						}
					
						$db->Execute("COMMIT;");
				echo "Cuentas | &nbsp;&nbsp;Ingresados ( $ist )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) <br/>";
				//$db->debug=true;
				$ist=0;$ups=0;$err=0;
				//$db->Execute("UPDATE cuentas c, cuenta_periodos cp SET cp.idestado=0 WHERE c.idcuenta=cp.idcuenta AND cp.idestado=1 AND cp.idperiodo=$id_periodo AND c.idcartera=51");
				$db->Execute("START TRANSACTION;");
						for($i=1;$i<=count($cuentas_p);$i++){
							if($flag_cta[4][$i]==""){ continue;}
							$flag=$db->Execute($flag_cta[4][$i]);
							if($flag->fields['idcuenta']==""){
								++$ist;
								$ok=$db->Execute($cuentas_p[$i]);
								if($ok == false){
									$db->Execute("ROLLBACK;");
									//return false;
								}
							}else{
								++$ups;
								$ok=$db->Execute($update_cta[4][$i]);
								if($ok == false){
									$db->Execute("ROLLBACK;");
									//return false;
								}
							}
						}
					
				$db->Execute("COMMIT;");
				echo "Cuentas Periodo | &nbsp;&nbsp;Ingresados ( $ist )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) <br/>";
				return false;
				//var_dump($clientes);
				
				/*var_dump($direcciones);
				var_dump($telefonos);
				var_dump($cuentas);
				var_dump($cuentas_p);*/
	
}






?>
