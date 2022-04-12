<?php
	include '../define_con.php';
	include 'fp.php';
	session_start();
		if($_SERVER['REMOTE_ADDR']=="192.168.40.245"){$db->debug=true;}
	//$db->debug=true;
	$user= $_SESSION['iduser'];	
	if(isset($_GET)){
		if($_GET['tipo']=="mail"){
			//$db->debug=true;
			$id = $_GET['id'];
			$mail = $_GET['mail'];
			$ori = $_GET['ori'];
			$ft = $_GET['ft'];
			$obs = $_GET['obs'];
			$prio = $_GET['prio'];

			if($prio =="1"){
				$db->Execute("UPDATE emails SET prioridad='0' where idcliente='$id'");
			}
			
			$sql="INSERT into emails(idfuente,idorigenemail,idcliente,email,prioridad,observacion,usureg)
				 VALUES('$ft','$ori','$id','$mail','$prio','$obs','$user') ";

			$query= $db->Execute($sql);
			if($query){
				if($query->EOF){
					echo "ok";
				}else{
					echo "false";
				}
			}


			//mysql_free_result($query->_queryID);
		}
		//------------------------------------------------------------------------------------------
		if($_GET['tipo']=="dir"){
			//$db->debug=true;
			$ori = $_GET['ori'];
			$prio = $_GET['prio'];
			$tipo = $_GET['tipod'];
			$dpto = $_GET['dpto'];
			$prov = $_GET['prov'];
			$dist = $_GET['dist'];
			$dir = $_GET['dir'];
			$cdr = $_GET['cdr'];
			$idcl= $_GET['id'];
			$ft = $_GET['ft'];
			$obs = $_GET['obs'];
			if($prio =="1"){
				$db->Execute("UPDATE direcciones SET prioridad='0' where idcliente='$idcl'");
			}
			
			$sql="INSERT into direcciones(usureg,idorigendireccion,idcliente,idcuadrante,direccion,coddpto,codprov,coddist,prioridad,idfuente,observacion)";
				
			$sql.=" VALUES('$user','$ori','$idcl','$cdr','$dir','$dpto','$prov','$dist','$prio','$ft','$obs') ";
			
			$query= $db->Execute($sql);
			if($query){
				if($query->EOF){
				echo "ok";
				}else{
				echo "false";
				}
			}
			//return false;
			//mysql_free_result($query->_queryID);
		}
		//------------------------------------------------------------------------------------------
		if($_GET['tipo']=="tel"){
			$ft = $_GET['ft'];
			$nro_t = trim($_GET['nrot']);
			$or_t= $_GET['o_t'];
			$pr_t = $_GET['p_t'];
			
			$tp_t = $_GET['t_t'];
			$d_t= $_GET['d_t'];
			$ob_t = $_GET['ob_t'];
			$idcl= $_GET['id'];
					
			if($pr_t=="1"){
				$db->Execute("UPDATE telefonos SET prioridad='0' where idcliente='$idcl'");
			}
			
			
			
			$sql="INSERT into telefonos(usureg,idorigentelefono,idcliente,telefono,prioridad,idfuente";
				if(isset($_GET['ob_t'])){
					$obs = $_GET['ob_t'];
					$sql.=",observacion)";
				}else{
					$sql.=")";
				}
				
				$no_tel= array("*", "-", ")", "(", ".", ",", ";", "#", ":", "+");
				$nro_t=str_replace($no_tel, "", $nro_t);	
					
				
				
			$sql.=" VALUES('$user','$or_t','$idcl','$nro_t','$pr_t','$ft','$ob_t') ";
			
			$query= $db->Execute($sql);
			if($query){
				if($query->EOF){
				echo "ok";
				}else{
				echo "false";
				}
			}
			//mysql_free_result($query->_queryID);
		}
		//------------------------------------------------------------------------------------------
		if($_GET['tipo']=="cont"){
			$name=$_GET['name'];
			$mail=$_GET['mail'];
			$fono=$_GET['fono'];
			$anex=$_GET['anex'];
			$doi=$_GET['doi'];
			$nrod=$_GET['nrod'];
			$parent=$_GET['paren'];
			$obs=$_GET['obs'];
			$idcl= $_GET['id'];
			
			if($_GET['flag']==0){
				$sql="INSERT into contactos(idcontacto,usureg,idparentesco,iddoi,idcliente,contacto,email,telefono,observacion)";
				$sql.=" VALUES('$nrod','$user','$parent','$doi','$idcl','$name','$mail','$fono-$anex','$obs') ";
			}else{
				$area=$_GET['area'];
				$sql="INSERT into contactos(idcontacto,usureg,idparentesco,iddoi,idcliente,contacto,email,telefono,anexo,area,observacion)";
				$sql.=" VALUES('$nrod','$user','$parent','$doi','$idcl','$name','$mail','$fono','$anex','$area','$obs') ";
			}
		
			$query= $db->Execute($sql);
			if($query){
				if($query->EOF){
					echo "ok";
				}else{
					echo "false";
				}
			}
			//mysql_free_result($query->_queryID);

		}
		//------------------------------------------------------------------------------------------
		 if($_GET['tipo']=="gest"){
		 
			if(isset($_GET['idcliente'])){
				$idcliente=$_GET['idcliente'];
			}else{
				$idcliente=$_GET['id'];
			}
			$dat=date("Y-m-d");
			$db->Execute("update clientes set u_fecges='$dat' where idcliente='$idcliente' ");
			
			if(isset($_GET['editid2']) && $_GET['editid2']==1){
				$db_pre=  &ADONewConnection('mysql');
				$db_pre->Connect("192.168.50.16", "kobbases", "kob_210911", "orionc7_bases");
				
				$d_ges=$_GET['d_ges'];
				$cont_pre=$_GET['idcliente'];
				//$justi= array(109,110,111,114,115);
				$justi= array(90,91,92,93,94,95,96,97,98,99,100,101,102,103,109,110,111,112,113,114,115,116,117,119,120,121,122,153,155,173,174,175,180,181,214,279);
				if(in_array($d_ges,$justi)){
					$db_pre->Execute("UPDATE ori_base SET FlagContacto=0 WHERE   Contacto='$cont_pre'");
					$db->Execute("Insert into debug_pre (consulta) values('UPDATE ori_base SET FlagContacto=0 WHERE   Contacto=$cont_pre ')");
				}
			}
			//file_get_contents('http://192.168.50.16/orionc7_core/kob/ws05.php?id='.$user);
			
			/*http_request('GET', '192.168.50.16', 80, 
						'/orionc7_core/kob/ws05.php', 
						array('id' => $user), 
						array(), 
						array('cookie1' => 'v_cookie1'), 
						array('X-My-Header' => 'My Value'));*/
			if(isset($_GET['idpredi']) && $_GET['idpredi']!=""){
				$db_pre=  &ADONewConnection('mysql');
				$db_pre->Connect("192.168.50.16", "kobbases", "kob_210911", "orionc7_bases");
				
				$nro_1= array(53,52,51,46,2,6,1,56,47,7,10,9,17); // cod 1
				$nro_2= array(55,54,11); // cod 2
				$nro_3= array(15,13,50,48,12,57,58,59,60,61,62,63); // cod 3
				
				
				
				$r_ges=$_GET['r_ges'];
				if($r_ges!=2 && $r_ges!=12){
					$r_ges=explode("-",$r_ges);
					$r_ges=$r_ges[1];
				}
					if(in_array($r_ges,$nro_1)){$cod=1;}
					if(in_array($r_ges,$nro_2)){$cod=2;}
					if(in_array($r_ges,$nro_3)){$cod=3;}
							
				$id_predictivo=$_GET['idpredi'];
				
				$db_pre->Execute("Update ori_base set contact='$cod' where id_ori_base='$id_predictivo' ");
			}		
			
			if(isset($_GET['t_estado']) && $_GET['t_estado']=="1"){
				$idT=$_GET['idtarea'];
				$up="Update tareas SET idestado='0' where idtarea='$idT' ";
				$db->Execute($up);
			}
			
			if(isset($_GET['id_gest']) && $_GET['id_gest']!=""){
				$id_gest=$_GET['id_gest'];
				$sql="UPDATE gestiones SET impcomp='0' where idgestion='$id_gest'";
				$consulta =$db->Execute($sql);
			}
			
			$cta=$_GET['cta'];
				
			$fges=$_GET['fges'];
			$r_ges=$_GET['r_ges'];
			if($r_ges!=2 && $r_ges!=12 && $r_ges!=13){
				$r_ges=explode("-",$r_ges);
				$r_ges=$r_ges[1];
			}
			
			if(strpos($r_ges,'-')){
				$r_ges=explode("-",$r_ges);
				$r_ges=$r_ges[1];
			}
			$d_ges=$_GET['d_ges'];
			$f_comp=$_GET['f_comp'];
			$i_compg=$_GET['i_compg'];
			$in_gest=$_GET['ind_gest'];
			$tf_ges=$_GET['tf_ges'];
			$dr_ges=$_GET['dr_ges'];
			if($_GET['val_dir']!=""){
				$sql_d="UPDATE direcciones set idvalidacion=".$_GET['val_dir']." where iddireccion='$dr_ges' ";
				$query= $db->Execute($sql_d);
				//mysql_free_result($query->_queryID);
			}
			
			$tc_ges=$_GET['tc_ges'];
			$o_ges=htmlspecialchars(addslashes($_GET['o_ges']));
			$mail=$_GET['mail'];
			$hora=date("H:i:s");
			//$db->debug=true;
			
			if($_GET['encuesta']!=""){
				$encuesta=explode("*",$_GET['encuesta']);
				if(count($encuesta)>8){
					$sql_enc="INSERT INTO encuesta_claro2 
					(
					p1, 
					p2, 
					p3, 
					p4, 
					p5, 
					p6, 
					p7, 
					p8,
					p9,
					obs1,
					obs3,
					obs9,
					idcliente,
					idcuenta,
					idusuario
					)
					VALUES
					( 
					'".$encuesta[0]."', 
					'".$encuesta[1]."', 
					'".$encuesta[2]."', 
					'".$encuesta[3]."', 
					'".$encuesta[4]."', 
					'".$encuesta[5]."', 
					'".$encuesta[6]."', 
					'".$encuesta[7]."', 
					'".$encuesta[8]."',
					'".$encuesta[9]."',
					'".$encuesta[10]."', 	
					'".$encuesta[11]."', 		
					'".$_GET['idcliente']."', 
					'".$cta."',
					'$user'
					);";
				}else if(count($encuesta)==8){
					$sql_enc="INSERT INTO encuesta_claro 
					(
					p1, 
					p2, 
					p3, 
					p4, 
					p5, 
					p6, 
					p7, 
					p8, 
					idcliente,
					idcuenta,
					idusuario
					)
					VALUES
					( 
					'".$encuesta[0]."', 
					'".$encuesta[1]."', 
					'".$encuesta[2]."', 
					'".$encuesta[3]."', 
					'".$encuesta[4]."', 
					'".$encuesta[5]."', 
					'".$encuesta[6]."', 
					'".$encuesta[7]."', 
					'".$_GET['idcliente']."', 
					'".$cta."',
					'$user'
					);";
				}else if(count($encuesta)==5){
					$sql_enc="INSERT INTO encuesta_tn 
					(
					p1, 
					p2, 
					p3, 
					p4, 
					nr3, 
					idcliente,
					idcuenta,
					idusuario
					)
					VALUES
					( 
					'".$encuesta[0]."', 
					'".$encuesta[1]."', 
					'".$encuesta[2]."', 
					'".$encuesta[3]."', 
					'".$encuesta[4]."', 
					'".$_GET['idcliente']."', 
					'".$cta."',
					'$user'
					);";
					
				}
					$db->Execute($sql_enc);
				
			}
			
			$sql="INSERT into gestiones(idemail,idcuenta,idcontactabilidad,idresultado,idjustificacion,observacion,fecges,impcomp,feccomp,idactividad,idtelefono,iddireccion,usureg,horges,peso)";
				$pos = strpos($cta, ",");
					if($pos){
						$cta = explode(",",$cta);
						$total=count($cta);
							for($i=0;$i<$total;$i++){
									$up_ct_ufg="UPDATE cuentas SET u_fecges='$fges' WHERE idcuenta='".$cta[$i]."'";
									$db->Execute($up_ct_ufg); 
								if($i==0){
									$sql="INSERT into gestiones(idemail,idcuenta,idcontactabilidad,idresultado,idjustificacion,observacion,fecges,impcomp,feccomp,idactividad,idtelefono,iddireccion,usureg,horges,peso)";
									$sql.=" VALUES('$mail','".$cta[$i]."','$tc_ges','$r_ges','$d_ges','$o_ges','$fges','$i_compg','$f_comp','$in_gest','$tf_ges','$dr_ges','$user','$hora',(select peso from justificaciones where idjustificacion='$d_ges')) ";
									//$db->Execute("UPDATE cuentas SET u_fecges='$fges' WHERE idcuenta='".$cta[$i]."'");  
									$db->Execute($up_ct_ufg); 
									
									if($_GET['fec_t']!=""){									
										$sql_tarea="INSERT INTO tareas (idcliente,idresultado,idgestion,fecha,hora,tarea,usureg) 
											  VALUES ('".$_GET['id']."','$r_ges','".$_GET['id_gest']."','".$_GET['fec_t']."','".$_GET['hor_t']."','".$_GET['com_t']."','".$_SESSION['iduser']."')";
										$c_tarea =$db->Execute($sql_tarea);
									}
									
									if(isset($_GET['det_cta']) and $_GET['det_cta']!=""){
										$obs_det=$_GET['det_cta'];
										$sql_det_val=$sql." VALUES('$mail','".$cta[$i]."','$tc_ges','6','189','$obs_det','$fges','$i_compg','$f_comp','$in_gest','$tf_ges','$dr_ges','$user','$hora',(select peso from justificaciones where idjustificacion='$d_ges')) ";
										$db->Execute($sql_det_val);	
										//$db->Execute("UPDATE cuentas SET u_fecges='$fges' WHERE idcuenta='".$cta[$i]."'");  /**/
										$db->Execute($up_ct_ufg); 	
									}
								}
								
								if($i>0){
									$sql.=" ,('$mail','".$cta[$i]."','$tc_ges','$r_ges','$d_ges','$o_ges','$fges','$i_compg','$f_comp','$in_gest','$tf_ges','$dr_ges','$user','$hora',(select peso from justificaciones where idjustificacion='$d_ges')) ";
									//$db->Execute("UPDATE cuentas SET u_fecges='$fges' WHERE idcuenta='".$cta[$i]."'");  
									$db->Execute($up_ct_ufg); 
								}
							
									if($i==$total-1){
										
										$query= $db->Execute($sql);
										//$db->Execute("UPDATE cuentas SET u_fecges='$fges' WHERE idcuenta='".$cta[$i]."'"); 
										$db->Execute($up_ct_ufg); 
										if($query->EOF){
											echo "ok";
										}else{
											echo "false";
										}
										//mysql_free_result($query->_queryID);
									}
							}
					}else{
						if(isset($_GET['det_cta']) and $_GET['det_cta']!=""){
							$obs_det=$_GET['det_cta'];
							$sql_det_val=$sql." VALUES('$mail','$cta','$tc_ges','6','189','$obs_det','$fges','$i_compg','$f_comp','$in_gest','$tf_ges','$dr_ges','$user','$hora',(select peso from justificaciones where idjustificacion='$d_ges')) ";
							$db->Execute($sql_det_val);	
							$up_ct_ufg="UPDATE cuentas SET u_fecges='$fges' WHERE idcuenta='".$cta[$i]."'";
							$db->Execute($up_ct_ufg); 
							//$db->Execute("UPDATE cuentas SET u_fecges='$fges' WHERE idcuenta='$cta[$i]'");  /**/
						}
						
						$sql.=" VALUES('$mail','$cta','$tc_ges','$r_ges','$d_ges','$o_ges','$fges','$i_compg','$f_comp','$in_gest','$tf_ges','$dr_ges','$user','$hora',(select peso from justificaciones where idjustificacion='$d_ges')) ";
						
						$db->Execute("UPDATE cuentas SET u_fecges='$fges' WHERE idcuenta='$cta'");
						$query= $db->Execute($sql);
						
						
						
						if($query){
							if($query->EOF){
								if($r_ges==2){
									$query2= $db->Execute("SELECT idgestion from gestiones where idcuenta='$cta' and idresultado='$r_ges' and idtelefono='$tf_ges' ORDER BY idgestion DESC ");
									
									$id_user = $_SESSION['iduser'];
									$id=$_GET['id'];
									$id_gest=$query2->fields['idgestion'];
									//$r_ges=$_GET['idre'];
									//$r_ges=explode("-",$r_ges);
									//$r_ges=$r_ges[1];
									$fec_t=$_GET['fec_t'];
									$hor_t=$_GET['hor_t'];
									$com_t=$_GET['com_t'];
									
									$sql="INSERT INTO tareas (idcliente,idresultado,idgestion,fecha,hora,tarea,usureg) 
										  VALUES ('$id','$r_ges','$id_gest','$fec_t','$hor_t','$com_t','$id_user')";

									
									$consulta =$db->Execute($sql);
									echo "ok";
								}else if($r_ges!=1){
									$id_user = $_SESSION['iduser'];
									$id=$_GET['id'];
									$id_gest=$_GET['id_gest'];
									$r_ges=$_GET['idre'];
									$r_ges=explode("-",$r_ges);
									$r_ges=$r_ges[1];
									$fec_t=$_GET['fec_t'];
									$hor_t=$_GET['hor_t'];
									$com_t=$_GET['com_t'];
									
									$sql="INSERT INTO tareas (idcliente,idresultado,idgestion,fecha,hora,tarea,usureg) 
										  VALUES ('$id','$r_ges','$id_gest','$fec_t','$hor_t','$com_t','$id_user')";
									
									$consulta =$db->Execute($sql);
									//$_SESSION['error']=$sql;
									//mysql_free_result($consulta->_queryID);
									echo "ok";
								}
							}else{
								echo "false";
							}
						}
						//mysql_free_result($query->_queryID);
						//mysql_free_result($consulta->_queryID);
						$db->Close();
					}
		 }
	 }
	 	//------------------------------------------------------------------------------------------
	if(isset($_POST['tipo'])){
		if($_POST['tipo']=="campo"){
			//$db->debug=true;
			$agen=$_POST['value1'];
			$id_cl=$_POST['value2'];
			$cl=$_POST['value3'];
			$fec=$_POST['value4'];
			$hor=$_POST['value5'];
			$rs_ges=$_POST['value6'];
			//$pa=$_GET['value7'];
			$ubi=$_POST['value8'];
			$dir=$_POST['value9'];
			$t_pre=$_POST['value10'];
			$m_pre=$_POST['value11'];
			$n_piso=$_POST['value12'];
			$c_prd=$_POST['value13'];
			$dir_t=$_POST['value14'];
			$obs=$_POST['value15'];
			$tc_ges=$_POST['value16'];
			$cart=$_POST['value17'];
			$feccon=$_POST['value18'];
			$imp_comp=$_POST['value19'];
			if($feccon=="0"){$feccon="0000-00-00";}
			//$db->debug=true;
			if($dir_t!=""){
			$db->Execute("UPDATE direcciones SET idvalidacion='$dir_t' WHERE iddireccion='$dir'");
			}
			$cuenta2=$db->Execute("SELECT c.idcuenta,ct.idcartera FROM cuentas c
								JOIN carteras ct ON c.idcartera=ct.idcartera
								WHERE c.idcliente='$id_cl' AND ct.idcartera='$cart' ");
			
			$total = $cuenta2->_numOfRows;
				while(!$cuenta2->EOF){
					$cta = $cuenta2->fields['idcuenta'];
						$sql="INSERT into gestiones(idcuenta,idcontactabilidad,idresultado,observacion,fecges,idactividad,iddireccion,usureg,horges,idagente,idubicabilidad,idpredio,idmaterial_predio,idnro_pisos,idcolor_pared,feccomp,impcomp)";
							$sql.=" VALUES('".$cta."','$tc_ges','$rs_ges','$obs','$fec','4','$dir','$user','$hor','$agen','$ubi','$t_pre','$m_pre','$n_piso','$c_prd','$feccon','$imp_comp') ";
							//if($_SERVER['REMOTE_ADDR']=="192.168.50.44"){echo $sql;return false;}
							$query= $db->Execute($sql);
								
							if($query->EOF){echo "ok";}
							else{echo "false";}
					$cuenta2->MoveNext();
				}
				//mysql_free_result($cuenta2->_queryID);
				$db->Close();
		 }
	 }
	// file_get_contents('http://192.168.50.16/orionc7_core/kob/ws05.php?id='.$user);
	
	 $db->Close();
	if(isset($_GET['idcliente'])){
		$idcliente=$_GET['idcliente'];
	}else{
		$idcliente=$_GET['id'];
	}
	

	// if($_SERVER['REMOTE_ADDR']=="192.168.50.44"){return false;}
	 if($_GET['USER_AUTH_TOKEN_APPLICATION']!=""){$tok="&USER_AUTH_TOKEN_APPLICATION=".$_GET['USER_AUTH_TOKEN_APPLICATION'];}else{$tok="";}
	 header ("Location: ../index.php?gestion=1&idCl=$idcliente".$tok);
	
?>