<?php
echo date("H:m:s");
ini_set('memory_limit', '-1');
set_time_limit(1800);
require_once 'Excel/reader.php';
include 'ConnecADO.php';
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
$data->read('Ubigeo2008.xls');
error_reporting(E_ALL ^ E_NOTICE);
$sql= array ();
$w=0;
$db->StartTrans();

foreach($data->sheets as $x => $y){//recorrido de las pestañas del archivo excel
            //for($q=0;$q<=$data->sheets[$q];$q++){
           $n_tabla= $data->boundsheets[$x]['name'];//definimos nombre de pestaña
           //$db->Execute("Select * from $n_tabla");//verificamos si la pestaña concuerda con la tabla
           //if(!$db){
             // return false;//si no concuerda almenos 1 se cancela toda la ejecucion
           //}
       //}
//------------------------------------------------------------------------
       echo $data->boundsheets[$x]['name'];//nombre de las pestañas
       echo "<table border='1'style='font-size:12px;'>";
//------------------------------------------------------------------------
           for($z=1;$z<=$data->sheets[$x]['numCols'];$z++){
                    echo "<th>".$data->sheets[$x]['cells'][1][$z]."</th>";//Nombre De Cabecera
                        $nameT=$data->sheets[$x]['cells'][1][$z];
                    $sql[$n_tabla][$z]['name']=$nameT;//almaceno nombres de titulos por pestaña(tabla)
                }
//--------------------------------------------------------------------------
            $query2="Select *";
                $n_datos= count($sql[$n_tabla]);
                    for($s=1;$s<=$n_datos;$s++){
                        if($s==$n_datos){
                            //$query2.=$sql[$n_tabla][$s]['name']." ";
                        continue;
                        }
                        //$query2.=$sql[$n_tabla][$s]['name'].",";
                    }
            $query2.=" from `$n_tabla`";
           // $db->Execute($query2);//consultamos si son iguales los nombres de las columnas de la pestaña con la tabla
            //if(!$db){return false;} // si no concuerdan o no existe se cancela la ejecucion
//--------------------------------------------------------------------------
            $query3="INSERT INTO `$n_tabla`(";
                for($g=1;$g<=$n_datos;$g++){
                        if($g==$n_datos){$query3.="`".$sql[$n_tabla][$g]['name']."`) ";
                        continue;
                        }
                        $query3.="`".$sql[$n_tabla][$g]['name']."`,";
                }
            $nuevo= array();
            $insert=array();
//--------------------------------------------------------------------------------
            $query3.="VALUES ";
            $q_verif=$query3;
               for ($i = 2,$h=1; $i <= $data->sheets[$x]['numRows']; $i++,$h++) {// defino $i=2 para q no tome la primera fila q es la cabecera
                   echo"<tr>";
                        if($i <= $data->sheets[$x]['numRows']){
                        $query3.="( ";
                        $r="";//*----------
                        $sql_i="(";
                        }
                        
                        for ($j = 1; $j <= $data->sheets[$x]['numCols']; $j++) {
                               if($j == $data->sheets[$x]['numCols']){
                               $query3.="'".$data->sheets[$x]['cells'][$i][$j]."')";
                               $sql_i.="'".$data->sheets[$x]['cells'][$i][$j]."')";
                               
                               $insert['insert'][$h]=$sql_i;
                              // $r.="'".$data->sheets[$x]['cells'][$i][$j]."')";//*----------
                               $nuevo['sql'][$h]= $query2."  ".$r;////se guarda los datos en array
                               //$nuevo['sql']= $r;
                                   if($i < $data->sheets[$x]['numRows']){
                                    $query3.=",";
                                    //$r.=",";
                                   }
                                   echo "<pre>";
                                   //echo $query3;
                               continue;
                               }
                         echo "<td>".$data->sheets[$x]['cells'][$i][$j]."</td>";
                        $query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
                        $sql_i.="'".$data->sheets[$x]['cells'][$i][$j]."',";
                        $r.="'".$data->sheets[$x]['cells'][$i][$j]."',";//*----------
                        if($j==1){
                         $r.="where ".$sql[$n_tabla][$j]['name']."='".$data->sheets[$x]['cells'][$i][$j]."'";
						 }//*----------
                          //$r.=" and ".$sql[$n_tabla][2]['name']."='".$data->sheets[$x]['cells'][$i][2]."'";}
                        //$r.="where iddatos=".$data->sheets[$x]['cells'][$i][$j].",";//*----------
                        
                        }
                    echo "</tr>";
               }
        echo "</table>";
       //echo $query3;
       //$ni= $q_verif." ".$insert['insert'][$m];
                    //echo $ni;
        //$db->Execute($query3) ;
        echo "<pre>";
       $num=count($nuevo['sql']);
       $num2=count($insert['insert']);
       
       for($m=1;$m<=$num2;$m++){
           //echo $nuevo['sql'][$m]." - ";
           //echo $nuevo['insert'][$m]."  "."<br>";
		$sl=$nuevo['insert'][$m];
		$db->Execute($sl);
            //$qqq =$nuevo['sql'][$m];
            //$result = $db->Execute($qqq);
           //$ni= $q_verif." ".$insert['insert'][$m];
            //$db->Execute($ni);
                    //echo $ni;
                if($result->fields[0]==""){
                    $ni= $q_verif." ".$insert['insert'][$m];
                   // echo $ni;
                   // $db->Execute($ni);
                   //echo "Insertar"."<br>";
                   // echo "Este Registro no Existe"."<br>";
                }else{
                    echo "Update"."<br>";
                                    }
        }
        // var_dump($insert);
        $w++;
		//return false;
}
//$db->CompleteTrans();
//$db->Close();
echo date("H:m:s");
?>
