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
	$pag=0;// x defecto
    $r_pag=20;// total registros x pag
	
	if($_GET['pag']!=1){
		$n=$_GET['pag']-1;
		$n=$n*$r_pag;
	}
    switch($id_param){
case "actv_c":
			$sql="SELECT  c.cartera, a.actividad, p.proveedor,ac.idactividadcartera,e.estado,e.idestado
                                    FROM actividad_carteras ac
                                    JOIN carteras c ON ac.idcartera=c.idcartera
                                    JOIN actividades a ON ac.idactividad=a.idactividad
                                    JOIN proveedores p ON c.idproveedor=p.idproveedor
                                    JOIN estados e ON e.idestado=ac.idestado
									ORDER BY p.proveedor ASC,c.cartera,a.actividad";
			
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
						echo "<input type=\"button\" class='btn' onclick=\"m5('i_ac');\" value=\"Agregar Nuevo \"/>";
						return false;
						}
			 echo "<div id=\"ykBody\">
                <center>
                <fieldset><legend>Actividad de Carteras </legend>
                    <div id=\"design1\">
                        <table style='text-align:left;'><th>Nº</th><th>Id</th><th>Proveedor</th><th>Cartera</th><th>Actividad</th><th>Estado</th> ";
                        while (!$result->EOF) {
                            $id=$result->fields['idactividadcartera'];
                             echo "<tr><td>".++$n."</td>
                             <td>".$result->fields['idactividadcartera']."</td>
                            <td style='background-color:white;'>".$result->fields['proveedor']."</td>
                            <td style='background-color:white;'>".$result->fields['cartera']."</td>
                            <td style='background-color:white;'><a style='text-decoration:underline;cursor:pointer;' onclick=\"m5('i_ac&&update=$id');\" >".utf8_encode($result->fields['actividad'])."</a></td>
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
									echo "<a onclick=\"m5('actv_c&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"m5('actv_c&&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"m5('actv_c&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"m5('actv_c&&pag=$t_p');\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=conta_cart'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!--Fin Pie Paginacion-->	

<?php                        echo "</table></div>
                 </fieldset>
                </br><input type=\"button\" class='btn' onclick=\"m5('i_ac');\" value=\"Agregar Nuevo \"/>
                    </center>
                </div>
                ";
            break;
//------------------------------------------------------------------------------------------------	
case "just_c":
			$sql="SELECT  c.cartera,r.resultado, j.justificacion, p.proveedor,jc.idjustificacioncartera,e.estado,e.idestado
                                    FROM justificacion_carteras jc
                                    JOIN carteras c ON jc.idcartera=c.idcartera
                                    JOIN justificaciones j ON jc.idjustificacion=j.idjustificacion
                                    JOIN resultados r ON j.idresultado=r.idresultado                                    
                                    JOIN proveedores p ON c.idproveedor=p.idproveedor
                                    JOIN estados e ON e.idestado=jc.idestado
									ORDER BY p.proveedor ASC,c.cartera,j.justificacion		";
			
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
						echo "<input type=\"button\" class='btn' onclick=\"m5('i_jc');\" value=\"Agregar Nuevo \"/>";
						return false;
						}
			 echo "<div id=\"ykBody\">
                <center>
                <fieldset><legend>Justificacion de Carteras </legend>
                    <div id=\"design1\">
                        <table style='text-align:left;'><th>Nº</th><th>Id</th><th>Proveedor</th><th>Cartera</th><th>Resultado</th><th>Justificacion</th><th>Estado</th> ";
                        while (!$result->EOF) {
                            $id=$result->fields['idjustificacioncartera'];
                             echo "<tr><td>".++$n."</td>
                             <td>".$result->fields['idjustificacioncartera']."</td>
                            <td style='background-color:white;'>".$result->fields['proveedor']."</td>
                            <td style='background-color:white;'>".$result->fields['cartera']."</td>
							<td style='background-color:white;'>".$result->fields['resultado']."</td>
                            <td style='background-color:white;'><a style='text-decoration:underline;cursor:pointer;' onclick=\"m5('i_jc&&update=$id');\" >".utf8_encode($result->fields['justificacion'])."</a></td>
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
									echo "<a onclick=\"m5('just_c&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"m5('just_c&&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"m5('just_c&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"m5('just_c&&pag=$t_p');\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=just_cart'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!--Fin Pie Paginacion-->	

<?php                        echo "</table></div>
                 </fieldset>
                </br><input type=\"button\" class='btn' onclick=\"m5('i_jc');\" value=\"Agregar Nuevo \"/>
                    </center>
                </div>
                ";
            break;
//------------------------------------------------------------------------------------------------
		 case "conta_c":
			$sql="SELECT  c.cartera, co.contactabilidad, p.proveedor,cc.idcontactabilidadcartera,e.estado,e.idestado
                                    FROM contactabilidad_carteras cc
                                    JOIN carteras c ON cc.idcartera=c.idcartera
                                    JOIN contactabilidad co ON cc.idcontactabilidad=co.idcontactabilidad
                                    JOIN proveedores p ON c.idproveedor=p.idproveedor
                                    JOIN estados e ON e.idestado=cc.idestado
									ORDER BY p.proveedor ASC,c.cartera,co.contactabilidad";
			
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
						echo "<input type=\"button\" class='btn' onclick=\"m5('i_cc');\" value=\"Agregar Nuevo \"/>";
						return false;
						}
			 echo "<div id=\"ykBody\">
                <center>
                <fieldset><legend>Contactabilidad de Carteras </legend>
                    <div id=\"design1\">
                        <table style='text-align:left;'><th>Nº</th><th>Id</th><th>Proveedor</th><th>Cartera</th><th>Contactabilidad</th><th>Estado</th> ";
                        while (!$result->EOF) {
                            $id=$result->fields['idcontactabilidadcartera'];
                             echo "<tr><td>".++$n."</td>
                             <td>".$result->fields['idcontactabilidadcartera']."</td>
                            <td style='background-color:white;'>".$result->fields['proveedor']."</td>
                            <td style='background-color:white;'>".$result->fields['cartera']."</td>
                            <td style='background-color:white;'><a style='text-decoration:underline;cursor:pointer;' onclick=\"m5('i_cc&&update=$id');\" >".$result->fields['contactabilidad']."</a></td>
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
									echo "<a onclick=\"m5('conta_c&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"m5('conta_c&&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"m5('conta_c&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"m5('conta_c&&pag=$t_p');\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=conta_cart'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!--Fin Pie Paginacion-->	

<?php                        echo "</table></div>
                 </fieldset>
                </br><input type=\"button\" class='btn' onclick=\"m5('i_cc');\" value=\"Agregar Nuevo \"/>
                    </center>
                </div>
                ";
            break;
//------------------------------------------------------------------------------------------------
        case "grupo":
			$sql="SELECT g.*,e.estado FROM grupo_gestiones g
				  JOIN estados e ON g.idestado=e.idestado
				  ORDER BY g.grupogestion";
            
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
						echo "<input type=\"button\" class='btn' onclick=\"m5('i_g');\" value=\"Agregar Nuevo \"/>";
						return false;
			}     						
             echo "<div id=\"ykBody\">
                <center>
                <fieldset><legend>Grupos </legend>
                    <div id=\"design1\">
                        <table style='text-align:left;'><th>Nº</th><th>Id</th><th>Descripcion</th><th>Estado</th>";
                         while (!$result->EOF) {
							$id=$result->fields['idgrupogestion'];
							echo "<tr><td>".++$n."</td>
                            <td>".$result->fields['idgrupogestion']."</td>
                             <td style='background-color:white;'><a style='text-decoration:underline;cursor:pointer;' onclick=\"m5('i_g&&update=$id');\">".$result->fields['grupogestion']."</a></td>
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
									echo "<a onclick=\"m5('grupo&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"m5('grupo&&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"m5('grupo&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"m5('grupo&&pag=$t_p');\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=grupos'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!--Fin Pie Paginacion-->	
<?php 				echo "</table></div>
                 </fieldset>
                </br><input type=\"button\" class='btn' onclick=\"m5('i_g');\" value=\"Agregar Nuevo \"/>
                    </center>
                </div>
                ";
            break;
//-----------------------------------------------------------------------------------------------------------
        case "result":
				$sql="SELECT gr.grupogestion,r.resultado,r.idresultado,e.idestado FROM resultados r
					JOIN grupo_gestiones gr ON r.idgrupogestion=gr.idgrupogestion
					JOIN estados e ON e.idestado=r.idestado
					ORDER BY gr.grupogestion ASC,r.resultado";
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
				echo "<input type=\"button\" class='btn' onclick=\"m5('i_r');\" value=\"Agregar Nuevo \"/>";
				return false;
			} 
			echo "<div id=\"ykBody\">
                <center>
                <fieldset><legend>Resultados </legend>
                    <div id=\"design1\">
                        <table style='text-align:left;'><th>Nº</th><th>Id</th><th>Grupo Gestion</th><th>Resultados</th><th>Estado</th>";
                         while (!$result->EOF) {
							$id=$result->fields['idresultado'];
							echo "<tr><td>".++$n."</td>
								<td>".$result->fields['idresultado']."</td>
								<td style='background-color:white;'>".$result->fields['grupogestion']."</td>
								<td style='background-color:white;'><a style='text-decoration:underline;cursor:pointer;' onclick=\"m5('i_r&update=$id');\">".utf8_encode($result->fields['resultado'])."</a></td>
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
									echo "<a onclick=\"m5('result&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"m5('result&&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"m5('result&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"m5('result&&pag=$t_p');\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=resultado'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!--Fin Pie Paginacion-->	

<?php                       echo "</table></div>
                 </fieldset>
                </br><input type=\"button\" class='btn' onclick=\"m5('i_r');\" value=\"Agregar Nuevo \"/>
                    </center>
                </div>
                ";
            break;
//------------------------------------------------------------------------------------------------
        case "just":
			$sql="SELECT gr.grupogestion,r.resultado,j.justificacion,j.idjustificacion,e.estado,e.idestado,j.peso FROM justificaciones j
                  JOIN resultados r ON j.idresultado=r.idresultado
                  JOIN grupo_gestiones gr ON r.idgrupogestion=gr.idgrupogestion
                  JOIN estados e ON e.idestado = j.idestado
				  ORDER BY gr.grupogestion  ASC,r.resultado,j.justificacion";
			
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
						echo "<input type=\"button\" class='btn' onclick=\"m5('i_j');\" value=\"Agregar Nuevo \"/>";
						return false;
						}
			 echo "<div id=\"ykBody\">
                <center>
                <fieldset><legend>Justificaciones </legend>
                    <div id=\"design1\">
                        <table style='text-align:left;'><th>Nº</th><th>Id</th><th>Grupo Gestion</th><th>Resultado</th><th>Justificacion</th><th>Peso</th><th>Estado</th> ";
                         while (!$result->EOF) {
                            $id=$result->fields['idjustificacion'];
                             echo "<tr><td>".++$n."</td>
                            <td>".$result->fields['idjustificacion']."</td>
                            <td style='background-color:white;'>".$result->fields['grupogestion']."</td>
                            <td style='background-color:white;'>".utf8_encode($result->fields['resultado'])."</td>
                            <td style='background-color:white;'><a style='text-decoration:underline;cursor:pointer;' onclick=\"m5('i_j&&update=$id');\" >".$result->fields['justificacion']."</a></td>
                             <td style='background-color:white;'>".$result->fields['peso']."</td>
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
			
					<td colspan="7">
						<?php //if(!isset($_GET['ndoc']) and !isset($_GET['name'])){?>
							<?php
							
							if($_GET['pag']!=1 && $t_p!=0 )
									echo "<a onclick=\"m5('just&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"m5('just&&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"m5('just&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"m5('just&&pag=$t_p');\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=justificacion'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!--Fin Pie Paginacion-->					

<?php                        echo "</table></div>
                 </fieldset>
                </br><input type=\"button\" class='btn' onclick=\"m5('i_j');\" value=\"Agregar Nuevo \"/>
                    </center>
                </div>
                ";
            break;
//--------------------------------------------------------------------------------------------------------------
        case "result_c":
			$sql="SELECT  c.cartera, v.resultado, p.proveedor,rc.idresultadocartera,e.estado,e.idestado
                                    FROM resultado_carteras rc
                                    JOIN carteras c ON rc.idcartera=c.idcartera
                                    JOIN resultados v ON rc.idresultado=v.idresultado
                                    JOIN proveedores p ON c.idproveedor=p.idproveedor
                                    JOIN estados e ON e.idestado=rc.idestado
									ORDER BY p.proveedor ASC,c.cartera,v.resultado";
			
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
						echo "<input type=\"button\" class='btn' onclick=\"m5('i_rc');\" value=\"Agregar Nuevo \"/>";
						return false;
						}
			 echo "<div id=\"ykBody\">
                <center>
                <fieldset><legend>Resultado de Carteras </legend>
                    <div id=\"design1\">
                        <table style='text-align:left;'><th>Nº</th><th>Id</th><th>Proveedor</th><th>Cartera</th><th>Resultado</th><th>Estado</th> ";
                        while (!$result->EOF) {
                            $id=$result->fields['idresultadocartera'];
                             echo "<tr><td>".++$n."</td>
                             <td>".$result->fields['idresultadocartera']."</td>
                            <td style='background-color:white;'>".$result->fields['proveedor']."</td>
                            <td style='background-color:white;'>".$result->fields['cartera']."</td>
                            <td style='background-color:white;'><a style='text-decoration:underline;cursor:pointer;' onclick=\"m5('i_rc&&update=$id');\" >".utf8_encode($result->fields['resultado'])."</a></td>
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
									echo "<a onclick=\"m5('result_c&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"m5('result_c&&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"m5('result_c&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"m5('result_c&&pag=$t_p');\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=r_cartera'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!--Fin Pie Paginacion-->	

<?php                        echo "</table></div>
                 </fieldset>
                </br><input type=\"button\" class='btn' onclick=\"m5('i_rc');\" value=\"Agregar Nuevo \"/>
                    </center>
                </div>
                ";
            break;
//------------------------------------------------------------------------------------------------
        case "i_g":
            if(isset($_GET['update']) && $_GET['update']!==""){
                $id_gg=$_GET['update'];
                $sql= $db->Execute("SELECT * FROM grupo_gestiones WHERE idgrupogestion=$id_gg");
				
                $gg=$sql->fields['grupogestion'];
                $est=$sql->fields['idestado'];
			
                echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;text-align:left;'><legend>Actualizar Grupo</legend>
                    <table>
                        <tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text' size='24' id='gru_g' value='$gg'></td></tr>";
                        $result = $db->Execute("SELECT * FROM estados");
                        echo "<tr><td class='zpFormLabel'>Estado :</td><td><select id='g_estado'>";
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
                <input type=\"button\" class='btn' onclick=\"insert('gest_g&&update=1&&id=$id_gg');\" value=\"Actualizar \"/>
                <td class=\"botones\"><input id='atra' type=\"button\"  class='btn' onclick=\"m5('grupo&pag=1');\"  value=\"Regresar \"/></td>
                    </center>
                </div>
                ";
                return false;
            }
            echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;text-align:left;'><legend>Tipos de Grupo</legend>
                    <table>
                        <tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text' size='24' id='gru_g'></td></tr>
                    </table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\" class='btn' onclick=\"insert('gest_g');\" value=\"Agregar \"/>
                <td class=\"botones\"><input id='atra' type=\"button\" class='btn' onclick=\"m5('grupo&pag=1');\"  value=\"Regresar \"/></td>
                    </center>
                </div>
                ";
            break;
//------------------------------------------------------------------------------------------------
        case "i_r":
            if(isset($_GET['update']) && $_GET['update']!==""){
                $id_r=$_GET['update'];
                $sql= $db->Execute("SELECT * FROM resultados WHERE idresultado=$id_r");
                $id_g=$sql->fields['idgrupogestion'];
                $rs=$sql->fields['resultado'];
                $est=$sql->fields['idestado'];
				$checked=$sql->fields['idcompromisos'];
				$flag=$sql->fields['flag'];
                echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;text-align:left;'><legend>Actualizar Resultado</legend>
                    <table>";
                    $result = $db->Execute("SELECT * FROM grupo_gestiones");
                         echo "<tr><td class=\"zpFormLabel\">Grupo :</td><td><select id='grup_r'>";
                         echo "<option value=''>-Seleccionar-</option>";
                         while (!$result->EOF) {
                            if($id_g==$result->fields['idgrupogestion']){
                             echo "<option value='".$result->fields['idgrupogestion']."' SELECTED>".$result->fields['grupogestion']."</option>";
                             $result->MoveNext();   
                                
                            }
                             echo "<option value='".$result->fields['idgrupogestion']."'>".$result->fields['grupogestion']."</option>";
                             $result->MoveNext();                                      }
                         echo "</select></td>";
                         echo "</tr>";
					
                    echo " <tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text' size='24' id='result' value='".utf8_encode($rs)."'></td></tr>
					
					<tr><td class=\"zpFormLabel\">Genera Pago :</td><td>
							Si<input id='si_r' type='radio' value='1' onclick='if(this.checked){ document.getElementById(\"no_r\").checked=false}'";
							if($checked=="1"){
								echo "checked ";
							}
					echo"/ >";
					echo "No<input value='0' id='no_r' type='radio'  onclick='if(this.checked){ document.getElementById(\"si_r\").checked=false}'"; 
							if($checked=="0"){
								echo "checked ";
							}
					echo "/ ></td></tr>";
					
					echo "<tr><td class=\"zpFormLabel\">Campo? :</td><td>
							Si<input id='si_rc' type='radio' value='1' onclick='if(this.checked){ document.getElementById(\"no_rc\").checked=false}'";
							if($flag=="1"){
								echo "checked ";
							}
					echo"/ >";
					echo "No<input value='0' id='no_rc' type='radio'  onclick='if(this.checked){ document.getElementById(\"si_rc\").checked=false}'"; 
							if($flag=="0"){
								echo "checked ";
							}
					echo "/ ></td></tr>";
					
                        $result = $db->Execute("SELECT * FROM estados");
                        echo "<tr><td class='zpFormLabel'>Estado :</td><td><select id='r_estado'>";
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
                <input type=\"button\" class='btn' onclick=\"insert('gest_r&&update=1&&id=$id_r');\" value=\"Actualizar \"/>
                <td class=\"botones\"><input id='atra' type=\"button\" class='btn' onclick=\"m5('result&pag=1');\"  value=\"Regresar \"/></td>
                    </center>
                </div>
                ";
                    return false;
            }
            echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;text-align:left;'><legend>Resultados</legend>
                    <table>";
                    $result = $db->Execute("SELECT * FROM grupo_gestiones where idestado=1 ORDER BY grupogestion ");
                         echo "<tr><td class=\"zpFormLabel\">Grupo :</td><td><select id='grup_r'>";
                         echo "<option value=''>-Seleccionar-</option>";
                         while (!$result->EOF) {
                         echo "<option value='".$result->fields['idgrupogestion']."'>".$result->fields['grupogestion']."</option>";
                         $result->MoveNext();                                      }
                         echo "</select></td>";
                         echo "</tr>";
                    echo " <tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type='text' size='24' id='result'></td></tr>
			      <tr><td class=\"zpFormLabel\">Genera Pago:</td><td>Si<input id='si_r' type='radio' value='1' onclick='if(this.checked){ document.getElementById(\"no_r\").checked=false}'/ >  No<input value='0' id='no_r' type='radio' checked onclick='if(this.checked){ document.getElementById(\"si_r\").checked=false}'/ ></td></tr>
			      <tr><td class=\"zpFormLabel\">Campo?:</td><td>Si<input id='si_rc' type='radio' value='1' onclick='if(this.checked){ document.getElementById(\"no_rc\").checked=false}'/ >  No<input value='0' id='no_rc' type='radio' checked onclick='if(this.checked){ document.getElementById(\"si_rc\").checked=false}'/ ></td></tr>				
			      </table>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\" class='btn' onclick=\"insert('gest_r');\" value=\"Agregar \"/>
                <td class=\"botones\"><input id='atra' type=\"button\" class='btn' onclick=\"m5('result&pag=1');\"  value=\"Regresar \"/></td>
                    </center>
                </div>
                ";
            break;
//------------------------------------------------------------------------------------------------
        case "i_j":
            if(isset($_GET['update']) && $_GET['update']!==""){
                $id_j=$_GET['update'];
                $sql= $db->Execute("SELECT * FROM justificaciones WHERE idjustificacion=$id_j");
                $id_r=$sql->fields['idresultado'];
                $jst=$sql->fields['justificacion'];
                $est=$sql->fields['idestado'];
				$ps_just=$sql->fields['peso'];
                echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:500px;text-align:left;'><legend>Actualizar Justificacion</legend>
                    <table>";
                    $result = $db->Execute("SELECT * FROM resultados ORDER BY flag,resultado");
                         echo "<tr><td class=\"zpFormLabel\">Resultado :</td><td><select id='id_r'>
                              <option value=''>-Seleccionar-</option>";
                         while (!$result->EOF) {
                            if($id_r==$result->fields['idresultado']){
                             echo "<option value='".$result->fields['idresultado']."' SELECTED>".utf8_encode($result->fields['resultado'])."</option>";
                             $result->MoveNext();
                                
                            }
                             echo "<option value='".$result->fields['idresultado']."'>".utf8_encode($result->fields['resultado'])."</option>";
                             $result->MoveNext();                                      }
                         echo "</select></td>";
                         echo "</tr>";
                    
                    echo "<tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text' size='24' id='just' value='$jst'></td></tr>
					      <tr><td class=\"zpFormLabel\">Peso:</td><td><input type'=text' size='24' id='ps_just' value='$ps_just' onkeyup='if(isNaN(this.value)){ this.value=\"\";}'></td></tr>";
                        $result = $db->Execute("SELECT * FROM estados");
                        echo "<tr><td class='zpFormLabel'>Estado :</td><td><select id='j_estado'>";
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
                    <input type=\"button\" class='btn' onclick=\"insert('just&&update=1&&id=$id_j');\" value=\"Actualizar \"/>
                    <td class=\"botones\"><input id='atra' type=\"button\" class='btn' onclick=\"m5('just&pag=1');\"  value=\"Regresar \"/></td>
                        </center>
                    </div>";
                    return false;
            }
            echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:500px;text-align:left;'><legend>Ingresar Justificacion</legend>
                    <table>";
                    $result = $db->Execute("SELECT * FROM resultados where idestado=1 ORDER BY flag,resultado ");
                         echo "<tr><td class=\"zpFormLabel\">Resultado :</td><td><select id='id_r'>
                              <option value=''>-Seleccionar-</option>";
                         while (!$result->EOF) {
                         echo "<option value='".$result->fields['idresultado']."'>".utf8_encode($result->fields['resultado'])."</option>";
                         $result->MoveNext();  }
                         echo "</select></td>";
                         echo "</tr>";
                    echo "<tr><td class=\"zpFormLabel\">Descripcion:</td><td><input type'=text' size='24' id='just'></td></tr>
						  <tr><td class=\"zpFormLabel\">Peso:</td><td><input type'=text' size='24' id='ps_just' onkeyup='if(isNaN(this.value)){ this.value=\"\";}'></td></tr>
                    </table>
                    </fieldset>
                    <div id=\"ykBodys\" style=\"color:red;\">
                    </div>
                    <input type=\"button\" class='btn' onclick=\"insert('just');\" value=\"Agregar \"/>
                    <td class=\"botones\"><input id='atra' type=\"button\" class='btn' onclick=\"m5('just&pag=1');\"  value=\"Regresar \"/></td>
                        </center>
                    </div>";
            break;
//------------------------------------------------------------------------------------------------
         case "i_rc":
            if(isset($_GET['update']) && $_GET['update']!==""){
                $id_rc=$_GET['update'];
                $sql= $db->Execute("SELECT p.idproveedor,p.proveedor,c.idcartera,c.cartera,r.idresultado,r.resultado,e.idestado,e.estado
                                    FROM resultado_carteras rc
                                    JOIN carteras c ON c.idcartera=rc.idcartera
                                    JOIN proveedores p ON p.idproveedor=c.idproveedor
                                    JOIN resultados r ON r.idresultado=rc.idresultado
                                    JOIN estados e ON e.idestado=rc.idestado
                                    WHERE rc.idresultadocartera=$id_rc");
                $id_p=$sql->fields['idproveedor'];
                $id_r=$sql->fields['idresultado'];
                $id_c=$sql->fields['idcartera'];
                $crt=$sql->fields['cartera'];
                $est=$sql->fields['idestado'];
                
                echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;text-align:left;'><legend>Actualizar Resultado Cartera</legend>
                    <table>";
                    $result = $db->Execute("SELECT * FROM proveedores ");
                         echo "<tr ><td class=\"zpFormLabel\">Proveedor:</td><td><select id='prov' onchange='cart();'><option value=''>-Seleccionar-</option>";
                            while (!$result->EOF) {
                                if($id_p==$result->fields['idproveedor']){
                                    echo "<option value='".$result->fields['idproveedor']."' SELECTED>".$result->fields['proveedor']."</option>";
                                    $result->MoveNext();
                                }
                                echo "<option value='".$result->fields['idproveedor']."'>".$result->fields['proveedor']."</option>";
                                $result->MoveNext();                                    }
                         echo "</select></td></tr>";
                         echo "<tr ><td class=\"zpFormLabel\">Cartera :</td><td><select id='id_c'>
                             <option value='$id_c'>$crt</option>";
                         echo "</select></td>";
                         echo "</tr>";
                    $result = $db->Execute("SELECT * FROM resultados where flag!=1");
                         echo "<tr ><td class=\"zpFormLabel\">Resultado :</td><td><select id='id_rs'>
                             <option value=''>-Seleccionar-</option>";
                         while (!$result->EOF) {
                             if($id_r==$result->fields['idresultado']){
                             echo "<option value='".$result->fields['idresultado']."' SELECTED>".$result->fields['resultado']."</option>";
                             $result->MoveNext();    
                             }
                             echo "<option value='".$result->fields['idresultado']."'>".$result->fields['resultado']."</option>";
                             $result->MoveNext();                                      }
                         echo "</select></td>";
                         echo "</tr>";
                  
                    $result = $db->Execute("SELECT * FROM estados");
                        echo "<tr ><td class='zpFormLabel'>Estado :</td><td><select id='rc_estado'>";
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
                    <input type=\"button\" class='btn' onclick=\"insert('r_xc&&update=1&&id=$id_rc');\" value=\"Actualizar \"/>
                    <input type=\"button\" id='atra' class='btn' onclick=\"m5('result_c&pag=1');\"  value=\"Regresar \"/>
                        </center>
                    </div>";
                    return false;
            }
            echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;text-align:left;'><legend>Ingresar Resultados Carteras</legend>
                    <table>";
                    $result = $db->Execute("SELECT * FROM proveedores where idestado=1 ORDER BY proveedor ");
                         echo "<tr><td class=\"zpFormLabel\">Proveedor:</td><td><select id='prov' onchange='cart();'><option value=''>-Seleccionar-</option>";
                            while (!$result->EOF) {
                         echo "<option value='".$result->fields['idproveedor']."'>".$result->fields['proveedor']."</option>";
                         $result->MoveNext();                                    }
                         echo "</select></td></tr>";
                         echo "<tr><td class=\"zpFormLabel\">Cartera :</td><td><select id='id_c'>
                             <option value=''>-Todos-</option>";
                         echo "</select></td>";
                         echo "</tr>";
                    $result = $db->Execute("SELECT * FROM resultados where idestado=1 and flag!=1 ORDER BY resultado ");
                         echo "<tr><td class=\"zpFormLabel\">Resultado :</td><td><select id='id_rs'>
                             <option value=''>-Seleccionar-</option>";
                         while (!$result->EOF) {
                         echo "<option value='".$result->fields['idresultado']."'>".$result->fields['resultado']."</option>";
                         $result->MoveNext();                                      }
                         echo "</select></td>";
                         echo "</tr>";
                    
                    echo "
                    </table>
                    </fieldset>
                    <div id=\"ykBodys\" style=\"color:red;\">
                    </div>
                    <input type=\"button\" class='btn' onclick=\"insert('r_xc');\" value=\"Agregar \"/>
                    <td class=\"botones\"><input id='atra' type=\"button\" class='btn' onclick=\"m5('result_c&pag=1');\"  value=\"Regresar \"/></td>
                        </center>
                    </div>";
            break;
//------------------------------------------------------------------------------------------------
         case "i_cc":
            if(isset($_GET['update']) && $_GET['update']!==""){
                $id_rc=$_GET['update'];
                $sql= $db->Execute("SELECT p.idproveedor,p.proveedor,c.idcartera,c.cartera,co.idcontactabilidad,co.contactabilidad,e.idestado,e.estado
                                    FROM contactabilidad_carteras cc
                                    JOIN carteras c ON c.idcartera=cc.idcartera
                                    JOIN proveedores p ON p.idproveedor=c.idproveedor
                                    JOIN contactabilidad co ON co.idcontactabilidad=cc.idcontactabilidad
                                    JOIN estados e ON e.idestado=cc.idestado
                                    WHERE cc.idcontactabilidadcartera=$id_rc");
                $id_p=$sql->fields['idproveedor'];
                $id_r=$sql->fields['idcontactabilidad'];
                $id_c=$sql->fields['idcartera'];
                $crt=$sql->fields['cartera'];
                $est=$sql->fields['idestado'];
                
                echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;text-align:left;'><legend>Actualizar Contactabilidad de Cartera</legend>
                    <table>";
                    $result = $db->Execute("SELECT * FROM proveedores ");
                         echo "<tr ><td class=\"zpFormLabel\">Proveedor:</td><td><select id='prov' onchange='cart();'><option value=''>-Seleccionar-</option>";
                            while (!$result->EOF) {
                                if($id_p==$result->fields['idproveedor']){
                                    echo "<option value='".$result->fields['idproveedor']."' SELECTED>".$result->fields['proveedor']."</option>";
                                    $result->MoveNext();
                                }
                                echo "<option value='".$result->fields['idproveedor']."'>".$result->fields['proveedor']."</option>";
                                $result->MoveNext();                                    }
                         echo "</select></td></tr>";
                         echo "<tr ><td class=\"zpFormLabel\">Cartera :</td><td><select id='id_c'>
                             <option value='$id_c'>$crt</option>";
                         echo "</select></td>";
                         echo "</tr>";
                    $result = $db->Execute("SELECT idcontactabilidad,contactabilidad FROM contactabilidad where idestado=1");
                         echo "<tr ><td class=\"zpFormLabel\">Contactabilidad :</td><td><select id='id_co'>
                             <option value=''>-Seleccionar-</option>";
                         while (!$result->EOF) {
                             if($id_r==$result->fields['idcontactabilidad']){
                             echo "<option value='".$result->fields['idcontactabilidad']."' SELECTED>".$result->fields['contactabilidad']."</option>";
                             $result->MoveNext();    
                             }
                             echo "<option value='".$result->fields['idcontactabilidad']."'>".$result->fields['contactabilidad']."</option>";
                             $result->MoveNext();                                      }
                         echo "</select></td>";
                         echo "</tr>";
                  
                    $result = $db->Execute("SELECT * FROM estados");
                        echo "<tr ><td class='zpFormLabel'>Estado :</td><td><select id='cc_estado'>";
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
                    <input type=\"button\" class='btn' onclick=\"insert('c_xc&&update=1&&id=$id_rc');\" value=\"Actualizar \"/>
                    <input type=\"button\" id='atra' class='btn' onclick=\"m5('conta_c&pag=1');\"  value=\"Regresar \"/>
                        </center>
                    </div>";
                    return false;
            }
            echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;text-align:left;'><legend>Ingresar Contactabilidad de Carteras</legend>
                    <table>";
                    $result = $db->Execute("SELECT * FROM proveedores where idestado=1 ORDER BY proveedor ");
                         echo "<tr><td class=\"zpFormLabel\">Proveedor:</td><td><select id='prov' onchange='cart();'><option value=''>-Seleccionar-</option>";
                            while (!$result->EOF) {
                         echo "<option value='".$result->fields['idproveedor']."'>".$result->fields['proveedor']."</option>";
                         $result->MoveNext();                                    }
                         echo "</select></td></tr>";
                         echo "<tr><td class=\"zpFormLabel\">Cartera :</td><td><select id='id_c'>
                             <option value=''>-Todos-</option>";
                         echo "</select></td>";
                         echo "</tr>";
                    $result = $db->Execute("SELECT idcontactabilidad,contactabilidad FROM contactabilidad where idestado=1 ORDER BY contactabilidad ");
                         echo "<tr><td class=\"zpFormLabel\">Contactabilidad :</td><td><select id='id_co'>
                             <option value=''>-Seleccionar-</option>";
                         while (!$result->EOF) {
                         echo "<option value='".$result->fields['idcontactabilidad']."'>".$result->fields['contactabilidad']."</option>";
                         $result->MoveNext();                                      }
                         echo "</select></td>";
                         echo "</tr>";
                    
                    echo "
                    </table>
                    </fieldset>
                    <div id=\"ykBodys\" style=\"color:red;\">
                    </div>
                    <input type=\"button\" class='btn' onclick=\"insert('c_xc');\" value=\"Agregar \"/>
                    <td class=\"botones\"><input id='atra' type=\"button\" class='btn' onclick=\"m5('conta_c&pag=1');\"  value=\"Regresar \"/></td>
                        </center>
                    </div>";
            break;
//------------------------------------------------------------------------------------------------
         case "i_ac":
            if(isset($_GET['update']) && $_GET['update']!==""){
                $id_rc=$_GET['update'];
                $sql= $db->Execute("SELECT  c.cartera,c.idcartera, a.actividad,a.idactividad, p.proveedor,p.idproveedor,e.estado,e.idestado
                                    FROM actividad_carteras ac
                                    JOIN carteras c ON ac.idcartera=c.idcartera
                                    JOIN actividades a ON ac.idactividad=a.idactividad
                                    JOIN proveedores p ON c.idproveedor=p.idproveedor
                                    JOIN estados e ON e.idestado=ac.idestado
                                    WHERE ac.idactividadcartera=$id_rc");
                $id_p=$sql->fields['idproveedor'];
                $id_a=$sql->fields['idactividad'];
                $id_c=$sql->fields['idcartera'];
                $crt=$sql->fields['cartera'];
                $est=$sql->fields['idestado'];
                
                echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;text-align:left;'><legend>Actualizar Actividad de Cartera</legend>
                    <table>";
                    $result = $db->Execute("SELECT * FROM proveedores ");
                         echo "<tr ><td class=\"zpFormLabel\">Proveedor:</td><td><select id='prov' onchange='cart();'><option value=''>-Seleccionar-</option>";
                            while (!$result->EOF) {
                                if($id_p==$result->fields['idproveedor']){
                                    echo "<option value='".$result->fields['idproveedor']."' SELECTED>".$result->fields['proveedor']."</option>";
                                    $result->MoveNext();
                                }
                                echo "<option value='".$result->fields['idproveedor']."'>".$result->fields['proveedor']."</option>";
                                $result->MoveNext();                                    }
                         echo "</select></td></tr>";
                         echo "<tr ><td class=\"zpFormLabel\">Cartera :</td><td><select id='id_c'>
                             <option value='$id_c'>$crt</option>";
                         echo "</select></td>";
                         echo "</tr>";
                    $result = $db->Execute("SELECT idactividad,actividad FROM actividades where idestado=1");
                         echo "<tr ><td class=\"zpFormLabel\">Actividad :</td><td><select id='id_ac'>
                             <option value=''>-Seleccionar-</option>";
                         while (!$result->EOF) {
                             if($id_a==$result->fields['idactividad']){
                             echo "<option value='".$result->fields['idactividad']."' SELECTED>".$result->fields['actividad']."</option>";
                             $result->MoveNext();    
                             }
                             echo "<option value='".$result->fields['idactividad']."'>".$result->fields['actividad']."</option>";
                             $result->MoveNext();                                      }
                         echo "</select></td>";
                         echo "</tr>";
                  
                    $result = $db->Execute("SELECT * FROM estados");
                        echo "<tr ><td class='zpFormLabel'>Estado :</td><td><select id='ac_estado'>";
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
                    <input type=\"button\" class='btn' onclick=\"insert('a_xc&&update=1&&id=$id_rc');\" value=\"Actualizar \"/>
                    <input type=\"button\" id='atra' class='btn' onclick=\"m5('actv_c&pag=1');\"  value=\"Regresar \"/>
                        </center>
                    </div>";
                    return false;
            }
            echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;text-align:left;'><legend>Ingresar Actividad de Carteras</legend>
                    <table>";
                    $result = $db->Execute("SELECT * FROM proveedores where idestado=1 ORDER BY proveedor ");
                         echo "<tr><td class=\"zpFormLabel\">Proveedor:</td><td><select id='prov' onchange='cart();'><option value=''>-Seleccionar-</option>";
                            while (!$result->EOF) {
                         echo "<option value='".$result->fields['idproveedor']."'>".$result->fields['proveedor']."</option>";
                         $result->MoveNext();                                    }
                         echo "</select></td></tr>";
                         echo "<tr><td class=\"zpFormLabel\">Cartera :</td><td><select id='id_c'>
                             <option value=''>-Todos-</option>";
                         echo "</select></td>";
                         echo "</tr>";
                    $result = $db->Execute("SELECT idactividad,actividad FROM actividades where idestado=1 ORDER BY actividad ");
                         echo "<tr><td class=\"zpFormLabel\">Actividad :</td><td><select id='id_ac'>
                             <option value=''>-Seleccionar-</option>";
                         while (!$result->EOF) {
                         echo "<option value='".$result->fields['idactividad']."'>".$result->fields['actividad']."</option>";
                         $result->MoveNext();                                      }
                         echo "</select></td>";
                         echo "</tr>";
                    
                    echo "
                    </table>
                    </fieldset>
                    <div id=\"ykBodys\" style=\"color:red;\">
                    </div>
                    <input type=\"button\" class='btn' onclick=\"insert('a_xc');\" value=\"Agregar \"/>
                    <td class=\"botones\"><input id='atra' type=\"button\" class='btn' onclick=\"m5('actv_c&pag=1');\"  value=\"Regresar \"/></td>
                        </center>
                    </div>";
            break;
//------------------------------------------------------------------------------------------------
         case "i_jc":
            if(isset($_GET['update']) && $_GET['update']!==""){
                $id_rc=$_GET['update'];
                $sql= $db->Execute("SELECT  c.idcartera,c.cartera, j.justificacion,j.idjustificacion, p.proveedor,p.idproveedor,e.estado,e.idestado
                                    FROM justificacion_carteras jc
                                    JOIN carteras c ON jc.idcartera=c.idcartera
                                    JOIN justificaciones j ON jc.idjustificacion=j.idjustificacion
                                    JOIN proveedores p ON c.idproveedor=p.idproveedor
                                    JOIN estados e ON e.idestado=jc.idestado
									WHERE jc.idjustificacioncartera='$id_rc'");
                $id_p=$sql->fields['idproveedor'];
                $id_j=$sql->fields['idjustificacion'];
                $id_c=$sql->fields['idcartera'];
                $crt=$sql->fields['cartera'];
                $est=$sql->fields['idestado'];
                
                echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;text-align:left;'><legend>Actualizar Justificacion de Cartera</legend>
                    <table>";
                    $result = $db->Execute("SELECT * FROM proveedores ");
                         echo "<tr ><td class=\"zpFormLabel\">Proveedor:</td><td><select id='prov' onchange='cart();'><option value=''>-Seleccionar-</option>";
                            while (!$result->EOF) {
                                if($id_p==$result->fields['idproveedor']){
                                    echo "<option value='".$result->fields['idproveedor']."' SELECTED>".$result->fields['proveedor']."</option>";
                                    $result->MoveNext();
                                }
                                echo "<option value='".$result->fields['idproveedor']."'>".$result->fields['proveedor']."</option>";
                                $result->MoveNext();                                    }
                         echo "</select></td></tr>";
                         echo "<tr ><td class=\"zpFormLabel\">Cartera :</td><td><select id='id_c'>
                             <option value='$id_c'>$crt</option>";
                         echo "</select></td>";
                         echo "</tr>";
                    $result = $db->Execute("SELECT idjustificacion,justificacion FROM justificaciones where idestado=1");
                         echo "<tr ><td class=\"zpFormLabel\">Justificacion :</td><td><select id='id_js'>
                             <option value=''>-Seleccionar-</option>";
                         while (!$result->EOF) {
                             if($id_j==$result->fields['idjustificacion']){
                             echo "<option value='".$result->fields['idjustificacion']."' SELECTED>".$result->fields['justificacion']."</option>";
                             $result->MoveNext();    
                             }
                             echo "<option value='".$result->fields['idjustificacion']."'>".$result->fields['justificacion']."</option>";
                             $result->MoveNext();                                      }
                         echo "</select></td>";
                         echo "</tr>";
                  
                    $result = $db->Execute("SELECT * FROM estados");
                        echo "<tr ><td class='zpFormLabel'>Estado :</td><td><select id='jc_estado'>";
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
                    <input type=\"button\" class='btn' onclick=\"insert('j_xc&&update=1&&id=$id_rc');\" value=\"Actualizar \"/>
                    <input type=\"button\" id='atra' class='btn' onclick=\"m5('just_c&pag=1');\"  value=\"Regresar \"/>
                        </center>
                    </div>";
                    return false;
            }
            echo "<div id=\"ykBody\">
                <center>
                <fieldset style='width:300px;text-align:left;'><legend>Ingresar Justificacion de Carteras</legend>
                    <table>";
                    $result = $db->Execute("SELECT * FROM proveedores where idestado=1 ORDER BY proveedor ");
                         echo "<tr><td class=\"zpFormLabel\">Proveedor:</td><td><select id='prov' onchange='cart();'><option value=''>-Seleccionar-</option>";
                            while (!$result->EOF) {
                         echo "<option value='".$result->fields['idproveedor']."'>".$result->fields['proveedor']."</option>";
                         $result->MoveNext();                                    }
                         echo "</select></td></tr>";
                         echo "<tr><td class=\"zpFormLabel\">Cartera :</td><td><select id='id_c'>
                             <option value=''>-Todos-</option>";
                         echo "</select></td>";
                         echo "</tr>";
                    $result = $db->Execute("SELECT idjustificacion,justificacion FROM justificaciones where idestado=1 ORDER BY justificacion ");
                         echo "<tr><td class=\"zpFormLabel\">Justificacion :</td><td><select id='id_js'>
                             <option value=''>-Seleccionar-</option>";
                         while (!$result->EOF) {
                         echo "<option value='".$result->fields['idjustificacion']."'>".$result->fields['justificacion']."</option>";
                         $result->MoveNext();                                      }
                         echo "</select></td>";
                         echo "</tr>";
                    
                    echo "
                    </table>
                    </fieldset>
                    <div id=\"ykBodys\" style=\"color:red;\">
                    </div>
                    <input type=\"button\" class='btn' onclick=\"insert('j_xc');\" value=\"Agregar \"/>
                    <td class=\"botones\"><input id='atra' type=\"button\" class='btn' onclick=\"m5('just_c&pag=1');\"  value=\"Regresar \"/></td>
                        </center>
                    </div>";
            break;			
			

    }
}
?>
