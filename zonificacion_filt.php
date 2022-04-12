<?php
session_start();
if (!isset($_SESSION["user"]) || $_SESSION["user"] == "") {
	header("Location: login.php");
}
require 'define_con.php';
$n = 0;
$tipo=$_GET['tipo'];
switch ($tipo) {
    case "ubig":

    if(isset($_GET['id_dpto'])&& $_GET['id_dpto']!==""){
         $id_dpto=$_GET['id_dpto'];
          $sql="SELECT * FROM ubigeos where coddpto=$id_dpto";
          $sql2="SELECT COUNT(*)FROM ubigeos where coddpto=$id_dpto";
            $RegistrosAMostrar=20;

            if(isset($_GET['pag'])&& $_GET['pag']!==""){
                    $RegistrosAEmpezar=($_GET['pag']-1)*$RegistrosAMostrar;
                    $PagAct=$_GET['pag'];

            }else{
                    $RegistrosAEmpezar=0;
                    $PagAct=1;
            }

            if(isset($_GET['id_prov'])&& $_GET['id_prov']!==""){
                 $id_prov= $_GET['id_prov'];
                 $sql.=" AND codprov=$id_prov";
                 $sql2.=" AND codprov=$id_prov";
                          }
             if(isset($_GET['id_dist'])&& $_GET['id_dist']!==""){
                 $id_dist= $_GET['id_dist'];
                 $sql.=" and coddist=$id_dist";
                 $sql2.=" and coddist=$id_dist";
                          }

             echo "<div id=\"ykBody\">
                    <center>";
             //-----------------------------------Seccion Dpto
                    $r1 = $db->Execute("SELECT * FROM ubigeos  WHERE codprov=00 AND coddist=00");
             echo  "Departamento:<select id='dpto' onchange='dpto();'><option value=''>-Todos-</option>";
                     while (!$r1->EOF) {
                            if($r1->fields['coddpto']==$id_dpto){
                            echo "<option onSelectStart='dpto();'  value='".$r1->fields['coddpto']."' SELECTED> ";
                            echo utf8_encode($r1->fields['nombre'])."</option>";
                            $r1->MoveNext();
                            }
                         echo "<option value='".$r1->fields['coddpto']."'>";
                         echo utf8_encode($r1->fields['nombre'])."</option>";
                         $r1->MoveNext();
                                              }
             echo  "</select>";
             //-----------------------------------Seccion Provincia
              echo " Provincia:<select id='prov' onchange='dist();'><option value=''>-Todos-</option>";
             if(isset($id_dpto)){
                 $r = $db->Execute("SELECT * FROM ubigeos  WHERE coddpto=$id_dpto GROUP BY codprov");
                 $r->MoveNext();
                 while (!$r->EOF){
                     if($r->fields['codprov']==$id_prov){
                            echo "<option   value='".$r->fields['codprov']."' SELECTED> ";
                            echo utf8_encode($r->fields['nombre'])."</option>";
                            $r->MoveNext();
                            }
                         echo "<option value='".$r->fields['codprov']."'>";
                         echo utf8_encode($r->fields['nombre'])."</option>";
                         $r->MoveNext();
                 }
             }
            //-----------------------------------Seccion Provincia
             echo "</select>
                    Distrito:<select id='dist'><option value=''>-Todos-</option>";
             if(isset($id_prov)){
                 $r = $db->Execute("SELECT * FROM ubigeos  WHERE coddpto=$id_dpto and codprov=$id_prov and coddist!=00");
                 //$r->MoveNext();
                 while (!$r->EOF){
                        if($r->fields['coddist']==$id_dist){
                            echo "<option   value='".$r->fields['coddist']."' SELECTED> ";
                            echo utf8_encode($r->fields['nombre'])."</option>";
                            $r->MoveNext();
                            }
                         echo "<option value='".$r->fields['coddist']."'>";
                         echo utf8_encode($r->fields['nombre'])."</option>";
                         $r->MoveNext();
                 }
             }

             echo "</select><input type='button' value='Filtrar' onclick='m7(\"ubig\",1);'>
                 Generar Pdf<img src='imag/pdf-icon.png'/ style='float:inherit;position:relative;top:6px;cursor:pointer;' onclick='pdf();'>
                    Generar Excel<img src='imag/logo_excel.png'/ style='float:inherit;position:relative;top:6px;cursor:pointer;' onclick='excel();'>

                    <fieldset style='width:800px;'><legend>Ubigeo</legend>
                    <div id=\"design1\"><table><th>Nº</th><th>Nombre</th></th><th>Departamento</th><th>Provincia</th><th>Distrito</th>";
             
                 $sql.=" LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
                 $result=$db->Execute($sql);
                
                 
             //$result = $db->Execute("SELECT * FROM ubigeos ORDER BY idubigeo LIMIT $RegistrosAEmpezar, $RegistrosAMostrar");
             while(!$result->EOF){
                    echo "<tr>";
                    echo "<td>".++$n."</td>";
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
            
            $NroRegistros=$db->Execute($sql2);
            $NroRegistros=$NroRegistros->fields['0'];

            $PagAnt=$PagAct-1;
            $PagSig=$PagAct+1;
            $PagUlt=$NroRegistros/$RegistrosAMostrar;

            $Res=$NroRegistros%$RegistrosAMostrar;

            if($Res>0) $PagUlt=floor($PagUlt)+1;

            //desplazamiento
            if($PagAct == 1){
                echo "<a>Primero</a> ";
            }
            else{
               echo "<a onclick=\"m7('ubig',1);\">Primero</a> ";
            }
            if($PagAct>1) echo "<a onclick=\"m7('ubig','$PagAnt')\">Anterior</a> ";
            echo "<strong>Pagina ".$PagAct."/".$PagUlt."</strong>";
            if($PagAct<$PagUlt)  echo " <a onclick=\"m7('ubig','$PagSig')\">Siguiente</a> ";
            if($PagAct == $PagUlt){
                echo "<a>Ultimo</a>
                </fieldset></center></div>";
            }
            else{
               echo "<a onclick=\"m7('ubig','$PagUlt')\">Ultimo</a>
                </fieldset></center></div>";
            }
         
      }else{
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
                    Distrito:<select id='dist'><option>-Todos-</option></select>
                    <input type='button' value='Filtrar' onclick='m7(\"ubig\",1);'>
                    Generar Pdf<img src='imag/pdf-icon.png'/ style='float:inherit;position:relative;top:6px;cursor:pointer;' onclick='pdf();'>
                    Generar Excel<img src='imag/logo_excel.png'/ style='float:inherit;position:relative;top:6px;cursor:pointer;' onclick='excel();'>
           
                    <fieldset style='width:600px;'><legend>Ubigeo</legend>
                    <div id=\"design1\"><table><th>Nº</th><th>Nombre</th></th><th>Departamento</th><th>Provincia</th><th>Distrito</th>";
             $result = $db->Execute("SELECT * FROM ubigeos ORDER BY idubigeo LIMIT $RegistrosAEmpezar, $RegistrosAMostrar");
             while(!$result->EOF){
                    echo "<tr>";
                    echo "<td>".++$n."</td>";
                    echo "<td>".utf8_encode($result->fields['nombre'])."</td>";
                    echo "<td>".$result->fields['coddpto']."</td>";
                    echo "<td>".$result->fields['codprov']."</td>";
                    echo "<td>".$result->fields['coddist']."</td>";
                    echo "</tr>";
                    $result->MoveNext();
            }
            echo "</table></div>
                ";
            //******--------determinar las páginas---------******
            $NroRegistros=$db->Execute("SELECT COUNT(*)FROM ubigeos");
            $NroRegistros=$NroRegistros->fields['0'];

            $PagAnt=$PagAct-1;
            $PagSig=$PagAct+1;
            $PagUlt=$NroRegistros/$RegistrosAMostrar;

            $Res=$NroRegistros%$RegistrosAMostrar;

            if($Res>0) $PagUlt=floor($PagUlt)+1;

            //desplazamiento
            if($PagAct == 1){
                echo "<a>Primero</a> ";
            }
            else{
               echo "<a onclick=\"m7('ubig',1);\">Primero</a> ";
            }
            
            if($PagAct>1) echo "<a onclick=\"m7('ubig','$PagAnt')\">Anterior</a> ";
            echo "<strong>Pagina ".$PagAct."/".$PagUlt."</strong>";
            if($PagAct<$PagUlt)  echo " <a onclick=\"m7('ubig','$PagSig')\">Siguiente</a> ";

            if($PagAct == $PagUlt){
                echo "<a>Ultimo</a>
                </fieldset></center></div>";
            }
            else{
               echo "<a onclick=\"m7('ubig','$PagUlt')\">Ultimo</a>
                </fieldset></center></div>";
            }
      }
      break;
   //------------------------------------------------------------------------------------------------
      case "planos":
          
          if(isset($_GET['id_dpto'])&& $_GET['id_dpto']!==""){
              $id_dpto=$_GET['id_dpto'];
              $sql="SELECT * FROM ubigeos where coddpto=$id_dpto";
              $sql2="SELECT COUNT(*)FROM ubigeos where coddpto=$id_dpto";
              }else{
              $sql="SELECT * FROM planos ";
              $sql2="SELECT COUNT(*)FROM planos ";
              }
                $RegistrosAMostrar=20;
                if(isset($_GET['pag'])&& $_GET['pag']!==""){
                        $RegistrosAEmpezar=($_GET['pag']-1)*$RegistrosAMostrar;
                        $PagAct=$_GET['pag'];
                }else{
                        $RegistrosAEmpezar=0;
                        $PagAct=1;
                }

            if(isset($_GET['id_prov'])&& $_GET['id_prov']!==""){
                 $id_prov= $_GET['id_prov'];
                 $sql.=" AND codprov=$id_prov";
                 $sql2.=" AND codprov=$id_prov";
                          }
                         
             if(isset($_GET['id_dist'])&& $_GET['id_dist']!==""){
                 $id_dist= $_GET['id_dist'];
                 $sql.=" and coddist=$id_dist";
                 $sql2.=" and coddist=$id_dist";
                          }

             echo "<div id=\"ykBody\">
                    <center>";
                    $r1 = $db->Execute("SELECT * FROM ubigeos  WHERE codprov=00 AND coddist=00");
             echo  "Departamento:<select id='dpto' onchange='dpto();'><option value=''>-Todos-</option>";
                     while (!$r1->EOF) {
                         if($r1->fields['coddpto']==$id_dpto){
                             echo "<option  value='".$r1->fields['coddpto']."' onchange=\"dpto();";
                             echo "\" SELECTED>".utf8_encode($r1->fields['nombre'])."</option>";
                             $r1->MoveNext();
                            }
                             echo "<option value='".$r1->fields['coddpto']."' ondclick=\"m6('ubig','','";
                             echo   $r1->fields['coddpto'];
                             echo "');\">".utf8_encode($r1->fields['nombre'])."</option>";
                             $r1->MoveNext();
                                       }
             echo  "</select>";
             echo " Provincia:<select id='prov' onchange='dist();'><option value=''>-Todos-</option>";
                if(isset($id_dpto)){
                     $r = $db->Execute("SELECT * FROM ubigeos  WHERE coddpto=$id_dpto GROUP BY codprov");
                     $r->MoveNext();
                     while (!$r->EOF){
                         if($r->fields['codprov']==$id_prov){
                            echo "<option   value='".$r->fields['codprov']."' SELECTED> ";
                            echo utf8_encode($r->fields['nombre'])."</option>";
                            $r->MoveNext();
                            }
                            echo "<option value='".$r->fields['codprov']."'>";
                            echo utf8_encode($r->fields['nombre'])."</option>";
                            $r->MoveNext();
                     }
                }
             echo "</select>
                   Distrito:<select id='dist'><option value=''>-Todos-</option>";
                   if(isset($id_prov)){
                 $r = $db->Execute("SELECT * FROM ubigeos  WHERE coddpto=$id_dpto and codprov=$id_prov and coddist!=00");
                 //$r->MoveNext();
                 while (!$r->EOF){
                        if($r->fields['coddist']==$id_dist){
                            echo "<option   value='".$r->fields['coddist']."' SELECTED> ";
                            echo utf8_encode($r->fields['nombre'])."</option>";
                            $r->MoveNext();
                            }
                         echo "<option value='".$r->fields['coddist']."'>";
                         echo utf8_encode($r->fields['nombre'])."</option>";
                         $r->MoveNext();
                 }
             }
                     
             echo "</select>
                    <input type='button' value='Filtrar' onclick='m7(\"planos\",1);'>
                    Generar Pdf<img src='imag/pdf-icon.png'/ style='float:inherit;position:relative;top:6px;cursor:pointer;' onclick='pdf();'>
                    Generar Excel<img src='imag/logo_excel.png'/ style='float:inherit;position:relative;top:6px;cursor:pointer;' onclick='excel();'>
                    <fieldset style='width:800px;'><legend>Planos</legend>
                    <div id=\"design1\"><table><th>Nº</th><th>Departamento</th></th><th>Provincia</th><th>Distrito</th><th>Nº de Plano</th>";
                         
                 $sql.=" LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
                 $result=$db->Execute($sql);
             //$result = $db->Execute("SELECT * FROM ubigeos ORDER BY idubigeo LIMIT $RegistrosAEmpezar, $RegistrosAMostrar");
             while(!$result->EOF){
                    echo "<tr>";
                    echo "<td>".++$n."</td>";
                    $idubi=$result->fields['idubigeo'];
                    $query = $db->Execute("SELECT * FROM ubigeos  WHERE idubigeo=$idubi");
                    //echo "<td>".utf8_encode($result->fields['nombre'])."</td>";
                    $iddpto=$query->fields['coddpto'];
                    $q2 = $db->Execute("SELECT * FROM ubigeos  WHERE coddpto=$iddpto and codprov=00 and coddist=00");
                    echo "<td>".utf8_encode($q2->fields['nombre'])."</td>";
                    //-----------------------------------------
                    $idprov=$query->fields['codprov'];
                    $q3 = $db->Execute("SELECT * FROM ubigeos  WHERE coddpto=$iddpto and codprov=$idprov and coddist=00");
                    echo "<td>".utf8_encode($q3->fields['nombre'])."</td>";
                    //-----------------------------------------
                    $iddist=$query->fields['coddist'];
                    $q4 = $db->Execute("SELECT * FROM ubigeos  WHERE coddpto=$iddpto and codprov=$idprov and coddist=$iddist");
                    echo "<td>".utf8_encode($q4->fields['nombre'])."</td>";
                    $q5 = $db->Execute("SELECT * FROM planos  WHERE idubigeo=$idubi");
                    echo "<td>".$q5->fields['plano']."</td>";
                    //echo "<td>".$result->fields['idubigeo']."</td>";
                    echo "</tr>";
                    $result->MoveNext();
            }
            echo "</table></div>
                ";
            //******--------determinar las páginas---------******//

            $NroRegistros=$db->Execute($sql2);
            $NroRegistros=$NroRegistros->fields['0'];

            $PagAnt=$PagAct-1;
            $PagSig=$PagAct+1;
            $PagUlt=$NroRegistros/$RegistrosAMostrar;

            $Res=$NroRegistros%$RegistrosAMostrar;

            if($Res>0) $PagUlt=floor($PagUlt)+1;
            //desplazamiento
            if($PagAct == 1){
                echo "<a>Primero</a> ";
            }
            else{
               echo "<a onclick=\"m7('planos',1);\">Primero</a> ";
            }
            if($PagAct>1) echo "<a onclick=\"m7('planos','$PagAnt')\">Anterior</a> ";
            echo "<strong>Pagina ".$PagAct."/".$PagUlt."</strong>";
            if($PagAct<$PagUlt)  echo " <a onclick=\"m7('planos','$PagSig')\">Siguiente</a> ";
            if($PagAct == $PagUlt){
                echo "<a>Ultimo</a>
                </fieldset><input type=\"button\" onclick=\"m6('i_plano');\" value=\"Agregar Nuevo \"/></center></div>";
            }
            else{
               echo "<a onclick=\"m7('planos','$PagUlt')\">Ultimo</a>
                </fieldset><input type=\"button\" onclick=\"m6('i_plano');\" value=\"Agregar Nuevo \"/>
                </center></div>";
            }
     // }
      break;
}
?>
