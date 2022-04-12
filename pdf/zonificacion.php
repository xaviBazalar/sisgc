<?php
//session_start();
/*if (!isset($_SESSION["user"]) || $_SESSION["user"] == "") {
	header("Location: login.php");
}*/
//require 'define_con.php'; esta lne la modifique porque ya noesta con ajax
$n = 0;

if(isset($_GET['parametros'])){
     $id_param=$_GET['parametros'];
      switch($id_param){
      case "ubig":
            $RegistrosAMostrar=20;
            
            if(isset($_GET['pag'])&& $_GET['pag']!==""){
                    $RegistrosAEmpezar=($_GET['pag']-1)*$RegistrosAMostrar;
                    $PagAct=$_GET['pag'];
            
            }else{
                    $RegistrosAEmpezar=0;
                    $PagAct=1;
            }
             echo "<div id=\"ykBody\">
                    <center>";
                    $r1 = $db->Execute("SELECT * FROM ubigeos  WHERE codprov=00 AND coddist=00");
             echo  "Departamento:<select id='dpto' onchange='dpto();'><option value=''>-Todos-</option>";
                     while (!$r1->EOF) {
                     echo "<option value='".$r1->fields['coddpto']."' ondclick=\"m6('ubig','','";
                     echo   $r1->fields['coddpto'];
                     echo "');\">".utf8_encode($r1->fields['nombre'])."</option>";
                     $r1->MoveNext();
                                          }
             echo  "</select>";
             echo " Provincia:<select id='prov' onchange='dist();'><option value=''>-Todos-</option>";
                    
             echo "</select>
                    Distrito:<select id='dist'><option value=''>-Todos-</option></select>
                    <input type='button' value='Filtrar' onclick='m7(\"ubig\",1);'>
                    Generar Pdf<img src='imag/pdf-icon.png'/ style='float:inherit;position:relative;top:6px;cursor:pointer;' onclick='pdf();'>
                    Generar Excel<img src='imag/logo_excel.png'/ style='float:inherit;position:relative;top:6px;cursor:pointer;' onclick='excel();'>
                    <fieldset style='width:800px;'><legend>Ubigeo</legend>
                    <div id=\"design1\"><table style='text-align:left;'><th>Nº</th><th>Id</th><th>Nombre</th></th><th>Departamento</th><th>Provincia</th><th>Distrito</th>";
             $result = $db->Execute("SELECT * FROM ubigeos ORDER BY idubigeo LIMIT $RegistrosAEmpezar, $RegistrosAMostrar");
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
            echo "</table></div>
                ";
            //******--------determinar las páginas---------******//
            $NroRegistros=$db->Execute("SELECT COUNT(*)FROM ubigeos");
            $NroRegistros=$NroRegistros->fields['0'];
            
            $PagAnt=$PagAct-1;
            $PagSig=$PagAct+1;
            $PagUlt=$NroRegistros/$RegistrosAMostrar;

            $Res=$NroRegistros%$RegistrosAMostrar;
            
            if($Res>0) $PagUlt=floor($PagUlt)+1;

            //desplazamiento
            echo "<a onclick=\"m6('ubig',1);\">Primero</a> ";
            if($PagAct>1) echo "<a onclick=\"m6('ubig','$PagAnt')\">Anterior</a> ";
            echo "<strong>Pagina ".$PagAct."/".$PagUlt."</strong>";
            if($PagAct<$PagUlt)  echo " <a onclick=\"m6('ubig','$PagSig')\">Siguiente</a> ";
            echo "<a onclick=\"m6('ubig','$PagUlt')\">Ultimo</a></fieldset>
                   </center>
                </div>";
                    
            break;
            //----------------------------------------------
      case "planos":
          $RegistrosAMostrar=20;

            if(isset($_GET['pag'])&& $_GET['pag']!==""){
                    $RegistrosAEmpezar=($_GET['pag']-1)*$RegistrosAMostrar;
                    $PagAct=$_GET['pag'];

            }else{
                    $RegistrosAEmpezar=0;
                    $PagAct=1;
            }
            //-------------------------------------------------------
             echo "
                    <center>";
                    $r1 = $db->Execute("SELECT * FROM ubigeos  WHERE codprov=00 AND coddist=00");
             echo  "Departamento:<select id='dpto' onchange='dpto();'><option value=''>-Todos-</option>";
                     while (!$r1->EOF) {
                     echo "<option value='".$r1->fields['coddpto']."' ondclick=\"m7('ubig','1'";
                     echo   $r1->fields['coddpto'];
                     echo "');\">".utf8_encode($r1->fields['nombre'])."</option>";
                     $r1->MoveNext();
                                          }
             echo  "</select>";
             echo " Provincia:<select id='prov' onchange='dist();'><option value=''>-Todos-</option>";

             echo "</select>
                    Distrito:<select id='dist'><option value=''>-Todos-</option></select>
                    <input type='button' value='Filtrar' onclick='m7(\"planos\",1);'>
                   <font color='blue'>Generar Pdf</font><img src='imag/pdf-icon.png'/ style='float:inherit;position:relative;top:6px;cursor:pointer;' onclick='pdf();'>
                   <font color='blue'> Generar Excel</font><img src='imag/logo_excel.png'/ style='float:inherit;position:relative;top:6px;cursor:pointer;' onclick='excel();'>
                    <fieldset style='width:800px;'><legend>Planos</legend>
                    <div id=\"design1\"><table style='text-align:left;'><th>Nº</th><th>Id</th><th>Departamento</th></th><th>Provincia</th><th>Distrito</th><th>Nº de Plano</th>";
             //$result = $db->Execute("SELECT * FROM planos  ORDER BY idubigeo LIMIT $RegistrosAEmpezar, $RegistrosAMostrar");
             $result = $db->Execute("SELECT pl.*,u2.nombre dpto,u3.nombre prov,u.nombre dist
                                    FROM planos pl
                                    JOIN ubigeos u ON pl.idubigeo=u.idubigeo
                                    JOIN ubigeos u2 ON u.coddpto=u2.coddpto AND u2.codprov=00
                                    JOIN ubigeos u3 ON u.coddpto=u3.coddpto AND  u.codprov=u3.codprov AND  u3.coddist=00
                                    LIMIT $RegistrosAEmpezar, $RegistrosAMostrar");

             while(!$result->EOF){
                    echo "<tr>";
                    echo "<td>".++$n."</td><td>".$result->fields['idplano']."</td>";
                    echo "<td>".utf8_encode($result->fields['dpto'])."</td>";
                    //-----------------------------------------
                    echo "<td>".utf8_encode($result->fields['prov'])."</td>";
                    //-----------------------------------------
                    echo "<td>".utf8_encode($result->fields['dist'])."</td>";
                    echo "<td><a  href=\"planos/".$result->fields['plano'].".pdf\" rel=\"shadowbox[plano];\">".$result->fields['plano']."</a></td>";
                    //echo "<td>".$result->fields['idubigeo']."</td>";
                    echo "</tr>";
                    $result->MoveNext();
            }
            echo "</table></div>
                ";
            //******--------determinar las páginas---------******//
            $NroRegistros=$db->Execute("SELECT COUNT(*)FROM planos");
            $NroRegistros=$NroRegistros->fields['0'];

            $PagAnt=$PagAct-1;
            $PagSig=$PagAct+1;
            $PagUlt=$NroRegistros/$RegistrosAMostrar;

            $Res=$NroRegistros%$RegistrosAMostrar;

            if($Res>0) $PagUlt=floor($PagUlt)+1;
            //--------------
            //desplazamiento
            echo "<a onclick=\"m6('planos',1);\">Primero</a> ";
            if($PagAct>1) echo "<a onclick=\"m6('planos','$PagAnt')\">Anterior</a> ";
            echo "<strong>Pagina ".$PagAct."/".$PagUlt."</strong>";
            if($PagAct<$PagUlt)  echo " <a onclick=\"m6('planos','$PagSig')\">Siguiente</a> ";
            echo "<a onclick=\"m6('planos','$PagUlt')\">Ultimo</a></fieldset>
            </br><input type=\"button\" onclick=\"m6('i_plano');\" value=\"Agregar Nuevo \"/>
                   </center>
				  
                ";

            break;
            //--------------------------------------------------------
      case "cuadr":
          $RegistrosAMostrar=20;

            if(isset($_GET['pag'])&& $_GET['pag']!==""){
                    $RegistrosAEmpezar=($_GET['pag']-1)*$RegistrosAMostrar;
                    $PagAct=$_GET['pag'];

            }else{
                    $RegistrosAEmpezar=0;
                    $PagAct=1;
            }
            //-------------------------------------------------------
             echo "<div id=\"ykBody\">
                    <center>";
                    $r1 = $db->Execute("SELECT * FROM ubigeos  WHERE codprov=00 AND coddist=00");
             echo  "Departamento:<select id='dpto' onchange='dpto();'><option value=''>-Todos-</option>";
                     while (!$r1->EOF) {
                     echo "<option value='".$r1->fields['coddpto']."' ondclick=\"m7('ubig','1'";
                     echo   $r1->fields['coddpto'];
                     echo "');\">".utf8_encode($r1->fields['nombre'])."</option>";
                     $r1->MoveNext();
                                          }
             echo  "</select>";
             echo " Provincia:<select id='prov' onchange='dist();'><option value=''>-Todos-</option>";

             echo "</select>
                    Distrito:<select id='dist'><option value=''>-Todos-</option></select>
                    <input type='button' value='Filtrar' onclick='m7(\"planos\",1);'>
                   <font color='blue'>Generar Pdf</font><img src='imag/pdf-icon.png'/ style='float:inherit;position:relative;top:6px;cursor:pointer;' onclick='pdf();'>
                   <font color='blue'> Generar Excel</font><img src='imag/logo_excel.png'/ style='float:inherit;position:relative;top:6px;cursor:pointer;' onclick='excel();'>
                    <fieldset style='width:800px;'><legend>Cuadrantes</legend>
                    <div id=\"design1\"><table><th>Nº</th><th>Id</th><th>Departamento</th></th><th>Provincia</th><th>Distrito</th><th>Nº de Plano</th><th>Cuadrante</th>";
             //$result = $db->Execute("SELECT * FROM planos  ORDER BY idubigeo LIMIT $RegistrosAEmpezar, $RegistrosAMostrar");
             $result = $db->Execute("SELECT cr.*,pl.plano,pl.idubigeo,u2.nombre dpto,u3.nombre prov,u.nombre dist
                                    FROM cuadrantes cr
                                    JOIN planos pl ON cr.idplano=pl.idplano
                                    JOIN ubigeos u ON pl.idubigeo=u.idubigeo
                                    JOIN ubigeos u2 ON u.coddpto=u2.coddpto AND u2.codprov=00
                                    JOIN ubigeos u3 ON u.coddpto=u3.coddpto AND  u.codprov=u3.codprov AND  u3.coddist=00
                                    LIMIT $RegistrosAEmpezar, $RegistrosAMostrar");

             while(!$result->EOF){
                    echo "<tr>";
                    echo "<td>".++$n."</td><td>".$result->fields['idcuadrante']."</td>";
                    echo "<td>".utf8_encode($result->fields['dpto'])."</td>";
                    //-----------------------------------------
                    echo "<td>".utf8_encode($result->fields['prov'])."</td>";
                    //-----------------------------------------
                    echo "<td>".utf8_encode($result->fields['dist'])."</td>";
                    //---------------------------------------------
                    echo "<td>".$result->fields['plano']."</td>";
                    //---------------------------------------------
                    echo "<td>".$result->fields['cuadrante']."</td>";
                    echo "</tr>";
                    $result->MoveNext();
            }
            echo "</table></div>
                ";
            //******--------determinar las páginas---------******//
            $NroRegistros=$db->Execute("SELECT COUNT(*)FROM planos");
            $NroRegistros=$NroRegistros->fields['0'];

            $PagAnt=$PagAct-1;
            $PagSig=$PagAct+1;
            $PagUlt=$NroRegistros/$RegistrosAMostrar;

            $Res=$NroRegistros%$RegistrosAMostrar;

            if($Res>0) $PagUlt=floor($PagUlt)+1;
            //--------------
            //desplazamiento
            echo "<a onclick=\"m6('planos',1);\">Primero</a> ";
            if($PagAct>1) echo "<a onclick=\"m6('planos','$PagAnt')\">Anterior</a> ";
            echo "<strong>Pagina ".$PagAct."/".$PagUlt."</strong>";
            if($PagAct<$PagUlt)  echo " <a onclick=\"m6('planos','$PagSig')\">Siguiente</a> ";
            echo "<a onclick=\"m6('planos','$PagUlt')\">Ultimo</a></fieldset>
            </br><input type=\"button\" onclick=\"m6('i_cuate');\" value=\"Agregar Nuevo \"/>
                   </center>
                </div>";

            break;
           
            //--------------------------------------------------------
      case "i_plano":
			require 'define_con.php'; 
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
                        <input type=\"button\" onclick=\"insert('plano');\" value=\"Agregar\"/>
                    <!--<input type=\"button\" onclick=\"m6('planos');\" value=\"Regresar\"/>-->
					 <input type=\"button\" onclick='location.reload();' value=\"Regresar\"/>
                    </center>
                    </div>";
          break;
          //--------------------------------------------------------
          case "i_cuate":
		  require 'define_con.php';
           echo "<center><div id=\"ykBody\">
                 <fieldset style='width:400px;'><legend> Cuadrante</legend>
                    <div id=\"design\">
                    <table>";
           /*$r1 = $db->Execute("SELECT * FROM ubigeos  WHERE codprov=00 AND coddist=00");
             echo  "<tr><td>Departamento:</td><td><select id='dpto' onchange='dpto();'><option value=''>-Todos-</option>";
                     while (!$r1->EOF) {
                     echo "<option value='".$r1->fields['coddpto']."' ondclick=\"m7('ubig','1'";
                     echo   $r1->fields['coddpto'];
                     echo "');\">".utf8_encode($r1->fields['nombre'])."</option>";
                     $r1->MoveNext();
                                          }
             echo  "</td></tr></select>";
             echo " <tr><td>Provincia:</td><td><select id='prov' onchange='dist();'><option value=''>-Todos-</option></select></td></tr>
                   <tr><td>Distrito:</td><td><select id='dist'><option value=''>-Todos-</option></select></td></tr>*/
              echo "<tr><td class=\"zpFormLabel\">Nº de Plano:</td><td><select id='c_plano'><option value=''>-Seleccionar-</option>";
              $r1 = $db->Execute("SELECT * FROM planos");
              while (!$r1->EOF){
                  echo "<option value='".$r1->fields['idplano']."'>".$r1->fields['plano']."</option>";
                  $r1->MoveNext();
              }
              echo "</select></td></tr>";
              echo "<tr><td class=\"zpFormLabel\">Cuadrante:</td><td><input type='text' id='cuadr'/></td></tr>
                   </table></div>
                    </fieldset>
                    <div id=\"ykBodys\" style=\"color:red;\">
                        </div>
                        <input type=\"button\" onclick=\"insert('cuadr');\" value=\"Agregar\"/>
                    <input type=\"button\" onclick=\"m6('cuadr');\" value=\"Regresar\"/>
                    </center>
                    </div>";
          break;
      }
      

}
?>
