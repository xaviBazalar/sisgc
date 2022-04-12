<?php
	session_start();
	class Login  {
		public $user;
		public $pass;
		public $check;
		public $estado;
		public $bol;
		
		function __construct($user='',$pass='',$check='0',$estado=''){
			$_SESSION['user_p']=$user;
			$_SESSION['pass_p']=$pass;
			$this->user=stripslashes($user);
			$this->pass=md5($pass);
			$this->check=stripslashes($check);
			$this->estado=stripslashes($estado);
		}
		
		public function test($user,$db){
			if($user->user=="") header("Location: login.php");
			else
			$query = $db->Execute("SELECT * FROM usuarios WHERE login='$user->user' AND clave='$user->pass'");
			
			if(!$query->EOF){
				
				if($user->user!=$user->pass && $user->check=="1"){
					$_SESSION['iduser']=$query->fields['idusuario'];
					$_SESSION['user']= $user->user;
					if($query->fields['idnivel']==6){
						$_SESSION['nivel']=1;
					}else{
						$_SESSION['nivel']=$query->fields['idnivel'];
					}
					mysql_free_result($query->_queryID);
					header("Location: change_c_u.php");
					
				}else if(md5($user->user)==$user->pass){
					
					$_SESSION['iduser']=$query->fields['idusuario'];
					$_SESSION['user']= $user->user;
					if($query->fields['idnivel']==6){
						$_SESSION['nivel']=1;
					}else{
						$_SESSION['nivel']=$query->fields['idnivel'];
					}
					mysql_free_result($query->_queryID);
					header("Location: change_c_u.php");
				}else{
					$user->bol="0";
					mysql_free_result($query->_queryID);
					return $user;
				}
			}else{
				mysql_free_result($query->_queryID);
				header("Location: login.php?error=login no existe y/o clave incorrecto");
			}
		}
		
		public function validar($user,$db){
				$query = $db->Execute("SELECT * FROM usuarios WHERE login='$user->user' AND clave='$user->pass'");
				
				$estado=$query->fields['idestado'];
			    $_SESSION['nameu_p']=$query->fields['usuario'];
				
				if($query->fields['flag_p']==0){
					
					$iu=$query->fields['idusuario'];
					$par="p0=0&"; ///:agregar, 1,:editar, 2:borrar
					$par.="p1=".$query->fields['idusuario']."&";        
					$par.="p2=".$query->fields['login']."&";   
					$par.="p3=".$query->fields['usuario']."&"; 
					$par.="p4=".$_SESSION['pass_p']."&"; 
					$par.="p5=1&";				//  1:agente		  2:supervisor			  4:administrador
					$par.="p6=3&";//id campaña
					$par.="p7=";

					$web="http://192.168.50.16/orionc7_core/kob/ws03.php?$par";
					//http://192.168.50.16/orionc7_core/kob/ws03.php?p0=2&p2=886
						//return false;
					$_SESSION['web']=$web;
					$db->Execute("UPDATE usuarios set flag_p='1' where idusuario='$iu'");
					
				}
				
				if($query->fields['enlinea']=="1"){
						mysql_free_result($query->_queryID);	
						header("Location: login.php?error=Usted ya inicio sesion");	
				}else if($query->fields){
					//session_start();
					if($estado == "0"){
						mysql_free_result($query->_queryID);
						header("Location: login.php?error=Usuario Inactivo");
					}else if($estado=="1"){
						
						$año=date("Y");
						$mes=date("m");
						$periodo = $db->Execute("SELECT idperiodo,periodo  FROM periodos WHERE fecini LIKE '$año-$mes%'");
						
						$_SESSION['iduser']=$query->fields['idusuario'];
						
						$_SESSION['user']= $user->user;
						if($query->fields['idnivel']==6){
							$_SESSION['nivel']=1;
						}else{
							$_SESSION['nivel']=$query->fields['idnivel'];
						}
						$_SESSION['periodo']=$periodo->fields['idperiodo'];
						$_SESSION['cartera']=$query->fields['idcartera'];
						$_SESSION['prove']=$query->fields['idproveedor'];
						
						if($_SESSION['nivel']=="3"){
							mysql_free_result($query->_queryID);
							header("Location: index.php?gestion=1&");
						}else{
							if($_SESSION['nivel']==2){
							
								$sql="SELECT c.idcliente,c.cliente
										FROM cuentas cu 
										JOIN clientes c ON c.idcliente=cu.idcliente 
										JOIN carteras cr ON cr.idcartera=cu.idcartera 
										JOIN cuenta_periodos cp ON cp.idcuenta=cu.idcuenta and cp.idestado='1'
										LEFT JOIN gestiones gt ON cp.idcuenta=gt.idcuenta AND YEAR(gt.fecges)='".date("Y")."' AND MONTH(gt.fecreg)='".date("m")."' AND MONTH(gt.fecges)='".date("m")."' 
										WHERE cp.idusuario='".$_SESSION['iduser']."' AND cp.idperiodo='".$_SESSION['periodo']."' AND cu.idcartera='".$_SESSION['cartera']."' AND cp.idestado='1' 
										GROUP BY cu.idcuenta ORDER BY cp.idestado DESC,idgestion ASC,idagente DESC, cp.imptot DESC ,gt.fecreg DESC";
						

								$but_next=$db->Execute($sql);
										
								while(!$but_next->EOF){
									++$z;
									$id_cli_n=$but_next->fields['idcliente'];
									if($z>1){
										$key = array_search($id_cli_n, $_SESSION['dni']);
										if($key!=""){
											$but_next->MoveNext();
											--$z;
											continue;
										}
									}
									
									$_SESSION['dni'][$z]="$id_cli_n";
									$but_next->MoveNext();
								}
						
								mysql_free_result($but_next->_queryID);	

							}
							
							$sql="SELECT a.idactividad,a.actividad from actividad_carteras ac
																	  JOIN actividades a ON ac.idactividad=a.idactividad
																	  JOIN carteras c ON ac.idcartera=c.idcartera
																	  JOIN proveedores p ON c.idproveedor=p.idproveedor
																	  WHERE p.idproveedor='".$_SESSION['prove']."'
																	  AND c.idcartera='".$_SESSION['cartera']."'
																	  and ac.idestado='1'
																	  ";
																	
							$cuenta2=$db->Execute($sql);
							$a=0;
							while(!$cuenta2->EOF){
								++$a;
								$_SESSION['actividad'][$a]=$cuenta2->fields['actividad']."*".$cuenta2->fields['idactividad'];
								$cuenta2->MoveNext();
							}
							mysql_free_result($cuenta2->_queryID);
							//-------------------------------------------------------
							$sql="SELECT r.idcompromisos,r.idresultado,rc.idresultadocartera,r.resultado  FROM resultado_carteras rc
																JOIN resultados r ON rc.idresultado=r.idresultado
																JOIN carteras c ON rc.idcartera=c.idcartera
																JOIN proveedores p ON c.idproveedor=p.idproveedor																																
																JOIN grupo_gestiones gp ON r.idgrupogestion=gp.idgrupogestion 
																WHERE rc.idestado=1 AND r.idestado=1 AND r.flag!=1 
																AND p.idproveedor='".$_SESSION['prove']."' AND c.idcartera='".$_SESSION['cartera']."' ";

							$cuenta2=$db->Execute($sql);
							$a=0;
							while(!$cuenta2->EOF){
									++$a;
									$_SESSION['result'][$a]=$cuenta2->fields['idcompromisos']."-".$cuenta2->fields['idresultado']."-".$cuenta2->fields['idresultadocartera']."*".utf8_encode($cuenta2->fields['resultado']);
									$cuenta2->MoveNext();
							}
							mysql_free_result($cuenta2->_queryID);
							//-----------------------------------------------------------
							
							 $sql="SELECT co.idcontactabilidad,co.contactabilidad
																				FROM contactabilidad_carteras cc
																				JOIN contactabilidad co ON cc.idcontactabilidad=co.idcontactabilidad
																				JOIN carteras c ON cc.idcartera=c.idcartera
																				JOIN proveedores p ON c.idproveedor=p.idproveedor																																
																				WHERE cc.idestado=1 AND co.idestado=1  AND p.idproveedor='".$_SESSION['prove']."'
																				GROUP BY co.idcontactabilidad";
							$tpc=$db->Execute($sql);
							$a=0;
							while(!$tpc->EOF){
								++$a;
								$_SESSION['contacto'][$a]=$tpc->fields['idcontactabilidad']."*".$tpc->fields['contactabilidad'];
								$tpc->MoveNext();
							}
							
							mysql_free_result($tpc->_queryID);
							
							//--------------------------------------------------------------
							
							
							$sql=$db->Execute("select idvalidaciones,validaciones from validaciones where idestado=1 order by idvalidaciones");
							$a=0;
							while(!$sql->EOF){
								++$a;
								$_SESSION['validacion'][$a]=$sql->fields['idvalidaciones']."*".$sql->fields['validaciones'];
								$sql->MoveNext();	
							}
							mysql_free_result($sql->_queryID);
							
							//----------------------------------------------------------------
							
							$sql=$db->Execute("select idorigendireccion,origendireccion from origen_direcciones where idestado=1 order by origendireccion");
							$a=0;
							while(!$sql->EOF){
								++$a;
								$_SESSION['ori_direcc'][$a]=$sql->fields['idorigendireccion']."*".$sql->fields['origendireccion'];
								$sql->MoveNext();	
							}
							mysql_free_result($sql->_queryID);	

							//-----------------------------------------------------------------

							$dpto = $db->Execute("SELECT coddpto,nombre FROM ubigeos  WHERE codprov=00 AND coddist=00");
							$a=0;
							while(!$dpto->EOF){
								++$a;
								$_SESSION['dpto'][$a]=$dpto->fields['coddpto']."*".$dpto->fields['nombre'];
								$dpto->MoveNext();
							}
							mysql_free_result($dpto->_queryID);	
							
							//-----------------------------------------------------------------
							
							$sql=$db->Execute("select idorigentelefono,origentelefono from origen_telefonos where idestado=1 order by origentelefono");
							$a=0;
							while(!$sql->EOF){
								++$a;
								$_SESSION['ori_tel'][$a]=$sql->fields['idorigentelefono']."*".$sql->fields['origentelefono'];
								$sql->MoveNext();	
							}
							mysql_free_result($sql->_queryID);	
							
							//-----------------------------------------------------------------
							
							$sql=$db->Execute("select iddoi,doi from doi");
							$a=0;
							while(!$sql->EOF){
								++$a;
								$_SESSION['doi'][$a]=$sql->fields['iddoi']."*".$sql->fields['doi'];
								$sql->MoveNext();	
							}
							mysql_free_result($sql->_queryID);	
							
							//-----------------------------------------------------------------
							
							$sql=$db->Execute("select idparentesco,parentescos from parentescos");
							$a=0;
							while(!$sql->EOF){
								++$a;
								$_SESSION['parentesco'][$a]=$sql->fields['idparentesco']."*".$sql->fields['parentescos'];
								$sql->MoveNext();	
							}
							mysql_free_result($sql->_queryID);	
							
							//-----------------------------------------------------------------
							
							$sql=$db->Execute("select idorigenemail,origenemail from origen_emails where idestado=1 order by idorigenemail");
							$a=0;
							while(!$sql->EOF){
								++$a;
								$_SESSION['ori_mail'][$a]=$sql->fields['idorigenemail']."*".$sql->fields['origenemail'];
								$sql->MoveNext();	
							}
							mysql_free_result($sql->_queryID);
							
							//-----------------------------------------------------------------
							/*if(isset($_SESSION['campo'])){
									if($cuenta2->fields['idactividad']=="4"){
										echo "<option value='".$cuenta2->fields['idactividad']."' SELECTED >".$cuenta2->fields['actividad']."</option>";
									}
									$cuenta2->MoveNext();	
							}*/
								
							mysql_free_result($query->_queryID);
							header("Location: index.php");
						}
					}
				}else{
					mysql_free_result($query->_queryID);
					header("Location: login.php?error=login no existe y/o clave incorrecto");
				}
		}
	}

		/*$new_stado=$query->fields['idestado'];
		$online=$query->fields['enlinea'];
		
        if($online=="1") {
			header("Location: login.php?error=Usted ya Inicio Sesion en Otro Lugar");
			return false;
		}   */
    
?>