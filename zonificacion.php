<?php
//session_start();
/*if (!isset($_SESSION["user"]) || $_SESSION["user"] == "") {
	header("Location: login.php");
}*/
//require 'define_con.php'; esta lne la modifique porque ya noesta con ajax

if (!isset($_GET["tipo"]) && !isset($_GET["parametros"])) {
	header("Location: login.php");
	return false;
}

$n = 0;
$pag=0;// x defecto
    $r_pag=20;// total registros x pag
	
	if($_GET['pag']!=1){
		$n=$_GET['pag']-1;
		$n=$n*$r_pag;
	}
	if(!isset($coddpto))
		$coddpto="";
	if(!isset($codpro))
		$codpro="";
	if(!isset($coddist))
		$coddist="";
	
if(isset($_GET['parametros'])){
     $id_param=$_GET['parametros'];
      switch($id_param){
      case "ubig":
				
             echo "<div id=\"ykBody\">
                    <center>";
                    $r1 = $db->Execute("SELECT * FROM ubigeos  WHERE codprov=00 AND coddist=00");
					
             echo  "Departamento:<select id='dpto' onchange='dpto();'><option value=''>-Todos-</option>";
                    while (!$r1->EOF) {
							if(isset($_GET['coddpto']) && $_GET['coddpto']!="" && $_GET['coddpto']==$r1->fields['coddpto'] ){
								$coddpto=$_GET['coddpto'];
								echo "<option onSelectStart='dpto();'  value='".$r1->fields['coddpto']."' SELECTED> ";
								echo utf8_encode($r1->fields['nombre'])."</option>";
								$r1->MoveNext();
                            }
                     echo "<option value='".$r1->fields['coddpto']."'>".utf8_encode($r1->fields['nombre'])."</option>";
                     $r1->MoveNext();
                    }
             echo  "</select>";
             echo " Provincia:<select id='prov' onchange='dist();'><option value=''>-Todos-</option>";
                    if(isset($_GET['codprov']) && $_GET['coddpto']!=""){
						 $codpro=$_GET['codprov'];
						 $r = $db->Execute("SELECT * FROM ubigeos  WHERE coddpto=$coddpto AND codprov!=00 GROUP BY codprov");
						 
						 while (!$r->EOF){
								if($r->fields['codprov']==$codpro){
									echo "<option   value='".$r->fields['codprov']."' SELECTED>".utf8_encode($r->fields['nombre'])."</option>";
									$r->MoveNext();
									}
								 echo "<option value='".$r->fields['codprov']."'>";
								 echo utf8_encode($r->fields['nombre'])."</option>";
								 $r->MoveNext();
						 }
					}
             echo "</select>
                    Distrito:<select id='dist'><option value=''>-Todos-</option>";
					if(isset($_GET['coddist']) && $_GET['codprov']!=""){
						$coddist=$_GET['coddist'];
						 $r = $db->Execute("SELECT * FROM ubigeos  WHERE coddpto=$coddpto and codprov=$codpro and coddist!=00");
						 //$r->MoveNext();
						 while (!$r->EOF){
								
								if($r->fields['coddist']==$coddist){
									
									echo "<option   value='".$r->fields['coddist']."' SELECTED>".utf8_encode($r->fields['nombre'])."</option>";
									$r->MoveNext();
								}
								 echo "<option value='".$r->fields['coddist']."'>".utf8_encode($r->fields['nombre'])."</option>";
								 $r->MoveNext();
						 }
					}
					
			 echo "		</select>
                    <input class='btn' type='button' value='Filtrar' onclick='zonif_fil();'>
                    <!--Generar Pdf<img src='imag/pdf-icon.png'/ style='float:inherit;position:relative;top:6px;cursor:pointer;' onclick='pdf();'>
                    Generar Excel<img src='imag/logo_excel.png'/ style='float:inherit;position:relative;top:6px;cursor:pointer;' onclick='excel();'>-->
                    <fieldset style='width:800px;'><legend>Ubigeo</legend>
                    <div id=\"design1\"><table style='text-align:left;'><th>Nº</th><th>Id</th><th>Nombre</th></th><th>Departamento</th><th>Provincia</th><th>Distrito</th>";
				
				$sql="SELECT * FROM ubigeos ";
				if(isset($_GET['coddpto']) && $_GET['coddpto']!=""){
					$coddpto=$_GET['coddpto'];
						$sql.=" where coddpto='$coddpto'";
						if(isset($_GET['codprov']) && $_GET['codprov']!=""){
							$codpro=$_GET['codprov'];
							$sql.=" and codprov='$codpro'";
								if(isset($_GET['coddpto']) && $_GET['coddist']!=""){
									$coddist=$_GET['coddist'];
										$sql.=" and coddist='$coddist'";
								}
						}
				
				}
				$sql.=" ORDER BY idubigeo";
				
				$total =$db->Execute($sql);
				
				$t_regist=$total->_numOfRows;
				
				if(isset($_GET['pag'])){
					$pag=$_GET['pag'];
					$pag=$pag-1;
					$pag=$pag*$r_pag;
					$sql.=" LIMIT $pag,$r_pag";
				}else{
					$sql.=" LIMIT $pag,$r_pag";
				}
				
			 $result = $db->Execute($sql);
			 
             while(!$result->EOF){
                    echo "<tr>";
                    echo "<td>".++$n."</td><td>".$result->fields['idubigeo']."</td>";
                    echo "<td>".utf8_encode($result->fields['nombre'])."</td>";
                    echo "<td>".$result->fields['coddpto']."</td>";
                    echo "<td>".$result->fields['codprov']."</td>";
                    echo "<td>".$result->fields['coddist']."</td>";
                    echo "</tr>";
                    $result->MoveNext();
            }
?>
										<!--Pie Paginacion-->	
				<tr>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</tr>
			  <tr>
								<?php
									
									$t_p=$t_regist/$r_pag;
									$Res=$t_regist%$r_pag;
									if($Res>0) $t_p=floor($t_p)+1;
										$Ant=$_GET['pag']-1;
									    $Sig=$_GET['pag']+1;
								?>
			
					<td colspan="6">
						<?php //if(!isset($_GET['ndoc']) and !isset($_GET['name'])){?>
							<?php
								
								
							if($_GET['pag']!=1 && $t_p!=0 )
									
									echo "<a onclick=\"location.href='index.php?tipo=plano&parametros=$id_param&&pag=1&&coddpto=$coddpto&codprov=$codpro&coddist=$coddist'\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"location.href='index.php?tipo=plano&parametros=$id_param&&pag=$Ant&&coddpto=$coddpto&codprov=$codpro&coddist=$coddist'\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									
									echo "<a onclick=\"location.href='index.php?tipo=plano&parametros=$id_param&&pag=$Sig&&coddpto=$coddpto&codprov=$codpro&coddist=$coddist'\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"location.href='index.php?tipo=plano&parametros=$id_param&&pag=$t_p&&coddpto=$coddpto&codprov=$codpro&coddist=$coddist'\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=ubigeos'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!--Fin Pie Paginacion-->	
<?php            echo "</table></div>
                   
                   </center>
                </div>
				<input id='iden' type='hidden' value='$id_param'/>";
                    
            break;
  //----------------------------------------------
  case "planos":
         
	echo "<div id=\"ykBody\">
                    <center>";
                    $r1 = $db->Execute("SELECT * FROM ubigeos  WHERE codprov=00 AND coddist=00");
					
             echo  "Departamento:<select id='dpto' onchange='dpto();'><option value=''>-Todos-</option>";
                    while (!$r1->EOF) {
							if(isset($_GET['coddpto']) && $_GET['coddpto']!="" && $_GET['coddpto']==$r1->fields['coddpto'] ){
								$coddpto=$_GET['coddpto'];
								echo "<option onSelectStart='dpto();'  value='".$r1->fields['coddpto']."' SELECTED> ";
								echo utf8_encode($r1->fields['nombre'])."</option>";
								$r1->MoveNext();
                            }
                     echo "<option value='".$r1->fields['coddpto']."'>".utf8_encode($r1->fields['nombre'])."</option>";
                     $r1->MoveNext();
                    }
             echo  "</select>";
             echo " Provincia:<select id='prov' onchange='dist();'><option value=''>-Todos-</option>";
                    if(isset($_GET['codprov']) && $_GET['coddpto']!=""){
						 $codpro=$_GET['codprov'];
						 $r = $db->Execute("SELECT * FROM ubigeos  WHERE coddpto=$coddpto AND codprov!=00 GROUP BY codprov");
						 
						 while (!$r->EOF){
								if($r->fields['codprov']==$codpro){
									echo "<option   value='".$r->fields['codprov']."' SELECTED>".utf8_encode($r->fields['nombre'])."</option>";
									$r->MoveNext();
									}
								 echo "<option value='".$r->fields['codprov']."'>";
								 echo utf8_encode($r->fields['nombre'])."</option>";
								 $r->MoveNext();
						 }
					}
             echo "</select>
                    Distrito:<select id='dist'><option value=''>-Todos-</option>";
					if(isset($_GET['coddist']) && $_GET['codprov']!=""){
						$coddist=$_GET['coddist'];
						 $r = $db->Execute("SELECT * FROM ubigeos  WHERE coddpto=$coddpto and codprov=$codpro and coddist!=00");
						 //$r->MoveNext();
						 while (!$r->EOF){
								
								if($r->fields['coddist']==$coddist){
									
									echo "<option   value='".$r->fields['coddist']."' SELECTED>".utf8_encode($r->fields['nombre'])."</option>";
									$r->MoveNext();
								}
								 echo "<option value='".$r->fields['coddist']."'>".utf8_encode($r->fields['nombre'])."</option>";
								 $r->MoveNext();
						 }
					}
					
			 echo "		</select>
                    <input class='btn' type='button' value='Filtrar' onclick='zonif_fil();'>
                    <!--Generar Pdf<img src='imag/pdf-icon.png'/ style='float:inherit;position:relative;top:6px;cursor:pointer;' onclick='pdf();'>
                    Generar Excel<img src='imag/logo_excel.png'/ style='float:inherit;position:relative;top:6px;cursor:pointer;' onclick='excel();'>
					--><fieldset style='width:800px;'><legend>Planos</legend>
                    <div id=\"design1\"><table style='text-align:left;'><th>Nº</th><th>Id</th><th>Departamento</th></th><th>Provincia</th><th>Distrito</th><th>Nº de Plano</th><th>Mapa</th>";
             //$result = $db->Execute("SELECT * FROM planos  ORDER BY idubigeo LIMIT $RegistrosAEmpezar, $RegistrosAMostrar");
             $sql="SELECT pl.*,u2.nombre dpto,u2.coddpto,u3.nombre prov,u3.codprov,u.nombre dist,u.coddist
                                    FROM planos pl
                                    JOIN ubigeos u ON pl.idubigeo=u.idubigeo
                                    JOIN ubigeos u2 ON u.coddpto=u2.coddpto AND u2.codprov=00
                                    JOIN ubigeos u3 ON u.coddpto=u3.coddpto AND  u.codprov=u3.codprov AND  u3.coddist=00
                                    ";
				if(isset($_GET['coddpto']) && $_GET['coddpto']!=""){
					$coddpto=$_GET['coddpto'];
						$sql.=" where u2.coddpto='$coddpto'";
						if(isset($_GET['codprov']) && $_GET['codprov']!=""){
							$codpro=$_GET['codprov'];
							$sql.=" and u3.codprov='$codpro'";
								if(isset($_GET['coddpto']) && $_GET['coddist']!=""){
									$coddist=$_GET['coddist'];
										$sql.=" and u.coddist='$coddist'";
								}
						}
				
				}
			 $total =$db->Execute($sql);
				
				$t_regist=$total->_numOfRows;
				
				if(isset($_GET['pag'])){
					$pag=$_GET['pag'];
					$pag=$pag-1;
					$pag=$pag*$r_pag;
					$sql.=" LIMIT $pag,$r_pag";
				}else{
					$sql.=" LIMIT $pag,$r_pag";
				}
			 $result = $db->Execute($sql);

             while(!$result->EOF){
					$idp=$result->fields['idplano'];
                    echo "<tr>";
                    echo "<td>".++$n."</td><td>".$result->fields['idplano']."</td>";
                    echo "<td>".utf8_encode($result->fields['dpto'])."</td>";
                    //-----------------------------------------
					if($result->fields['codprov']=="00"){
						echo "<td> </td>";
					}else{
						echo "<td>".utf8_encode($result->fields['prov'])."</td>";
					}
                    //-----------------------------------------
					if($result->fields['coddist']=="00"){
						echo "<td> </td>";
					}else{
						echo "<td>".utf8_encode($result->fields['dist'])."</td>";
					}
					echo "<td><a style='cursor:pointer;' onclick=\"m6('i_plano&update=$idp');\">".$result->fields['plano']."</a></td>";
					$plano=$result->fields['plano'];
                   //echo "<td><a  href=\"planos/".$result->fields['plano'].".pdf\" rel=\"shadowbox[plano];\">".$result->fields['plano']."</a></td>";
                    //echo "<td>".$result->fields['idubigeo']."</td>";
                   // echo "<td><a style='cursor:pointer;' href=\"planos/PLANO_1.jpg\" rel=\"shadowbox[planos];\">Ver</a></td></tr>";
					 echo "<td><a style='cursor:pointer;' href=\"mapas.php?mapa=$plano\" rel=\"shadowbox;background-color:white;\">Ver</a></td></tr>";
                    $result->MoveNext();
            }
?>
											<!--Pie Paginacion-->	
				<tr>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</tr>
			  <tr>
								<?php
									
									$t_p=$t_regist/$r_pag;
									$Res=$t_regist%$r_pag;
									if($Res>0) $t_p=floor($t_p)+1;
										$Ant=$_GET['pag']-1;
									    $Sig=$_GET['pag']+1;
								?>
			
					<td colspan="7">
						<?php //if(!isset($_GET['ndoc']) and !isset($_GET['name'])){?>
							<?php
							
							if($_GET['pag']!=1 && $t_p!=0 )
									echo "<a onclick=\"location.href='index.php?tipo=plano&parametros=$id_param&&pag=1&&coddpto=$coddpto&codprov=$codpro&coddist=$coddist'\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"location.href='index.php?tipo=plano&parametros=$id_param&&pag=$Ant&&coddpto=$coddpto&codprov=$codpro&coddist=$coddist'\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"location.href='index.php?tipo=plano&parametros=$id_param&&pag=$Sig&&coddpto=$coddpto&codprov=$codpro&coddist=$coddist'\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"location.href='index.php?tipo=plano&parametros=$id_param&&pag=$t_p&&coddpto=$coddpto&codprov=$codpro&coddist=$coddist'\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=planos'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!--Fin Pie Paginacion-->	
<?php
            echo "</table></div></fieldset>
            </br><input class='btn' type=\"button\" onclick=\"m6('i_plano');\" value=\"Agregar Nuevo \"/>
                   </center>
				  <input id='iden' type='hidden' value='$id_param'/>
                ";

            break;
            //--------------------------------------------------------
 case "cuadr":
          
             echo "<div id=\"ykBody\">
                    <center>";
                    $r1 = $db->Execute("SELECT * FROM ubigeos  WHERE codprov=00 AND coddist=00");
					
             echo  "Departamento:<select id='dpto' onchange='dpto();'><option value=''>-Todos-</option>";
                    while (!$r1->EOF) {
							if(isset($_GET['coddpto']) && $_GET['coddpto']!="" && $_GET['coddpto']==$r1->fields['coddpto'] ){
								$coddpto=$_GET['coddpto'];
								echo "<option onSelectStart='dpto();'  value='".$r1->fields['coddpto']."' SELECTED> ";
								echo utf8_encode($r1->fields['nombre'])."</option>";
								$r1->MoveNext();
                            }
                     echo "<option value='".$r1->fields['coddpto']."'>".utf8_encode($r1->fields['nombre'])."</option>";
                     $r1->MoveNext();
                    }
             echo  "</select>";
             echo " Provincia:<select id='prov' onchange='dist();'><option value=''>-Todos-</option>";
                    if(isset($_GET['codprov']) && $_GET['coddpto']!=""){
						 $codpro=$_GET['codprov'];
						 $r = $db->Execute("SELECT * FROM ubigeos  WHERE coddpto=$coddpto AND codprov!=00 GROUP BY codprov");
						 
						 while (!$r->EOF){
								if($r->fields['codprov']==$codpro){
									echo "<option   value='".$r->fields['codprov']."' SELECTED>".utf8_encode($r->fields['nombre'])."</option>";
									$r->MoveNext();
									}
								 echo "<option value='".$r->fields['codprov']."'>";
								 echo utf8_encode($r->fields['nombre'])."</option>";
								 $r->MoveNext();
						 }
					}
             echo "</select>
                    Distrito:<select id='dist'><option value=''>-Todos-</option>";
					if(isset($_GET['coddist']) && $_GET['codprov']!=""){
						$coddist=$_GET['coddist'];
						 $r = $db->Execute("SELECT * FROM ubigeos  WHERE coddpto=$coddpto and codprov=$codpro and coddist!=00");
						 //$r->MoveNext();
						 while (!$r->EOF){
								
								if($r->fields['coddist']==$coddist){
									
									echo "<option   value='".$r->fields['coddist']."' SELECTED>".utf8_encode($r->fields['nombre'])."</option>";
									$r->MoveNext();
								}
								 echo "<option value='".$r->fields['coddist']."'>".utf8_encode($r->fields['nombre'])."</option>";
								 $r->MoveNext();
						 }
					}
					
			 echo "		</select>
                    <input class='btn' type='button' value='Filtrar' onclick='zonif_fil();'>
          
                   <!--<font color='blue'>Generar Pdf</font><img src='imag/pdf-icon.png'/ style='float:inherit;position:relative;top:6px;cursor:pointer;' onclick='pdf();'>
                   <font color='blue'> Generar Excel</font><img src='imag/logo_excel.png'/ style='float:inherit;position:relative;top:6px;cursor:pointer;' onclick='excel();'>
                    --><fieldset style='width:800px;'><legend>Cuadrantes</legend>
                    <div id=\"design1\"><table><th>Nº</th><th>Id</th><th>Departamento</th></th><th>Provincia</th><th>Distrito</th><th>Nº de Plano</th><th>Cuadrante</th><th>Vacio</th>";
             //$result = $db->Execute("SELECT * FROM planos  ORDER BY idubigeo LIMIT $RegistrosAEmpezar, $RegistrosAMostrar");
             $sql="SELECT cr.*,pl.plano,pl.idubigeo,u2.nombre dpto,u2.coddpto,u3.nombre prov,u3.codprov,u.nombre dist,u.coddist
                                    FROM cuadrantes cr
                                    JOIN planos pl ON cr.idplano=pl.idplano
                                    JOIN ubigeos u ON pl.idubigeo=u.idubigeo
                                    JOIN ubigeos u2 ON u.coddpto=u2.coddpto AND u2.codprov=00
                                    JOIN ubigeos u3 ON u.coddpto=u3.coddpto AND  u.codprov=u3.codprov AND  u3.coddist=00
                                    ";
			
			if(isset($_GET['coddpto']) && $_GET['coddpto']!=""){
					$coddpto=$_GET['coddpto'];
						$sql.=" where u2.coddpto='$coddpto'";
						if(isset($_GET['codprov']) && $_GET['codprov']!=""){
							$codpro=$_GET['codprov'];
							$sql.=" and u3.codprov='$codpro'";
								if(isset($_GET['coddpto']) && $_GET['coddist']!=""){
									$coddist=$_GET['coddist'];
										$sql.=" and u.coddist='$coddist'";
								}
						}
				
				}
			$sql.=" order by pl.plano asc,cr.cuadrante ";
			 $total =$db->Execute($sql);
				
				$t_regist=$total->_numOfRows;
				
				if(isset($_GET['pag'])){
					$pag=$_GET['pag'];
					$pag=$pag-1;
					$pag=$pag*$r_pag;
					$sql.=" LIMIT $pag,$r_pag";
				}else{
					$sql.=" LIMIT $pag,$r_pag";
				}
				$result = $db->Execute($sql);
             while(!$result->EOF){
					$idc=$result->fields['idcuadrante'];
                    echo "<tr align='center'>";
                    echo "<td>".++$n."</td><td>".$result->fields['idcuadrante']."</td>";
                    echo "<td>".utf8_encode($result->fields['dpto'])."</td>";
                    //-----------------------------------------
						if($result->fields['codprov']=="00"){
							echo "<td> </td>";
						}else{	
							echo "<td>".utf8_encode($result->fields['prov'])."</td>";
						}
                    //-----------------------------------------
						if($result->fields['coddist']=="00"){
							echo "<td> </td>";
						}else{
							echo "<td>".utf8_encode($result->fields['dist'])."</td>";
						}
                    //---------------------------------------------
                    echo "<td>".$result->fields['plano']."</td>";
                    //---------------------------------------------
                    echo "<td><a style='cursor:pointer;' onclick=\"m6('i_cuate&update=$idc');\">".$result->fields['cuadrante']."</a></td>
						  <td>";
						  if($result->fields['vacio']==0){
								echo "No";
						  }else{
								echo "Si";
						  }
	  
					echo "</td></tr>";
                    $result->MoveNext();
            }
?>
											<!--Pie Paginacion-->	
				<tr>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</tr>
			  <tr>
								<?php
									
									$t_p=$t_regist/$r_pag;
									$Res=$t_regist%$r_pag;
									if($Res>0) $t_p=floor($t_p)+1;
										$Ant=$_GET['pag']-1;
									    $Sig=$_GET['pag']+1;
								?>
			
					<td colspan="8">
						<?php //if(!isset($_GET['ndoc']) and !isset($_GET['name'])){?>
							<?php
							
							if($_GET['pag']!=1 && $t_p!=0 )
									echo "<a onclick=\"location.href='index.php?tipo=plano&parametros=$id_param&&pag=1&&coddpto=$coddpto&codprov=$codpro&coddist=$coddist'\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"location.href='index.php?tipo=plano&parametros=$id_param&&pag=$Ant&&coddpto=$coddpto&codprov=$codpro&coddist=$coddist'\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"location.href='index.php?tipo=plano&parametros=$id_param&&pag=$Sig&&coddpto=$coddpto&codprov=$codpro&coddist=$coddist'\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"location.href='index.php?tipo=plano&parametros=$id_param&&pag=$t_p&&coddpto=$coddpto&codprov=$codpro&coddist=$coddist'\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=cuadrante'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!--Fin Pie Paginacion-->	
<?php			
            echo "</table></div>
           </fieldset>
            </br><input class='btn' type=\"button\" onclick=\"m6('i_cuate');\" value=\"Agregar Nuevo \"/>
                   </center>
                </div>
				<input id='iden' type='hidden' value='$id_param'/>";

            break;
           
            //--------------------------------------------------------
      case "i_plano":
			require 'define_con.php'; 
			if(isset($_GET['update']) && $_GET['update']!==""){
                $id_d=$_GET['update'];
				$sql= $db->Execute("SELECT * FROM planos WHERE idplano=$id_d");
				$idpl=$sql->fields['idubigeo'];
					$dat_ubi=$db->Execute("SELECT u2.coddpto,u2.nombre,u3.codprov,u3.nombre,u4.coddist,u4.nombre FROM planos p
											JOIN ubigeos u ON p.idubigeo=u.idubigeo
											JOIN ubigeos u2 ON u.coddpto=u2.coddpto AND u2.codprov=00 AND u2.coddist=00 
											JOIN ubigeos u3 ON u.coddpto=u3.coddpto AND u.codprov=u3.codprov AND u3.coddist=00
											JOIN ubigeos u4 ON u.coddpto=u4.coddpto AND u.codprov=u4.codprov AND u.coddist=u4.coddist
											WHERE u.idubigeo=$idpl");
                $plano=$sql->fields['plano'];
					$dpto=$dat_ubi->fields['coddpto'];
					$prov=$dat_ubi->fields['codprov'];
					$dist=$dat_ubi->fields['coddist'];
				//$vacio=$sql->fields['vacio'];
				echo "<center><div id=\"ykBody\">
                 <fieldset style='width:400px;'><legend>Actualizar Plano</legend>
                    <div id=\"design\">
						<table>";
				$r1 = $db->Execute("SELECT * FROM ubigeos  WHERE codprov=00 AND coddist=00");
					
				echo  "<tr><td class=\"zpFormLabel\">Departamento:</td><td><select id='dpto' onchange='dpto();'><option value=''>-Todos-</option>";
                    while (!$r1->EOF) {
							if($r1->fields['coddpto']==$dpto){
								 echo "<option value='".$r1->fields['coddpto']."' ondclick=\"m7('ubig','1'";
								 echo   $r1->fields['coddpto'];
								 echo "');\" selected >".utf8_encode($r1->fields['nombre'])."</option>";
								 $r1->MoveNext();
							}
						 echo "<option value='".$r1->fields['coddpto']."' ondclick=\"m7('ubig','1'";
						 echo   $r1->fields['coddpto'];
						 echo "');\">".utf8_encode($r1->fields['nombre'])."</option>";
						 $r1->MoveNext();
                    }
				echo  "</td></tr></select>";
					
				$r1 = $db->Execute("SELECT * FROM ubigeos WHERE coddpto=$dpto AND codprov!=00 GROUP BY codprov");
				echo " <tr><td class=\"zpFormLabel\">Provincia:</td><td><select id='prov' onchange='dist();'>
							<option value=''>-Todos-</option>";
					while (!$r1->EOF) {
							if($r1->fields['codprov']==$prov){
								 echo "<option value='".$r1->fields['codprov']."' selected >".utf8_encode($r1->fields['nombre'])."</option>";
								 $r1->MoveNext();
							}
						 echo "<option value='".$r1->fields['coddpto']."' >".utf8_encode($r1->fields['nombre'])."</option>";
						 $r1->MoveNext();
                    }		
					
					$dist=$dat_ubi->fields['coddist'];
				$r1 = $db->Execute("SELECT * FROM ubigeos WHERE coddpto=$dpto AND codprov=$prov AND coddist!=00 GROUP BY coddist");
				echo "</select></td></tr>
                   <tr><td class=\"zpFormLabel\">Distrito:</td><td><select id='dist'>
						<option value=''>-Todos-</option>";
						while (!$r1->EOF) {
							if($r1->fields['coddist']==$dist){
								 echo "<option value='".$r1->fields['coddist']."' selected >".utf8_encode($r1->fields['nombre'])."</option>";
								 $r1->MoveNext();
							}
							echo "<option value='".$r1->fields['coddist']."' >".utf8_encode($r1->fields['nombre'])."</option>";
							$r1->MoveNext();
						}
				echo "</select></td></tr>
                   <tr><td class=\"zpFormLabel\">Plano:</td><td><input type='text' id='plano' value='$plano'/></td></tr>
                   </table></div>
                    </fieldset>
                    <div id=\"ykBodys\" style=\"color:red;\">
                        </div>
                        <input type=\"button\" class='btn' onclick=\"insert('plano&update=1&id=$id_d');\" value=\"Actualizar\"/>
                    <!--<input type=\"button\" onclick=\"m6('planos');\" value=\"Regresar\"/>-->
					 <input type=\"button\" class='btn' id='atra' onclick='location.reload();' value=\"Regresar\"/>
                    </center>
                    </div>";
					
					return false;
			}
           echo "<center><div id=\"ykBody\">
                 <fieldset style='width:400px;'><legend> Plano</legend>
                    <div id=\"design\">
                    <table>";
           $r1 = $db->Execute("SELECT * FROM ubigeos  WHERE codprov=00 AND coddist=00");
             echo  "<tr><td class=\"zpFormLabel\">Departamento:</td><td><select id='dpto' onchange='dpto();'><option value=''>-Todos-</option>";
                     while (!$r1->EOF) {
                     echo "<option value='".$r1->fields['coddpto']."' ondclick=\"m7('ubig','1'";
                     echo   $r1->fields['coddpto'];
                     echo "');\">".utf8_encode($r1->fields['nombre'])."</option>";
                     $r1->MoveNext();
                                          }
             echo  "</td></tr></select>";
             echo " <tr><td class=\"zpFormLabel\">Provincia:</td><td><select id='prov' onchange='dist();'><option value=''>-Todos-</option></select></td></tr>
                   <tr><td class=\"zpFormLabel\">Distrito:</td><td><select id='dist'><option value=''>-Todos-</option></select></td></tr>
                   <tr><td class=\"zpFormLabel\">Plano:</td><td><input type='text' id='plano'/></td></tr>
                   </table></div>
                    </fieldset>
                    <div id=\"ykBodys\" style=\"color:red;\">
                        </div>
                        <input type=\"button\" class='btn' onclick=\"insert('plano');\" value=\"Agregar\"/>
                    <!--<input type=\"button\" onclick=\"m6('planos');\" value=\"Regresar\"/>-->
					 <input type=\"button\" class='btn' id='atra' onclick='location.reload();' value=\"Regresar\"/>
                    </center>
                    </div>";
          break;
          //--------------------------------------------------------
          case "i_cuate":
		  require 'define_con.php';
			if(isset($_GET['update']) && $_GET['update']!==""){
                $id_d=$_GET['update'];
				$sql= $db->Execute("SELECT * FROM cuadrantes WHERE idcuadrante=$id_d");
				$idpl=$sql->fields['idplano'];
                $cuad=$sql->fields['cuadrante'];
				$vacio=$sql->fields['vacio'];
                //$est=$sql->fields['idestado'];
				 echo "<center><div id=\"ykBody\">
                 <fieldset style='width:400px;'><legend>Actualizar Cuadrante</legend>
                    <div id=\"design\">
                    <table>";
				echo "<tr><td class=\"zpFormLabel\">Nº de Plano:</td><td><select id='c_plano'><option value=''>-Seleccionar-</option>";
				  $r1 = $db->Execute("SELECT * FROM planos");
				  while (!$r1->EOF){
						if($r1->fields['idplano']==$idpl){
						echo "<option value='".$r1->fields['idplano']."' selected>".$r1->fields['plano']."</option>";
						$r1->MoveNext();
						}
						echo "<option value='".$r1->fields['idplano']."'>".$r1->fields['plano']."</option>";
						$r1->MoveNext();
				  }
				  echo "</select></td></tr>";
				  echo "<tr><td class=\"zpFormLabel\">Cuadrante:</td><td><input type='text' id='cuadr' value='$cuad'/></td></tr>
						<tr>
							<td class=\"zpFormLabel\">Vacio:</td><td>"; 
							if($vacio==0){
								echo "Si<input type='radio'  name='cdr' value='1'>No<input type='radio' name='cdr' value='0' checked>";
							}else{
								echo "Si<input type='radio'  name='cdr' value='1' checked>No<input type='radio' name='cdr' value='0'>";
							}
							
				echo  "</td>
						</tr>
					   </table></div>
						</fieldset>
						<div id=\"ykBodys\" style=\"color:red;\">
							</div>
							<input type=\"button\" class='btn' onclick=\"insert('cuadr&update=1&id=$id_d');\" value=\"Actualizar\"/>
						<input type=\"button\" class='btn' id='atra' onclick='location.reload();' value=\"Regresar\"/>
						</center>
						</div>";
				return false;
				
			}
           echo "<center><div id=\"ykBody\">
                 <fieldset style='width:400px;'><legend> Cuadrante</legend>
                    <div id=\"design\">
                    <table>";
           echo "<tr><td class=\"zpFormLabel\">Nº de Plano:</td><td><select id='c_plano'><option value=''>-Seleccionar-</option>";
              $r1 = $db->Execute("SELECT * FROM planos");
              while (!$r1->EOF){
                  echo "<option value='".$r1->fields['idplano']."'>".$r1->fields['plano']."</option>";
                  $r1->MoveNext();
              }
              echo "</select></td></tr>";
              echo "<tr><td class=\"zpFormLabel\">Cuadrante:</td><td><input type='text' id='cuadr'/></td></tr>
					<tr><td class=\"zpFormLabel\">Vacio:</td><td> Si<input type='radio'  name='cdr' value='1'>No<input type='radio' name='cdr' value='0' checked></td></tr>
                   </table></div>
                    </fieldset>
                    <div id=\"ykBodys\" style=\"color:red;\">
                        </div>
                        <input type=\"button\" class='btn' onclick=\"insert('cuadr');\" value=\"Agregar\"/>
                    <input type=\"button\" class='btn' id='atra' onclick='location.reload();' value=\"Regresar\"/>
                    </center>
                    </div>";
          break;
      }
      

}
?>
