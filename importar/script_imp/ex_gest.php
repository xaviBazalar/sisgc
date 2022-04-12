<?php
//return false;
session_start();
$iduser=$_SESSION['iduser'];
$name=$_GET['archivo'];
ini_set('memory_limit', '-1');
set_time_limit(1800);
$ip=$_SERVER['REMOTE_ADDR'];
$val_ip=explode(".",$ip);
$validar_name=explode(".",$name);

if($validar_name[1]!="xls"){
	echo "Por favor cargue un archivo valido.(Solo se aceptan con extension .xls)";
	return false;
}

require_once 'Excel/reader.php';
include 'ConnecADO.php';

/*$flag=$db->Execute("select flag from flag_reportes where reporte='importacion'");
if($flag->fields['flag']=="0"){
	$fecha=date("Y-m-d");
	$hora=date("H:i:s");
	$db->Execute("INSERT into detalle_reportes (`idreporte`,`idusuario`,`fecha`,`hora_ini`,`host`) VALUES('1','$iduser','$fecha','$hora','$ip')");
}else{
	echo "En estos momentos ya se encuentra una importacion en curso. Espero uno minutos por favor y vuelva a intentarlo.";
	//return false;
}*/
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
$data->read($name);
error_reporting(E_ALL ^ E_NOTICE);
$sql= array ();
$w=0;


$periodo= $_SESSION['periodo'];
//$db->debug=true;
echo "<font color='red'>Inicio de importacion :</font>".$ti=date("H:i:s")."<br/>";
//echo $ti;
echo "<pre>";
//$fp = fopen('verif.txt', 'w');
//fwrite($fp, 'importando');
//fclose($fp);
foreach($data->sheets as $x => $y){//recorrido de las pestañas del archivo excel
            
           $n_tabla=  strtolower($data->boundsheets[$x]['name']);//definimos nombre de pestaña
 //------------------------------------------------------------------------
	$name= strtolower($data->boundsheets[$x]['name']);
	
	$flag1="";
	$flag2="";
	$flag3="";
	$flag4="";
	$flag5="";
	if($_GET['t_imp']==0){$flag1="2";}
	if($_GET['t_imp']==1){$flag2="2";}
	if($_GET['t_imp']==2){$flag3="2";}
	if($_GET['t_imp']==3){$flag4="2";}
	if($_GET['t_imp']==4){$flag5="2";}
	
	if( $name=="clientes$flag1" xor 
		$name=="direcciones$flag1" xor 
		$name=="telefonos$flag1" xor 
		$name=="cuentas$flag2" xor 
		$name=="cuenta_pagos$flag3" xor 
		$name=="cuenta_detalles$flag4" xor 
		$name=="contactos$flag1" xor 
		$name=="Tablas" xor 
		$name=="Diccionario"){
		
		continue;
	}
	

        echo "Datos Importados : ".$data->boundsheets[$x]['name'];//nombre de las pestañas

		    $nuevo= array();
            $insert=array();
			$cli =array();
//--------------------------------------------------------------------------------
            
               for ($i = 2,$h=1; $i <= $data->sheets[$x]['numRows']; $i++,$h++) {// defino $i=2 para q no tome la primera fila q es la cabecera
					$fecha_up=date("Y-m-d");
					
					if($name=="contactos"){
						$qr1="SELECT idcontacto from contactos where idcontacto=";
						$qr2="UPDATE clientes SET ";
						$query3="INSERT INTO `$n_tabla`(`idcliente`,`iddoi`,`idcontacto`,`contacto`,`idparentesco`,`telefono`,`observacion`,`idestado`) VALUES (";
					}
					
					if($name=="clientes"){
						
						$qr1="SELECT idcliente from clientes where idcliente=";
						$qr2="UPDATE clientes SET ";
						$query3="INSERT INTO `$n_tabla`(`idcliente`,`iddoi`,`cliente`,`idpersoneria`,`observacion`) VALUES (";
					}
     			
					if($name=="telefonos"){
						$qr1="SELECT idcliente from telefonos where idcliente=";
						$fech=date("Y-m-d H:i:s");
						$qr2="UPDATE telefonos SET fecreg='$fech', ";
						$query3="INSERT INTO `$n_tabla`(`idcliente`,`telefono`,`idorigentelefono`,`idestado`,`prioridad`,`observacion`,`idfuente`) VALUES ( ";
					}
  
					if($name=="direcciones"){
						$qr1="SELECT iddireccion from direcciones where idcliente=";
						$fech=date("Y-m-d H:i:s");
						$qr2="UPDATE direcciones SET fecreg='$fech',fecact='$fecha_up', ";
						$query3="INSERT INTO `$n_tabla`(`idcliente`,`direccion`,`idorigendireccion`,`idcuadrante`,`coddpto`,`codprov`,`coddist`,`idestado`,`prioridad`,`observacion`,`idfuente`) VALUES (";
					}
					
					if($name=="cuentas"){
						$qr1="SELECT idcuenta from cuentas where idcliente=";
						$qrs2="SELECT idcuenta from cuenta_periodos where idcuenta=";
						$qr_c="UPDATE cuentas SET fecconf='$fecha_up', ";
						$qr_cta="update cuentas set ";
						$qr2="UPDATE cuenta_periodos SET ";
						$query3="INSERT INTO `$n_tabla`(`idusuario`,`idcliente`,`idcuenta`,`idmoneda`,`idcartera`,`idproducto`,`feccon`,`idtipocartera`) VALUES (";
						$query4="INSERT INTO `cuenta_periodos`
						(`idcuenta`,`idestado`,`idperiodo`,`idusuario`,`fecven`,`nrocuotas`,`diasmora`,`grupo`,`ciclo`,`observacion`,`impcap`,`impint`,`impmor`,`impotr`,`imptot`,
						`impven`,`impmin`,`impdestot`,`impedesmor`,`impdesmmo`,`impfraini`,`fracuo`,`impfracpr`,`impframnt`,`impcapori`,`impprxpag`,`observacion2`,`obs3`) VALUES (";
					}
					
					if($name=="cuenta_detalles"){
						$qr1="SELECT idcuentaperiodo from cuenta_detalles where idcuentaperiodo=";
						$qr2="UPDATE cuenta_detalles SET ";
						$query3="INSERT INTO `$n_tabla`(`idcuentaperiodo`,`fecven`,`nrocuota`,`observacion`,`impcap`,`impint`,`impmor`,`impotr`,`imptot`,`idestado`) VALUES (";
					}
					
					if($name=="cuenta_pagos"){
						$qrx="SELECT idcuentapago FROM cuenta_pagos WHERE idcuenta='";
						$qr2="UPDATE cuenta_pagos SET ";
						$query3="INSERT INTO `$n_tabla`(`idcuenta`,`idperiodo`,`fecpag`,`observacion`,`imptot`,`idestado`) VALUES (";
					}
					
					//echo"<tr>";
                                               
                        for ($j = 1; $j <= $data->sheets[$x]['numCols']; $j++) {
								if($j == $data->sheets[$x]['numCols']){
									
								   if($name=="direcciones"){
										$qr2.="idfuente='".$data->sheets[$x]['cells'][$i][$j]."' ";
												$id_cl_dr=$data->sheets[$x]['cells'][$i][1];
												$cl_dr=$data->sheets[$x]['cells'][$i][2];
												$cons=$db->Execute("SELECT iddireccion from direcciones where idcliente='$id_cl_dr' and direccion='$cl_dr'");
												$iddir=$cons->fields['iddireccion'];
										$qr2.= "WHERE iddireccion='".$iddir."'";
										$query3.="'".$data->sheets[$x]['cells'][$i][$j]."')";
										$insert['up'][$h]=$qr2;
										$insert['select'][$h]=$qr1;
										$insert['insert'][$h]=$query3;
										$query3= array();
								   }
								   if($name=="telefonos"){
										$qr2.="idfuente='".$data->sheets[$x]['cells'][$i][$j]."' ";
												$id_cl_tf=$data->sheets[$x]['cells'][$i][1];
												$cl_tf=$data->sheets[$x]['cells'][$i][2];
												$cons=$db->Execute("SELECT idtelefono from telefonos where idcliente='$id_cl_tf' and telefono='$cl_tf'");
												$idfon=$cons->fields['idtelefono'];
										
										$qr2.= "WHERE idtelefono='".$idfon."'";
										$query3.="'".$data->sheets[$x]['cells'][$i][$j]."')";
										$insert['up'][$h]=$qr2;
										$insert['select'][$h]=$qr1;
										$insert['insert2'][$h]=$query3;
										$query3= array();
								   }
								   if($name=="clientes"){ 
										$pos = strpos($data->sheets[$x]['cells'][$i][$j], "/");

										if($pos){
											$fecs = explode("/",$data->sheets[$x]['cells'][$i][$j]);
											$fecs = $fecs[2]."-".$fecs[1]."-".$fecs[0];
											$qr2.="fec_nac='$fecs' ";
											$query3.="'$fecs')";
										}else{
											$qr2.="fec_nac='".$data->sheets[$x]['cells'][$i][$j]."' ";
											$query3.="'".addslashes($data->sheets[$x]['cells'][$i][$j])."')";
										}
										$qr2.= "WHERE idcliente='".$data->sheets[$x]['cells'][$i][1]."'";
										//$query3.="'".addslashes($data->sheets[$x]['cells'][$i][$j])."')";
										$insert['up'][$h]=$qr2;
										$insert['select'][$h]=$qr1;
										$insert['i3'][$h]=$query3;
										$query3= array();
								   }
								   if($name=="contactos"){ 
										//$qr2.="observacion='".$data->sheets[$x]['cells'][$i][$j]."' ";
										$qr2.= "WHERE idcontacto='".$data->sheets[$x]['cells'][$i][3]."'";
										$query3.="'".addslashes($data->sheets[$x]['cells'][$i][$j])."')";
										$insert['up'][$h]=$qr2;
										$insert['select'][$h]=$qr1;
										$insert['ic'][$h]=$query3;
										$query3= array();
								   }
								   if($name=="cuentas"){ 
										//$qr1.=" and idcuenta='".$data->sheets[$x]['cells'][$i][2]."-".$data->sheets[$x]['cells'][$i][3]."'" ;
										$qrs2.=" and idperiodo=".$data->sheets[$x]['cells'][$i][8] ;
										
										if($data->sheets[$x]['cells'][$i][1]!=""){
											$qr2.=" impprxpag='".$data->sheets[$x]['cells'][$i][32]."',";
											$qr2.=" observacion2='".addslashes($data->sheets[$x]['cells'][$i][34])."',";
											$qr2.=" obs3='".addslashes($data->sheets[$x]['cells'][$i][35])."' ";
											$qr2.=" WHERE idcuenta='".$data->sheets[$x]['cells'][$i][2]."-".$data->sheets[$x]['cells'][$i][3]."' and idperiodo=".$data->sheets[$x]['cells'][$i][8] ;
										}
										//$query3.="'".$data->sheets[$x]['cells'][$i][$j]."')";
										$qr_c.=" idtipocartera='".$data->sheets[$x]['cells'][$i][33]."' ";
										$qr_c.=" WHERE idcuenta='".$data->sheets[$x]['cells'][$i][2]."-".$data->sheets[$x]['cells'][$i][3]."' ";
										$qr_cta.=" WHERE idcuenta='".$data->sheets[$x]['cells'][$i][2]."-".$data->sheets[$x]['cells'][$i][3]."' ";
										$query3.="'".$data->sheets[$x]['cells'][$i][33]."')";
										$query4.="'".$data->sheets[$x]['cells'][$i][$j]."')";
										$insert['up_c'][$h]=$qr_c;
										$insert['up_cta'][$h]=$qr_cta;
										$insert['up'][$h]=$qr2;
										$insert['select'][$h]=$qr1;
										$insert['select_p'][$h]=$qrs2;
										$insert['i_cuentas'][$h]=$query3;
										$insert['i_cuentas_p'][$h]=$query4;
										$cli[$h]=$data->sheets[$x]['cells'][$i][1];
										$query3= array();
								   }

								   if($name=="cuenta_detalles"){ 
										$fec = explode("/",$data->sheets[$x]['cells'][$i][3]);
										$fec = $fec[2]."-".$fec[1]."-".$fec[0];
										$qr2.="idestado='".$data->sheets[$x]['cells'][$i][$j]."' where idcuentaperiodo='$id' and fecven='".$fec."' and nrocuota='".$data->sheets[$x]['cells'][$i][4]."' ";
                								//$qr2.="idestado='".$data->sheets[$x]['cells'][$i][$j]."' where idcuentaperiodo='$id' and fecven='".$fec."'  ";

										//$qr2.= "WHERE idcliente='".$data->sheets[$x]['cells'][$i][1]."'";
										$query3.="'".$data->sheets[$x]['cells'][$i][$j]."')";
										$insert['up'][$h]=$qr2;
										$insert['select'][$h]=$qr1;
										$insert['i_cuentas_d'][$h]=$query3;
										$query3= array();
								   }
								   
								   
								   if($name=="cuenta_pagos"){ 
										$qr2.=", idestado='".$data->sheets[$x]['cells'][$i][7]."' where idcuenta='".$data->sheets[$x]['cells'][$i][1]."-".$data->sheets[$x]['cells'][$i][2]."' and idperiodo='".$data->sheets[$x]['cells'][$i][3]."' and fecpag='".$fecs."'";
										//$qr2.="observacion='".$data->sheets[$x]['cells'][$i][$j]."' ";
										//$qr2.= "WHERE idcliente='".$data->sheets[$x]['cells'][$i][1]."'";
										$query3.="'".$data->sheets[$x]['cells'][$i][$j]."')";
										$insert['up'][$h]=$qr2;
										$insert['select'][$h]=$qrx;
										$insert['i_cuentas_p2'][$h]=$query3;
										$query3= array();
								   }
									continue;
								}
					 
						if($name=="contactos"){ 
							if($data->sheets[$x]['cells'][$i][1]==""){
								break;
							}
							
							if($j==1){
							
							$qr2.="idcliente='".$data->sheets[$x]['cells'][$i][$j]."',";
							
							$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
							continue;
							}
							if($j==2){
							$qr2.="iddoi='".$data->sheets[$x]['cells'][$i][$j]."',";
							$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
							continue;
							}
							if($j==3){
							$qr1.="'".$data->sheets[$x]['cells'][$i][$j]."'";
							$qr2.="idcontacto='".$data->sheets[$x]['cells'][$i][$j]."',";
							$query3.="'".addslashes ($data->sheets[$x]['cells'][$i][$j])."',";
							continue;
							}
							if($j==4)
							{
							$qr2.="contacto='".$data->sheets[$x]['cells'][$i][$j]."',";
							$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
							continue;
							}
							if($j==5)
							{
							$qr2.="idparentesco='".$data->sheets[$x]['cells'][$i][$j]."',";
							$query3.="'".addslashes($data->sheets[$x]['cells'][$i][$j])."',";
							continue;
							}
							if($j==6)
							{
							$qr2.="telefono='".$data->sheets[$x]['cells'][$i][$j]."',";
							$query3.="'".addslashes($data->sheets[$x]['cells'][$i][$j])."',";
							continue;
							}
							if($j==7)
							{
							$qr2.="observacion='".$data->sheets[$x]['cells'][$i][$j]."',";
							$query3.="'".addslashes($data->sheets[$x]['cells'][$i][$j])."',";
							continue;
							}
							
						}
						
						if($name=="clientes"){ 
							if($data->sheets[$x]['cells'][$i][1]==""){
								break;
							}
							
							if($j==1){
							$qr1.="'".$data->sheets[$x]['cells'][$i][$j]."'";
							$qr2.="idcliente='".$data->sheets[$x]['cells'][$i][$j]."',";
							
							$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
							continue;
							}
							if($j==2){
							$qr2.="iddoi='".$data->sheets[$x]['cells'][$i][$j]."',";
							$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
							continue;
							}
							if($j==3){
							$qr2.="cliente='".$data->sheets[$x]['cells'][$i][$j]."',";
							$query3.="'".addslashes ($data->sheets[$x]['cells'][$i][$j])."',";
							continue;
							}
							if($j==4)
							{
							$qr2.="idpersoneria='".$data->sheets[$x]['cells'][$i][$j]."',";
							$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
							continue;
							}
							if($j==5)
							{
							$qr2.="observacion='".addslashes($data->sheets[$x]['cells'][$i][$j])."',";
							$query3.="'".addslashes($data->sheets[$x]['cells'][$i][$j])."',";
							continue;
							}
							if($j==6)
							{
							//$qr2.="observacion='".addslashes($data->sheets[$x]['cells'][$i][$j])."' ";
								$pos = strpos($data->sheets[$x]['cells'][$i][$j], "/");
								if($pos){
									$fecs = explode("/",$data->sheets[$x]['cells'][$i][$j]);
									$fecs = $fecs[2]."-".$fecs[1]."-".$fecs[0];
									$query3.="'$fecs'";
								}else{
									$query3.="'".$data->sheets[$x]['cells'][$i][$j]."'";
								}

							//$query3.="'".addslashes($data->sheets[$x]['cells'][$i][$j])."'";
							continue;
							}
						}
						
						if($name=="direcciones"){
							if($data->sheets[$x]['cells'][$i][1]==""){
								break;
							}
							if($j==1){
							$qr1.="'".$data->sheets[$x]['cells'][$i][$j]."'";	
							$id=$data->sheets[$x]['cells'][$i][$j];	
							
							$query3.="'$id',";
							continue;
							}
							if($j==2 ){
							$qr1.=" and direccion='".addslashes ($data->sheets[$x]['cells'][$i][$j])."'";
							$qr2.="direccion='".addslashes ($data->sheets[$x]['cells'][$i][$j])."',";
							$query3.="'".addslashes ($data->sheets[$x]['cells'][$i][$j])."',";
							continue;
							}
							if($j==3){
							$qr2.="idorigendireccion='".$data->sheets[$x]['cells'][$i][$j]."',";
							$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
							continue;
							}
							if($j==4){
							$qr2.="idcuadrante='".$data->sheets[$x]['cells'][$i][$j]."',";
							$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
							continue;
							}
							if($j==5){
							$qr2.="coddpto='".$data->sheets[$x]['cells'][$i][$j]."',";
							$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
							continue;
							}
							if($j==6){
							$qr2.="codprov='".$data->sheets[$x]['cells'][$i][$j]."',";
							$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
							continue;
							}
							if($j==7){
							$qr2.="coddist='".$data->sheets[$x]['cells'][$i][$j]."',";
							$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
							continue;
							}
							if($j==8){
							$qr2.="idestado='".$data->sheets[$x]['cells'][$i][$j]."',";
							$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
							continue;
							}
							if($j==9){
							$qr2.="prioridad='".$data->sheets[$x]['cells'][$i][$j]."',";
							$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
							continue;
							}
							if($j==10){
							$qr2.="observacion='".$data->sheets[$x]['cells'][$i][$j]."',";
							$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
							continue;
							}
							
						}
						if($name=="telefonos"){
							if($data->sheets[$x]['cells'][$i][1]==""){
								break;
							}
							if($j==1){
								$qr1.="'".$data->sheets[$x]['cells'][$i][$j]."'";
								$id=$data->sheets[$x]['cells'][$i][$j];	
							$query3.="'$id',";
							continue;
							}
							
							if($j==2){
								$n_tel=strlen($data->sheets[$x]['cells'][$i][$j]);
								
								$cadena1 = $data->sheets[$x]['cells'][$i][$j];
								$patron = "/^[[:digit:]]+$/";/*solo numeros*/
								
								$fono=$data->sheets[$x]['cells'][$i][$j];
									
										$no_tel= array("*", "-", ")", "(", ".", ",", ";", "#", ":", "+");
										$fono=str_replace($no_tel, "", $fono);
								
										if(strlen($fono)<7){
											$query3="";
											continue;
											
										}
										$qr1.=" and telefono='".trim($fono)."'";
										$qr2.="telefono='".trim($fono)."',";
										$query3.="'".trim($fono)."',";
									
										/*if (preg_match($patron, $cadena1)) {
											if($n_tel<7){
												echo "Verique sus telefonos , telefono invalido ";
												return false;
											}
											if($n_tel>9){
												echo "Verique sus telefonos , telefono invalido ";
												return false;
											}
											$qr1.=" and telefono='".$data->sheets[$x]['cells'][$i][$j]."'";
											$qr2.="telefono='".$data->sheets[$x]['cells'][$i][$j]."',";
											$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
										} else {
											echo "Verique sus telefonos , posiblemente exista un caracter invalido en el campo telefono ";
											return false;
										}	*/
											/*$qr1.=" and telefono='".$data->sheets[$x]['cells'][$i][$j]."'";
											$qr2.="telefono='".$data->sheets[$x]['cells'][$i][$j]."',";
											$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";*/
								
									continue;
							}
							
							if($j==3){
							$qr2.="idorigentelefono='".$data->sheets[$x]['cells'][$i][$j]."',";
							$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
							continue;
							}
							if($j==4){
							$qr2.="idestado='".$data->sheets[$x]['cells'][$i][$j]."',";
							$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
							continue;
							}
							if($j==5){
							$qr2.="prioridad='".$data->sheets[$x]['cells'][$i][$j]."',";
							$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
							continue;
							}
							if($j==6){
							$qr2.="observacion='".$data->sheets[$x]['cells'][$i][$j]."',";
							$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
							continue;
							}
							if($j==7){
							//$qr2.="idfuente='".$data->sheets[$x]['cells'][$i][$j]."' ";
							$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
							continue;
							}
						}
						

						if($name=="cuentas"){
							if($data->sheets[$x]['cells'][$i][1]==""){
								break;
							}
							if($j==1){
								$qr1.="'".$data->sheets[$x]['cells'][$i][$j]."'";
								$id=$data->sheets[$x]['cells'][$i][$j];	
								if($id==""){
									break;
								}
							$query3.="'".$data->sheets[$x]['cells'][$i][9]."',";	
							$qr_c.=" idusuario='".$data->sheets[$x]['cells'][$i][9]."', ";
							//$query3.="'1',";//este es el idusuario de cuentas
							$query3.="'$id',";
							$qr_c.=" idcliente='".$id."', ";
							
							$query4.="'".$data->sheets[$x]['cells'][$i][2]."-".$data->sheets[$x]['cells'][$i][3]."',";
							continue;
							}
							
							if($j==2){
								$qr1.=" and idcuenta='".$data->sheets[$x]['cells'][$i][2]."-".$data->sheets[$x]['cells'][$i][3]."'";
								//$qr2.="telefono='".$data->sheets[$x]['cells'][$i][$j]."',";
								$query3.="'".$data->sheets[$x]['cells'][$i][2]."-".$data->sheets[$x]['cells'][$i][3]."',"; /*aa*/
								$qrs2.="'".$data->sheets[$x]['cells'][$i][2]."-".$data->sheets[$x]['cells'][$i][3]."'";
								continue;
							}
							
							if($j==3){
								$qr_c.=" idmoneda='".$data->sheets[$x]['cells'][$i][$j]."', ";
							}
							
							if($j==4){
								//$qr2.="idorigentelefono='".$data->sheets[$x]['cells'][$i][$j]."',";
								//$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
								continue;
							}
							
							if($j==5){
								$qr_c.=" idcartera='".$data->sheets[$x]['cells'][$i][$j]."', ";		
							}
							if($j==6){
							//$qr2.="observacion='".$data->sheets[$x]['cells'][$i][$j]."',";
							$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
							$qr_c.=" idproducto='".$data->sheets[$x]['cells'][$i][$j]."', ";
							continue;
							}
							if($j==7){
							//$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
							$query4.="'".$data->sheets[$x]['cells'][$i][$j]."',";
							$qr2.=" idestado='".$data->sheets[$x]['cells'][$i][$j]."',";
							continue;
							}
							
							if($j==8){
								if($data->sheets[$x]['cells'][$i][$j]=="" or $data->sheets[$x]['cells'][$i][$j]==0){
									$query4.="'".$_SESSION['periodo']."',";
								}else{
									$query4.="'".$data->sheets[$x]['cells'][$i][$j]."',";
								}
								
								$_SESSION['periodo_cd']=$_SESSION['periodo'];
								continue;
							}
							
							if($j==9){
								$qr2.=" idusuario='".$data->sheets[$x]['cells'][$i][$j]."',";
								if($data->sheets[$x]['cells'][$i][$j]=="" or $data->sheets[$x]['cells'][$i][$j]==0){
									$query4.="'2',";
								}else{
									$query4.="'".$data->sheets[$x]['cells'][$i][$j]."',";
								}
							}
							if($j==10){
								$pos = strpos($data->sheets[$x]['cells'][$i][$j], "/");
								if($pos){
									$fecs = explode("/",$data->sheets[$x]['cells'][$i][$j]);
									$fecs = $fecs[2]."-".$fecs[1]."-".$fecs[0];
									$qr2.=" fecven='".$fecs."',";
								}else{
									$qr2.=" fecven='".$fecs."',";
								}
								
							}
							
							if($j==3){
								//$qr_c.=" feccon='".$data->sheets[$x]['cells'][$i][$j]."', ";
							}
							
							if($j==13){
								$qr2.=" diasmora='".$data->sheets[$x]['cells'][$i][$j]."',";
							}
							if($j==14){
								$qr2.=" grupo='".$data->sheets[$x]['cells'][$i][$j]."',";
							}
							if($j==15){
								$qr2.=" ciclo='".$data->sheets[$x]['cells'][$i][$j]."',";
							}
							if($j==16){
								$qr2.=" observacion='".$data->sheets[$x]['cells'][$i][$j]."',";
							}
							if($j==17){
								$qr2.=" impcap='".$data->sheets[$x]['cells'][$i][$j]."',";
							}
							if($j==18){
								$qr2.=" impint='".$data->sheets[$x]['cells'][$i][$j]."',";
							}
							if($j==19){
								$qr2.=" impmor='".$data->sheets[$x]['cells'][$i][$j]."',";
							}
							if($j==20){
								$qr2.=" impotr='".$data->sheets[$x]['cells'][$i][$j]."',";
							}
							if($j==21){
								$qr2.=" imptot='".$data->sheets[$x]['cells'][$i][$j]."',";
							}
							if($j==22){
								$qr2.=" impven='".$data->sheets[$x]['cells'][$i][$j]."',";
							}
							if($j==23){
								$qr2.=" impmin='".$data->sheets[$x]['cells'][$i][$j]."',";
							}
							if($j==24){
								$qr2.=" impdestot='".$data->sheets[$x]['cells'][$i][$j]."',";
							}
							if($j==25){
								$qr2.=" impedesmor='".$data->sheets[$x]['cells'][$i][$j]."',";
							}
							if($j==26){
								$qr2.=" impdesmmo='".$data->sheets[$x]['cells'][$i][$j]."',";
							}
							if($j==27){
								$qr2.=" impfraini='".$data->sheets[$x]['cells'][$i][$j]."',";
							}
							if($j==28){
								$qr2.=" fracuo='".$data->sheets[$x]['cells'][$i][$j]."',";
							}
							if($j==29){
								$qr2.=" impfracpr='".$data->sheets[$x]['cells'][$i][$j]."',";
							}
							if($j==30){
								$qr2.=" impframnt='".$data->sheets[$x]['cells'][$i][$j]."',";
							}
							if($j==31){
								$qr2.=" impcapori='".$data->sheets[$x]['cells'][$i][$j]."',";
							}
							
							if($j==11){
								$pos = strpos($data->sheets[$x]['cells'][$i][$j], "/");
								if($pos){
									$fec = explode("/",$data->sheets[$x]['cells'][$i][$j]);
									$fec = $fec[2]."-".$fec[1]."-".$fec[0];
									$query3.="'".$fec."',";
									$qr_cta.=" feccon='".$fec."' ";/*Lo agregue recien 14/08/2012*/
									continue;
								}
								
							$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
							continue;
							}
							
							if($j==12){
							//$query3.="'".$data->sheets[$x]['cells'][$i][$j]."'";
							$query4.="'".$data->sheets[$x]['cells'][$i][$j]."',";
							continue;
							}
							
							
							$nro= array(3,5);
							if(in_array($j,$nro)){
							$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
							continue;
							}
							$nro= array(10,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34);
							if(in_array($j,$nro)){
								$pos = strpos($data->sheets[$x]['cells'][$i][$j], "/");
								if($pos){
									$fec = explode("/",$data->sheets[$x]['cells'][$i][$j]);
									$fec = $fec[2]."-".$fec[1]."-".$fec[0];
									$query4.="'".$fec."',";
									continue;
								}
								if($j==33){
									//$query4.="'".$data->sheets[$x]['cells'][$i][$j]."'";
									
									continue;
								}else{
									$query4.="'".$data->sheets[$x]['cells'][$i][$j]."',";
								}
								continue;
							}
						}
						
						if($name=="cuenta_detalles"){
							if($data->sheets[$x]['cells'][$i][1]==""){
								break;
							}
							if($j==1){
								$id_cp=$data->sheets[$x]['cells'][$i][$j]."-".$data->sheets[$x]['cells'][$i][2];
								//$periodo=$_SESSION['periodo_cd'];
								//$db->debug=true;
								$ok=$db->Execute("SELECT idcuentaperiodo FROM cuenta_periodos WHERE idcuenta='$id_cp' and idperiodo='22' ");
								$fec = explode("/",$data->sheets[$x]['cells'][$i][3]);
								$fec = $fec[2]."-".$fec[1]."-".$fec[0];
								$id=$ok->fields['idcuentaperiodo'];
								$qr1.="'$id'  and fecven='".$fec."' and nrocuota='".$data->sheets[$x]['cells'][$i][4]."' ";
								//$qr1.="'$id'  and fecven='".$fec."'  ";

								$query3.="'$id',";
								//$query3.="'$id_cp',";
								
								continue;
							}
										
							$nro= array(4,5,6,7,8,9,10);	
								
							if(in_array($j,$nro)){
							//$qr2.="idorigentelefono='".$data->sheets[$x]['cells'][$i][$j]."',";
								//if($j==4){	$qr2.="nrocuota=";	}
								if($j==5){	$qr2.="observacion=";}
								if($j==6){	$qr2.="impcap=";	}
								if($j==7){	$qr2.="impint=";	}
								if($j==8){	$qr2.="impmor=";	}
								if($j==9){	$qr2.="impotr=";	}
								if($j==10){	$qr2.="imptot=";	}
								
							$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
								if($j!=4){$qr2.="'".$data->sheets[$x]['cells'][$i][$j]."',";}
							continue;
							}
							
							$nro= array(2);
							if(in_array($j,$nro)){
							continue;
							}
							if($j==3){
									$pos = strpos($data->sheets[$x]['cells'][$i][$j], "/");
									
									if($pos){
										$fec = explode("/",$data->sheets[$x]['cells'][$i][$j]);
										$fec = $fec[2]."-".$fec[1]."-".$fec[0];
										$query3.="'".$fec."',";
										$qr2.=" fecven='".$fec."',";
										continue;
									}
								continue;
							}
						}
						
						if($name=="cuenta_pagos"){
							if($data->sheets[$x]['cells'][$i][1]==""){
								break;
							}
							if($j==1){
								$id_cp=$data->sheets[$x]['cells'][$i][$j]."-".$data->sheets[$x]['cells'][$i][2];
							
								$ok=$db->Execute($qr1);
								$qrx.="".$id_cp."'";
								$id=$ok->fields[0];
								
									if($id==""){
									//break;
									}
								$query3.="'".$id_cp."',";
								continue;
							}
							
							if($j==3){
								$qrx.=" and idperiodo='".$data->sheets[$x]['cells'][$i][$j]."'";
								
							}
							
							if($j==4){
									$pos = strpos($data->sheets[$x]['cells'][$i][$j], "/");
									if($pos){
										$fec = explode("/",$data->sheets[$x]['cells'][$i][$j]);
										
										$fecs = $fec[2]."-".$fec[1]."-".$fec[0]; /* yyyy - mm - dd*/
										if(!checkdate($fec[1], $fec[0], $fec[2])){ /*$month ,$day ,  $year */
											
											//echo $fec[1]."-".$fec[0]."-".$fec[2]."<br>";
											echo "Verifique su archivo. Introduzca una Fecha Valida";
											$db->Execute("update flag_reportes set flag='0' where reporte='importacion'");
											return false;
										}
										$query3.="'".$fecs."',";
										$qrx.=" and fecpag='".$fecs."'";
										$qr2.=" fecpag='".$fecs."',";
										continue;
										
										
									}else if($pos = strpos($data->sheets[$x]['cells'][$i][$j], "-")){
										$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
										continue;
									}else{
										echo "Formato de fecha invalida";
										$db->Execute("update flag_reportes set flag='0' where reporte='importacion'");
										return false;
									}
									
									
							}
							
							if($j==5){
								$qr2.=" observacion='".$data->sheets[$x]['cells'][$i][$j]."',";
							}
							
							if($j==6){
								$qr2.=" imptot='".$data->sheets[$x]['cells'][$i][$j]."'";
							}
										
							$nro= array(3,4,5,6);							
							if(in_array($j,$nro)){
							//$qr2.="idorigentelefono='".$data->sheets[$x]['cells'][$i][$j]."',";
								
							$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
								continue;
							}
							
							$nro= array(2);
							if(in_array($j,$nro)){
								continue;
							}
						}
		
						}
				}

				
       $num2=count($insert['insert']);
	   $num3=count($insert['insert2']);
	   $num4=count($insert['i3']);
	   $num5=count($insert['i_cuentas']);
	   $num6=count($insert['i_cuentas_d']);
	   $num7=count($insert['i_cuentas_p2']);
	   $num8=count($insert['ic']);

	   $ist=0;
	   $ups=0;
	   $err=0;
		
	   if($name=="clientes"){
		$fp = fopen('cli.txt', 'w');

			//$db->StartTrans();
				for($m=1;$m<=$num4;$m++){

					 $val=$insert['select'][$m];
					 $ok=$db->Execute($val);
					 $var=$ok->fields['idcliente'];
					 if(!$ok->EOF){
							$up=$insert['up'][$m];
								
							$ok=$db->Execute($up);	
							if ($ok == false) {
									++$err;
									$nro_e=$db->ErrorNo();
									$msg_e=$db->ErrorMsg();
									$error= 'error('.$nro_e.') en '.$name.': '.$msg_e;
									fwrite($fp, $error);
									fwrite($fp , chr(13).chr(10));
							}else{ ++$ups;}
					}else if($var==""){
							$sq=$insert['i3'][$m];
								
							$ok=$db->Execute($sq);
							if ($ok == false) {
									++$err;
									$nro_e=$db->ErrorNo();
									$msg_e=$db->ErrorMsg();
									$error= 'error('.$nro_e.') en '.$name.': '.$msg_e;
									fwrite($fp, $error);
									fwrite($fp , chr(13).chr(10));
							}else{ ++$ist;}
					}
				}
			fclose($fp);
			echo "&nbsp;&nbsp;Ingresados ( $ist )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) <br/>";
			echo "Log Errores:<a href='importar/script_imp/cli.txt' target='blank'>Log</a><br/>";	
			//$db->CompleteTrans($ok);
		
		}
		
	   $ist=0;
	   $ups=0;
	   $err=0;
	   echo" <br/>";
	   if($name=="direcciones"){
			$fp = fopen('direcc.txt', 'w');
			//$db->StartTrans();
				for($m=1;$m<=$num2;$m++){
					
					 $val=$insert['select'][$m];
					 $ok=$db->Execute($val);
					 $var=$ok->fields['iddireccion'];
					 if($var!=""){
							$up=$insert['up'][$m];
							$ok=$db->Execute($up);	
							if ($ok == false) {
									++$err;

									$nro_e=$db->ErrorNo();
									$msg_e=$db->ErrorMsg();
									$error= 'idcliente : '.$data->sheets[$x]['cells'][($m+1)][1].' error('.$nro_e.') en '.$name.': '.$msg_e;
									fwrite($fp, $error);
									fwrite($fp , chr(13).chr(10));
							}else{ ++$ups;}
					 }else if($var==""){
							$sq=$insert['insert'][$m];
							$ok=$db->Execute($sq);
							if ($ok == false) {
									++$err;
									$nro_e=$db->ErrorNo();
									$msg_e=$db->ErrorMsg();
									$error= 'idcliente : '.$data->sheets[$x]['cells'][($m+1)][1].' error('.$nro_e.') en '.$name.': '.$msg_e;
									fwrite($fp, $error);
									fwrite($fp , chr(13).chr(10));
							}else{ ++$ist;}
					 }
					 
				}
			fclose($fp);
			echo "&nbsp;&nbsp;Ingresados ( $ist )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) <br/>";
			echo "Log Errores:<a href='importar/script_imp/direcc.txt' target='blank'>Log</a><br/>";	
		    //$db->CompleteTrans($ok);
				$increment=$db->Execute("SELECT  iddireccion FROM direcciones ORDER BY iddireccion DESC LIMIT 0,1");
				$autoin=$increment->fields['iddireccion']+1;
				//$db->Execute("ALTER TABLE `direcciones` AUTO_INCREMENT=$autoin ");
		}
		
	   $ist=0;
	   $ups=0;
	   $err=0;
	   echo" <br/>";
		if($name=="telefonos"){
			$fp = fopen('telf.txt', 'w');
			//$db->StartTrans();
				for($m=1;$m<=$num3;$m++){
					 $val=$insert['select'][$m];
					 $ok=$db->Execute($val);
					 $var=$ok->fields['idcliente'];
									
					 if(!$ok->EOF ){				 
							$up=$insert['up'][$m];
							$ok=$db->Execute($up);
							if ($ok == false) {
									++$err;
									
									$nro_e=$db->ErrorNo();
									$msg_e=$db->ErrorMsg();
									$error= 'idcliente : '.$data->sheets[$x]['cells'][($m+1)][1].' error('.$nro_e.') en '.$name.': '.$msg_e;
									fwrite($fp, $error);
									fwrite($fp , chr(13).chr(10));
							}else{ ++$ups;}
								
					 }else if($var==""){
							$sq=$insert['insert2'][$m];
							//echo $sq."<br>";
							$ok=$db->Execute($sq);
							
							if ($ok == false) {
									++$err;
									$nro_e=$db->ErrorNo();
									$msg_e=$db->ErrorMsg();
									$error= 'idcliente : '.$data->sheets[$x]['cells'][($m+1)][1].' error('.$nro_e.') en '.$name.': '.$msg_e;
									fwrite($fp, $error);
									fwrite($fp , chr(13).chr(10));
							}else{ ++$ist;}
					 }
					 
				}
			fclose($fp);
			echo "&nbsp;&nbsp;Ingresados ( $ist )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) <br/>";
			echo "Log Errores:<a href='importar/script_imp/telf.txt' target='blank'>Log</a><br/>";		
			//$db->CompleteTrans($ok);
				$increment=$db->Execute("SELECT  idtelefono FROM telefonos ORDER BY idtelefono DESC LIMIT 0,1");
				
				$autoin=$increment->fields['idtelefono']+1;
				//$db->Execute("ALTER TABLE `telefonos` AUTO_INCREMENT=$autoin ");
				$db->Execute("UPDATE telefonos SET telefono = REPLACE(telefono, '(', '')");
				$db->Execute("UPDATE telefonos SET telefono = REPLACE(telefono, ')', '')");
				$db->Execute("UPDATE telefonos SET telefono = REPLACE(telefono, '*', '')");
				$db->Execute("UPDATE telefonos SET telefono = REPLACE(telefono, '-', '')");
				$db->Execute("UPDATE telefonos SET telefono = REPLACE(telefono, '.', '')");
				$db->Execute("UPDATE telefonos SET telefono = REPLACE(telefono, ' ', '')");
				$db->debug=false;
		}

		$ist=0;
	    $ups=0;
	    $err=0;
		echo" <br/>";
		if($name=="cuentas"){
			
			$fp = fopen('ctas.txt', 'w');
			//$db->StartTrans();
				for($m=1;$m<=$num5;$m++){
					 $val=$insert['select'][$m];
					 $ok=$db->Execute($val);
					 $var=$ok->fields['idcliente'];
									
					 if(!$ok->EOF ){	
							
							$up2=$insert['up_cta'][$m];
							$ok2=$db->Execute($up2);
					
							$up=$insert['up_c'][$m];
							$ok=$db->Execute($up);
							if($db->ErrorNo()!=0){
								++$err;
								//echo $up."<br/>";
								echo $db->ErrorMsg()."<br/>";
							//	$cli[$m]."<br/>";;
							}else{ ++$ups;}
					 }else if($var==""){
							$sq=$insert['i_cuentas'][$m];
							$ok=$db->Execute($sq);
							if($db->ErrorNo()!=0){
								//echo $insert['i_cuentas'][$m]."   ";
								if($db->ErrorNo()==1452){
									echo  "'".$cli[$m]."<br/>";
								}
								//echo $db->ErrorMsg()."<br/>";
							}
							if ($ok == false) {
									++$err;
									$nro_e=$db->ErrorNo();
									$msg_e=$db->ErrorMsg();
									
								//	$cta=$data->sheets[$x]['cells'][($m+1)][1];
									$error= '('.$nro_e.')error en '.$name.': en fila'.$m.'  '.$msg_e;
									
									fwrite($fp, $error);
									fwrite($fp , chr(13).chr(10));
							}else{ ++$ist;}
					 }
				}
			 fclose($fp);
			 $ist=0;
			   $ups=0;
			   $err=0; 
			
			echo "&nbsp;&nbsp;Ingresados ( $ist )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) <br/>";
			echo "Log Errores:<a href='importar/script_imp/ctas.txt' rel='shadowbox'>Log</a><br/>";	
			//$db->CompleteTrans($ok);
			
			$fp = fopen('ctas_p.txt', 'w');

			//$db->StartTrans();
				for($m=1;$m<=$num5;$m++){
					 $val=$insert['select_p'][$m];
					 $ok=$db->Execute($val);
					 $var=$ok->fields['idcliente'];
									
					 if(!$ok->EOF ){	
							$up=$insert['up'][$m];
							$ok=$db->Execute($up);
							if ($ok == false) {
								++$err;
									$nro_e=$db->ErrorNo();
									$msg_e=$db->ErrorMsg();
									
								//	$cta=$data->sheets[$x]['cells'][($m+1)][1];
									$error= 'error en '.$name.': en fila'.$m.'  '.$msg_e;
									
									fwrite($fp, $error);
									fwrite($fp , chr(13).chr(10));
							}else{ ++$ups;}
					 }else if($var==""){
							$sq=$insert['i_cuentas_p'][$m];
							$ok=$db->Execute($sq);
							if ($ok == false) {
								++$err;
									$nro_e=$db->ErrorNo();
									$msg_e=$db->ErrorMsg();
									
								//	$cta=$data->sheets[$x]['cells'][($m+1)][1];
									$error= 'error en '.$name.': en fila'.$m.'  '.$msg_e;
									
									fwrite($fp, $error);
									fwrite($fp , chr(13).chr(10));
							}else{ ++$ist;}
							
					 }
				}
				
				 fclose($fp);
			echo "Cuentas Periodo :";	 
			echo "&nbsp;&nbsp;Ingresados ( $ist )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) <br/>";
			echo "Log Errores:<a href='importar/script_imp/ctas_p.txt' target='blank' >Log</a><br/>";	
			//$db->CompleteTrans($ok);
				$increment=$db->Execute("SELECT  idcuentaperiodo FROM cuenta_periodos ORDER BY idcuentaperiodo DESC LIMIT 0,1");
				$autoin=$increment->fields['idcuentaperiodo']+1;
				//$db->Execute("ALTER TABLE `cuenta_periodos` AUTO_INCREMENT=$autoin ");
		}
		$ist=0;
			   $ups=0;
			   $err=0; 
		echo" <br/>";	   
		if($name=="cuenta_detalles"){
			


			$db->StartTrans();
				$fp = fopen('verif.txt', 'w');
				for($m=1,$i=0;$m<=$num6;$m++,$i++){
					 $val=$insert['select'][$m];

					 $ok=$db->Execute($val);
					 $var=$ok->fields['idcuentaperiodo'];
									
					 if(!$ok->EOF ){		
							
							$up=$insert['up'][$m];
							//echo $up."<br>";
							
							$ok=$db->Execute($up);
								
							//$ok=true;
							if($ok==false){
								++$err;
							}else{ ++$ups;}
					 }else if($var==""){
							$sq=$insert['i_cuentas_d'][$m];
							//$db->debug=true;
							$ok=$db->Execute($sq);
							//$ok=true;
							if ($ok == false) {
									$nro_e=$db->ErrorNo();
									$msg_e=$db->ErrorMsg();
									
									$cta=$data->sheets[$x]['cells'][($m+1)][1];
									$error= 'error en '.$name.': en fila'.$m.' idcuenta('.$cta.') no existe ';
									
									fwrite($fp, $error);
									fwrite($fp , chr(13).chr(10));
							}else{ ++$ist;}
					 }
				}
				fclose($fp);
				echo "&nbsp;&nbsp;Ingresados ( $ist )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) <br/>";
				echo "Log Errores:<a href='importar/script_imp/verif.txt' target='blank' >Log</a><br/>";
			$db->CompleteTrans($ok);
			$increment=$db->Execute("SELECT  idcuentadetalle FROM cuenta_detalles ORDER BY idcuentadetalle DESC LIMIT 0,1");
			$autoin=$increment->fields['idcuentadetalle']+1;
			//$db->Execute("ALTER TABLE `cuenta_detalle` AUTO_INCREMENT=$autoin ");
		}

		echo" <br/>";
		$ist=0;
			   $ups=0;
			   $err=0; 
		if($name=="cuenta_pagos"){
			$db->debug = true;
			//$db->StartTrans();
				$fp = fopen('cta_pags.txt', 'w');
				for($m=1;$m<=$num7;$m++){
					 $val=$insert['select'][$m];
					 $ok=$db->Execute($val);
					 $var=$ok->fields['idcliente'];
									
					 if(!$ok->EOF ){				 
							$up=$insert['up'][$m];
							$ok=$db->Execute($up);
							if($ok== false){
								++$err;
							}else{ ++$ups;}
					 }else if($var==""){
							$sq=$insert['i_cuentas_p2'][$m];
							$ok=$db->Execute($sq);
							if ($ok == false) {
								++$err;
									$nro_e=$db->ErrorNo();
									$msg_e=$db->ErrorMsg();
									
									$cta=$data->sheets[$x]['cells'][($m+1)][1];
									$error= 'error('.$nro_e.') en '.$name.':  '.$msg_e;
									
									fwrite($fp, $error);
									fwrite($fp , chr(13).chr(10));
							}else{ ++$ist;}
					 }
				}
				fclose($fp);
				echo "&nbsp;&nbsp;Ingresados ( $ist )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) <br/>";
				echo "Log Errores:<a href='importar/script_imp/verif.txt' target='blank' >Log</a><br/>";
			//$db->CompleteTrans($ok);
			$increment=$db->Execute("SELECT  idcuentapago FROM cuenta_pagos ORDER BY idcuentapago DESC LIMIT 0,1");
			$autoin=$increment->fields['idcuentapago']+1;
			//$db->Execute("ALTER TABLE `cuenta_pagos` AUTO_INCREMENT=$autoin ");
		}
		
		if($name=="contactos"){

			$db->debug = true;
			$db->StartTrans();
				for($m=1;$m<=$num8;$m++){
					 $val=$insert['select'][$m];
					 $ok=$db->Execute($val);
					 //$var=$ok->fields['idcontacto'];
					 //$sq=$insert['ic'][$m];	
					 //$ok=$db->Execute($sq);					 
					 if(!$ok->EOF ){				 
						//	$up=$insert['up'][$m];
							//$ok=$db->Execute($up);
								
					 }else if($var==""){
					//		$sq=$insert['ic'][$m];
						//	$ok=$db->Execute($sq);
					 }
				}
			$db->CompleteTrans($ok);
			$increment=$db->Execute("SELECT  idcontacto FROM contactos ORDER BY idcontacto DESC LIMIT 0,1");
			$autoin=$increment->fields['idcontacto']+1;
			$db->Execute("ALTER TABLE `contactos` AUTO_INCREMENT=$autoin ");
		}
		
		
	$w++;
}


//$db->CompleteTrans($ok);
$db->debug=true;
$db->Execute("update flag_reportes set flag='0' where reporte='importacion'");
$db->Close();
$tf=date("H:i:s");

echo "<br/>";
echo "<font color='blue'>Fin de importacion:</font>".$tf;

?>
