<?php
session_start();
if (!isset($_SESSION["user"]) || $_SESSION["user"] == "") {
	header("Location: login.php");
}

if (!isset($_GET["user_new"])  && !isset($_GET["update"]) ) {
	header("Location: login.php");
	return false;
}


require 'define_con.php';
$n=0;

if(isset($_GET['user_new']) && $_GET['user_new']=="2"){

$pag=0;
$r_pag=20;

	$sql="SELECT  idusuario,login, usuario, nivel, e.idestado
                        FROM usuarios u
                        JOIN niveles n ON u.idnivel=n.idnivel
                        JOIN estados e ON u.idestado=e.idestado
						";
	if($_SESSION['nivel']==5){
		$idprove=$_SESSION['prove'];
		$sql.=" where u.idproveedor='$idprove' and u.idnivel='2'";
	}		
	
	$sql.=" ORDER BY u.idestado DESC,u.usuario  ";	
					
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
			if($_GET['pag']!=1){
				$n=$_GET['pag']-1;
				$n=$n*$r_pag;
			}

	$result=$db->Execute($sql);		
//$result = $db->Execute($sql);
						if(!$result){
						echo "<input class='btn' type=\"button\" onclick=\"mostrar('user_new=1');\" value=\"Nuevo Usuario\"/>";
						return false;
						}
echo "<div id=\"ykBody\">
      <center>
      <fieldset><legend>Usuarios del Sistema</legend>
      <div id=\"design1\">
      <table style='text-align:left;'>
      <tr><th>Nº</th><th>Id</th><th>Usuario</th><th>Nombres</th><th>Nivel</th><th>Estado</th><tr>";
			while (!$result->EOF) {
				   //for ($i=0, $max=$result->FieldCount(); $i < $max; $i++)
					  echo "<tr><td>".++$n."</td>
					  <td style='background-color:white;'>".$result->fields['idusuario']."</td>
					  <td style='background-color:white;'>".$result->fields['login']."</td>";
					  if($_SESSION['nivel']==1 or $_SESSION['nivel']==5){
					//echo "<td style='background-color:white;'> <a style='text-decoration:underline;cursor:pointer;' href='#'>".htmlspecialchars($result->fields['usuario'])."</a></td>";
					  //}else{
						echo "<td style='background-color:white;'> <a style='text-decoration:underline;cursor:pointer;'  onclick=\"mostrar('update=".$result->fields['idusuario']."');\">".htmlspecialchars($result->fields['usuario'])."</a></td>";
					  }
					  echo "<td style='background-color:white;'>".$result->fields['nivel']."</td>
					  <td style='background-color:white;'>";
						   if($result->fields['idestado']== "1"){
							   echo "<img src='imag/icons/estado_1.png'/>";
						   }else{
							   echo "<img src='imag/icons/estado_2.png'/>";
								}
					  echo  "</td></tr>";
					  $result->MoveNext();                            
			}
	?>
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
									echo "<a onclick=\"mostrar('user_new=2&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"mostrar('user_new=2&&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"mostrar('user_new=2&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"mostrar('user_new=2&&pag=$t_p');\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=users'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
	<?php
        echo "</table></div>
        </fieldset>";
		
        //if($_SESSION['nivel']!=1 && $_SESSION['nivel']!=5) {
			echo "<input class='btn' type=\"button\" onclick=\"mostrar('user_new=1');\" value=\"Nuevo Usuario\"/>";
		//}
        
		echo"</center>
        </div>";
//------------------------------------------------------------------------------------------------------------------------------
}else if(isset($_GET['user_new']) && $_GET['user_new']=="1"){
    echo "<center><div id=\"ykBody\">
          <fieldset style='width:320px;text-align:left;'><legend>Ingresar Usuario</legend>
          <div id=\"design\">
          <table>
          
          <!--<tr><td class=\"zpFormLabel\">Contraseña</td><td><input type='password' size='20' id='u_pas'></td></tr>-->
          <tr><td class=\"zpFormLabel\">Apellidos,Nombres:</td><td><input id='u_name'' type='text' size='20'></td></tr>";
          $result = $db->Execute("SELECT * FROM doi where idestado=1");
          echo "<tr><td class=\"zpFormLabel\">Tipo de Doc. :</td><td><select id='u_doc'>
          <option value=''>-Seleccionar-</option>";
                while (!$result->EOF) {
          echo "<option value='".$result->fields['iddoi']."'>".$result->fields['doi']."</option>";
          $result->MoveNext();  }
          echo "</select></td></tr>";
          echo "<tr><td class=\"zpFormLabel\">Nº Documento</td><td><input type='text' size='20' id='u_ndoc' onblur=\"insert('user');\"/></td></tr>
                <tr><td class=\"zpFormLabel\">Fecha de Nac.</td><td><input type='text' size='20' id='u_fn'><button onclick=\"cale3();\" id=\"f_b1\">...</button></td></tr>
		  <tr><td class=\"zpFormLabel\">Domicilio</td><td><input type='text' size='20' id='u_dom'></td></tr>";
          $r1 = $db->Execute("SELECT * FROM ubigeos  WHERE codprov=00 AND coddist=00");
          echo  "<tr><td class=\"zpFormLabel\">Departamento:</td><td><select id='dpto' onchange=\"dpto();\"><option value=''>-Todos-</option>";
                while (!$r1->EOF) {
          echo "<option value='".$r1->fields['coddpto']."' ondclick=\"m7('ubig','1'";
          echo   $r1->fields['coddpto'];
          echo "');\">".utf8_encode($r1->fields['nombre'])."</option>";
          $r1->MoveNext();        }
          echo  "</td></tr></select>
          <tr><td class=\"zpFormLabel\">Provincia:</td><td><select id='prov' onchange=\"dist();\"><option value=''>-Todos-</option></select></td></tr>
          <tr><td class=\"zpFormLabel\">Distrito:</td><td><select id='dist'><option value=''>-Todos-</option></select></td></tr>
          
          <tr><td class=\"zpFormLabel\">Telefono</td><td><input type='text' size='20' id='u_fono'></td></tr>
          <tr><td class=\"zpFormLabel\">E-mail</td><td><input type='text' size='20' id='u_email'></td></tr>
          <tr><td class=\"zpFormLabel\">Fecha de Ingreso</td><td><input type='text' size='20' id='u_fi'><button onclick=\"cale2();\" id=\"f_btn1\">...</button></td></tr>";
          $result = $db->Execute("SELECT * FROM niveles where idestado=1");
          echo "<tr><td class=\"zpFormLabel\">Nivel :</td><td><select id='u_niv' onchange='users();'>";
          echo "<option value=''>-Seleccionar-</option>";
                while (!$result->EOF) {
                echo "<option value='".$result->fields['idnivel']."'>".$result->fields['nivel']."</option>";
                $result->MoveNext();  }
          $result = $db->Execute("SELECT * FROM proveedores where idestado=1");
          echo "<tr id='t_prov' style='visibility:hidden;display:none;'><td class=\"zpFormLabel\">Proveedor :</td><td><select id='u_prove' onchange='cart();' '>";
          echo "<option value=''>-Seleccionar-</option>";
                while (!$result->EOF) {
                echo "<option value='".$result->fields['idproveedor']."'>".$result->fields['proveedor']."</option>";
                $result->MoveNext();  }
          $result = $db->Execute("SELECT * FROM carteras where idestado=1");
          echo "<tr id='t_cart' style='visibility:hidden;display:none;'><td class=\"zpFormLabel\">Cartera :</td><td><select id='u_cart' >";
          echo "<option value=''>-Seleccionar-</option>";
                while (!$result->EOF) {
               
                $result->MoveNext();  }
          echo    "</select></td></tr>";
          echo    "</select></td></tr>";
          $result = $db->Execute("SELECT * FROM estados where idestado=1");
          echo "<tr style='visibility:hidden;position:absolute;'><td class=\"zpFormLabel\">Estado :</td><td><select id='u_est'>";
                while (!$result->EOF) {
                echo "<option value='".$result->fields['idestado']."'>".$result->fields['estado']."</option>";
                $result->MoveNext();  }
          echo "</select></td></tr>
		  <tr style='visibility:hidden;position:absolute;'><td class=\"zpFormLabel\">Empresa:</td><td><select id='u_emp'>";
				$result = $db->Execute("SELECT * FROM empresas");
                while (!$result->EOF) {
                echo "<option value='".$result->fields['idempresa']."'>".$result->fields['empresa']."</option>";
                $result->MoveNext();  }
		  echo "</select></td></tr>
		  <tr style='visibility:hidden;position:absolute;'><td class=\"zpFormLabel\">Modalidad:</td><td><select id='u_mod'>";
				$result = $db->Execute("SELECT * FROM modalidades");
                while (!$result->EOF) {
                echo "<option value='".$result->fields['idmodalidad']."'>".$result->fields['modalidad']."</option>";
                $result->MoveNext();  }
		  echo "</select></td></tr>
		  <tr><td class=\"zpFormLabel\">Turno:</td><td><select id='u_tur'>";
				echo "<option value=''>-Seleccionar-</option>";
				$result = $db->Execute("SELECT * FROM turnos");
                while (!$result->EOF) {
                echo "<option value='".$result->fields['idturno']."'>".$result->fields['turno']."</option>";
                $result->MoveNext();  }
		  echo "</select></td></tr>
		  <tr><td class=\"zpFormLabel\">Horario S.:</td><td><input type='text' size='20' id='u_hor_s'></td></tr>
		  <tr><td class=\"zpFormLabel\">Horario L-V:</td><td><input type='text' size='20' id='u_hor_lv'></td></tr>
		   <tr><td class=\"zpFormLabel\">Login:</td><td><input onblur=\"insert('user');\" type='text' size='20' id='u_user'></td></tr>
		 </table></div>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input id='a_user' class='btn' type=\"button\" onclick=\"insert('user');\" value=\"Agregar Usuario \" />
                <input id='atra' class='btn' type=\"button\" onclick=\"mostrar('user_new=2&pag=1');\" value=\"Regresar\"/>
                </center>
                </div>";

}

if(isset($_GET['update']) && $_GET['update']!==""){
    $id_user=$_GET['update'];
    $result = $db->Execute("SELECT  u.*, e.idestado,ubs.nombre departamento,ub.nombre provincia ,ub2.nombre distrito
                            FROM usuarios u
                            JOIN niveles n ON u.idnivel=n.idnivel
                            JOIN estados e ON u.idestado=e.idestado
                            JOIN ubigeos ubs ON ubs.coddpto=u.coddpto  AND  ubs.codprov=00 AND ubs.coddist=00
                            JOIN ubigeos ub ON ub.coddpto=u.coddpto  AND  ub.codprov=u.codprov AND ub.coddist=00
                            JOIN ubigeos ub2 ON ub2.coddpto=u.coddpto  AND  ub2.codprov=u.codprov AND ub2.coddist=u.coddist
                            WHERE u.idusuario=$id_user
                            ");
							if(!$result){
						return false;
						}
    $t_doi=$result->fields['iddoi'];
    $doi=$result->fields['documento'];
    $login=$result->fields['login'];
    $fecha_n=$result->fields['fechanacimiento'];
    $dpto=$result->fields['coddpto'];
    $id_prov=$result->fields['codprov'];
    $prov=$result->fields['provincia'];
    $id_dis=$result->fields['coddist'];
    $dis=$result->fields['distrito'];
    $dom=$result->fields['direccion'];
    $fono=$result->fields['telefonos'];
    $mail=$result->fields['email'];
    $fecha_i=$result->fields['fechaingreso'];
    $nivel=$result->fields['idnivel'];
    $provee=$result->fields['idproveedor'];
    $cart=$result->fields['idcartera'];
    $estado=$result->fields['idestado'];
	$modalidad=$result->fields['idmodalidad'];
	$turno=$result->fields['idturno'];
	$empresa=$result->fields['idempresa'];
	$hors=$result->fields['horarios'];
	$horlv=$result->fields['horariolv'];
	
    echo "<center><div id=\"ykBody\">
          <fieldset style='width:320px;text-align:left;'><legend>Actualizar Usuario</legend>
          <div id=\"design\">
          <table>
          <!--<tr><td class=\"zpFormLabel\">Apellidos,Nombres:</td><td><input type='password' size='20' id='u_pas' value='".$result->fields['clave']."'></td></tr>-->
          <tr><td class=\"zpFormLabel\">Apellidos,Nombres:</td><td><input id='u_name'' type='text' size='20' value='".$result->fields['usuario']."'></td></tr>";
          $result = $db->Execute("SELECT * FROM doi where idestado=1");
          echo "<tr><td class=\"zpFormLabel\">Tipo de Doc. :</td><td><select id='u_doc'>
          <option value=''>-Seleccionar-</option>";
		  
          while (!$result->EOF) {
              if($t_doi == $result->fields['iddoi'] ){
              echo "<option value='".$result->fields['iddoi']."' SELECTED>".$result->fields['doi']."</option>";
              $result->MoveNext();
			  
              }
              echo "<option value='".$result->fields['iddoi']."'>".$result->fields['doi']."</option>";
              $result->MoveNext();  }
          echo "</select></td></tr>";
          echo "<tr><td class=\"zpFormLabel\">Nº Documento</td><td><input type='text' size='20' id='u_ndoc' value='".$doi."'></td></tr>
                <tr><td class=\"zpFormLabel\">Fecha de Nac.</td><td><input type='text' size='20' id='u_fn' value='".$fecha_n."'><button onclick=\"cale3();\" id=\"f_b1\">...</button></td></tr>
		  <tr><td class=\"zpFormLabel\">Domicilio</td><td><input type='text' size='20' id='u_dom' value='".$dom."'></td></tr>";
          $r1 = $db->Execute("SELECT * FROM ubigeos  WHERE codprov=00 AND coddist=00");
          echo  "<tr><td class=\"zpFormLabel\">Departamento:</td><td><select id='dpto' onchange=\"dpto();\"><option value=''>-Todos-</option>";
                while (!$r1->EOF) {
                    if($dpto == $r1->fields['coddpto']){
                    echo "<option value='".$r1->fields['coddpto']."' ondclick=\"m7('ubig','1'";
                    echo   $r1->fields['coddpto'];
                    echo "');\" SELECTED>".utf8_encode($r1->fields['nombre'])."</option>";
                    $r1->MoveNext();
                    }
                echo "<option value='".$r1->fields['coddpto']."' ondclick=\"m7('ubig','1'";
                echo   $r1->fields['coddpto'];
                echo "');\">".utf8_encode($r1->fields['nombre'])."</option>";
                $r1->MoveNext();   }
		  $q_sql=$db->Execute("SELECT * FROM ubigeos WHERE coddpto='$dpto' AND codprov!='00' AND coddist='00'");	
          echo  "</td></tr></select>
          <tr><td class=\"zpFormLabel\">Provincia:</td><td><select id='prov' onchange=\"dist();\">";
			  while (!$q_sql->EOF) {
				if($id_prov==$q_sql->fields['codprov']){
					echo "<option value='".$q_sql->fields['codprov']."' SELECTED>".utf8_encode($q_sql->fields['nombre'])."</option>";
					$q_sql->MoveNext();
					continue;
				}
				echo "<option value='".$q_sql->fields['codprov']."'>".utf8_encode($q_sql->fields['nombre'])."</option>";
				$q_sql->MoveNext();
			  }
		  echo "</select></td></tr>
          <tr><td class=\"zpFormLabel\">Distrito:</td><td><select id='dist'>";
		  $qd_sql=$db->Execute("SELECT * FROM ubigeos WHERE coddpto='$dpto' AND codprov='$id_prov' AND coddist!='00'");	 
			  while (!$qd_sql->EOF) {
				if($id_dis==$qd_sql->fields['coddist']){
					echo "<option value='".$qd_sql->fields['coddist']."' SELECTED>".utf8_encode($qd_sql->fields['nombre'])."</option>";
					$qd_sql->MoveNext();
					continue;
				}
				echo "<option value='".$qd_sql->fields['coddist']."'>".utf8_encode($qd_sql->fields['nombre'])."</option>";
				$qd_sql->MoveNext();
			  }
		  echo "</select></td></tr>
           <tr><td class=\"zpFormLabel\">Telefono</td><td><input type='text' size='20' id='u_fono' value='".$fono."'></td></tr>
          <tr><td class=\"zpFormLabel\">E-mail</td><td><input type='text' size='20' id='u_email' value='".$mail."'></td></tr>
          <tr><td class=\"zpFormLabel\">Fecha de Ingreso</td><td><input type='text' size='20' id='u_fi' value='".$fecha_i."'><button onclick=\"cale2();\" id=\"f_btn1\">...</button></td></tr>";
          $result = $db->Execute("SELECT * FROM niveles where idestado=1");
          echo "<tr><td class=\"zpFormLabel\">Nivel :</td><td><select id='u_niv' onchange='users();'>";
          echo "<option value=''>-Seleccionar-</option>";
                while (!$result->EOF) {
                    if($nivel==$result->fields['idnivel']){
                    echo "<option value='".$result->fields['idnivel']."' SELECTED>".$result->fields['nivel']."</option>";
                    $result->MoveNext();
                    }
                    echo "<option value='".$result->fields['idnivel']."'>".$result->fields['nivel']."</option>";
                    $result->MoveNext();  }
          echo "</select></td></tr>";
          $result = $db->Execute("SELECT * FROM proveedores where idestado=1");
          echo "<tr id='t_prov'><td class=\"zpFormLabel\">Proveedor :</td><td><select id='u_prove' onchange='cart();'>";
          echo "<option value=''>-Seleccionar-</option>";
                while (!$result->EOF) {
                    if($provee==$result->fields['idproveedor']){
                    echo "<option value='".$result->fields['idproveedor']."' SELECTED>".$result->fields['proveedor']."</option>";    
                    $result->MoveNext();
                    }
                    echo "<option value='".$result->fields['idproveedor']."'>".$result->fields['proveedor']."</option>";
                    $result->MoveNext();  }
          $result = $db->Execute("SELECT * FROM carteras where idestado=1");
          echo "</select></td></tr>";
          echo "<tr id='t_cart'><td class=\"zpFormLabel\">Cartera :</td><td><select id='u_cart'>";
          echo "<option value=''>-Seleccionar-</option>";
                while (!$result->EOF) {
                    if($cart==$result->fields['idcartera']){
                        echo "<option value='".$result->fields['idcartera']."' SELECTED>".$result->fields['cartera']."</option>";
                        $result->MoveNext();
                    }
                    echo "<option value='".$result->fields['idcartera']."'>".$result->fields['cartera']."</option>";
                    $result->MoveNext();  }
          echo    "</select></td></tr>";
         
          $result = $db->Execute("SELECT * FROM estados");
          echo "<tr><td class=\"zpFormLabel\">Estado :</td><td><select id='u_est'>";
          echo "<option value=''>-Seleccionar-</option>";
                while (!$result->EOF) {
                    if($estado==$result->fields['idestado']){
                        echo "<option value='".$result->fields['idestado']."' SELECTED>".$result->fields['estado']."</option>";
                        $result->MoveNext();
						continue;
                    }
                    echo "<option value='".$result->fields['idestado']."'>".$result->fields['estado']."</option>";
                    $result->MoveNext();  
				}
          echo "</select></td></tr>
		    <tr><td class=\"zpFormLabel\">Empresa:</td><td><select id='u_emp'>";
				echo "<option value=''>-Seleccionar-</option>";
				$result = $db->Execute("SELECT * FROM empresas");
                while (!$result->EOF) {
				if($empresa==$result->fields['idempresa']){ $select="SELECTED";}else{$select="";}
                echo "<option value='".$result->fields['idempresa']."' $select>".$result->fields['empresa']."</option>";
				
                $result->MoveNext();  }
		  echo "</select></td></tr>
		  <tr><td class=\"zpFormLabel\">Modalidad:</td><td><select id='u_mod'>";
				echo "<option value=''>-Seleccionar-</option>";
				$result = $db->Execute("SELECT * FROM modalidades");
                while (!$result->EOF) {
				if($modalidad==$result->fields['idmodalidad']){ $select="SELECTED";}else{$select="";}
                echo "<option value='".$result->fields['idmodalidad']."' $select>".$result->fields['modalidad']."</option>";
                $result->MoveNext();  }
		  echo "</select></td></tr>
		  <tr><td class=\"zpFormLabel\">Turno:</td><td><select id='u_tur'>";
				echo "<option value=''>-Seleccionar-</option>";
				$result = $db->Execute("SELECT * FROM turnos");
                while (!$result->EOF) {
				if($turno==$result->fields['idturno']){ $select="SELECTED";}else{$select="";}
                echo "<option value='".$result->fields['idturno']."' $select>".$result->fields['turno']."</option>";
                $result->MoveNext();  }
		  echo "</select></td></tr>
		  <tr><td class=\"zpFormLabel\">Horario S.:</td><td><input type='text' size='20' id='u_hor_s' value='$hors'></td></tr>
		  <tr><td class=\"zpFormLabel\">Horario L-V:</td><td><input type='text' size='20' id='u_hor_lv' value='$horlv'></td></tr>
			<tr style='display:none;'><td class=\"zpFormLabel\">Login:</td><td><input type='text' value='$login' size='20' id='u_user'></td></tr>		
		  </table></div>
                </fieldset>
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input class='btn' type=\"button\" onclick=\"insert('user&&update=1&&id=$id_user');\" value=\"Actualizar \"/>
                <input class='btn' type=\"button\" id='atra' onclick=\"mostrar('user_new=2&pag=1');\" value=\"Regresar\"/>
                </center>
                </div>";
    
}
?>
