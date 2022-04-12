<script>
function op(){
var x=screen.width/2
var y=screen.height/2
var x1=960
var y1=560
var params="'position: absolute, top=" + (600-y1) + ", left=" + (1100-x1) + ", height=" + y1 + ", width=" + x1 + "'" 
window.open("functions/consulta_todo.php?id=&pag=1", "Gestion", params);
}
</script>
<?php 
if(!isset($_SESSION)){
		session_start();
	}
	
if(!isset($_SESSION['campo'])){ ?>

<div id="sidebar" style="float:left">
<?php
	$id_user = $_SESSION['iduser'];
	$id_prove = $_SESSION['prove'];
	$id_cart=$_SESSION['cartera'];
	$periodo=$_SESSION['periodo'];
	$año=Date("Y-m-d");
	$hora=Date("H");
	$sql="SELECT cp.idestado,HOUR(t.fecreg),t.idcliente,t.idtarea,t.fecha,hora,t.tarea,t.idestado,t.usureg,t.idresultado,t.idgestion  
			FROM tareas t
			JOIN  cuentas c ON t.idcliente=c.idcliente and c.idcartera=$id_cart
			JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta AND cp.idperiodo='$periodo' AND cp.idestado='1' 
			WHERE DATE(t.fecha)='$año'  and t.usureg='$id_user' and t.idestado=1 GROUP BY t.idtarea order by t.hora";
	//$sql="SELECT * from tareas where idtarea=0";
	//echo $id_cart;

	$consulta =$db->Execute($sql);
	$t_regist=$consulta->_numOfRows;
?>
                                        <table id="design3" width="100%" ">
                                            <tr>
                                                    <th>Tareas (<?php echo $t_regist; ?>)</th>
                                            </tr>
											<?php 
													while(!$consulta->EOF){
													
											?>
											
											<?php 
													if($consulta->fields['idresultado']==2){		
											?>		
												<tr style='<?php echo "color:#FFF;background-color:#F08080";?>'> 
													<td>
														<a href="index.php?gestion=1&idCl=<?php echo $consulta->fields['idcliente']; ?>&idT=<?php echo $consulta->fields['idtarea']; ?>&idGt=<?php echo $consulta->fields['idgestion']?>"><?php echo $consulta->fields['fecha']." ".$consulta->fields['hora']; ?></a>- <?php echo $consulta->fields['idcliente']; ?><br /><?php if($consulta->fields['hora']>=Date("H:i:s")){echo $consulta->fields['tarea']."(".$consulta->fields['fecha'].")";}?> 
													</td> 	
											<?php 
													}else{
											?>
													<tr style='<?php if($consulta->fields['hora']<=Date("H:i:s")){ echo "color:#808080";}else{echo "background:#FFFFCC";}?>'> 
											<?php 
													}
													if($consulta->fields['idresultado']!=2 ){
											?>
													<td>
														<a href="index.php?gestion=1&idCl=<?php echo $consulta->fields['idcliente']; ?>&idT=<?php echo $consulta->fields['idtarea']; ?>"><?php echo $consulta->fields['fecha']." ".$consulta->fields['hora']; ?></a>- <?php echo $consulta->fields['idcliente']; ?><br /><?php if($consulta->fields['hora']>=Date("H:i:s")){echo $consulta->fields['tarea']."(".$consulta->fields['fecha'].")";}?> 
													</td> 
												</tr> 
											<?php 
													}else if($consulta->fields['idresultado']!=2 ){
											?>		
													<td>
														<a href="index.php?gestion=1&idCl=<?php echo $consulta->fields['idcliente']; ?>&idT=<?php echo $consulta->fields['idtarea']; ?>"><?php echo $consulta->fields['fecha']." ".$consulta->fields['hora']; ?></a>- <?php echo $consulta->fields['idcliente']; ?><br /><?php if($consulta->fields['hora']>=Date("H:i:s")){echo $consulta->fields['tarea']."(".$consulta->fields['fecha'].")";}?> 
													</td> 
												</tr> 
											
											<?php	}	
												$consulta->MoveNext();
												}
											?>
                                         </table>
</div>
<?php }?>
<div id="main_sys" style="float: left; width:auto;">

<?php
		$hora=date("H");
				$id_user=$_SESSION['iduser'];
				/*if($_SESSION['nivel']==2){
					$sql_tot="
								SELECT
									tt.hora,
									COUNT(DISTINCT(tt.idcliente)) tot_cli,
									COUNT(*) tot_ges,
									SUM(tt.idresultado=2) tot_prom,
									SUM(tt.idtipocontactabilidad=1) CD,
									SUM(tt.idtipocontactabilidad=2) CI,
									SUM(tt.idtipocontactabilidad=1 OR tt.idtipocontactabilidad=2) tot_CONT,
									SUM(tt.tipo_fono='C') Cel,
									SUM(tt.tipo_fono='F') Fij,
									COUNT(DISTINCT(tt.hora)) tot_hor
									FROM
									(
										SELECT 	
											CASE
												WHEN telefono LIKE '9%' AND LENGTH(telefono)=9 THEN 'C' ELSE 'F'
											END tipo_fono,
											c.idcliente,g.idcuenta,g.idcontactabilidad,idresultado,
											idjustificacion,fecges,HOUR(horges) hora,tc.idtipocontactabilidad
										FROM gestiones g
										JOIN telefonos t ON g.idtelefono=t.idtelefono
										JOIN contactabilidad ct ON g.idcontactabilidad=ct.idcontactabilidad
										JOIN tipo_contactabilidad tc ON ct.idtipocontactabilidad=tc.idtipocontactabilidad
										JOIN cuentas c ON g.idcuenta=c.idcuenta
										WHERE g.usureg=$id_user
										AND fecges=DATE(NOW())
										-- AND HOUR(horges)=$hora
										GROUP BY g.fecreg
									) AS tt
									-- GROUP BY tt.hora
	
					
							";
					$qr=$db->Execute($sql_tot);
					echo "<div style='position:absolute;right:-1px;top:1px;'>";
					echo "<table style='border: 1px solid black;float:left;' cellspacing=0 cellpadding=0 >";
					echo "<tr>
											<td style='background-color:black;'></td>
											<td style='border-right: 1px solid black;padding-left:4px;padding-right:4px;background-color:chartreuse;'>
												CLIENTES 
											</td>
											<td style='border-right: 1px solid black;padding-left:4px;padding-right:4px;background-color:chartreuse;'>
												GESTIONES
											</td>
											<td style='border-right: 1px solid black;padding-left:4px;padding-right:4px;background-color:chartreuse;'>
												PROMESAS 
											</td>
											<td style='padding-left:4px;padding-right:4px;background-color:chartreuse;'>
												CONTACTOS 
											</td>
									  </tr>";
					
					$t_cli=round(($qr->fields['tot_cli']/$qr->fields['tot_hor']));
					$t_ges=round(($qr->fields['tot_ges']/$qr->fields['tot_hor']));
					$t_pro=round(($qr->fields['tot_prom']/$qr->fields['tot_hor']));
					$t_cont=round(($qr->fields['tot_CONT']/$qr->fields['tot_hor']));
					
					if($t_cli<18){
							$ima_cli="<img src='img/s_rojo.png' title='Debajo del Promedio' />";
					}else if($t_cli==18){
							$ima_cli="<img src='img/s_amarillo.png' title='En el Promedio' />";
					}else if($t_cli>18){
							$ima_cli="<img src='img/s_verde.png' title='Encima del Promedio' />";
					}
					
					if($t_ges<20){
							$ima_gs="<img src='img/s_rojo.png' title='Debajo del Promedio' />";
					}else if($t_ges==20){
							$ima_gs="<img src='img/s_amarillo.png' title='En el Promedio' />";
					}else if($t_ges>20){
							$ima_gs="<img src='img/s_verde.png' title='Encima del Promedio' />";
					}
					
					if($t_pro<1){
							$ima_p="<img src='img/s_rojo.png' title='Debajo del Promedio' />";
					}else if($t_pro==1){
							$ima_p="<img src='img/s_amarillo.png' title='En el Promedio' />";
					}else if($t_pro>1){
							$ima_p="<img src='img/s_verde.png' title='Encima del Promedio' />";
					}
					
					if($t_cont<7){
							$ima_c="<img src='img/s_rojo.png' title='Debajo del Promedio' />";
					}else if($t_cont==7){
							$ima_c="<img src='img/s_amarillo.png' title='En el Promedio' />";
					}else if($t_cont>7){
							$ima_c="<img src='img/s_verde.png' title='Encima del Promedio' />";
					}
					
					while(!$qr->EOF){
						echo "<tr style='border: 1px solid black;'>
									<td align='left' style='background-color:black;color:white;border: 1px solid #6FC44E ;padding-left:4px;padding-right:4px;'>PROMEDIO</td>
									<td align='center' style='border: 1px solid black;'>
										".$t_cli."
										$ima_cli
									</td>
									<td align='center' style='border: 1px solid black;'>
										".$t_ges."
										$ima_gs
									</td>
									<td align='center' style='border: 1px solid black;'>
										".$t_pro."
										$ima_p
									</td>
									<td align='center' style='border: 1px solid black;'>
										".$t_cont."
										$ima_c
									</td>
							  </tr>";
						echo "<tr style='border: 1px solid black;'>
									<td align='left' style='background-color:#7A7A7A;color:white;border: 1px solid black;padding-left:4px;padding-right:4px;'>TOTALES</td>
									<td align='center' style='border: 1px solid black;'>
										".$qr->fields['tot_cli']."
										
									</td>
									<td align='center' style='border: 1px solid black;'>
										".$qr->fields['tot_ges']."
										
									</td>
									<td align='center' style='border: 1px solid black;'>
										".$qr->fields['tot_prom']."
										
									</td>
									<td align='center' style='border: 1px solid black;'>
										".$qr->fields['tot_CONT']."
										
									</td>
							  </tr>";
							 $qr->MoveNext();
					}		
					echo "</table>";
					echo "<img src='img/semaforo.png' alt='Leyenda' style='float:left;' />";
					echo "</div><br/>";
				}*/
				
?>
<table width="780" cellpadding="0" cellspacing="0" border="0" style='margin-top:26px;'>
	<tr>
		<td>
			<table border="0" class="blue">
				<!--<form name="frmDatos">-->
				<tr>
					<td class="tit_bus">Proveedor</td>
                    <td class="tit_bus">Cartera</td>
					 <td class="tit_bus">Tipo Cartera</td>
                    <td class="tit_bus">Resultados</td>
					<td class="tit_bus">N° Documento</td>
					<td class="tit_bus">Nombres</td>
                    <td class="tit_bus">Grupo</td>
					<td class="tit_bus">Ciclo</td>
<td class="tit_bus">Clasi.</td>
<td class="tit_bus">Riesgo</td>
				</tr>
				<tr>
					<td>

						<input type="hidden" name="orden" value="1" />
						<input type="hidden" name="ad" value="a" />
													<?php  
															
															//if($_SERVER['REMOTE_ADDR']=="192.168.50.44"){var_dump($_SESSION);}
															$c="SELECT * FROM proveedores ";
															$l=1;
															if (!isset($_SESSION['admin'])){
																$user_id=$_SESSION['iduser'];
																$var_c=$db->Execute("SELECT idproveedor,idcartera FROM usuarios_carteras WHERE idusuario=$user_id and idestado=1 order by idproveedor");
																
																$tot_ca=array();
																$tot_pca=array();
																$carteras="";
																while(!$var_c->EOF){
																	$idpov=$var_c->fields['idproveedor'];
																	$tot_ca[$l]=$var_c->fields['idproveedor'];
																	$_SESSION['varios'][$idpov]=$var_c->fields['idcartera'];
																	
																	if($l==1){
																		$carteras=$var_c->fields['idcartera'];
																		$_SESSION['varios'][$idpov]=$carteras;
																		$idprov=$var_c->fields['idproveedor'];
																	}else{
																		$carteras=$carteras.",".$var_c->fields['idcartera'];
																		if($idprov!=$var_c->fields['idproveedor']){
																			$carteras=$var_c->fields['idcartera'];
																			$idprov=$var_c->fields['idproveedor'];
																			$_SESSION['varios'][$idpov]=$carteras;
																		}else{
																			$_SESSION['varios'][$idpov]=$carteras;
																		}
																	}
																	
																	$l++;
																	
																	
																	$var_c->MoveNext();
																}
																
																if($_SERVER['REMOTE_ADDR']=="192.168.50.44"){
																	/*echo "<pre>";
																	var_dump($_SESSION['varios'][14]);*/
																}
																
																if(count($tot_ca)==0 or $_SESSION['tnivel']==4){
																	$c.="where idproveedor='$id_prove'";
																}else{
																	$c.="where idproveedor in (";
																	for($i=1;$i<=count($tot_ca);$i++){
																			if($i==count($tot_ca)){
																				$c.=$tot_ca[$i].")";
																				break;
																			}
																			$c.=$tot_ca[$i].",";															
																	}
																}
																
															}
															$opc_p="onchange='cart();'";
															$sele="";
															if($_SESSION['tnivel']==4){
																	$c.="where idproveedor='$id_prove'";
																	$opc_p=" DISABLED";
																	if($_SESSION['prove']!=14){
																		$opc_c=" DISABLED";
																	}
																	$sele="SELECTED";
																	
															}
															$result = $db->Execute($c);
														
													?>
						
						<select id='u_prove' <?php echo $opc_p;?> >
                                                    <option value="">--Seleccionar--</option>
                                                    <?php
														
														while (!$result->EOF) {
															echo "<option value='".$result->fields['idproveedor']."' $sele>".$result->fields['proveedor']."</option>";
															$result->MoveNext();                                    
														}
                                                    ?>
						</select>
                                        </td>
                                        <td>
                                                <select id='u_cart' onchange='r_cart();'  <?php echo $opc_c;?> >
							<option value="">--Seleccionar--</option>
							<?php
														if($_SESSION['tnivel']==4 ){
															
															if($_SESSION['prove']==14){
																$c="Select idcartera, cartera from carteras where idproveedor='".$_SESSION['prove']."' ";
																$sele="";
															}else{
																$c="Select idcartera, cartera from carteras where idcartera='".$_SESSION['cartera']."' ";
															}
															$result = $db->Execute($c);	
															while (!$result->EOF) {
																echo "<option value='".$result->fields['idcartera']."' $sele>".$result->fields['cartera']."</option>";
																$result->MoveNext();                                    
															}
														}
                                                    ?>
						</select>
					</td>
										<td>
											<select id="u_tcart" name="u_tcart">
												<option value="">..Seleccione
												</option>
											</select>
										</td>
                                        <td>
                                           	<select id="r_cartera" >
							<option value="">--Seleccionar--</option>
							<?php
													if($_SESSION['tnivel']==4){	
														$c="SELECT  rc.idresultado,r.resultado FROM resultado_carteras rc
															JOIN carteras c ON rc.idcartera=c.idcartera
															JOIN resultados r ON rc.idresultado=r.idresultado
															WHERE rc.idcartera=".$_SESSION['cartera']." ";
														
														$result = $db->Execute($c);	
														while (!$result->EOF) {
															echo "<option value='".$result->fields['idresultado']."'>".$result->fields['resultado']."</option>";
															$result->MoveNext();                                    
														}
													}
                                                    ?>
						</select>
                                        </td>
										
					<td><input type="text" id="numdoc" value="" size="18" maxlength="18" /></td>
					<td><input type="text" id="name_gs" value="" size="35" maxlength="35" /></td>
										<td><input type="text" id="grup"  value="<?php if (isset($_SESSION['grupo'])){ echo $_SESSION['grupo'];}?>" size="12" maxlength="10" /></td>
										<td><input type="text" id="ciclo" value="<?php if (isset($_SESSION['ciclo'])){ echo $_SESSION['ciclo'];}?>" size="12" maxlength="10" /></td>
                                       <td> <select id='u_clasi' >
                                                    <option value="">--Seleccionar--</option>
							 <option value="0">Normal</option>
							 <option value="1">Cpp</option>
							 <option value="2">Deficiente</option>
							 <option value="3">Dudoso</option>
							 <option value="4">Perdida</option>

                                                    
						</select></td>
					<td> <select id='u_ries' >
                                                    <option value="">--Seleccionar--</option>
							 <option value="1">Alto</option>
							 <option value="2">Medio</option>
							 <option value="3">Bajo</option>
                                                 
						</select></td>     
						<!-- <td><input type="checkbox" name="todo" value="1" /></td>-->
                                        
										<!--<td><a onclick='window.open("functions/consulta_todo.php?id=&pag=1", "ventana_busqueda", "height=600,width=940,status=yes,toolbar=no,scrollbars=yes,menubar=no,location=no");' href="#">Ver</a></td>-->
                                    
				</tr>
				<tr>
					<td valign="middle" colspan="6">
						
					</td>
				</tr>
				<!--</form>-->
			</table>
			<table>
				<tr>
					<td><input id="srch" class='btn' type="button" value="Buscar" onclick="buscar('functions/consulta.php?id=&pag=1&ord_cart=DESC');" /></td>
										
										<td><input class='btn' type="button" value="Ver Todo" onclick='op();' /></td>
					
				</tr>
			</table>
			<?php
				
				
				if( $_SESSION['prove']==14 or $_SESSION['prove']==21 or $_SESSION['prove']==20 or $_SESSION['prove']==22 or $_SESSION['prove']==27){
					echo "<table id='design3' width='30%' style='font-size:9px;'>";
					$id_user=$_SESSION['iduser'];
					$cs="SELECT rs.tipo,COUNT(*) total
							FROM
							(SELECT g.idresultado,r.resultado,g.fecges, 
							CASE
								WHEN r.idresultado=46 THEN 'A'
								WHEN r.idresultado=2 THEN 'A'
								WHEN r.idresultado=47 THEN 'B'
								WHEN r.idresultado=45 THEN 'B'
								WHEN r.idresultado=11 THEN 'B'
								WHEN r.idresultado=23 THEN 'B'
								WHEN r.idresultado=12 THEN 'C'
								WHEN r.idresultado=18 THEN 'C'
								WHEN r.idresultado=17 THEN 'C'
								WHEN r.idresultado=13 THEN 'D'
								WHEN r.idresultado=6 THEN 'D'
							END tipo
							FROM gestiones g
							JOIN cuentas c ON g.idcuenta=c.idcuenta 
							JOIN resultados r ON g.idresultado=r.idresultado
							WHERE g.fecges LIKE '".date("Y-m-d")."' AND  g.usureg=$id_user AND c.idcartera IN (30,45,8,21)
							AND g.peso=(SELECT MIN(peso) FROM gestiones WHERE idcuenta=c.idcuenta  AND fecges='".date("Y-m-d")."')
							GROUP BY c.idcliente ORDER BY r.idresultado) AS rs
							WHERE tipo IS NOT NULL
							GROUP BY rs.tipo ORDER BY 1";
					//echo $cs;
					$qry=$db->Execute($cs);
					$tot_tip= array();
					
					while(!$qry->EOF){
						$tot_tip[$qry->fields['tipo']]=$qry->fields['total'];
						$qry->MoveNext();
					}
					$total=array_sum($tot_tip);
					/*$total_p=	
								number_format(
									(
										number_format((($tot_tip['A'])*(1.6)),2,',','')+
										number_format((($tot_tip['B'])*(1.4)),2,',','')+
										number_format((($tot_tip['C'])*(1.2)),2,',','')+
										number_format((($tot_tip['D'])*(0.5)),2,',','')
									),2,',',''
								);*/
					$total_p=	number_format(
									((($tot_tip['A'])*(1.6))+(($tot_tip['B'])*(1.4))+(($tot_tip['C'])*(1.2))+(($tot_tip['D'])*(0.5))),2,',',''
								);
								
					echo "<tr>
							<td>
								
							</td>
							<td>
								TIPO
							</td>
							<td>
								TOTAL
							</td>
							<td>
								EFECTIVIDAD
							</td>
						</tr>";
					echo	"<tr><td>+</td><td>A</td><td>".$tot_tip['A']."</td><td>".number_format((($tot_tip['A'])*(1.6)),2,',','')."%</td></tr>
								<tr><td></td><td>B</td><td>".$tot_tip['B']."</td><td>".number_format((($tot_tip['B'])*(1.4)),2,',','')."%</td></tr>
								<tr><td>-</td><td>C</td><td>".$tot_tip['C']."</td><td>".number_format((($tot_tip['C'])*(1.2)),2,',','')."%</td></tr>
								<tr><td></td><td>D</td><td>".$tot_tip['D']."</td><td>".number_format((($tot_tip['D'])*(0.5)),2,',','')."%</td></tr>
								<tr><td></td><td>TOTAL</td><td>".$total."</td><td>".$total_p."%</td></tr>
								";
					echo "</table>";
				}	
			?>
<div id="c_gestion">
	<!--aca va el tbody-->
</div>
		</td>
	</tr>
</table>
</div>

<?php if($_SESSION['nivel']!=1 and $_SESSION['nivel']!=5 and $_SESSION['nivel']!=4){?>
<script>
document.getElementById("srch").click();
</script>
<?php }?>