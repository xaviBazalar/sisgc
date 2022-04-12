<?php
session_start();
if (!isset($_SESSION["user"]) || $_SESSION["user"] == "") {
	header("Location: login.php");
}

if (!isset($_GET["parametros"]) || $_GET["parametros"]=="") {
	header("Location: login.php");
	return false;
}

require 'define_con.php';
if(isset($_GET['parametros'])){
    $id_param=$_GET['parametros'];
$n=0;
$pag=0;
$r_pag=20;// total registros x pag

if($_GET['pag']!=1){
	$n=$_GET['pag']-1;
	$n=$n*$r_pag;
}
    switch($id_param){
		//--------------------------------------------------------------------------------
        case "valid":
			$sql="SELECT v.*,e.estado FROM validaciones v
                  JOIN estados e ON v.idestado=e.idestado
				  ORDER BY v.idvalidaciones";
				  
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
            if(!$result){
						echo "<input type=\"button\" class='btn' onclick=\"m2('insert_vald');\" value=\"Agregar Nuevo \"/>";
						return false;
						}
			echo "<div id=\"ykBody\">
                <center>
                <fieldset ><legend>Validaciones</legend>
                <div id=\"design1\">
                    <table style='text-align:left;'><th>Nº</th><th>Id</th><th>Descripcion</th><th>Estado</th>";

                        while (!$result->EOF) {
							$id_ord=$result->fields['idvalidaciones'];
							//for ($i=0, $max=$result->FieldCount(); $i < $max; $i++)
							echo "<tr><td>".++$n."</td>
                            <td style='background-color:white;'>".$result->fields['idvalidaciones']."</td>
                            <td style='background-color:white;'><a style='text-decoration:underline;cursor:pointer;' onclick=\"m2('insert_vald&&update=$id_ord');\">".$result->fields['validaciones']."<a/></td>
                            <td style='background-color:white;'>";
								if($result->fields['idestado']== "1"){
									echo "<img src='imag/icons/estado_1.png'/>";
								}else{
									echo "<img src='imag/icons/estado_2.png'/>";
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
			
					<td colspan="4">
						<?php //if(!isset($_GET['ndoc']) and !isset($_GET['name'])){?>
							<?php
							
							if($_GET['pag']!=1 && $t_p!=0 )
									echo "<a onclick=\"m2('valid&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"m2('valid&&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"m2('valid&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"m2('valid&&pag=$t_p');\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=valida'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!-- Fin Pie Paginacion-->	
<?php             echo       "</table></div>
                </fieldset>
                </br><input type=\"button\" class='btn' onclick=\"m2('insert_vald');\" value=\"Agregar Nuevo \"/>
                    </center>
                </div> ";
            break;
	
		//--------------------------------------------------------------------------------
        case "t_conta":
			$sql="SELECT tc.*,e.estado FROM tipo_contactabilidad tc
                  JOIN estados e ON tc.idestado=e.idestado
				  ORDER BY tc.idtipocontactabilidad";
				  
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
            if(!$result){
						echo "<input type=\"button\" class='btn' onclick=\"m2('insert_tpc');\" value=\"Agregar Nuevo \"/>";
						return false;
						}
			echo "<div id=\"ykBody\">
                <center>
                <fieldset ><legend>Tipo Contactabilidad</legend>
                <div id=\"design1\">
                    <table style='text-align:left;'><th>Nº</th><th>Id</th><th>Descripcion</th><th>Estado</th>";

                        while (!$result->EOF) {
							$id_ord=$result->fields['idtipocontactabilidad'];
							//for ($i=0, $max=$result->FieldCount(); $i < $max; $i++)
							echo "<tr><td>".++$n."</td>
                            <td style='background-color:white;'>".$result->fields['idtipocontactabilidad']."</td>
                            <td style='background-color:white;'><a style='text-decoration:underline;cursor:pointer;' onclick=\"m2('insert_tpc&&update=$id_ord');\">".$result->fields['tipocontactabilidad']."<a/></td>
                            <td style='background-color:white;'>";
								if($result->fields['idestado']== "1"){
									echo "<img src='imag/icons/estado_1.png'/>";
								}else{
									echo "<img src='imag/icons/estado_2.png'/>";
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
			
					<td colspan="4">
						<?php //if(!isset($_GET['ndoc']) and !isset($_GET['name'])){?>
							<?php
							
							if($_GET['pag']!=1 && $t_p!=0 )
									echo "<a onclick=\"m2('t_conta&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"m2('t_conta&&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"m2('t_conta&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"m2('t_conta&&pag=$t_p');\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=t_conta'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!-- Fin Pie Paginacion-->	
<?php             echo       "</table></div>
                </fieldset>
                </br><input type=\"button\" class='btn' onclick=\"m2('insert_tpc');\" value=\"Agregar Nuevo \"/>
                    </center>
                </div> ";
            break;
//--------------------------------------------------------------------------------
        case "or_email":
			$sql="SELECT oe.*,e.estado FROM origen_emails oe
                  JOIN estados e ON e.idestado=oe.idestado
				  ORDER BY oe.idorigenemail";
				  
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
            if(!$result){
						echo "<input type=\"button\" class='btn' onclick=\"m2('insert_ord');\" value=\"Agregar Nuevo \"/>";
						return false;
						}
			echo "<div id=\"ykBody\">
                <center>
                <fieldset ><legend>Origen de Emails</legend>
                <div id=\"design1\">
                    <table style='text-align:left;'><th>Nº</th><th>Id</th><th>Descripcion</th><th>Estado</th>";

                        while (!$result->EOF) {
							$id_ord=$result->fields['idorigenemail'];
							//for ($i=0, $max=$result->FieldCount(); $i < $max; $i++)
							echo "<tr><td>".++$n."</td>
                            <td style='background-color:white;'>".$result->fields['idorigenemail']."</td>
                            <td style='background-color:white;'><a style='text-decoration:underline;cursor:pointer;' onclick=\"m2('insert_ore&&update=$id_ord');\">".$result->fields['origenemail']."<a/></td>
                            <td style='background-color:white;'>";
								if($result->fields['idestado']== "1"){
									echo "<img src='imag/icons/estado_1.png'/>";
								}else{
									echo "<img src='imag/icons/estado_2.png'/>";
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
			
					<td colspan="4">
						<?php //if(!isset($_GET['ndoc']) and !isset($_GET['name'])){?>
							<?php
							
							if($_GET['pag']!=1 && $t_p!=0 )
									echo "<a onclick=\"m2('or_email&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"m2('or_email&&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"m2('or_email&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"m2('or_email&&pag=$t_p');\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=o_e'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!-- Fin Pie Paginacion-->	
<?php             echo       "</table></div>
                </fieldset>
                </br><input type=\"button\" class='btn' onclick=\"m2('insert_ore');\" value=\"Agregar Nuevo \"/>
                    </center>
                </div> ";
            break;
//--------------------------------------------------------------------------------
		case "fuente":
			$sql="SELECT f.*,e.estado FROM fuentes f
                  JOIN estados e ON e.idestado=f.idestado";
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
			
			if(!$result){
						echo "<input type=\"button\" class='btn' onclick=\"m2('insert_ft');\" value=\"Agregar Nuevo \"/>";
						return false;
						}						
            echo "<div id=\"ykBody\">
                <center>
                <fieldset><legend>Fuentes</legend>
                     <div id=\"design1\">
                        <table style='text-align:left;'><th>Nº</th><th>Id</th><th>Descripcion</th><th>Estado</th> ";
                         while (!$result->EOF) {
                        //for ($i=0, $max=$result->FieldCount(); $i < $max; $i++)
                         $id_ft=$result->fields['idfuente'];
                         echo "<tr><td>".++$n."</td>
                            <td style='background-color:white;'>".$result->fields['idfuente']."</td>
                            <td style='background-color:white;'><a style='text-decoration:underline;cursor:pointer;' onclick=\"m2('insert_ft&&update=$id_ft');\">".$result->fields['fuente']."</a></td>
                             <td style='background-color:white;'>";
                            if($result->fields['idestado']== "1"){
                                echo "<img src='imag/icons/estado_1.png'/>";
                            }else{
                                echo "<img src='imag/icons/estado_2.png'/>";
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
			
					<td colspan="4">
						<?php //if(!isset($_GET['ndoc']) and !isset($_GET['name'])){?>
							<?php
							
							if($_GET['pag']!=1 && $t_p!=0 )
									echo "<a onclick=\"m2('fuente&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"m2('fuente&&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"m2('fuente&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"m2('fuente&&pag=$t_p');\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=fuente'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!-- Fin Pie Paginacion-->	
<?php            echo       "</table></div>
                </fieldset>
                <div id='editar'>
                </div>
               </br><input type=\"button\" class='btn' onclick=\"m2('insert_ft');\" value=\"Agregar Nuevo \"/>
                    </center>
                </div>
                ";
            break;
//---------------------------------------------------------------------------------
        case "doc":
			$sql="SELECT d.*,e.estado FROM doi d
                  JOIN estados e ON e.idestado=d.idestado";
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
			
			if(!$result){
						echo "<input type=\"button\" class='btn' onclick=\"m2('insert_d');\" value=\"Agregar Nuevo \"/>";
						return false;
						}						
            echo "<div id=\"ykBody\">
                <center>
                <fieldset><legend>Tipos de Documento</legend>
                     <div id=\"design1\">
                        <table style='text-align:left;'><th>Nº</th><th>Id</th><th>Descripcion</th><th>Estado</th> ";
                         while (!$result->EOF) {
                        //for ($i=0, $max=$result->FieldCount(); $i < $max; $i++)
                         $id_doi=$result->fields['iddoi'];
                         echo "<tr><td>".++$n."</td>
                            <td style='background-color:white;'>".$result->fields['iddoi']."</td>
                            <td style='background-color:white;'><a style='text-decoration:underline;cursor:pointer;' onclick=\"m2('insert_d&&update=$id_doi');\">".$result->fields['doi']."</a></td>
                             <td style='background-color:white;'>";
                            if($result->fields['idestado']== "1"){
                                echo "<img src='imag/icons/estado_1.png'/>";
                            }else{
                                echo "<img src='imag/icons/estado_2.png'/>";
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
			
					<td colspan="4">
						<?php //if(!isset($_GET['ndoc']) and !isset($_GET['name'])){?>
							<?php
							
							if($_GET['pag']!=1 && $t_p!=0 )
									echo "<a onclick=\"m2('doc&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"m2('doc&&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"m2('doc&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"m2('doc&&pag=$t_p');\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=doc'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!-- Fin Pie Paginacion-->	
<?php            echo       "</table></div>
                </fieldset>
                <div id='editar'>
                </div>
               </br><input type=\"button\" class='btn' onclick=\"m2('insert_d');\" value=\"Agregar Nuevo \"/>
                    </center>
                </div>
                ";
            break;
//---------------------------------------------------------------------------------
        case "pers":
			
			$sql="SELECT p.*,e.estado FROM personerias p
                  JOIN estados e ON e.idestado=p.idestado
				  ORDER BY p.personerias";
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
									
			if(!$result){
						echo "<input type=\"button\" class='btn' onclick=\"m2('insert_p');\" value=\"Agregar Nuevo \"/>";
						return false;
						}						
            echo "<div id=\"ykBody\">
                <center>
                <fieldset ><legend>Tipos de Persona</legend>
                    <div id=\"design1\">
                        <table style='text-align:left;'><th>Nº</th><th>Id</th><th>Descripcion</th><th>Estado</th> ";
                         while (!$result->EOF) {
							$id_psn=$result->fields['idpersoneria'];//for ($i=0, $max=$result->FieldCount(); $i < $max; $i++)
							echo "<tr><td>".++$n."</td>
                            <td style='background-color:white;'>".$result->fields['idpersoneria']."</td>
                            <td style='background-color:white;'><a  style='text-decoration:underline;cursor:pointer;' onclick=\"m2('insert_p&&update=$id_psn');\">".$result->fields['personerias']."</a></td>
                            <td style='background-color:white;'>";
								if($result->fields['idestado']== "1"){
									echo "<img src='imag/icons/estado_1.png'/>";
								}else{
									echo "<img src='imag/icons/estado_2.png'/>";
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
			
					<td colspan="4">
						<?php //if(!isset($_GET['ndoc']) and !isset($_GET['name'])){?>
							<?php
							
							if($_GET['pag']!=1 && $t_p!=0 )
									echo "<a onclick=\"m2('pers&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"m2('pers&&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"m2('pers&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"m2('pers&&pag=$t_p');\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=personeria'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!-- Fin Pie Paginacion-->	


<?php             echo       "</table></div>
                </fieldset>
                </br><input type=\"button\" class='btn' onclick=\"m2('insert_p');\" value=\"Agregar Nuevo \"/>
                    </center>
                </div> ";
            break;
//--------------------------------------------------------------------------------
        case "or_dir":
			$sql="SELECT o.*,e.estado FROM origen_direcciones o
                  JOIN estados e ON e.idestado=o.idestado
				  ORDER BY o.origendireccion";
				  
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
            if(!$result){
						echo "<input type=\"button\" class='btn' onclick=\"m2('insert_ord');\" value=\"Agregar Nuevo \"/>";
						return false;
						}
			echo "<div id=\"ykBody\">
                <center>
                <fieldset ><legend>Origen de Direcciones</legend>
                <div id=\"design1\">
                    <table style='text-align:left;'><th>Nº</th><th>Id</th><th>Descripcion</th><th>Estado</th>";

                        while (!$result->EOF) {
							$id_ord=$result->fields['idorigendireccion'];
							//for ($i=0, $max=$result->FieldCount(); $i < $max; $i++)
							echo "<tr><td>".++$n."</td>
                            <td style='background-color:white;'>".$result->fields['idorigendireccion']."</td>
                            <td style='background-color:white;'><a style='text-decoration:underline;cursor:pointer;' onclick=\"m2('insert_ord&&update=$id_ord');\">".$result->fields['origendireccion']."<a/></td>
                            <td style='background-color:white;'>";
								if($result->fields['idestado']== "1"){
									echo "<img src='imag/icons/estado_1.png'/>";
								}else{
									echo "<img src='imag/icons/estado_2.png'/>";
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
			
					<td colspan="4">
						<?php //if(!isset($_GET['ndoc']) and !isset($_GET['name'])){?>
							<?php
							
							if($_GET['pag']!=1 && $t_p!=0 )
									echo "<a onclick=\"m2('or_dir&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"m2('or_dir&&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"m2('or_dir&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"m2('or_dir&&pag=$t_p');\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=o_dir'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!-- Fin Pie Paginacion-->	
<?php             echo       "</table></div>
                </fieldset>
                </br><input type=\"button\" class='btn' onclick=\"m2('insert_ord');\" value=\"Agregar Nuevo \"/>
                    </center>
                </div> ";
            break;
//--------------------------------------------------------------------------------
        case "or_tel":
			$sql="SELECT o.*,e.estado FROM origen_telefonos o
                  JOIN estados e ON e.idestado=o.idestado
				  ORDER BY o.origentelefono";
		
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
            if(!$result){
				echo "<input type=\"button\" class='btn' onclick=\"m2('insert_ort');\" value=\"Agregar Nuevo \"/>";
				return false;
			}
			echo "<div id=\"ykBody\">
                <center>
                <fieldset ><legend>Origen de Telefonos</legend>
                    <div id=\"design1\">
                    <table style='text-align:left;'><th>Nº</th><th>Id</th><th>Descripcion</th><th>Estado</th>";

                        while (!$result->EOF) {
							$id_ot=$result->fields['idorigentelefono'];
							//for ($i=0, $max=$result->FieldCount(); $i < $max; $i++)
							echo "<tr><td>".++$n."</td>
                            <td style='background-color:white;'>".$result->fields['idorigentelefono']."</td>
                            <td style='background-color:white;'><a style='text-decoration:underline;cursor:pointer;' onclick=\"m2('insert_ort&&update=$id_ot');\">".$result->fields['origentelefono']."</a></td>
                            <td style='background-color:white;'>";
								if($result->fields['idestado']== "1"){
									echo "<img src='imag/icons/estado_1.png'/>";
								}else{
									echo "<img src='imag/icons/estado_2.png'/>";
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
			
					<td colspan="4">
						<?php //if(!isset($_GET['ndoc']) and !isset($_GET['name'])){?>
							<?php
							
							if($_GET['pag']!=1 && $t_p!=0 )
									echo "<a onclick=\"m2('or_tel&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"m2('or_tel&&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"m2('or_tel&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"m2('or_tel&&pag=$t_p');\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=o_tel'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!-- Fin Pie Paginacion-->					
<?php             echo       "</table></div>
                </fieldset>
                </br><input type=\"button\" class='btn' onclick=\"m2('insert_ort');\" value=\"Agregar Nuevo \"/>
                    </center>
                </div> ";
            break;
//--------------------------------------------------------------------------------
        case "parent":
			$sql="SELECT p.*,e.estado FROM parentescos p
                  JOIN estados e ON e.idestado=p.idestado
				  ORDER BY p.parentescos";
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
			
            if(!$result){
				echo "<input type=\"button\" class='btn' onclick=\"m2('insert_pa');\" value=\"Agregar Nuevo \"/>";
				return false;
			}
			echo "<div id=\"ykBody\">
                <center>
                <fieldset ><legend>Tipos de Parentesco</legend>
                    <div id=\"design1\">
                    <table style='text-align:left;'><th>Nº</th><th>Id</th><th>Parentesco</th><th>Estado</th>";

                        while (!$result->EOF) {
							$id_p=$result->fields['idparentesco'];
							echo "<tr><td>".++$n."</td>
                            <td style='background-color:white;'>".$result->fields['idparentesco']."</td>
                            <td style='background-color:white;'><a style='text-decoration:underline;cursor:pointer;' onclick=\"m2('insert_pa&&update=$id_p');\">".$result->fields['parentescos']."</a></td>
                            <td style='background-color:white;'>";
								if($result->fields['idestado']== "1"){
									echo "<img src='imag/icons/estado_1.png'/>";
								}else{
									echo "<img src='imag/icons/estado_2.png'/>";
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
			
					<td colspan="4">
						<?php //if(!isset($_GET['ndoc']) and !isset($_GET['name'])){?>
							<?php
							
							if($_GET['pag']!=1 && $t_p!=0 )
									echo "<a onclick=\"m2('parent&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"m2('parent&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"m2('parent&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"m2('parent&pag=$t_p');\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=parentesco'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!-- Fin Pie Paginacion-->		
<?php           echo       "</table></div>
                </fieldset>
                </br><input type=\"button\" class='btn' onclick=\"m2('insert_pa');\" value=\"Agregar Nuevo \"/>
                    </center>
                </div> ";
             break;
//--------------------------------------------------------------------------------
        case "conta":
			$sql="SELECT c.*,e.estado,tpc.tipocontactabilidad FROM contactabilidad c
                  JOIN estados e ON c.idestado=e.idestado
				  left JOIN tipo_contactabilidad tpc on c.idtipocontactabilidad=tpc.idtipocontactabilidad
				  ORDER BY c.contactabilidad";
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
				if(!$result){
					echo "<input type=\"button\" class='btn' onclick=\"m2('insert_con');\" value=\"Agregar Nuevo \"/>";
					return false;
				}
			echo "<div id=\"ykBody\">
                <center>
                <fieldset ><legend>Tipo De Contactos</legend>
                        <div id=\"design1\">
                        <table style='text-align:left;'><th>Nº</th><th>Id</th><th>Descripcion</th><th>Tipo Contactabilidad</th><th>Estado</th> ";
                        while (!$result->EOF) {
							$id=$result->fields['idcontactabilidad'];
                             echo "<tr><td>".++$n."</td>
                             <td style='background-color:white;'>".$result->fields['idcontactabilidad']."</td>
                             <td style='background-color:white;'><a style='text-decoration:underline;cursor:pointer;' onclick=\"m2('insert_con&&update=$id');\" >".$result->fields['contactabilidad']."</a></td>
                             <td style='background-color:white;'>".$result->fields['tipocontactabilidad']."</td>
							 <td style='background-color:white;'>";
								if($result->fields['idestado']== "1"){
									echo "<img src='imag/icons/estado_1.png'/>";
                                }else{
									echo "<img src='imag/icons/estado_2.png'/>";
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
			
					<td colspan="4">
						<?php //if(!isset($_GET['ndoc']) and !isset($_GET['name'])){?>
							<?php
							
							if($_GET['pag']!=1 && $t_p!=0 )
									echo "<a onclick=\"m2('conta&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"m2('conta&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"m2('conta&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"m2('conta&pag=$t_p');\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=contac'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!-- Fin Pie Paginacion-->					
<?php             echo       "</table></div>
                </fieldset>
                </br><input type=\"button\" class='btn' onclick=\"m2('insert_con');\" value=\"Agregar Nuevo \"/>
                    </center>
                </div>
                ";
            break;
//--------------------------------------------------------------------------------
        case "ubic":
			$sql="SELECT u.*,e.estado FROM ubicabilidad u
                  JOIN estados e ON u.idestado=e.idestado
				  ORDER BY u.ubicabilidad";
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
            if(!$result){
						echo "<input type=\"button\" class='btn' onclick=\"m2('insert_ubi');\" value=\"Agregar Nuevo \"/>";
						return false;
						}
			echo "<div id=\"ykBody\">
                <center>
                <fieldset><legend>Ubicabilidad</legend>
                        <div id=\"design1\">
                        <table style='text-align:left;' ><th>Nº</th><th>Id</th><th>Ubicabilidad</th><th>Estado</th> ";
                         while (!$result->EOF) {
							//for ($i=0, $max=$result->FieldCount(); $i < $max; $i++)
                            $id=$result->fields['idubicabilidad'];
                             echo "<tr><td>".++$n."</td>
                            <td style='background-color:white;'>".$result->fields['idubicabilidad']."</td>
                            <td style='background-color:white;'><a style='text-decoration:underline;cursor:pointer;' onclick=\"m2('insert_ubi&&update=$id');\" >".$result->fields['ubicabilidad']."</a></td>
                            <td style='background-color:white;'>";
								if($result->fields['idestado']== "1"){
									echo "<img src='imag/icons/estado_1.png'/>";
                                }else{
									echo "<img src='imag/icons/estado_2.png'/>";
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
			
					<td colspan="4">
						<?php //if(!isset($_GET['ndoc']) and !isset($_GET['name'])){?>
							<?php
							
							if($_GET['pag']!=1 && $t_p!=0 )
									echo "<a onclick=\"m2('ubic&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"m2('ubic&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"m2('ubic&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"m2('ubic&pag=$t_p');\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=ubic'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!-- Fin Pie Paginacion-->	
<?php             echo       "</table></div>
                </fieldset>
                </br><input type=\"button\" class='btn' onclick=\"m2('insert_ubi');\" value=\"Agregar Nuevo \"/>
                    </center>
                </div>
                ";
            break;
//--------------------------------------------------------------------------------
        case "money":
			$sql="SELECT m.*,e.estado FROM monedas m
                  JOIN estados e ON m.idestado=e.idestado
				  ORDER BY monedas";
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
            if(!$result){
						echo "<input type=\"button\" class='btn' onclick=\"m2('insert_m');\" value=\"Agregar Nuevo \"/>";
						return false;
						}
			echo "<div id=\"ykBody\">
                <center>
                <fieldset><legend>Tipos de Moneda</legend>
                        <div id=\"design1\">
                        <table style='text-align:left;'><th>Nº</th><th>Id</th><th>Descripcion</th><th>Estado</th> ";
                         while (!$result->EOF) {
							//for ($i=0, $max=$result->FieldCount(); $i < $max; $i++)
							$id=$result->fields['idmoneda'];
                             echo "<tr><td>".++$n."</td>
                            <td style='background-color:white;'>".$result->fields['idmoneda']."</td>
                            <td style='background-color:white;'><a style='text-decoration:underline;cursor:pointer;' onclick=\"m2('insert_m&&update=$id');\" >".$result->fields['monedas']."</td>
                            <td style='background-color:white;'>";
								if($result->fields['idestado']== "1"){
									echo "<img src='imag/icons/estado_1.png'/>";
                                }else{
									echo "<img src='imag/icons/estado_2.png'/>";
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
			
					<td colspan="4">
						<?php //if(!isset($_GET['ndoc']) and !isset($_GET['name'])){?>
							<?php
							
							if($_GET['pag']!=1 && $t_p!=0 )
									echo "<a onclick=\"m2('money&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"m2('money&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"m2('money&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"m2('money&pag=$t_p');\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=money'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!-- Fin Pie Paginacion-->						
<?php             echo       "</table></div>
                </fieldset>
                </br><input type=\"button\" class='btn' onclick=\"m2('insert_m');\" value=\"Agregar Nuevo \"/>
                    </center>
                </div>
                ";
            break;
//--------------------------------------------------------------------------------
        case "nivel":
			$sql="SELECT n.*,e.estado FROM niveles n
                  JOIN estados e ON n.idestado=e.idestado
				  order by n.nivel";
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
            if(!$result){
						echo "<input type=\"button\" class='btn' onclick=\"m2('insert_n');\" value=\"Agregar Nuevo \"/>";
						return false;
						}      
            echo "<div id=\"ykBody\">
                <center>
                <fieldset><legend>Tipos de Niveles</legend>
                <div id=\"design1\">
                    <table style='text-align:left;'><th>Nº</th><th>Id</th><th>Nivel</th><th>Estado</th>";
            
                    while (!$result->EOF) {
                        $id_nv=$result->fields['idnivel'];
                        //for ($i=0, $max=$result->FieldCount(); $i < $max; $i++)
                        echo "<tr><td>".++$n."</td>
                            <td style='background-color:white;'>".$result->fields['idnivel']."</td>
                            <td style='background-color:white;'><a style='text-decoration:underline;cursor:pointer;' onclick=\"m2('insert_n&&update=$id_nv');\" >".$result->fields['nivel']."<a/></td>
                            <td style='background-color:white;'>";
                            if($result->fields['idestado']== "1"){
                                echo "<img src='imag/icons/estado_1.png'/>";
                            }else{
                                echo "<img src='imag/icons/estado_2.png'/>";
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
			
					<td colspan="4">
						<?php //if(!isset($_GET['ndoc']) and !isset($_GET['name'])){?>
							<?php
							
							if($_GET['pag']!=1 && $t_p!=0 )
									echo "<a onclick=\"m2('nivel&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"m2('nivel&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"m2('nivel&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"m2('nivel&pag=$t_p');\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=nivel'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!-- Fin Pie Paginacion-->					
<?php         echo   "</table></div>
                </fieldset>
                </br><input type=\"button\" class='btn' onclick=\"m2('insert_n');\" value=\"Agregar Nuevo \"/>
                    </center>
                </div>
                ";
            break;
//--------------------------------------------------------------------------------			
		case "actvd":
			$sql="SELECT a.*,e.estado FROM actividades a
				  JOIN estados e ON e.idestado=a.idestado
				  ORDER BY a.actividad";
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
									
            if(!$result){
				echo "<input type=\"button\" class='btn' onclick=\"m2('insert_act');\" value=\"Agregar Nuevo \"/>";
				return false;
			}      
            echo "<div id=\"ykBody\">
                <center>
                <fieldset><legend>Actividades</legend>
                <div id=\"design1\">
                    <table style='text-align:left;'><th>Nº</th><th>Id</th><th>Nivel</th><th>Estado</th>";
            
                    while (!$result->EOF) {
                        $id_a=$result->fields['idactividad'];
                        //for ($i=0, $max=$result->FieldCount(); $i < $max; $i++)
                        echo "<tr><td>".++$n."</td>
                            <td style='background-color:white;'>".$result->fields['idactividad']."</td>
                            <td style='background-color:white;'><a style='text-decoration:underline;cursor:pointer;' onclick=\"m2('insert_act&&update=$id_a');\" >".$result->fields['actividad']."<a/></td>
                           <td style='background-color:white;'>";
								if($result->fields['idestado']== "1"){
									echo "<img src='imag/icons/estado_1.png'/>";
                                }else{
									echo "<img src='imag/icons/estado_2.png'/>";
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
			
					<td colspan="4">
						<?php //if(!isset($_GET['ndoc']) and !isset($_GET['name'])){?>
							<?php
							
							if($_GET['pag']!=1 && $t_p!=0 )
									echo "<a onclick=\"m2('actvd&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"m2('actvd&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"m2('actvd&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"m2('actvd&pag=$t_p');\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=actividad'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!-- Fin Pie Paginacion-->					
<?php         echo   "</table></div>
                </fieldset>
                </br><input type=\"button\" class='btn' onclick=\"m2('insert_act');\" value=\"Agregar Nuevo \"/>
                    </center>
                </div>
                ";
            break;	
//--------------------------------------------------------------------------------
        case "tpredio":
			$sql="SELECT * from tipo_predio";
				  
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
            if(!$result or $t_regist=="0"){
						echo "<input type=\"button\" class='btn' onclick=\"m2('insert_tpredio');\" value=\"Agregar Nuevo \"/>";
						return false;
						}
			echo "<div id=\"ykBody\">
                <center>
                <fieldset ><legend>Tipo Predio</legend>
                <div id=\"design1\">
                    <table style='text-align:left;'><th>Nº</th><th>Id</th><th>Descripcion</th><th>Estado</th>";

                        while (!$result->EOF) {
							$id_pr=$result->fields['idpredio'];
							//for ($i=0, $max=$result->FieldCount(); $i < $max; $i++)
							echo "<tr><td>".++$n."</td>
                            <td style='background-color:white;'>".$result->fields['idpredio']."</td>
							<td style='background-color:white;'><a style='text-decoration:underline;cursor:pointer;' onclick=\"m2('insert_tpredio&&update=$id_pr');\" >".$result->fields['tipo_predio']."</a></td>
                            <td style='background-color:white;'>";
								if($result->fields['idestado']== "1"){
									echo "<img src='imag/icons/estado_1.png'/>";
								}else{
									echo "<img src='imag/icons/estado_2.png'/>";
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
			
					<td colspan="4">
						<?php //if(!isset($_GET['ndoc']) and !isset($_GET['name'])){?>
							<?php
							
							if($_GET['pag']!=1 && $t_p!=0 )
									echo "<a onclick=\"m2('or_dir&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"m2('or_dir&&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"m2('or_dir&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"m2('or_dir&&pag=$t_p');\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=o_dir'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!-- Fin Pie Paginacion-->	
<?php             echo       "</table></div>
                </fieldset>
                </br><input type=\"button\" class='btn' onclick=\"m2('insert_tpredio');\" value=\"Agregar Nuevo \"/>
                    </center>
                </div> ";
            break;			
//--------------------------------------------------------------------------------
        case "mpredio":
			$sql="SELECT * from material_predio";
				  
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
            if(!$result or $t_regist=="0"){
						echo "<input type=\"button\" class='btn' onclick=\"m2('insert_mpredio');\" value=\"Agregar Nuevo \"/>";
						return false;
						}
			echo "<div id=\"ykBody\">
                <center>
                <fieldset ><legend>Material Predio</legend>
                <div id=\"design1\">
                    <table style='text-align:left;'><th>Nº</th><th>Descripcion</th><th>Estado</th>";

                        while (!$result->EOF) {
							$id_mp=$result->fields['idmaterial_predio'];
							//for ($i=0, $max=$result->FieldCount(); $i < $max; $i++)
							echo "<tr><td>".++$n."</td>
                            <td style='background-color:white;'><a style='text-decoration:underline;cursor:pointer;' onclick=\"m2('insert_mpredio&&update=$id_mp');\" >".$result->fields['material']."</a></td>
                            <td style='background-color:white;'>";
								if($result->fields['idestado']== "1"){
									echo "<img src='imag/icons/estado_1.png'/>";
								}else{
									echo "<img src='imag/icons/estado_2.png'/>";
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
			
					<td colspan="4">
						<?php //if(!isset($_GET['ndoc']) and !isset($_GET['name'])){?>
							<?php
							
							if($_GET['pag']!=1 && $t_p!=0 )
									echo "<a onclick=\"m2('mpredio&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"m2('mpredio&&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"m2('mpredio&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"m2('mpredio&&pag=$t_p');\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=o_dir'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!-- Fin Pie Paginacion-->	
<?php             echo       "</table></div>
                </fieldset>
                </br><input type=\"button\" class='btn' onclick=\"m2('insert_mpredio');\" value=\"Agregar Nuevo \"/>
                    </center>
                </div> ";
            break;			
//--------------------------------------------------------------------------------
        case "npisos":
			$sql="SELECT * from nro_pisos";
				  
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
            if(!$result or $t_regist=="0"){
						echo "<input type=\"button\" class='btn' onclick=\"m2('insert_npisos');\" value=\"Agregar Nuevo \"/>";
						return false;
						}
			echo "<div id=\"ykBody\">
                <center>
                <fieldset ><legend>Nro Pisos</legend>
                <div id=\"design1\">
                    <table style='text-align:left;'><th>Nº</th><th>Descripcion</th><th>Estado</th>";

                        while (!$result->EOF) {
							$id_ps=$result->fields['idnro_pisos'];
							//for ($i=0, $max=$result->FieldCount(); $i < $max; $i++)
							echo "<tr><td>".++$n."</td>
                            <td style='background-color:white;'><a style='text-decoration:underline;cursor:pointer;' onclick=\"m2('insert_npisos&&update=$id_ps');\" >".$result->fields['piso']."</a></td>
                            <td style='background-color:white;'>";
								if($result->fields['idestado']== "1"){
									echo "<img src='imag/icons/estado_1.png'/>";
								}else{
									echo "<img src='imag/icons/estado_2.png'/>";
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
			
					<td colspan="4">
						<?php //if(!isset($_GET['ndoc']) and !isset($_GET['name'])){?>
							<?php
							
							if($_GET['pag']!=1 && $t_p!=0 )
									echo "<a onclick=\"m2('npisos&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"m2('npisos&&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"m2('npisos&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"m2('npisos&&pag=$t_p');\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=o_dir'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!-- Fin Pie Paginacion-->	
<?php             echo       "</table></div>
                </fieldset>
                </br><input type=\"button\" class='btn' onclick=\"m2('insert_npisos');\" value=\"Agregar Nuevo \"/>
                    </center>
                </div> ";
            break;			
//--------------------------------------------------------------------------------
        case "cpared":
			$sql="SELECT * from colores_pared";
				  
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
            if(!$result or $t_regist=="0"){
				echo "<input type=\"button\" class='btn' onclick=\"m2('insert_cpared');\" value=\"Agregar Nuevo \"/>";
				return false;
			}
			echo "<div id=\"ykBody\">
                <center>
                <fieldset ><legend>Colores Pared</legend>
                <div id=\"design1\">
                    <table style='text-align:left;'><th>Nº</th><th>Descripcion</th><th>Estado</th>";

                        while (!$result->EOF) {
							$id_cp=$result->fields['idcolor_pared'];
							//for ($i=0, $max=$result->FieldCount(); $i < $max; $i++)
							echo "<tr><td>".++$n."</td>
                            <td style='background-color:white;'><a style='text-decoration:underline;cursor:pointer;' onclick=\"m2('insert_cpared&&update=$id_cp');\" >".$result->fields['color']."</a></td>
                            <td style='background-color:white;'>";
								if($result->fields['idestado']== "1"){
									echo "<img src='imag/icons/estado_1.png'/>";
								}else{
									echo "<img src='imag/icons/estado_2.png'/>";
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
			
					<td colspan="4">
						<?php //if(!isset($_GET['ndoc']) and !isset($_GET['name'])){?>
							<?php
							
							if($_GET['pag']!=1 && $t_p!=0 )
									echo "<a onclick=\"m2('cpared&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"m2('cpared&&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"m2('cpared&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"m2('cpared&&pag=$t_p');\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=o_dir'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!-- Fin Pie Paginacion-->	
<?php             echo       "</table></div>
                </fieldset>
                </br><input type=\"button\" class='btn' onclick=\"m2('insert_cpared');\" value=\"Agregar Nuevo \"/>
                    </center>
                </div> ";
            break;			
//--------------------------------------------------------------------------------
        case "insert_d":
             if(isset($_GET['update']) && $_GET['update']!==""){
                $id_d=$_GET['update'];
                $sql= $db->Execute("SELECT * FROM doi WHERE iddoi=$id_d");
                $doc=$sql->fields['doi'];
                $est=$sql->fields['idestado'];
                 echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;text-align:left;'><legend>Actualizar Documento</legend>
                    <table>
                        <tr><td class=\"zpFormLabel\">Tipo Doc. :</td><td><input type'=text' size='24' id='doc' value='$doc'></td></tr>";
                    $result = $db->Execute("SELECT * FROM estados");
                        echo "<tr><td class='zpFormLabel'>Estado :</td><td><select id='d_estado'>";
                         while (!$result->EOF) {
                             if( $est == $result->fields['idestado'] ){
                                echo "<option value='".$result->fields['idestado']."' SELECTED>".$result->fields['estado']."</option>";
                                $result->MoveNext();
                             }
                             echo "<option value='".$result->fields['idestado']."'>".$result->fields['estado']."</option>";
                             $result->MoveNext();
                                                      }
                        echo    "</select></td></tr>";
                echo "</table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\" class='btn' onclick=\"insert('doc&&update=1&&id=$id_d');\" value=\"Actualizar \"/>
                <td class=\"botones\"><input id='atra' type=\"button\" class='btn' onclick=\"m2('doc&pag=1');\"  value=\"Regresar \"/></td>
                    </center>
                </div>
                ";
            return false;

             }
            echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;'><legend>Ingresar Documento</legend>
                    <table>
                        <tr><td class=\"zpFormLabel\">Tipo Doc. :</td><td><input type'=text' size='24' id='doc'></td></tr>
                </table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\" class='btn' onclick=\"insert('doc');\" value=\"Agregar \"/>
                <td class=\"botones\"><input  id='atra' type=\"button\" class='btn' onclick=\"m2('doc&pag=1');\"  value=\"Regresar \"/></td>
                    </center>
                </div>
                ";
            break;
//-----------------------------------------------------------------------------------
        case "insert_p":
            if(isset($_GET['update']) && $_GET['update']!==""){
                $id_p=$_GET['update'];
                $sql= $db->Execute("SELECT * FROM personerias WHERE idpersoneria=$id_p");
                    $prs=$sql->fields['personerias'];
                    $est=$sql->fields['idestado'];
                echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;text-align:left;'><legend>Actualizar Tipo</legend>
                    <table>
                        <tr><td class=\"zpFormLabel\">Tipo Persona:</td><td><input type'=text' size='24' id='pers' value='$prs'></td></tr>";
                        $result = $db->Execute("SELECT * FROM estados");
                        echo "<tr><td class='zpFormLabel'>Estado :</td><td><select id='p_estado' >";
                         while (!$result->EOF) {
                             if( $est == $result->fields['idestado'] ){
                                echo "<option value='".$result->fields['idestado']."' SELECTED>".$result->fields['estado']."</option>";
                                $result->MoveNext();
								continue;
                             }
                             echo "<option value='".$result->fields['idestado']."'>".$result->fields['estado']."</option>";
                             $result->MoveNext();
                        }
                        echo    "</select></td></tr>";
                echo "</table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\" class='btn' onclick=\"insert('pers&&update=1&&id=$id_p');\" value=\"Actualizar \"/>
                <input type=\"button\" id='atra' class='btn' onclick=\"m2('pers&pag=1');\" value=\"Regresar \"/>
                    </center>
                </div>
                ";
                return false;
                }
            echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;'><legend>Agregar Tipo</legend>
                    <table>
                        <tr><td class=\"zpFormLabel\">Tipo Persona:</td><td><input type'=text' size='24' id='pers'></td></tr>
                    </table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\" class='btn' onclick=\"insert('pers');\" value=\"Agregar \"/>
                <input type=\"button\" id='atra' class='btn' onclick=\"m2('pers&pag=1');\" value=\"Regresar \"/>
                    </center>
                </div>
                ";
            break;
//-------------------------------------------------------------------------------------------
        case "insert_m":
            if(isset($_GET['update']) && $_GET['update']!==""){
                $id_m=$_GET['update'];
                $sql= $db->Execute("SELECT * FROM monedas WHERE idmoneda=$id_m");
                    $mnd=$sql->fields['monedas'];
					$simb=$sql->fields['simbolo'];
                    $est=$sql->fields['idestado'];
					
            echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;text-align:left;'><legend>Agregar Tipo Moneda</legend>
                    <table>
                        <tr><td class=\"zpFormLabel\">Tipo Moneda:</td><td><input type'=text' size='24' id='moneda' name='t_m' value='$mnd'></td></tr>
                        <tr><td class=\"zpFormLabel\">Simbolo:</td><td><input type'=text' size='24' id='s_moneda' name='s_m' value='$simb'></td></tr>";
						$result = $db->Execute("SELECT * FROM estados");
                        echo "<tr><td class='zpFormLabel'>Estado :</td><td><select id='mn_estado' >";
                         while (!$result->EOF) {
                             if( $est == $result->fields['idestado'] ){
                                echo "<option value='".$result->fields['idestado']."' SELECTED>".$result->fields['estado']."</option>";
                                $result->MoveNext();
								continue;
                             }
                             echo "<option value='".$result->fields['idestado']."'>".$result->fields['estado']."</option>";
                             $result->MoveNext();
                                                      }
                        echo    "</select></td></tr>";
            echo "</table>
                </fieldset>
                 <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                 <input type=\"button\" class='btn' onclick=\"insert('moneda&&update=1&&id=$id_m');\" value=\"Agregar \"/>
                <input type=\"button\" id='atra' class='btn' onclick=\"m2('money&pag=1');\" value=\"Regresar \"/>
                    </center>
                </div>
                ";
            return false;

            }
            echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;'><legend>Agregar Tipo Moneda</legend>
                    <table>
                        <tr><td class=\"zpFormLabel\">Tipo Moneda:</td><td><input type'=text' size='24' id='moneda' name='t_m'></td></tr>
						<tr><td class=\"zpFormLabel\">Simbolo:</td><td><input type'=text' size='24' id='s_moneda' name='s_m'></td></tr>
                    </table>
                </fieldset>
                 <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                 <input type=\"button\" class='btn' onclick=\"insert('moneda');\" value=\"Agregar \"/>
                <input type=\"button\" id='atra' class='btn' onclick=\"m2('money&pag=1');\" value=\"Regresar \"/>
                    </center>
                </div>
                ";
            break;
//---------------------------------------------------------------------------------------------------------
        case "insert_pa":
            if(isset($_GET['update']) && $_GET['update']!==""){
                $id_p=$_GET['update'];
                $sql= $db->Execute("SELECT * FROM parentescos WHERE idparentesco=$id_p");
                    $prs=$sql->fields['parentescos'];
                    $est=$sql->fields['idestado'];
                echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;text-align:left;'><legend>Actualizar Parentesco</legend>
                    <table>
                        <tr><td class=\"zpFormLabel\">Parentesco:</td><td><input type'=text' size='24' id='parent' name='t_p' value='$prs'></td></tr>";
                $result = $db->Execute("SELECT * FROM estados");
                        echo "<tr><td class='zpFormLabel'>Estado :</td><td><select id='pr_estado' >";
                         while (!$result->EOF) {
                             if( $est == $result->fields['idestado'] ){
                                echo "<option value='".$result->fields['idestado']."' SELECTED>".$result->fields['estado']."</option>";
                                $result->MoveNext();
                             }
                             echo "<option value='".$result->fields['idestado']."'>".$result->fields['estado']."</option>";
                             $result->MoveNext();
                                                      }
                        echo    "</select></td></tr>";
                echo "</table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                 <input type=\"button\" class='btn' onclick=\"insert('parent&&update=1&&id=$id_p');\" value=\"Actualizar \"/>
                <input type=\"button\" id='atra' class='btn' onclick=\"m2('parent&pag=1');\" value=\"Regresar \"/>
                    </center>
                </div>
                ";
                return false;
            }

            echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;'><legend>Agregar Tipo Parentesco</legend>
                    <table>
                        <tr><td class=\"zpFormLabel\">Parentesco:</td><td><input type'=text' size='24' id='parent' name='t_p'></td></tr>
                    </table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                 <input type=\"button\" class='btn' onclick=\"insert('parent');\" value=\"Agregar \"/>
                <input type=\"button\" id='atra' class='btn' onclick=\"m2('parent&pag=1');\" value=\"Regresar \"/>
                    </center>
                </div>
                ";
            break;
//-------------------------------------------------------------------------------------------------------
         case "insert_n":
            if(isset($_GET['update']) && $_GET['update']!==""){
                $id_nv=$_GET['update'];
                $sql= $db->Execute("SELECT * FROM niveles WHERE idnivel=$id_nv");
                   $nv= $sql->fields['nivel'];
                   $id_es=$sql->fields['idestado'];
                   
                 echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;text-align:left;'><legend>Actualizar Nivel</legend>
                    <table>
                        <tr><td class=\"zpFormLabel\">Tipo Nivel:</td><td><input type'=text' size='24' id='nivel' name='nivel' value='$nv'></td></tr>";
                 $result = $db->Execute("SELECT * FROM estados");
                        echo "<tr><td class='zpFormLabel'>Estado :</td><td><select id='n_estado' >";
                         while (!$result->EOF) {
                             if( $id_es == $result->fields['idestado'] ){
                                echo "<option value='".$result->fields['idestado']."' SELECTED>".$result->fields['estado']."</option>";
                                $result->MoveNext();
                             }
                             echo "<option value='".$result->fields['idestado']."'>".$result->fields['estado']."</option>";
                             $result->MoveNext();
                                                      }
                        echo    "</select></td></tr>";

                echo "</table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\" class='btn' onclick=\"insert('nivel&&update=1&&id=$id_nv');\" value=\"Actualizar \"/>
                <input type=\"button\" id='atra' class='btn' onclick=\"m2('nivel&pag=1');\" value=\"Regresar \"/>
                    </center>
                </div>
                ";
				return false;
            }
            echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;'><legend>Agregar Nivel</legend>
                    <table>
                        <tr><td class=\"zpFormLabel\">Tipo Nivel:</td><td><input type'=text' size='24' id='nivel' name='nivel'></td></tr>
                    </table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\" class='btn' onclick=\"insert('nivel');\" value=\"Agregar \"/>
                <input type=\"button\" id='atra' class='btn' onclick=\"m2('nivel&pag=1');\" value=\"Regresar \"/>
                    </center>
                </div>
                ";
            break;
//----------------------------------------------------------------------------------------------------
        case "insert_con":
            if(isset($_GET['update']) && $_GET['update']!==""){
                $id_c=$_GET['update'];
                $sql= $db->Execute("SELECT * FROM contactabilidad WHERE idcontactabilidad=$id_c");
                    $cntb=$sql->fields['contactabilidad'];
                    $est=$sql->fields['idestado'];
                    
                echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;text-align:left;'><legend>Actualizar Contactabilidad</legend>
                    <table>
                        <tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text' size='24' id='conta' value='$cntb' ></td></tr>
               <tr><td class=\"zpFormLabel\">Tipo :</td><td><select id='tp_cont' >
								<option value=''>..Seleccione</option>";
							$t_con= $db->Execute("SELECT * FROM tipo_contactabilidad where idestado!=0");
							while (!$t_con->EOF) {
								if($sql->fields['idtipocontactabilidad']==$t_con->fields['idtipocontactabilidad']){
									echo "<option value='".$t_con->fields['idtipocontactabilidad']."' SELECTED>".$t_con->fields['tipocontactabilidad']."</option>";
									$t_con->MoveNext();
								}else{
									echo "<option value='".$t_con->fields['idtipocontactabilidad']."'>".$t_con->fields['tipocontactabilidad']."</option>";
									$t_con->MoveNext();
								}
                            }
				echo		"</select></td>";

			   $result = $db->Execute("SELECT * FROM estados");
                        echo "<tr><td class='zpFormLabel'>Estado :</td><td><select id='ctb_estado' >";
                         while (!$result->EOF) {
                             if( $est == $result->fields['idestado'] ){
                                echo "<option value='".$result->fields['idestado']."' SELECTED>".$result->fields['estado']."</option>";
                                $result->MoveNext();
                             }
                             echo "<option value='".$result->fields['idestado']."'>".$result->fields['estado']."</option>";
                             $result->MoveNext();
                                                      }
                        echo    "</select></td></tr>";

                echo "</table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\" class='btn' onclick=\"insert('cont&&update=1&&id=$id_c');\" value=\"Actualizar \"/>
                <input type=\"button\" id='atra' class='btn' onclick=\"m2('conta&pag=1');\" value=\"Regresar \"/>
                    </center>
                </div>
                ";
                return false;
            }
            echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;'><legend>Agregar Contactabilidad</legend>
                    <table>
                        <tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text' size='24' id='conta' ></td></tr>
						<tr><td class=\"zpFormLabel\">Tipo:</td>
							<td><select id='tp_cont' >
								<option value=''>..Seleccione</option>";
							$t_con= $db->Execute("SELECT * FROM tipo_contactabilidad where idestado!=0");
							while (!$t_con->EOF) {
								echo "<option value='".$t_con->fields['idtipocontactabilidad']."'>".$t_con->fields['tipocontactabilidad']."</option>";
								$t_con->MoveNext();
                            }
			echo		"</select></td>
						</tr>
						</table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\" class='btn' onclick=\"insert('cont');\" value=\"Agregar \"/>
                <input type=\"button\" id='atra'  class='btn' onclick=\"m2('conta&pag=1');\" value=\"Regresar \"/>
                    </center>
                </div>

                ";
            break;
//----------------------------------------------------------------------------------------------------

        case "insert_ubi":
            if(isset($_GET['update']) && $_GET['update']!==""){
                $id_ub=$_GET['update'];
                $sql= $db->Execute("SELECT * FROM ubicabilidad WHERE idubicabilidad=$id_ub");
                    $ubic=$sql->fields['ubicabilidad'];
                    $est=$sql->fields['idestado'];
                echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;text-align:left;'><legend>Agregar Ubicabilidad</legend>
                    <table>
                        <tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text' size='24' id='ubic' value='$ubic' ></td></tr>";
                $result = $db->Execute("SELECT * FROM estados");
                        echo "<tr><td class='zpFormLabel'>Estado :</td><td><select id='ub_estado' >";
                         while (!$result->EOF) {
                             if( $est == $result->fields['idestado'] ){
                                echo "<option value='".$result->fields['idestado']."' SELECTED>".$result->fields['estado']."</option>";
                                $result->MoveNext();
                             }
                             echo "<option value='".$result->fields['idestado']."'>".$result->fields['estado']."</option>";
                             $result->MoveNext();
                                                      }
                        echo    "</select></td></tr>";
                echo "</table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\" class='btn' onclick=\"insert('ubic&&update=1&&id=$id_ub');\" value=\"Actualizar \"/>
                <input type=\"button\" id='atra' class='btn' onclick=\"m2('ubic&pag=1');\" value=\"Regresar \"/>
                    </center>
                </div>
                ";
                return false;
            }
            echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;'><legend>Agregar Ubicabilidad</legend>
                    <table>
                        <tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text' size='24' id='ubic' ></td></tr>
                    </table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\" class='btn' onclick=\"insert('ubic');\" value=\"Agregar \"/>
                <input type=\"button\" id='atra' class='btn' onclick=\"m2('ubic&pag=1');\" value=\"Regresar \"/>
                    </center>
                </div>

                ";
            break;
//----------------------------------------------------------------------------------------------------

        case "insert_ord":
            if(isset($_GET['update']) && $_GET['update']!==""){
                $id_nv=$_GET['update'];
                $sql= $db->Execute("SELECT * FROM origen_direcciones WHERE idorigendireccion=$id_nv");
                   $o_d=$sql->fields['origendireccion'];
                   $i_es=$sql->fields['idestado'];

                
                echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;text-align:left;'><legend>Actualizar Origen Direccion</legend>
                    <table>
                        <tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text' size='24' id='or_d' value='$o_d' ></td></tr>";
                        $result = $db->Execute("SELECT * FROM estados");
                        echo "<tr><td class='zpFormLabel'>Estado :</td><td><select id='od_estado' >";
                         while (!$result->EOF) {
                             if( $i_es == $result->fields['idestado'] ){
                                echo "<option value='".$result->fields['idestado']."' SELECTED>".$result->fields['estado']."</option>";
                                $result->MoveNext();
                             }
                             echo "<option value='".$result->fields['idestado']."'>".$result->fields['estado']."</option>";
                             $result->MoveNext();
                                                      }
                        echo    "</select></td></tr>";
                echo "</table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\" class='btn' onclick=\"insert('or_d&&update=1&&id=$id_nv');\" value=\"Agregar \"/>
                <input type=\"button\" id='atra' class='btn' onclick=\"m2('or_dir&pag=1');\" value=\"Regresar \"/>
                    </center>
                </div>
                ";
                return false;
            }
            echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;'><legend>Agregar Origen Direccion</legend>
                    <table>
                        <tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text' size='24' id='or_d' ></td></tr>
                    </table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\" class='btn' onclick=\"insert('or_d');\" value=\"Agregar \"/>
                <input type=\"button\" id='atra' class='btn' onclick=\"m2('or_dir&pag=1');\" value=\"Regresar \"/>
                    </center>
                </div>

                ";
            break;
//----------------------------------------------------------------------------------------------------

        case "insert_ort":
            if(isset($_GET['update']) && $_GET['update']!==""){
                $id_ot=$_GET['update'];
                $sql= $db->Execute("SELECT * FROM origen_telefonos WHERE idorigentelefono=$id_ot");
                   $o_t=$sql->fields['origentelefono'];
                   $i_es=$sql->fields['idestado'];

                   echo "<div id=\"ykBody\">
                    <center>
                    <fieldset style='width:300px;text-align:left;'><legend>Actualizar Origen Telefono</legend>
                        <table>
                            <tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text' size='24' id='or_t' value='$o_t' ></td></tr>";
                    $result = $db->Execute("SELECT * FROM estados");
                        echo "<tr><td class='zpFormLabel'>Estado :</td><td><select id='ot_estado' >";
                         while (!$result->EOF) {
                             if( $i_es == $result->fields['idestado'] ){
                                echo "<option value='".$result->fields['idestado']."' SELECTED>".$result->fields['estado']."</option>";
                                $result->MoveNext();
                             }
                             echo "<option value='".$result->fields['idestado']."'>".$result->fields['estado']."</option>";
                             $result->MoveNext();
                                                      }
                        echo    "</select></td></tr>";
                    echo "</table>
                    </fieldset>
                    <div id=\"ykBodys\" style=\"color:red;\">
                    </div>
                    <input type=\"button\" class='btn' onclick=\"insert('or_t&&update=1&&id=$id_ot');\" value=\"Actualizar \"/>
                    <input type=\"button\" id='atra' class='btn' onclick=\"m2('or_tel&pag=1');\" value=\"Regresar \"/>
                        </center>
                    </div>
                    ";
                   return false;
            }
            echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;'><legend>Actualizar Origen Telefono</legend>
                    <table>
                        <tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text' size='24' id='or_t' ></td></tr>
                    </table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\" class='btn' onclick=\"insert('or_t');\" value=\"Agregar \"/>
                <input type=\"button\" id='atra' class='btn' onclick=\"m2('or_tel&pag=1');\" value=\"Regresar \"/>
                    </center>
                </div>

                ";
            break;
	//--------------------------------------------------------------------
	 case "insert_act":
		if(isset($_GET['update']) && $_GET['update']!==""){
                $id_a=$_GET['update'];
                $sql= $db->Execute("SELECT * FROM actividades WHERE idactividad=$id_a");
                   $act=$sql->fields['actividad'];
                   $i_es=$sql->fields['idestado'];

                   echo "<div id=\"ykBody\">
                    <center>
                    <fieldset style='width:300px;text-align:left;'><legend>Actualizar Actividad</legend>
                        <table>
                            <tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text' size='24' id='act' value='$act' ></td></tr>";
                    $result = $db->Execute("SELECT * FROM estados");
                        echo "<tr><td class='zpFormLabel'>Estado :</td><td><select id='act_est' >";
                         while (!$result->EOF) {
                             if( $i_es == $result->fields['idestado'] ){
                                echo "<option value='".$result->fields['idestado']."' SELECTED>".$result->fields['estado']."</option>";
                                $result->MoveNext();
								continue;
                             }
                             echo "<option value='".$result->fields['idestado']."'>".$result->fields['estado']."</option>";
                             $result->MoveNext();
                                                      }
                        echo    "</select></td></tr>";
                    echo "</table>
                    </fieldset>
                    <div id=\"ykBodys\" style=\"color:red;\">
                    </div>
                    <input type=\"button\" class='btn' onclick=\"insert('p_act&&update=1&&id=$id_a');\" value=\"Actualizar \"/>
                    <input type=\"button\" id='atra' class='btn' onclick=\"m2('actvd&pag=1');\" value=\"Regresar \"/>
                        </center>
                    </div>
                    ";
                   return false;
            }
            echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;'><legend>Ingresar Actividad</legend>
                    <table>
                        <tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text' size='24' id='act' ></td></tr>
                    </table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\" class='btn' onclick=\"insert('p_act');\" value=\"Agregar \"/>
                <input type=\"button\" id='atra' class='btn' onclick=\"m2('actvd&pag=1');\" value=\"Regresar \"/>
                    </center>
                </div>

                ";
            break;
	//--------------------------------------------------------------------
	 case "insert_tpredio":
		if(isset($_GET['update']) && $_GET['update']!==""){
                $id_a=$_GET['update'];
                $sql= $db->Execute("SELECT * FROM tipo_predio WHERE idpredio=$id_a");
                   $predio=$sql->fields['tipo_predio'];
                   $i_es=$sql->fields['idestado'];

                   echo "<div id=\"ykBody\">
                    <center>
                    <fieldset style='width:300px;text-align:left;'><legend>Actualizar Tipo Predio</legend>
                        <table>
                            <tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text' size='24' id='c_pre' value='$predio' ></td></tr>";
                    $result = $db->Execute("SELECT * FROM estados");
                        echo "<tr><td class='zpFormLabel'>Estado :</td><td><select id='c_p_estado' >";
                         while (!$result->EOF) {
                             if( $i_es == $result->fields['idestado'] ){
                                echo "<option value='".$result->fields['idestado']."' SELECTED>".$result->fields['estado']."</option>";
                                $result->MoveNext();
								continue;
                             }
                             echo "<option value='".$result->fields['idestado']."'>".$result->fields['estado']."</option>";
                             $result->MoveNext();
                                                      }
                        echo    "</select></td></tr>";
                    echo "</table>
                    </fieldset>
                    <div id=\"ykBodys\" style=\"color:red;\">
                    </div>
                    <input type=\"button\" class='btn' onclick=\"insert('i_pre&&update=1&&id=$id_a');\" value=\"Actualizar \"/>
                    <input type=\"button\" id='atra' class='btn' onclick=\"m2('tpredio&pag=1');\" value=\"Regresar \"/>
                        </center>
                    </div>
                    ";
                   return false;
            }
            echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;'><legend>Ingresar Tipo Predio</legend>
                    <table>
                        <tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text' size='24' id='c_pre' ></td></tr>
                    </table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\" class='btn' onclick=\"insert('i_pre');\" value=\"Agregar \"/>
                <input type=\"button\" id='atra' class='btn' onclick=\"m2('tpredio&pag=1');\" value=\"Regresar \"/>
                    </center>
                </div>

                ";
            break;
	//--------------------------------------------------------------------
	 case "insert_mpredio":
		if(isset($_GET['update']) && $_GET['update']!==""){
                $id_a=$_GET['update'];
                $sql= $db->Execute("SELECT * FROM material_predio WHERE idmaterial_predio=$id_a");
                   $predio=$sql->fields['material'];
                   $i_es=$sql->fields['idestado'];

                   echo "<div id=\"ykBody\">
                    <center>
                    <fieldset style='width:300px;text-align:left;'><legend>Actualizar Material Predio</legend>
                        <table>
                            <tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text' size='24' id='c_mpre' value='$predio' ></td></tr>";
                    $result = $db->Execute("SELECT * FROM estados");
                        echo "<tr><td class='zpFormLabel'>Estado :</td><td><select id='c_pm_estado' >";
                         while (!$result->EOF) {
                             if( $i_es == $result->fields['idestado'] ){
                                echo "<option value='".$result->fields['idestado']."' SELECTED>".$result->fields['estado']."</option>";
                                $result->MoveNext();
								continue;
                             }
                             echo "<option value='".$result->fields['idestado']."'>".$result->fields['estado']."</option>";
                             $result->MoveNext();
                                                      }
                        echo    "</select></td></tr>";
                    echo "</table>
                    </fieldset>
                    <div id=\"ykBodys\" style=\"color:red;\">
                    </div>
                    <input type=\"button\" class='btn' onclick=\"insert('i_mpre&&update=1&&id=$id_a');\" value=\"Actualizar \"/>
                    <input type=\"button\" id='atra' class='btn' onclick=\"m2('mpredio&pag=1');\" value=\"Regresar \"/>
                        </center>
                    </div>
                    ";
                   return false;
            }
            echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;'><legend>Ingresar Material Predio</legend>
                    <table>
                        <tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text' size='24' id='c_mpre' ></td></tr>
                    </table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\" class='btn' onclick=\"insert('i_mpre');\" value=\"Agregar \"/>
                <input type=\"button\" id='atra' class='btn' onclick=\"m2('mpredio&pag=1');\" value=\"Regresar \"/>
                    </center>
                </div>

                ";
            break;
//--------------------------------------------------------------------
	 case "insert_npisos":
		if(isset($_GET['update']) && $_GET['update']!==""){
                $id_a=$_GET['update'];
                $sql= $db->Execute("SELECT * FROM nro_pisos WHERE idnro_pisos=$id_a");
                   $piso=$sql->fields['piso'];
                   $i_es=$sql->fields['idestado'];

                   echo "<div id=\"ykBody\">
                    <center>
                    <fieldset style='width:300px;text-align:left;'><legend>Actualizar Nro Pisos</legend>
                        <table>
                            <tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text' size='24' id='c_npisos' value='$piso' ></td></tr>";
                    $result = $db->Execute("SELECT * FROM estados");
                        echo "<tr><td class='zpFormLabel'>Estado :</td><td><select id='c_ps_estado' >";
                         while (!$result->EOF) {
                             if( $i_es == $result->fields['idestado'] ){
                                echo "<option value='".$result->fields['idestado']."' SELECTED>".$result->fields['estado']."</option>";
                                $result->MoveNext();
								continue;
                             }
                             echo "<option value='".$result->fields['idestado']."'>".$result->fields['estado']."</option>";
                             $result->MoveNext();
                                                      }
                        echo    "</select></td></tr>";
                    echo "</table>
                    </fieldset>
                    <div id=\"ykBodys\" style=\"color:red;\">
                    </div>
                    <input type=\"button\" class='btn' onclick=\"insert('i_npisos&&update=1&&id=$id_a');\" value=\"Actualizar \"/>
                    <input type=\"button\" id='atra' class='btn' onclick=\"m2('npisos&pag=1');\" value=\"Regresar \"/>
                        </center>
                    </div>
                    ";
                   return false;
            }
            echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;'><legend>Ingresar Nro Pisos</legend>
                    <table>
                        <tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text' size='24' id='c_npisos' ></td></tr>
                    </table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\" class='btn' onclick=\"insert('i_npisos');\" value=\"Agregar \"/>
                <input type=\"button\" id='atra' class='btn' onclick=\"m2('npisos&pag=1');\" value=\"Regresar \"/>
                    </center>
                </div>

                ";
            break;
//--------------------------------------------------------------------
	 case "insert_cpared":
		if(isset($_GET['update']) && $_GET['update']!==""){
                $id_a=$_GET['update'];
                $sql= $db->Execute("SELECT * FROM colores_pared WHERE idcolor_pared=$id_a");
                   $color=$sql->fields['color'];
                   $i_es=$sql->fields['idestado'];

                   echo "<div id=\"ykBody\">
                    <center>
                    <fieldset style='width:300px;text-align:left;'><legend>Actualizar Color Pared</legend>
                        <table>
                            <tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text' size='24' id='c_cpared' value='$color' ></td></tr>";
                    $result = $db->Execute("SELECT * FROM estados");
                        echo "<tr><td class='zpFormLabel'>Estado :</td><td><select id='c_cp_estado' >";
                         while (!$result->EOF) {
                             if( $i_es == $result->fields['idestado'] ){
                                echo "<option value='".$result->fields['idestado']."' SELECTED>".$result->fields['estado']."</option>";
                                $result->MoveNext();
								continue;
                             }
                             echo "<option value='".$result->fields['idestado']."'>".$result->fields['estado']."</option>";
                             $result->MoveNext();
                                                      }
                        echo    "</select></td></tr>";
                    echo "</table>
                    </fieldset>
                    <div id=\"ykBodys\" style=\"color:red;\">
                    </div>
                    <input type=\"button\" class='btn' onclick=\"insert('i_cpared&&update=1&&id=$id_a');\" value=\"Actualizar \"/>
                    <input type=\"button\" id='atra' class='btn' onclick=\"m2('cpared&pag=1');\" value=\"Regresar \"/>
                        </center>
                    </div>
                    ";
                   return false;
            }
            echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;'><legend>Ingresar Color Pared</legend>
                    <table>
                        <tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text' size='24' id='c_cpared' ></td></tr>
                    </table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\" class='btn' onclick=\"insert('i_cpared');\" value=\"Agregar \"/>
                <input type=\"button\" id='atra' class='btn' onclick=\"m2('cpared&pag=1');\" value=\"Regresar \"/>
                    </center>
                </div>

                ";
            break;
//----------------------------------------------------------------------------------------
 case "insert_ft":
		if(isset($_GET['update']) && $_GET['update']!==""){
                $id_ft=$_GET['update'];
                $sql= $db->Execute("SELECT * FROM fuentes WHERE idfuente=$id_ft");
                   $fuente=$sql->fields['fuente'];
                   $i_es=$sql->fields['idestado'];

                   echo "<div id=\"ykBody\">
                    <center>
                    <fieldset style='width:300px;text-align:left;'><legend>Actualizar Fuente</legend>
                        <table>
                            <tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text' size='24' id='fuente' value='$fuente' ></td></tr>";
                    $result = $db->Execute("SELECT * FROM estados");
                        echo "<tr><td class='zpFormLabel'>Estado :</td><td><select id='ft_estado' >";
                         while (!$result->EOF) {
                             if( $i_es == $result->fields['idestado'] ){
                                echo "<option value='".$result->fields['idestado']."' SELECTED>".$result->fields['estado']."</option>";
                                $result->MoveNext();
								continue;
                             }
                             echo "<option value='".$result->fields['idestado']."'>".$result->fields['estado']."</option>";
                             $result->MoveNext();
                                                      }
                        echo    "</select></td></tr>";
                    echo "</table>
                    </fieldset>
                    <div id=\"ykBodys\" style=\"color:red;\">
                    </div>
                    <input type=\"button\" class='btn' onclick=\"insert('fuente&&update=1&&id=$id_ft');\" value=\"Actualizar \"/>
                    <input type=\"button\" id='atra' class='btn' onclick=\"m2('fuente&pag=1');\" value=\"Regresar \"/>
                        </center>
                    </div>
                    ";
                   return false;
            }
            echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;'><legend>Ingresar Fuente</legend>
                    <table>
                        <tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text' size='24' id='fuente' ></td></tr>
                    </table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\" class='btn' onclick=\"insert('fuente');\" value=\"Agregar \"/>
                <input type=\"button\" id='atra' class='btn' onclick=\"m2('fuente&pag=1');\" value=\"Regresar \"/>
                    </center>
                </div>

                ";
            break;
			//----------------------------------------------------------------------------------------------------

        case "insert_ore":
            if(isset($_GET['update']) && $_GET['update']!==""){
                $id_nv=$_GET['update'];
                $sql= $db->Execute("SELECT * FROM origen_emails WHERE idorigenemail=$id_nv");
                   $o_e=$sql->fields['origenemail'];
                   $i_es=$sql->fields['idestado'];

                
                echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;text-align:left;'><legend>Actualizar Origen Email</legend>
                    <table>
                        <tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text' size='24' id='or_e' value='$o_e' ></td></tr>";
                        $result = $db->Execute("SELECT * FROM estados");
                        echo "<tr><td class='zpFormLabel'>Estado :</td><td><select id='oe_estado' >";
                         while (!$result->EOF) {
                             if( $i_es == $result->fields['idestado'] ){
                                echo "<option value='".$result->fields['idestado']."' SELECTED>".$result->fields['estado']."</option>";
                                $result->MoveNext();
                             }
                             echo "<option value='".$result->fields['idestado']."'>".$result->fields['estado']."</option>";
                             $result->MoveNext();
                                                      }
                        echo    "</select></td></tr>";
                echo "</table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\" class='btn' onclick=\"insert('or_email&&update=1&&id=$id_nv');\" value=\"Agregar \"/>
                <input type=\"button\" id='atra' class='btn' onclick=\"m2('or_email&pag=1');\" value=\"Regresar \"/>
                    </center>
                </div>
                ";
                return false;
            }
            echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;'><legend>Agregar Origen Email</legend>
                    <table>
                        <tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text' size='24' id='or_e' ></td></tr>
                    </table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\" class='btn' onclick=\"insert('or_email');\" value=\"Agregar \"/>
                <input type=\"button\" id='atra' class='btn' onclick=\"m2('or_email&pag=1');\" value=\"Regresar \"/>
                    </center>
                </div>

                ";
            break;
//----------------------------------------------------------------------------------------------------
	        case "insert_tpc":
            if(isset($_GET['update']) && $_GET['update']!==""){
                $id_nv=$_GET['update'];
                $sql= $db->Execute("SELECT * FROM tipo_contactabilidad WHERE idtipocontactabilidad=$id_nv");
                   $tpc=$sql->fields['tipocontactabilidad'];
                   $i_es=$sql->fields['idestado'];

                
                echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;text-align:left;'><legend>Actualizar Tipo Contactabilidad</legend>
                    <table>
                        <tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text' size='24' id='tpc' value='$tpc' ></td></tr>";
                        $result = $db->Execute("SELECT * FROM estados");
                        echo "<tr><td class='zpFormLabel'>Estado :</td><td><select id='tc_estado' >";
                         while (!$result->EOF) {
                             if( $i_es == $result->fields['idestado'] ){
                                echo "<option value='".$result->fields['idestado']."' SELECTED>".$result->fields['estado']."</option>";
                                $result->MoveNext();
                             }
                             echo "<option value='".$result->fields['idestado']."'>".$result->fields['estado']."</option>";
                             $result->MoveNext();
                                                      }
                        echo    "</select></td></tr>";
                echo "</table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\" class='btn' onclick=\"insert('t_conta&&update=1&&id=$id_nv');\" value=\"Agregar \"/>
                <input type=\"button\" id='atra' class='btn' onclick=\"m2('t_conta&pag=1');\" value=\"Regresar \"/>
                    </center>
                </div>
                ";
                return false;
            }
            echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;'><legend>Agregar Tipo Contactabilidad</legend>
                    <table>
                        <tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text' size='24' id='tpc' ></td></tr>
                    </table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\" class='btn' onclick=\"insert('t_conta');\" value=\"Agregar \"/>
                <input type=\"button\" id='atra' class='btn' onclick=\"m2('t_conta&pag=1');\" value=\"Regresar \"/>
                    </center>
                </div>

                ";
            break;
//----------------------------------------------------------------------------------------------------
case "insert_vald":
            if(isset($_GET['update']) && $_GET['update']!==""){
                $id_nv=$_GET['update'];
                $sql= $db->Execute("SELECT * FROM validaciones WHERE idvalidaciones=$id_nv");
                   $vald=$sql->fields['validaciones'];
                   $i_es=$sql->fields['idestado'];

                
                echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;text-align:left;'><legend>Actualizar Validaciones</legend>
                    <table>
                        <tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text' size='24' id='vald' value='$vald' ></td></tr>";
                        $result = $db->Execute("SELECT * FROM estados");
                        echo "<tr><td class='zpFormLabel'>Estado :</td><td><select id='vald_estado' >";
                         while (!$result->EOF) {
                             if( $i_es == $result->fields['idestado'] ){
                                echo "<option value='".$result->fields['idestado']."' SELECTED>".$result->fields['estado']."</option>";
                                $result->MoveNext();
                             }
                             echo "<option value='".$result->fields['idestado']."'>".$result->fields['estado']."</option>";
                             $result->MoveNext();
                                                      }
                        echo    "</select></td></tr>";
                echo "</table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\" class='btn' onclick=\"insert('vald&&update=1&&id=$id_nv');\" value=\"Agregar \"/>
                <input type=\"button\" id='atra' class='btn' onclick=\"m2('valid&pag=1');\" value=\"Regresar \"/>
                    </center>
                </div>
                ";
                return false;
            }
            echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;'><legend>Agregar valdalidacion</legend>
                    <table>
                        <tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text' size='24' id='vald' ></td></tr>
                    </table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\" class='btn' onclick=\"insert('vald');\" value=\"Agregar \"/>
                <input type=\"button\" id='atra' class='btn' onclick=\"m2('valid&pag=1');\" value=\"Regresar \"/>
                    </center>
                </div>

                ";
            break;
//----------------------------------------------------------------------------------------------------
	 
    }
}
?>
