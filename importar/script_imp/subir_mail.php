<?php
//return false;
ini_set('memory_limit', '-1');
set_time_limit(1800);
require_once 'Excel/reader.php';
include 'ConnecADO.php';
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
$data->read('Carga_CORREOS.xls');
error_reporting(E_ALL ^ E_NOTICE);
$db->debug=true;
$w=0;

//$db->debug=true;
	//echo "<font color='red'>Inicio de importacion :</font>".$ti=date("H:i:s")."<br/>";
//echo $ti;
	//echo "<pre>";
//$fp = fopen('verif.txt', 'w');
//fwrite($fp, 'importando');
//fclose($fp);

	echo "<pre>";								  		
$d_nr=0;
				$d_nr_x=0;										
foreach($data->sheets as $x => $y){//recorrido de las pestañas del archivo excel
            
           $n_tabla=  strtolower($data->boundsheets[$x]['name']);//definimos nombre de pestaña
 //------------------------------------------------------------------------
	$name= strtolower($data->boundsheets[$x]['name']);
	
	
        echo "Datos Importados : ".$data->boundsheets[$x]['name']."<br/>";//nombre de las pestañas
       // echo "<table border='1'style='font-size:12px;'>";
				
            
               for ($i = 2,$h=1,$c=2; $i <= $data->sheets[$x]['numRows']; $i++,$h++,$c++) {// defino $i=2 para q no tome la primera fila q es la cabecera

                        for ($j = 1; $j <= $data->sheets[$x]['numCols']; $j++) {
								
								if($data->sheets[$x]['cells'][$i][1]==""){
									//break;
								}
								
								if($j==1){
									$sql="INSERT INTO ori_base(`id_ori_campana`,`id_ori_usuario`,`Contacto`,`TelefonoMarcado`,`TipoTelefono`,`Orden`,`pertel`,`cortel`,`cobert`,`bestch`,`prior1`,`prior2`,`prior3`,`prior4`,`prior5`,`activo`)
									VALUES(";
									$sql.="'".$data->sheets[$x]['cells'][$i][$j]."',";
									continue;
								}

								if($j==2){
									$sql.="'".$data->sheets[$x]['cells'][$i][$j]."',";
									continue;
								}
								
								if($j==3){
									$sql.="'".$data->sheets[$x]['cells'][$i][$j]."',";
									continue;
								}
								
								if($j==4){
									$sql.="'".$data->sheets[$x]['cells'][$i][$j]."',";
									continue;
								}
								if($j==5){
									$sql.="'".$data->sheets[$x]['cells'][$i][$j]."',";
									continue;
								}
								if($j==6){
									$sql.="'".$data->sheets[$x]['cells'][$i][$j]."',";
									continue;
								}
								if($j==7){
									$sql.="'".$data->sheets[$x]['cells'][$i][$j]."',";
									continue;
								}
								
								if($j==8){
									$sql.="'".$data->sheets[$x]['cells'][$i][$j]."',";
									continue;
								}
								
								if($j==9){
									$sql.="'".$data->sheets[$x]['cells'][$i][$j]."',";
									continue;
								}
								
								if($j==10){
									$sql.="'".$data->sheets[$x]['cells'][$i][$j]."',";
									continue;
								}
								
								if($j==11){
									$sql.="'".$data->sheets[$x]['cells'][$i][$j]."',";
									continue;
								}
								
								if($j==12){
									$sql.="'".$data->sheets[$x]['cells'][$i][$j]."',";
									continue;
								}
								
								if($j==13){
									$sql.="'".$data->sheets[$x]['cells'][$i][$j]."',";
									continue;
								}
								
								if($j==14){
									$sql.="'".$data->sheets[$x]['cells'][$i][$j]."',";
									continue;
								}
								
								if($j==15){
									$sql.="'".$data->sheets[$x]['cells'][$i][$j]."',";
									continue;
								}
								
								if($j==16){
									$sql.="'".$data->sheets[$x]['cells'][$i][$j]."')";
									echo $sql."<br/>";
									//$db->Execute($sql);
									continue;
								}

						}
						
		
		

						
                    //echo "</tr>";
				}
        //echo "</table>";

	$w++;
}
	echo $d_nr."<br>";
		echo $d_nr_x;
$db->Close();
?>
