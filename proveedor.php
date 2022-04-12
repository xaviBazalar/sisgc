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
$n = 0;
$pag=0;
$r_pag=20;// total registros x pag

if($_GET['pag']!=1){
	$n=$_GET['pag']-1;
	$n=$n*$r_pag;
}
						
if(isset($_GET['parametros'])){
    $id_param=$_GET['parametros'];
    switch($id_param){
		        case "tip_cart":
			
				
				$sql="SELECT * from tipo_carteras ";
					
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
				echo "<input type=\"button\" class='btn' onclick=\"m3('i_tc');\" value=\"Agregar Nuevo \"/>";
				return false;
			}		
            echo "<div id=\"ykBody\">
                <center>
                <fieldset ><legend>Tipo Carteras</legend>
                    <div id=\"design1\">
                    <table style='text-align:left;' ><th>Nº</th><th>Id</th><th>Tipo Cartera</th><th>Estado</th>";
                    while (!$result->EOF) {
                        //for ($i=0, $max=$result->FieldCount(); $i < $max; $i++)
                        echo "<tr><td>".++$n."</td>
                            <td style='background-color:white;'>".$result->fields['idtipocartera']."</td>
							<td style='background-color:white;'><a style='text-decoration:underline;cursor:pointer;' onclick=\"m3('i_tc&&update=".$result->fields['idtipocartera']."');\">".$result->fields['tipocartera']."<a/></td>
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
			
					<td colspan="5">
						<?php //if(!isset($_GET['ndoc']) and !isset($_GET['name'])){?>
							<?php
							
							if($_GET['pag']!=1 && $t_p!=0 )
									echo "<a onclick=\"m3('tip_cart&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"m3('tip_cart&&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"m3('tip_cart&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"m3('tip_cart&&pag=$t_p');\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=tip_cart'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!--Fin Pie Paginacion-->					
<?php             echo"  </table>
                    </div>
                </fieldset>
                <input type=\"button\" class='btn' onclick=\"m3('i_tc');\" value=\"Agregar Nuevo \"/>
                    </center>
                </div>
                ";
            break;
//----------------------------------------------------------------------------------------------------------
        case "provee":
			$sql="SELECT pr.*,d.doi, pe.personerias, e.estado
                                    FROM proveedores pr
                                    JOIN doi d ON pr.iddoi=d.iddoi
                                    JOIN personerias pe ON pr.idpersoneria=pe.idpersoneria
                                    JOIN estados e ON pr.idestado=e.idestado
									order by pr.proveedor";
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
            //$result2 = $db->Execute("SELECT * FROM doi");
			if(!$result){
						echo "<input type=\"button\" class='btn' onclick=\"m3('i_p');\" value=\"Agregar Nuevo \"/>";
						return false;
						}
            echo "<div id=\"ykBody\">
                <center>
                <fieldset style=''><legend>Proveedores</legend>
                    <div id=\"design1\">
                    <table style='text-align:left;'><th>Nº</th><th>Id</th><th>Proveedor</th><th>Personeria</th><th>Tipo Documento</th><th>N° Documento</th><th>Teléfono</th><th>Email</th><th>Contacto</th><th>Observación</th><th>Estado</th>";
                    while (!$result->EOF) {
                        //for ($i=0, $max=$result->FieldCount(); $i < $max; $i++)
                            echo "<tr><td>".++$n."</td>
                                <td style='background-color:white;'>".$result->fields['idproveedor']."</td>
                                <td style='background-color:white;'><a style='text-decoration:underline;cursor:pointer;' onclick=\"m3('i_p&&update=".$result->fields['idproveedor']."')\">".$result->fields['proveedor']."</a></td>";
                            echo "<td style='background-color:white;'>".$result->fields['personerias']."</td>";
                            echo "<td style='background-color:white;'>".$result->fields['doi']."</td>";
                            echo "<td style='background-color:white;'>".$result->fields['documento']."</td>
                            <td style='background-color:white;'>".$result->fields['telefonos']."</td>
                            <td style='background-color:white;'>".$result->fields['email']."</td>
                            <td style='background-color:white;'>".$result->fields['contacto']."</td>
                            <td style='background-color:white;'>".$result->fields['observacion']."</td>
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
			
					<td colspan="11">
						<?php //if(!isset($_GET['ndoc']) and !isset($_GET['name'])){?>
							<?php
							
							if($_GET['pag']!=1 && $t_p!=0 )
									echo "<a onclick=\"m3('provee&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"m3('provee&&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"m3('provee&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"m3('provee&&pag=$t_p');\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=proveedor'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!--Fin Pie Paginacion-->	

<?php            echo  "</table>
                    </div>
                </fieldset>
                <input type=\"button\" class='btn' onclick=\"m3('i_p');\" value=\"Agregar Nuevo \"/>
                    </center>
                </div>
                ";
            break;
//------------------------------------------------------------------------------------------------------------
        case "cart":
			
				
				$sql="SELECT p.proveedor,c.*,e.estado FROM carteras c
                    JOIN proveedores p ON c.idproveedor=p.idproveedor
                    JOIN estados e ON c.idestado=e.idestado
                    ORDER BY proveedor ASC,c.cartera";
					
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
				echo "<input type=\"button\" class='btn' onclick=\"m3('i_c');\" value=\"Agregar Nuevo \"/>";
				return false;
			}		
            echo "<div id=\"ykBody\">
                <center>
                <fieldset ><legend>Carteras</legend>
                    <div id=\"design1\">
                    <table style='text-align:left;' ><th>Nº</th><th>Id</th><th>Proveedor</th><th>Cartera</th><th>Estado</th>";
                    while (!$result->EOF) {
                        //for ($i=0, $max=$result->FieldCount(); $i < $max; $i++)
                        echo "<tr><td>".++$n."</td>
                            <td style='background-color:white;'>".$result->fields['idcartera']."</td>
                            <td style='background-color:white;'>".$result->fields['proveedor']."</td>
                            <td style='background-color:white;'><a style='text-decoration:underline;cursor:pointer;' onclick=\"m3('i_c&&update=".$result->fields['idcartera']."');\">".$result->fields['cartera']."<a/></td>
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
			
					<td colspan="5">
						<?php //if(!isset($_GET['ndoc']) and !isset($_GET['name'])){?>
							<?php
							
							if($_GET['pag']!=1 && $t_p!=0 )
									echo "<a onclick=\"m3('cart&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"m3('cart&&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"m3('cart&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"m3('cart&&pag=$t_p');\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=cartera'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!--Fin Pie Paginacion-->					
<?php             echo"  </table>
                    </div>
                </fieldset>
                <input type=\"button\" class='btn' onclick=\"m3('i_c');\" value=\"Agregar Nuevo \"/>
                    </center>
                </div>
                ";
            break;
//----------------------------------------------------------------------------------------------------------
        case "pro":
			$sql="SELECT pr.*, po.proveedor,e.estado, s.segmentos
                                    FROM productos pr
                                    JOIN proveedores po ON pr.idproveedor=po.idproveedor
                                    JOIN segmentos s ON pr.idsegmento=s.idsegmento
                                    JOIN estados e ON pr.idestado=e.idestado
									order by po.proveedor asc,pr.producto";
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
						echo "<input type=\"button\" class='btn' onclick=\"m3('i_pr');\" value=\"Agregar Nuevo \"/>";
						return false;
						}
            echo "<div id=\"ykBody\">
                <center>
                <fieldset ><legend>Productos</legend>
                    <div id=\"design1\">
                    <table style='text-align:left;'><tr><th>Nº</th><th>Id</th><th>Proveedor</th><th>Producto</th><th>Segmento</th><th>Estado</th></tr>";
                    while (!$result->EOF) {
                        $id_pr=$result->fields['idproducto'];
                        //for ($i=0, $max=$result->FieldCount(); $i < $max; $i++)
                        echo "<tr><td>".++$n."</td>
                            <td style='background-color:white;'>".$result->fields['idproducto']."</td>
                        <td style='background-color:white;'>".$result->fields['proveedor']."</td>
                        <td style='background-color:white;'><a style='text-decoration:underline;cursor:pointer;' onclick=\"m3('i_pr&&update=$id_pr');\">".$result->fields['producto']."<a/></td>
                        <td style='background-color:white;'>".$result->fields['segmentos']."</td>
                        <td style='background-color:white;'>";
							if($result->fields['idestado']== "1"){
								echo "<img src='imag/icons/estado_1.png'/>";
							}else{
								echo "<img src='imag/icons/estado_2.png'/>";
							}
                        echo "</td>";
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
									echo "<a onclick=\"m3('pro&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"m3('pro&&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"m3('pro&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"m3('pro&&pag=$t_p');\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=producto'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!--Fin Pie Paginacion-->		
					
<?php               echo" </tr></table>
                    </div>
                </fieldset>
                <input type=\"button\" class='btn' onclick=\"m3('i_pr');\" value=\"Agregar Nuevo \"/>
                    </center>
                </div>
                ";
            break;
//----------------------------------------------------------------------------------------------------------
        case "seg":
			$sql="SELECT s.*,e.estado FROM segmentos s
                  JOIN estados e ON s.idestado=e.idestado
				  order by s.segmentos";
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
						echo "<input type=\"button\" class='btn' onclick=\"m3('i_s');\" value=\"Agregar Nuevo \"/>";
						return false;
						}
            echo "<div id=\"ykBody\">
                <center>
                <fieldset ><legend>Segmento</legend>
                    <div id=\"design1\">
                    <table style='text-align:left;'><th>Nº</th><th>Id</th><th>Segmento</th><th>Estado</th>";
                    while (!$result->EOF) {
                          $id_s=$result->fields['idsegmento'];
                        //for ($i=0, $max=$result->FieldCount(); $i < $max; $i++)
                        echo "<tr>
                            <td>".++$n."</td>
                                <td style='background-color:white;'>".$result->fields['idsegmento']."</td>
                                <td style='background-color:white;'><a style='text-decoration:underline;cursor:pointer;' onclick=\"m3('i_s&&update=$id_s');\">".$result->fields['segmentos']."<a/></td>
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
			
					<td colspan="6">
						<?php //if(!isset($_GET['ndoc']) and !isset($_GET['name'])){?>
							<?php
							
							if($_GET['pag']!=1 && $t_p!=0 )
									echo "<a onclick=\"m3('seg&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"m3('seg&&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"m3('seg&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"m3('seg&&pag=$t_p');\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=segmento'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!--Fin Pie Paginacion-->		
<?php          echo  "</table>
                    </div>
                </fieldset>
                <input type=\"button\" class='btn' onclick=\"m3('i_s');\" value=\"Agregar Nuevo \"/>
                    </center>
                </div>
                ";
            break;
//------------------------------------------------------------------------------------------------------
        case "i_p":
             if(isset($_GET['update']) && $_GET['update']!==""){
                $id_p=$_GET['update'];

                $prove= $db->Execute("SELECT p.*,ubs.nombre departamento,ub.nombre provincia ,ub2.nombre distrito FROM proveedores p
                                    JOIN ubigeos ubs ON ubs.coddpto=p.coddpto  AND  ubs.codprov=00 AND ubs.coddist=00
                                    JOIN ubigeos ub ON ub.coddpto=p.coddpto  AND  ub.codprov=p.codprov AND ub.coddist=00
                                    JOIN ubigeos ub2 ON ub2.coddpto=p.coddpto  AND  ub2.codprov=p.codprov AND ub2.coddist=p.coddist
                                    WHERE idproveedor='$id_p'");
                $name = $prove->fields['proveedor'];
                $tipo = $prove->fields['idpersoneria'];
                $tipo_doc = $prove->fields['iddoi'];
                $n_doc = $prove->fields['documento'];
                $fono = $prove->fields['telefonos'];
                $contacto = $prove->fields['contacto'];
                $obs = $prove->fields['observacion'];
                $est= $prove->fields['idestado'];
                $id_dpto=$prove->fields['coddpto'];
                $dpto=$prove->fields['departamento'];
                $id_prov=$prove->fields['codprov'];
                $prov=$prove->fields['provincia'];
                $id_dis=$prove->fields['coddist'];
                $dis=$prove->fields['distrito'];

                $result = $db->Execute("SELECT * FROM personerias where idestado=1");
                echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:340px;text-align:left;'><legend>Actualizar Proveedor</legend>

                        <table>
                        <tr><td class='zpFormLabel'>Nombre:</td><td><input type'=text' size='30' id='p_nomb' value='$name'></td></tr>
                        <tr><td class='zpFormLabel'>Tipo :</td><td><select id='p_pers'>
                        <option value=''>-Seleccionar-</option>";
                while (!$result->EOF) {
                 if( $tipo == $result->fields['idpersoneria'] ){
                    echo "<option value='".$result->fields['idpersoneria']."' SELECTED>".$result->fields['personerias']."</option>";
                    $result->MoveNext();
					
                 }
					echo "<option value='".$result->fields['idpersoneria']."' >".$result->fields['personerias']."</option>";
                 $result->MoveNext();
                                          }
             echo    "</select></td>";
             //------------------------
             $result = $db->Execute("SELECT * FROM doi where idestado=1");
             echo "<tr><td class='zpFormLabel'>Tipo de Doc. :</td><td><select id='p_doc'>
                 <option value=''>-Seleccionar-</option>";
             while (!$result->EOF) {
                 if( $tipo_doc == $result->fields['iddoi'] ){
                    echo "<option value='".$result->fields['iddoi']."' SELECTED>".$result->fields['doi']."</option>";
                    $result->MoveNext();
                 }
                 echo "<option value='".$result->fields['iddoi']."'>".$result->fields['doi']."</option>";
                 $result->MoveNext();
                                          }
             echo    "</select></td>";
             //----------------------------
            echo "<tr><td class='zpFormLabel'>NºDocumento:</td><td><input type'=text' size='30' id='p_ndoc' value='$n_doc'></td></tr>
                <tr><td class='zpFormLabel'>Telefono:</td><td><input type'=text' size='30' id='p_tel' value='$fono'></td></tr>";
               $r1 = $db->Execute("SELECT * FROM ubigeos  WHERE codprov=00 AND coddist=00");
             echo  "<tr><td class='zpFormLabel'>Departamento:</td><td><select id='dpto' onchange='dpto();'><option value=''>-Todos-</option>";
                     while (!$r1->EOF) {
                         if($id_dpto==$r1->fields['coddpto']){
                         echo "<option value='".$r1->fields['coddpto']."'\" SELECTED>".utf8_encode($r1->fields['nombre'])."</option>";
                         $r1->MoveNext();    
                         }
                         echo "<option value='".$r1->fields['coddpto']."'\">".utf8_encode($r1->fields['nombre'])."</option>";
                         $r1->MoveNext();
                                          }
             echo  "</td></tr></select>";
             echo " <tr><td class='zpFormLabel'>Provincia:</td><td><select id='prov' onchange='dist();'><option value='$id_prov'>$prov</option></select></td></tr>
                    <tr><td class='zpFormLabel'>Distrito:</td><td><select id='dist'><option value='$id_dis'>$dis</option></select></td></tr>
                   <tr><td class='zpFormLabel'>Contacto:</td><td><input type'=text' size='30' id='p_cont' value='$contacto'></td></tr>
                <tr><td class='zpFormLabel'>Observacion:</td><td><input type'=text' size='30' id='p_obs' value='$obs'></td></tr>";
                $result = $db->Execute("SELECT * FROM estados");
            echo "<tr><td class='zpFormLabel'>Estado :</td><td><select id='p_estado'>";
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
            echo"</table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\"  class='btn' onclick=\"insert('prove&&update=1&&id=$id_p');\" value=\"Actualizar \"/>
                <td class=\"botones\"><input type=\"button\" id='atra' class='btn' onclick=\"m3('provee&pag=1');\"  value=\"Regresar \"/></td>
                    </center>
                </div>
                ";
            return false;

                }
       //<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
             $result = $db->Execute("SELECT * FROM personerias where idestado=1");
             
             echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:340px;text-align:left;'><legend>Ingresar Proveedor</legend>
                        
                        <table>
                        <tr><td class='zpFormLabel'>Nombre:</td><td><input type'=text' size='30' id='p_nomb'></td></tr>
                        <tr><td class='zpFormLabel'>Tipo :</td><td><select id='p_pers'>
                        <option value=''>-Seleccionar-</option>";
             while (!$result->EOF) {
             echo "<option value='".$result->fields['idpersoneria']."'>".$result->fields['personerias']."</option>";
             $result->MoveNext();
                                          }
             echo    "</select></td>";
             //------------------------
             $result = $db->Execute("SELECT * FROM doi where idestado=1");
             echo "<tr><td class='zpFormLabel'>Tipo de Doc. :</td><td><select id='p_doc'>
                 <option value=''>-Seleccionar-</option>";
             while (!$result->EOF) {
             echo "<option value='".$result->fields['iddoi']."'>".$result->fields['doi']."</option>";
             $result->MoveNext();
                                          }
             echo    "</select></td>";
             //----------------------------
            echo "<tr><td class='zpFormLabel'>Nº Documento:</td><td><input type'=text' size='30' onblur=\"insert('prove');\" id='p_ndoc'></td></tr>
                <tr><td class='zpFormLabel'>Telefono:</td><td><input type'=text' size='30' id='p_tel'></td></tr>";
               $r1 = $db->Execute("SELECT * FROM ubigeos  WHERE codprov=00 AND coddist=00");
             echo  "<tr><td class='zpFormLabel'>Departamento:</td><td><select id='dpto' onchange='dpto();'><option value=''>-Todos-</option>";
                     while (!$r1->EOF) {
                     echo "<option value='".$r1->fields['coddpto']."' ondclick=\"m7('ubig','1'";
                     echo   $r1->fields['coddpto'];
                     echo "');\">".utf8_encode($r1->fields['nombre'])."</option>";
                     $r1->MoveNext();
                                          }
             echo  "</td></tr></select>";
             echo " <tr><td class='zpFormLabel'>Provincia:</td><td><select id='prov' onchange='dist();'><option value=''>-Todos-</option></select></td></tr>
                    <tr><td class='zpFormLabel'>Distrito:</td><td><select id='dist'><option value=''>-Todos-</option></select></td></tr>
                    <tr><td class='zpFormLabel'>Contacto:</td><td><input type'=text' size='30' id='p_cont'></td></tr>
                    <tr><td class='zpFormLabel'>Observacion:</td><td><input type'=text' size='30' id='p_obs'></td></tr>";
                $result = $db->Execute("SELECT * FROM estados");
             echo "<tr><td class='zpFormLabel'>Estado :</td><td><select id='p_estado'>";
             while (!$result->EOF) {
             echo "<option value='".$result->fields['idestado']."'>".$result->fields['estado']."</option>";
             $result->MoveNext();
                                          }
            echo    "</select></td></tr>";
            echo"    </table>
                     </fieldset>
                     <div id=\"ykBodys\" style=\"color:red;\">
                     </div>
                <input id='a_pr' type=\"button\" class='btn' onclick=\"insert('prove');\" value=\"Agregar \"/>
                <td class=\"botones\"><input type=\"button\" id='atra' class='btn' onclick=\"m3('provee&pag=1');\"  value=\"Regresar \"/></td>
                    </center>
                </div>
                ";
            break;
//-----------------------------------------------------------------------------------------------------
        case "i_c":
          if(isset($_GET['update']) && $_GET['update']!==""){
            $id_c=$_GET['update'];
            $query_c=$db->Execute("SELECT * FROM carteras where idcartera=$id_c ");
                $prv=$query_c->fields['idproveedor'];
                $crt=$query_c->fields['cartera'];
                $id_es=$query_c->fields['idestado'];
				$id_tpc=$query_c->fields['idtipocartera'];
            $result = $db->Execute("SELECT * FROM proveedores where idestado=1");
             echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;text-align:left;'><legend>Actualizar Cartera</legend>
                    <table>";
                       echo "<tr><td class=\"zpFormLabel\">Proveedor :</td><td><select id='c_prove'>
                           <option value=''>-Seleccionar-</option>";
                       while (!$result->EOF) {
                            if($prv==$result->fields['idproveedor']){
                            echo "<option value='".$result->fields['idproveedor']."' SELECTED>".$result->fields['proveedor']."</option>";
                            $result->MoveNext();
                            }
                         echo "<option value='".$result->fields['idproveedor']."'>".$result->fields['proveedor']."</option>";
                         $result->MoveNext();
                                                      }
                       echo    "</select></td></tr>";
					   
					   
                echo "<tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text'  id='cart' value='$crt'></td></tr>";
                $result = $db->Execute("SELECT * FROM estados");
                echo "<tr><td class=\"zpFormLabel\">Estado :</td><td><select id='c_estado'>
                    <option value=''>-Seleccionar-</option>";
                while (!$result->EOF) {
                    if($id_es==$result->fields['idestado']){
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
                <input type=\"button\" class='btn' onclick=\"insert('cart&&update=1&&id=$id_c');\" value=\"Actualizar \"/>
                <td class=\"botones\"><input type=\"button\" id='atra' class='btn' onclick=\"m3('cart&pag=1');\"  value=\"Regresar \"/></td>
                    </center>
                </div>
                ";
                return false;
          }
            $result = $db->Execute("SELECT * FROM proveedores where idestado=1");
             echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;text-align:left;'><legend>Ingresar Cartera</legend>
                    <table>";
                       echo "<tr><td class=\"zpFormLabel\">Proveedor :</td><td><select id='c_prove'>
                           <option value=''>-Seleccionar-</option>";
                       while (!$result->EOF) {
                       echo "<option value='".$result->fields['idproveedor']."'>".$result->fields['proveedor']."</option>";
                         $result->MoveNext();
                                                      }
                       echo    "</select></td></tr>";
			
			
                echo "<tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text'  id='cart'></td></tr>";
                $result = $db->Execute("SELECT * FROM estados ");
                echo "<tr><td class=\"zpFormLabel\">Estado :</td><td><select id='c_estado'>
                    <option value=''>-Seleccionar-</option>";
                while (!$result->EOF) {
                echo "<option value='".$result->fields['idestado']."'>".$result->fields['estado']."</option>";
                $result->MoveNext();
                                          }
                echo    "</select></td></tr>";
              
                echo "</table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\"  class='btn' onclick=\"insert('cart');\" value=\"Agregar \"/>
                <td class=\"botones\"><input type=\"button\" id='atra' class='btn'onclick=\"m3('cart&pag=1');\"  value=\"Regresar \"/></td>
                    </center>
                </div>
                ";
            break;
//----------------------------------------------------------------------------------------------------
        case "i_pr":

            if(isset($_GET['update']) && $_GET['update']!==""){
            $id_pr=$_GET['update'];
            $sql_pr=$db->Execute("SELECT * FROM productos where idproducto=$id_pr ");
                $id_prv=$sql_pr->fields['idproveedor'];
                $pr=$sql_pr->fields['producto'];
                $id_s=$sql_pr->fields['idsegmento'];
                $id_es=$sql_pr->fields['idestado'];
            echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:280px;text-align:left;'><legend>Actualizar Producto</legend>
                    <table>";
                $result = $db->Execute("SELECT * FROM proveedores");
                echo "<tr><td class=\"zpFormLabel\">Proveedor :</td><td><select id='p_prove' >
                    <option value=''>-Seleccionar-</option>";
                while (!$result->EOF) {
                    if($id_prv==$result->fields['idproveedor']){
                    echo "<option value='".$result->fields['idproveedor']."' SELECTED>".$result->fields['proveedor']."</option>";
                    $result->MoveNext();
                    }
                    echo "<option value='".$result->fields['idproveedor']."'>".$result->fields['proveedor']."</option>";
                    $result->MoveNext();
				}
                echo "</select></td></tr>";
                echo "<tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text'  id='pro' value='$pr'></td></tr>";
                $result = $db->Execute("SELECT * FROM segmentos");
                echo "<tr><td class=\"zpFormLabel\">Segmento :</td><td><select id='p_seg'>
                    <option value=''>-Seleccionar-</option>";
                while (!$result->EOF) {
                    if($id_s==$result->fields['idsegmento']){
						echo "<option value='".$result->fields['idsegmento']."'SELECTED>".$result->fields['segmentos']."</option>";
						$result->MoveNext(); 
				    }
                    echo "<option value='".$result->fields['idsegmento']."'>".$result->fields['segmentos']."</option>";
                    $result->MoveNext();
				}
                echo "</select></td></tr>";
                $result = $db->Execute("SELECT * FROM estados");
                echo "<tr><td class=\"zpFormLabel\">Estado :</td><td><select id='p_estado'>
                    <option value=''>-Seleccionar-</option>";
                while (!$result->EOF) {
                    if($id_es==$result->fields['idestado']){
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
                <input type=\"button\" class='btn' onclick=\"insert('produ&&update=1&&id=$id_pr');\" value=\"Actualizar \"/>
                <td class=\"botones\"><input type=\"button\" id='atra' class='btn' onclick=\"m3('pro&pag=1');\"  value=\"Regresar \"/></td>
                    </center>
                </div>
                ";
                return false;
            }
             echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:280px;text-align:left;'><legend>Ingresar Producto</legend>
                    <table>";
                $result = $db->Execute("SELECT * FROM proveedores where idestado=1");
                echo "<tr><td class=\"zpFormLabel\">Proveedor :</td><td><select id='p_prove'>
                    <option value=''>-Seleccionar-</option>";
                while (!$result->EOF) {
					echo "<option value='".$result->fields['idproveedor']."'>".$result->fields['proveedor']."</option>";
					$result->MoveNext();
				}
                echo "</select></td></tr>";
                echo "<tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text'  id='pro'></td></tr>";
                $result = $db->Execute("SELECT * FROM segmentos where idestado=1");
                echo "<tr><td class=\"zpFormLabel\">Segmento :</td><td><select id='p_seg'>
                    <option value=''>-Seleccionar-</option>";
                while (!$result->EOF) {
					echo "<option value='".$result->fields['idsegmento']."'>".$result->fields['segmentos']."</option>";
					$result->MoveNext();
				}
                echo "</select></td></tr>";
                echo " </table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\" class='btn' onclick=\"insert('produ');\" value=\"Agregar \"/>
                <td class=\"botones\"><input type=\"button\" id='atra' class='btn' onclick=\"m3('pro&pag=1');\"  value=\"Regresar \"/></td>
                    </center>
                </div>
                ";
            break;
//---------------------------------------------------------------------------------------------------------------
        case "i_s":
            if(isset($_GET['update']) && $_GET['update']!==""){
            $id_s=$_GET['update'];
            $sql_s=$db->Execute("SELECT * FROM segmentos where idsegmento=$id_s ");
                $seg=$sql_s->fields['segmentos'];
                $id_es=$sql_s->fields['idestado'];
            echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;text-align:left;'><legend>Actualizar Segmento</legend>
                    <table>
                        <tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text' size='24' id='seg' value='$seg'></td></tr>";
            $result = $db->Execute("SELECT * FROM estados");
            echo "<tr><td class=\"zpFormLabel\">Estado :</td><td><select id='sg_estado'>
                    <option value=''>-Seleccionar-</option>";
                while (!$result->EOF) {
                    if($id_es==$result->fields['idestado']){
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
                <input type=\"button\" class='btn'  onclick=\"insert('seg&&update=1&&id=$id_s');\" value=\"Actualizar \"/>
                <td class=\"botones\"><input type=\"button\" id='atra' class='btn' onclick=\"m3('seg&pag=1');\"  value=\"Regresar\"/></td>
                    </center>
                </div>
                ";
            return false;
            }
             echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;'><legend>Ingresar Segmento</legend>
                    <table>
                        <tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text' size='24' id='seg'></td></tr>
                    </table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\" class='btn' onclick=\"insert('seg');\" value=\"Agregar \"/>
                <td class=\"botones\"><input type=\"button\" id='atra' class='btn'  onclick=\"m3('seg&pag=1');\"  value=\"Regresar\"/></td>
                    </center>
                </div>
                ";
            break;
	//-----------------------------------------------------------------------------------------------------
        case "i_tc":
          if(isset($_GET['update']) && $_GET['update']!==""){
            $id_c=$_GET['update'];
            $query_c=$db->Execute("SELECT * FROM tipo_carteras where idtipocartera='$id_c' ");
                
                $crt=$query_c->fields['tipocartera'];
                $id_es=$query_c->fields['idestado'];
				$id_tpc=$query_c->fields['idtipocartera'];
           
             echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;text-align:left;'><legend>Actualizar Tipo Cartera</legend>
                    <table>";
                        
					   
                echo "<tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text'  id='tipo_cart' value='$crt'></td></tr>";
                $result = $db->Execute("SELECT * FROM estados");
                echo "<tr><td class=\"zpFormLabel\">Estado :</td><td><select id='tipc_estado'>
                    <option value=''>-Seleccionar-</option>";
                while (!$result->EOF) {
                    if($id_es==$result->fields['idestado']){
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
                <input type=\"button\" class='btn' onclick=\"insert('tip_cart&&update=1&&id=$id_c');\" value=\"Actualizar \"/>
                <td class=\"botones\"><input type=\"button\" id='atra' class='btn' onclick=\"m3('tip_cart&pag=1');\"  value=\"Regresar \"/></td>
                    </center>
                </div>
                ";
                return false;
          }
            $result = $db->Execute("SELECT * FROM proveedores where idestado=1");
             echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;text-align:left;'><legend>Ingresar Tipo Cartera</legend>
                    <table>";
                     			
                echo "<tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text'  id='tipo_cart'></td></tr>";
                $result = $db->Execute("SELECT * FROM estados ");
               
              
                echo "</table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\"  class='btn' onclick=\"insert('tip_cart');\" value=\"Agregar \"/>
                <td class=\"botones\"><input type=\"button\" id='atra' class='btn'onclick=\"m3('tip_cart&pag=1');\"  value=\"Regresar \"/></td>
                    </center>
                </div>
                ";
            break;
    }
}
?>
